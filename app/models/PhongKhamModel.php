<?php
/**
 * Model kết nối SQL Server - SHPT_PhongKham
 * Đọc danh mục nguồn khách hàng từ bảng DKKhamDoan
 */
class PhongKhamModel {
    private $sqlsrv;

    public function __construct($sqlsrv) {
        if (!$sqlsrv) {
            throw new Exception('SQL Server chưa được cấu hình');
        }
        $this->sqlsrv = $sqlsrv;
    }

    /**
     * Lấy TẤT CẢ nguồn khách từ DKKhamDoan
     */
    public function getAllSources() {
        $st = $this->sqlsrv->query("
            SELECT 
                ID,
                MaCty,
                Ten,
                Tungay,
                Denngay,
                Ngaylap,
                Phanloai,
                Trangthai
            FROM DKKhamDoan
            ORDER BY Ngaylap DESC, ID DESC
        ");
        return $st->fetchAll();
    }

    /**
     * Chỉ lấy nguồn đang hoạt động (Trangthai = 'Đang hoạt động' hoặc tương tự)
     */
    public function getActiveSources() {
        $st = $this->sqlsrv->query("
            SELECT ID, MaCty, Ten, Tungay, Denngay, Ngaylap, Phanloai, Trangthai
            FROM DKKhamDoan
            WHERE Denngay IS NULL OR Denngay >= GETDATE()
            ORDER BY Ngaylap DESC, ID DESC
        ");
        return $st->fetchAll();
    }

    /**
     * Đếm tổng số nguồn
     */
    public function countSources() {
        return (int)$this->sqlsrv->query("SELECT COUNT(*) FROM DKKhamDoan")->fetchColumn();
    }

    /**
     * Đếm số nguồn theo phân loại
     */
    public function countByCategory() {
        $st = $this->sqlsrv->query("
            SELECT Phanloai, COUNT(*) as total
            FROM DKKhamDoan
            WHERE Phanloai IS NOT NULL
            GROUP BY Phanloai
            ORDER BY total DESC
        ");
        return $st->fetchAll();
    }

    /**
     * Đếm nguồn đang hoạt động
     */
    public function countActive() {
        return (int)$this->sqlsrv->query("
            SELECT COUNT(*) FROM DKKhamDoan
            WHERE Denngay IS NULL OR Denngay >= GETDATE()
        ")->fetchColumn();
    }

    /**
     * Lấy 1 nguồn theo ID
     */
    public function getSourceById($id) {
        $st = $this->sqlsrv->prepare("SELECT * FROM DKKhamDoan WHERE ID = ?");
        $st->execute([$id]);
        return $st->fetch();
    }

    /**
     * Tìm nguồn theo tên hoặc mã
     */
    public function search($keyword, $limit = 100) {
        $st = $this->sqlsrv->prepare("
            SELECT TOP " . (int)$limit . " ID, MaCty, Ten, Tungay, Denngay, Ngaylap, Phanloai, Trangthai
            FROM DKKhamDoan 
            WHERE Ten LIKE ? OR MaCty LIKE ? OR Phanloai LIKE ?
            ORDER BY Ngaylap DESC
        ");
        $kw = "%$keyword%";
        $st->execute([$kw, $kw, $kw]);
        return $st->fetchAll();
    }
        /**
     * Lấy danh sách nguồn có PHÂN TRANG (dùng OFFSET/FETCH của SQL Server)
     */
    public function getSourcesPaged($page = 1, $perPage = 50, $keyword = '') {
        $offset = ($page - 1) * $perPage;
        
        if ($keyword) {
            $sql = "
                SELECT ID, MaCty, Ten, Tungay, Denngay, Ngaylap, Phanloai, Trangthai
                FROM DKKhamDoan
                WHERE Ten LIKE ? OR MaCty LIKE ? OR Phanloai LIKE ?
                ORDER BY Ngaylap DESC, ID DESC
                OFFSET ? ROWS
                FETCH NEXT ? ROWS ONLY
            ";
            $kw = "%$keyword%";
            $st = $this->sqlsrv->prepare($sql);
            $st->bindValue(1, $kw);
            $st->bindValue(2, $kw);
            $st->bindValue(3, $kw);
            $st->bindValue(4, $offset, PDO::PARAM_INT);
            $st->bindValue(5, $perPage, PDO::PARAM_INT);
        } else {
            $sql = "
                SELECT ID, MaCty, Ten, Tungay, Denngay, Ngaylap, Phanloai, Trangthai
                FROM DKKhamDoan
                ORDER BY Ngaylap DESC, ID DESC
                OFFSET ? ROWS
                FETCH NEXT ? ROWS ONLY
            ";
            $st = $this->sqlsrv->prepare($sql);
            $st->bindValue(1, $offset, PDO::PARAM_INT);
            $st->bindValue(2, $perPage, PDO::PARAM_INT);
        }
        
        $st->execute();
        return $st->fetchAll();
    }

    /**
     * Đếm tổng số nguồn (có filter keyword)
     */
    public function countSourcesFiltered($keyword = '') {
        if ($keyword) {
            $st = $this->sqlsrv->prepare("
                SELECT COUNT(*) FROM DKKhamDoan
                WHERE Ten LIKE ? OR MaCty LIKE ? OR Phanloai LIKE ?
            ");
            $kw = "%$keyword%";
            $st->execute([$kw, $kw, $kw]);
        } else {
            $st = $this->sqlsrv->query("SELECT COUNT(*) FROM DKKhamDoan");
        }
        return (int)$st->fetchColumn();
    }

    /**
     * Lấy NHIỀU nguồn theo mảng ID (dùng cho syncAll có phân trang)
     */
    public function getSourcesByIds($ids) {
        if (empty($ids)) return [];
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $st = $this->sqlsrv->prepare("
            SELECT ID, MaCty, Ten, Tungay, Denngay, Ngaylap, Phanloai, Trangthai
            FROM DKKhamDoan
            WHERE ID IN ($placeholders)
        ");
        $st->execute($ids);
        return $st->fetchAll();
    }
}
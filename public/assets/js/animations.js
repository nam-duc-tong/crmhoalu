// === Reusable Animation Utilities ===

window.AnimKit = {
  
  // Reveal on scroll
  reveal(selector, options = {}) {
    gsap.utils.toArray(selector).forEach(el => {
      gsap.from(el, {
        opacity: 0,
        y: options.y || 40,
        duration: options.duration || 0.8,
        ease: 'power3.out',
        scrollTrigger: { trigger: el, start: 'top 85%' },
        delay: options.delay || 0
      });
    });
  },

  // Count up number
  countUp(el, target, options = {}) {
    const obj = { val: 0 };
    gsap.to(obj, {
      val: target,
      duration: options.duration || 2,
      ease: 'power2.out',
      onUpdate: () => {
        el.textContent = (options.prefix || '') + 
                        Math.floor(obj.val).toLocaleString('vi-VN') + 
                        (options.suffix || '');
      }
    });
  },

  // Confetti burst
  confetti(x, y) {
    const colors = ['#06b6d4','#8b5cf6','#ec4899','#f59e0b','#10b981'];
    for (let i = 0; i < 30; i++) {
      const p = document.createElement('div');
      p.style.cssText = `
        position:fixed;left:${x}px;top:${y}px;
        width:8px;height:8px;
        background:${colors[Math.floor(Math.random()*colors.length)]};
        border-radius:50%;pointer-events:none;z-index:9999;
      `;
      document.body.appendChild(p);
      gsap.to(p, {
        x: (Math.random() - .5) * 500,
        y: (Math.random() - .5) * 500 - 100,
        rotation: Math.random() * 720,
        opacity: 0,
        duration: 1.5 + Math.random(),
        ease: 'power2.out',
        onComplete: () => p.remove()
      });
    }
  },

  // Shake element
  shake(el) {
    gsap.fromTo(el,
      { x: -10 },
      { x: 10, duration: 0.1, repeat: 5, yoyo: true, ease: 'power1.inOut',
        onComplete: () => gsap.set(el, { x: 0 }) }
    );
  },

  // Pulse glow
  pulseGlow(el) {
    gsap.to(el, {
      boxShadow: '0 0 30px rgba(6,182,212,.8), 0 0 60px rgba(139,92,246,.5)',
      duration: 1,
      repeat: -1,
      yoyo: true,
      ease: 'power1.inOut'
    });
  }
};
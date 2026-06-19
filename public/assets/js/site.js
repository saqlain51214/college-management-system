(function () {
  function setHamburgerOpen(hb1, hb2, hb3, open) {
    if (!hb1 || !hb2 || !hb3) return;
    if (open) {
      hb1.classList.add('translate-y-[6px]', 'rotate-45');
      hb2.classList.add('opacity-0');
      hb3.classList.add('-translate-y-[6px]', '-rotate-45');
    } else {
      hb1.classList.remove('translate-y-[6px]', 'rotate-45');
      hb2.classList.remove('opacity-0');
      hb3.classList.remove('-translate-y-[6px]', '-rotate-45');
    }
  }

  function initMobileNav() {
    var menuToggle = document.getElementById('menuToggle');
    var mobileMenu = document.getElementById('mobileMenu');
    var hb1 = document.getElementById('hb1');
    var hb2 = document.getElementById('hb2');
    var hb3 = document.getElementById('hb3');
    if (!menuToggle || !mobileMenu || !hb1 || !hb2 || !hb3) return;

    menuToggle.addEventListener('click', function () {
      mobileMenu.classList.toggle('hidden');
      var open = !mobileMenu.classList.contains('hidden');
      if (!open) {
        mobileMenu.querySelectorAll('details').forEach(function (d) {
          d.removeAttribute('open');
        });
      }
      setHamburgerOpen(hb1, hb2, hb3, open);
      menuToggle.setAttribute('aria-expanded', open);
      menuToggle.setAttribute(
        'aria-label',
        open ? 'Close navigation menu' : 'Open navigation menu'
      );
    });

    mobileMenu.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', function () {
        mobileMenu.classList.add('hidden');
        mobileMenu.querySelectorAll('details').forEach(function (d) {
          d.removeAttribute('open');
        });
        setHamburgerOpen(hb1, hb2, hb3, false);
        menuToggle.setAttribute('aria-expanded', 'false');
        menuToggle.setAttribute('aria-label', 'Open navigation menu');
      });
    });
  }

  function initPreloader() {
    var el = document.getElementById('site-preloader');
    if (!el) return;
    var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var done = false;
    var fallback = setTimeout(hide, 10000);
    function hide() {
      if (done) return;
      done = true;
      clearTimeout(fallback);
      el.setAttribute('aria-busy', 'false');
      el.classList.add('opacity-0', 'pointer-events-none');
      var t = reduce ? 120 : 500;
      setTimeout(function () {
        if (el.parentNode) el.remove();
      }, t);
    }
    if (reduce) {
      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', hide);
      } else {
        hide();
      }
    } else if (document.readyState === 'complete') {
      setTimeout(hide, 200);
    } else {
      window.addEventListener('load', function () {
        setTimeout(hide, 200);
      });
    }
  }

  function boot() {
    initMobileNav();
    initPreloader();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }
})();

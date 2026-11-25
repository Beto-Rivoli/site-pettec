(function () {
  'use strict';

  var prefersReduced =
    window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* 1. Sticky Header Scroll State */
  var header = document.querySelector('.site-header');
  function updateHeader() {
    if (!header) return;
    header.classList.toggle('is-scrolled', window.scrollY > 36);
  }
  updateHeader();

  /* 2. Lightweight rAF Parallax + Rotating Scroll Badge */
  var parallaxNodes = Array.prototype.slice.call(document.querySelectorAll('[data-parallax-speed]'));
  var rotateNodes = Array.prototype.slice.call(document.querySelectorAll('[data-scroll-rotate]'));
  var ticking = false;

  function renderScrollMotion() {
    ticking = false;
    updateHeader();

    if (prefersReduced || window.innerWidth < 768) {
      return;
    }

    var scrollY = window.scrollY;
    var vh = window.innerHeight || 800;

    for (var i = 0; i < parallaxNodes.length; i++) {
      var el = parallaxNodes[i];
      var speed = parseFloat(el.getAttribute('data-parallax-speed') || '0.12');
      var rect = el.getBoundingClientRect();
      var offset = ((rect.top + rect.height / 2) - vh / 2) * speed;
      var clamped = Math.max(-48, Math.min(48, offset));
      el.style.transform = 'translate3d(0, ' + clamped.toFixed(1) + 'px, 0)';
    }

    for (var j = 0; j < rotateNodes.length; j++) {
      var rEl = rotateNodes[j];
      var factor = parseFloat(rEl.getAttribute('data-scroll-rotate') || '0.18');
      rEl.style.transform = 'rotate(' + (scrollY * factor).toFixed(1) + 'deg)';
    }
  }

  window.addEventListener(
    'scroll',
    function () {
      if (!ticking) {
        ticking = true;
        window.requestAnimationFrame(renderScrollMotion);
      }
    },
    { passive: true }
  );

  /* 3. Scroll Reveal via IntersectionObserver */
  var revealNodes = Array.prototype.slice.call(document.querySelectorAll('.reveal'));
  if (!prefersReduced && 'IntersectionObserver' in window && revealNodes.length) {
    var io = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            io.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.12 }
    );
    revealNodes.forEach(function (node) {
      io.observe(node);
    });
  } else {
    revealNodes.forEach(function (node) {
      node.classList.add('is-visible');
    });
  }

  /* 4. Accessible Mobile Drawer */
  var toggleBtn = document.querySelector('.menu-toggle');
  var drawer = document.querySelector('.mobile-drawer');
  var closeBtn = document.querySelector('.drawer-close');

  function setDrawerOpen(open) {
    if (!drawer || !toggleBtn) return;
    drawer.classList.toggle('is-open', open);
    drawer.setAttribute('aria-hidden', open ? 'false' : 'true');
    toggleBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
    document.body.style.overflow = open ? 'hidden' : '';
  }

  if (toggleBtn && drawer) {
    toggleBtn.addEventListener('click', function () {
      var isOpen = drawer.classList.contains('is-open');
      setDrawerOpen(!isOpen);
    });
    if (closeBtn) {
      closeBtn.addEventListener('click', function () {
        setDrawerOpen(false);
      });
    }
    drawer.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', function () {
        setDrawerOpen(false);
      });
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && drawer.classList.contains('is-open')) {
        setDrawerOpen(false);
      }
    });
  }

  /* 5. Declarative Client-Side Archive Filtering (data-filter-group) */
  var filterGroups = document.querySelectorAll('[data-filter-group]');
  filterGroups.forEach(function (group) {
    var activeFilters = {};
    var items = group.querySelectorAll('[data-filter-item]');
    var emptyEl = group.querySelector('[data-filter-empty]');

    group.querySelectorAll('[data-filter-key]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var key = btn.getAttribute('data-filter-key');
        var val = btn.getAttribute('data-filter-value');
        activeFilters[key] = val;

        group.querySelectorAll('[data-filter-key="' + key + '"]').forEach(function (sibling) {
          sibling.classList.toggle('is-active', sibling === btn);
        });

        var visibleCount = 0;
        items.forEach(function (item) {
          var matches = true;
          Object.keys(activeFilters).forEach(function (k) {
            var expected = activeFilters[k];
            if (!expected || expected === 'all') return;
            var actual = (item.getAttribute('data-' + k) || '').split(',');
            if (actual.indexOf(expected) === -1) {
              matches = false;
            }
          });
          item.classList.toggle('is-hidden', !matches);
          if (matches) visibleCount++;
        });

        if (emptyEl) {
          emptyEl.classList.toggle('is-hidden', visibleCount > 0);
        }
      });
    });
  });
})();

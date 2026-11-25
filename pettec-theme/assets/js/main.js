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

/* ==========================================================================
   6. Extrafazant-style Page Transition Curtain + Two26 Deck + Onlines Kinetic
   ========================================================================== */
(function () {
  'use strict';

  var prefersReduced =
    window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* 6A. Full-Screen Page Transition Curtain */
  var curtain = document.getElementById('pettec-page-transition');
  if (curtain) {
    if (prefersReduced) {
      curtain.classList.remove('is-entering', 'is-leaving');
      curtain.classList.add('is-entered');
    } else {
      window.requestAnimationFrame(function () {
        window.requestAnimationFrame(function () {
          curtain.classList.remove('is-entering');
          curtain.classList.add('is-entered');
        });
      });

      window.addEventListener('pageshow', function (evt) {
        if (evt.persisted) {
          curtain.classList.remove('is-leaving', 'is-entering');
          curtain.classList.add('is-entered');
        }
      });

      document.addEventListener('click', function (e) {
        var link = e.target && e.target.closest ? e.target.closest('a[href]') : null;
        if (!link) return;
        if (e.defaultPrevented || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey || e.button !== 0) return;
        if (link.target === '_blank' || link.hasAttribute('download')) return;

        var href = link.getAttribute('href') || '';
        if (!href || href.charAt(0) === '#' || href.indexOf('mailto:') === 0 || href.indexOf('tel:') === 0) return;

        var url;
        try {
          url = new URL(link.href, window.location.href);
        } catch (_) {
          return;
        }
        if (url.origin !== window.location.origin) return;
        if (url.pathname.indexOf('/wp-admin') === 0 || url.pathname.indexOf('/wp-login') === 0) return;
        if (url.pathname === window.location.pathname && url.search === window.location.search) return;

        e.preventDefault();
        curtain.classList.remove('is-entered', 'is-entering');
        curtain.classList.add('is-leaving');
        window.setTimeout(function () {
          window.location.href = url.href;
        }, 380);
      });
    }
  }

  /* 6B. Two26 Clip-Path Reveal Observer */
  var clipNodes = Array.prototype.slice.call(document.querySelectorAll('.work-card-clip'));
  if (!prefersReduced && 'IntersectionObserver' in window && clipNodes.length) {
    var clipIO = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            clipIO.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.14 }
    );
    clipNodes.forEach(function (n) {
      clipIO.observe(n);
    });
  } else {
    clipNodes.forEach(function (n) {
      n.classList.add('is-visible');
    });
  }

  /* 6C. Two26 Stacked Testimonials Deck */
  var decks = document.querySelectorAll('[data-testimonial-deck]');
  decks.forEach(function (deck) {
    var cards = Array.prototype.slice.call(deck.querySelectorAll('.deck-card'));
    var ticks = Array.prototype.slice.call(deck.querySelectorAll('.deck-tick'));
    if (!cards.length) return;

    var activeIdx = 0;
    var isAnimating = false;

    function applyPositions() {
      var total = cards.length;
      for (var i = 0; i < total; i++) {
        var rel = (i - activeIdx + total) % total;
        cards[i].setAttribute('data-pos', rel <= 3 ? String(rel) : 'back');
      }
      for (var t = 0; t < ticks.length; t++) {
        ticks[t].classList.toggle('is-active', t === activeIdx);
      }
    }

    function advance(nextIndex) {
      if (isAnimating || cards.length < 2) return;
      isAnimating = true;
      var currentCard = cards[activeIdx];
      currentCard.classList.add('is-throwing');

      window.setTimeout(function () {
        currentCard.classList.remove('is-throwing');
        activeIdx = typeof nextIndex === 'number' ? nextIndex : (activeIdx + 1) % cards.length;
        applyPositions();
        isAnimating = false;
      }, prefersReduced ? 0 : 280);
    }

    applyPositions();

    deck.addEventListener('click', function (e) {
      var tickBtn = e.target && e.target.closest ? e.target.closest('.deck-tick') : null;
      if (tickBtn) {
        var targetIdx = parseInt(tickBtn.getAttribute('data-tick-index') || '0', 10);
        if (targetIdx !== activeIdx) advance(targetIdx);
        return;
      }
      advance();
    });

    deck.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.key === ' ' || e.key === 'ArrowRight') {
        e.preventDefault();
        advance();
      }
    });
  });

  /* 6D. Onlines-inspired Interactive Cell Matrix */
  var matrices = document.querySelectorAll('.cell-matrix');
  matrices.forEach(function (matrix) {
    var count = 72;
    var cells = [];
    for (var i = 0; i < count; i++) {
      var cell = document.createElement('span');
      cell.className = 'cell-matrix__cell';
      matrix.appendChild(cell);
      cells.push(cell);
    }
    if (!prefersReduced) {
      window.setInterval(function () {
        var pick = cells[Math.floor(Math.random() * cells.length)];
        if (!pick) return;
        pick.classList.add('is-lit');
        window.setTimeout(function () {
          pick.classList.remove('is-lit');
        }, 1200);
      }, 650);
    }
  });

  /* 6E. Kinetic 3D Magnetic Tilt & Cursor Spotlight on Member Cards */
  if (!prefersReduced) {
    var tiltCards = document.querySelectorAll('[data-tilt-card]');
    tiltCards.forEach(function (card) {
      card.addEventListener('pointermove', function (e) {
        var rect = card.getBoundingClientRect();
        var px = (e.clientX - rect.left) / rect.width;
        var py = (e.clientY - rect.top) / rect.height;
        var rx = (0.5 - py) * 12;
        var ry = (px - 0.5) * 12;
        card.style.setProperty('--mx', (px * 100).toFixed(1) + '%');
        card.style.setProperty('--my', (py * 100).toFixed(1) + '%');
        card.style.transform =
          'perspective(800px) rotateX(' + rx.toFixed(1) + 'deg) rotateY(' + ry.toFixed(1) + 'deg) translateY(-6px)';
      });
      card.addEventListener('pointerleave', function () {
        card.style.transform = '';
      });
    });
  }
})();

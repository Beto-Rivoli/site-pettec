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

    if (prefersReduced) {
      return;
    }

    var scrollY = window.scrollY;
    var vh = window.innerHeight || 800;
    var mobileScale = window.innerWidth < 768 ? 0.55 : 1;

    for (var i = 0; i < parallaxNodes.length; i++) {
      var el = parallaxNodes[i];
      var speed = parseFloat(el.getAttribute('data-parallax-speed') || '0.12');
      var maxShift = parseFloat(el.getAttribute('data-parallax-max') || '48') * mobileScale;
      var rect = el.getBoundingClientRect();
      var offset = ((rect.top + rect.height / 2) - vh / 2) * speed * mobileScale;
      var clamped = Math.max(-maxShift, Math.min(maxShift, offset));
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

/* ==========================================================================
   7. Force-Field Repulsion + Ambient Drift + Scroll Parallax for Courses
   ========================================================================== */
(function () {
  'use strict';

  var prefersReduced =
    window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (prefersReduced) return;

  var stage = document.querySelector('[data-force-stage]');
  if (!stage) return;

  var words = Array.prototype.slice.call(stage.querySelectorAll('[data-force-word]'));
  var styleCycle = ['sans', 'serif', 'outline', 'sans', 'red-outline', 'mono', 'serif', 'sans'];
  var chars = [];

  words.forEach(function (wordEl, wIdx) {
    var text = (wordEl.getAttribute('data-force-word') || wordEl.textContent || '').trim();
    wordEl.setAttribute('aria-label', text);
    wordEl.textContent = '';

    for (var i = 0; i < text.length; i++) {
      var ch = text.charAt(i);
      var span = document.createElement('span');
      var styleName = styleCycle[(i + wIdx * 3) % styleCycle.length];
      span.className = 'force-char force-char--' + styleName;
      span.setAttribute('aria-hidden', 'true');
      span.textContent = ch === ' ' ? '\u00A0' : ch;
      wordEl.appendChild(span);

      chars.push({
        el: span,
        wordIdx: wIdx,
        phase: (i * 0.65) + (wIdx * 1.9),
        amp: 3.5 + (i % 3) * 1.4,
        x: 0,
        y: 0,
        rot: 0,
        vx: 0,
        vy: 0,
        vrot: 0
      });
    }
  });

  var pointer = { active: false, x: -9999, y: -9999 };
  var radius = 165;
  var strength = 68;
  var spring = 0.085;
  var damping = 0.78;
  var isVisible = false;

  stage.addEventListener('pointermove', function (e) {
    pointer.active = true;
    pointer.x = e.clientX;
    pointer.y = e.clientY;
  });

  stage.addEventListener('pointerleave', function () {
    pointer.active = false;
  });

  if ('IntersectionObserver' in window) {
    var stageIO = new IntersectionObserver(
      function (entries) {
        isVisible = entries[0] && entries[0].isIntersecting;
      },
      { threshold: 0.02 }
    );
    stageIO.observe(stage);
  } else {
    isVisible = true;
  }

  function tick(now) {
    if (isVisible) {
      var t = now * 0.0014;
      var stageRect = stage.getBoundingClientRect();
      var vh = window.innerHeight || 800;
      var scrollProgress = ((stageRect.top + stageRect.height / 2) - vh / 2) / vh;

      words.forEach(function (wEl, idx) {
        var dir = idx % 2 === 0 ? -1 : 1;
        var shiftX = Math.max(-36, Math.min(36, scrollProgress * 44 * dir));
        wEl.style.transform = 'translate3d(' + shiftX.toFixed(1) + 'px, 0, 0)';
      });

      for (var i = 0; i < chars.length; i++) {
        var c = chars[i];
        var targetX = Math.cos(t * 0.8 + c.phase) * (c.amp * 0.55);
        var targetY = Math.sin(t + c.phase) * c.amp;
        var targetRot = Math.sin(t * 0.7 + c.phase) * 2.2;

        if (pointer.active) {
          var rect = c.el.getBoundingClientRect();
          var cx = rect.left + rect.width / 2 - c.x;
          var cy = rect.top + rect.height / 2 - c.y;
          var dx = cx - pointer.x;
          var dy = cy - pointer.y;
          var dist = Math.sqrt(dx * dx + dy * dy);

          if (dist < radius && dist > 0.01) {
            var force = Math.pow(1 - dist / radius, 1.6);
            var nx = dx / dist;
            var ny = dy / dist;
            targetX += nx * force * strength;
            targetY += ny * force * strength;
            targetRot += (nx * 24 - ny * 10) * force;
          }
        }

        c.vx = (c.vx + (targetX - c.x) * spring) * damping;
        c.vy = (c.vy + (targetY - c.y) * spring) * damping;
        c.vrot = (c.vrot + (targetRot - c.rot) * spring) * damping;

        c.x += c.vx;
        c.y += c.vy;
        c.rot += c.vrot;

        c.el.style.transform =
          'translate3d(' + c.x.toFixed(1) + 'px, ' + c.y.toFixed(1) + 'px, 0) rotate(' + c.rot.toFixed(1) + 'deg)';
      }
    }
    window.requestAnimationFrame(tick);
  }

  window.requestAnimationFrame(tick);
})();

/* ==========================================================================
   8. Ambient Wave Motion for Hero Headline ([data-float-headline])
   ========================================================================== */
(function () {
  'use strict';

  var prefersReduced =
    window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (prefersReduced) return;

  var headlines = document.querySelectorAll('[data-float-headline]');
  if (!headlines.length) return;

  var floatChars = [];

  function splitElementText(container) {
    var nodes = Array.prototype.slice.call(container.childNodes);
    nodes.forEach(function (node) {
      if (node.nodeType === 3) {
        var raw = node.nodeValue || '';
        if (!raw.trim()) return;
        var frag = document.createDocumentFragment();
        var tokens = raw.split(/(\s+)/);
        tokens.forEach(function (tok) {
          if (!tok) return;
          if (/^\s+$/.test(tok)) {
            frag.appendChild(document.createTextNode(' '));
            return;
          }
          var wordSpan = document.createElement('span');
          wordSpan.className = 'float-word';
          for (var i = 0; i < tok.length; i++) {
            var chSpan = document.createElement('span');
            chSpan.className = 'float-char';
            chSpan.setAttribute('aria-hidden', 'true');
            chSpan.textContent = tok.charAt(i);
            wordSpan.appendChild(chSpan);
            floatChars.push({
              el: chSpan,
              phase: floatChars.length * 0.55,
              amp: 2.6 + (floatChars.length % 3) * 1.1
            });
          }
          frag.appendChild(wordSpan);
        });
        container.replaceChild(frag, node);
      } else if (node.nodeType === 1) {
        splitElementText(node);
      }
    });
  }

  headlines.forEach(function (h) {
    h.setAttribute('aria-label', (h.textContent || '').replace(/\s+/g, ' ').trim());
    splitElementText(h);
  });

  if (!floatChars.length) return;

  var heroVisible = true;
  if ('IntersectionObserver' in window) {
    var heroIO = new IntersectionObserver(
      function (entries) {
        heroVisible = entries[0] && entries[0].isIntersecting;
      },
      { threshold: 0.02 }
    );
    heroIO.observe(headlines[0]);
  }

  function animateHero(now) {
    if (heroVisible) {
      var t = now * 0.0014;
      for (var i = 0; i < floatChars.length; i++) {
        var c = floatChars[i];
        var x = Math.cos(t * 0.8 + c.phase) * (c.amp * 0.45);
        var y = Math.sin(t + c.phase) * c.amp;
        var rot = Math.sin(t * 0.7 + c.phase) * 1.8;
        c.el.style.transform =
          'translate3d(' + x.toFixed(1) + 'px, ' + y.toFixed(1) + 'px, 0) rotate(' + rot.toFixed(1) + 'deg)';
      }
    }
    window.requestAnimationFrame(animateHero);
  }

  window.requestAnimationFrame(animateHero);
})();

/* ==========================================================================
   9. Scroll-Driven Lateral Parallax for Selected Works Cards
   ========================================================================== */
(function () {
  'use strict';

  var prefersReduced =
    window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (prefersReduced) return;

  var sideCards = Array.prototype.slice.call(document.querySelectorAll('[data-side-parallax]'));
  if (!sideCards.length) return;

  var sideTicking = false;

  function updateSideParallax() {
    sideTicking = false;
    var isDesktop = window.innerWidth >= 820;
    var vh = window.innerHeight || 800;
    var maxTravel = isDesktop ? 120 : 68;
    var maxRot = isDesktop ? 3.2 : 2.2;

    for (var i = 0; i < sideCards.length; i++) {
      var card = sideCards[i];
      var dir = card.getAttribute('data-side-parallax') === 'left' ? -1 : 1;
      var rect = card.getBoundingClientRect();
      // Progress goes 0 (entering bottom at 96% vh) -> 1 (settled at 42% vh)
      var startY = vh * 0.96;
      var endY = vh * (isDesktop ? 0.42 : 0.48);
      var raw = (startY - rect.top) / (startY - endY);
      var progress = Math.max(0, Math.min(1, raw));
      var remaining = Math.pow(1 - progress, 2);
      var tx = dir * remaining * maxTravel;
      var rot = dir * remaining * maxRot;

      if (remaining < 0.002) {
        card.style.transform = 'translate3d(0, 0, 0)';
      } else {
        card.style.transform =
          'translate3d(' + tx.toFixed(1) + 'px, 0, 0) rotate(' + rot.toFixed(2) + 'deg)';
      }
    }
  }

  function requestSideUpdate() {
    if (!sideTicking) {
      sideTicking = true;
      window.requestAnimationFrame(updateSideParallax);
    }
  }

  updateSideParallax();
  window.addEventListener('scroll', requestSideUpdate, { passive: true });
  window.addEventListener('resize', requestSideUpdate, { passive: true });
})();

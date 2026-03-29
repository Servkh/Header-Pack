/**
 * Hackman Sticky Header — scroll-aware sticky behaviour
 * Paste into: Elementor > Custom Code (JS) — or enqueue via functions.php
 *
 * Behaviour:
 *   • Past scrollThreshold px → header becomes fixed (.hsh-sticky)
 *   • Scroll DOWN → slides off the top (.hsh-hidden)
 *   • Scroll UP   → slides back in
 *   • Back at top → reverts to in-flow position
 */

(function () {
  'use strict';

  var SELECTOR  = '.hsh-header';
  var THRESHOLD = 80;   // px before sticky activates

  function ready(fn) {
    document.readyState === 'loading'
      ? document.addEventListener('DOMContentLoaded', fn)
      : fn();
  }

  ready(function () {
    var headers = document.querySelectorAll(SELECTOR);
    if (!headers.length) return;
    headers.forEach(initSticky);
    initMobileNav();
    initDropdowns();
  });

  /* ── Sticky ──────────────────────────────────────────────────────────── */

  function initSticky(header) {
    var lastY         = window.pageYOffset;
    var ticking       = false;
    var isSticky      = false;
    var offsetApplied = false;

    function applyOffset() {
      if (!offsetApplied) {
        document.body.style.paddingTop = header.getBoundingClientRect().height + 'px';
        document.body.classList.add('hsh-offset-active');
        offsetApplied = true;
      }
    }

    function removeOffset() {
      if (offsetApplied) {
        document.body.style.paddingTop = '';
        document.body.classList.remove('hsh-offset-active');
        offsetApplied = false;
      }
    }

    function update() {
      var y = window.pageYOffset;

      if (y <= 0) {
        header.classList.remove('hsh-sticky', 'hsh-hidden');
        removeOffset();
        isSticky = false;
        lastY = 0;
        ticking = false;
        return;
      }

      if (y > THRESHOLD && !isSticky) {
        header.classList.add('hsh-sticky');
        applyOffset();
        isSticky = true;
      }

      if (isSticky) {
        y > lastY
          ? header.classList.add('hsh-hidden')     // scrolling down
          : header.classList.remove('hsh-hidden');  // scrolling up
      }

      lastY   = y;
      ticking = false;
    }

    window.addEventListener('scroll', function () {
      if (!ticking) { requestAnimationFrame(update); ticking = true; }
    }, { passive: true });

    // Recalculate body offset on resize
    window.addEventListener('resize', function () {
      if (isSticky && offsetApplied) {
        document.body.style.paddingTop = header.getBoundingClientRect().height + 'px';
      }
    });

    // Handle pre-scrolled state (browser back button)
    update();
  }

  /* ── Mobile hamburger ────────────────────────────────────────────────── */

  function initMobileNav() {
    document.querySelectorAll('.hsh-mobile-toggle').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var open = btn.getAttribute('aria-expanded') === 'true';
        btn.setAttribute('aria-expanded', String(!open));
        var inner = btn.closest('.hsh-inner');
        var wrap  = inner && inner.querySelector('.hsh-nav-wrap');
        if (wrap) wrap.classList.toggle('hsh-mobile-open', !open);
      });
    });

    // Close on outside click
    document.addEventListener('click', function (e) {
      if (!e.target.closest('.hsh-header')) {
        document.querySelectorAll('.hsh-nav-wrap.hsh-mobile-open').forEach(function (w) {
          w.classList.remove('hsh-mobile-open');
          var btn = w.closest('.hsh-inner') && w.closest('.hsh-inner').querySelector('.hsh-mobile-toggle');
          if (btn) btn.setAttribute('aria-expanded', 'false');
        });
      }
    });
  }

  /* ── Dropdowns ───────────────────────────────────────────────────────── */

  function initDropdowns() {
    var parents = document.querySelectorAll(
      '.hsh-nav .hsh-has-dropdown, .hsh-nav .menu-item-has-children'
    );

    parents.forEach(function (item) {
      var link     = item.querySelector(':scope > a');
      var dropdown = item.querySelector(':scope > .hsh-dropdown, :scope > ul');
      if (!link || !dropdown) return;

      // Desktop: open class for arrow rotation (CSS handles visibility)
      item.addEventListener('mouseenter', function () {
        if (window.innerWidth > 768) item.classList.add('hsh-open');
      });
      item.addEventListener('mouseleave', function () {
        if (window.innerWidth > 768) item.classList.remove('hsh-open');
      });

      // Mobile: toggle on click
      link.addEventListener('click', function (e) {
        if (window.innerWidth <= 768) {
          e.preventDefault();
          var open = item.classList.toggle('hsh-open');
          link.setAttribute('aria-expanded', String(open));
        }
      });

      // Keyboard accessibility
      link.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          var open = item.classList.toggle('hsh-open');
          link.setAttribute('aria-expanded', String(open));
        }
        if (e.key === 'Escape') {
          item.classList.remove('hsh-open');
          link.setAttribute('aria-expanded', 'false');
          link.focus();
        }
      });

      item.addEventListener('focusout', function (e) {
        if (!item.contains(e.relatedTarget)) {
          item.classList.remove('hsh-open');
          link.setAttribute('aria-expanded', 'false');
        }
      });
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') {
        document.querySelectorAll('.hsh-has-dropdown.hsh-open, .menu-item-has-children.hsh-open')
                .forEach(function (i) { i.classList.remove('hsh-open'); });
      }
    });
  }

})();

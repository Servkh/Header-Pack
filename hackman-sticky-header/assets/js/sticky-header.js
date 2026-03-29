/**
 * Hackman Sticky Header — Scroll-aware sticky behaviour
 *
 * Behaviour:
 *   • After the user scrolls past `scrollThreshold` px the header becomes
 *     position:fixed (class: hsh-sticky).
 *   • Scrolling DOWN  → header slides off the top (class: hsh-hidden).
 *   • Scrolling UP    → header slides back in (hsh-hidden removed).
 *   • At the very top → header reverts to its natural in-flow position.
 *
 * Config (passed via wp_localize_script as window.hshConfig):
 *   selector        {string}  CSS selector for the header element.
 *   scrollThreshold {number}  Px before sticky activates. Default 80.
 *   animationSpeed  {number}  CSS transition ms. Default 300.
 */

(function () {
  'use strict';

  /* ── Guard ──────────────────────────────────────────────────────────── */
  if (typeof window === 'undefined') return;

  /* ── Config ─────────────────────────────────────────────────────────── */
  var cfg = window.hshConfig || {};
  var SELECTOR  = cfg.selector        || '.hsh-header';
  var THRESHOLD = cfg.scrollThreshold !== undefined ? parseInt(cfg.scrollThreshold, 10) : 80;
  var SPEED_MS  = cfg.animationSpeed  !== undefined ? parseInt(cfg.animationSpeed,  10) : 300;

  /* ── Init after DOM is ready ─────────────────────────────────────────── */
  function ready(fn) {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', fn);
    } else {
      fn();
    }
  }

  ready(function () {
    var headers = document.querySelectorAll(SELECTOR);
    if (!headers.length) return;

    headers.forEach(function (header) {
      initStickyHeader(header);
    });

    // Also handle the widget variant which may use .hsh-widget-header
    var widgets = document.querySelectorAll('.hsh-widget-header');
    widgets.forEach(function (widget) {
      // Only init if not already covered by SELECTOR
      if (!widget.matches(SELECTOR)) {
        initStickyHeader(widget);
      }
    });

    // Mobile nav toggle
    initMobileNav();
    // Dropdown keyboard accessibility
    initDropdowns();
  });

  /* ── Sticky logic ───────────────────────────────────────────────────── */

  function initStickyHeader(header) {
    /* Apply the configured transition speed from admin settings. */
    header.style.transition = [
      'transform ' + SPEED_MS + 'ms ease',
      'box-shadow ' + SPEED_MS + 'ms ease',
    ].join(', ');

    var headerHeight   = 0;
    var lastScrollY    = window.pageYOffset;
    var ticking        = false;
    var isSticky       = false;
    var offsetApplied  = false;

    function getHeaderHeight() {
      return header.getBoundingClientRect().height;
    }

    function applyBodyOffset(h) {
      if (!offsetApplied) {
        document.body.style.paddingTop = h + 'px';
        document.body.classList.add('hsh-offset-active');
        offsetApplied = true;
      }
    }

    function removeBodyOffset() {
      if (offsetApplied) {
        document.body.style.paddingTop = '';
        document.body.classList.remove('hsh-offset-active');
        offsetApplied = false;
      }
    }

    function onScroll() {
      if (!ticking) {
        window.requestAnimationFrame(update);
        ticking = true;
      }
    }

    function update() {
      var currentScrollY = window.pageYOffset;
      headerHeight       = getHeaderHeight();

      /* ── At the very top → revert to natural position ── */
      if (currentScrollY <= 0) {
        header.classList.remove('hsh-sticky', 'hsh-hidden');
        removeBodyOffset();
        isSticky = false;
        lastScrollY = 0;
        ticking = false;
        return;
      }

      /* ── Past threshold → activate sticky ── */
      if (currentScrollY > THRESHOLD && !isSticky) {
        header.classList.add('hsh-sticky');
        applyBodyOffset(headerHeight);
        isSticky = true;
      }

      if (isSticky) {
        var scrollingDown = currentScrollY > lastScrollY;

        if (scrollingDown) {
          /* Hide header when scrolling down */
          header.classList.add('hsh-hidden');
        } else {
          /* Show header when scrolling up */
          header.classList.remove('hsh-hidden');
        }
      }

      lastScrollY = currentScrollY;
      ticking = false;
    }

    window.addEventListener('scroll', onScroll, { passive: true });

    /* Recalculate on resize so body padding stays correct */
    var resizeTimer;
    window.addEventListener('resize', function () {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(function () {
        headerHeight = getHeaderHeight();
        if (isSticky && offsetApplied) {
          document.body.style.paddingTop = headerHeight + 'px';
        }
      }, 100);
    });

    /* Trigger once on load to handle pre-scrolled pages (browser back) */
    onScroll();
  }

  /* ── Mobile nav ─────────────────────────────────────────────────────── */

  function initMobileNav() {
    var toggles = document.querySelectorAll('.hsh-mobile-toggle');
    toggles.forEach(function (toggle) {
      toggle.addEventListener('click', function () {
        var expanded = toggle.getAttribute('aria-expanded') === 'true';
        toggle.setAttribute('aria-expanded', !expanded);
        var navWrap = toggle.closest('.hsh-nav-wrap') ||
                      (toggle.parentElement && toggle.parentElement.querySelector('.hsh-nav-wrap'));
        if (!navWrap) {
          // fallback: look in sibling
          navWrap = toggle.closest('.hsh-inner')
                          ? toggle.closest('.hsh-inner').querySelector('.hsh-nav-wrap')
                          : null;
        }
        if (navWrap) {
          navWrap.classList.toggle('hsh-mobile-open', !expanded);
        }
      });
    });

    /* Close mobile nav when clicking outside */
    document.addEventListener('click', function (e) {
      var insideHeader = e.target.closest('.hsh-widget-header, .hsh-header');
      if (!insideHeader) {
        document.querySelectorAll('.hsh-nav-wrap.hsh-mobile-open').forEach(function (wrap) {
          wrap.classList.remove('hsh-mobile-open');
          var toggle = wrap.closest('.hsh-inner')
                           ? wrap.closest('.hsh-inner').querySelector('.hsh-mobile-toggle')
                           : null;
          if (toggle) toggle.setAttribute('aria-expanded', 'false');
        });
      }
    });
  }

  /* ── Dropdown keyboard / click ──────────────────────────────────────── */

  function initDropdowns() {
    var dropdownParents = document.querySelectorAll('.hsh-has-dropdown, .menu-item-has-children');

    dropdownParents.forEach(function (item) {
      var link     = item.querySelector(':scope > a');
      var dropdown = item.querySelector(':scope > .hsh-dropdown, :scope > ul.sub-menu');

      if (!link || !dropdown) return;

      /* Hover — CSS handles desktop; JS adds the open class for arrow rotation */
      item.addEventListener('mouseenter', function () {
        if (window.innerWidth > 768) item.classList.add('hsh-open');
      });
      item.addEventListener('mouseleave', function () {
        if (window.innerWidth > 768) item.classList.remove('hsh-open');
      });

      /* Click — toggles on mobile, follows href on desktop if no sub-menu */
      link.addEventListener('click', function (e) {
        if (window.innerWidth <= 768) {
          e.preventDefault();
          item.classList.toggle('hsh-open');
          link.setAttribute('aria-expanded', item.classList.contains('hsh-open'));
        }
      });

      /* Keyboard: Enter / Space on parent toggles dropdown */
      link.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          item.classList.toggle('hsh-open');
          link.setAttribute('aria-expanded', item.classList.contains('hsh-open'));
        }
        /* Escape closes */
        if (e.key === 'Escape') {
          item.classList.remove('hsh-open');
          link.setAttribute('aria-expanded', 'false');
          link.focus();
        }
      });

      /* Close when focus leaves the entire item */
      item.addEventListener('focusout', function (e) {
        if (!item.contains(e.relatedTarget)) {
          item.classList.remove('hsh-open');
          link.setAttribute('aria-expanded', 'false');
        }
      });
    });

    /* Escape anywhere closes all open dropdowns */
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') {
        document.querySelectorAll('.hsh-has-dropdown.hsh-open, .menu-item-has-children.hsh-open')
                .forEach(function (item) { item.classList.remove('hsh-open'); });
      }
    });
  }

})();

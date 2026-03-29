=== Hackman Sticky Header for Elementor ===
Contributors: servkh
Tags: elementor, sticky header, navigation, scroll, header
Requires at least: 5.8
Tested up to: 6.5
Stable tag: 1.0.0
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A pixel-perfect, scroll-aware sticky header inspired by Hackman Paving, Inc.
Fully compatible with Elementor (free) and Elementor Pro Theme Builder.

== Description ==

This plugin delivers a dark-red-background navigation header with:

* Logo (image or SVG fallback with text)
* Centre navigation with multi-level dropdown support
* "Free Estimate" CTA button on the right
* Sticky on scroll-up / hidden on scroll-down behaviour — zero layout shift
* Fully responsive with a hamburger mobile menu
* Keyboard-accessible dropdowns (Enter, Space, Escape, focusout)
* No jQuery dependency — vanilla JS only
* Editor-safe: sticky effect is disabled inside the Elementor editor

= Three ways to use it =

**Option A — Hackman Header Widget**
Open any Elementor page, search for "Hackman Header" and drag the widget in.
Customise logo, menu, colours and padding from the Elementor panel — no code needed.

**Option B — Section toggle (Elementor Pro required for header templates)**
Already built your header as an Elementor section/container?
Go to *Advanced → Hackman Sticky Header* and flip the toggle.
The `.hsh-header` class is applied automatically.

**Option C — Manual CSS class**
Add `hsh-header` to any Elementor section via *Advanced → CSS Classes*.

= Admin Settings =

Navigate to *Settings → Hackman Sticky Header* to change:
* Header CSS selector (default `.hsh-header`)
* Scroll threshold in pixels (default 80 px)
* Animation speed in milliseconds (default 300 ms)

== Installation ==

1. Upload the `hackman-sticky-header` folder to `/wp-content/plugins/`.
2. Activate the plugin through the *Plugins* screen in WordPress.
3. Make sure Elementor is installed and active.
4. Follow one of the three usage options above.

== Frequently Asked Questions ==

= Does this require Elementor Pro? =
No. The widget and the CSS class approach work with the free version of Elementor.
The "Section toggle" control in the Advanced tab also works with free Elementor,
but for full Theme Builder header templates you need Elementor Pro.

= The header overlaps my page content. =
The plugin adds `padding-top` to `<body>` equal to the header height as soon as
sticky mode activates, so content should never be hidden. If a theme adds its own
header, set the CSS selector in *Settings → Hackman Sticky Header* to target only
the Elementor section.

= Can I change the colours without editing CSS? =
Yes — through the widget Style tab. Alternatively you can override the CSS custom
properties in *Elementor → Custom CSS* or your child theme:

    :root {
      --hsh-bg:      #7b2020;
      --hsh-hover:   #ffcc00;
    }

== Changelog ==

= 1.0.0 =
* Initial release.

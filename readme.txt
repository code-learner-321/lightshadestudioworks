=== Light Shade Studio Works ===

Contributors: Najubudeen
Tags: block-styles, custom-colors, custom-logo, custom-menu, editor-style, featured-images, full-width-template, portfolio, responsive-layout, theme-options
Requires at least: 5.2
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 2026
License: GNU General Public License v3 or later
License URI: https://www.gnu.org/licenses/gpl.html

A custom hybrid WordPress theme for photography and creative studios, built with Tailwind CSS, Gutenberg, and a guided setup wizard.


== Description ==

Light Shade Studio Works is a production-ready custom hybrid WordPress theme crafted for photography studios, creative agencies, and portfolio-driven businesses. It blends a modern Tailwind CSS utility framework with deep WordPress Customizer integration, giving you a polished front-end experience without sacrificing flexibility.

The theme ships with a guided setup wizard, prebuilt Gutenberg page templates, and companion plugin recommendations so you can launch a complete studio website in minutes. Every major design element — colors, typography, navigation, buttons, footer, and scroll-to-top behavior — can be tuned from the WordPress Customizer.

= Key Features =

* **Hybrid Architecture** — Classic PHP templates combined with block editor support, `theme.json` color palette integration, and Gutenberg-ready page content.
* **Tailwind CSS 4** — Utility-first styling compiled from `src/input.css` into a production-ready stylesheet.
* **Guided Setup Wizard** — On activation, the theme walks administrators through required plugin installation and one-click creation of default pages, blog posts, and navigation menus.
* **Five Navbar Layouts** — Choose from Standard Minimalist, Sleek Minimalist, Classic Inline Right, Modern Bold Minimalist, or Tabbed Overlap header styles, each with layout-specific Customizer controls.
* **Dynamic Color System** — Four built-in color schemes (Pastel, Dark, Ocean, Golden Ember) with a 60/30/10 palette model and neutral tone controls, all output as CSS custom properties.
* **Button Hover Effects** — Five animated hover styles: Internal Fill, Magnetic Border Expansion, Shutter Reveal, Pulse Wave, and Staggered Radial Ripple.
* **Typography Controls** — Adjust body and heading font sizes (H1–H6), container max-width, and per-side container padding from the Customizer.
* **Footer Customization** — Set background color, padding, text alignment, and custom footer content with HTML support.
* **Scroll-to-Top Button** — Optional back-to-top button with full color, size, padding, border-radius, and position controls.
* **Prebuilt Content** — Ready-made Gutenberg layouts for Home, About, Portfolio, Portfolio Gallery, Blog, Contact, and portfolio sub-pages (Wedding Celebrations, Portrait Sessions, Candid Moments, Real Estate Spaces), plus sample blog posts with featured images.
* **WooCommerce Ready** — Built-in WooCommerce theme support.
* **Accessibility & SEO** — Skip-to-content link, schema.org markup, semantic HTML5, and screen-reader-friendly navigation.
* **Optimized Performance** — Disabled oversized intermediate image sizes, cache-busted asset loading, and a lightweight compiled CSS bundle.

= Recommended Plugins =

The theme setup wizard recommends and helps install these companion plugins:

* **Axis Folio** — Portfolio and gallery functionality (bundled)
* **What Snap App** — Custom block for the theme homepage (bundled)
* **Carousel Block** — Gutenberg carousel block for galleries
* **Contact Form 7** — Contact form handling (auto-configured on setup)

= Developer Notes =

* Source styles live in `src/input.css` and compile to `assets/css/lightshadestudioworks-tailwindcss.css`.
* Modular PHP includes: `inc/customizer.php`, `inc/dynamic-css.php`, `inc/setup-wizard.php`, `inc/shortcodes.php`, and `inc/class-tailwind-nav-walker.php`.
* Custom shortcode: `[my_contact_form]` renders the auto-created Contact Form 7 form.
* Navbar template parts are located in `template-parts/navbar/`.


== Installation ==

= Automatic Installation =

1. Download the theme ZIP file or clone the repository.
2. In your WordPress admin, go to **Appearance → Themes → Add New → Upload Theme**.
3. Upload the ZIP file and click **Install Now**, then **Activate**.

= Manual Installation =

1. Clone or download the theme into your `wp-content/themes/` directory:

`git clone https://github.com/code-learner-321/lightshadestudioworks.git`

2. Activate the theme from **Appearance → Themes**.

= First-Time Setup =

1. After activation, you will be redirected to install the recommended plugins via the TGM Plugin Activation screen.
2. Once plugins are installed and activated, the **Theme Setup** wizard appears under **Appearance → Theme Setup**.
3. Click **Create Pages & Finish** to generate all prebuilt pages, sample blog posts, featured images, and the main navigation menu.
4. Visit **Appearance → Customize** to adjust colors, typography, navbar layout, button effects, footer, and scroll-to-top settings.

= Building Styles (Developers) =

If you modify Tailwind source files, rebuild the CSS from the theme root:

1. Install Node.js dependencies:

`npm install`

2. Watch for changes during development:

`npm run watch`

3. Build a minified production stylesheet:

`npm run build`


== Frequently Asked Questions ==

= Do I need to run the setup wizard? =

No. The setup wizard is optional. You can skip it and build your site manually, or run it later from **Appearance → Theme Setup** or **Tools → Default Pages**.

= Can I change the navbar layout after setup? =

Yes. Go to **Appearance → Customize → Header Navbar Layouts** and select any of the five available layouts. Layout-specific options (CTA button, colors, padding) appear based on your selection.

= How do I change the color scheme? =

Open **Appearance → Customize → Theme Color Palette**. Choose one of the four preset schemes or override individual primary, secondary, accent, and neutral colors.

= How do I add the contact form to a page? =

Insert the `[my_contact_form]` shortcode into any page or block editor shortcode block. The theme automatically creates and configures a Contact Form 7 form on first load when the plugin is active.

= Does this theme work with the block editor? =

Yes. The theme supports wide and full-width blocks, editor styles, block templates, appearance tools, and a `theme.json` color palette mapped to Customizer CSS variables.

= Can I reset all Customizer settings? =

Yes. In **Appearance → Customize → General**, use the **Reset Theme Settings** button to restore defaults.


== Screenshots ==

1. Homepage hero with photography studio branding and social links.
2. Customizer color palette with four preset schemes.
3. Navbar layout selector with five header styles.
4. Portfolio gallery page powered by Axis Folio and Gutenberg blocks.
5. Button hover effect options in the Customizer.


== Changelog ==

= 2026 =
* Added scroll-to-top button with full Customizer controls (colors, size, padding, position, scroll offset).
* Added five navbar layout template parts with layout-specific Customizer options.
* Added button hover effects: Internal Fill, Magnetic Border Expansion, Shutter Reveal, Pulse Wave, and Staggered Radial Ripple.
* Added guided setup wizard with TGM Plugin Activation and one-click default page creation.
* Added prebuilt Gutenberg page templates for Home, About, Portfolio, Blog, Contact, and portfolio sub-pages.
* Added dynamic CSS color system with four preset schemes and 60/30/10 palette model.
* Added typography controls for body and H1–H6 font sizes.
* Added footer styling controls (background, padding, text alignment, custom content).
* Added Tailwind CSS 4 integration with npm build workflow.
* Added WooCommerce theme support.
* Added schema.org markup, skip-to-content link, and platform detection classes.
* Added `[my_contact_form]` shortcode with auto-configured Contact Form 7 form.
* Added Tailwind-compatible custom navigation walker.


== Upgrade Notice ==

= 2026 =
Initial production release. Run the setup wizard after updating to generate prebuilt pages and install recommended plugins.


== Credits ==

* **Author:** [Najubudeen](https://www.najubudeen.com)
* **Repository:** [github.com/code-learner-321/lightshadestudioworks](https://github.com/code-learner-321/lightshadestudioworks)
* **Tailwind CSS:** [tailwindcss.com](https://tailwindcss.com) — MIT License
* **TGM Plugin Activation:** [github.com/TGMPA/TGM-Plugin-Activation](https://github.com/TGMPA/TGM-Plugin-Activation) — GPL-2.0-or-later

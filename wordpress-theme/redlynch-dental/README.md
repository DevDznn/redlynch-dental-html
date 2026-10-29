# Redlynch Dental WordPress theme

This is a custom WordPress theme for **Redlynch Dental**, 4/2 Chelsea Lane, Redlynch QLD 4870. It converts the Redlynch Dental static website faithfully: the design, pages, wording, images and behaviour are the same. Every page is editable in **Elementor (free)**, and SEO is handled by **ThinkRank**.

- **Requires:** WordPress 6.4+, PHP 7.4+, and Elementor 3.x or 4.x (free). Tested with WordPress 7.1.2, PHP 8.3, Elementor 4.3.5 and ThinkRank 2.17.1.
- **No other plugins are required.** Elementor Pro is not needed (see §9).
- **Nothing is loaded from third parties** except the Google map embed and the online booking site the buttons link to. Fonts, icons and images are bundled with the theme.

---

## 1. Install

1. **Appearance › Themes › Add New › Upload Theme**, choose `redlynch-dental.zip`, then **Install** and **Activate**.
2. **Plugins › Add New:** install and activate **Elementor** and **ThinkRank**.
3. **Appearance › Redlynch setup:** keep the boxes ticked and press **Import website content**. This creates:
   - 25 pages, each built in Elementor: Home, About, Meet Our Team, New Patients, Services, the 15 treatment pages, FAQs, Blog, Contact, New Patient Offer and Privacy Policy;
   - the blog article *Why choose dental implants?*;
   - the 5 team profiles;
   - 25 images in the Media Library, with their alternative text;
   - the menus (main, footer Services, footer Practice), the logo and the homepage setting;
   - if ThinkRank is active, each page's SEO title and meta description from the static site, and `noindex` on the privacy policy.

   You can run the import again at any time. It reuses existing pages, images and profiles, and it never deletes anything. Pages that already existed before the first import are left alone unless you tick **Replace**.
   The same import is available on the command line: `wp redlynch import` (add `--replace` to overwrite).
4. **Settings › Permalinks:** the import sets `/blog/%postname%/` if permalinks were still "Plain". With that setting, pages live at `/dental-implants/` and the article at `/blog/why-choose-dental-implants/`, the same addresses as the practice's previous website.

## 2. Practice details, opening hours, header and footer

Go to **Appearance › Customize › Redlynch Dental**.

| Section | What it controls |
|---|---|
| **Practice details** | Name, legal name, phone (displayed and tap-to-call), email, address, location note, **online booking link**, Facebook page, Google Maps directions link and **map embed address**. These are used by every page: header, top bar, footer, contact cards, sidebars, call and book buttons, the map and the enquiry form. |
| **Opening hours** | Opening and closing time for each day (24-hour time, for example `08:00` and `16:30`; leave both empty for a closed day), and the after-hours note. The full hours tables, the compact tables, the one-line summary in the top bar and the **"Open now / Closed · opens …"** indicator (Australia/Brisbane time) are all built from these values. |
| **Header and menu** | Show or hide the top bar, and the heading and text of the emergency card in the Services mega-menu. |
| **Footer and call to action** | Footer introduction, the Ahpra health statement, an optional separate footer logo, and the default call-to-action text used on blog posts and the 404 page. |

**Logo:** use **Customize › Site Identity › Logo**. The header and footer are dark, so use a logo with light text. The import sets the bundled light logo. Without a logo, the bundled one is shown. The **Site Icon** setting replaces the bundled favicons.

**Menus:** use **Appearance › Menus**.
- **Main menu:** a top-level item that has sub-items becomes a dropdown. If a top-level item has the CSS class `mega` (*Screen Options › CSS Classes*), its sub-items are shown as the Services mega-menu with the emergency card, and its *Description* is the mega-menu heading. Sub-items that link to a service page show that service's icon. The urgent-care service is shown in red. Items set to "Open link in a new tab" get ↗ and a screen-reader note. On phones, the same menu becomes the right-hand drawer, with the emergency, call and book buttons first.
- **Footer: Services** and **Footer: Practice:** the menu's name becomes the column heading.

## 3. Editing the homepage and other pages in Elementor

Open a page and choose **Edit with Elementor**. The pages are built from:

- **native Elementor containers** for every section and layout. The container's *Advanced › CSS Classes* field holds the design's layout classes (see the table below);
- **native widgets:** *Heading* for headings, *Text Editor* for paragraphs, lists, tables and figures, and *Image* for photos;
- the **Redlynch Dental** widgets (panel category "Redlynch Dental"), used only where a native widget cannot reproduce a component of the design.

| Redlynch widget | Used for |
|---|---|
| Buttons | Rows of brand buttons. "Book online", "Call" and "Emergency" buttons always use the Customizer's booking link, phone number and emergency page. |
| Page Hero / Emergency Hero | The top of inner pages: breadcrumbs, small heading, H1, intro, buttons, info chips, and a photo, illustration, type graphic or checklist card. The emergency version has the tap-to-call card and the "Call 000" bar. |
| Visit Card, Link Strip, Path Cards, Emergency Panel | Homepage hero card, the dark three-link bar, "How can we help", and the "In pain?" panel. |
| Icon Cards | Card grids (2, 3 or 4 columns), the dark-green comfort cards, the icon feature list, the emergency triage cards and the "Call the dentist / Call 000" panels. |
| Service Cards, Service Directory, Related Treatments, Guide Cards | Lists of services. They read the service's name, summary, icon and photo from the service page (see §4). |
| Team | Team profiles in six layouts (see §5). |
| FAQ, Jump Links | Accordions (native `<details>`, animated), with optional group headings, and the FAQ category index. |
| Steps | The dashed timeline or the numbered list. |
| Callout | "From the Redlynch Dental team", the lime, sand and warning callouts, and the "At a glance" cards. |
| Offer | The new patient offer card. |
| Contact Cards, Opening Hours, Map, Enquiry Form | These read the Customizer's details and hours. |
| Featured Article | The large blog card, showing the latest post or a chosen one. |
| Call to Action | The lime band at the bottom of pages. |
| Booking Sidebar | The sticky sidebar beside a service article. |

**Layout classes on containers** (keep `rd` on every container the theme styles):

| Class | Effect |
|---|---|
| `section` + `bg-paper` / `bg-sand` / `bg-forest` / `sec-tight` | A page section with its background and vertical padding (set the container's HTML tag to `section`). |
| `wrap` (`wrap narrow`) | The centred content width with side gutters. |
| `split`, `split top`, `split wide-left` | Two columns that stack below 1020px. |
| `sec-head`, `sec-head center`, `sec-head row` | Section heading block, centred, or with a button on the right. |
| `hero-home`, `hero-grid`, `hero-copy`, `hero-media` | The homepage hero. |
| `contact-grid`, `faq-cats`, `layout`, `prose` | Contact grid, FAQ page, service article + sidebar, and article text. |
| `rd-reveal` | The fade-up animation as the block scrolls into view. |
| `media-frame ratio-4-3` (on an Image widget) | Rounded 4:3 photo frame. Add `rd-focus-64-30` and similar classes to choose the visible part of the photo. |

Small headings ("eyebrows") are Text Editor paragraphs with the class `eyebrow`. In the editor's *Code* tab they look like `<p class="eyebrow">Our services</p>`. Duplicate an existing one to make a new one.

**Elementor defaults:** the import turns off Elementor's default colours and fonts and its Google Fonts. The theme's stylesheet supplies the brand styles and loads after Elementor's. The brand fonts, Fraunces and Manrope, appear in Elementor's font lists.

## 4. Services

Each treatment is an ordinary **page**, so its address stays `/dental-implants/` and so on. In the page editor (not in Elementor), the **Service listing** box in the sidebar sets:
- *This page is a service*;
- the short name used in menus and cards (for example "Crowns & Bridges");
- the one-to-two-sentence summary;
- the icon;
- the category for the Services page;
- whether the featured image is a diagram shown whole on white.

The page's **featured image** is its card image, and **Page Attributes › Order** sets the list order.

These details feed the homepage service cards, the Services page directory, "Related treatments", the Blog guide cards and the mega-menu icons. Edit them once and every listing follows.

**To add a service:** duplicate an existing service page in Elementor (*Save as Template*, then insert the template into a new page) or start from a blank page. Then fill in the Service listing box, set a featured image, and add the page to the Services menu.

The Emergency Dental Care page has its own layout (Emergency Hero, triage cards, steps, "Call the dentist / Call 000" panels).

## 5. Team

Team profiles are edited under **Team** in the admin menu:
- **Name:** the title.
- **Biography:** the main text.
- **Photo:** the featured image.
- **Profile details box:** role, short qualifications (for example "BDS"), full qualifications, the list heading (for example "Areas of expertise") and its items, the link anchor, and the group (Dentist or Support team).
- **Order:** Page Attributes.

The **Team** widget shows them in six layouts: homepage cards, the About page's dark row, full profiles on Meet Our Team, the support team row and cards, and the jump links. Every layout crops photos with CSS at a fixed aspect ratio and keeps the top of the photo, so replacement portraits stay consistent. Use a portrait at least 720px wide. Alternative text is written from the name and role.

## 6. SEO with ThinkRank

ThinkRank owns titles, meta descriptions, canonical URLs, robots directives, Open Graph and X tags, XML sitemaps, `robots.txt` and all structured data. The theme prints **none** of these. It has no JSON-LD, no robots meta, no canonical and no Open Graph tags.

The only exception applies **while no SEO plugin is active**: the theme then adds a plain meta description from the page excerpt. The `<title>` comes from WordPress core, and ThinkRank replaces it.

Setup:
1. **ThinkRank › Setup Wizard:** set the site name, logo and social profiles.
2. **ThinkRank › Local SEO:** enter the practice as a *Dentist* with the address, phone, opening hours and areas served (Redlynch, Brinsmead, Edge Hill, Smithfield, Cairns). This replaces the static site's `Dentist` JSON-LD.
3. FAQ, BreadcrumbList and other schema are ThinkRank's job too. The theme's FAQ widget prints only the visible accordion.
4. The import copies each page's SEO title and description into ThinkRank (only where empty) and sets the privacy policy to `noindex, nofollow`, as on the static site.
5. **Domain:** the production address in the project's config (`_build/lib.js`, `SITE_URL`) is **https://www.redlynchdental.com.au/**, not the Vercel preview. WordPress uses its own *Settings › General* address for canonicals and sitemaps, so set that to the production domain on the live site.

**Staging versus production:** the static site's staging build had `noindex` on every page and a `robots.txt` that blocked all crawlers. **None of that is carried into the theme.**
- On a staging copy, tick *Settings › Reading › Discourage search engines from indexing this site*. ThinkRank respects it.
- On the live site, untick it, then check the robots settings in ThinkRank and submit the sitemap in Google Search Console.

## 7. Booking, phone, email, map and the enquiry form

- **Booking:** "Book online" opens the Centaur booking page from the Customizer in a new tab, with a screen-reader note. No booking system is built into the site.
- **Phone and email:** they use `tel:` and `mailto:` links from the Customizer.
- **Map:** one Google map embed with one **Reload map** button that refreshes the same map in place. It has a loading spinner, a "couldn't load" message with an *open it in Google Maps* link (after 15 seconds, or straight away when offline), and the directions link. The embed address is set in the Customizer.
- **Enquiry form:** it behaves as on the static site. It checks the fields, then opens the visitor's own email app with the message addressed to the practice. **The website itself sends and stores nothing**, and the form says so. To receive enquiries through the website, replace the *Enquiry Form* widget on the Contact page with a form plugin's form (for example WPForms Lite or Fluent Forms), then update the privacy policy to say where enquiries are stored.

## 8. Old addresses

When a request would otherwise be a 404, the theme sends a 301 redirect:
- from the static site's file names, for example `/about.html` → `/about/`;
- from the practice's previous addresses that changed: `/about-us`, `/meet-our-team`, `/crowns-and-bridge-work`, `/whitening` and `/blog/why-choose-dental-implants`.

Redirects set in ThinkRank or on the server run first. To turn the theme's redirects off, use `add_filter( 'rd_legacy_redirects', '__return_false' );`.

## 9. Elementor Pro

**Elementor Pro is not required.**
- **Header and footer:** the theme's header and footer are edited with **Menus** and the **Customizer**, which is the free alternative to the Pro Theme Builder. If Elementor Pro is installed, the theme registers its locations, so a Theme Builder header or footer replaces the theme's own.
- **Custom attributes:** Elementor free can't add data attributes to containers. The CSS classes `rd-reveal` and `rd-reveal-group` do the same job for the scroll animations.

## 10. Files

```
style.css                 Theme header
functions.php             Loads inc/
header.php, footer.php    Document shell; template-parts/header|footer hold the markup
page.php                  Elementor pages print their own sections; other pages get hero + text
single.php, index.php, 404.php, comments.php
inc/practice.php          Customizer: practice details and opening hours (+ helpers)
inc/template-tags.php     Icons, buttons, breadcrumbs, hours tables, map, call-to-action band
inc/navigation.php        Header menu walker (dropdowns, mega-menu, drawer) and footer menus
inc/services.php          "Service listing" box and service helpers
inc/team.php              Team post type, profile box, jump links
inc/posts.php             Blog byline box
inc/seo.php               ThinkRank ownership (no SEO output; fallback description only)
inc/redirects.php         Old addresses → pages (301, on 404 only)
inc/elementor/            Widget category, base class and the 25 Redlynch widgets
inc/import/               Importer (Appearance › Redlynch setup, wp redlynch import) and content.json
assets/css/site.css       The static site's stylesheet, unchanged
assets/css/wp-base.css    Fonts and neutral Elementor defaults (loads before site.css)
assets/css/wp.css         WordPress/Elementor adaptations (loads after site.css)
assets/js/main.js         The static site's script, adapted (also runs in the Elementor editor)
assets/fonts/             Fraunces and Manrope (SIL Open Font License)
assets/images/            All site images and the logo
```

`inc/import/content.json` is generated from the static site's source by `wordpress-theme/tools/build-content.js` in the project repository.

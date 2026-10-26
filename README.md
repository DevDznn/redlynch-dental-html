# Redlynch Dental: static website

A complete static HTML/CSS/JavaScript website for **Redlynch Dental**, 4/2 Chelsea Lane, Redlynch QLD 4870.

- No framework, no database, no runtime dependencies.
- Works straight from a folder or on any static host.
- All practice facts come from the official site (https://www.redlynchdental.com.au/) and are documented in `research/`.
- The Coral Coast Dental HTML site was used as the design-quality reference only. Redlynch has its own palette, type, layouts and components, and **none** of Coral Coast's content, branding or images.

> **This build is a STAGING build.** Every page carries `noindex,nofollow` and `robots.txt` blocks all crawlers. (There is no visible staging badge; the site looks finished to visitors.) Follow "Going live" below before launch.

---

## 1. Pages (27)

| File | Page |
|---|---|
| `index.html` | Home |
| `about.html` | About Redlynch Dental |
| `our-team.html` | Meet Our Team (full profiles, jump links) |
| `new-patients.html` | New Patients |
| `services.html` | All services, grouped by category with a sticky index |
| `check-ups-and-dental-cleans.html` | Check-ups and Dental Cleans |
| `childrens-dentistry.html` | Children's Dentistry |
| `cosmetic-veneers.html` | Cosmetic Veneers |
| `crowns-and-bridges.html` | Crowns and Bridges |
| `dental-implants.html` | Dental Implants |
| `dentures.html` | Dentures |
| `emergency-dental-care.html` | **Emergency Dental Care** (its own layout: tap-to-call card, 000 guidance, triage cards) |
| `extractions.html` | Extractions |
| `facial-injectables-and-skin-treatments.html` | Facial Injectables and Skin Treatments |
| `fillings.html` | Fillings |
| `invisalign.html` | Invisalign® |
| `mouthguards.html` | Mouthguards |
| `root-canal-therapy.html` | Root Canal Therapy |
| `teeth-whitening.html` | Teeth Whitening |
| `wisdom-teeth.html` | Wisdom Teeth |
| `faq.html` | FAQs (7 categories, accordions, sticky category index) |
| `blog.html` | Blog listing (the one genuine article + treatment guides) |
| `blog-why-choose-dental-implants.html` | Blog article (original post by Dr Alex Bornsztejn, 29 Jan 2021) |
| `contact.html` | Contact, hours, enquiry form, map |
| `special-offers.html` | New patient offer (from the official Special Offers page) |
| `privacy-policy.html` | Website-only privacy notice; the practice should add its full patient-records policy; `noindex` |
| `404.html` | Not found; `noindex` |

Also: `sitemap.xml`, `robots.txt`.

## 2. Folder structure

```
redlynch-dental-html/
├── *.html                    27 generated pages (flat, relative links)
├── assets/
│   ├── css/style.css         Whole design system (tokens → components → responsive)
│   ├── js/main.js            Navigation, mega-menu, mobile drawer, reveals, accordions, open-now, form
│   ├── js/practice-data.js   Phone, email, booking URL and opening hours used at runtime
│   └── images/               branding/ hero/ team/ services/ emergency/ blog/ offers/
├── _build/                   Generator (Node.js, no dependencies)
│   ├── build.js              Entry point; writes pages, sitemap.xml, robots.txt
│   ├── lib.js                Verified facts, icons, header/nav/footer layout, JSON-LD
│   ├── services.js           All 15 service pages' content and FAQs
│   └── pages.js              Home, About, Team, New Patients, Services, Emergency, FAQ, Blog, Contact…
├── research/
│   ├── official-site-research.md   What was found on the official site, and what is unverified
│   ├── practice-facts.json         Structured facts with verification status
│   ├── IMAGE-MANIFEST.md           Every image: local path, use, original URL, status
│   ├── QA-REPORT.md                What was tested, and results
│   └── source-snapshots/           Raw HTML of every official page, as downloaded
└── README.md
```

## 3. Preview locally

**Simplest:** double-click `index.html`. All paths are relative, so the site works from the folder.

**Closer to production (recommended):**

```bash
cd redlynch-dental-html
npx serve .            # or: python -m http.server 8000
```

Then open the URL shown, for example `http://localhost:3000`.

Google Fonts (Fraunces, Manrope) need an internet connection. Offline, the site falls back to system fonts. The Google map is embedded directly (lazy-loaded); a "Reload map" button under it refreshes the same map in place.

## 4. Editing content

**Option A: edit the generator (recommended).** Header, navigation and footer are shared across 27 pages, so edit the source and rebuild:

1. Practice facts (phone, hours, address, booking link) are in `_build/lib.js` → `F`. If hours change, also update `assets/js/practice-data.js`, which drives the "Open now" indicator.
2. Service content is in `_build/services.js`.
3. All other pages are in `_build/pages.js`.
4. Rebuild:
   ```bash
   node _build/build.js                # staging (noindex)
   node _build/build.js --production   # live (indexable)
   ```
   Node.js 18+ is required. There is no `npm install`. The build overwrites the generated `.html`, `sitemap.xml` and `robots.txt` in the project root.

**Option B: edit the HTML directly.** Fine for one-off text changes, but those edits will be overwritten if you later run the build.

## 5. Going live (checklist)

1. Work through the "Still to verify" list in `research/official-site-research.md` (offer, prices, free kids dentistry, Ahpra details, privacy policy).
2. Run `node _build/build.js --production`. This:
   - switches every page (except privacy draft and 404) to `index,follow`
   - writes `robots.txt` that allows crawling and points to the sitemap
3. Upload the whole folder **except** `_build/` and `research/`. Those folders are not needed on the server; keep them in your source copy.
4. Add 301 redirects from the old URLs (section 6).
5. Configure the 404 page on the host (for example `ErrorDocument 404 /404.html` on Apache).
6. Submit `https://www.redlynchdental.com.au/sitemap.xml` in Google Search Console.

**Hosting:** any static host works (cPanel/Apache, Netlify, Cloudflare Pages, Vercel, S3). No server-side code is required.

## 6. Redirects from the old site

The new file names differ from the current live URLs. Add these 301s to keep search rankings and old links working. Apache `.htaccess` example:

```apache
RewriteEngine On
RewriteRule ^about-us/?$                 /about.html [R=301,L]
RewriteRule ^meet-our-team/?$            /our-team.html [R=301,L]
RewriteRule ^services/?$                 /services.html [R=301,L]
RewriteRule ^check-ups-and-dental-cleans/?$ /check-ups-and-dental-cleans.html [R=301,L]
RewriteRule ^childrens-dentistry/?$      /childrens-dentistry.html [R=301,L]
RewriteRule ^cosmetic-veneers/?$         /cosmetic-veneers.html [R=301,L]
RewriteRule ^crowns-and-bridge-work/?$   /crowns-and-bridges.html [R=301,L]
RewriteRule ^dental-implants/?$          /dental-implants.html [R=301,L]
RewriteRule ^dentures/?$                 /dentures.html [R=301,L]
RewriteRule ^emergency-dental-care/?$    /emergency-dental-care.html [R=301,L]
RewriteRule ^extractions/?$              /extractions.html [R=301,L]
RewriteRule ^facial-injectables-and-skin-treatments/?$ /facial-injectables-and-skin-treatments.html [R=301,L]
RewriteRule ^fillings/?$                 /fillings.html [R=301,L]
RewriteRule ^invisalign/?$               /invisalign.html [R=301,L]
RewriteRule ^mouthguards/?$              /mouthguards.html [R=301,L]
RewriteRule ^root-canal-therapy/?$       /root-canal-therapy.html [R=301,L]
RewriteRule ^whitening/?$                /teeth-whitening.html [R=301,L]
RewriteRule ^wisdom-teeth/?$             /wisdom-teeth.html [R=301,L]
RewriteRule ^special-offers/?$           /special-offers.html [R=301,L]
RewriteRule ^contact/?$                  /contact.html [R=301,L]
RewriteRule ^blog/why-choose-dental-implants/?$ /blog-why-choose-dental-implants.html [R=301,L]
RewriteRule ^blog/?$                     /blog.html [R=301,L]
ErrorDocument 404 /404.html
```

## 7. Contact form

The enquiry form on `contact.html` **does not submit to a server**, because this static build has no backend. After validation it opens the visitor's own email app with a pre-filled message to reception@redlynchdental.com.au, and the page says so plainly. There is no fake "message sent" confirmation.

To make it submit directly, connect a form service and replace the submit handler in `assets/js/main.js` ("Contact form" block):
- **Netlify Forms** (if hosted on Netlify): add `data-netlify="true"` and `name="enquiry"` to the form, and remove the JS `preventDefault` hand-off.
- **Formspree / Basin / Web3Forms**: set `action="https://…"` and `method="POST"`, and post with `fetch`.
- **The practice's existing CMS endpoint** (`/EnquiryForm/` on the old site) is part of the old platform and will not exist on a static host.

Whatever service is chosen, update the privacy policy to say where enquiries are stored. Health information sent by email is sensitive, so the form deliberately asks only for contact details and a short message.

## 8. Design system (Redlynch identity)

| | |
|---|---|
| **Concept** | "Rainforest valley": Redlynch sits in a green valley below the rainforest. The logo's lime on near-black sets the tone, warmed with cream and sand. |
| **Colours** (`:root` in `style.css`) | `--ink #111915` (header/footer, hosts the black-background logo) · `--forest #22392B` · `--lime #BCD531` (sampled from the logo; dark text only) · `--moss #566F12` (text-safe green, 5.2:1 on cream) · `--cream #F7F4EC` · `--sand #ECE5D3` · `--ember #B5371F` (emergency; white text 5.9:1) |
| **Type** | Fraunces (soft serif display, italic accents) + Manrope (body, uppercase tracked eyebrows) |
| **Signature shapes** | Arch-topped photo frames (team, hero), pill buttons with sliding arrows, bento service grid, numbered editorial lists, dashed step timelines |
| **Navigation** | Dark sticky header; Services opens a 3-column **mega-menu** with an emergency feature card; About and Patient Info use compact dropdowns; mobile uses a right-hand drawer with emergency, call and book buttons first |
| **Motion** | Hero entrance, staggered scroll reveals, tile zoom and lift, animated accordions, header shrink on scroll. All disabled under `prefers-reduced-motion`. Content is never hidden without JavaScript, because reveals only apply when `html.js` is set. |

How it differs from Coral Coast: dark header instead of a white header with a top bar; mega-menu instead of a list dropdown; light editorial hero with an arch photo instead of a dark gradient split hero; bento tiles and category rows instead of image cards; serif display type instead of Poppins; lime/forest/cream instead of sky blue/charcoal/coral.

## 9. Accessibility and SEO notes

- Semantic landmarks, skip link, one `h1` per page, labelled navigation, visible focus rings.
- Dropdowns open on hover, click and keyboard: Enter or Space toggles, ArrowDown moves into the menu, Escape closes. The mobile drawer traps focus and closes on Escape or a scrim tap.
- FAQs use native `<details>`, so they work without JavaScript.
- Unique `<title>` and meta description on every page; canonical URLs; Open Graph tags.
- JSON-LD: `Dentist` (verified address, phone, hours and dentists), `BreadcrumbList`, `FAQPage`, `MedicalProcedure`, `Person` and `BlogPosting`. Only verified facts are used.
- Images: WebP, explicit dimensions, `loading="lazy"` below the fold, `fetchpriority="high"` on hero images.

## 10. Compliance notes for the practice

- **Ahpra advertising:** the site avoids testimonials, before/after patient photos, guarantees and "pain-free" claims. The official site's "pain free" (implants) and "without any pain whatsoever" (root canal) were reworded. The Ahpra risk statement appears on surgical pages and in the footer.
- **Facial injectables page:** Ahpra's guidelines for non-surgical cosmetic procedures and the TGA's ban on advertising prescription-only medicines are strict. The page lists treatment *areas* (as the official site does), avoids naming substances and the term "anti-wrinkle", and stresses consultation and risks. **Have it reviewed before launch.**
- **Prices and offers** shown are those published on the official site and are flagged for confirmation: the new patient offer (gap free or $199) and the wisdom tooth price ($200–$400).
- No Medicare, CDBS, health-fund preferred-provider, HICAPS, finance-provider or 24/7 claims are made, because none are published by the practice.

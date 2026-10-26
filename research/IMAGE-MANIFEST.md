# Image manifest

Every image in the site is stored locally in `assets/images/` (1.4 MB in total). Nothing hot-links to redlynchdental.com.au.

**Origin:** every photograph and illustration was downloaded from the official Redlynch Dental website on 26 October 2026, from `https://www.redlynchdental.com.au/thumbnaillarge/<file>`. The site's `/files/<file>` originals return 404, so the `thumbnaillarge` copies are the largest versions available. Images were converted to WebP (quality 76–88) and resized only where wider than needed. Aspect ratios are preserved.

**No Coral Coast images, logos or team photos are used.** The supplied `C:\Users\KD\Dental\IMAGES` folder was checked. It contains Coral Coast and unrelated practice assets of unknown licence, and none were used.

**No stock image is presented as the practice, its staff or its patients.** Alt text for stock and illustrative images describes the image only. The only images described as Redlynch Dental people are the team photographs.

## Authentic practice photography

| Local file | Used on | Original source | Notes |
|---|---|---|---|
| `team/dr-alex-bornsztejn.webp` | Home (team), About, Our Team (profile + jump link) | `thumbnaillarge/IMG_1618.jpg` (2881×2757) | Matched by order and heading on `/meet-our-team`. Resized to 720 px wide. |
| `team/dr-nadia-mullins.webp` | Home, About, Our Team | `thumbnaillarge/NADIANEWPIC.jpg` | Filename and page order match. |
| `team/dr-julian-fung.webp` | Home, About, Our Team | `thumbnaillarge/IMG_4664.jpg` (2375×2620) | Matched by page order and heading. |
| `team/erin-practice-manager.webp` | Home (support row), Our Team | `thumbnaillarge/ERINNEWPIC.jpg` | Filename match. |
| `team/ella-dental-assistant.webp` | Home (support row), Our Team | `thumbnaillarge/ELLANEW.jpg` | Filename match. |
| `team/redlynch-dental-team-group.webp` | Home hero, About hero, default social share image, JSON-LD `image` | `thumbnaillarge/good1.jpg` (1100×733) | Three team members. Only Dr Alex Bornsztejn (right) is named in alt text, because he is clearly identifiable from his portrait. **The two women are not named**, because they could not be identified with certainty. Ask the practice who they are. |

## Branding

| Local file | Used on | Original source | Notes |
|---|---|---|---|
| `branding/redlynch-dental-logo-original.png` | JSON-LD `logo` | `/files/logo.png` (900×215) | Untouched original: lime mark and white wordmark on a black background. Lime sampled as `#BCD531`. |
| `branding/redlynch-dental-logo-light.png` | Header and footer, every page | Derived from the original | Black background made transparent; colours unchanged. |
| `branding/redlynch-dental-logo-dark.png` | Not currently used | Derived from the original | Dark wordmark with a deeper green mark, for light backgrounds or print. A **derived colour variant**, so confirm with the practice before using it. |
| `branding/favicon-32.png`, `favicon-192.png` | Every page | `/favicon.png` (270×270) | Resized. |
| `branding/apple-touch-icon.png` | Every page | `/apple-touch-icon.png` | Copied as is. |

## Treatment and educational images (stock, from the official site)

These were used by the practice on its own website. File names show they are licensed stock (Shutterstock, iStock, 123RF-style IDs). **The licences were not available to check.** Confirm that the practice's licences cover use on the redesigned site.

| Local file | Main use | Also appears as a thumbnail on | Original source |
|---|---|---|---|
| `services/dental-examination.webp` | Check-ups hero; home intro | Services list, home bento, related cards | `thumbnaillarge/iStock-1125916927.jpg` (official site used it on Extractions) |
| `services/check-up-tartar-before-after.webp` | Check-ups article figure | n/a | `thumbnaillarge/17049876_l-o1uzrl23mtjm7cski6cp8.jpg`. Captioned as illustrative, not a Redlynch patient. |
| `services/childrens-dentistry-smiling-girl.webp` | Children's hero; home family section | Services list, bento, related | `thumbnaillarge/iStock-1165526820.jpg` |
| `services/boy-in-dental-chair.webp` | Fillings hero | Services list, related | `thumbnaillarge/iStock-1284419412.jpg` |
| `services/porcelain-veneers.webp` | Veneers hero | Services list, related | `thumbnaillarge/shutterstock_1121377172-o2x633mf.jpg` |
| `services/dental-crown-illustration.webp` | Crowns & Bridges hero + figure | Bento, services list, related | `thumbnaillarge/shutterstock_1260385099.jpg` |
| `services/dental-bridge-illustration.webp` | Crowns & Bridges figure | n/a | `thumbnaillarge/shutterstock_1260385081.jpg` |
| `services/dental-implant-row.webp` | Implants hero | Services list, related | `thumbnaillarge/23133809_m-o2omk5oxviidf80arrit2.jpg` |
| `blog/dental-implant-crown-illustration.webp` | Blog listing + article; implants page blog teaser | n/a | `thumbnaillarge/59994567_m.jpg` (the official blog post's image) |
| `services/full-denture.webp` | Dentures hero | Services list, related | `thumbnaillarge/19380071_s.jpg` |
| `emergency/jaw-pain.webp` | Emergency page "what happens next" figure | Services list, related | `thumbnaillarge/header-emergency-dentistry-o28ht.jpg` |
| `services/clear-aligners.webp` | Invisalign® hero | Bento, services list, related | `thumbnaillarge/shutterstock_1051143311-o2x6iaoh.jpg` |
| `services/invisalign-banner.webp` | Invisalign® article figure | n/a | `thumbnaillarge/services-invisalign-banner-o278v.jpg`. Contains the Invisalign® logo, as on the official site. |
| `services/sports-mouthguard.webp` | Mouthguards hero | Services list, related | `thumbnaillarge/70298001_s.jpg` |
| `services/root-canal-diagram.webp` | Root canal hero + figure | Services list, related | `thumbnaillarge/shutterstock_93206026-1024x1024.jpg`. Labelled diagram, shown on white. |
| `services/wisdom-teeth-x-ray.webp` | Wisdom teeth hero | Services list, related | `thumbnaillarge/33519726_m.jpg` |
| `hero/smiling-mother-and-daughter.webp` | Teeth whitening hero | Services list, related | `thumbnaillarge/subBanner.jpg` (the old site's interior page banner) |
| `offers/new-patient-special-offer.webp` | Home, New Patients, Special Offers | n/a | `thumbnaillarge/SP-2.jpg`. The text in the graphic is repeated in HTML and in the full alt text. |

## Pages without a dedicated photograph (by design)

| Page | What is shown instead | Why |
|---|---|---|
| Extractions | Designed type graphic: icon and "Calm, careful and at your pace." | The only official image for this page (`iStock-1125916927`) is used as the Check-ups hero, to avoid repeating it as a hero. |
| Facial injectables and skin treatments | Designed type graphic: icon and "Your smile, seen in context." | The official page's only image (`Do-It-All-A4.jpg`) is a third-party (AADFA) promotional poster with small baked-in text, so it is unsuitable. Before/after or face imagery for cosmetic injectables is also restricted under Ahpra's cosmetic advertising guidelines. |
| About, New Patients, FAQ, Blog, Contact, Services hubs | Brand colour heroes, team photos and offer graphic | There is no interior or exterior photography of the practice on the official site. |

## Downloaded but deliberately not used

| Original | Reason |
|---|---|
| `thumbnaillarge/Do-It-All-A4.jpg` | Third-party association poster with baked-in text; see above. |
| `thumbnaillarge/before_after.jpg` | Small (450 px), carries Philips Zoom branding, and before/after whitening images need care under Ahpra advertising guidance. |
| `thumbnaillarge/_homeSlider.gif` | 1000×1 px spacer, not an image. |

## Outstanding image issues

1. **No photographs of the practice building, reception or treatment rooms exist on the official site.** The single most valuable addition would be 4–6 real photos (exterior and signage at Chelsea Lane, reception, a treatment room). Drop-in places: the home intro image (`.stack-img`), the About hero, and the Contact page.
2. Identify the two team members in `redlynch-dental-team-group.webp` so the alt text can name them.
3. Confirm that stock-image licences cover the new site.
4. No broken image paths: an automated check of every `src` and `href` in all 27 pages found **0 missing files**.

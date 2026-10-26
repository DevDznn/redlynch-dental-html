# Redlynch Dental: official website research

**Source of truth:** https://www.redlynchdental.com.au/
**Researched:** 26 October 2026, by downloading every page linked from the official navigation, plus the services page and blog (HTTP 200 for all).
**Raw copies:** every page downloaded is saved in `research/source-snapshots/` so facts can be re-checked later.

Coral Coast Dental was used **only** as a design-quality reference. No Coral Coast business details, copy, branding or images were used.

---

## 1. Pages found on the official site

| Official URL | Status | Notes | New site file |
|---|---|---|---|
| `/` | 200 | Hero "Gentle and affordable dentistry!", "Why Choose Us" list, intro copy, hours | `index.html` |
| `/about-us` | 200 | **Empty**: heading and a "Meet Our Team" link only | `about.html` (built from homepage copy) |
| `/meet-our-team` | 200 | 3 dentists + 2 support staff, with photos | `our-team.html` |
| `/services` | 200 | List of 15 services (includes Facial Injectables, which is not in the menu) | `services.html` |
| `/check-ups-and-dental-cleans` | 200 | First-visit checklist | `check-ups-and-dental-cleans.html` |
| `/childrens-dentistry` | 200 | Short paragraph | `childrens-dentistry.html` |
| `/cosmetic-veneers` | 200 | Two-appointment process | `cosmetic-veneers.html` |
| `/crowns-and-bridge-work` | 200 | Crowns + bridges paragraphs | `crowns-and-bridges.html` |
| `/dental-implants` | 200 | 3-stage process, complimentary consultation | `dental-implants.html` |
| `/dentures` | 200 | Short paragraph | `dentures.html` |
| `/emergency-dental-care` | 200 | Emergency list, "reserve specific appointments daily" | `emergency-dental-care.html` |
| `/extractions` | 200 | Short paragraph | `extractions.html` |
| `/facial-injectables-and-skin-treatments` | 200 | Treatment list (linked only from `/services`) | `facial-injectables-and-skin-treatments.html` |
| `/fillings` | 200 | Decay progression, white fillings | `fillings.html` |
| `/invisalign` | 200 | Advantages + 4 FAQs | `invisalign.html` |
| `/mouthguards` | 200 | Injury list | `mouthguards.html` |
| `/root-canal-therapy` | 200 | Paragraph | `root-canal-therapy.html` |
| `/whitening` | 200 | 5 FAQs, Philips Zoom | `teeth-whitening.html` |
| `/wisdom-teeth` | 200 | Problems list, 5 FAQs incl. price | `wisdom-teeth.html` |
| `/special-offers` | 200 | New patient offer (graphic only) | `special-offers.html` |
| `/blog/` | 200 | 1 post | `blog.html` |
| `/blog/why-choose-dental-implants` | 200 | Post, 29/01/2021, Alex Bornsztejn | `blog-why-choose-dental-implants.html` |
| `/contact` | 200 | Details, map embed, enquiry form (posts to `/EnquiryForm/` on the old CMS) | `contact.html` |
| `/faq`, `/new-patients`, `/privacy-policy` | **404** | Do not exist on the official site | New pages written for this build (see section 6) |

## 2. Verified practice facts

| Fact | Value | Where found |
|---|---|---|
| Practice name | Redlynch Dental | All pages, logo |
| Legal entity | Chelsea Lane Dental Pty Ltd | Footer copyright on every page; service page `<title>`s |
| Phone | (07) 4039 4720 | Header, footer, contact |
| Email | reception@redlynchdental.com.au | Footer, contact |
| Address | 4/2 Chelsea Lane, Redlynch QLD 4870 | Header, footer, contact |
| Location | "located within the Redlynch Commercial Hub" | Homepage |
| Areas served | Redlynch, Brinsmead, Edge Hill, Smithfield and surrounding suburbs of Cairns | Homepage |
| Hours | Mon–Thu 8am–5pm · Fri 8am–4:30pm · Sat & Sun closed | Footer, every page |
| After hours | "after hours and weekend appointments available At Request" | Homepage "Why Choose Us" |
| Online booking | https://www.centaurportal.com/d4w/org-2836/search?practiceID=3243 (Centaur D4W; resolves, HTTP 200) | "Book Now" in nav |
| Facebook | https://www.facebook.com/Redlynch-Dental-102113211727578 | Header/footer (Facebook blocks automated checks, HTTP 400 to curl: not independently verified) |
| Google Maps | `goo.gl/maps/pXrkYmtVfPAuVJ5H7` link and an embed for the "Redlynch Dental" place | Header, contact |

### "Why Choose Us" (homepage), verbatim headings
- affordable pricing
- after hours and weekend appointments available At Request
- knowledgeable, caring and gentle staff
- the very best modern equipment
- Payment plans available

### Homepage service list (hero)
Check ups and dental cleans · **Free kids dentistry** · Fillings · Whitening · Implants · Dentures · Invisalign · Veneers, Crowns and bridges · Root canals · Mouthguards · Emergency appointments

### Patient-experience claims (homepage)
- "Let us know what music we can play for you, or choose a movie to watch whilst you have your treatment completed."
- "We help many anxious patients overcome their apprehensions and complete their dental treatment."
- "Many of our patients say they wish they had come to see us earlier!"

## 3. Team (from `/meet-our-team`)

| Person | Role | Qualifications (as published) | Photo file on official site |
|---|---|---|---|
| Dr Alex Bornsztejn | Principal Dentist | Bachelor Health Science in Dentistry, Masters in Dentistry (BHSc(Dent), MDent). Degree from La Trobe University (published as "Latrobe") | `IMG_1618.jpg` |
| Dr Nadia Mullins | Dentist | Bachelor of Dental Surgery (BDS), James Cook University, graduated 2018 | `NADIANEWPIC.jpg` |
| Dr Julian Fung | Dentist | Bachelor of Dental Surgery (BDS), JCU Cairns | `IMG_4664.jpg` |
| Erin | Practice Manager | Certificate III in Dental Assisting | `ERINNEWPIC.jpg` |
| Ella | Dental Assistant | none published | `ELLANEW.jpg` |

Biographies were restated closely from the official page (light edits for grammar and third-person consistency). **Not verified against the Ahpra register** (no register lookup was performed). Confirm registration details before launch.

Alex's published areas of expertise: general dentistry; implant dentistry; wisdom teeth removal; crowns, veneers and smile makeovers.

## 4. Service-specific facts used

- **Check-ups:** examine teeth and gums; assess jaw joints; necessary X-rays (close-up and full-face); photos of potential problems; scale, clean and polish; topical fluoride; printed treatment plan ("most accurate possible estimate of time and cost").
- **Emergency:** "reserve specific appointments daily"; list = painful tooth or gums, broken teeth and fillings, facial swelling, trauma to face/teeth/gums, broken or lost denture; "call us immediately"; "We will always strive to make time for you to be seen ASAP." **No 24/7, guaranteed same-day or after-hours emergency service is claimed** on the official site, and none is claimed on the new site.
- **Implants:** "complimentary implant consultation"; mould + "special 3D xray"; placement "completely computer guided"; tooth fitted after ~3 months. The official page says "It is a pain free procedure"; the new site **does not** repeat the pain-free claim (it says placement is under local anaesthetic and the patient is checked for numbness).
- **Root canal:** official says "without any pain whatsoever"; the new site says "completed comfortably under local anaesthetic" instead.
- **Veneers:** 2 appointments; high-precision magnification; temporary veneers.
- **Invisalign®:** trays changed every 2 weeks; removable up to 2 hours/day; as quick as 7 weeks for minor cases, average 12–18 months; retention required; "Invisalign® trained dentist".
- **Whitening:** practice "exclusively" uses the Philips ZOOM system; repeat ~every 6 months on average.
- **Wisdom teeth:** **published price $200–$400** for removal under local anaesthetic in the chair; referral to an oral surgeon in some cases; a sedative can be prescribed.
- **Facial injectables:** lip, cheek, nasal/"bunny lines", droopy eyebrow and frown line treatments using "fillers and anti-wrinkle treatments"; skin: PRP, micro-needling, "permanent fat reduction injections", non-surgical facelifts and thread lifts.

## 5. Offers, payment and pricing

- **New patient special offer** (`/special-offers`, graphic `SP-2.jpg`, text read from the image): Comprehensive dental check-up · All necessary X-rays (inc full-face X-rays) · Scale, clean & polish · Fluoride treatment · Treatment plan · **GAP FREE with any health fund** · **Not in a health fund? All the above for only $199** · "T&Cs apply". Page text: "Terms and conditions apply to all offers". **No date or expiry is published**. Confirm it is current.
- **Payment plans:** "Payment plans available". **No provider is named**, and none is named on the new site.
- **Health funds:** apart from the offer wording ("gap free with any health fund"), no preferred-provider or HICAPS information is published. The new site makes no HICAPS or preferred-provider claims.
- **Free kids dentistry:** listed on the homepage with no eligibility details. The new site repeats it and asks patients to check eligibility. It **does not** claim Child Dental Benefits Schedule (CDBS) participation.

## 6. Pages written for the new site without an official equivalent

| Page | Basis |
|---|---|
| `new-patients.html` | Official check-up page ("we welcome all new patients of all ages…"), offer, homepage comfort claims. The "what to bring" list is phrased as general suggestions, not practice policy. |
| `faq.html` | Answers drawn from official facts above, plus general information from healthdirect/ADA. No invented policies, fees, cancellation rules or health-fund affiliations. |
| `privacy-policy.html` | **Draft only**, visibly labelled and `noindex`. It describes only how this static website handles data. The practice must complete and approve it. |
| `about.html` | Official About page is empty, so it is built from the homepage copy. |

## 7. Issues found on the official site

1. **Blog post references another practice.** `/blog/why-choose-dental-implants` ends: "call our friendly staff at South Cairns Dental in Edmonton on 4055 4214". The new site reproduces the article (title, author, date, content) but replaces this call to action with Redlynch Dental's own number, and says so in an editor's note. **Confirm with the practice that this is acceptable.**
2. The blog post says the implant screw "is inserted into the gum"; the new site says "placed in the jaw" (clinically accurate), noted in the editor's note.
3. The old site's meta descriptions use "Call 4039 4720" without the area code; the new site uses (07) 4039 4720 throughout.
4. The official About page has no content.
5. The old site's URLs differ from the new file names (see README, "Redirects").

## 8. General (non-practice) sources consulted

All URLs were checked and returned HTTP 200 on 26 October 2026. The exact text was checked for children's toothpaste guidance and first dental visit timing (healthdirect) and for mouthguard protection (ADA).

- healthdirect: tooth decay, gum disease, tooth abscess, dry socket, dental problems, dental check-up, dental fillings, root canal treatment, teeth whitening, veneers, dental bridge, dental implant, dentures, tooth extraction, looking after your baby or child's teeth.
- Australian Dental Association (teeth.org.au): crowns, bridges and veneers; dentures; fillings; root canal treatment; sports mouthguards; teeth straightening and braces; teeth whitening; wisdom teeth.
- Ahpra: Cosmetic surgery hub (consumer information for cosmetic procedures).
- Queensland: 13 HEALTH (13 43 25 84) is given as the Queensland health advice line on the emergency page.

## 9. Still to verify with the practice before launch

- [ ] New patient offer is current, and its full T&Cs
- [ ] Wisdom tooth price range ($200–$400) is still current
- [ ] "Free kids dentistry": eligibility and what it covers
- [ ] Complimentary implant consultation still offered
- [ ] Payment plan provider(s), if they want them named
- [ ] Dentists' Ahpra registration details; whether Ella has a qualification to list
- [ ] Facebook page URL still active
- [ ] Facial injectables page wording reviewed for Ahpra/TGA advertising compliance (see README)
- [ ] Blog post call-to-action change approved
- [ ] Privacy policy completed and approved
- [ ] Licences for stock images taken from the official site cover use on the new site (see IMAGE-MANIFEST)

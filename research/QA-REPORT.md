# QA report

**Date:** 26 October 2026 · **Build:** staging (`node _build/build.js`)
**Browser used for testing:** Google Chrome (installed), driven headless by Playwright, loading the pages from disk (`file://`).

## Automated checks (all 27 pages)

| Check | Result |
|---|---|
| Every required page exists (home, about, team, new patients, services, 15 service pages, emergency, FAQ, blog, contact, privacy) | ✅ 27 pages generated |
| Internal links (`href`/`src`) point to existing files | ✅ 0 missing |
| In-page anchors (`page.html#id`) exist | ✅ 0 missing |
| Every `<img>` has `alt` | ✅ |
| Exactly one `<h1>` per page | ✅ |
| Unique `<title>` and meta description per page | ✅ 0 duplicates |
| JSON-LD parses as valid JSON | ✅ |
| No placeholder or lorem text, no Coral Coast / Bargara / Coral Coast phone number | ✅ 0 hits |
| JavaScript errors (`pageerror` + `console.error`) on load, desktop 1440 px and mobile 390 px | ✅ 0 errors on 54 page loads |
| Failed network requests | ✅ none |
| Horizontal overflow at 1440 px and 390 px | ✅ none (a 6 px overflow from the off-canvas drawer was found and fixed) |
| External source links (healthdirect ×15, teeth.org.au ×8, Ahpra) | ✅ all HTTP 200 |
| Online booking URL (Centaur) | ✅ HTTP 200 |
| Coral Coast reference project unmodified | ✅ `git status` in `coral-coast-html` is clean; no files were written there |

## Interaction tests (Chrome, scripted)

| Test | Result |
|---|---|
| Services mega-menu opens on hover; `aria-expanded` updates; all 15 service links visible | ✅ |
| About dropdown opens with Enter; Escape closes and returns focus to the button | ✅ |
| ArrowDown from the Services button moves focus into the menu | ✅ |
| Visible focus outline on keyboard focus | ✅ |
| FAQ accordion opens and closes (animated) | ✅ |
| Mobile drawer opens from the burger (touch), current section auto-expanded | ✅ |
| Mobile submenu toggles on tap | ✅ |
| Escape closes the mobile drawer | ✅ (bug found and fixed: it previously closed only the open submenu) |
| Emergency page `tel:` links | ✅ all use `tel:+61740394720` (12 links) plus `tel:000` |

## Visual review (screenshots at 1440 px and 390 px)

Reviewed: home (full), emergency (desktop + mobile), dental implants, services, our team, extractions, facial injectables, contact, FAQ, the mega-menu and the mobile drawer.

Fixed during review:
- Desktop nav labels wrapped onto two lines → `nowrap`, tighter spacing and a phone-number-only icon below 1360 px.
- Mega-menu was sized to its button (CSS specificity) → now spans the header width.
- Eyebrow labels had low contrast on dark-green heroes → switched to lime.
- Icon-only hero art looked cartoonish → replaced with editorial type graphics.
- Staging badge overlapped content on phones → hidden below 700 px.

## Not tested / limitations

- **Real devices and Safari/Firefox were not tested**, only Chrome (desktop and mobile emulation).
- **Screen readers were not tested.** The ARIA and keyboard behaviour was checked by script only.
- **No Lighthouse or performance audit was run.** The images total 1.4 MB across the whole site.
- **The contact form has no backend.** It opens the visitor's email app; see README §7.
- **The Google map embed** loads on demand. The embed URL is copied from the official contact page and was not visually checked in the test run (offline-safe fallback shown).
- **Page tests used `file://`**, not a web server. Relative paths make the two equivalent, but redirects and the 404 page need checking on the real host.

## Still needs verification by the practice

See `research/official-site-research.md` §9 (offer currency, wisdom tooth pricing, free kids dentistry eligibility, Ahpra details, unnamed staff in the group photo, injectables wording, privacy policy, stock image licences, blog CTA change).

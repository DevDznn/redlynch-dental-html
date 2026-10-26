/* Redlynch Dental site generator: shared facts, icons, helpers and page layout.
   Node.js only, no dependencies. Run from the project root: node _build/build.js */
'use strict';

const SITE_URL = 'https://www.redlynchdental.com.au/';

/* Verified from https://www.redlynchdental.com.au/ on 26 Oct 2026 (see research/practice-facts.json). */
const F = {
  name: 'Redlynch Dental',
  legalName: 'Chelsea Lane Dental Pty Ltd',
  phone: '(07) 4039 4720',
  tel: 'tel:+61740394720',
  telIntl: '+61740394720',
  email: 'reception@redlynchdental.com.au',
  street: '4/2 Chelsea Lane',
  suburb: 'Redlynch',
  state: 'QLD',
  postcode: '4870',
  booking: 'https://www.centaurportal.com/d4w/org-2836/search?practiceID=3243',
  facebook: 'https://www.facebook.com/Redlynch-Dental-102113211727578',
  mapsLink: 'https://www.google.com/maps/search/?api=1&query=Redlynch+Dental+4%2F2+Chelsea+Lane+Redlynch+QLD+4870',
  mapEmbed: 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7635.351081263452!2d145.69347367458835!3d-16.891937453509275!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6978656ebcfc004d%3A0x465a2fade70d802a!2sRedlynch%20Dental!5e0!3m2!1sen!2sau!4v1611892217850!5m2!1sen!2sau',
  hours: [
    ['Monday', 1, '8:00am – 5:00pm', '08:00', '17:00'],
    ['Tuesday', 2, '8:00am – 5:00pm', '08:00', '17:00'],
    ['Wednesday', 3, '8:00am – 5:00pm', '08:00', '17:00'],
    ['Thursday', 4, '8:00am – 5:00pm', '08:00', '17:00'],
    ['Friday', 5, '8:00am – 4:30pm', '08:00', '16:30'],
    ['Saturday', 6, 'Closed'],
    ['Sunday', 0, 'Closed'],
  ],
  suburbs: ['Redlynch', 'Brinsmead', 'Edge Hill', 'Smithfield', 'Surrounding Cairns suburbs'],
};
F.address = `${F.street}, ${F.suburb} ${F.state} ${F.postcode}`;

const RISK = 'Any surgical or invasive procedure carries risks. Before proceeding, you should seek a second opinion from an appropriately qualified health practitioner.';

/* ---------- Icons (24×24, stroke) ---------- */
const P = {
  phone: '<path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.9.6 2.8.7a2 2 0 0 1 1.7 2z"/>',
  mail: '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/>',
  pin: '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>',
  clock: '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>',
  arrow: '<path d="M5 12h14M13 6l6 6-6 6"/>',
  ext: '<path d="M15 3h6v6M10 14 21 3M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>',
  chev: '<path d="m6 9 6 6 6-6"/>',
  alert: '<path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"/><path d="M12 9v4M12 17h.01"/>',
  tooth: '<path d="M12 5.5c-1.5-1.5-4-2-5.5-.5S4 9 5 12c1 3 1.5 8 3 8s1.5-4 4-4 2.5 4 4 4 2-5 3-8 1-5.5-.5-7-4-1-5.5.5z"/>',
  smile: '<circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2M9 9h.01M15 9h.01"/>',
  child: '<circle cx="12" cy="7" r="4"/><path d="M5.5 21a6.5 6.5 0 0 1 13 0"/><path d="M10 7h.01M14 7h.01"/>',
  shield: '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
  sparkle: '<path d="M12 3v4M12 17v4M3 12h4M17 12h4M5.6 5.6l2.8 2.8M15.6 15.6l2.8 2.8M5.6 18.4l2.8-2.8M15.6 8.4l2.8-2.8"/>',
  crown: '<path d="m3 8 4 4 5-7 5 7 4-4-2 11H5z"/>',
  implant: '<path d="M8 3h8l-1 5H9z"/><path d="M10 8v3l-1 1 2 1-2 1 2 1-2 1 3 4 3-4-2-1 2-1-2-1 2-1-1-1V8"/>',
  denture: '<path d="M3 10c0-3 4-5 9-5s9 2 9 5"/><path d="M3 10c0 5 4 9 9 9s9-4 9-9"/><path d="M7 10v3M10 10v4M14 10v4M17 10v3"/>',
  extract: '<path d="M9 3 5 14l3 7M15 3l4 11-3 7"/><path d="M7 10h10"/>',
  filling: '<path d="M12 5.5c-1.5-1.5-4-2-5.5-.5S4 9 5 12c1 3 1.5 8 3 8s1.5-4 4-4 2.5 4 4 4 2-5 3-8 1-5.5-.5-7-4-1-5.5.5z"/><circle cx="12" cy="10" r="2.2"/>',
  aligner: '<path d="M4 9c0-2 3.5-4 8-4s8 2 8 4v3c0 4-3.5 7-8 7s-8-3-8-7z"/><path d="M8 9.5h8"/>',
  guard: '<path d="M3 9c3-3 15-3 18 0l-1.5 6c-2 3-13 3-15 0z"/><path d="M8 11.5h8"/>',
  root: '<path d="M12 5.5c-1.5-1.5-4-2-5.5-.5S4 9 5 12c1 3 1.5 8 3 8s1.5-4 4-4 2.5 4 4 4 2-5 3-8 1-5.5-.5-7-4-1-5.5.5z"/><path d="M10 11l-.5 6M14 11l.5 6"/>',
  sun: '<circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/>',
  wisdom: '<rect x="3" y="5" width="18" height="14" rx="3"/><path d="M7 9h2M11 9h2M15 9h2M7 15h2M11 15h2M15 15h2" stroke-width="2.4"/>',
  face: '<path d="M12 2C7 2 5 6 5 10c0 5 3 10 7 10s7-5 7-10c0-4-2-8-7-8z"/><path d="M9 10h.01M15 10h.01M10 15c1 .7 3 .7 4 0"/>',
  veneer: '<path d="M5 6c2-1 4-1 7-1s5 0 7 1l-1 9c-.5 3-2 4-6 4s-5.5-1-6-4z"/><path d="M9 6v12M15 6v12"/>',
  bridge: '<path d="M2 9h20"/><path d="M4 9v6c0 2 1 4 2 4s1.5-3 2-3M20 9v6c0 2-1 4-2 4s-1.5-3-2-3M9 9v5c0 1.5 1.3 2 3 2s3-.5 3-2V9"/>',
  calendar: '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
  wallet: '<rect x="2" y="6" width="20" height="14" rx="2"/><path d="M2 10h20M16 15h2"/>',
  heart: '<path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1.1L12 21l7.8-7.5 1-1.1a5.5 5.5 0 0 0 0-7.8z"/>',
  music: '<path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/>',
  film: '<rect x="2" y="3" width="20" height="18" rx="2"/><path d="M7 3v18M17 3v18M2 8h5M2 16h5M17 8h5M17 16h5M2 12h20"/>',
  users: '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.9M16 3.1a4 4 0 0 1 0 7.8"/>',
  tag: '<path d="M20.6 13.4 13.4 20.6a2 2 0 0 1-2.8 0L2 12V2h10l8.6 8.6a2 2 0 0 1 0 2.8z"/><circle cx="7" cy="7" r="1.5"/>',
  cpu: '<rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M9 1v3M15 1v3M9 20v3M15 20v3M20 9h3M20 14h3M1 9h3M1 14h3"/>',
  moon: '<path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/>',
  check: '<path d="M20 6 9 17l-5-5"/>',
  info: '<circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>',
  bolt: '<path d="M13 2 3 14h9l-1 8 10-12h-9z"/>',
  swelling: '<circle cx="12" cy="12" r="9"/><path d="M8 13c1.5 2 6.5 2 8 0M9 9h.01M15 9h.01"/><path d="M18.5 7.5c1.5 1 2 3 1.5 4.5"/>',
  broken: '<path d="M12 5.5c-1.5-1.5-4-2-5.5-.5S4 9 5 12c1 3 1.5 8 3 8s1.5-4 4-4 2.5 4 4 4 2-5 3-8 1-5.5-.5-7-4-1-5.5.5z"/><path d="m12 5.5-1.5 3 2.5 1.5-2 3"/>',
  hand: '<path d="M18 11V6a2 2 0 0 0-4 0v5M14 10V4a2 2 0 0 0-4 0v6M10 10.5V6a2 2 0 0 0-4 0v8"/><path d="M18 8a2 2 0 1 1 4 0v6a8 8 0 0 1-8 8h-2c-2.8 0-4.5-.9-6-2.4l-3.6-3.6a2 2 0 0 1 2.8-2.8L7 15"/>',
  book: '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20V3H6.5A2.5 2.5 0 0 0 4 5.5z"/><path d="M4 19.5V21h16"/>',
  leaf: '<path d="M11 20A7 7 0 0 1 4 13c0-6 6-9 16-10-1 10-4 16-9 17z"/><path d="M4 21c4-4 7-7 10-11"/>',
  search: '<circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/>',
  x: '<path d="M18 6 6 18M6 6l12 12"/>',
};
const ico = (n, cls = '') => `<svg${cls ? ` class="${cls}"` : ''} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">${P[n] || P.tooth}</svg>`;

/* ---------- Helpers ---------- */
const esc = s => String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
const strip = s => String(s).replace(/<[^>]+>/g, '').replace(/\s+/g, ' ').trim();
const ARROW = ico('arrow', 'arr');
const callBtn = (cls = '', label = `Call ${F.phone}`) => `<a class="btn ${cls}" href="${F.tel}">${ico('phone')} ${label}</a>`;
const bookBtn = (cls = '', label = 'Book online') => `<a class="btn ${cls}" href="${F.booking}" target="_blank" rel="noopener">${label} ${ARROW}<span class="sr-only"> (opens the practice's online booking system in a new tab)</span></a>`;

function faqList(items) {
  return `<div class="faq" data-reveal-group>${items.map(([q, a]) => `<details><summary>${q}</summary><div class="ans">${a.startsWith('<') ? a : `<p>${a}</p>`}</div></details>`).join('')}</div>`;
}
const faqLd = items => ({ '@context': 'https://schema.org', '@type': 'FAQPage', mainEntity: items.map(([q, a]) => ({ '@type': 'Question', name: strip(q), acceptedAnswer: { '@type': 'Answer', text: strip(a) } })) });
const crumbsLd = trail => ({ '@context': 'https://schema.org', '@type': 'BreadcrumbList', itemListElement: trail.map(([n, u], i) => ({ '@type': 'ListItem', position: i + 1, name: n, item: SITE_URL + (u === 'index.html' ? '' : u) })) });
const crumbs = trail => `<nav class="crumbs" aria-label="Breadcrumb">${trail.map(([n, u], i) => i === trail.length - 1 ? `<span aria-current="page">${n}</span>` : `<a href="${u}">${n}</a><span aria-hidden="true">/</span>`).join('')}</nav>`;

function hoursRows(cls = 'hours') {
  return `<table class="${cls}"><caption class="sr-only">Opening hours</caption><tbody>${F.hours.map(([d, n, t]) => `<tr data-day="${n}"><th scope="row">${d}</th><td>${t}</td></tr>`).join('')}</tbody></table>`;
}
const hoursMini = (cls = 'hours-mini') => `<table class="${cls}"><tbody><tr><td>Mon – Thu</td><td>8am – 5pm</td></tr><tr><td>Friday</td><td>8am – 4:30pm</td></tr><tr><td>Sat – Sun</td><td>Closed</td></tr></tbody></table>`;

/* Shared sidebar for service pages */
function sideBar(opts = {}) {
  return `<aside class="side" aria-label="Book and contact">
 <div class="side-box${opts.em ? ' em' : ''}"><h3>${opts.em ? 'Call us now' : 'Book an appointment'}</h3><p>${opts.em ? 'Appointments are reserved every day for dental emergencies.' : 'Call our Redlynch team, or book online at a time that suits you.'}</p>${callBtn(opts.em ? 'white' : '', F.phone)}${opts.em ? '' : bookBtn('ghost-light')}</div>
 <div class="side-box light"><h3>Opening hours</h3>${hoursMini()}<p class="fine" style="margin-top:12px">After-hours and weekend appointments are available at request.</p></div>
 <div class="side-box light"><h3>Find us</h3><p style="margin-bottom:8px">${F.street}<br>${F.suburb} ${F.state} ${F.postcode}</p><a class="text-link" href="contact.html">Map and directions ${ico('arrow')}</a></div>
</aside>`;
}

/* ---------- Navigation model ---------- */
// services is injected by build.js (setServices) so the menu and pages share one list.
let SERVICES = [];
const setServices = s => { SERVICES = s; };

function navHtml(page) {
  const cur = page.file;
  const sec = page.section;
  const a = (href, label, extra = '') => `<a href="${href}"${href === cur ? ' aria-current="page"' : ''}${extra}>${label}</a>`;
  const li = (key, inner, dd = false) => `<li class="${dd ? 'has-dd' : ''}${dd && key === 'services' ? ' has-mega' : ''}${sec === key ? ' is-active' : ''}">${inner}</li>`.replace('class=""', '');
  const ddBtn = (id, label) => `<button class="dd-btn" type="button" aria-expanded="false" aria-controls="${id}">${label}${ico('chev')}</button>`;
  const svcLinks = SERVICES.map(s => `<li><a href="${s.file}"${s.file === cur ? ' aria-current="page"' : ''}${s.slug === 'emergency-dental-care' ? ' class="is-em"' : ''}>${ico(s.icon)}<span>${s.navName}</span></a></li>`).join('');
  return `<nav class="main" id="site-nav" aria-label="Main">
 <div class="nav-mobile-extra">
  <a class="btn ember" href="emergency-dental-care.html">${ico('alert')} Dental emergency?</a>
  ${callBtn('', `Call ${F.phone}`)}
  ${bookBtn('ghost-light')}
 </div>
 <ul>
  ${li('home', a('index.html', 'Home'))}
  ${li('about', `${ddBtn('dd-about', 'About Us')}<ul class="dd" id="dd-about"><li>${a('about.html', 'About Redlynch Dental')}</li><li>${a('our-team.html', 'Meet Our Team')}</li><li>${a('new-patients.html', 'New Patients')}</li></ul>`, true)}
  ${li('services', `${ddBtn('dd-services', 'Services')}<div class="mega" id="dd-services"><div><div class="mega-head"><strong>Our services</strong>${a('services.html', 'View all services →')}</div><ul class="mega-cols"><li class="mobile-only">${a('services.html', `${ico('search')}<span>All services</span>`)}</li>${svcLinks}</ul></div><div class="mega-feature"><span class="em-tag" style="background:rgba(0,0,0,.18);margin:0;align-self:flex-start">${ico('alert')} Emergency</span><strong>In pain right now?</strong><p>We reserve appointments every day for dental emergencies. Call us straight away.</p><a class="btn white sm" href="${F.tel}">${ico('phone')} ${F.phone}</a><a href="emergency-dental-care.html" style="color:#fff;font-weight:700;font-size:.9rem">Emergency dental care →</a></div></div>`, true)}
  ${li('patient', `${ddBtn('dd-patient', 'Patient Info')}<ul class="dd" id="dd-patient"><li>${a('faq.html', 'Frequently Asked Questions')}</li><li>${a('special-offers.html', 'New Patient Offer')}</li><li>${a('emergency-dental-care.html', 'Emergency Dental Care')}</li><li><a href="${F.booking}" target="_blank" rel="noopener">Book Online ↗<span class="sr-only"> (opens in a new tab)</span></a></li></ul>`, true)}
  ${li('blog', a('blog.html', 'Blog'))}
  ${li('contact', a('contact.html', 'Contact Us'))}
 </ul>
</nav>`;
}

/* ---------- Page layout ---------- */
function layout(page, opts) {
  const prod = opts.production;
  const url = SITE_URL + (page.file === 'index.html' ? '' : page.file);
  const robots = page.noindex || !prod ? 'noindex,nofollow' : 'index,follow,max-image-preview:large';
  const ogImg = SITE_URL + (page.ogImage || 'assets/images/team/redlynch-dental-team-group.webp');
  const ld = [dentistLd(), ...(page.ld || [])];
  if (page.trail) ld.push(crumbsLd(page.trail));
  const title = page.title;
  return `<!DOCTYPE html>
<html lang="en-AU">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>${esc(title)}</title>
<meta name="description" content="${esc(page.desc)}">
<meta name="robots" content="${robots}">
<link rel="canonical" href="${url}">
<meta property="og:type" content="${page.ogType || 'website'}">
<meta property="og:site_name" content="Redlynch Dental">
<meta property="og:title" content="${esc(title)}">
<meta property="og:description" content="${esc(page.desc)}">
<meta property="og:url" content="${url}">
<meta property="og:image" content="${ogImg}">
<meta property="og:locale" content="en_AU">
<meta name="twitter:card" content="summary_large_image">
<meta name="geo.region" content="AU-QLD">
<meta name="geo.placename" content="Redlynch, Cairns">
<meta name="theme-color" content="#111915">
<link rel="icon" href="assets/images/branding/favicon-32.png" sizes="32x32" type="image/png">
<link rel="icon" href="assets/images/branding/favicon-192.png" sizes="192x192" type="image/png">
<link rel="apple-touch-icon" href="assets/images/branding/apple-touch-icon.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;1,9..144,400&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
<script>document.documentElement.classList.add('js')</script>
${ld.map(o => `<script type="application/ld+json">${JSON.stringify(o)}</script>`).join('\n')}
</head>
<body class="page-${page.file.replace('.html', '')}">
<a class="skip" href="#main">Skip to main content</a>
<div class="topline"><div class="wrap">
 <div class="tl-l">
  <span class="tl-item tl-addr">${ico('pin')}<a href="${F.mapsLink}" target="_blank" rel="noopener">${F.address}</a></span>
  <span class="tl-item tl-hours status" data-status><i></i><span>Mon–Thu 8am–5pm · Fri 8am–4:30pm</span></span>
 </div>
 <div class="tl-r">
  <a class="tl-item tl-em" href="emergency-dental-care.html">${ico('alert')} Dental emergency? Call ${F.phone}</a>
 </div>
</div></div>
<header class="site"><div class="wrap">
 <a class="logo" href="index.html"><img src="assets/images/branding/redlynch-dental-logo-light.png" alt="Redlynch Dental home" width="900" height="215"></a>
 ${navHtml(page)}
 <div class="hdr-actions">
  <a class="hdr-phone" href="${F.tel}" aria-label="Call Redlynch Dental on ${F.phone}">${ico('phone')}<span>${F.phone}</span></a>
  ${bookBtn('sm')}
  <button class="burger" type="button" aria-expanded="false" aria-controls="site-nav"><span class="bars" aria-hidden="true"><span></span></span><span class="lbl">Menu</span></button>
 </div>
</div></header>
<div class="nav-scrim" aria-hidden="true"></div>
<main id="main">
${page.body}
${page.noCta ? '' : ctaBand(page.cta)}
</main>
${footer()}
<div class="mcall">${callBtn('', 'Call us')}${bookBtn('white', 'Book online')}</div>
${prod ? '' : '<div class="staging-flag" aria-hidden="true">Staging build · not indexed</div>'}
<script src="assets/js/practice-data.js" defer></script>
<script src="assets/js/main.js" defer></script>
</body>
</html>
`;
}

function ctaBand(c = {}) {
  return `<section class="cta" aria-labelledby="cta-h"><div class="wrap">
 <div data-reveal><h2 id="cta-h">${c.h || 'Ready when you are.'}</h2><p>${c.p || 'New patients of all ages are welcome. Call our friendly Redlynch team or book online, and we will find a time that suits you.'}</p></div>
 <div class="actions" data-reveal>${callBtn('dark lg', F.phone)}${bookBtn('ghost lg')}</div>
</div></section>`;
}

function footer() {
  const svc = SERVICES.filter(s => s.footer).map(s => `<li><a href="${s.file}">${s.navName}</a></li>`).join('');
  return `<footer class="site"><div class="wrap">
 <div class="cols">
  <div>
   <a class="logo" href="index.html"><img src="assets/images/branding/redlynch-dental-logo-light.png" alt="Redlynch Dental home" width="900" height="215" loading="lazy"></a>
   <p>Gentle and affordable dentistry for families in Redlynch, Brinsmead, Edge Hill, Smithfield and surrounding Cairns suburbs.</p>
   <a class="f-tel" href="${F.tel}">${F.phone}</a><br>
   <a href="mailto:${F.email}">${F.email}</a>
  </div>
  <div><h2>Services</h2><ul>${svc}<li><a href="services.html">All services →</a></li></ul></div>
  <div><h2>Practice</h2><ul>
   <li><a href="about.html">About Redlynch Dental</a></li>
   <li><a href="our-team.html">Meet Our Team</a></li>
   <li><a href="new-patients.html">New Patients</a></li>
   <li><a href="special-offers.html">New Patient Offer</a></li>
   <li><a href="faq.html">FAQs</a></li>
   <li><a href="blog.html">Blog</a></li>
   <li><a href="contact.html">Contact Us</a></li>
   <li><a href="${F.booking}" target="_blank" rel="noopener">Book Online ↗<span class="sr-only"> (opens in a new tab)</span></a></li>
  </ul></div>
  <div><h2>Visit us</h2>
   <p><a href="${F.mapsLink}" target="_blank" rel="noopener">${F.street}<br>${F.suburb} ${F.state} ${F.postcode}</a><br><span style="font-size:.86rem">Within the Redlynch Commercial Hub</span></p>
   <table><tbody><tr><td>Mon – Thu</td><td>8am – 5pm</td></tr><tr><td>Friday</td><td>8am – 4:30pm</td></tr><tr><td>Sat – Sun</td><td>Closed</td></tr></tbody></table>
   <p style="margin-top:14px"><a href="${F.facebook}" target="_blank" rel="noopener">Redlynch Dental on Facebook ↗<span class="sr-only"> (opens in a new tab)</span></a></p>
  </div>
 </div>
 <div class="f-bottom">
  <span>© <span data-year>2026</span> ${F.legalName} · Redlynch Dental · <a href="privacy-policy.html">Privacy policy</a> · <a href="sitemap.xml">Sitemap</a></span>
  <p>The information on this website is general in nature and is not a substitute for advice from your dentist. ${RISK}</p>
 </div>
</div></footer>`;
}

function dentistLd() {
  return {
    '@context': 'https://schema.org', '@type': 'Dentist', '@id': SITE_URL + '#dentist',
    name: F.name, legalName: F.legalName, url: SITE_URL,
    logo: SITE_URL + 'assets/images/branding/redlynch-dental-logo-original.png',
    image: SITE_URL + 'assets/images/team/redlynch-dental-team-group.webp',
    telephone: F.telIntl, email: F.email,
    address: { '@type': 'PostalAddress', streetAddress: F.street, addressLocality: F.suburb, addressRegion: F.state, postalCode: F.postcode, addressCountry: 'AU' },
    areaServed: ['Redlynch', 'Brinsmead', 'Edge Hill', 'Smithfield', 'Cairns'],
    hasMap: F.mapsLink,
    sameAs: [F.facebook],
    openingHoursSpecification: [
      { '@type': 'OpeningHoursSpecification', dayOfWeek: ['Monday', 'Tuesday', 'Wednesday', 'Thursday'], opens: '08:00', closes: '17:00' },
      { '@type': 'OpeningHoursSpecification', dayOfWeek: 'Friday', opens: '08:00', closes: '16:30' },
    ],
    employee: [
      { '@type': 'Person', name: 'Dr Alex Bornsztejn', jobTitle: 'Principal Dentist' },
      { '@type': 'Person', name: 'Dr Nadia Mullins', jobTitle: 'Dentist' },
      { '@type': 'Person', name: 'Dr Julian Fung', jobTitle: 'Dentist' },
    ],
  };
}

module.exports = { SITE_URL, F, RISK, ico, esc, strip, ARROW, callBtn, bookBtn, faqList, faqLd, crumbs, crumbsLd, hoursRows, hoursMini, sideBar, layout, setServices, SERVICES: () => SERVICES };

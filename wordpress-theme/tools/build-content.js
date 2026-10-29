/* Redlynch Dental: generates the WordPress theme's starter content from the static site's own
   source (_build/lib.js, _build/pages.js, _build/services.js), so the WordPress pages carry
   exactly the verified text of the static site. Where the finished HTML pages were edited by hand
   after the build, the HTML is followed: service articles are read from the .html files, and the
   campaign landing pages (cosmetic-dentistry.html, full-arch-dental-implants.html) are converted
   from their HTML.

   Usage (from the repository root):  node wordpress-theme/tools/build-content.js
   Output: wordpress-theme/redlynch-dental/inc/import/content.json

   Each page becomes Elementor data built from native Elementor containers (layout), native
   Heading / Text Editor / Image widgets (headings, paragraphs, lists, photos) and the theme's
   Redlynch widgets where native widgets can't reproduce a component (cards, FAQ, hours, map …).
   Placeholders resolved by the importer on the WordPress site:
     rd-page:<slug>      → page permalink        rd-post:<slug> → post permalink
     rd-home             → home URL              rd-id:<slug>   → page ID (service pickers)
     {"rd_media": "<path under assets/images>"} → Media Library image {id, url}
     rd-media:<path>     → Media Library image URL (inside HTML)
   No dependencies. Content already imported into a site is not affected by re-running this. */
'use strict';
const fs = require('fs');
const path = require('path');
const crypto = require('crypto');

const ROOT = path.resolve(__dirname, '..', '..');
const L = require(path.join(ROOT, '_build', 'lib.js'));
const Pg = require(path.join(ROOT, '_build', 'pages.js'));
const { services, CATS } = require(path.join(ROOT, '_build', 'services.js'));
L.setServices(services);
const { F, RISK } = L;

/* ------------------------------------------------------------------ helpers */
let seq = 0;
const eid = () => crypto.createHash('md5').update('rd' + (++seq)).digest('hex').slice(0, 7);
const byslug = Object.fromEntries(services.map(s => [s.slug, s]));
const ICON_BY_SVG = {};
Object.keys(require('fs').readFileSync(path.join(ROOT, '_build', 'lib.js'), 'utf8').match(/const P = \{([\s\S]*?)\n\};/)[1].match(/^\s*([a-z0-9]+):/gm).reduce((o, k) => (o[k.trim().slice(0, -1)] = 1, o), {}))
  .forEach(n => { ICON_BY_SVG[L.ico(n)] = n; });
const iconOf = html => { const m = String(html).match(/<svg[\s\S]*?<\/svg>/); return m ? (ICON_BY_SVG[m[0]] || 'tooth') : ''; };
const stripSvg = html => String(html).replace(/<svg[\s\S]*?<\/svg>\s*/g, '');

/* Links and image paths → importer placeholders. */
function links(html) {
  return String(html)
    .replace(/href="index\.html"/g, 'href="rd-home"')
    .replace(/href="blog-why-choose-dental-implants\.html"/g, 'href="rd-post:why-choose-dental-implants"')
    .replace(/href="([a-z0-9-]+)\.html(#[^"]*)?"/g, (m, slug, hash) => `href="rd-page:${slug}${hash || ''}"`)
    .replace(/src="assets\/images\/([^"]+)"/g, 'src="rd-media:$1"');
}
const url = (u, ext = false) => ({ url: links(`href="${u}"`).slice(6, -1), is_external: ext ? 'on' : '', nofollow: '' });
const pageUrl = slug => ({ url: slug === 'home' ? 'rd-home' : `rd-page:${slug}`, is_external: '', nofollow: '' });
const media = p => ({ rd_media: p.replace(/^assets\/images\//, '') });

/* Elementor elements. Containers are "full width" (no inner wrapper) and carry the static site's
   layout classes; every container also has "rd", which the theme CSS uses to neutralise
   Elementor's default container padding, gap and flex layout. */
function con(classes, children, opts = {}) {
  const settings = { content_width: 'full', css_classes: ('rd ' + (classes || '')).trim() };
  if (opts.tag) settings.html_tag = opts.tag;
  if (opts.id) settings._element_id = opts.id;
  return { id: eid(), elType: 'container', isInner: !!opts.inner, settings, elements: children.filter(Boolean) };
}
const widget = (type, settings, classes) => ({ id: eid(), elType: 'widget', widgetType: type, isInner: false, settings: classes ? { ...settings, _css_classes: classes } : settings, elements: [] });
const heading = (title, tag = 'h2', classes) => widget('heading', { title, header_size: tag }, classes);
const text = (html, classes) => widget('text-editor', { editor: links(html) }, classes);
const eyebrow = t => text(`<p class="eyebrow">${t}</p>`);
const image = (file, classes) => widget('image', { image: media(file), image_size: 'full' }, classes);
const rep = arr => arr.map(o => ({ _id: eid(), ...o }));

/* A static button (bookBtn / callBtn) as a Redlynch Buttons item. */
function btn(kind, cls = '', label = '', link, icon = '') {
  const parts = cls.split(/\s+/).filter(Boolean);
  const size = parts.find(p => p === 'sm' || p === 'lg') || '';
  const style = parts.find(p => p !== 'sm' && p !== 'lg') || '';
  const o = { kind, label, style, size, icon, arrow: '' };
  if (link) o.link = link;
  return o;
}
const book = (cls = '', label = '') => btn('book', cls, label === 'Book online' ? '' : label);
const call = (cls = '', label = '') => btn('call', cls, String(label).replace(F.phone, '{phone}'));
const linkBtn = (slug, label, cls = '', arrow = false) => ({ ...btn('link', cls, label, pageUrl(slug)), arrow: arrow ? 'yes' : '' });
const buttons = (items, spacing = '', wrap = 'actions', classes) => widget('rd-buttons', { buttons: rep(items), spacing, wrap }, classes);

/* Section building blocks. */
const section = (cls, children, opts = {}) => con(('section ' + cls).trim(), [con('wrap' + (opts.narrow ? ' narrow' : ''), children, { inner: true })], { tag: 'section', id: opts.id });
const bare = w => con('rd-bare', [w]);
function secHead({ eb, h, p, center = false, row = false, btns = null, reveal = true }) {
  const inner = [eb && eyebrow(eb), h && heading(h, 'h2'), p && text(`<p>${p}</p>`)].filter(Boolean);
  if (row) return con('sec-head row' + (reveal ? ' rd-reveal' : ''), [con('', inner, { inner: true }), btns], { inner: true });
  return con('sec-head' + (center ? ' center' : '') + (reveal ? ' rd-reveal' : ''), inner, { inner: true });
}
const faqWidget = (items, extra = {}) => widget('rd-faq', { ...extra, items: rep(items.map(([q, a]) => ({ question: q.replace(/<[^>]+>/g, ''), answer: links(a.startsWith('<') ? a : `<p>${a}</p>`) }))) });
const cta = c => bare(widget('rd-cta', { heading: (c && c.h) || '', text: (c && c.p) ? c.p.replace(F.phone, '{phone}') : '' }));
const crumbParents = trail => rep(trail.slice(1, -1).map(([n, u]) => ({ label: n, link: url(u) })));

/* Minimal HTML reader for the generated service bodies (well-formed by construction). */
const VOID = new Set(['img', 'br', 'hr', 'input', 'wbr', 'meta', 'link', 'source', 'path', 'circle', 'rect']);
function topLevel(html) {
  const out = []; const re = /<(\/?)([a-zA-Z0-9]+)([^>]*?)(\/?)>/g; let depth = 0, start = -1, tag = '', attrs = '', m;
  while ((m = re.exec(html))) {
    const [all, close, name, at, selfClose] = m; const n = name.toLowerCase();
    if (!close) {
      if (depth === 0) { start = m.index; tag = n; attrs = at; }
      if (!VOID.has(n) && !selfClose) depth++;
      else if (depth === 0) out.push({ tag, attrs, outer: all, inner: '' });
    } else {
      depth--;
      if (depth === 0) { const outer = html.slice(start, m.index + all.length); out.push({ tag, attrs, outer, inner: outer.slice(outer.indexOf('>') + 1, outer.length - all.length) }); }
    }
  }
  return out;
}
const cls = n => ((n.attrs.match(/class="([^"]*)"/) || [])[1] || '');

/* Static service-body HTML → Elementor widgets (prose column). */
function bodyWidgets(html) {
  const out = []; let buf = [];
  const flush = () => { if (buf.length) { out.push(text(buf.join(''))); buf = []; } };
  for (const n of topLevel(html)) {
    const c = cls(n);
    if (n.tag === 'h2') { flush(); out.push(heading(n.inner, 'h2')); continue; }
    if (n.tag === 'div' && c === 'from-practice') {
      flush();
      const label = (n.inner.match(/<span class="label">([\s\S]*?)<\/span>/) || [])[1] || '';
      out.push(widget('rd-callout', { style: 'from-practice', label, content: links(n.inner.replace(/<span class="label">[\s\S]*?<\/span>/, '')) }));
      continue;
    }
    if (n.tag === 'div' && /^callout/.test(c)) {
      flush();
      const style = c.replace(/\s*mt-2\s*/, '').trim();
      // A small button at the end of the callout ("Cosmetic dentistry options →").
      const b = n.inner.match(/<a class="btn sm" href="([^"]+)">([\s\S]*?)<\/a>\s*$/);
      const extra = b ? { button_label: stripSvg(b[2]).trim(), button_link: url(b[1]) } : {};
      out.push(widget('rd-callout', { style, content: links(b ? n.inner.slice(0, b.index) : n.inner), spacing: /mt-2/.test(c) ? 'mt-2' : '', ...extra }));
      continue;
    }
    if (n.tag === 'ol' && /num-list/.test(c)) {
      flush();
      const items = [...n.inner.matchAll(/<li><div><h3>([\s\S]*?)<\/h3><p>([\s\S]*?)<\/p><\/div><\/li>/g)].map(x => ({ title: x[1], text: links(x[2]) }));
      out.push(widget('rd-steps', { layout: 'numbered', items: rep(items) }));
      continue;
    }
    if (n.tag === 'ol' && /timeline/.test(c)) {
      flush();
      const items = [...n.inner.matchAll(/<li><h3>([\s\S]*?)<\/h3><p>([\s\S]*?)<\/p><\/li>/g)].map(x => ({ title: x[1], text: links(x[2]) }));
      out.push(widget('rd-steps', { layout: 'timeline', items: rep(items) }));
      continue;
    }
    if (n.tag === 'div' && /^grid /.test(c)) {
      flush();
      const items = topLevel(n.inner).map(card => {
        const icon = /<div class="ic">/.test(card.inner) ? iconOf(card.inner) : '';
        const inner = stripSvg(card.inner).replace(/<div class="ic"><\/div>/, '');
        const title = (inner.match(/<h3>([\s\S]*?)<\/h3>/) || [])[1] || '';
        return { icon, title, text: links(inner.replace(/<h3>[\s\S]*?<\/h3>/, '').trim()), extra: '', tone: 'dent' };
      });
      out.push(widget('rd-icon-cards', { layout: 'cards', columns: c.split(' ')[1], items: rep(items) }));
      continue;
    }
    if (n.tag === 'p' && c === 'risk') { buf.push(n.outer); continue; }
    // Paragraphs, lists, figures, image pairs and tables stay as HTML in a Text Editor.
    buf.push(n.outer);
  }
  flush();
  return out;
}

/* ------------------------------------------------------------------ pages */
const HOME_FAQS = [
  ['Are you taking new patients?', 'Yes. Redlynch Dental welcomes new patients of all ages. If it is your first visit, we recommend starting with a thorough consultation and dental clean. <a href="new-patients.html">What to expect as a new patient</a>.'],
  ['What should I do in a dental emergency?', `Call us straight away on <a href="${F.tel}">${F.phone}</a>. We reserve specific appointments every day for dental emergencies and will always try to see you as soon as possible. <a href="emergency-dental-care.html">Emergency dental care</a>.`],
  ['Can I book online?', `Yes. You can <a href="${F.booking}" target="_blank" rel="noopener">book online</a> through the practice’s booking system, or call ${F.phone} and our team will find a time for you.`],
  ['What payment options do you offer?', `Payment options include ${F.payList}. Eligibility and terms are set by each provider. <a href="payments-and-fees.html">Payments and fees</a>.`],
  ['What are your opening hours?', Pg.HOURS_TEXT],
  ['I’m nervous about visiting the dentist. Can you help?', 'Yes. The team helps many anxious patients overcome their worries and complete their treatment. Let us know what music you would like, or choose a movie to watch during your treatment, and tell us about any concerns when you book.'],
];
const OFFER_TICKS = ['Comprehensive dental check-up', 'All necessary X-rays, including full-face X-rays', 'Scale, clean and polish', 'Fluoride treatment'];
/* The offer photo and prices are the static site's (_build/pages.js OFFER_IMG and PRICES). */
const PRICES = [...Pg.PRICES.matchAll(/<div><b>([\s\S]*?)<\/b><span>([\s\S]*?)<\/span><\/div>/g)].map(m => ({ big: m[1], small: m[2] }));
const offer = o => widget('rd-offer', { image: media(Pg.OFFER_IMG), lazy: o.lazy === false ? '' : 'yes', eyebrow: o.eyebrow, title: o.title, title_tag: o.tag || 'h2', intro: o.intro || '', ticks: (o.ticks || []).join('\n'), prices: rep(PRICES), fine: o.fine, buttons: rep(o.buttons) });

function home() {
  const p = Pg.home();
  const cards = [['emergency-dental-care', '62% 40%'], ['check-ups-and-dental-cleans', '62% 30%'], ['childrens-dentistry', '60% 40%'], ['dental-implants', '50% 50%'], ['invisalign', '50% 50%'], ['teeth-whitening', '28% 40%'], ['crowns-and-bridges', '50% 50%'], ['wisdom-teeth', '50% 50%']];
  const els = [
    con('section hero-home', [con('wrap', [con('hero-grid', [
      con('hero-copy', [
        eyebrow('Family dentist in Redlynch, Cairns'),
        heading('Gentle, affordable dentistry <em>for the whole family</em>', 'h1'),
        text('<p class="lead">A modern dental practice in the Redlynch Commercial Hub, caring for patients of all ages, from children’s check-ups to implants, clear aligners and cosmetic dentistry.</p>'),
        buttons([book('lg', 'Book an Appointment'), call('ghost lg', `Call ${F.phone}`)]),
        text('<ul class="hero-facts"><li><b>3 dentists</b> caring for local families</li><li><b>Online booking</b> at a time that suits you</li></ul>'),
      ], { inner: true }),
      con('hero-media', [
        image('hero/smiling-mother-and-daughter.webp', 'rd-focus-30-40'),
        widget('rd-visit-card', { title: 'Book your visit', rows: rep([['Emergency appointments', 'Reserved daily'], ['New patients', 'All ages welcome'], ['Eligible children', 'CDBS bulk billed'], ['Payment options', 'Afterpay, Zip and more']].map(([label, value]) => ({ label, value }))), button: 'Call {phone}' }),
      ], { inner: true }),
    ], { inner: true })], { inner: true })], { tag: 'section' }),
    bare(widget('rd-link-strip', { items: rep([
      { kind: 'book', icon: 'calendar', title: 'Book online', text: 'Choose a time that suits you' },
      { kind: 'call', icon: 'phone', title: 'Call our practice', text: '{phone}' },
      { kind: 'maps', icon: 'pin', title: 'Find us', text: '{address}' },
    ]) })),
    section('bg-paper', [
      secHead({ eb: 'Start here', h: 'How can we help you today?', p: 'Choose where you’d like to start, and we’ll take you to the right place.', center: true }),
      widget('rd-path-cards', { label: 'Where to start', items: rep([
        { icon: 'alert', title: 'I’m in pain', text: 'Emergency dental care', link: pageUrl('emergency-dental-care'), em: 'yes' },
        { icon: 'smile', title: 'I’m a new patient', text: 'What to expect', link: pageUrl('new-patients'), em: '' },
        { icon: 'child', title: 'It’s for my child', text: 'Children’s dentistry', link: pageUrl('childrens-dentistry'), em: '' },
        { icon: 'tag', title: 'New patient offer', text: 'Check-up, clean and X-rays', link: pageUrl('special-offers'), em: '' },
      ]) }),
      widget('rd-emergency-panel', { tag: 'Dental emergency', title: 'In pain? We reserve emergency appointments every day.', title_tag: 'h3', text: 'Painful teeth or gums, broken teeth and fillings, facial swelling, trauma, or a broken or lost denture. Problems can get worse quickly, so call us straight away.', buttons: rep([btn('call', 'ember', 'Call {phone}'), linkBtn('emergency-dental-care', 'What to do now', 'ghost', true)]) }),
    ]),
    section('', [con('split', [
      con('rd-reveal', [
        eyebrow('About the practice'),
        heading('The very best treatment, at competitive prices, in a relaxing environment.', 'h2'),
        text('<p>Redlynch Dental caters to all of your family’s dental needs, from children’s dentistry all the way up to high-end cosmetic cases. We take the time to listen, understand what you want to achieve, and guide you down the right path to get there.</p>'),
        widget('rd-icon-cards', { layout: 'features', items: rep([['wallet', 'Affordable pricing', 'Competitive prices and payment options'], ['moon', 'Later appointments', 'Open until 6:30pm on Wednesday and Thursday'], ['heart', 'Caring, gentle staff', 'More than just a job to us'], ['cpu', 'Modern equipment', 'In a relaxing environment']].map(([icon, title, t]) => ({ icon, title, text: t, extra: '', tone: 'dent' }))) }),
        text(`<p class="area-note">Proudly serving ${F.suburbs.slice(0, 4).join(', ')} and surrounding Cairns suburbs.</p>`),
        buttons([linkBtn('about', 'More about the practice', 'ghost', true)]),
      ], { inner: true }),
      image('services/dental-examination.webp', 'media-frame ratio-4-3 rd-reveal rd-focus-64-30'),
    ], { inner: true })]),
    section('bg-paper', [
      secHead({ eb: 'Our services', h: 'Everything your family needs, under one roof', p: 'From routine check-ups and children’s dentistry to implants, clear aligners and same-day emergency care.', row: true, btns: buttons([linkBtn('services', 'Explore all 15 services', 'ghost', true)]) }),
      widget('rd-service-cards', { items: rep(cards.map(([slug, focus]) => ({ service: `rd-id:${slug}`, focus, badge: slug === 'emergency-dental-care' ? 'Same-day care' : '', more: slug === 'emergency-dental-care' ? 'What to do now' : 'Learn more' }))) }),
    ]),
    section('bg-forest', [con('split top', [
      con('rd-reveal', [
        eyebrow('Our approach'),
        heading('We listen first, then help you get where you want to be.', 'h2'),
        text('<p class="lead">Dental visits shouldn’t feel rushed or intimidating. We take the time to understand what matters to you, explain your options and costs clearly, and make your visit as comfortable as we can.</p>'),
        buttons([book('', 'Book an Appointment')], 'mt-1'),
      ], { inner: true }),
      widget('rd-icon-cards', { layout: 'comfort', items: rep([
        ['music', 'Your choice of music or a movie', 'Let us know what music we can play for you, or choose a movie to watch while your treatment is completed.'],
        ['heart', 'Care for anxious patients', 'We help many anxious patients overcome their apprehensions and complete their dental treatment, at a pace that suits them.'],
        ['book', 'A clear, printed treatment plan', 'After a comprehensive check-up you receive a printed plan with the most accurate estimate we can give of the time and cost involved.'],
        ['wallet', 'Flexible ways to pay', `Payment options include ${F.payList}, and implant consultations are complimentary. Ask our team which options suit you.`],
      ].map(([icon, title, t]) => ({ icon, title, text: `<p>${t}</p>`, extra: '', tone: 'dent' }))) }),
    ], { inner: true })]),
    section('', [
      secHead({ eb: 'Meet the dentists', h: 'Friendly faces, gentle hands', p: 'Our three dentists care for patients of all ages, from children’s check-ups and cosmetic dentistry to single and full arch dental implants.', row: true, btns: buttons([linkBtn('our-team', 'Meet our team', 'ghost', true)]) }),
      widget('rd-team', { layout: 'cards', link: pageUrl('our-team') }),
      widget('rd-team', { layout: 'support-row' }),
    ]),
    section('bg-sand', [offer({ eyebrow: 'New patient special offer', title: 'New to Redlynch Dental? Start with a thorough check-up.', intro: 'We welcome new patients of all ages. Your first visit includes:', ticks: [...OFFER_TICKS, 'A printed treatment plan'], fine: 'Terms and conditions apply. Ask our team for full details when you book.', buttons: [book('', 'Book an Appointment'), linkBtn('new-patients', 'New patient information', 'ghost')] })]),
    section('bg-paper', [con('split', [
      image('services/childrens-dentistry-smiling-girl.webp', 'media-frame ratio-4-3 rd-reveal rd-focus-62-50'),
      con('rd-reveal', [
        eyebrow('Preventive and family dentistry'),
        heading('Healthy habits start young, and last a lifetime.', 'h2'),
        text('<p>Fun, positive early experiences at the dentist help children grow up without unnecessary dental fears. We bulk bill eligible children under the Child Dental Benefits Schedule (CDBS), so ask our team about eligibility when you book.</p><p>For the whole family, regular check-ups and cleans let us find and treat small problems early, which saves time and money and helps you keep your natural teeth.</p>'),
        buttons(['childrens-dentistry', 'check-ups-and-dental-cleans', 'mouthguards'].map(s => linkBtn(s, byslug[s].navName, s === 'childrens-dentistry' ? 'sm' : 'ghost sm'))),
      ], { inner: true }),
    ], { inner: true })]),
    section('', [con('split top', [
      secHead({ eb: 'Questions', h: 'Things patients often ask us', p: `Can’t see your question? Call us on <a href="${F.tel}">${F.phone}</a>, or read the <a href="faq.html">full FAQ</a>.` }),
      faqWidget(HOME_FAQS),
    ], { inner: true })]),
    section('bg-paper', [
      secHead({ eb: 'Visit us', h: 'Find us in the Redlynch Commercial Hub' }),
      con('contact-grid', [
        widget('rd-contact-cards', { items: rep([{ type: 'address', title: 'Address' }, { type: 'phone', title: 'Phone' }, { type: 'email', title: 'Email', wbr: 'yes' }, { type: 'hours', title: 'Opening hours' }]) }),
        widget('rd-map', {}, 'rd-reveal'),
      ], { inner: true }),
    ]),
    cta(p.cta),
  ];
  return { slug: 'home', title: 'Home', page: p, elements: els, excerpt: p.desc };
}

function about() {
  const p = Pg.about();
  const els = [
    bare(widget('rd-page-hero', { tone: 'forest', crumbs: 'yes', parents: [], current: 'About Us', eyebrow: 'About Redlynch Dental', title: 'A modern practice with a local, family feel.', lead: 'Redlynch Dental is a modern Cairns dental practice in the Redlynch Commercial Hub, caring for local families with gentle, affordable dentistry.', buttons: [], meta: '{address}\nNew patients of all ages\nEmergency appointments daily', visual: 'image', image: media('services/dental-examination.webp'), alt: 'A dentist examining a smiling patient’s teeth', focus: '64% 30%' })),
    section('bg-paper', [con('split wide-left top', [
      con('rd-reveal', [
        eyebrow('Who we are'),
        heading('More than just a job to us', 'h2'),
        text('<p>We proudly serve the local community of Redlynch, as well as Brinsmead, Edge Hill, Smithfield and the surrounding suburbs of Cairns. We cater to all of your family’s dental needs, from children’s dentistry all the way up to high-end cosmetic cases, and everything in between.</p><p>We take great pride in providing the Quality treatment at competitive prices in the most relaxing environment possible. All of our staff are knowledgeable and passionate about what we do. It is definitely more than just a job to us.</p><p>We take the time to listen to you and understand what you want to achieve, and then help guide you down the right path to get there. Many of our patients say they wish they had come to see us earlier.</p>'),
      ], { inner: true }),
      widget('rd-callout', { style: 'card lime', label: 'At a glance', content: `<ul class="ticks"><li>Located within the Redlynch Commercial Hub, at ${F.street}</li><li>Three dentists, supported by our practice manager and support staff</li><li>Appointments reserved every day for dental emergencies</li><li>Bulk billing for eligible children under the Child Dental Benefits Schedule (CDBS)</li><li>Payment options include ${F.payList}</li><li>Online booking available</li></ul>` }),
    ], { inner: true })]),
    section('', [
      secHead({ eb: 'Why patients choose us', h: 'What you can expect from us' }),
      widget('rd-icon-cards', { layout: 'cards', columns: 'g3', items: rep([
        ['wallet', 'Affordable pricing', 'The very best treatment at competitive prices, with a printed treatment plan and cost estimate before you begin. Several payment options are available.'],
        ['heart', 'Knowledgeable, caring and gentle', 'A team that listens, explains your options clearly, and helps anxious patients feel at ease.'],
        ['cpu', 'Modern equipment', 'Including 3D X-ray planning and computer-guided implant placement, and high-precision magnification for veneers.'],
        ['music', 'A relaxing environment', 'Tell us what music you would like, or choose a movie to watch while your treatment is completed.'],
        ['moon', 'Flexible appointments', 'Open until 6:30pm on Wednesday and Thursday, and you can book online.'],
        ['users', 'For the whole family', 'From children’s first visits to implants, clear aligners and cosmetic dentistry, all in one place.'],
      ].map(([icon, title, t]) => ({ icon, title, text: `<p>${t}</p>`, extra: '', tone: 'dent' }))) }),
    ]),
    section('bg-forest', [
      secHead({ eb: 'Our dentists', h: 'Meet the people who will look after you' }),
      widget('rd-team', { layout: 'row', link: pageUrl('our-team') }),
      buttons([linkBtn('our-team', 'Meet our team', '', true)], 'mt-2', 'p'),
    ]),
    section('bg-paper', [con('split', [
      con('rd-reveal', [
        eyebrow('Location'),
        heading('Easy to find, easy to visit', 'h2'),
        text(`<p>You will find us at ${F.street} in Redlynch, within the Redlynch Commercial Hub, convenient for families across Redlynch, Brinsmead, Edge Hill, Smithfield and the surrounding suburbs of Cairns.</p>`),
        widget('rd-hours', { style: 'table' }),
        buttons([{ ...linkBtn('contact', 'Contact details and map'), style: 'text' }], 'mt-2', 'p'),
      ], { inner: true }),
      widget('rd-map', {}, 'rd-reveal'),
    ], { inner: true })]),
    cta(p.cta),
  ];
  return { slug: 'about', title: 'About', page: p, elements: els };
}

function team() {
  const p = Pg.team();
  const els = [
    bare(widget('rd-page-hero', { tone: 'forest', crumbs: 'yes', parents: rep([{ label: 'About Us', link: pageUrl('about') }]), current: 'Meet Our Team', eyebrow: 'Meet our team', title: 'The people behind your smile.', lead: 'Three dentists and a dedicated support team who take the time to listen, explain clearly and make every visit as comfortable as possible.', buttons: [], meta: '', team_jump: 'yes', visual: 'none' })),
    section('bg-paper', [widget('rd-team', { layout: 'profiles', book: 'yes' })]),
    section('', [
      secHead({ eb: 'Support team', h: 'The friendly faces who keep everything running', p: 'From your first phone call to your follow-up, our support team makes sure your visit runs smoothly.' }),
      widget('rd-team', { layout: 'support-grid' }),
    ], { id: 'support-team' }),
    cta(p.cta),
  ];
  return { slug: 'our-team', title: 'Meet Our Team', page: p, elements: els };
}

function newPatients() {
  const p = Pg.newPatients();
  const faqs = [
    ['Do I need a referral?', 'No referral is needed for general dental care. Just call us or book online.'],
    ['What if I haven’t been to the dentist in years?', 'You are very welcome. There is no judgement. Many of our patients say they wish they had come in sooner. We will start with a thorough check-up and talk you through what we find.'],
    ['Can I bring my children to their first visit?', 'Yes. Patients of all ages are welcome, and positive early visits help children feel comfortable at the dentist. See <a href="childrens-dentistry.html">children’s dentistry</a>.'],
    ['Will I be told the cost before treatment?', 'Yes. After your comprehensive check-up you receive a printed treatment plan with the most accurate estimate we can give of the time and cost involved. Ask us about anything you are unsure of.'],
  ];
  const els = [
    bare(widget('rd-page-hero', { tone: 'cream', crumbs: 'yes', parents: rep([{ label: 'About Us', link: pageUrl('about') }]), current: 'New Patients', eyebrow: 'New patients', title: 'Welcome. Here’s what to expect at your first visit.', lead: 'Redlynch Dental welcomes new patients of all ages. We recommend beginning with a thorough consultation and dental clean, so we can understand your oral health and plan your care with you.', buttons: rep([book('lg'), call('ghost lg', F.phone)]), meta: '', visual: 'card', card_eyebrow: 'Your first visit', card_title: 'A comprehensive check-up and clean', card_ticks: ['Examine all of your teeth and gums', 'Assess your jaw joints', 'Take the X-rays you need, close-up and full-face', 'Photos of any potential problems, so you can see them too', 'Scale, clean and polish', 'Topical fluoride treatment', 'A printed treatment plan with time and cost estimate'].join('\n') })),
    section('bg-paper', [
      secHead({ eb: 'Step by step', h: 'Getting started is easy' }),
      widget('rd-steps', { layout: 'timeline', items: rep([
        { title: 'Book your visit', text: links(`Call ${F.phone} or <a href="${F.booking}" target="_blank" rel="noopener">book online</a>. Let us know if you are in pain, nervous, or have something specific you want to discuss.`) },
        { title: 'Tell us about you', text: 'We ask about your general health, medications and any dental concerns, so we can care for you safely.' },
        { title: 'Check-up and clean', text: 'A thorough examination, any X-rays needed, then a scale, clean, polish and fluoride.' },
        { title: 'Your treatment plan', text: 'We explain what we found, show you photos, and give you a printed plan with time and cost estimates.' },
      ]) }),
    ]),
    section('', [con('split top', [
      con('rd-reveal', [
        eyebrow('Before you come in'),
        heading('A few things that help', 'h2'),
        text('<p>These are general suggestions to help your first appointment go smoothly. If you’re not sure about anything, just call us.</p><ul class="ticks"><li>A list of any medications or supplements you take, and any allergies</li><li>Details of any medical conditions, or recent changes to your health</li><li>Your health fund card, if you have extras cover</li><li>Any recent dental X-rays or records from a previous dentist, if you have them</li><li>A note of any questions or concerns you’d like to talk about</li></ul>'),
      ], { inner: true }),
      con('rd-reveal', [
        eyebrow('Feeling nervous?'),
        heading('You’re in good company', 'h2'),
        text('<p>We help many anxious patients overcome their apprehensions and complete their dental treatment. Tell us how you are feeling when you book. We can take things slowly, explain each step before we start, and agree a signal for you to pause at any time.</p><p>Let us know what music you would like us to play, or choose a movie to watch while your treatment is completed.</p>'),
        widget('rd-callout', { style: 'callout', content: '<p><strong>Understanding your treatment plan.</strong> You are always welcome to ask why a treatment is recommended, what the alternatives are, what happens if you wait, and what it will cost. It is your decision, and we want you to feel confident making it.</p>' }),
      ], { inner: true }),
    ], { inner: true })]),
    section('bg-sand', [offer({ eyebrow: 'New patient special offer', title: 'Comprehensive check-up, X-rays, clean and treatment plan', tag: 'h3', fine: 'Terms and conditions apply. Ask our team for full details when you book.', buttons: [book(''), linkBtn('special-offers', 'Offer details', 'ghost')] })]),
    section('bg-paper', [con('split top', [
      secHead({ eb: 'Questions', h: 'New patient questions', p: 'More answers on our <a href="faq.html">FAQ page</a>.' }),
      faqWidget(faqs),
    ], { inner: true })]),
    cta(p.cta),
  ];
  return { slug: 'new-patients', title: 'New Patients', page: p, elements: els };
}

function servicesIndex() {
  const p = Pg.servicesIndex();
  const els = [
    bare(widget('rd-page-hero', { tone: 'forest', crumbs: 'yes', parents: [], current: 'Services', eyebrow: 'Our services', title: 'Complete dental care for every age and every smile.', lead: 'From check-ups and children’s dentistry to implants, clear aligners and emergency care. Choose a service to learn more, or call us and we will point you in the right direction.', buttons: rep([call('', F.phone), btn('emergency', 'ember', 'Dental emergency?')]), meta: '', visual: 'none' })),
    section('bg-paper', [widget('rd-service-directory', { ...Object.fromEntries(CATS.flatMap(c => [[`name_${c.id}`, c.name], [`blurb_${c.id}`, c.blurb]])), note: `Treatment suitability depends on an individual clinical assessment. ${RISK}` })]),
    cta(p.cta),
  ];
  return { slug: 'services', title: 'Services', page: p, elements: els };
}

/* The service article as published in the finished HTML page (it may have been edited by hand). */
function htmlArticle(s) {
  const file = path.join(ROOT, s.file);
  const m = fs.existsSync(file) && fs.readFileSync(file, 'utf8').match(/<article class="prose">([\s\S]*?)<\/article>/);
  return m ? m[1].trim() : '';
}

function servicePage(s) {
  const p = Pg.servicePage(s);
  const crumb = s.navName;
  let hero = { tone: 'forest', crumbs: 'yes', parents: rep([{ label: 'Services', link: pageUrl('services') }]), current: crumb, eyebrow: s.eyebrow, title: s.h1, lead: s.lead, buttons: rep([book(''), call('ghost-light', F.phone)]), meta: (s.heroMeta || []).join('\n'), visual: 'none' };
  if (!s.img) hero = { ...hero, visual: 'art', art_icon: s.icon, art_text: s.artText || s.name };
  else if (s.imgContain) hero = { ...hero, visual: 'contain', image: media(s.img), alt: s.imgAlt };
  else hero = { ...hero, visual: 'image', image: media(s.img), alt: s.imgAlt, focus: s.imgPos || '' };
  const els = [
    bare(widget('rd-page-hero', hero)),
    section('bg-paper', [con('layout', [
      con('prose', bodyWidgets(htmlArticle(s) || s.body()), { inner: true, tag: 'article' }),
      widget('rd-service-sidebar', { em: '', book_title: 'Book an appointment', book_text: 'Call our Redlynch team, or book online at a time that suits you.', contact_link: pageUrl('contact') }),
    ], { inner: true })]),
    s.faqs && s.faqs.length && section('', [con('split top', [
      secHead({ eb: 'Questions', h: `${s.navName}: your questions answered`, p: `Still unsure? Call <a href="${F.tel}">${F.phone}</a> and talk to our team.` }),
      faqWidget(s.faqs),
    ], { inner: true })]),
    s.blog && section('bg-sand sec-tight', [widget('rd-post-feature', { post: 'rd-post-id:why-choose-dental-implants', tag: 'From our blog', text: 'The advantages of implants, from the Redlynch Dental team.', date: '', decorative: 'yes', more: 'Read the article' })]),
    (s.related || []).length && section('bg-paper sec-tight', [widget('rd-related', { heading: 'Related treatments', services: s.related.map(r => `rd-id:${r}`) })]),
    cta(p.cta),
  ].filter(Boolean);
  return {
    slug: s.slug, title: s.name, page: p, elements: els,
    service: { nav: s.navName, short: s.short, icon: s.icon, cat: s.cat, contain: !!s.imgContain, img: s.img ? s.img.replace(/^assets\/images\//, '') : '' },
  };
}

function emergency() {
  const s = byslug['emergency-dental-care'];
  const p = Pg.emergency();
  const faqs = [
    ['Can I be seen today?', 'We reserve specific appointments every day for dental emergencies, and will always strive to make time to see you as soon as possible. Call as early as you can. Same-day appointments depend on availability, so call us straight away.'],
    ['What if it’s outside opening hours?', `Our hours are Monday and Tuesday 8am to 5:30pm, Wednesday and Thursday 8am to 6:30pm, and Friday 8am to 4:30pm. We are closed on weekends. Outside these hours, if your symptoms are severe or getting worse, go to your nearest hospital emergency department or call 000. For health advice, you can call 13 HEALTH (13 43 25 84) in Queensland.`],
    ['Should I go to hospital instead?', 'Go to a hospital emergency department or call 000 if you have swelling affecting your breathing, swallowing or eye; bleeding that won’t stop; a serious head or facial injury; or if you feel very unwell with a high fever. For most other dental pain, broken teeth and lost fillings, call us.'],
    ['What can I do for a toothache until I’m seen?', 'Take over-the-counter pain relief as directed on the packet or by your pharmacist, rinse gently with warm water, and avoid very hot, cold or sweet foods. Don’t put aspirin or other tablets directly on the gum. Call us to arrange an appointment.'],
    ['A tooth has been knocked out. What do I do?', 'Call us immediately: time matters. Handle the tooth by the crown (the white part), not the root. If it is dirty, rinse it briefly in milk. Keep it moist in milk, or for adults, inside the cheek, and bring it with you. Don’t try to put a baby tooth back in. We will give you more instructions on the phone.'],
    ['Do you see new patients for emergencies?', 'Yes. Redlynch Dental welcomes new patients of all ages, including for emergencies. Call us to arrange an appointment.'],
  ];
  const ems = [
    ['tooth', 'Painful tooth or gums', 'Throbbing, constant or night-time pain, or pain when biting, can be a sign of infection or deep decay.', 'Take pain relief as directed and avoid very hot, cold or sweet foods. Call us.'],
    ['broken', 'Broken teeth and fillings', 'Even without pain, a broken tooth or lost filling can crack further, trap food or expose the nerve.', 'Keep any broken pieces, rinse gently, and avoid chewing on that side.'],
    ['swelling', 'Facial swelling', 'Swelling of the face, jaw or gums can mean an infection that needs prompt attention.', 'Call us straight away. If swelling affects breathing, swallowing or your eye, call 000.'],
    ['bolt', 'Trauma to the face, teeth or gums', 'A knock or fall that chips, loosens, moves or knocks out a tooth, or injures the gums or lips.', 'Knocked-out tooth? Hold it by the crown, keep it moist in milk, and call immediately.'],
    ['denture', 'Broken or lost denture', 'A cracked, broken or lost denture can make eating and speaking difficult.', 'Keep all the pieces and don’t try to glue it yourself. Call us.'],
  ];
  const els = [
    bare(widget('rd-emergency-hero', {
      crumbs: 'yes', parents: rep([{ label: 'Services', link: pageUrl('services') }]), current: 'Emergency Dental Care',
      tag: 'Dental emergency', title: 'Emergency dental care in Redlynch',
      lead: 'Dental emergencies can be painful and distressing. That is why we reserve specific appointments every day for them. If you are experiencing a dental emergency, please call us immediately.',
      buttons: rep([btn('call', 'ember lg', 'Call {phone} now'), linkBtn('contact', 'Contact and directions', 'ghost-light lg')]),
      call_label: 'Call us immediately', call_button: 'Tap to call', call_note: 'We will always strive to make time for you to be seen as soon as possible. {address}.',
      bar: '<strong>Call <a href="tel:000">000</a> or go to your nearest hospital emergency department</strong> if you have swelling that affects your breathing, swallowing or eye, bleeding that won’t stop, a serious head or facial injury, or you feel very unwell.',
    })),
    section('bg-paper', [
      secHead({ eb: 'What counts as a dental emergency', h: 'If it hurts, it’s broken or it’s swollen, call us.', p: 'Dental emergencies are often serious, and if left untreated they can get worse quickly. A dental emergency may include:' }),
      widget('rd-icon-cards', { layout: 'emergency', items: rep(ems.map(([icon, title, t, d]) => ({ icon, title, text: `<p>${t}</p>`, extra: d, tone: 'dent' }))), call_title: 'Not sure if it’s an emergency?', call_text: 'Call anyway. Tell us what has happened and we will advise you on what to do next.' }),
    ]),
    section('', [con('split top', [
      con('rd-reveal', [
        eyebrow('What happens next'),
        heading('What to do, step by step', 'h2'),
        widget('rd-steps', { layout: 'numbered', items: rep([
          { title: 'Call us straight away', text: `Phone <a href="${F.tel}">${F.phone}</a>. Tell us what happened, where it hurts, how long it has been going on and whether there is any swelling.` },
          { title: 'We find you a time', text: 'Specific appointments are reserved every day for emergencies. We will offer the earliest suitable time and tell you what to do in the meantime.' },
          { title: 'Assessment and relief', text: 'The dentist examines the problem, takes any X-rays that help, and focuses on relieving your pain and stabilising the problem.' },
          { title: 'A plan for what’s next', text: 'If you need further treatment, we explain your options and costs before booking anything.' },
        ]) }),
      ], { inner: true }),
      con('rd-reveal', [
        text(`<figure style="margin:0 0 24px"><img src="${s.img}" alt="${s.imgAlt}" width="800" height="350" loading="lazy" decoding="async" style="border-radius:var(--r-lg);width:100%"></figure>`),
        widget('rd-icon-cards', { layout: 'when', items: rep([
          { icon: 'phone', title: 'Call the dentist', text: '<ul><li>Toothache or sensitive gums</li><li>Chipped or broken tooth</li><li>Lost filling or crown</li><li>Broken or lost denture</li><li>Mild swelling around a tooth</li></ul>', extra: '', tone: 'dent' },
          { icon: 'alert', title: 'Call 000', text: '<ul><li>Swelling affecting breathing, swallowing or the eye</li><li>Bleeding that won’t stop</li><li>Serious head or facial injury</li><li>Feeling very unwell, with high fever</li></ul>', extra: '', tone: '000' },
        ]) }),
      ], { inner: true }),
    ], { inner: true })]),
    section('bg-paper', [con('split top', [
      secHead({ eb: 'Questions', h: 'Emergency dental questions', p: 'When you call, our team will advise you based on your situation.' }),
      faqWidget(faqs),
    ], { inner: true })]),
    section('sec-tight', [widget('rd-related', { heading: 'Treatments that often follow an emergency', services: ['root-canal-therapy', 'extractions', 'crowns-and-bridges'].map(r => `rd-id:${r}`) })]),
    cta(p.cta),
  ];
  return {
    slug: s.slug, title: s.name, page: p, elements: els,
    service: { nav: s.navName, short: s.short, icon: s.icon, cat: s.cat, contain: false, img: s.img.replace(/^assets\/images\//, '') },
  };
}

/* FAQ page: the groups are taken from the static FAQ page's own HTML. */
function faq() {
  const p = Pg.faq();
  const groups = [...p.body.matchAll(/<div class="faq-group" id="([a-z]+)"><h2>([\s\S]*?)<\/h2><div class="faq" data-reveal-group>([\s\S]*?)<\/div><\/div>(?=<div class="faq-group"|<\/div>\s*<\/div><\/section>)/g)]
    .map(m => ({ id: m[1], name: m[2], items: [...m[3].matchAll(/<details><summary>([\s\S]*?)<\/summary><div class="ans">([\s\S]*?)<\/div><\/details>/g)].map(x => [x[1], x[2]]) }));
  if (groups.length !== 7) throw new Error('FAQ page: expected 7 groups, found ' + groups.length);
  const els = [
    bare(widget('rd-page-hero', { tone: 'forest', crumbs: 'yes', parents: [], current: 'FAQs', eyebrow: 'Patient information', title: 'Frequently asked questions', lead: `Answers to common questions about visiting Redlynch Dental. If your question isn’t here, call us on ${F.phone}. We’re happy to help.`, buttons: [], meta: '', visual: 'none' })),
    section('bg-paper', [con('faq-cats', [
      widget('rd-anchor-nav', { label: 'FAQ categories', items: rep(groups.map(g => ({ text: g.name, anchor: g.id }))) }),
      con('', groups.map(g => faqWidget(g.items, { heading: g.name, anchor: g.id })), { inner: true }),
    ], { inner: true })]),
    cta(p.cta),
  ];
  return { slug: 'faq', title: 'Frequently Asked Questions', page: p, elements: els };
}

function blog() {
  const p = Pg.blog();
  const guides = [
    ['emergency-dental-care', 'Urgent', 'What counts as a dental emergency, and what to do right now.'],
    ['childrens-dentistry', 'Family', 'Helping children have positive early visits, and caring for little teeth at home.'],
    ['teeth-whitening', 'Cosmetic', 'Is whitening safe? Side effects, suitability and keeping your results.'],
    ['wisdom-teeth', 'Oral surgery', 'Common wisdom tooth problems, and when removal is recommended.'],
    ['invisalign', 'Straightening', 'How clear aligners work, how long treatment takes and what happens after.'],
    ['root-canal-therapy', 'Restorative', 'Why root canal treatment can save a tooth, and what to expect.'],
  ];
  const els = [
    bare(widget('rd-page-hero', { tone: 'cream', crumbs: 'yes', parents: [], current: 'Blog', eyebrow: 'Blog', title: 'News and dental health articles', lead: 'Articles from the Redlynch Dental team, plus practical patient guides to common treatments.', buttons: [], meta: '', visual: 'none' })),
    section('bg-paper', [
      heading('Latest article', 'h2', 'sr-only'),
      widget('rd-post-feature', { post: 'rd-post-id:why-choose-dental-implants', tag: '', text: '', date: 'yes', decorative: '', more: 'Read the article' }),
    ]),
    section('', [
      secHead({ eb: 'Patient guides', h: 'Explore our treatment guides', p: 'Clear, practical information on common treatments, written for our patients.' }),
      widget('rd-guide-cards', { more: 'Read guide', items: rep(guides.map(([sl, k, t]) => ({ service: `rd-id:${sl}`, title: '', kicker: k, text: t }))) }),
    ]),
    cta(p.cta),
  ];
  return { slug: 'blog', title: 'Blog', page: p, elements: els };
}

function contact() {
  const p = Pg.contact();
  const els = [
    bare(widget('rd-page-hero', { tone: 'forest', crumbs: 'yes', parents: [], current: 'Contact Us', eyebrow: 'Contact us', title: 'We’d love to hear from you.', lead: 'Call, email or book online. For a dental emergency, please phone us rather than emailing, so we can help you as quickly as possible.', buttons: rep([call('', F.phone), book('ghost-light'), btn('emergency', 'ember', 'Dental emergency?')]), meta: '', visual: 'none' })),
    section('bg-paper', [con('contact-grid', [
      con('', [
        widget('rd-contact-cards', { items: rep([{ type: 'phone', title: 'Phone' }, { type: 'email', title: 'Email', wbr: '' }, { type: 'visit', title: 'Address' }, { type: 'book', title: 'Book online', text: 'Choose an appointment time' }]) }),
        widget('rd-hours', { style: 'card', heading: 'Opening hours' }, 'mt-2'),
      ], { inner: true }),
      con('rd-reveal', [widget('rd-contact-form', {
        heading: 'Send an enquiry',
        intro: 'Fields marked * are required. Pressing “Prepare email” opens your email app with your message ready to send to {email}. For urgent problems, please call us.',
        reasons: 'General enquiry\nNew patient appointment\nCheck-up and clean\nChildren’s dentistry\nCosmetic treatment\nDental implants\nClear aligners\nSomething else',
        consent: 'I agree to {practice} using these details to reply to my enquiry. See our {privacy}.',
        button: 'Prepare email',
      })], { inner: true }),
    ], { inner: true })]),
    section('sec-tight', [widget('rd-map', {})]),
  ];
  return { slug: 'contact', title: 'Contact Us', page: p, elements: els };
}

function specialOffers() {
  const p = Pg.specialOffers();
  const els = [
    bare(widget('rd-page-hero', { tone: 'cream', crumbs: 'yes', parents: [], current: 'New Patient Offer', eyebrow: 'Special offers', title: 'New patient special offer', lead: 'A comprehensive start with Redlynch Dental: check-up, X-rays, clean, fluoride and a treatment plan.', buttons: [], meta: '', visual: 'none' })),
    section('bg-paper', [
      offer({ lazy: false, eyebrow: 'What’s included', title: 'Everything you need for a fresh start', ticks: [...OFFER_TICKS, 'Treatment plan'], fine: 'Terms and conditions apply to all offers. Please ask our team for the full terms and conditions when you book.', buttons: [book(''), call('ghost', F.phone)] }),
      buttons([{ ...linkBtn('new-patients', 'What to expect at your first visit'), style: 'text' }], 'mt-2', 'p'),
      buttons([{ ...linkBtn('payments-and-fees', 'Payments and fees'), style: 'text' }], 'mt-1', 'p'),
    ]),
    cta(p.cta),
  ];
  return { slug: 'special-offers', title: 'New Patient Special Offer', page: p, elements: els };
}

function payments() {
  const p = Pg.payments();
  const els = [
    bare(widget('rd-page-hero', { tone: 'cream', crumbs: 'yes', parents: [], current: 'Payments and Fees', eyebrow: 'Payments and fees', title: 'Clear costs and flexible ways to pay', lead: 'Your treatment plan and its costs are discussed with you before any treatment starts. Here is our new patient offer, how treatment costs work, and the payment options available at Redlynch Dental.', buttons: rep([book(''), call('ghost', F.phone)]), meta: '', visual: 'none' })),
    section('bg-paper', [offer({ eyebrow: 'New patient special offer', title: 'Check-up, X-rays, clean and treatment plan', ticks: [...OFFER_TICKS, 'A printed treatment plan'], fine: 'Terms and conditions apply. Ask our team for full details when you book.', buttons: [book('', 'Book an Appointment'), linkBtn('special-offers', 'Offer details', 'ghost')] })]),
    section('', [con('split top', [
      con('rd-reveal', [
        eyebrow('Treatment costs'),
        heading('Your costs, explained before treatment', 'h2'),
        text('<p>Treatment costs depend on what you need, so we don’t quote one price for everyone. After your examination, your dentist explains your treatment plan, and we discuss the plan and its costs with you before any treatment starts.</p><p>You are always welcome to ask why a treatment is recommended, what the alternatives are and what each option costs. It is your decision, and we want you to feel confident making it.</p>'),
      ], { inner: true }),
      widget('rd-callout', { style: 'card lime', label: 'Health funds', content: '<p>If you have private health insurance with dental cover, what you can claim depends on your fund and your level of cover. Ask your fund what you are covered for, and ask our team when you book.</p>' }),
    ], { inner: true })]),
    section('bg-paper', [
      secHead({ eb: 'Payment options', h: 'Ways to pay for your treatment', p: 'Redlynch Dental offers these payment providers. Ask our team which options can be used for your treatment.' }),
      widget('rd-icon-cards', { layout: 'cards', columns: 'g4', items: rep(F.payProviders.map(title => ({ icon: 'wallet', title, text: '', extra: '', tone: 'dent' }))) }),
      widget('rd-callout', { style: 'callout info', spacing: 'mt-2', content: '<p><strong>Before you apply.</strong> Eligibility, fees and terms are set by each provider and may vary. Approval is not guaranteed, and Redlynch Dental can’t promise specific payment terms. Please read the provider’s terms before you apply, and ask our team if you have any questions.</p>' }),
    ]),
    section('', [con('split', [
      image('services/childrens-dentistry-smiling-girl.webp', 'media-frame ratio-4-3 rd-reveal rd-focus-62-50'),
      con('rd-reveal', [
        eyebrow('Children’s dental benefits'),
        heading('Bulk billing for eligible children', 'h2'),
        text('<p>Redlynch Dental bulk bills eligible children under the Child Dental Benefits Schedule (CDBS).</p><p>Not every child is eligible, so please ask our team about your child’s situation when you book.</p>'),
        buttons([linkBtn('childrens-dentistry', 'Children’s dentistry', 'sm'), linkBtn('contact', 'Contact us', 'ghost sm')]),
      ], { inner: true }),
    ], { inner: true })]),
    cta(p.cta),
  ];
  return { slug: 'payments-and-fees', title: 'Payments and Fees', page: p, elements: els };
}

function privacy() {
  const p = Pg.privacy();
  const prose = p.body.match(/<div class="wrap narrow prose">([\s\S]*?)<\/div><\/section>/)[1];
  const els = [
    bare(widget('rd-page-hero', { tone: 'cream', narrow: 'yes', crumbs: 'yes', parents: [], current: 'Privacy Policy', eyebrow: 'Privacy', title: 'Privacy policy', lead: '', buttons: [], meta: '', visual: 'none' })),
    con('section bg-paper', [con('wrap narrow prose', [text(prose.trim())], { inner: true })], { tag: 'section' }),
  ];
  return { slug: 'privacy-policy', title: 'Privacy Policy', page: p, elements: els };
}

/* ------------------------------------------------------------------ campaign landing pages */
/* The landing pages exist only as HTML (no _build source). Each section becomes native containers
   with the same classes, native Heading / Text Editor / Image widgets, and Redlynch widgets for the
   buttons, icon cards, steps, enquiry form and FAQ. The page uses the "Campaign landing page"
   template, which adds body.lp and landing.css. */
const LANDING = [['cosmetic-dentistry', 'Cosmetic Dentistry'], ['full-arch-dental-implants', 'Full Arch Dental Implants']];
const revealCls = n => (/ data-reveal(?=[\s>=]|$)/.test(n.attrs) ? ' rd-reveal' : '');
const attr = (n, a) => (n.attrs.match(new RegExp(` ${a}="([^"]*)"`)) || [])[1] || '';
const noLabelledby = html => html.replace(/ aria-labelledby="[^"]*"/g, '');

function lpButton(a) {
  const href = attr(a, 'href').replace(/&amp;/g, '&');
  const c = cls(a).replace(/^btn\s*/, '');
  const label = stripSvg(a.inner).replace(/<span class="sr-only">[\s\S]*?<\/span>/, '').replace(/<[^>]+>/g, '').trim();
  if (href === F.booking) return book(c, label);
  if (href === F.tel) return call(c, label);
  return { ...btn('link', c, label, url(href)), arrow: /class="arr"/.test(a.inner) ? 'yes' : '' };
}
function lpImage(n, classes) {
  const img = n.tag === 'img' ? n.outer : n.inner;
  const pos = img.match(/object-position:(\d+)% (\d+)%/);
  return image(img.match(/src="([^"]+)"/)[1], classes + (pos ? ` rd-focus-${pos[1]}-${pos[2]}` : ''));
}
function lpChildren(html) {
  const out = []; let buf = []; let btns = null;
  const flush = () => { if (buf.length) { out.push(text(noLabelledby(buf.join('')))); buf = []; } btns = null; };
  for (const n of topLevel(html)) {
    const c = cls(n);
    if (n.tag === 'a' && /\bbtn\b/.test(c)) {
      // Buttons placed one after another in a panel: one Buttons widget, no wrapper.
      if (!btns) { flush(); btns = buttons([], '', 'none'); out.push(btns); }
      btns.settings.buttons.push(...rep([lpButton(n)]));
      continue;
    }
    if (n.tag === 'span' && c === 'eyebrow') { flush(); out.push(eyebrow(n.inner)); continue; }
    if (/^h[1-6]$/.test(n.tag)) { flush(); out.push(heading(n.inner, n.tag)); continue; }
    if (n.tag === 'div' && /\bactions\b/.test(c)) {
      flush();
      out.push(buttons(topLevel(n.inner).map(lpButton), '', 'actions', (c.replace(/\bactions\b/, '') + revealCls(n)).trim() || undefined));
      continue;
    }
    if (n.tag === 'ul' && /\blp-cards\b/.test(c)) {
      flush();
      const items = topLevel(n.inner).map(li => {
        const body = topLevel(li.inner).find(x => x.tag === 'div');
        return { icon: iconOf(li.inner), title: (body.inner.match(/<h3>([\s\S]*?)<\/h3>/) || [])[1] || '', text: links(body.inner.replace(/<h3>[\s\S]*?<\/h3>/, '').trim()), extra: '', tone: 'dent' };
      });
      const grid = /\blp-options\b/.test(c) ? 'lp-options on-cream' : (/\bc3\b/.test(c) ? 'c3' : '');
      out.push(widget('rd-icon-cards', { layout: 'lp', lp_grid: grid, items: rep(items) }));
      continue;
    }
    if (n.tag === 'ol' && /\blp-steps\b/.test(c)) {
      flush();
      const items = [...n.inner.matchAll(/<li><h3>([\s\S]*?)<\/h3><p>([\s\S]*?)<\/p><\/li>/g)].map(x => ({ title: x[1], text: links(x[2]) }));
      out.push(widget('rd-steps', { layout: 'lp', items: rep(items) }));
      continue;
    }
    if (n.tag === 'div' && c === 'faq') {
      flush();
      out.push(faqWidget([...n.inner.matchAll(/<details><summary>([\s\S]*?)<\/summary><div class="ans">([\s\S]*?)<\/div><\/details>/g)].map(x => [x[1], x[2]])));
      continue;
    }
    if (n.tag === 'form') {
      flush();
      const m = re => (n.inner.match(re) || [])[1] || '';
      out.push(widget('rd-contact-form', {
        heading: m(/<h3>([\s\S]*?)<\/h3>/),
        intro: m(/<p id="form-intro"[^>]*>([\s\S]*?)<\/p>/).replace(F.email, '{email}'),
        reason_fixed: m(/name="reason" value="([^"]*)"/),
        message_label: m(/<label for="f-message">([\s\S]*?)<\/label>/),
        button: stripSvg(m(/<button[^>]*>([\s\S]*?)<\/button>/)).trim(),
        variant: /\bdark\b/.test(m(/<button class="([^"]*)"/)) ? 'landing' : '',
      }));
      continue;
    }
    if (n.tag === 'figure' || (n.tag === 'div' && /\bph-img\b/.test(c))) { flush(); out.push(lpImage(n, (c + revealCls(n)).trim())); continue; }
    if (n.tag === 'div' && c === 'lp-book-meta') { buf.push(n.outer); continue; }
    if (n.tag === 'div') { flush(); out.push(con((c + revealCls(n)).trim(), lpChildren(n.inner), { inner: true })); continue; }
    // The footnote's parts stay separate (they sit side by side); a lone text link gets a paragraph.
    if (n.tag === 'p' && /\b(risk|lp-more)\b/.test(c)) { flush(); out.push(text(n.outer)); continue; }
    if (n.tag === 'a') { flush(); out.push(text(`<p class="lp-link">${n.outer}</p>`)); continue; }
    buf.push(n.outer);
  }
  flush();
  return out;
}
function landingPage([slug, title]) {
  const h = fs.readFileSync(path.join(ROOT, slug + '.html'), 'utf8');
  const head = re => ((h.match(re) || [])[1] || '').replace(/&amp;/g, '&');
  const els = topLevel(h.match(/<main id="main">([\s\S]*?)<\/main>/)[1]).map(sec => {
    const wrap = topLevel(sec.inner)[0];
    return con(('section ' + cls(sec)).trim(), [con(cls(wrap), lpChildren(wrap.inner), { inner: true })], { tag: 'section', id: attr(sec, 'id') || undefined });
  });
  const page = { title: head(/<title>([\s\S]*?)<\/title>/), desc: head(/<meta name="description" content="([^"]*)"/), noindex: /noindex/.test(head(/<meta name="robots" content="([^"]*)"/)) };
  for (const m of h.matchAll(/<img src="assets\/images\/([^"]+)" alt="([^"]+)"/g)) if (!LANDING_ALTS[m[1]]) LANDING_ALTS[m[1]] = m[2];
  return { slug, title, page, elements: els, template: 'page-templates/landing.php' };
}
const LANDING_ALTS = {};

/* ------------------------------------------------------------------ assemble */
const pages = [home(), about(), team(), newPatients(), servicesIndex(),
  ...services.map(s => (s.custom ? emergency() : servicePage(s))),
  faq(), blog(), contact(), specialOffers(), payments(), privacy(), ...LANDING.map(landingPage)];

const out = {
  generator: 'wordpress-theme/tools/build-content.js',
  source: '_build/*.js (static site)',
  pages: pages.map((pg, i) => ({
    slug: pg.slug,
    title: pg.title,
    menu_order: pg.service ? services.findIndex(s => s.slug === pg.slug) : i,
    seo: { title: pg.page.title, description: pg.page.desc.replace(/\s+/g, ' '), noindex: !!pg.page.noindex },
    excerpt: pg.page.desc.replace(/\s+/g, ' '),
    service: pg.service || null,
    template: pg.template || 'default',
    elementor: pg.elements,
  })),
};

/* Team: the static site's TEAM and SUPPORT data. Members without a photo show their initials. */
const rel = img => (img || '').replace(/^assets\/images\//, '');
out.team = [
  ...Pg.TEAM.map((m, i) => ({ name: m.name, group: 'dentist', order: i, role: m.role, quals: m.quals, quals_long: m.qualsLong, focus_heading: 'Known for', focus: m.focus, anchor: m.id, img: rel(m.img), bio: m.bio })),
  ...Pg.SUPPORT.map((m, i) => ({ name: m.name, group: 'support', order: 10 + i, role: m.role, quals: m.quals, quals_long: '', focus_heading: '', focus: [], anchor: m.id, img: rel(m.img), bio: [] })),
];
/* Profiles of people who have left the practice: the importer moves them to Drafts. */
out.retired_team = ['dr-alex-bornsztejn', 'dr-julian-fung', 'erin', 'ella'];

/* The blog article (post content is plain HTML for the block or classic editor). */
const bp = Pg.blogPost();
const article = bp.body.match(/<article class="article prose">([\s\S]*?)<\/article>/)[1]
  .replace(/<figure[\s\S]*?<\/figure>/, '')
  .replace(/<p class="mt-2">[\s\S]*?<\/p>/, '')
  .replace(/\n\s+/g, '\n').trim();
out.posts = [{
  slug: 'why-choose-dental-implants', title: 'Why choose dental implants?', date: '2021-01-29 09:00:00',
  category: 'Dental implants', byline: 'Redlynch Dental',
  excerpt: 'Dental implants are a simple and proven way to replace one or more missing teeth. Here are the main advantages.',
  featured: 'blog/dental-implant-crown-illustration.webp',
  content: links(article),
  seo: { title: bp.title, description: bp.desc },
}];

/* Media Library alternative text (informative images; decorative uses set alt="" in the widgets). */
out.media = {
  'hero/smiling-mother-and-daughter.webp': 'A smiling mother and young daughter hugging',
  'services/dental-examination.webp': 'A dentist examining a smiling patient’s teeth',
  'services/childrens-dentistry-smiling-girl.webp': 'A smiling young girl in a dental chair',
  'blog/dental-implant-crown-illustration.webp': 'Illustration of a dental implant post in the jaw with a crown being lowered onto it',
  ...Object.fromEntries([...Pg.TEAM, ...Pg.SUPPORT].filter(m => m.img).map(m => [rel(m.img), `Portrait of ${m.name}, ${m.role} at Redlynch Dental`])),
};
for (const s of services) if (s.img && !out.media[s.img.replace(/^assets\/images\//, '')]) out.media[s.img.replace(/^assets\/images\//, '')] = s.imgAlt;
for (const [img, alt] of Object.entries(LANDING_ALTS)) if (!out.media[img]) out.media[img] = alt;

/* Menus, as in the static site's header and footer. */
const svcItems = services.map(s => ({ page: s.slug, label: s.navName }));
out.menus = {
  primary: { name: 'Main menu', items: [
    { page: 'home', label: 'Home' },
    { url: '#', label: 'About Us', children: [{ page: 'about', label: 'About Redlynch Dental' }, { page: 'our-team', label: 'Meet Our Team' }, { page: 'new-patients', label: 'New Patients' }] },
    { page: 'services', label: 'Services', classes: 'mega', description: 'Our services', children: svcItems },
    { url: '#', label: 'Patient Info', children: [{ page: 'faq', label: 'Frequently Asked Questions' }, { page: 'special-offers', label: 'New Patient Offer' }, { page: 'emergency-dental-care', label: 'Emergency Dental Care' }, { url: 'rd-booking', label: 'Book Online', target: '_blank' }] },
    { page: 'payments-and-fees', label: 'Payments & Fees' },
    { page: 'blog', label: 'Blog' },
    { page: 'contact', label: 'Contact Us' },
  ] },
  footer_services: { name: 'Services', items: [...services.filter(s => s.footer).map(s => ({ page: s.slug, label: s.navName })), { page: 'services', label: 'All services →' }] },
  footer_practice: { name: 'Practice', items: [
    { page: 'about', label: 'About Redlynch Dental' }, { page: 'our-team', label: 'Meet Our Team' }, { page: 'new-patients', label: 'New Patients' },
    { page: 'special-offers', label: 'New Patient Offer' }, { page: 'payments-and-fees', label: 'Payments and Fees' }, { page: 'faq', label: 'FAQs' }, { page: 'blog', label: 'Blog' }, { page: 'contact', label: 'Contact Us' },
    { url: 'rd-booking', label: 'Book Online', target: '_blank' },
  ] },
};

const dest = path.join(ROOT, 'wordpress-theme', 'redlynch-dental', 'inc', 'import', 'content.json');
fs.writeFileSync(dest, JSON.stringify(out, null, 1) + '\n', 'utf8');

/* Report: anything that still points at a static file or carries a source note. */
const json = JSON.stringify(out);
const leftovers = [...new Set((json.match(/[a-z0-9-]+\.html/g) || []))];
const notes = /healthdirect|teeth\.org\.au|Sources?:|TODO|lorem/i.test(json.replace(/ahpra\.gov\.au/g, ''));
console.log(`Wrote ${path.relative(ROOT, dest)}: ${out.pages.length} pages, ${out.posts.length} post, ${out.team.length} team members, ${Object.keys(out.media).length} image alts.`);
if (leftovers.length) console.log('Unresolved .html links:', leftovers.join(', '));
if (notes) console.log('WARNING: source/reference notes found in content.');

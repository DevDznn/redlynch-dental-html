/* Redlynch Dental: static site generator.
   Usage (from the project root):
     node _build/build.js               staging build: every page noindex, robots.txt blocks all crawlers
     node _build/build.js --production  production build: indexable pages, robots.txt allows crawling, sitemap linked
   Node.js 18+, no dependencies. Overwrites the generated .html files, sitemap.xml and robots.txt in the project root. */
'use strict';
const fs = require('fs');
const path = require('path');
const L = require('./lib');
const Pg = require('./pages');

const ROOT = path.resolve(__dirname, '..');
const production = process.argv.includes('--production');
L.setServices(Pg.services);

const pages = [
  Pg.home(), Pg.about(), Pg.team(), Pg.newPatients(), Pg.servicesIndex(),
  ...Pg.services.map(s => (s.custom ? Pg.emergency() : Pg.servicePage(s))),
  Pg.faq(), Pg.blog(), Pg.blogPost(), Pg.contact(), Pg.specialOffers(), Pg.payments(), Pg.privacy(), Pg.notFound(),
];

const seen = new Set();
for (const p of pages) {
  if (seen.has(p.file)) throw new Error('Duplicate page: ' + p.file);
  seen.add(p.file);
  fs.writeFileSync(path.join(ROOT, p.file), L.layout(p, { production }), 'utf8');
}

/* The campaign landing pages are written by hand (always noindex). Refresh only their shared parts
   from the layout: the header and navigation, the footer and the practice JSON-LD. */
const shell = L.layout({ file: 'landing.html', section: '', title: '', desc: '', body: '', noCta: true }, { production });
const part = (html, from, to) => html.slice(html.indexOf(from), html.indexOf(to));
const DENTIST_LD = /<script type="application\/ld\+json">\{"@context":"https:\/\/schema\.org","@type":"Dentist".*<\/script>/;
for (const file of ['cosmetic-dentistry.html', 'full-arch-dental-implants.html']) {
  const f = path.join(ROOT, file);
  if (!fs.existsSync(f)) continue;
  let html = fs.readFileSync(f, 'utf8');
  html = html.replace(part(html, '<a class="skip"', '<main id="main">'), () => part(shell, '<a class="skip"', '<main id="main">'));
  html = html.slice(0, html.indexOf('</main>')) + shell.slice(shell.indexOf('</main>'));
  html = html.replace(DENTIST_LD, () => shell.match(DENTIST_LD)[0]);
  fs.writeFileSync(f, html, 'utf8');
}

const today = new Date().toISOString().slice(0, 10);
const indexable = pages.filter(p => !p.noindex);
fs.writeFileSync(path.join(ROOT, 'sitemap.xml'),
  `<?xml version="1.0" encoding="UTF-8"?>\n<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\n${indexable.map(p => `  <url><loc>${L.SITE_URL}${p.file === 'index.html' ? '' : p.file}</loc><lastmod>${today}</lastmod></url>`).join('\n')}\n</urlset>\n`);
fs.writeFileSync(path.join(ROOT, 'robots.txt'), production
  ? `User-agent: *\nAllow: /\n\nSitemap: ${L.SITE_URL}sitemap.xml\n`
  : `# STAGING BUILD: blocks all crawlers. Rebuild with "node _build/build.js --production" before launch.\nUser-agent: *\nDisallow: /\n`);

console.log(`Built ${pages.length} pages (${production ? 'PRODUCTION: indexable' : 'STAGING: noindex'}).`);

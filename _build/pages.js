/* Redlynch Dental: page builders. Each returns { file, title, desc, section, body, ... } for lib.layout(). */
'use strict';
const L = require('./lib');
const { F, RISK, ico, esc, ARROW, callBtn, bookBtn, faqList, faqLd, crumbs, hoursRows, hoursMini, sideBar } = L;
const { services, CATS, SRC } = require('./services');

const T = 'assets/images/team/';
const byslug = Object.fromEntries(services.map(s => [s.slug, s]));
const svcImg = (s, cls = '', w = 800, h = 500) => s.img ? `<img src="${s.img}" alt=""${cls ? ` class="${cls}"` : ''} width="${w}" height="${h}" loading="lazy" decoding="async">` : `<span class="ph">${ico(s.icon)}</span>`;

const TEAM = [
  { id: 'dr-alex-bornsztejn', name: 'Dr Alex Bornsztejn', role: 'Principal Dentist', quals: 'BHSc(Dent), MDent', qualsLong: 'Bachelor of Health Science in Dentistry, Master of Dentistry (BHSc(Dent), MDent)', img: T + 'dr-alex-bornsztejn.webp', w: 720, h: 689,
    bio: [
      'Since early childhood, Alex always wanted to be a dentist. He grew up in Victoria and earned his dental degree from La Trobe University, then completed his Master’s degree in rural Victoria before relocating to Queensland. Alex has worked all over Queensland, from Brisbane to Townsville, and now calls Cairns home.',
      'Alex is passionate about all aspects of general dentistry. He places a high importance on continuing education and regularly attends courses run by leaders in the dental field. His enthusiasm for dentistry has taken him around the world to learn the latest technology and techniques in dental implants and wisdom teeth surgery, which he is excited to share with his patients.',
      'Outside dentistry, Alex loves spending time on the water fishing and exploring the outdoors.',
    ],
    focus: ['General dentistry', 'Implant dentistry', 'Wisdom teeth removal', 'Crowns, veneers and smile makeovers'] },
  { id: 'dr-nadia-mullins', name: 'Dr Nadia Mullins', role: 'Dentist', quals: 'BDS', qualsLong: 'Bachelor of Dental Surgery (BDS)', img: T + 'dr-nadia-mullins.webp', w: 720, h: 791,
    bio: [
      'Originally from Toronto, Canada, Dr Nadia brings a warm and personable approach to dentistry, paired with a strong foundation in clinical expertise. She completed her Bachelor of Dental Surgery locally at James Cook University in Cairns, graduating in 2018.',
      'After her studies, Dr Nadia returned to Canada to practise dentistry closer to family, building a strong reputation for her gentle approach, attention to detail and commitment to high-quality care. After five years working and living in Toronto, she is thrilled to call Cairns home once again, drawn back by the lifestyle and community.',
      'Dr Nadia has a special interest in cosmetic dentistry. She is known for her approachable personality, her ability to explain treatments clearly, and her dedication to making every visit as comfortable and reassuring as possible.',
      'Outside the clinic, Dr Nadia enjoys staying active and soaking up the outdoors. You’ll often find her running or hiking local trails, exploring new food spots around Cairns, or spending time with her husband and their dog.',
    ],
    focus: ['Special interest in cosmetic dentistry', 'Clear explanations of treatment', 'Comfortable, reassuring visits'] },
  { id: 'dr-julian-fung', name: 'Dr Julian Fung', role: 'Dentist', quals: 'BDS', qualsLong: 'Bachelor of Dental Surgery (BDS)', img: T + 'dr-julian-fung.webp', w: 720, h: 795,
    bio: [
      'Julian is proud to be a Far North Queensland local, having completed his studies at James Cook University in Cairns.',
      'Julian prides himself on being easygoing and extremely gentle with his patients. He has a passion for working with children and for easing dental fears in patients of all ages, and helping his patients feel at ease is something he takes pride in. Alongside his friendly nature, Julian is highly skilled in many technical aspects of dentistry, and the team is grateful to have him.',
      'Outside work, Julian enjoys spending time with friends and family, and with his two sausage dogs, Basil and Frankfurt.',
    ],
    focus: ['Working with children', 'Easing dental fears in all ages', 'Gentle, easygoing care'] },
];
const SUPPORT = [
  { id: 'erin', name: 'Erin', role: 'Practice Manager', quals: 'Certificate III in Dental Assisting', img: T + 'erin-practice-manager.webp' },
  { id: 'ella', name: 'Ella', role: 'Dental Assistant', quals: '', img: T + 'ella-dental-assistant.webp' },
];

const sourcesBlock = keys => keys && keys.length ? `<div class="sources"><h2>Sources for general information on this page</h2><ul>${keys.map(k => SRC[k]).filter(Boolean).map(([n, u]) => `<li><a href="${u}" target="_blank" rel="noopener">${n} ↗</a></li>`).join('')}</ul><p style="margin:8px 0 0">General information only. Your dentist will advise on what is right for you after an examination.</p></div>` : '';

/* =================================================================== HOME */
function home() {
  const faqs = [
    ['Are you taking new patients?', 'Yes. Redlynch Dental welcomes new patients of all ages. If it is your first visit, we recommend starting with a thorough consultation and dental clean. <a href="new-patients.html">What to expect as a new patient</a>.'],
    ['What should I do in a dental emergency?', `Call us straight away on <a href="${F.tel}">${F.phone}</a>. We reserve specific appointments every day for dental emergencies and will always try to see you as soon as possible. <a href="emergency-dental-care.html">Emergency dental care</a>.`],
    ['Can I book online?', `Yes. You can <a href="${F.booking}" target="_blank" rel="noopener">book online</a> through the practice’s booking system, or call ${F.phone} and our team will find a time for you.`],
    ['Do you offer payment plans?', 'Yes, payment plans are available. Ask our team about the options when you book or at your consultation.'],
    ['Can I get an appointment outside normal hours?', 'Our regular hours are Monday to Thursday 8am to 5pm and Friday 8am to 4:30pm. After-hours and weekend appointments are available at request, so call us to ask.'],
    ['I’m nervous about visiting the dentist. Can you help?', 'Yes. The team helps many anxious patients overcome their worries and complete their treatment. Let us know what music you would like, or choose a movie to watch during your treatment, and tell us about any concerns when you book.'],
  ];
  const tiles = [
    ['emergency-dental-care', 'em span2', 'Pain, a broken tooth, swelling or a lost denture? We reserve appointments every day.'],
    ['check-ups-and-dental-cleans', 'photo span2 tall', null],
    ['childrens-dentistry', 'photo', null],
    ['dental-implants', 'lime', null],
    ['invisalign', 'photo', null],
    ['teeth-whitening', 'ink', null],
    ['crowns-and-bridges', 'photo', null],
    ['wisdom-teeth', '', null],
  ];
  const tile = ([slug, cls, txt]) => {
    const s = byslug[slug];
    const photo = cls.includes('photo');
    return `<a class="tile ${cls}" href="${s.file}">${photo ? `<img src="${s.img}" alt="" loading="lazy" decoding="async" width="800" height="500">` : `<span class="ic">${ico(s.icon)}</span>`}<span class="go">${ico('arrow')}</span><h3>${s.navName}</h3><p>${txt || s.short}</p></a>`;
  };
  const body = `
<section class="hero-home" aria-labelledby="hero-h"><div class="wrap">
 <div class="hero-copy">
  <span class="eyebrow">Your local dentist in Redlynch, Cairns</span>
  <h1 id="hero-h">Gentle and <span class="u">affordable</span> dentistry, <em>for the whole family.</em></h1>
  <p class="lead">A modern dental practice in the Redlynch Commercial Hub, caring for Redlynch, Brinsmead, Edge Hill, Smithfield and the surrounding suburbs of Cairns, from children’s check-ups to high-end cosmetic cases.</p>
  <div class="actions">${bookBtn('lg')}${callBtn('ghost lg', F.phone)}</div>
  <ul class="hero-facts">
   <li>${ico('users')} New patients of all ages</li>
   <li>${ico('alert')} Emergency appointments daily</li>
   <li>${ico('wallet')} Payment plans available</li>
  </ul>
 </div>
 <div class="hero-visual">
  <div class="arch"><img src="${T}redlynch-dental-team-group.webp" alt="Three members of the Redlynch Dental team in black uniforms, smiling, including Principal Dentist Dr Alex Bornsztejn on the right" width="1100" height="733" fetchpriority="high"></div>
  <div class="float-card hours-card"><h3>Opening hours</h3><div class="status" data-status><i></i><span>Mon–Thu 8am–5pm</span></div><p>Mon–Thu 8am–5pm · Fri 8am–4:30pm<br>After-hours and weekends at request</p></div>
  <div class="float-card cred-card"><span class="dot">${ico('pin')}</span><div><b>4/2 Chelsea Lane</b><span>Redlynch Commercial Hub</span></div></div>
 </div>
</div></section>

<div class="wrap"><nav class="paths" aria-label="Quick links" data-reveal-group>
 <a class="path em" href="emergency-dental-care.html"><span class="pi">${ico('alert')}</span><span><b>I’m in pain</b><span>Emergency dental care</span></span></a>
 <a class="path" href="new-patients.html"><span class="pi">${ico('smile')}</span><span><b>I’m a new patient</b><span>What to expect</span></span></a>
 <a class="path" href="childrens-dentistry.html"><span class="pi">${ico('child')}</span><span><b>It’s for my child</b><span>Children’s dentistry</span></span></a>
 <a class="path" href="special-offers.html"><span class="pi">${ico('tag')}</span><span><b>New patient offer</b><span>Check-up, clean and X-rays</span></span></a>
</nav></div>

<section class="sec-tight" aria-label="Why choose Redlynch Dental"><div class="wrap">
 <div class="why-band" style="border-radius:var(--r-lg);overflow:hidden"><ul data-reveal-group>
  <li>${ico('wallet')}<span>Affordable pricing<small>The very best treatment at competitive prices</small></span></li>
  <li>${ico('moon')}<span>After-hours and weekend appointments<small>Available at request</small></span></li>
  <li>${ico('heart')}<span>Knowledgeable, caring, gentle staff<small>More than just a job to us</small></span></li>
  <li>${ico('cpu')}<span>Modern equipment<small>In a relaxing environment</small></span></li>
  <li>${ico('calendar')}<span>Payment plans available<small>Ask our team about options</small></span></li>
 </ul></div>
</div></section>

<section class="bg-paper" aria-labelledby="intro-h"><div class="wrap split wide-left">
 <div data-reveal>
  <span class="eyebrow">About the practice</span>
  <h2 id="intro-h" class="sr-only">About Redlynch Dental</h2>
  <p class="pull">The very best dental treatment, at <em>competitive prices</em>, in the most relaxing environment possible.</p>
  <p>Redlynch Dental is a modern Cairns dental practice located within the Redlynch Commercial Hub. We cater to all of your family’s dental needs, from children’s dentistry all the way up to high-end cosmetic cases, and everything in between.</p>
  <p>All of our staff are knowledgeable and passionate about the work we do. We take the time to listen to you, understand what you want to achieve, and then help guide you down the right path to get there. Many of our patients tell us they wish they had come to see us earlier.</p>
  <ul class="chips" aria-label="Areas we serve">${F.suburbs.map(s => `<li>${s}</li>`).join('')}</ul>
  <p class="mt-2"><a class="text-link" href="about.html">More about Redlynch Dental ${ico('arrow')}</a></p>
 </div>
 <div class="stack-img" data-reveal>
  <div class="main"><img src="assets/images/services/dental-examination.webp" alt="A dentist examining a smiling patient’s teeth" width="1100" height="459" loading="lazy" decoding="async"></div>
  <div class="note"><b>Let us know what music you’d like</b>Or choose a movie to watch while your treatment is completed. We help many anxious patients overcome their worries.</div>
 </div>
</div></section>

<section aria-labelledby="svc-h"><div class="wrap">
 <div class="sec-head" data-reveal><span class="eyebrow">Our services</span><h2 id="svc-h">Everything your family needs, under one roof.</h2><p>Fifteen services, from routine check-ups and children’s dentistry to implants, Invisalign® and emergency care.</p></div>
 <div class="bento" data-reveal-group>${tiles.map(tile).join('')}</div>
 <p class="mt-2 center"><a class="btn dark" href="services.html">View all 15 services ${ARROW}</a></p>
</div></section>

<section class="bg-forest" aria-labelledby="comfort-h"><div class="wrap">
 <div class="split wide-right top">
  <div class="sec-head" data-reveal style="margin-bottom:0"><span class="eyebrow">Our approach</span><h2 id="comfort-h">We listen first. Then we help you get where you want to be.</h2><p>Dental visits shouldn’t feel rushed or intimidating. We take the time to understand what matters to you, explain your options clearly, and make your visit as comfortable as we can.</p><p class="mt-2">${bookBtn()}</p></div>
  <div class="comfort" style="grid-template-columns:1fr" data-reveal-group>
   <div><span>${ico('music')}</span><h3>Your choice of music or a movie</h3><p>Let us know what music we can play for you, or choose a movie to watch while your treatment is completed.</p></div>
   <div><span>${ico('heart')}</span><h3>Care for anxious patients</h3><p>We help many anxious patients overcome their apprehensions and complete their dental treatment, at a pace that suits them.</p></div>
   <div><span>${ico('book')}</span><h3>A clear, printed treatment plan</h3><p>After a comprehensive check-up you receive a printed plan with the most accurate estimate we can give of the time and cost involved.</p></div>
  </div>
 </div>
</div></section>

<section aria-labelledby="team-h"><div class="wrap">
 <div class="split" style="align-items:end;margin-bottom:40px"><div class="sec-head" data-reveal style="margin-bottom:0"><span class="eyebrow">Meet the dentists</span><h2 id="team-h">Friendly faces, gentle hands.</h2></div><p data-reveal style="margin:0">Our dentists trained at La Trobe University and James Cook University, and between them bring experience from across Queensland and overseas. <a href="our-team.html">Read the team’s full profiles</a>.</p></div>
 <div class="team-row" data-reveal-group>${TEAM.map(m => `<a class="member" href="our-team.html#${m.id}"><div class="arch"><img src="${m.img}" alt="${m.name}, ${m.role} at Redlynch Dental" width="${m.w}" height="${m.h}" loading="lazy" decoding="async"></div><h3>${m.name}</h3><span class="role">${m.role}</span><span class="qual">${m.quals}</span></a>`).join('')}</div>
 <div class="support-row" data-reveal-group>${SUPPORT.map(m => `<div class="support"><img src="${m.img}" alt="${m.name}, ${m.role} at Redlynch Dental" width="92" height="92" loading="lazy" decoding="async"><div><h3>${m.name}</h3><p>${m.role}${m.quals ? ` · ${m.quals}` : ''}</p></div></div>`).join('')}</div>
</div></section>

<section class="bg-sand" aria-labelledby="np-h"><div class="wrap">
 <div class="sec-head" data-reveal><span class="eyebrow">New patients</span><h2 id="np-h">New to Redlynch Dental? Start with a thorough check-up.</h2><p>We welcome new patients of all ages. Your first visit is a comprehensive consultation and clean, so we can understand your oral health and plan your care together.</p></div>
 <div class="offer" data-reveal>
  <div class="o-img"><img src="assets/images/offers/new-patient-special-offer.webp" alt="New patient special offer: comprehensive dental check-up; all necessary X-rays including full-face X-rays; scale, clean and polish; fluoride treatment; treatment plan. Gap free with any health fund. Not in a health fund? All the above for only $199. T&amp;Cs apply." width="880" height="1100" loading="lazy" decoding="async"></div>
  <div class="o-body">
   <span class="eyebrow">New patient special offer</span>
   <h3>Your first visit includes</h3>
   ${`<ul class="ticks"><li>Comprehensive dental check-up</li><li>All necessary X-rays, including full-face X-rays</li><li>Scale, clean and polish</li><li>Fluoride treatment</li><li>A printed treatment plan</li></ul>`}
   <div class="price-strip"><div><b>Gap free</b><span>with any health fund</span></div><div><b>$199</b><span>if you’re not in a health fund</span></div></div>
   <p class="fine">Terms and conditions apply. Offer as published by Redlynch Dental; please confirm the current offer and its conditions with our team when booking.</p>
   <div class="actions mt-1">${bookBtn()}<a class="btn ghost" href="new-patients.html">New patient information</a></div>
  </div>
 </div>
</div></section>

<section aria-labelledby="em-h"><div class="wrap">
 <div class="em-band" data-reveal>
  <div>
   <span class="eyebrow" style="color:#fff">Dental emergencies</span>
   <h2 id="em-h">In pain? Don’t wait. Call us now.</h2>
   <p>Dental emergencies can be painful and distressing, so we reserve specific appointments every day for them. Problems can get worse quickly if left untreated.</p>
   <a class="big-tel" href="${F.tel}">${ico('phone')} ${F.phone}</a>
   <div class="actions"><a class="btn white" href="emergency-dental-care.html">What counts as an emergency ${ARROW}</a></div>
  </div>
  <ul aria-label="Dental emergencies we see">
   <li>${ico('tooth')} Painful tooth or gums</li>
   <li>${ico('broken')} Broken teeth and fillings</li>
   <li>${ico('swelling')} Facial swelling</li>
   <li>${ico('bolt')} Trauma to the face, teeth or gums</li>
   <li>${ico('denture')} Broken or lost denture</li>
  </ul>
 </div>
</div></section>

<section class="bg-paper" aria-labelledby="fam-h"><div class="wrap split">
 <div data-reveal>
  <div class="arch" style="aspect-ratio:4/4.2;max-width:520px"><img src="assets/images/services/childrens-dentistry-smiling-girl.webp" alt="A smiling young girl in a dental chair" width="1100" height="729" loading="lazy" decoding="async" style="object-position:62% 50%"></div>
 </div>
 <div data-reveal>
  <span class="eyebrow">Preventive and family dentistry</span>
  <h2 id="fam-h">Healthy habits start young, and last a lifetime.</h2>
  <p>Fun, positive early experiences at the dentist help children grow up without unnecessary dental fears. Free kids dentistry is listed among our services. Ask our team about eligibility when you book.</p>
  <p>For the whole family, regular check-ups and cleans let us find and treat small problems early, which saves time and money and helps you keep your natural teeth.</p>
  <ul class="ticks">
   <li>Brush twice a day with a fluoride toothpaste</li>
   <li>Clean between your teeth every day</li>
   <li>Keep sugary food and drinks to a minimum, and drink tap water</li>
   <li>Wear a custom mouthguard for contact sport</li>
  </ul>
  <div class="actions">${['childrens-dentistry', 'check-ups-and-dental-cleans', 'mouthguards'].map(s => `<a class="btn ${s === 'childrens-dentistry' ? '' : 'ghost'} sm" href="${byslug[s].file}">${byslug[s].navName}</a>`).join('')}</div>
  <p class="fine mt-1">General oral health information. See the <a href="https://www.healthdirect.gov.au/treatments-and-self-care/looking-after-your-baby-or-childs-teeth" target="_blank" rel="noopener">healthdirect guide to children’s teeth ↗</a>.</p>
 </div>
</div></section>

<section class="bg-ink" aria-labelledby="pay-h"><div class="wrap split wide-left">
 <div data-reveal>
  <span class="eyebrow">Costs and payment</span>
  <h2 id="pay-h">Clear costs, and flexible ways to pay.</h2>
  <p>We pride ourselves on providing the very best treatment at competitive prices. Before treatment begins, you receive a printed treatment plan with the most accurate estimate we can give of the time and cost involved.</p>
  <p>Payment plans are available, so ask our team which options suit you.</p>
 </div>
 <ol class="num-list" data-reveal-group>
  <li><div><h3>Printed treatment plan</h3><p>Know the time and cost estimate up front.</p></div></li>
  <li><div><h3>Payment plans available</h3><p>Ask us about spreading the cost of larger treatment.</p></div></li>
  <li><div><h3>Complimentary implant consultation</h3><p>Find out whether implants suit you, and what they would cost.</p></div></li>
 </ol>
</div></section>

<section aria-labelledby="faq-h"><div class="wrap split top">
 <div class="sec-head" data-reveal><span class="eyebrow">Questions</span><h2 id="faq-h">Things patients often ask us</h2><p>Can’t see your question? Call us on <a href="${F.tel}">${F.phone}</a>, or read the <a href="faq.html">full FAQ</a>.</p></div>
 ${faqList(faqs)}
</div></section>

<section class="bg-paper" aria-labelledby="visit-h"><div class="wrap">
 <div class="sec-head" data-reveal><span class="eyebrow">Visit us</span><h2 id="visit-h">Find us in the Redlynch Commercial Hub</h2></div>
 <div class="contact-grid">
  <div class="c-cards" data-reveal-group>
   <div class="c-card"><span class="ic">${ico('pin')}</span><div><h3>Address</h3><p><a href="${F.mapsLink}" target="_blank" rel="noopener">${F.street}<br>${F.suburb} ${F.state} ${F.postcode}</a></p></div></div>
   <div class="c-card"><span class="ic">${ico('phone')}</span><div><h3>Phone</h3><p><a href="${F.tel}">${F.phone}</a></p></div></div>
   <div class="c-card"><span class="ic">${ico('mail')}</span><div><h3>Email</h3><p><a href="mailto:${F.email}">${F.email}</a></p></div></div>
   <div class="c-card"><span class="ic">${ico('clock')}</span><div style="flex:1"><h3>Opening hours</h3>${hoursRows()}</div></div>
  </div>
  <div data-reveal>${mapBox()}</div>
 </div>
</div></section>`;
  return { file: 'index.html', section: 'home', title: 'Redlynch Dental | Gentle & Affordable Dentist in Redlynch, Cairns', desc: `Redlynch Dental is a modern family dental practice at ${F.address}. Check-ups, children’s dentistry, implants, Invisalign® and emergency appointments daily. Call ${F.phone}.`, body, ld: [faqLd(faqs), { '@context': 'https://schema.org', '@type': 'WebSite', name: 'Redlynch Dental', url: L.SITE_URL }], cta: { h: 'Book your visit to Redlynch Dental', p: 'New patients of all ages are welcome. Call us or book online, and we’ll find a time that suits you.' } };
}

function mapBox() {
  return `<div class="map" data-map-src="${esc(F.mapEmbed)}"><div class="map-fallback"><div><p style="margin-bottom:14px"><strong>Redlynch Dental</strong><br>${F.address}</p><div class="actions" style="justify-content:center"><button class="btn dark sm" type="button">${ico('pin')} Show the map here</button><a class="btn ghost sm" href="${F.mapsLink}" target="_blank" rel="noopener">Open in Google Maps ↗</a></div><p class="fine" style="margin-top:12px">The map loads from Google when you choose to show it.</p></div></div></div>`;
}

/* =================================================================== ABOUT */
function about() {
  const trail = [['Home', 'index.html'], ['About Us', 'about.html']];
  const body = `
<section class="page-hero split-hero"><div class="wrap">
 <div>${crumbs(trail)}<span class="eyebrow">About Redlynch Dental</span><h1>A modern practice with a local, family feel.</h1><p class="lead">Redlynch Dental is a modern Cairns dental practice in the Redlynch Commercial Hub, caring for local families with gentle, affordable dentistry.</p>
 <ul class="hero-meta"><li>${F.street}, ${F.suburb}</li><li>New patients of all ages</li><li>Emergency appointments daily</li></ul></div>
 <div class="ph-img"><img src="${T}redlynch-dental-team-group.webp" alt="Three members of the Redlynch Dental team in black uniforms, smiling" width="1100" height="733" fetchpriority="high"></div>
</div></section>

<section class="bg-paper"><div class="wrap split wide-left top">
 <div data-reveal>
  <span class="eyebrow">Who we are</span>
  <h2>More than just a job to us</h2>
  <p>We proudly serve the local community of Redlynch, as well as Brinsmead, Edge Hill, Smithfield and the surrounding suburbs of Cairns. We cater to all of your family’s dental needs, from children’s dentistry all the way up to high-end cosmetic cases, and everything in between.</p>
  <p>We take great pride in providing the very best dental treatment, at competitive prices, in the most relaxing environment possible. All of our staff are knowledgeable and passionate about what we do. It is definitely more than just a job to us.</p>
  <p>We take the time to listen to you and understand what you want to achieve, and then help guide you down the right path to get there. Many of our patients say they wish they had come to see us earlier.</p>
 </div>
 <aside class="card" data-reveal style="background:var(--lime-wash);border:0">
  <h3>At a glance</h3>
  <ul class="ticks" style="margin:0">
   <li>Located within the Redlynch Commercial Hub, at ${F.street}</li>
   <li>Three dentists, supported by our practice manager and dental assistant</li>
   <li>Appointments reserved every day for dental emergencies</li>
   <li>After-hours and weekend appointments available at request</li>
   <li>Payment plans available</li>
   <li>Online booking available</li>
  </ul>
 </aside>
</div></section>

<section><div class="wrap">
 <div class="sec-head" data-reveal><span class="eyebrow">Why patients choose us</span><h2>What you can expect from us</h2></div>
 <div class="grid g3" data-reveal-group>
  <div class="card"><div class="ic">${ico('wallet')}</div><h3>Affordable pricing</h3><p>The very best treatment at competitive prices, with a printed treatment plan and cost estimate before you begin. Payment plans are available.</p></div>
  <div class="card"><div class="ic">${ico('heart')}</div><h3>Knowledgeable, caring and gentle</h3><p>A team that listens, explains your options clearly, and helps anxious patients feel at ease.</p></div>
  <div class="card"><div class="ic">${ico('cpu')}</div><h3>Modern equipment</h3><p>Including 3D X-ray planning and computer-guided implant placement, and high-precision magnification for veneers.</p></div>
  <div class="card"><div class="ic">${ico('music')}</div><h3>A relaxing environment</h3><p>Tell us what music you would like, or choose a movie to watch while your treatment is completed.</p></div>
  <div class="card"><div class="ic">${ico('moon')}</div><h3>Flexible appointments</h3><p>After-hours and weekend appointments are available at request, and you can book online.</p></div>
  <div class="card"><div class="ic">${ico('users')}</div><h3>For the whole family</h3><p>From children’s first visits to implants, Invisalign® and cosmetic dentistry, all in one place.</p></div>
 </div>
</div></section>

<section class="bg-forest"><div class="wrap">
 <div class="sec-head" data-reveal><span class="eyebrow">Our dentists</span><h2>Meet the people who will look after you</h2></div>
 <div class="team-row" data-reveal-group>${TEAM.map(m => `<a class="member" href="our-team.html#${m.id}" style="color:#fff"><div class="arch"><img src="${m.img}" alt="${m.name}, ${m.role}" width="${m.w}" height="${m.h}" loading="lazy" decoding="async"></div><h3>${m.name}</h3><span class="role" style="color:var(--lime)">${m.role}</span><span class="qual" style="color:#C3CEC6">${m.quals}</span></a>`).join('')}</div>
 <p class="mt-2"><a class="btn" href="our-team.html">Meet our team ${ARROW}</a></p>
</div></section>

<section class="bg-paper"><div class="wrap split">
 <div data-reveal><span class="eyebrow">Location</span><h2>Easy to find, easy to visit</h2><p>You will find us at ${F.street} in Redlynch, within the Redlynch Commercial Hub, convenient for families across Redlynch, Brinsmead, Edge Hill, Smithfield and the surrounding suburbs of Cairns.</p>${hoursRows()}<p class="mt-2"><a class="text-link" href="contact.html">Contact details and map ${ico('arrow')}</a></p></div>
 <div data-reveal>${mapBox()}</div>
</div></section>`;
  return { file: 'about.html', section: 'about', title: 'About Redlynch Dental | Modern Family Dentist in Redlynch, Cairns', desc: 'Redlynch Dental is a modern family dental practice in the Redlynch Commercial Hub, serving Redlynch, Brinsmead, Edge Hill and Smithfield. Gentle, affordable care.', body, trail };
}

/* =================================================================== TEAM */
function team() {
  const trail = [['Home', 'index.html'], ['About Us', 'about.html'], ['Meet Our Team', 'our-team.html']];
  const body = `
<section class="page-hero"><div class="wrap">
 ${crumbs(trail)}<span class="eyebrow">Meet our team</span><h1>The people behind your smile.</h1><p class="lead">Three dentists and a dedicated support team who take the time to listen, explain clearly and make every visit as comfortable as possible.</p>
 <ul class="team-jump">${TEAM.map(m => `<li><a href="#${m.id}"><img src="${m.img}" alt="" width="34" height="34">${m.name}</a></li>`).join('')}<li><a href="#support-team"><img src="${SUPPORT[0].img}" alt="" width="34" height="34">Support team</a></li></ul>
</div></section>

<section class="bg-paper"><div class="wrap">
 ${TEAM.map((m, i) => `<article class="profile${i % 2 ? ' flip' : ''}" id="${m.id}" aria-labelledby="${m.id}-h">
  <div class="p-photo" data-reveal><div class="arch"><img src="${m.img}" alt="Portrait of ${m.name}, ${m.role} at Redlynch Dental" width="${m.w}" height="${m.h}" loading="lazy" decoding="async"></div></div>
  <div data-reveal>
   <span class="role">${m.role}</span>
   <h2 id="${m.id}-h">${m.name}</h2>
   <span class="quals">${m.qualsLong}</span>
   ${m.bio.map(p => `<p>${p}</p>`).join('')}
   <div class="interests"><h3>${i === 0 ? 'Areas of expertise' : 'Known for'}</h3>${`<ul class="ticks">${m.focus.map(f => `<li>${f}</li>`).join('')}</ul>`}</div>
   <div class="actions mt-1">${bookBtn('sm')}</div>
  </div>
 </article>`).join('')}
</div></section>

<section id="support-team" aria-labelledby="sup-h"><div class="wrap">
 <div class="sec-head" data-reveal><span class="eyebrow">Support team</span><h2 id="sup-h">The friendly faces who keep everything running</h2><p>From your first phone call to your follow-up, our support team makes sure your visit runs smoothly.</p></div>
 <div class="support-grid" data-reveal-group>${SUPPORT.map(m => `<div class="support-card"><img src="${m.img}" alt="Portrait of ${m.name}, ${m.role} at Redlynch Dental" width="720" height="800" loading="lazy" decoding="async"><div><span class="eyebrow">${m.role}</span><h3 style="font-size:1.8rem">${m.name}</h3>${m.quals ? `<p>${m.quals}</p>` : ''}</div></div>`).join('')}</div>
</div></section>`;
  const ld = TEAM.map(m => ({ '@context': 'https://schema.org', '@type': 'Person', name: m.name, jobTitle: m.role, image: L.SITE_URL + m.img, worksFor: { '@id': L.SITE_URL + '#dentist' }, hasCredential: m.qualsLong }));
  return { file: 'our-team.html', section: 'about', title: 'Meet Our Team | Dentists at Redlynch Dental, Cairns', desc: 'Meet the Redlynch Dental team: Principal Dentist Dr Alex Bornsztejn, Dr Nadia Mullins and Dr Julian Fung, with Practice Manager Erin and Dental Assistant Ella.', body, trail, ld, ogImage: T + 'dr-alex-bornsztejn.webp' };
}

/* =================================================================== NEW PATIENTS */
function newPatients() {
  const trail = [['Home', 'index.html'], ['About Us', 'about.html'], ['New Patients', 'new-patients.html']];
  const faqs = [
    ['Do I need a referral?', 'No referral is needed for general dental care. Just call us or book online.'],
    ['What if I haven’t been to the dentist in years?', 'You are very welcome. There is no judgement. Many of our patients say they wish they had come in sooner. We will start with a thorough check-up and talk you through what we find.'],
    ['Can I bring my children to their first visit?', 'Yes. Patients of all ages are welcome, and positive early visits help children feel comfortable at the dentist. See <a href="childrens-dentistry.html">children’s dentistry</a>.'],
    ['Will I be told the cost before treatment?', 'Yes. After your comprehensive check-up you receive a printed treatment plan with the most accurate estimate we can give of the time and cost involved. Ask us about anything you are unsure of.'],
  ];
  const body = `
<section class="page-hero cream split-hero"><div class="wrap">
 <div>${crumbs(trail)}<span class="eyebrow">New patients</span><h1>Welcome. Here’s what to expect at your first visit.</h1><p class="lead">Redlynch Dental welcomes new patients of all ages. We recommend beginning with a thorough consultation and dental clean, so we can understand your oral health and plan your care with you.</p><div class="actions mt-1">${bookBtn('lg')}${callBtn('ghost lg', F.phone)}</div></div>
 <div class="card" style="background:var(--ink);color:#C9D4CD;border:0;padding:32px">
  <span class="eyebrow">Your first visit</span>
  <h2 style="color:#fff;font-size:1.7rem">A comprehensive check-up and clean</h2>
  <ul class="ticks" style="margin:0">
   <li>Examine all of your teeth and gums</li><li>Assess your jaw joints</li><li>Take the X-rays you need, close-up and full-face</li><li>Photos of any potential problems, so you can see them too</li><li>Scale, clean and polish</li><li>Topical fluoride treatment</li><li>A printed treatment plan with time and cost estimate</li>
  </ul>
 </div>
</div></section>

<section class="bg-paper"><div class="wrap">
 <div class="sec-head" data-reveal><span class="eyebrow">Step by step</span><h2>Getting started is easy</h2></div>
 ${`<ol class="timeline" style="--n:4" data-reveal-group>
  <li><h3>Book your visit</h3><p>Call ${F.phone} or <a href="${F.booking}" target="_blank" rel="noopener">book online</a>. Let us know if you are in pain, nervous, or have something specific you want to discuss.</p></li>
  <li><h3>Tell us about you</h3><p>We ask about your general health, medications and any dental concerns, so we can care for you safely.</p></li>
  <li><h3>Check-up and clean</h3><p>A thorough examination, any X-rays needed, then a scale, clean, polish and fluoride.</p></li>
  <li><h3>Your treatment plan</h3><p>We explain what we found, show you photos, and give you a printed plan with time and cost estimates.</p></li>
 </ol>`}
</div></section>

<section><div class="wrap split top">
 <div data-reveal>
  <span class="eyebrow">Before you come in</span>
  <h2>A few things that help</h2>
  <p>These are general suggestions to help your first appointment go smoothly. If you’re not sure about anything, just call us.</p>
  <ul class="ticks">
   <li>A list of any medications or supplements you take, and any allergies</li>
   <li>Details of any medical conditions, or recent changes to your health</li>
   <li>Your health fund card, if you have extras cover</li>
   <li>Any recent dental X-rays or records from a previous dentist, if you have them</li>
   <li>A note of any questions or concerns you’d like to talk about</li>
  </ul>
 </div>
 <div data-reveal>
  <span class="eyebrow">Feeling nervous?</span>
  <h2>You’re in good company</h2>
  <p>We help many anxious patients overcome their apprehensions and complete their dental treatment. Tell us how you are feeling when you book. We can take things slowly, explain each step before we start, and agree a signal for you to pause at any time.</p>
  <p>Let us know what music you would like us to play, or choose a movie to watch while your treatment is completed.</p>
  <div class="callout"><p><strong>Understanding your treatment plan.</strong> You are always welcome to ask why a treatment is recommended, what the alternatives are, what happens if you wait, and what it will cost. It is your decision, and we want you to feel confident making it.</p></div>
 </div>
</div></section>

<section class="bg-sand"><div class="wrap">
 <div class="offer" data-reveal>
  <div class="o-img"><img src="assets/images/offers/new-patient-special-offer.webp" alt="New patient special offer: comprehensive dental check-up; all necessary X-rays including full-face X-rays; scale, clean and polish; fluoride treatment; treatment plan. Gap free with any health fund. Not in a health fund? All the above for only $199. T&amp;Cs apply." width="880" height="1100" loading="lazy" decoding="async"></div>
  <div class="o-body"><span class="eyebrow">New patient special offer</span><h3>Comprehensive check-up, X-rays, clean and treatment plan</h3>
   <div class="price-strip"><div><b>Gap free</b><span>with any health fund</span></div><div><b>$199</b><span>if you’re not in a health fund</span></div></div>
   <p class="fine">Terms and conditions apply. Please confirm the current offer and its conditions with our team when booking.</p>
   <div class="actions mt-1">${bookBtn()}<a class="btn ghost" href="special-offers.html">Offer details</a></div></div>
 </div>
</div></section>

<section class="bg-paper"><div class="wrap split top">
 <div class="sec-head" data-reveal><span class="eyebrow">Questions</span><h2>New patient questions</h2><p>More answers on our <a href="faq.html">FAQ page</a>.</p></div>
 ${faqList(faqs)}
</div></section>`;
  return { file: 'new-patients.html', section: 'about', title: 'New Patients | Your First Visit to Redlynch Dental, Cairns', desc: 'New patients of all ages are welcome at Redlynch Dental. What to expect at your first visit: a comprehensive check-up, X-rays, a clean and a printed treatment plan.', body, trail, ld: [faqLd(faqs)] };
}

/* =================================================================== SERVICES INDEX */
function servicesIndex() {
  const trail = [['Home', 'index.html'], ['Services', 'services.html']];
  const groups = CATS.map(c => {
    const list = services.filter(s => s.cat === c.id);
    return `<div class="svc-group" id="${c.id}"><h2>${c.name} <small>${list.length} service${list.length > 1 ? 's' : ''}</small></h2><p>${c.blurb}</p>
 <ul class="svc-list" data-reveal-group>${list.map(s => `<li><a class="svc-row${s.cat === 'urgent' ? ' em' : ''}" href="${s.file}">${s.img ? `<img src="${s.img}" alt="" width="150" height="104" loading="lazy" decoding="async"${s.imgContain ? ' style="object-fit:contain;background:#fff"' : ''}>` : `<span class="ph">${ico(s.icon)}</span>`}<div><h3>${s.name}</h3><p>${s.short}</p></div><span class="go">${ico('arrow')}</span></a></li>`).join('')}</ul></div>`;
  }).join('');
  const body = `
<section class="page-hero"><div class="wrap">
 ${crumbs(trail)}<span class="eyebrow">Our services</span><h1>Complete dental care for every age and every smile.</h1><p class="lead">From check-ups and children’s dentistry to implants, Invisalign® and emergency care. Choose a service to learn more, or call us and we will point you in the right direction.</p>
 <div class="actions mt-1">${callBtn('', F.phone)}<a class="btn ember" href="emergency-dental-care.html">${ico('alert')} Dental emergency?</a></div>
</div></section>
<section class="bg-paper"><div class="wrap svc-index">
 <nav aria-label="Service categories"><ul class="svc-jump" data-spy>${CATS.map(c => `<li><a href="#${c.id}">${c.name}</a></li>`).join('')}</ul></nav>
 <div>${groups}<p class="fine">Treatment suitability depends on an individual clinical assessment. ${RISK}</p></div>
</div></section>`;
  const ld = [{ '@context': 'https://schema.org', '@type': 'ItemList', itemListElement: services.map((s, i) => ({ '@type': 'ListItem', position: i + 1, name: s.name, url: L.SITE_URL + s.file })) }];
  return { file: 'services.html', section: 'services', title: 'Dental Services Redlynch | Redlynch Dental Cairns', desc: 'All dental services at Redlynch Dental, Cairns: check-ups, children’s dentistry, fillings, crowns, implants, dentures, root canals, extractions, wisdom teeth, Invisalign®, whitening, veneers and emergency care.', body, trail, ld };
}

/* =================================================================== SERVICE PAGE */
function servicePage(s) {
  const trail = [['Home', 'index.html'], ['Services', 'services.html'], [s.navName.replace('®', ''), s.file]];
  let visual;
  if (!s.img) visual = `<div class="type-art" aria-hidden="true"><span class="ta-ic">${ico(s.icon)}</span><span class="ta-k">${s.eyebrow}</span><p class="ta-t">${s.artText || s.name}</p></div>`;
  else if (s.imgContain) visual = `<div class="ph-img" style="background:#fff;aspect-ratio:1/1;max-width:440px;justify-self:center;width:100%"><img src="${s.img}" alt="${s.imgAlt}" style="object-fit:contain;padding:14px" width="1024" height="1024" fetchpriority="high"></div>`;
  else visual = `<div class="ph-img"><img src="${s.img}" alt="${s.imgAlt}" width="1100" height="733" fetchpriority="high"${s.imgPos ? ` style="object-position:${s.imgPos}"` : ''}></div>`;
  const rel = (s.related || []).map(r => byslug[r]).filter(Boolean);
  const body = `
<section class="page-hero split-hero"><div class="wrap">
 <div>${crumbs(trail)}<span class="eyebrow">${s.eyebrow}</span><h1>${s.h1}</h1><p class="lead">${s.lead}</p>
  <div class="actions mt-1">${bookBtn()}${callBtn('ghost-light', F.phone)}</div>
  ${s.heroMeta ? `<ul class="hero-meta">${s.heroMeta.map(m => `<li>${m}</li>`).join('')}</ul>` : ''}
 </div>
 ${visual}
</div></section>
<section class="bg-paper"><div class="wrap layout">
 <article class="prose">${s.body()}${sourcesBlock(s.sources)}</article>
 ${sideBar()}
</div></section>
${s.faqs && s.faqs.length ? `<section aria-labelledby="sfaq-h"><div class="wrap split top"><div class="sec-head" data-reveal><span class="eyebrow">Questions</span><h2 id="sfaq-h">${s.navName.replace('®', '®')}: your questions answered</h2><p>Still unsure? Call <a href="${F.tel}">${F.phone}</a> and talk to our team.</p></div>${faqList(s.faqs)}</div></section>` : ''}
${s.blog ? `<section class="bg-sand sec-tight"><div class="wrap"><a class="post-feature" href="blog-why-choose-dental-implants.html" data-reveal><img src="assets/images/blog/dental-implant-crown-illustration.webp" alt="" width="1100" height="825" loading="lazy" decoding="async"><div class="pf-body"><div class="post-meta"><span class="tag">From our blog</span><span>Dr Alex Bornsztejn</span></div><h2>Why choose dental implants?</h2><p>The advantages of implants, from our Principal Dentist.</p><span class="text-link">Read the article ${ico('arrow')}</span></div></a></div></section>` : ''}
${rel.length ? `<section class="bg-paper sec-tight" aria-labelledby="rel-h"><div class="wrap"><h2 id="rel-h" style="font-size:1.7rem">Related treatments</h2><div class="related" data-reveal-group>${rel.map(r => `<a class="rel" href="${r.file}">${r.img ? `<img src="${r.img}" alt="" width="84" height="84" loading="lazy" decoding="async"${r.imgContain ? ' style="object-fit:contain;background:#fff"' : ''}>` : `<span class="ph">${ico(r.icon)}</span>`}<span><b>${r.name}</b><span>${r.short.split('.')[0]}.</span></span></a>`).join('')}</div></div></section>` : ''}`;
  const ld = [{ '@context': 'https://schema.org', '@type': 'MedicalProcedure', name: s.name, description: s.short, url: L.SITE_URL + s.file, provider: { '@id': L.SITE_URL + '#dentist' } }];
  if (s.faqs && s.faqs.length) ld.push(faqLd(s.faqs));
  return { file: s.file, section: 'services', title: s.title, desc: s.desc, body, trail, ld, ogImage: s.img || undefined, cta: { h: `Talk to us about ${s.navName.toLowerCase().replace('invisalign®', 'Invisalign®')}`, p: 'Every treatment starts with an assessment and a conversation about your options. Call our Redlynch team or book online.' } };
}

/* =================================================================== EMERGENCY */
function emergency() {
  const s = byslug['emergency-dental-care'];
  const trail = [['Home', 'index.html'], ['Services', 'services.html'], ['Emergency Dental Care', s.file]];
  const faqs = [
    ['Can I be seen today?', 'We reserve specific appointments every day for dental emergencies, and will always strive to make time to see you as soon as possible. Call as early as you can. Same-day appointments depend on availability, so call us straight away.'],
    ['What if it’s outside opening hours?', `Our hours are Monday to Thursday 8am to 5pm and Friday 8am to 4:30pm. After-hours and weekend appointments are available at request, but are not guaranteed, so call ${F.phone} to check. If you can’t reach us and your symptoms are severe or getting worse, go to your nearest hospital emergency department or call 000. For health advice, you can call 13 HEALTH (13 43 25 84) in Queensland.`],
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
  const body = `
<section class="em-hero"><div class="wrap">
 <div>
  ${crumbs(trail)}
  <span class="em-tag">${ico('alert')} Dental emergency</span>
  <h1>Emergency dental care in Redlynch</h1>
  <p class="lead" style="color:#C8D4CC">Dental emergencies can be painful and distressing. That is why we reserve specific appointments every day for them. If you are experiencing a dental emergency, please call us immediately.</p>
  <div class="actions mt-1"><a class="btn ember lg" href="${F.tel}">${ico('phone')} Call ${F.phone} now</a><a class="btn ghost-light lg" href="contact.html">Contact and directions</a></div>
 </div>
 <div class="em-call" role="region" aria-label="Call the practice">
  <span class="label">Call us immediately</span>
  <a class="num" href="${F.tel}">${F.phone}</a>
  <div class="status" data-status><i></i><span>Mon–Thu 8am–5pm · Fri 8am–4:30pm</span></div>
  <a class="btn ember" href="${F.tel}">${ico('phone')} Tap to call</a>
  <p class="small">We will always strive to make time for you to be seen as soon as possible. ${F.street}, ${F.suburb}.</p>
 </div>
</div></section>
<div class="call-000" role="note"><div class="wrap">${ico('alert')}<p><strong>Call <a href="tel:000">000</a> or go to your nearest hospital emergency department</strong> if you have swelling that affects your breathing, swallowing or eye, bleeding that won’t stop, a serious head or facial injury, or you feel very unwell. This page is general information and can’t diagnose your problem.</p></div></div>

<section class="bg-paper" aria-labelledby="what-h"><div class="wrap">
 <div class="sec-head" data-reveal><span class="eyebrow">What counts as a dental emergency</span><h2 id="what-h">If it hurts, it’s broken or it’s swollen, call us.</h2><p>Dental emergencies are often serious, and if left untreated they can get worse quickly. A dental emergency may include:</p></div>
 <div class="em-grid" data-reveal-group>
  ${ems.map(([i, h, p, d]) => `<div class="em-card"><span class="ic">${ico(i)}</span><h3>${h}</h3><p>${p}</p><div class="do"><b>Right now</b>${d}</div></div>`).join('')}
  <div class="em-card call-card"><h3>Not sure if it’s an emergency?</h3><p>Call anyway. Tell us what has happened and we will advise you on what to do next.</p><a class="btn white" href="${F.tel}">${ico('phone')} ${F.phone}</a></div>
 </div>
</div></section>

<section aria-labelledby="next-h"><div class="wrap split top">
 <div data-reveal>
  <span class="eyebrow">What happens next</span>
  <h2 id="next-h">What to do, step by step</h2>
  <ol class="num-list">
   <li><div><h3>Call us straight away</h3><p>Phone <a href="${F.tel}">${F.phone}</a>. Tell us what happened, where it hurts, how long it has been going on and whether there is any swelling.</p></div></li>
   <li><div><h3>We find you a time</h3><p>Specific appointments are reserved every day for emergencies. We will offer the earliest suitable time and tell you what to do in the meantime.</p></div></li>
   <li><div><h3>Assessment and relief</h3><p>The dentist examines the problem, takes any X-rays that help, and focuses on relieving your pain and stabilising the problem.</p></div></li>
   <li><div><h3>A plan for what’s next</h3><p>If you need further treatment, we explain your options and costs before booking anything.</p></div></li>
  </ol>
 </div>
 <div data-reveal>
  <figure style="margin:0 0 24px"><img src="${s.img}" alt="${s.imgAlt}" width="800" height="350" loading="lazy" decoding="async" style="border-radius:var(--r-lg);width:100%"></figure>
  <div class="when">
   <div class="w-dent"><h3>${ico('phone')} Call the dentist</h3><ul class="ticks" style="margin:0"><li>Toothache or sensitive gums</li><li>Chipped or broken tooth</li><li>Lost filling or crown</li><li>Broken or lost denture</li><li>Mild swelling around a tooth</li></ul></div>
   <div class="w-000"><h3>${ico('alert')} Call 000</h3><ul class="ticks" style="margin:0"><li>Swelling affecting breathing, swallowing or the eye</li><li>Bleeding that won’t stop</li><li>Serious head or facial injury</li><li>Feeling very unwell, with high fever</li></ul></div>
  </div>
 </div>
</div></section>

<section class="bg-paper" aria-labelledby="efaq-h"><div class="wrap split top">
 <div class="sec-head" data-reveal><span class="eyebrow">Questions</span><h2 id="efaq-h">Emergency dental questions</h2><p>This is general guidance only. When you call, our team will advise you based on your situation.</p></div>
 ${faqList(faqs)}
</div></section>

<section class="sec-tight" aria-labelledby="erel-h"><div class="wrap">
 <h2 id="erel-h" style="font-size:1.7rem">Treatments that often follow an emergency</h2>
 <div class="related" data-reveal-group>${['root-canal-therapy', 'extractions', 'crowns-and-bridges'].map(r => byslug[r]).map(r => `<a class="rel" href="${r.file}">${r.img ? `<img src="${r.img}" alt="" width="84" height="84" loading="lazy" decoding="async"${r.imgContain ? ' style="object-fit:contain;background:#fff"' : ''}>` : `<span class="ph">${ico(r.icon)}</span>`}<span><b>${r.name}</b><span>${r.short.split('.')[0]}.</span></span></a>`).join('')}</div>
 ${sourcesBlock(['problems', 'abscess', 'rct'])}
</div></section>`;
  const ld = [{ '@context': 'https://schema.org', '@type': 'MedicalProcedure', name: 'Emergency dental care', description: s.short, url: L.SITE_URL + s.file, provider: { '@id': L.SITE_URL + '#dentist' } }, faqLd(faqs)];
  return { file: s.file, section: 'services', title: s.title, desc: s.desc, body, trail, ld, ogImage: s.img, cta: { h: 'Dental emergency? Call us now.', p: `We reserve appointments every day for dental emergencies. Call ${F.phone} and our team will help.` } };
}

/* =================================================================== FAQ */
function faq() {
  const trail = [['Home', 'index.html'], ['Patient Information', 'faq.html'], ['FAQs', 'faq.html']].filter((x, i) => i !== 1);
  const G = [
    ['new', 'New patients', [
      ['Are you accepting new patients?', 'Yes. Redlynch Dental welcomes new patients of all ages.'],
      ['What happens at my first visit?', 'We recommend starting with a thorough consultation and dental clean. We examine your teeth, gums and jaw joints, take the X-rays you need, photograph any potential problems, scale, clean and polish your teeth, apply fluoride, and give you a printed treatment plan. <a href="new-patients.html">More about your first visit</a>.'],
      ['Is there a new patient offer?', 'The practice publishes a new patient offer for a comprehensive check-up, necessary X-rays, a scale, clean and polish, fluoride and a treatment plan: gap free with any health fund, or $199 if you are not in a health fund. Terms and conditions apply. <a href="special-offers.html">See the offer</a> and confirm the details with our team when you book.'],
      ['What should I bring?', 'It helps to bring a list of your medications and any allergies, details of any medical conditions, your health fund card if you have extras cover, and any recent dental X-rays or records if you have them.'],
    ]],
    ['appointments', 'Appointments', [
      ['How do I book?', `Call ${F.phone}, email <a href="mailto:${F.email}">${F.email}</a>, or <a href="${F.booking}" target="_blank" rel="noopener">book online</a>.`],
      ['What are your opening hours?', 'Monday to Thursday 8am to 5pm, Friday 8am to 4:30pm. Closed Saturday and Sunday.'],
      ['Do you have after-hours or weekend appointments?', 'After-hours and weekend appointments are available at request. Call us to ask about availability.'],
      ['Where are you located?', `${F.address}, within the Redlynch Commercial Hub. <a href="contact.html">Map and directions</a>.`],
      ['Can I watch something or listen to music during treatment?', 'Yes. Let us know what music you’d like us to play, or choose a movie to watch while your treatment is completed.'],
    ]],
    ['general', 'General dental care', [
      ['How often should I have a check-up?', 'Your dentist will recommend an interval based on your oral health. Many people come in every six to twelve months.'],
      ['How should I look after my teeth at home?', 'Brush twice a day with a fluoride toothpaste, clean between your teeth every day, limit sugary foods and drinks, drink tap water, and keep up regular check-ups.'],
      ['Why are X-rays needed?', 'X-rays show areas the eye can’t see, such as decay between teeth, under fillings or below the gum line, and the position of wisdom teeth. Your dentist will only recommend X-rays you need.'],
      ['My gums bleed when I brush. Should I worry?', 'Bleeding gums can be an early sign of gum inflammation. It is worth booking a check-up and clean. See <a href="check-ups-and-dental-cleans.html">check-ups and cleans</a>.'],
    ]],
    ['children', 'Children’s dentistry', [
      ['When should my child first see a dentist?', 'healthdirect advises that children should see a dentist by the time their first tooth appears, or by their first birthday. Call us to book.'],
      ['Is children’s dentistry free?', 'Free kids dentistry is listed among the practice’s services. Please ask our team about eligibility and what is included when you book.'],
      ['What toothpaste should my child use?', 'healthdirect advises a small pea-sized amount of fluoride toothpaste, twice a day, from 18 months to 5 years. Under 18 months, only use toothpaste if your child’s dentist recommends it. Ask us if you’re unsure.'],
      ['Does my child need a mouthguard?', 'Children who play contact sport should wear a mouthguard. The Australian Dental Association advises that a mouthguard custom-made by a dentist gives the most protection. See <a href="mouthguards.html">mouthguards</a>.'],
    ]],
    ['emergency', 'Emergency dentistry', [
      ['What counts as a dental emergency?', 'Painful teeth or gums, broken teeth or fillings, facial swelling, trauma to the face, teeth or gums, and broken or lost dentures. <a href="emergency-dental-care.html">Emergency dental care</a>.'],
      ['Can I be seen the same day?', 'Specific appointments are reserved every day for emergencies, and the team will always strive to see you as soon as possible. Call as early as you can.'],
      ['When should I call 000 instead?', 'If swelling affects your breathing, swallowing or eye, bleeding won’t stop, you have a serious head or facial injury, or you feel very unwell, call 000 or go to your nearest emergency department.'],
    ]],
    ['cosmetic', 'Cosmetic and restorative treatments', [
      ['Should I choose whitening or veneers?', 'Whitening brightens your natural teeth but won’t change fillings, crowns or the shape of teeth. Veneers change shape and colour but involve more treatment. Your dentist can help you decide. See <a href="teeth-whitening.html">whitening</a> and <a href="cosmetic-veneers.html">veneers</a>.'],
      ['Am I suitable for dental implants?', 'It depends on your bone, gum health and general health. The practice offers a complimentary implant consultation, with a 3D X-ray, to assess suitability. <a href="dental-implants.html">Dental implants</a>.'],
      ['How long does Invisalign® take?', 'It depends on your needs. Some minor cases take as little as seven weeks, but average treatment is around 12 to 18 months. <a href="invisalign.html">Invisalign®</a>.'],
      ['Does root canal treatment hurt?', 'Most root canal treatment is carried out comfortably under local anaesthetic. The tooth may be tender for a few days afterwards. <a href="root-canal-therapy.html">Root canal therapy</a>.'],
    ]],
    ['costs', 'Payment and costs', [
      ['How much will my treatment cost?', 'It depends on what you need. After a comprehensive check-up you receive a printed treatment plan with the most accurate estimate we can give of the time and cost involved.'],
      ['Do you offer payment plans?', 'Yes, payment plans are available. Ask our team about the options.'],
      ['Can I use my health fund?', 'What you can claim depends on your fund and level of extras cover. Ask your fund what you are covered for, and ask our team when you book. The current new patient offer is advertised as gap free with any health fund.'],
      ['Is Medicare available for dental care?', 'Medicare does not cover most general dental care for adults. Some government dental programs exist for eligible people. Check eligibility with Services Australia or Queensland Health, and ask our team whether a particular program can be used at the practice.'],
    ]],
  ];
  const all = G.flatMap(g => g[2]);
  const body = `
<section class="page-hero"><div class="wrap">
 ${crumbs(trail)}<span class="eyebrow">Patient information</span><h1>Frequently asked questions</h1><p class="lead">Answers to common questions about visiting Redlynch Dental. If your question isn’t here, call us on ${F.phone}. We’re happy to help.</p>
</div></section>
<section class="bg-paper"><div class="wrap faq-cats">
 <nav aria-label="FAQ categories"><ul class="faq-nav" data-spy>${G.map(([id, n]) => `<li><a href="#${id}">${n}</a></li>`).join('')}</ul></nav>
 <div>${G.map(([id, n, q]) => `<div class="faq-group" id="${id}"><h2>${n}</h2>${faqList(q)}</div>`).join('')}
 <p class="fine">General information only. Practice details were checked against the Redlynch Dental website; please confirm offers and availability with our team.</p></div>
</div></section>`;
  return { file: 'faq.html', section: 'patient', title: 'Frequently Asked Questions | Redlynch Dental, Cairns', desc: 'Answers to common questions about Redlynch Dental: new patients, appointments, children’s dentistry, emergencies, cosmetic and restorative treatment, and payment.', body, trail, ld: [faqLd(all)] };
}

/* =================================================================== BLOG */
function blog() {
  const trail = [['Home', 'index.html'], ['Blog', 'blog.html']];
  const guides = [
    ['emergency-dental-care', 'Urgent', 'What counts as a dental emergency, and what to do right now.'],
    ['childrens-dentistry', 'Family', 'Helping children have positive early visits, and caring for little teeth at home.'],
    ['teeth-whitening', 'Cosmetic', 'Is whitening safe? Side effects, suitability and keeping your results.'],
    ['wisdom-teeth', 'Oral surgery', 'Common wisdom tooth problems, and when removal is recommended.'],
    ['invisalign', 'Straightening', 'How Invisalign® works, how long it takes and what happens after.'],
    ['root-canal-therapy', 'Restorative', 'Why root canal treatment can save a tooth, and what to expect.'],
  ];
  const body = `
<section class="page-hero cream"><div class="wrap">
 ${crumbs(trail)}<span class="eyebrow">Blog</span><h1>News and dental health articles</h1><p class="lead">Articles from the Redlynch Dental team, plus practical patient guides to common treatments.</p>
</div></section>
<section class="bg-paper"><div class="wrap">
 <h2 class="sr-only">Latest article</h2>
 <a class="post-feature" href="blog-why-choose-dental-implants.html" data-reveal>
  <img src="assets/images/blog/dental-implant-crown-illustration.webp" alt="Illustration of a dental implant post with a crown being lowered onto it" width="1100" height="825" loading="lazy" decoding="async">
  <div class="pf-body"><div class="post-meta"><span class="tag">Dental implants</span><span>Dr Alex Bornsztejn</span><time datetime="2021-01-29">29 January 2021</time></div><h2>Why choose dental implants?</h2><p>Dental implants are a simple and proven way to replace one or more missing teeth. Here are the main advantages.</p><span class="text-link">Read the article ${ico('arrow')}</span></div>
 </a>
</div></section>
<section><div class="wrap">
 <div class="sec-head" data-reveal><span class="eyebrow">Patient guides</span><h2>Explore our treatment guides</h2><p>Detailed information on common treatments, written for patients, with links to reputable Australian health sources.</p></div>
 <div class="guide-grid" data-reveal-group>${guides.map(([sl, k, p]) => `<a class="guide" href="${byslug[sl].file}"><span class="k">${k}</span><h3>${byslug[sl].name}</h3><p>${p}</p><span class="text-link" style="margin-top:auto">Read guide ${ico('arrow')}</span></a>`).join('')}</div>
</div></section>`;
  return { file: 'blog.html', section: 'blog', title: 'Blog | Dental Health Articles from Redlynch Dental, Cairns', desc: 'Dental health articles and patient guides from Redlynch Dental, Cairns, including why choose dental implants, by Principal Dentist Dr Alex Bornsztejn.', body, trail };
}

function blogPost() {
  const file = 'blog-why-choose-dental-implants.html';
  const trail = [['Home', 'index.html'], ['Blog', 'blog.html'], ['Why choose dental implants?', file]];
  const body = `
<section class="page-hero cream"><div class="wrap narrow">
 ${crumbs(trail)}<div class="post-meta"><span class="tag">Dental implants</span><span>By Dr Alex Bornsztejn, Principal Dentist</span><time datetime="2021-01-29">29 January 2021</time></div>
 <h1>Why choose dental implants?</h1>
</div></section>
<section class="bg-paper"><div class="wrap">
 <article class="article prose">
  <figure style="margin-top:0"><img src="assets/images/blog/dental-implant-crown-illustration.webp" alt="Illustration of a dental implant post in the jaw with a crown being lowered onto it" width="1100" height="825" loading="lazy" decoding="async"></figure>
  <p class="lead">Dental implants are a simple and proven way to replace one or more missing teeth. A titanium screw is placed in the jaw, and a tooth is then placed on top.</p>
  <h2>What are the advantages of dental implants?</h2>
  <ul>
   <li>Fully fixed in place, so they can’t fall out like a denture</li>
   <li>Look like a natural tooth</li>
   <li>Function and chew like a natural tooth</li>
   <li>Help prevent drifting and movement of the natural teeth around the gap</li>
   <li>Help prevent the jawbone shrinking away where the tooth has been removed</li>
  </ul>
  <h2>Is an implant right for you?</h2>
  <p>Not everyone is suitable for implants, and every case is different. If you would like more information about dental implants, call our friendly team on <a href="${F.tel}">${F.phone}</a> to book a consultation. Read more about <a href="dental-implants.html">dental implants at Redlynch Dental</a>, including the three stages of treatment.</p>
  <p class="risk">${RISK}</p>
  <div class="editor-note"><strong>Editor’s note:</strong> This article was first published on the Redlynch Dental website on 29 January 2021. It has been lightly edited for this site: the closing call to action now gives Redlynch Dental’s own contact details, and the description of the implant is worded as being placed in the jaw.</div>
  <p class="mt-2"><a class="text-link" href="blog.html">${ico('arrow')} Back to all articles</a></p>
 </article>
</div></section>`;
  const ld = [{ '@context': 'https://schema.org', '@type': 'BlogPosting', headline: 'Why choose dental implants?', datePublished: '2021-01-29', author: { '@type': 'Person', name: 'Dr Alex Bornsztejn' }, publisher: { '@id': L.SITE_URL + '#dentist' }, image: L.SITE_URL + 'assets/images/blog/dental-implant-crown-illustration.webp', mainEntityOfPage: L.SITE_URL + file }];
  return { file, section: 'blog', ogType: 'article', title: 'Why Choose Dental Implants? | Redlynch Dental Blog', desc: 'Dr Alex Bornsztejn explains the advantages of dental implants: fixed in place, natural-looking, and helping protect neighbouring teeth and the jawbone.', body, trail, ld, ogImage: 'assets/images/blog/dental-implant-crown-illustration.webp' };
}

/* =================================================================== CONTACT */
function contact() {
  const trail = [['Home', 'index.html'], ['Contact Us', 'contact.html']];
  const body = `
<section class="page-hero"><div class="wrap">
 ${crumbs(trail)}<span class="eyebrow">Contact us</span><h1>We’d love to hear from you.</h1><p class="lead">Call, email or book online. For a dental emergency, please phone us rather than emailing, so we can help you as quickly as possible.</p>
 <div class="actions mt-1">${callBtn('', F.phone)}${bookBtn('ghost-light')}<a class="btn ember" href="emergency-dental-care.html">${ico('alert')} Dental emergency?</a></div>
</div></section>
<section class="bg-paper"><div class="wrap contact-grid">
 <div>
  <div class="c-cards" data-reveal-group>
   <div class="c-card"><span class="ic">${ico('phone')}</span><div><h3>Phone</h3><p><a href="${F.tel}">${F.phone}</a></p></div></div>
   <div class="c-card"><span class="ic">${ico('mail')}</span><div><h3>Email</h3><p><a href="mailto:${F.email}">${F.email}</a></p></div></div>
   <div class="c-card"><span class="ic">${ico('pin')}</span><div><h3>Address</h3><p>${F.street}<br>${F.suburb} ${F.state} ${F.postcode}</p><p style="font-weight:500;color:var(--muted);font-size:.92rem;margin-top:6px">Within the Redlynch Commercial Hub · <a href="${F.mapsLink}" target="_blank" rel="noopener">Get directions ↗</a></p></div></div>
   <div class="c-card"><span class="ic">${ico('calendar')}</span><div><h3>Book online</h3><p><a href="${F.booking}" target="_blank" rel="noopener">Choose an appointment time ↗<span class="sr-only"> (opens in a new tab)</span></a></p></div></div>
  </div>
  <div class="card mt-2" data-reveal><h2 style="font-size:1.6rem">Opening hours</h2>${hoursRows()}<p class="fine" style="margin-top:12px">After-hours and weekend appointments are available at request.</p></div>
 </div>
 <div data-reveal>
  <form class="form" id="enquiry-form" data-mailto="${F.email}" novalidate aria-describedby="form-intro">
   <h2 style="font-size:1.8rem">Send an enquiry</h2>
   <p id="form-intro" class="fine" style="margin-bottom:20px">Fields marked * are required. Pressing “Prepare email” opens your own email app with your message ready to send to ${F.email}. Nothing is sent from this page. For urgent problems, please call.</p>
   <div class="fields">
    <div class="field"><label for="f-name">Name *</label><input id="f-name" name="name" autocomplete="name" required><span class="err" data-err="f-name" aria-live="polite"></span></div>
    <div class="field"><label for="f-phone">Phone *</label><input id="f-phone" name="phone" type="tel" autocomplete="tel" required><span class="err" data-err="f-phone" aria-live="polite"></span></div>
    <div class="field full"><label for="f-email">Email *</label><input id="f-email" name="email" type="email" autocomplete="email" required><span class="err" data-err="f-email" aria-live="polite"></span></div>
    <div class="field full"><label for="f-reason">What is it about? <span class="opt">(optional)</span></label><select id="f-reason" name="reason"><option>General enquiry</option><option>New patient appointment</option><option>Check-up and clean</option><option>Children’s dentistry</option><option>Cosmetic treatment</option><option>Dental implants</option><option>Invisalign®</option><option>Something else</option></select></div>
    <div class="field full"><label for="f-message">Your message *</label><textarea id="f-message" name="message" required></textarea><span class="err" data-err="f-message" aria-live="polite"></span></div>
    <div class="consent"><input id="f-consent" type="checkbox" required><label for="f-consent">I agree to Redlynch Dental using these details to reply to my enquiry. See our <a href="privacy-policy.html">privacy policy</a>.</label></div>
    <span class="err" data-err="f-consent" aria-live="polite" style="grid-column:1/-1;color:var(--ember);font-weight:700;font-size:.85rem"></span>
   </div>
   <button class="btn lg mt-1" type="submit">${ico('mail')} Prepare email</button>
   <div class="form-note" id="form-note" role="status" aria-live="polite"></div>
   <noscript><p class="fine" style="margin-top:12px">This form needs JavaScript. Please email <a href="mailto:${F.email}">${F.email}</a> or call ${F.phone}.</p></noscript>
  </form>
 </div>
</div></section>
<section class="sec-tight"><div class="wrap">${mapBox()}</div></section>`;
  return { file: 'contact.html', section: 'contact', title: 'Contact Redlynch Dental | 4/2 Chelsea Lane, Redlynch QLD 4870', desc: `Contact Redlynch Dental: phone ${F.phone}, email ${F.email}, ${F.address}. Opening hours, directions and online booking.`, body, trail, noCta: true };
}

/* =================================================================== SPECIAL OFFERS */
function specialOffers() {
  const trail = [['Home', 'index.html'], ['New Patient Offer', 'special-offers.html']];
  const body = `
<section class="page-hero cream"><div class="wrap">
 ${crumbs(trail)}<span class="eyebrow">Special offers</span><h1>New patient special offer</h1><p class="lead">A comprehensive start with Redlynch Dental: check-up, X-rays, clean, fluoride and a treatment plan.</p>
</div></section>
<section class="bg-paper"><div class="wrap">
 <div class="offer" data-reveal>
  <div class="o-img"><img src="assets/images/offers/new-patient-special-offer.webp" alt="New patient special offer: comprehensive dental check-up; all necessary X-rays including full-face X-rays; scale, clean and polish; fluoride treatment; treatment plan. Gap free with any health fund. Not in a health fund? All the above for only $199. T&amp;Cs apply." width="880" height="1100"></div>
  <div class="o-body">
   <span class="eyebrow">What’s included</span>
   <h2 style="font-size:clamp(1.6rem,2.6vw,2.2rem)">Everything you need for a fresh start</h2>
   <ul class="ticks"><li>Comprehensive dental check-up</li><li>All necessary X-rays, including full-face X-rays</li><li>Scale, clean and polish</li><li>Fluoride treatment</li><li>Treatment plan</li></ul>
   <div class="price-strip"><div><b>Gap free</b><span>with any health fund</span></div><div><b>$199</b><span>not in a health fund</span></div></div>
   <p class="fine">Terms and conditions apply to all offers. This offer is reproduced from the Redlynch Dental website. Please ask our team to confirm that it is current, and for the full terms and conditions, when you book.</p>
   <div class="actions mt-1">${bookBtn()}${callBtn('ghost', F.phone)}</div>
  </div>
 </div>
 <p class="mt-2"><a class="text-link" href="new-patients.html">What to expect at your first visit ${ico('arrow')}</a></p>
</div></section>`;
  return { file: 'special-offers.html', section: 'patient', title: 'New Patient Special Offer | Redlynch Dental, Cairns', desc: 'New patient offer at Redlynch Dental: comprehensive check-up, X-rays, scale and clean, fluoride and a treatment plan. Gap free with any health fund, or $199. T&Cs apply.', body, trail, ogImage: 'assets/images/offers/new-patient-special-offer.webp' };
}

/* =================================================================== PRIVACY (draft) */
function privacy() {
  const trail = [['Home', 'index.html'], ['Privacy Policy', 'privacy-policy.html']];
  const body = `
<section class="page-hero cream"><div class="wrap narrow">${crumbs(trail)}<span class="eyebrow">Privacy</span><h1>Privacy policy</h1></div></section>
<section class="bg-paper"><div class="wrap narrow prose">
 <div class="draft-banner" role="note"><strong>Draft for practice review.</strong> Redlynch Dental’s current website does not publish a privacy policy, so this page is a starting draft only. It must be reviewed, completed and approved by the practice before the site goes live. Until then this page is excluded from search engines.</div>
 <p>Redlynch Dental (${F.legalName}) respects your privacy. As a health service provider, we handle personal and health information in line with the <em>Privacy Act 1988</em> (Cth) and the Australian Privacy Principles.</p>
 <h2>Information collected through this website</h2>
 <ul>
  <li><strong>Enquiry form:</strong> the contact form on this website does not send or store any information itself. It prepares an email in your own email app, addressed to ${F.email}. The details you choose to send are received by the practice by email.</li>
  <li><strong>Online booking:</strong> online bookings are handled by a third-party booking system (Centaur) on its own website, under its own privacy terms.</li>
  <li><strong>Maps:</strong> the map on our contact pages loads from Google only when you choose to show it. Google’s privacy policy applies to that content.</li>
  <li><strong>Fonts:</strong> this website loads fonts from Google Fonts.</li>
 </ul>
 <h2>To be completed by the practice</h2>
 <ul>
  <li>The kinds of personal and health information the practice collects and holds, and why</li>
  <li>How information is stored and protected, and how long records are kept</li>
  <li>When information may be disclosed (for example, to specialists, laboratories or health funds)</li>
  <li>Any analytics or cookies used on the live website</li>
  <li>How patients can access or correct their information</li>
  <li>How to make a privacy complaint, and contact details for the Office of the Australian Information Commissioner (OAIC)</li>
 </ul>
 <h2>Contact</h2>
 <p>For privacy questions, contact Redlynch Dental on <a href="${F.tel}">${F.phone}</a> or <a href="mailto:${F.email}">${F.email}</a>, or write to ${F.address}.</p>
</div></section>`;
  return { file: 'privacy-policy.html', section: '', title: 'Privacy Policy | Redlynch Dental', desc: 'Privacy policy for the Redlynch Dental website.', body, trail, noindex: true, noCta: true };
}

function notFound() {
  const body = `<section class="page-hero" style="min-height:50vh"><div class="wrap"><span class="eyebrow">Error 404</span><h1>Sorry, we can’t find that page.</h1><p class="lead">The page may have moved. Try one of these instead, or call us on ${F.phone}.</p><div class="actions mt-1"><a class="btn" href="index.html">Home</a><a class="btn ghost-light" href="services.html">Services</a><a class="btn ember" href="emergency-dental-care.html">Emergency dental care</a><a class="btn ghost-light" href="contact.html">Contact</a></div></div></section>`;
  return { file: '404.html', section: '', title: 'Page not found | Redlynch Dental', desc: 'The page you were looking for could not be found.', body, noindex: true };
}

module.exports = { home, about, team, newPatients, servicesIndex, servicePage, emergency, faq, blog, blogPost, contact, specialOffers, privacy, notFound, services, TEAM };

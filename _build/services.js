/* Redlynch Dental: service data and page content.
   "From the practice" blocks restate the official redlynchdental.com.au page for that service.
   General education is written from the Australian sources listed in each page's `sources`.
   Treatment suitability always depends on an individual clinical assessment. */
'use strict';
const { F, RISK, ico, callBtn, bookBtn } = require('./lib');

const IMG = 'assets/images/services/';
const HD = 'https://www.healthdirect.gov.au/';
const SRC = {
  checkup: ['healthdirect: Dental check-up', HD + 'procedures/health-screenings/dental-check-up'],
  decay: ['healthdirect: Tooth decay', HD + 'conditions/mouth-and-tooth-diseases/tooth-decay'],
  gum: ['healthdirect: Gum disease', HD + 'conditions/mouth-and-tooth-diseases/gum-disease'],
  kids: ['healthdirect: Looking after your baby or child’s teeth', HD + 'treatments-and-self-care/looking-after-your-baby-or-childs-teeth'],
  veneers: ['healthdirect: Veneers', HD + 'procedures/dental-procedures-and-appliances/veneers'],
  bridge: ['healthdirect: Dental bridge', HD + 'procedures/dental-procedures-and-appliances/dental-bridge'],
  implant: ['healthdirect: Dental implant', HD + 'procedures/dental-procedures-and-appliances/dental-implant'],
  dentures: ['healthdirect: Dentures', HD + 'procedures/dental-procedures-and-appliances/dentures'],
  extraction: ['healthdirect: Tooth extraction', HD + 'procedures/dental-procedures-and-appliances/tooth-extraction'],
  drysocket: ['healthdirect: Dry socket', HD + 'conditions/mouth-and-tooth-diseases/dry-socket'],
  fillings: ['healthdirect: Dental fillings', HD + 'procedures/dental-procedures-and-appliances/dental-fillings'],
  rct: ['healthdirect: Root canal treatment', HD + 'procedures/dental-procedures-and-appliances/root-canal-treatment'],
  abscess: ['healthdirect: Tooth abscess', HD + 'conditions/mouth-and-tooth-diseases/tooth-abscess'],
  whitening: ['healthdirect: Teeth whitening', HD + 'procedures/dental-procedures-and-appliances/teeth-whitening'],
  problems: ['healthdirect: Dental problems', HD + 'conditions/mouth-and-tooth-diseases/dental-problems'],
  grinding: ['healthdirect: Teeth grinding', HD + 'conditions/mouth-and-tooth-diseases/teeth-grinding'],
  adaWisdom: ['Australian Dental Association (teeth.org.au): Wisdom teeth', 'https://teeth.org.au/wisdom-teeth'],
  adaGuards: ['Australian Dental Association (teeth.org.au): Sports mouthguards', 'https://teeth.org.au/sports-mouthguards'],
  adaCrowns: ['Australian Dental Association (teeth.org.au): Crowns, bridges and veneers', 'https://teeth.org.au/crowns-bridges-and-veneers'],
  adaBraces: ['Australian Dental Association (teeth.org.au): Teeth straightening and braces', 'https://teeth.org.au/teeth-straightening-and-braces'],
  adaWhitening: ['Australian Dental Association (teeth.org.au): Teeth whitening', 'https://teeth.org.au/teeth-whitening'],
  adaRct: ['Australian Dental Association (teeth.org.au): Root canal treatment', 'https://teeth.org.au/root-canal-treatment'],
  adaDentures: ['Australian Dental Association (teeth.org.au): Dentures', 'https://teeth.org.au/dentures'],
  adaFillings: ['Australian Dental Association (teeth.org.au): Fillings', 'https://teeth.org.au/fillings'],
  ahpraCosmetic: ['Ahpra: Non-surgical cosmetic procedures (guidance for consumers)', 'https://www.ahpra.gov.au/Resources/Cosmetic-surgery-hub.aspx'],
};

const fp = (txt, src) => `<div class="from-practice"><span class="label">From the Redlynch Dental team</span>${txt}</div>`;
const steps = (arr, n) => `<ol class="timeline" style="--n:${n || arr.length}" data-reveal-group>${arr.map(([h, p]) => `<li><h3>${h}</h3><p>${p}</p></li>`).join('')}</ol>`;
const ticks = arr => `<ul class="ticks">${arr.map(x => `<li>${x}</li>`).join('')}</ul>`;
const cards = (arr, cls = 'g2') => `<div class="grid ${cls}" data-reveal-group>${arr.map(([i, h, p]) => `<div class="card"><div class="ic">${ico(i)}</div><h3>${h}</h3><p>${p}</p></div>`).join('')}</div>`;

/* Each service: identity, imagery, meta, hero, article body, FAQs, related links, sources.
   `hero`: 'split' (photo), 'contain' (illustration on white), 'type' (designed graphic, no photo). */
const services = [
{
  slug: 'check-ups-and-dental-cleans', navName: 'Check-ups & Cleans', name: 'Check-ups and Dental Cleans', icon: 'shield', cat: 'everyday', footer: true,
  img: IMG + 'dental-examination.webp', imgAlt: 'A dentist in blue gloves examining a smiling patient’s teeth',
  short: 'A thorough examination, X-rays where needed, a professional scale and clean, and a printed treatment plan.',
  title: 'Check-ups and Dental Cleans Redlynch | Redlynch Dental Cairns',
  desc: 'Thorough dental check-ups and professional cleans at Redlynch Dental, 4/2 Chelsea Lane, Redlynch. Examination, X-rays, scale and clean, fluoride and a printed treatment plan. Call (07) 4039 4720.',
  eyebrow: 'Preventive care', h1: 'Check-ups and dental cleans', lead: 'Prevention is far better than cure. Regular check-ups let us spot small problems early, before they become bigger, costlier ones.',
  body: () => `
${fp(`<p>At Redlynch Dental, we welcome all new patients of all ages. If you are visiting us for the first time, we recommend starting with a thorough consultation and dental clean.</p><p>By attending regularly for check-ups and cleans, we can recognise and treat the early signs of potential problems so they don’t progress. This saves you time and money, and helps you keep your own natural teeth looking great and functioning comfortably for years to come.</p>`)}
<h2>What we do at your visit</h2>
<p>We take the time to look at the whole picture, not just one tooth. At a comprehensive check-up and clean we:</p>
<ol class="num-list">
 <li><div><h3>Examine your teeth and gums</h3><p>A careful check of every tooth, your gums and the soft tissues of your mouth.</p></div></li>
 <li><div><h3>Assess your jaw joints</h3><p>We check how your jaw moves and how your teeth meet when you bite.</p></div></li>
 <li><div><h3>Take the X-rays you need</h3><p>Both close-up and full-face X-rays where necessary, so we can see what isn’t visible to the eye.</p></div></li>
 <li><div><h3>Show you what we see</h3><p>We take photos of any potential problems so you can see them for yourself.</p></div></li>
 <li><div><h3>Scale, clean and polish</h3><p>Removing plaque and hardened tartar from your teeth and along the gum line, followed by a topical fluoride treatment.</p></div></li>
 <li><div><h3>Give you a printed treatment plan</h3><p>The most accurate estimate we can give of the time and cost involved in restoring your mouth to full health, function and appearance.</p></div></li>
</ol>
<figure><img src="${IMG}check-up-tartar-before-after.webp" alt="Two photos side by side: lower front teeth with heavy brown tartar along the gums, and the same teeth after a professional clean" width="800" height="350" loading="lazy" decoding="async"><figcaption>Illustrative image of tartar build-up before and after a professional clean. Tartar can’t be removed by brushing at home.</figcaption></figure>
<h2>Why regular check-ups matter</h2>
<p>Tooth decay and gum disease are among the most common health problems in Australia, and both can progress without obvious pain in the early stages. A regular examination and clean helps find problems while they are small and simpler to treat. Your dentist will recommend how often you should come in, based on your own oral health.</p>
<div class="callout"><p><strong>Between visits:</strong> brush twice a day with a fluoride toothpaste, clean between your teeth, limit sugary food and drinks, and drink tap water. These everyday habits make the biggest difference to your teeth.</p></div>
<h2>Gum health</h2>
<p>Bleeding gums when you brush or floss can be an early sign of gum inflammation (gingivitis). Left untreated, gum disease can progress and affect the bone that supports your teeth. A professional clean, combined with good daily care, is the starting point for healthier gums.</p>`,
  faqs: [
    ['How often should I have a check-up and clean?', 'It depends on your oral health, so your dentist will recommend an interval that suits you. Many people come in every six to twelve months, but some people benefit from more frequent visits.'],
    ['Do I need X-rays every time?', 'Not always. X-rays are taken when they are needed to check areas that can’t be seen with the eye, such as between teeth, under existing fillings or below the gum line. Your dentist will explain why any X-ray is recommended.'],
    ['Is there a special offer for new patients?', 'The practice publishes a new patient offer covering a comprehensive check-up, necessary X-rays, a scale, clean and polish, fluoride and a treatment plan. <a href="special-offers.html">See the current offer and its terms</a>, and confirm the details with our team when you book.'],
    ['Will my health fund cover a check-up and clean?', 'Cover for preventive dental care depends on your fund and your level of extras cover. Check with your fund, or ask our team when you book.'],
  ],
  related: ['childrens-dentistry', 'fillings', 'teeth-whitening'],
  sources: ['checkup', 'decay', 'gum'],
},
{
  slug: 'childrens-dentistry', navName: 'Children’s Dentistry', name: 'Children’s Dentistry', icon: 'child', cat: 'everyday', footer: true,
  img: IMG + 'childrens-dentistry-smiling-girl.webp', imgAlt: 'A smiling young girl sitting in a dental chair',
  short: 'Positive early experiences, gentle care and early detection, so children grow up comfortable at the dentist.',
  title: 'Children’s Dentist Redlynch | Kids Dentistry Cairns | Redlynch Dental',
  desc: 'Gentle children’s dentistry in Redlynch, Cairns. Fun, positive early visits, preventive care and early treatment. Ask about free kids dentistry. Call Redlynch Dental on (07) 4039 4720.',
  eyebrow: 'Family dentistry', h1: 'Children’s dentistry', lead: 'Your child’s early experiences at the dentist shape how they feel about dental care for life. We keep visits calm, positive and fun.',
  body: () => `
${fp(`<p>Your child’s early experiences at the dentist are so important in shaping their future attitude towards their dental health. Fun, positive early experiences are the best way to make sure your child doesn’t develop unnecessary long-term dental fears.</p><p>Early diagnosis and treatment of any problems with your child’s teeth can help reduce the time and money needed to fix them, and prevent unnecessary discomfort.</p>`)}
<div class="callout"><p><strong>Free kids dentistry.</strong> Free kids dentistry is listed among the services at Redlynch Dental. Eligibility and what is included can vary, so please ask our team about your child’s situation when you book.</p></div>
<h2>Making the first visits easy</h2>
${cards([
  ['smile', 'Start early', 'Bringing children in early, before there is a problem, means their first experiences of the dental chair are relaxed and positive.'],
  ['heart', 'Gentle, patient care', 'Dr Julian Fung has a particular passion for working with children and for easing dental fears in patients of all ages.'],
  ['film', 'Distraction helps', 'Let us know what music your child likes, or choose a movie to watch during their appointment.'],
  ['users', 'Parents are part of it', 'You’re welcome to stay with your child, ask questions and take home practical tips for brushing at home.'],
])}
<h2>Looking after little teeth at home</h2>
<p>healthdirect’s guidance for children’s teeth includes:</p>
<ul>
 <li>Clean your baby’s teeth as soon as they appear, using a soft toothbrush. Under 18 months, only use toothpaste if your child’s dentist recommends it.</li>
 <li>From 18 months to 5 years, brush twice a day with a soft toothbrush and a small pea-sized amount of fluoride toothpaste. Your dentist may recommend a different toothpaste if your child is at high risk of decay.</li>
 <li>Children should spit out toothpaste after brushing, rather than swallowing it or rinsing.</li>
 <li>Help with brushing until your child can do it well on their own. By about 9 years of age, most children can brush and floss their own teeth.</li>
 <li>Offer water between meals, limit sweet snacks and drinks, and avoid putting a baby to bed with a bottle of milk or juice.</li>
</ul>
<p>Baby teeth matter. They help children chew, speak and smile with confidence, and they hold space for the adult teeth that follow.</p>`,
  faqs: [
    ['When should my child first see a dentist?', 'healthdirect advises that children should see a dentist by the time their first tooth appears, or by their first birthday. Early visits help problems be spotted early and make the dental chair familiar. Call us to book.'],
    ['Is children’s dentistry free?', 'Free kids dentistry is listed among the practice’s services. Please ask our team about eligibility and what is included when you book, so you know what to expect before the visit.'],
    ['What if my child is nervous?', 'That’s common. Tell us when you book. We take things slowly, explain each step in child-friendly language, and can play music or a movie to help them relax.'],
    ['Do baby teeth really need fillings?', 'Decay in baby teeth can cause pain and infection, and can affect the adult teeth developing underneath. Your dentist will explain the options for your child’s situation.'],
  ],
  related: ['check-ups-and-dental-cleans', 'mouthguards', 'fillings'],
  sources: ['kids', 'decay'],
},
{
  slug: 'cosmetic-veneers', navName: 'Cosmetic Veneers', name: 'Cosmetic Veneers', icon: 'veneer', cat: 'cosmetic', footer: false,
  img: IMG + 'porcelain-veneers.webp', imgAlt: 'A row of thin, translucent porcelain veneers on a dark background',
  short: 'Thin, custom-made porcelain shells bonded to the front of teeth to change their shape, colour or alignment.',
  title: 'Porcelain Veneers Redlynch | Cosmetic Dentist Cairns | Redlynch Dental',
  desc: 'Custom porcelain veneers at Redlynch Dental. A two-appointment process with high-precision magnification and quality porcelain. Book a cosmetic consultation: (07) 4039 4720.',
  eyebrow: 'Cosmetic dentistry', h1: 'Cosmetic veneers', lead: 'Thin shells of porcelain, made for your smile and bonded to the front of your teeth. Veneers can address gaps, chips, staining and worn or uneven teeth.',
  body: () => `
${fp(`<p>In a similar way to false fingernails, veneers are thin shells of porcelain that are bonded to your teeth to give the appearance of a beautiful smile. Veneers can correct gappy teeth, crooked teeth, discoloured or stained teeth, and short and worn teeth.</p><p>Veneers have the potential to last many years when done correctly.</p>`)}
<h2>Is a veneer right for you?</h2>
<p>Veneers are often considered for:</p>
<div class="grid g2" data-reveal-group>
 <div class="card"><h3>Gaps and uneven teeth</h3><p>Closing small gaps or improving the look of slightly crooked or irregular teeth.</p></div>
 <div class="card"><h3>Discolouration</h3><p>Teeth that are stained or discoloured and don’t respond well to whitening.</p></div>
 <div class="card"><h3>Chips and wear</h3><p>Front teeth that are chipped, short or worn down over time.</p></div>
 <div class="card"><h3>Shape and proportion</h3><p>Teeth that are small, oddly shaped or out of proportion with the rest of your smile.</p></div>
</div>
<p class="mt-2">Veneers aren’t the right answer for everyone. Teeth need to be healthy, and active decay or gum disease must be treated first. Your dentist may also suggest alternatives such as <a href="teeth-whitening.html">whitening</a>, tooth-coloured bonding, <a href="invisalign.html">Invisalign®</a> or <a href="crowns-and-bridges.html">crowns</a>, depending on your goals.</p>
<h2>The treatment process</h2>
<p>Treatment usually takes two appointments.</p>
${steps([
  ['First appointment', 'Your teeth are prepared and impressions are taken so your veneers are made to fit your teeth and suit your smile. Preparation may involve removing a small amount of enamel so the final veneers aren’t bulky. Using high-precision magnification and high-quality porcelain helps keep enamel removal to a minimum, and sometimes none is needed. Temporary veneers are fitted while your custom veneers are made in the laboratory.'],
  ['Final appointment', 'Your temporary veneers are gently removed and your permanent veneers are bonded to your teeth. Any adjustments are made then and there, and we run through how to care for your new smile.'],
], 2)}
<h2>Things to consider</h2>
<ul>
 <li>Where enamel is removed, the change can’t be reversed, and the tooth will need a veneer or other restoration from then on.</li>
 <li>Porcelain veneers can chip or crack, and may eventually need replacing.</li>
 <li>Habits such as grinding your teeth, biting nails or opening packets with your teeth increase the risk of damage. A night guard may be recommended.</li>
 <li>Veneers don’t change colour with whitening, so any whitening is usually done first.</li>
</ul>
<p class="risk">${RISK}</p>`,
  faqs: [
    ['How long do veneers last?', 'Veneers can last many years with good care, but they are not permanent. How long they last depends on the material, your bite, your habits and how well you look after them. Your dentist can talk through what to expect for your situation.'],
    ['Will my teeth need to be shaved down?', 'Often a small amount of enamel is removed so the veneers sit naturally. Using magnification and high-quality porcelain helps keep this to a minimum, and in some cases none is needed. Your dentist will explain what is planned for your teeth before you begin.'],
    ['Do veneers look natural?', 'Veneers are custom-made for your smile, with the shade and shape planned with you, so they can look very natural.'],
    ['Who can I see for veneers?', 'Dr Alex Bornsztejn lists crowns, veneers and smile makeovers among his areas of expertise, and Dr Nadia Mullins has a special interest in cosmetic dentistry.'],
  ],
  related: ['teeth-whitening', 'crowns-and-bridges', 'invisalign'],
  sources: ['veneers', 'adaCrowns'],
},
{
  slug: 'crowns-and-bridges', navName: 'Crowns & Bridges', name: 'Crowns and Bridges', icon: 'crown', cat: 'restore', footer: true,
  img: IMG + 'dental-crown-illustration.webp', imgAlt: 'Illustration of a porcelain crown being placed over a prepared tooth',
  short: 'Custom caps that strengthen broken or heavily filled teeth, and fixed bridges that replace missing ones.',
  title: 'Dental Crowns and Bridges Redlynch | Redlynch Dental Cairns',
  desc: 'Dental crowns for broken, decayed or heavily filled teeth, and fixed bridges to replace missing teeth, at Redlynch Dental, Cairns. Call (07) 4039 4720.',
  eyebrow: 'Restorative dentistry', h1: 'Crowns and bridges', lead: 'A crown reinforces a weakened tooth so you can keep it. A bridge fills the gap left by a missing tooth with a fixed, natural-looking replacement.',
  body: () => `
${fp(`<p>If your tooth is broken, decayed or has a large filling, it might need a crown. A crown is a cap that is custom made to fit over your existing tooth, reinforcing it and helping to prevent further breakdown. If your tooth needs a crown, the sooner it is done, the better: it can save you money in the medium to long term and help you keep your own natural teeth.</p><p>If you have a missing tooth, a bridge may be the best solution. A bridge replaces one or more missing teeth by joining an artificial tooth to the neighbouring natural teeth. Bridges are fixed in place and look, feel and function like natural teeth.</p>`)}
<div class="img-pair">
 <figure><img src="${IMG}dental-crown-illustration.webp" alt="Illustration of a crown being placed over a prepared tooth" width="1000" height="788" loading="lazy" decoding="async"><figcaption><b>Crown:</b> a cap that covers and protects a weakened tooth.</figcaption></figure>
 <figure><img src="${IMG}dental-bridge-illustration.webp" alt="Illustration of a three-unit bridge being placed over two prepared teeth to replace the missing tooth between them" width="1000" height="750" loading="lazy" decoding="async"><figcaption><b>Bridge:</b> a replacement tooth supported by the teeth either side of the gap.</figcaption></figure>
</div>
<h2>Crown or bridge? A quick comparison</h2>
<div class="table-scroll"><table class="compare">
 <thead><tr><th scope="col"></th><th scope="col">Crown</th><th scope="col">Bridge</th></tr></thead>
 <tbody>
  <tr><th scope="row">Used for</th><td>A tooth that is broken, cracked, heavily filled, badly worn or root-filled</td><td>One or more missing teeth</td></tr>
  <tr><th scope="row">How it works</th><td>Covers the whole visible part of the tooth</td><td>Artificial tooth joined to crowns on the neighbouring teeth</td></tr>
  <tr><th scope="row">Removable?</th><td>No, cemented in place</td><td>No, fixed in place</td></tr>
  <tr><th scope="row">Things to weigh up</th><td>The tooth is reshaped to make room for the crown</td><td>Neighbouring teeth are reshaped to support it; cleaning under the bridge takes extra care</td></tr>
 </tbody>
</table></div>
<h2>What to expect</h2>
${steps([
  ['Assessment', 'We examine the tooth, take X-rays and talk through your options, including alternatives such as fillings, implants or dentures.'],
  ['Preparation', 'The tooth (or teeth) are shaped under local anaesthetic and an impression or scan is taken. A temporary may be fitted.'],
  ['Fitting', 'Your custom crown or bridge is checked for fit, bite and colour, then cemented in place.'],
])}
<h2>Caring for your crown or bridge</h2>
<p>Brush twice a day and clean between your teeth every day, including under a bridge using floss threaders or small interdental brushes. Keep up regular check-ups so we can check the margins of your crown or bridge and the health of the supporting teeth.</p>
<p class="risk">${RISK}</p>`,
  faqs: [
    ['How long does a crown take?', 'Usually two appointments: one to prepare the tooth and take an impression, and one to fit the final crown once it has been made.'],
    ['Will a crown look like my other teeth?', 'Tooth-coloured crowns are shade-matched to your neighbouring teeth so they blend in.'],
    ['What are the alternatives to a bridge?', 'A missing tooth can also be replaced with a <a href="dental-implants.html">dental implant</a> or a <a href="dentures.html">denture</a>. Each option has different benefits, costs and considerations, which we can talk through with you.'],
    ['Why might I need a crown after a root canal?', 'Back teeth that have had <a href="root-canal-therapy.html">root canal therapy</a> can become more brittle. A crown is often recommended to protect them from breaking.'],
  ],
  related: ['dental-implants', 'root-canal-therapy', 'dentures'],
  sources: ['bridge', 'adaCrowns'],
},
{
  slug: 'dental-implants', navName: 'Dental Implants', name: 'Dental Implants', icon: 'implant', cat: 'restore', footer: true,
  img: IMG + 'dental-implant-row.webp', imgAlt: 'Illustration of a dental implant and crown sitting among natural teeth, with the gum shown in cross-section',
  short: 'A fixed, natural-looking way to replace one or more missing teeth, planned with 3D imaging.',
  title: 'Dental Implants Redlynch & Cairns | Redlynch Dental',
  desc: 'Dental implants at Redlynch Dental, Cairns. Complimentary implant consultation, 3D X-ray planning and computer-guided placement. Call (07) 4039 4720 to book.',
  eyebrow: 'Tooth replacement', h1: 'Dental implants', lead: 'One of the most natural and long-lasting ways to replace missing teeth. Implant-supported teeth are fixed in place and look, feel and function like natural teeth.',
  heroMeta: ['Complimentary implant consultation', '3D X-ray planning', 'Computer-guided placement'],
  body: () => `
${fp(`<p>People who have lost teeth may feel self-conscious about smiling or talking. Chewing difficulties caused by tooth loss can also affect eating habits, which can lead to other health problems.</p><p>Dental implants offer a proven way to replace missing teeth. The resulting tooth or teeth are fixed in place and non-removable.</p>`)}
<h2>What is a dental implant?</h2>
<p>A dental implant is a small titanium post placed in the jawbone, where it acts like a tooth root. Once it has healed and bonded with the bone, it supports a crown, a bridge or a denture. Unlike a bridge, a single implant doesn’t rely on the neighbouring teeth for support.</p>
<h2>Why people choose implants</h2>
${cards([
  ['check', 'Fixed in place', 'Implant-supported teeth can’t slip or fall out like a removable denture.'],
  ['smile', 'Natural look and feel', 'The crown is made to match your other teeth, and chews like a natural tooth.'],
  ['shield', 'Protects neighbouring teeth', 'Filling the gap helps stop surrounding teeth drifting or tilting into the space.'],
  ['leaf', 'Supports the jawbone', 'An implant helps reduce the bone shrinkage that happens where a tooth has been lost.'],
])}
<h2>The implant process at Redlynch Dental</h2>
${steps([
  ['Treatment plan', 'Call us to book your complimentary implant consultation. We assess your suitability, take moulds of your teeth and a 3D X-ray, and go through your treatment plan and costs in detail.'],
  ['The implant is placed', 'The implant is placed under local anaesthetic, and we make sure you are completely numb before starting. Placement is computer-guided for a high level of accuracy. Many patients tell us it was easier than they expected.'],
  ['The tooth is fitted', 'Once the implant has healed and settled, usually after about three months, your permanent tooth is fitted on top.'],
])}
<div class="callout info mt-2"><p><strong>Is everyone suitable?</strong> Not always. Suitability depends on things like the amount and quality of bone, gum health, general health, smoking and some medications. Your 3D X-ray and consultation will show whether implants are right for you, and what the alternatives are.</p></div>
<h2>Implants for dentures</h2>
<p>Implants can also be used to anchor a denture so it doesn’t rock or slip. If you are tired of a loose denture, ask about implant-retained options. See <a href="dentures.html">dentures</a>.</p>
<p>Dr Alex Bornsztejn, our Principal Dentist, lists implant dentistry among his areas of expertise and has travelled to learn the latest techniques in dental implants.</p>
<p class="risk">${RISK}</p>`,
  faqs: [
    ['Is the implant consultation really free?', 'The practice offers a complimentary implant consultation to assess your suitability and go through your treatment plan and costs. Any X-rays or further treatment are discussed with you first.'],
    ['Does getting an implant hurt?', 'The implant is placed under local anaesthetic, and we make sure you are numb before we begin. Some tenderness afterwards is normal, and we will explain how to manage it.'],
    ['How long does the whole process take?', 'It varies from person to person. As a guide, the implant usually needs about three months to heal before the permanent tooth is fitted.'],
    ['How do I look after an implant?', 'Brush and clean between your teeth as you would for natural teeth, and keep up regular check-ups. Gum health around an implant is important to its long-term success.'],
  ],
  related: ['crowns-and-bridges', 'dentures', 'extractions'],
  sources: ['implant', 'bridge'],
  blog: true,
},
{
  slug: 'dentures', navName: 'Dentures', name: 'Dentures', icon: 'denture', cat: 'restore', footer: false,
  img: IMG + 'full-denture.webp', imgAlt: 'A hand holding a full upper and lower acrylic denture',
  short: 'New full or partial dentures, replacements for loose dentures, and implant-anchored options.',
  title: 'Dentures Redlynch | Full, Partial & Implant Dentures | Redlynch Dental',
  desc: 'Full and partial dentures, replacement dentures and implant-retained dentures at Redlynch Dental, Cairns. Broken or lost denture? Call (07) 4039 4720.',
  eyebrow: 'Tooth replacement', h1: 'Dentures', lead: 'Tired of a rocking denture? Need dentures for the first time? Or want implants to anchor your denture in place? We can help with all of these.',
  body: () => `
${fp(`<p>Tired of your old rocking dentures? Do you need dentures for the first time? Maybe you want some implants to anchor down your denture? Whatever your question, our team is trained to provide you with the very best in tooth replacement solutions.</p>`)}
<h2>Types of dentures</h2>
<div class="grid g3" data-reveal-group>
 <div class="card"><div class="ic">${ico('denture')}</div><h3>Full dentures</h3><p>Replace all the teeth in the upper or lower jaw, resting on the gums.</p></div>
 <div class="card"><div class="ic">${ico('tooth')}</div><h3>Partial dentures</h3><p>Replace one or several missing teeth, clipping onto your remaining natural teeth.</p></div>
 <div class="card"><div class="ic">${ico('implant')}</div><h3>Implant-retained</h3><p>Dentures that click onto dental implants for more stability when you eat and speak.</p></div>
</div>
<h2>Signs your denture needs attention</h2>
<ul>
 <li>It rocks, slips or feels loose when you eat or talk.</li>
 <li>Sore spots or ulcers on your gums that don’t settle.</li>
 <li>It is cracked, chipped or a tooth has come off.</li>
 <li>It no longer looks or feels the way it used to. Gums and bone change shape over time, so dentures that once fitted well may not any more.</li>
</ul>
<div class="callout warn"><p><strong>Broken or lost denture?</strong> This is one of the dental emergencies we reserve appointments for. <a href="emergency-dental-care.html">See emergency dental care</a> or call <a href="${F.tel}">${F.phone}</a>.</p></div>
<h2>Caring for your dentures</h2>
<ul>
 <li>Clean your dentures every day with a soft brush, and rinse them after meals.</li>
 <li>Take them out at night, unless your dentist advises otherwise, and store them as recommended.</li>
 <li>Handle them over a folded towel or a basin of water in case you drop them.</li>
 <li>Keep having regular check-ups. Your dentist will check your gums, the fit of your denture, and the soft tissues of your mouth.</li>
</ul>`,
  faqs: [
    ['How long does it take to get new dentures?', 'It depends on the type of denture and whether any teeth need to be removed first. Making a denture normally takes several appointments for impressions, fittings and adjustments. We will give you a timeline at your consultation.'],
    ['Can implants stop my denture moving?', 'In many cases, yes. Implants can be used to anchor a denture so it is more stable. Suitability depends on your bone and general health, which we assess with an X-ray. See <a href="dental-implants.html">dental implants</a>.'],
    ['Will it feel strange at first?', 'New dentures often feel unusual for the first few weeks while you get used to eating and speaking with them. Sore spots can usually be eased with small adjustments, so come back and see us.'],
  ],
  related: ['dental-implants', 'crowns-and-bridges', 'extractions'],
  sources: ['dentures', 'adaDentures'],
},
{
  slug: 'emergency-dental-care', navName: 'Emergency Dental Care', name: 'Emergency Dental Care', icon: 'alert', cat: 'urgent', footer: true,
  img: 'assets/images/emergency/jaw-pain.webp', imgAlt: 'A woman holding her jaw in pain',
  short: 'Appointments reserved daily for pain, broken teeth, swelling, trauma and broken dentures. Call us straight away.',
  title: 'Emergency Dentist Redlynch | Emergency Dental Care Cairns | Redlynch Dental',
  desc: 'Dental emergency in Redlynch or Cairns? Redlynch Dental reserves appointments every day for toothache, broken teeth, swelling, trauma and broken dentures. Call (07) 4039 4720 now.',
  custom: true, // rendered by pages.js (emergency page has its own layout)
},
{
  slug: 'extractions', navName: 'Extractions', name: 'Tooth Extractions', icon: 'extract', cat: 'surgery', footer: false, artText: 'Calm, careful and <em>at your pace.</em>',
  img: null, imgAlt: '',
  short: 'Gentle tooth removal, with time to talk through your concerns and steps to help you relax.',
  title: 'Tooth Extractions Redlynch | Gentle Tooth Removal Cairns | Redlynch Dental',
  desc: 'Gentle tooth extractions at Redlynch Dental, Cairns. We take time to discuss your concerns and help you feel relaxed. Call (07) 4039 4720.',
  eyebrow: 'Oral surgery', h1: 'Extractions', lead: 'Having a tooth removed can be a real source of anxiety. Our team is trained to remove teeth as comfortably as possible, and to help you feel relaxed throughout.',
  body: () => `
${fp(`<p>We understand that having a tooth removed can be a serious source of anxiety. Our team is highly trained both in removing teeth as comfortably as possible and in making sure you are as relaxed as possible during the process.</p><p>If you need a tooth removed, we will take the time to discuss any reservations you have, and what additional steps we can take so we overcome them together.</p>`)}
<h2>Why a tooth may need to come out</h2>
<div class="grid g2" data-reveal-group>
 <div class="card"><h3>Severe decay or damage</h3><p>When a tooth is too broken down to be repaired with a filling, crown or root canal therapy.</p></div>
 <div class="card"><h3>Infection</h3><p>An infected tooth that can’t be saved, or where saving it isn’t the right choice for you.</p></div>
 <div class="card"><h3>Advanced gum disease</h3><p>When the bone supporting a tooth has been lost and the tooth is loose.</p></div>
 <div class="card"><h3>Wisdom teeth and crowding</h3><p>Impacted or problem <a href="wisdom-teeth.html">wisdom teeth</a>, or sometimes to make space for orthodontic treatment.</p></div>
</div>
<div class="callout info mt-2"><p><strong>Saving the tooth comes first.</strong> Where it is possible and sensible, your dentist will talk through options for keeping a tooth, such as a <a href="fillings.html">filling</a>, <a href="crowns-and-bridges.html">crown</a> or <a href="root-canal-therapy.html">root canal therapy</a>, so you can make an informed choice.</p></div>
<h2>What to expect</h2>
${steps([
  ['Before', 'We examine the tooth, take an X-ray, go through your medical history and medications, and explain the procedure and your options.'],
  ['On the day', 'The area is numbed with local anaesthetic. You may feel pressure, but you shouldn’t feel sharp pain. Tell us at any time if you need a break.'],
  ['Afterwards', 'We give you written aftercare instructions and talk about options for replacing the tooth if needed.'],
])}
<h2>Aftercare: the first few days</h2>
<ul>
 <li>Bite gently on gauze to help a blood clot form, and rest for the remainder of the day.</li>
 <li>Avoid rinsing, spitting forcefully, drinking through a straw, smoking and alcohol for the period your dentist advises. These can disturb the clot.</li>
 <li>Eat soft foods and chew on the other side.</li>
 <li>Take pain relief as directed by your dentist or pharmacist.</li>
</ul>
<div class="callout warn"><p><strong>Call us</strong> if pain gets worse a few days after the extraction (this can be a sign of dry socket), if bleeding won’t stop with firm pressure, or if you notice increasing swelling or a fever. If swelling affects your breathing or swallowing, call <a href="tel:000">000</a>.</p></div>
<p class="risk">${RISK}</p>`,
  faqs: [
    ['Will it hurt?', 'The tooth and surrounding area are numbed with local anaesthetic, so you shouldn’t feel sharp pain during the extraction. Some discomfort afterwards is normal, and we will explain how to manage it.'],
    ['I’m very anxious. What can be done?', 'Tell us when you book. We take time to discuss your concerns, explain each step, and can agree a signal for you to pause. For some procedures, such as wisdom tooth removal, a sedative can be prescribed to take beforehand where appropriate.'],
    ['Should I replace the tooth?', 'It depends on where the tooth is and how it affects your bite and appearance. Options include an <a href="dental-implants.html">implant</a>, a <a href="crowns-and-bridges.html">bridge</a> or a <a href="dentures.html">denture</a>.'],
  ],
  related: ['wisdom-teeth', 'dental-implants', 'root-canal-therapy'],
  sources: ['extraction', 'drysocket'],
},
{
  slug: 'facial-injectables-and-skin-treatments', navName: 'Facial Injectables & Skin', name: 'Facial Injectables and Skin Treatments', icon: 'face', cat: 'cosmetic', footer: false, artText: 'Your smile, seen <em>in context.</em>',
  img: null, imgAlt: '',
  short: 'Dento-facial aesthetic treatments that consider how your face frames your smile. By consultation only.',
  title: 'Facial Aesthetic Treatments Redlynch | Redlynch Dental Cairns',
  desc: 'Dento-facial aesthetic and skin treatments at Redlynch Dental, Cairns, available by consultation. Call (07) 4039 4720 to arrange an assessment.',
  eyebrow: 'Dento-facial aesthetics', h1: 'Facial injectables and skin treatments', lead: 'Dento-facial aesthetics considers how the overall structure of your face contributes to your smile. All treatments start with a consultation and assessment.',
  body: () => `
${fp(`<p>Redlynch Dental offers dento-facial aesthetic treatments to enhance your smile. This is an approach in cosmetic dentistry that considers how the overall structure of your face contributes towards your smile.</p>`)}
<h2>Treatment areas</h2>
<p>The practice lists the following treatment areas. Whether any treatment is suitable for you, and which product or technique is used, can only be decided after a consultation.</p>
<div class="grid g2" data-reveal-group>
 <div class="card"><div class="ic">${ico('face')}</div><h3>Facial aesthetic treatments</h3><ul class="ticks"><li>Lip enhancement</li><li>Cheek enhancement</li><li>Nasal enhancement and “bunny lines”</li><li>Droopy eyebrow correction</li><li>Frown line correction</li></ul></div>
 <div class="card"><div class="ic">${ico('sparkle')}</div><h3>Skin treatments</h3><ul class="ticks"><li>PRP treatments</li><li>Micro-needling</li><li>Fat reduction injections</li><li>Non-surgical facelifts and thread lifts</li></ul></div>
</div>
<h2>Before you decide</h2>
<ul>
 <li><strong>A consultation comes first.</strong> Your practitioner will take your medical history, discuss your goals and explain whether a treatment is appropriate for you.</li>
 <li><strong>Every treatment has risks.</strong> These can include bruising, swelling, asymmetry, infection and, rarely, more serious complications. Ask your practitioner to explain the risks, the expected recovery and what happens if you are not happy with the outcome.</li>
 <li><strong>Results vary.</strong> Outcomes differ from person to person and many treatments are temporary.</li>
 <li><strong>Take your time.</strong> You don’t need to decide on the day. It is reasonable to go away, think it over and ask more questions.</li>
</ul>
<div class="callout info"><p>Ahpra publishes information for consumers considering cosmetic procedures, including questions to ask your practitioner. <a href="https://www.ahpra.gov.au/Resources/Cosmetic-surgery-hub.aspx" target="_blank" rel="noopener">Visit the Ahpra cosmetic procedures hub ↗</a></p></div>
<p class="risk">${RISK}</p>`,
  faqs: [
    ['How do I find out whether a treatment is right for me?', 'Call the practice to arrange a consultation. Suitability, options, risks and costs are discussed individually at that appointment.'],
    ['Why would a dental practice offer facial treatments?', 'Dento-facial aesthetics looks at your smile in the context of your whole face, including your lips and the surrounding structures, rather than your teeth alone.'],
  ],
  related: ['cosmetic-veneers', 'teeth-whitening', 'invisalign'],
  sources: ['ahpraCosmetic'],
},
{
  slug: 'fillings', navName: 'Fillings', name: 'Fillings', icon: 'filling', cat: 'everyday', footer: false,
  img: IMG + 'boy-in-dental-chair.webp', imgAlt: 'A smiling young boy sitting in a dental chair',
  short: 'Treating decay early with tooth-coloured (white) fillings, before it reaches the nerve.',
  title: 'Tooth-Coloured Fillings Redlynch | Redlynch Dental Cairns',
  desc: 'White, tooth-coloured fillings at Redlynch Dental. Treating tooth decay early saves time, money and your natural tooth. Call (07) 4039 4720.',
  eyebrow: 'Everyday dentistry', h1: 'Fillings', lead: 'Treating tooth decay early saves time, effort and money, and helps you keep your natural tooth. We repair decayed teeth with white, tooth-coloured filling material.',
  body: () => `
${fp(`<p>Dental decay is one of the most common health issues we face as a population. If left untreated, decay progresses through the tooth until the nerve dies and an infection develops. At that point, the only treatment options are root canal therapy or extraction.</p><p>By treating decay early, we save a lot of time, effort and money. To treat early tooth decay, we remove all of the decayed part of the tooth and replace it with a white filling material.</p>`)}
<h2>How decay progresses</h2>
<ol class="num-list">
 <li><div><h3>Enamel</h3><p>Early decay starts in the hard outer layer of the tooth. It often causes no symptoms, which is why regular check-ups matter.</p></div></li>
 <li><div><h3>Dentine</h3><p>Once decay reaches the softer layer underneath, it can spread more quickly and the tooth may become sensitive to sweet, hot or cold.</p></div></li>
 <li><div><h3>Nerve (pulp)</h3><p>If decay reaches the nerve, it can cause pain and infection. Treatment then usually means <a href="root-canal-therapy.html">root canal therapy</a> or <a href="extractions.html">extraction</a>.</p></div></li>
</ol>
<h2>What happens during a filling</h2>
${steps([
  ['Numbing', 'Local anaesthetic is used where needed so you are comfortable.'],
  ['Removing decay', 'All of the decayed part of the tooth is carefully removed and the tooth is cleaned.'],
  ['Restoring', 'Tooth-coloured filling material is placed in layers, shaped and polished to fit your bite.'],
])}
<h2>Looking after your fillings</h2>
<p>A filling repairs the tooth, but it doesn’t stop new decay from forming around it. Brushing twice a day with fluoride toothpaste, cleaning between your teeth and keeping sugary foods and drinks to a minimum all help your fillings, and your teeth, last longer.</p>`,
  faqs: [
    ['Do white fillings last as long as silver ones?', 'Modern tooth-coloured materials are durable for most situations. How long any filling lasts depends on its size, where it is, your bite and your oral hygiene. Large fillings in back teeth may eventually need a crown.'],
    ['How do I know if I need a filling?', 'Early decay often has no symptoms. It is usually found at a check-up, on X-rays or during an examination. Sensitivity to sweet or cold, a rough edge or a visible hole are also reasons to book in.'],
    ['Can I eat after a filling?', 'Tooth-coloured fillings are set during the appointment. If you have had local anaesthetic, wait until the numbness wears off before eating so you don’t bite your cheek or tongue.'],
  ],
  related: ['check-ups-and-dental-cleans', 'root-canal-therapy', 'crowns-and-bridges'],
  sources: ['fillings', 'decay', 'adaFillings'],
},
{
  slug: 'invisalign', navName: 'Invisalign®', name: 'Invisalign® Clear Aligners', icon: 'aligner', cat: 'cosmetic', footer: true,
  img: IMG + 'clear-aligners.webp', imgAlt: 'Clear plastic aligner trays on a light background',
  short: 'Clear, removable aligners that straighten teeth gradually. Assessed by an Invisalign®-trained dentist.',
  title: 'Invisalign® Redlynch & Cairns | Clear Aligners | Redlynch Dental',
  desc: 'Invisalign® clear aligners at Redlynch Dental, Cairns. Suitability assessment with an Invisalign®-trained dentist, time frames and costs explained. Call (07) 4039 4720.',
  eyebrow: 'Teeth straightening', h1: 'Invisalign® clear aligners', lead: 'A series of clear, removable trays that gradually move your teeth into position. In suitable cases, Invisalign® can achieve results similar to traditional braces.',
  body: () => `
${fp(`<p>Invisalign® is an orthodontic system that straightens and aligns your teeth, often giving a result similar to traditional metal braces. A series of clear plastic trays is fitted to your teeth and changed every two weeks, with each tray moving your teeth closer to their final position.</p>`)}
<figure><img src="${IMG}invisalign-banner.webp" alt="A close-up of a smile beside the Invisalign logo and the words ‘The clear alternative to braces’" width="800" height="350" loading="lazy" decoding="async"></figure>
<h2>Advantages of Invisalign®</h2>
${cards([
  ['smile', 'Discreet', 'Virtually invisible compared with traditional metal braces.'],
  ['check', 'Removable', 'Taken out for up to two hours a day, so you can eat your normal foods and brush and floss as usual.'],
  ['shield', 'No wires or brackets', 'Nothing metal to poke or irritate your cheeks and gums.'],
  ['heart', 'Fits your lifestyle', 'Comfortable, with minimal impact on your day-to-day routine.'],
])}
<h2>How it works</h2>
${steps([
  ['Assessment', 'A detailed examination with our Invisalign®-trained dentist to check suitability and talk through your goals, time frames and costs.'],
  ['Records', 'X-rays, photos and moulds of your teeth are sent to the Invisalign® laboratory to plan your treatment.'],
  ['Aligners', 'You usually start wearing your trays within a few weeks, changing to the next set every two weeks.'],
  ['Retention', 'Once treatment is complete, retainers hold your teeth in their new position.'],
], 4)}
<div class="callout"><p><strong>Results depend on wearing your aligners.</strong> Aligners only move teeth while they are in your mouth, so they need to be worn for most of the day and night, as your dentist instructs.</p></div>`,
  faqs: [
    ['Can I get Invisalign®?', 'Many people are good candidates, but Invisalign® doesn’t suit everyone. Your Invisalign®-trained dentist will assess your individual needs. In some cases you may need traditional braces or a referral to a specialist orthodontist.'],
    ['How long does Invisalign® take?', 'It depends on your needs. Some minor cases can take as little as seven weeks, but average treatment time is usually around 12 to 18 months.'],
    ['What happens when I finish?', 'You need to commit to keeping your new smile. This can mean clear retainers, a wire bonded to the inside of your teeth, or both. Without retention, teeth tend to move back towards where they started.'],
    ['How do I get started?', 'Your first visit is a detailed examination to check suitability and discuss time frames and costs. From there we take X-rays, photos and moulds to send to the Invisalign® lab.'],
  ],
  related: ['cosmetic-veneers', 'teeth-whitening', 'check-ups-and-dental-cleans'],
  sources: ['adaBraces'],
},
{
  slug: 'mouthguards', navName: 'Mouthguards', name: 'Custom Mouthguards', icon: 'guard', cat: 'everyday', footer: false,
  img: IMG + 'sports-mouthguard.webp', imgAlt: 'A blue custom-fitted sports mouthguard',
  short: 'Custom-fitted sports mouthguards to help protect teeth and jaws during contact sport, for all ages.',
  title: 'Custom Sports Mouthguards Redlynch | Redlynch Dental Cairns',
  desc: 'Custom-fitted sports mouthguards for children and adults at Redlynch Dental, Cairns. Help protect teeth and jaws in contact sport. Call (07) 4039 4720.',
  eyebrow: 'Sport and protection', h1: 'Mouthguards', lead: 'A properly fitted mouthguard can greatly reduce the risk of injury to your teeth and jaws during contact sport. People of all ages should wear one.',
  body: () => `
${fp(`<p>A mouthguard is a protective device worn on your upper teeth during contact sport, which can greatly reduce or eliminate injury to your teeth and jaws. People of all ages should wear a mouthguard, including children, as injuries can be devastating to their developing teeth.</p>`)}
<h2>What a mouthguard helps protect against</h2>
<div class="grid g3" data-reveal-group>
 <div class="card"><div class="ic">${ico('broken')}</div><h3>Damaged teeth</h3><p>Chipped, broken or knocked-out teeth.</p></div>
 <div class="card"><div class="ic">${ico('bolt')}</div><h3>Jaw injuries</h3><p>Jaw fractures and damage to the jaw joints.</p></div>
 <div class="card"><div class="ic">${ico('face')}</div><h3>Soft tissue</h3><p>Cuts to the lips and cheeks.</p></div>
</div>
<p class="mt-2">The practice also lists concussion among the injuries a mouthguard can help reduce. Head injuries can still happen with a mouthguard, so follow your sport’s concussion guidelines.</p>
<h2>Custom-fitted vs over-the-counter</h2>
<div class="table-scroll"><table class="compare">
 <thead><tr><th scope="col"></th><th scope="col">Custom-fitted (from your dentist)</th><th scope="col">Over-the-counter</th></tr></thead>
 <tbody>
  <tr><th scope="row">Fit</th><td>Made from a mould of your teeth</td><td>Generic sizing, or shaped at home</td></tr>
  <tr><th scope="row">Comfort</th><td>Designed to stay in place so you can breathe and speak more easily</td><td>Can be bulky and may loosen during play</td></tr>
  <tr><th scope="row">Recommended by</th><td colspan="2">The Australian Dental Association advises that a mouthguard custom-made by a dentist provides the most protection for the teeth and mouth.</td></tr>
 </tbody>
</table></div>
<h2>Caring for your mouthguard</h2>
<ul>
 <li>Rinse it after use and clean it gently with a soft toothbrush.</li>
 <li>Store it in a ventilated case and keep it out of hot cars and direct sun, which can warp it.</li>
 <li>Bring it to your check-ups so we can check the fit. Children’s mouthguards need replacing as their teeth and jaws grow.</li>
</ul>`,
  faqs: [
    ['Which sports need a mouthguard?', 'Any sport with a risk of contact with a ball, another player or the ground, such as football codes, hockey, basketball, netball, cricket, martial arts and skateboarding.'],
    ['How often should my child’s mouthguard be replaced?', 'Children’s mouths change as teeth come through and jaws grow, so their mouthguard should be checked regularly and replaced when it no longer fits well.'],
    ['What if a tooth is knocked out during sport?', 'Call us straight away. See <a href="emergency-dental-care.html">emergency dental care</a> for what to do.'],
  ],
  related: ['childrens-dentistry', 'emergency-dental-care', 'check-ups-and-dental-cleans'],
  sources: ['adaGuards'],
},
{
  slug: 'root-canal-therapy', navName: 'Root Canal Therapy', name: 'Root Canal Therapy', icon: 'root', cat: 'restore', footer: true,
  img: IMG + 'root-canal-diagram.webp', imgAlt: 'Diagram showing the stages of root canal treatment, from an infected tooth to a crowned, root-filled tooth', imgContain: true,
  short: 'Saving a tooth whose nerve is inflamed beyond repair or has died, so you can avoid an extraction.',
  title: 'Root Canal Therapy Redlynch | Save Your Tooth | Redlynch Dental Cairns',
  desc: 'Root canal therapy at Redlynch Dental, Cairns. A safe, long-lasting way to save an infected or damaged tooth and avoid extraction. Call (07) 4039 4720.',
  eyebrow: 'Saving natural teeth', h1: 'Root canal therapy', lead: 'When the nerve inside a tooth is inflamed beyond repair or has died, root canal therapy can save the tooth and avoid the need to remove it.',
  body: () => `
${fp(`<p>Root canal therapy is needed when the nerve in your tooth is inflamed beyond repair or has died. This can happen because of deep decay or an injury or knock to the tooth. During treatment, the nerve is removed from the tooth and replaced with a filling material.</p><p>Despite what you may have heard, the majority of root canal treatments are completed comfortably under local anaesthetic. Root canal treatment can be a safe and long-lasting option for patients who want to save their natural teeth and avoid having them removed.</p>`)}
<h2>Signs you may need a root canal</h2>
<ul>
 <li>Lingering pain, especially when biting or chewing</li>
 <li>Sensitivity to heat or cold that lasts after the trigger is gone</li>
 <li>Swelling or tenderness in the gum near a tooth, or a pimple on the gum</li>
 <li>A tooth that has darkened after a knock</li>
</ul>
<p>Sometimes there are no symptoms at all, and the problem is found on an X-ray.</p>
<figure><img src="${IMG}root-canal-diagram.webp" alt="Six-panel diagram of root canal treatment: infected tooth; opening made in the tooth; infected tissue removed and canals cleaned; canals filled with permanent material; opening sealed with a filling; new crown cemented onto the rebuilt tooth" width="1024" height="1024" loading="lazy" decoding="async" style="max-width:560px;background:#fff;padding:12px"><figcaption>The stages of root canal treatment, from an infected tooth to a sealed, crowned tooth.</figcaption></figure>
<h2>What happens during treatment</h2>
${steps([
  ['Access', 'The tooth is numbed and a small opening is made to reach the canals inside the root.'],
  ['Cleaning', 'The inflamed or infected nerve tissue is removed, and the canals are cleaned and shaped.'],
  ['Sealing', 'The canals are filled and sealed, and the tooth is closed with a filling.'],
  ['Protecting', 'A crown is often recommended afterwards, especially for back teeth, to protect the tooth from breaking.'],
], 4)}
<p class="mt-2">Treatment often takes more than one appointment. Your dentist will explain the plan for your tooth.</p>
<div class="callout warn"><p><strong>Swelling or severe pain?</strong> A tooth infection can spread. Call us promptly, and see <a href="emergency-dental-care.html">emergency dental care</a>. If swelling affects your breathing or swallowing, call <a href="tel:000">000</a>.</p></div>
<p class="risk">${RISK}</p>`,
  faqs: [
    ['Is a root canal painful?', 'Most root canal treatment is carried out comfortably under local anaesthetic. The tooth may feel tender for a few days afterwards, and we will advise you on pain relief.'],
    ['Why not just take the tooth out?', 'Keeping your natural tooth helps you chew properly and stops neighbouring teeth from drifting. A missing tooth may also need replacing later with an implant, bridge or denture. Your dentist will explain the pros and cons of each option.'],
    ['Will I need a crown afterwards?', 'Often, particularly for back teeth, because root-filled teeth can become more brittle. See <a href="crowns-and-bridges.html">crowns and bridges</a>.'],
  ],
  related: ['crowns-and-bridges', 'fillings', 'emergency-dental-care'],
  sources: ['rct', 'abscess', 'adaRct'],
},
{
  slug: 'teeth-whitening', navName: 'Teeth Whitening', name: 'Teeth Whitening', icon: 'sun', cat: 'cosmetic', footer: true,
  img: 'assets/images/hero/smiling-mother-and-daughter.webp', imgAlt: 'A smiling mother and young daughter hugging', imgPos: '30% 40%',
  short: 'Professional whitening using the Philips Zoom system, supervised by your dental team.',
  title: 'Teeth Whitening Redlynch | Philips Zoom Whitening | Redlynch Dental Cairns',
  desc: 'Professional teeth whitening at Redlynch Dental using the Philips Zoom system. Safe, supervised whitening and honest advice on suitability. Call (07) 4039 4720.',
  eyebrow: 'Cosmetic dentistry', h1: 'Teeth whitening', lead: 'Professional whitening is a safe and effective way to brighten your smile. A whitening gel is applied to your teeth to lift discolouration that has built up over time.',
  heroMeta: ['Philips Zoom whitening system', 'Supervised by your dentist'],
  body: () => `
${fp(`<p>Professional teeth whitening is a safe and effective way to brighten your smile. A specially designed bleach is applied to your teeth, absorbing into your enamel and removing discolouration that has built up over time. The procedure is straightforward and takes very little of your time.</p><p>We exclusively use the <strong>Philips Zoom</strong> whitening system, which we believe is the best on the market.</p>`)}
<h2>Is whitening right for you?</h2>
<p>Many people are good candidates for whitening. The main exception is fillings, crowns or caps on your front teeth. These won’t whiten like your natural teeth, and can leave your smile looking patchy. If that is the case, the answer may be as simple as replacing old fillings with lighter ones to match, or <a href="cosmetic-veneers.html">veneers</a> may be a better, longer-lasting option.</p>
<p>Whitening isn’t suitable for everyone. Decay, gum problems and some types of sensitivity or staining need to be checked and managed first, so a check-up is the best place to start.</p>
<h2>Why professional, supervised whitening?</h2>
<div class="grid g2" data-reveal-group>
 <div class="card"><div class="ic">${ico('shield')}</div><h3>Safety</h3><p>When a whitening product is recommended and supervised by your dental professional, the procedure has proven to be very safe and does not cause permanent damage to your teeth.</p></div>
 <div class="card"><div class="ic">${ico('alert')}</div><h3>Avoid risky products</h3><p>Some whitening systems sold online contain very high concentrations of bleaching agent that can damage your teeth. Your dentist will never recommend these.</p></div>
</div>
<h2>What about sensitivity?</h2>
<p>Some patients notice mild sensitivity during whitening. This is temporary, and returns to normal once you stop. If bleach trays are overfilled, you may notice a slight burning sensation and whitening of the gums. Again, this is temporary and settles fairly quickly once the gel is rinsed away.</p>
<h2>Keeping your results</h2>
<p>How long whitening lasts varies from person to person and depends a lot on diet and lifestyle. Tea, coffee, red wine and smoking stain teeth more quickly. On average, whitening may need to be repeated around every six months.</p>`,
  faqs: [
    ['Does whitening damage your teeth?', 'Whitening products recommended and supervised by your dental professional have proven to be very safe and do not cause permanent damage to your teeth. Some products sold online contain very high concentrations of bleaching agent, which can cause damage.'],
    ['Should I expect side effects?', 'Mild, temporary sensitivity is common during whitening and settles after you stop. Overfilled trays can cause temporary gum irritation, which settles once the gel is rinsed away.'],
    ['How often do I need to whiten?', 'It depends on your diet and lifestyle. Coffee, tea, red wine and smoking stain teeth faster. On average, whitening may need to be repeated around every six months.'],
    ['Will my fillings or crowns whiten?', 'No. Fillings, crowns and caps don’t change colour with whitening, which can leave your smile looking uneven. Your dentist will discuss options with you.'],
    ['Which whitening system do you use?', 'The practice exclusively uses the Philips Zoom whitening system.'],
  ],
  related: ['cosmetic-veneers', 'check-ups-and-dental-cleans', 'invisalign'],
  sources: ['whitening', 'adaWhitening'],
},
{
  slug: 'wisdom-teeth', navName: 'Wisdom Teeth', name: 'Wisdom Teeth', icon: 'wisdom', cat: 'surgery', footer: true,
  img: IMG + 'wisdom-teeth-x-ray.webp', imgAlt: 'A panoramic dental X-ray with the four wisdom teeth highlighted',
  short: 'Assessment with X-rays, conservative care where possible, and in-chair removal when needed.',
  title: 'Wisdom Teeth Removal Redlynch & Cairns | Redlynch Dental',
  desc: 'Wisdom tooth assessment and removal at Redlynch Dental, Cairns. X-ray assessment, clear costing and in-chair removal under local anaesthetic. Call (07) 4039 4720.',
  eyebrow: 'Oral surgery', h1: 'Wisdom teeth', lead: 'Most people get their wisdom teeth from their late teens to early twenties, and for many they cause problems. An assessment and X-ray will show whether yours need attention.',
  body: () => `
${fp(`<p>Wisdom teeth are molars that grow at the very back of your mouth. Unfortunately for most people, they are an inconvenient part of early adulthood.</p><p>For some patients, conservative treatment may be all that is needed. For others, removing the problem wisdom tooth is often necessary. As a general rule, if a wisdom tooth does need to be removed, the sooner the better: delaying can increase the difficulty and cost of the procedure.</p>`)}
<h2>Common wisdom tooth problems</h2>
<div class="grid g3" data-reveal-group>
 <div class="card"><h3>Pain and swelling</h3><p>Around a partly erupted tooth, often coming and going.</p></div>
 <div class="card"><h3>Infection</h3><p>Food and bacteria trapped under the gum can cause infection, bad breath or a bad taste.</p></div>
 <div class="card"><h3>Damage to the next tooth</h3><p>Decay and gum disease in the tooth directly in front of the wisdom tooth.</p></div>
</div>
<p class="mt-2">Wisdom teeth can also crowd other teeth. Importantly, many wisdom tooth problems don’t cause pain, which is why an assessment and X-ray are so valuable.</p>
<h2>What to expect</h2>
${steps([
  ['Consultation', 'One of our dentists assesses your wisdom teeth and takes an X-ray, then tells you whether they need to be removed.'],
  ['Clear costing', 'If removal is recommended, you receive a detailed costing of the procedure, and we find an appointment that suits you.'],
  ['Removal', 'Most wisdom teeth can be removed in the chair under local anaesthetic while you are awake. Some cases are referred to an oral surgeon for removal under sedation or general anaesthetic.'],
])}
<p class="mt-2">Dr Alex Bornsztejn lists wisdom teeth removal among his areas of expertise.</p>
<div class="callout warn"><p><strong>Call us</strong> if pain or swelling around a wisdom tooth is getting worse, or you have trouble opening your mouth. If swelling affects your breathing or swallowing, call <a href="tel:000">000</a>.</p></div>
<p class="risk">${RISK}</p>`,
  faqs: [
    ['I’m having problems with my wisdom teeth. What should I do?', 'Call us to arrange a consultation with one of our dentists. We will assess your wisdom teeth, take an X-ray and let you know whether they need to be removed. If they do, we will give you a detailed costing and find an appointment that suits you.'],
    ['How much does it cost to have a wisdom tooth removed?', '<p>The practice’s published guide is $200 to $400 per tooth for removal under local anaesthetic while you are awake in the chair. Some cases need referral to an oral surgeon for removal under sedation or general anaesthetic, and the cost will differ.</p><p>Your exact cost depends on the tooth and is confirmed in writing after your assessment.</p>'],
    ['I’m anxious about having them removed. What can be done?', 'Our dentists and support team are experienced in managing wisdom teeth. Where appropriate, a sedative can be prescribed for you to take before the appointment to help you relax.'],
    ['Do wisdom teeth always need to be removed?', 'No. Some people have enough room for the wisdom tooth to come through normally, and can brush and floss it to keep it clean. However, this is not very common.'],
    ['My wisdom teeth aren’t sore. Can they still cause problems?', 'Yes. Many of the problems wisdom teeth cause are painless, so it is important to have them assessed and X-rayed.'],
  ],
  related: ['extractions', 'emergency-dental-care', 'check-ups-and-dental-cleans'],
  sources: ['adaWisdom', 'extraction', 'drysocket'],
},
];

// Navigation / listing order follows the official site (alphabetical), with the new file names.
services.forEach(s => { s.file = s.slug + '.html'; });

const CATS = [
  { id: 'everyday', name: 'Everyday and family care', blurb: 'The routine care that keeps teeth healthy for life, for every age.' },
  { id: 'urgent', name: 'Urgent care', blurb: 'Appointments are reserved every day for dental emergencies.' },
  { id: 'restore', name: 'Restoring and replacing teeth', blurb: 'Repairing damaged teeth and replacing missing ones, so you can eat, speak and smile comfortably.' },
  { id: 'surgery', name: 'Oral surgery', blurb: 'Gentle tooth removal, including wisdom teeth, with time to talk through your concerns.' },
  { id: 'cosmetic', name: 'Cosmetic and smile treatments', blurb: 'Brightening, straightening and reshaping, planned around your face and your goals.' },
];

module.exports = { services, CATS, SRC };

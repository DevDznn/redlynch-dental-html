/* Redlynch Dental: verified practice facts used by main.js at runtime.
   Source: https://www.redlynchdental.com.au/ (checked 26 October 2026).
   See research/practice-facts.json for sources and verification notes.
   If any of these change, also run `node _build/build.js` so the static HTML matches. */
window.REDLYNCH = {
  name: 'Redlynch Dental',
  phoneDisplay: '(07) 4039 4720',
  phoneHref: 'tel:+61740394720',
  email: 'reception@redlynchdental.com.au',
  address: '4/2 Chelsea Lane, Redlynch QLD 4870',
  mapsLink: 'https://www.google.com/maps/search/?api=1&query=Redlynch+Dental+4%2F2+Chelsea+Lane+Redlynch+QLD+4870',
  bookingUrl: 'https://www.centaurportal.com/d4w/org-2836/search?practiceID=3243',
  timeZone: 'Australia/Brisbane',
  /* Day 0 = Sunday. [open, close] in 24h decimal hours; null = closed. */
  hours: { 0: null, 1: [8, 17], 2: [8, 17], 3: [8, 17], 4: [8, 17], 5: [8, 16.5], 6: null }
};

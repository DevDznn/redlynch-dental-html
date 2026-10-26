/* Redlynch Dental: shared site behaviour. No dependencies.
   Reads practice facts from assets/js/practice-data.js (window.REDLYNCH).
   Everything here is progressive enhancement: the pages are fully readable,
   and every link works, with JavaScript disabled. */
(function () {
  'use strict';
  var doc = document.documentElement;
  var D = window.REDLYNCH || {};
  var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var mqMobile = window.matchMedia('(max-width: 1020px)');

  /* ---------- Header: height variable, scrolled state ---------- */
  var header = document.querySelector('header.site');
  function setHeaderVars() {
    if (!header) return;
    var r = header.getBoundingClientRect();
    doc.style.setProperty('--hdr-h', Math.round(r.height) + 'px');
    doc.style.setProperty('--nav-top', Math.max(0, Math.round(r.bottom)) + 'px');
  }
  setHeaderVars();
  addEventListener('resize', setHeaderVars);

  /* ---------- Desktop dropdowns + mega menu (hover, click, keyboard) ---------- */
  var nav = document.getElementById('site-nav');
  var items = nav ? nav.querySelectorAll('li.has-dd') : [];
  function closeAll(except) {
    items.forEach(function (li) {
      if (li !== except) { li.classList.remove('open'); var b = li.querySelector('.dd-btn'); if (b) b.setAttribute('aria-expanded', 'false'); }
    });
  }
  function setOpen(li, open) {
    li.classList.toggle('open', open);
    li.querySelector('.dd-btn').setAttribute('aria-expanded', open ? 'true' : 'false');
  }
  items.forEach(function (li) {
    var btn = li.querySelector('.dd-btn');
    var timer;
    btn.addEventListener('click', function () {
      var open = !li.classList.contains('open');
      if (!mqMobile.matches) closeAll(li);
      setOpen(li, open);
    });
    li.addEventListener('mouseenter', function () { if (mqMobile.matches) return; clearTimeout(timer); closeAll(li); setOpen(li, true); });
    li.addEventListener('mouseleave', function () { if (mqMobile.matches) return; timer = setTimeout(function () { setOpen(li, false); }, 160); });
    li.addEventListener('focusout', function (e) { if (mqMobile.matches) return; if (!li.contains(e.relatedTarget)) setOpen(li, false); });
    li.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && li.classList.contains('open') && !mqMobile.matches) { setOpen(li, false); btn.focus(); e.stopPropagation(); }
      if (e.key === 'ArrowDown' && document.activeElement === btn && !mqMobile.matches) {
        e.preventDefault(); setOpen(li, true); var first = li.querySelector('.dd a, .mega a'); if (first) first.focus();
      }
    });
  });
  document.addEventListener('click', function (e) { if (nav && !mqMobile.matches && !nav.contains(e.target)) closeAll(); });

  /* ---------- Mobile drawer ---------- */
  var burger = document.querySelector('.burger');
  var scrim = document.querySelector('.nav-scrim');
  function openNav(open) {
    document.body.classList.toggle('nav-open', open);
    burger.setAttribute('aria-expanded', open ? 'true' : 'false');
    burger.querySelector('.lbl').textContent = open ? 'Close' : 'Menu';
    if (open) {
      setHeaderVars();
      // expand the current section in the drawer
      var cur = nav.querySelector('li.has-dd.is-active');
      if (cur) setOpen(cur, true);
      var f = nav.querySelector('a, button'); if (f) f.focus({ preventScroll: true });
    }
  }
  if (burger && nav) {
    burger.addEventListener('click', function () { openNav(!document.body.classList.contains('nav-open')); });
    if (scrim) scrim.addEventListener('click', function () { openNav(false); burger.focus(); });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && document.body.classList.contains('nav-open')) { openNav(false); burger.focus(); }
      // keep keyboard focus inside the open drawer
      if (e.key === 'Tab' && document.body.classList.contains('nav-open')) {
        var f = [burger].concat(Array.prototype.slice.call(nav.querySelectorAll('a, button'))).filter(function (el) { return el.offsetParent !== null; });
        var i = f.indexOf(document.activeElement);
        if (e.shiftKey && i <= 0) { e.preventDefault(); f[f.length - 1].focus(); }
        else if (!e.shiftKey && i === f.length - 1) { e.preventDefault(); f[0].focus(); }
      }
    });
    mqMobile.addEventListener('change', function () { openNav(false); closeAll(); });
  }

  /* ---------- Scroll state, back to top ---------- */
  var totop = document.createElement('button');
  totop.type = 'button'; totop.className = 'totop'; totop.setAttribute('aria-label', 'Back to top');
  totop.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 19V5M5 12l7-7 7 7"/></svg>';
  document.body.appendChild(totop);
  totop.addEventListener('click', function () { window.scrollTo({ top: 0, behavior: reduceMotion ? 'auto' : 'smooth' }); });
  var ticking = false;
  function onScroll() {
    var y = window.scrollY;
    if (header) header.classList.toggle('is-scrolled', y > 24);
    totop.classList.toggle('show', y > 700);
    ticking = false;
  }
  addEventListener('scroll', function () { if (!ticking) { ticking = true; requestAnimationFrame(onScroll); } }, { passive: true });
  onScroll();
  if (header) header.addEventListener('transitionend', setHeaderVars);

  /* ---------- Reveal on scroll (staggered within groups) ---------- */
  var groups = document.querySelectorAll('[data-reveal-group]');
  groups.forEach(function (g) {
    Array.prototype.forEach.call(g.children, function (c, i) { c.setAttribute('data-reveal', ''); c.style.setProperty('--d', Math.min(i, 8) * 0.07 + 's'); });
  });
  var revealEls = document.querySelectorAll('[data-reveal]');
  if ('IntersectionObserver' in window && !reduceMotion) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) { if (en.isIntersecting) { en.target.classList.add('in'); io.unobserve(en.target); } });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.05 });
    revealEls.forEach(function (el) { io.observe(el); });
    // safety net: never leave content hidden (e.g. anchors jumped past, print)
    setTimeout(function () { revealEls.forEach(function (el) { if (el.getBoundingClientRect().top < innerHeight) el.classList.add('in'); }); }, 1200);
    addEventListener('beforeprint', function () { revealEls.forEach(function (el) { el.classList.add('in'); }); });
  } else {
    revealEls.forEach(function (el) { el.classList.add('in'); });
  }

  /* ---------- FAQ accordions: animated <details> ---------- */
  document.querySelectorAll('.faq details').forEach(function (d) {
    var sum = d.querySelector('summary'), body = d.querySelector('.ans');
    if (!sum || !body || reduceMotion || !body.animate) return;
    sum.addEventListener('click', function (e) {
      e.preventDefault();
      if (d.dataset.anim) return;
      d.dataset.anim = '1';
      if (d.open) {
        var h = body.offsetHeight;
        var a = body.animate([{ height: h + 'px', opacity: 1 }, { height: '0px', opacity: 0 }], { duration: 260, easing: 'ease' });
        a.onfinish = function () { d.open = false; delete d.dataset.anim; };
      } else {
        d.open = true;
        var h2 = body.offsetHeight;
        var b = body.animate([{ height: '0px', opacity: 0 }, { height: h2 + 'px', opacity: 1 }], { duration: 300, easing: 'ease' });
        b.onfinish = function () { delete d.dataset.anim; };
      }
    });
  });

  /* ---------- Opening status (Australia/Brisbane, no daylight saving) ---------- */
  var DAYS = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
  function fmt(h) { var hr = Math.floor(h), m = Math.round((h - hr) * 60), ap = hr >= 12 ? 'pm' : 'am', h12 = hr % 12 || 12; return h12 + (m ? ':' + (m < 10 ? '0' : '') + m : '') + ap; }
  var now = null;
  try { now = new Date(new Date().toLocaleString('en-US', { timeZone: D.timeZone || 'Australia/Brisbane' })); } catch (e) { /* leave static text */ }
  if (now && D.hours) {
    var day = now.getDay(), hr = now.getHours() + now.getMinutes() / 60, t = D.hours[day];
    var open = !!(t && hr >= t[0] && hr < t[1]), msg;
    if (open) msg = 'Open now · until ' + fmt(t[1]);
    else if (t && hr < t[0]) msg = 'Closed · opens today ' + fmt(t[0]);
    else {
      for (var i = 1; i <= 7; i++) { var nd = (day + i) % 7; if (D.hours[nd]) { msg = 'Closed · opens ' + (i === 1 ? 'tomorrow' : DAYS[nd]) + ' ' + fmt(D.hours[nd][0]); break; } }
    }
    document.querySelectorAll('[data-status]').forEach(function (el) {
      el.classList.toggle('is-open', open);
      var s = el.querySelector('span'); if (s && msg) s.textContent = msg;
    });
    document.querySelectorAll('table.hours tr[data-day="' + day + '"]').forEach(function (r) { r.classList.add('today'); });
  }

  /* ---------- Sticky sub-navigation highlight (services index, FAQ) ---------- */
  document.querySelectorAll('[data-spy]').forEach(function (list) {
    var links = list.querySelectorAll('a[href^="#"]');
    if (!('IntersectionObserver' in window) || !links.length) return;
    var map = {};
    links.forEach(function (a) { var s = document.getElementById(a.getAttribute('href').slice(1)); if (s) map[s.id] = a; });
    var spy = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (en.isIntersecting) { links.forEach(function (a) { a.classList.remove('is-current'); a.removeAttribute('aria-current'); }); var a = map[en.target.id]; if (a) { a.classList.add('is-current'); a.setAttribute('aria-current', 'true'); } }
      });
    }, { rootMargin: '-30% 0px -60% 0px' });
    Object.keys(map).forEach(function (id) { spy.observe(document.getElementById(id)); });
  });

  /* ---------- Contact form: validate, then hand off to the visitor's email app.
     This static build has no form backend, so nothing is sent from the page itself.
     To connect a form service, see README > "Contact form". ---------- */
  var form = document.getElementById('enquiry-form');
  if (form) {
    var note = document.getElementById('form-note');
    var val = function (id) { return (document.getElementById(id).value || '').trim(); };
    var setErr = function (id, msg) {
      var f = document.getElementById(id), wrap = f.closest('.field') || f.closest('.consent'), slot = form.querySelector('[data-err="' + id + '"]');
      if (wrap) wrap.classList.toggle('invalid', !!msg);
      if (slot) slot.textContent = msg || '';
      f.setAttribute('aria-invalid', msg ? 'true' : 'false');
    };
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var ok = true, first = null;
      var check = function (id, cond, msg) { setErr(id, cond ? '' : msg); if (!cond) { ok = false; if (!first) first = id; } };
      check('f-name', val('f-name').length > 1, 'Please tell us your name.');
      check('f-phone', /^[0-9+()\s-]{8,}$/.test(val('f-phone')), 'Please enter a phone number we can call you on.');
      check('f-email', /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(val('f-email')), 'Please enter a valid email address.');
      check('f-message', val('f-message').length > 4, 'Please add a short message.');
      check('f-consent', document.getElementById('f-consent').checked, 'Please tick this box so we can reply to you.');
      note.className = 'form-note show';
      if (!ok) { note.classList.add('error'); note.textContent = 'Please check the highlighted fields.'; document.getElementById(first).focus(); return; }
      var to = D.email || form.getAttribute('data-mailto');
      var subject = 'Website enquiry: ' + val('f-reason') + ' (' + val('f-name') + ')';
      var body = 'Name: ' + val('f-name') + '\nPhone: ' + val('f-phone') + '\nEmail: ' + val('f-email') + '\nAbout: ' + val('f-reason') + '\n\n' + val('f-message');
      window.location.href = 'mailto:' + to + '?subject=' + encodeURIComponent(subject) + '&body=' + encodeURIComponent(body);
      note.textContent = 'Your email app should now open with this message addressed to ' + to + '. It is only sent when you press Send in your email app. If nothing opened, email us directly or call ' + (D.phoneDisplay || 'the practice') + '.';
    });
    form.querySelectorAll('input, textarea, select').forEach(function (f) {
      f.addEventListener('input', function () { if (f.closest('.invalid')) setErr(f.id, ''); });
    });
  }

  /* ---------- Map: load the Google Maps iframe only when requested ---------- */
  document.querySelectorAll('[data-map-src]').forEach(function (box) {
    var btn = box.querySelector('button');
    if (!btn) return;
    btn.addEventListener('click', function () {
      var f = document.createElement('iframe');
      f.src = box.getAttribute('data-map-src');
      f.title = 'Map showing Redlynch Dental, 4/2 Chelsea Lane, Redlynch';
      f.loading = 'lazy'; f.referrerPolicy = 'no-referrer-when-downgrade'; f.allowFullscreen = true;
      box.innerHTML = ''; box.appendChild(f);
    });
  });

  /* ---------- Footer year ---------- */
  document.querySelectorAll('[data-year]').forEach(function (el) { el.textContent = new Date().getFullYear(); });
})();

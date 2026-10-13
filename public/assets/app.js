/* PetCare Clinic – shared UI (no build step required) */
(function () {
  const $ = (s, r = document) => r.querySelector(s), $$ = (s, r = document) => [...r.querySelectorAll(s)];
  const csrf = () => (window.APP && APP.csrf) || $('meta[name=csrf-token]')?.content;

  window.toast = function (msg, ok = true) {
    const t = $('#toast'); if (!t) return alert(msg);
    t.textContent = msg; t.style.background = ok ? 'var(--n)' : '#B91C1C'; t.classList.add('show');
    clearTimeout(t._h); t._h = setTimeout(() => t.classList.remove('show'), 3500);
  };

  window.fetchJSON = async function (url, opts = {}) {
    const res = await fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrf(), ...(opts.headers || {}) }, credentials: 'same-origin', ...opts });
    let data = {}; try { data = await res.json(); } catch (e) {}
    return { ok: res.ok, status: res.status, data };
  };

  document.addEventListener('DOMContentLoaded', () => {
    // Mobile menu
    const h = $('.ham'); if (h) h.onclick = () => $('.menu').classList.toggle('open');

    // Choice groups (radio cards)
    $$('.choice').forEach(g => $$('label', g).forEach(lb => lb.addEventListener('click', () => {
      $$('label', g).forEach(x => x.classList.remove('on')); lb.classList.add('on');
      const inp = $('input', lb); if (inp) inp.checked = true;
    })));

    // FAQ accordion: one open at a time
    $$('[data-accordion]').forEach(acc => $$('details', acc).forEach(d => d.addEventListener('toggle', () => {
      if (d.open) $$('details', acc).forEach(o => { if (o !== d) o.open = false; });
    })));

    // AJAX forms (contact, newsletter): data-ajax
    $$('form[data-ajax]').forEach(f => f.addEventListener('submit', async e => {
      e.preventDefault();
      $$('.err', f).forEach(x => x.remove()); $$('.is-invalid', f).forEach(x => x.classList.remove('is-invalid'));
      const btn = $('button', f); if (btn) btn.disabled = true;
      const r = await fetchJSON(f.action, { method: 'POST', body: new FormData(f) });
      if (btn) btn.disabled = false;
      if (r.ok) { toast(r.data.message || 'OK'); f.reset(); return; }
      if (r.status === 422 && r.data.errors) {
        Object.entries(r.data.errors).forEach(([k, v]) => {
          const el = f.querySelector(`[name="${k}"]`);
          if (el) { el.classList.add('is-invalid'); const s = document.createElement('span'); s.className = 'err'; s.textContent = v[0]; el.after(s); }
        });
        toast(Object.values(r.data.errors)[0][0], false);
      } else if (r.status === 429) toast('Too many attempts. Please wait a minute.', false);
      else toast('Something went wrong. Please try again.', false);
    }));

    // Doctor search / filter (live, no reload)
    const df = $('#doc-filter'), grid = $('#doc-grid');
    if (df && grid) {
      let tm;
      const run = async () => {
        const qs = new URLSearchParams(new FormData(df)).toString();
        grid.style.opacity = .5;
        const r = await fetchJSON(df.action + '?' + qs);
        grid.style.opacity = 1;
        if (r.ok) { grid.innerHTML = r.data.html; history.replaceState(null, '', '?' + qs); }
      };
      df.addEventListener('input', () => { clearTimeout(tm); tm = setTimeout(run, 300); });
      df.addEventListener('change', run);
      df.addEventListener('submit', e => { e.preventDefault(); run(); });
    }

    // Doctor profile quick booking
    const qb = $('#quick-book');
    if (qb) {
      const date = $('#qb-date'), box = $('#qb-slots'), go = $('#qb-go');
      const load = async () => {
        box.innerHTML = `<span class="msg">${APP.t.loading}</span>`;
        const r = await fetchJSON(qb.dataset.slotsUrl + '?date=' + date.value);
        const slots = (r.data.slots || []);
        go.href = go.dataset.base + '&date=' + date.value;
        if (!slots.length) { box.innerHTML = `<span class="msg">${APP.t.noSlots}</span>`; return; }
        box.innerHTML = '';
        slots.forEach(s => {
          const b = document.createElement('button'); b.type = 'button'; b.textContent = s.label; b.disabled = !s.available;
          b.onclick = () => { $$('button', box).forEach(x => x.classList.remove('on')); b.classList.add('on'); go.href = go.dataset.base + '&date=' + date.value + '&time=' + s.time; };
          box.appendChild(b);
        });
      };
      date.addEventListener('change', load); load();
    }
  });
})();

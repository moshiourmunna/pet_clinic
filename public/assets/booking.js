/* Appointment page: mode switch, live slots via fetch, live summary sidebar */
(function () {
  const $ = (s, r = document) => r.querySelector(s), $$ = (s, r = document) => [...r.querySelectorAll(s)];
  const f = $('#book-form'); if (!f) return;
  const time = $('#b-time'), slotsBox = $('#slots'), date = $('#b-date'), svc = $('#b-svc');
  const sum = k => $(`[data-sum="${k}"]`);
  const mode = () => (f.querySelector('input[name=type]:checked') || {}).value || 'slot';
  const docLabel = () => { const i = f.querySelector('input[name=doctor_id]:checked'); return i ? i.closest('label') : null; };
  const fmtDate = v => v ? new Date(v + 'T00:00').toLocaleDateString(APP.locale === 'bn' ? 'bn-BD' : 'en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : '—';
  const money = n => '৳' + Number(n || 0).toLocaleString('en-US');
  let slotLabel = '';

  function applyMode() {
    const slot = mode() === 'slot';
    $$('.only-slot').forEach(e => e.style.display = slot ? '' : 'none');
    if (slot) loadSlots(); else time.value = '';
    summary();
  }

  async function loadSlots() {
    const dl = docLabel();
    if (!dl || !date.value) { slotsBox.innerHTML = `<span class="msg">${APP.t.pickDate}</span>`; return; }
    const id = f.querySelector('input[name=doctor_id]:checked').value;
    slotsBox.innerHTML = `<span class="msg">${APP.t.loading}</span>`;
    const r = await fetchJSON(`${f.dataset.slotsUrl}/${id}/slots?date=${date.value}`);
    const slots = r.data.slots || [];
    slotsBox.innerHTML = '';
    if (!slots.length) { slotsBox.innerHTML = `<span class="msg">${APP.t.noSlots}</span>`; time.value = ''; slotLabel = ''; summary(); return; }
    let keep = false;
    slots.forEach(s => {
      const b = document.createElement('button');
      b.type = 'button'; b.textContent = s.label; b.disabled = !s.available; b.dataset.t = s.time;
      if (s.available && s.time === time.value) { b.classList.add('on'); keep = true; slotLabel = s.label; }
      b.onclick = () => { $$('button', slotsBox).forEach(x => x.classList.remove('on')); b.classList.add('on'); time.value = s.time; slotLabel = s.label; summary(); };
      slotsBox.appendChild(b);
    });
    if (!keep) { time.value = ''; slotLabel = ''; }
    summary();
  }

  function summary() {
    const o = svc.options[svc.selectedIndex];
    sum('service').textContent = o ? o.text : '—';
    const dl = docLabel(), slot = mode() === 'slot';
    sum('doctor').textContent = dl ? dl.dataset.name : '—';
    sum('date').textContent = fmtDate(date.value);
    sum('time').textContent = slotLabel || '—';
    const price = Number(o?.dataset.price || 0);
    sum('fee').textContent = money(slot && dl ? Math.max(price, Number(dl.dataset.fee)) : price);
    const pay = f.querySelector('input[name=payment_method]:checked');
    sum('pay').textContent = pay ? pay.closest('label').dataset.label : '—';
    const step = $('[data-step-pay]'); if (step) step.classList.toggle('on', !!pay && pay.value !== 'clinic');
  }

  // "My pets" quick fill
  const mp = $('#b-mypet');
  if (mp) mp.addEventListener('change', () => { const o = mp.options[mp.selectedIndex]; if (!o.value) return; $('#b-pet').value = o.dataset.name; $('#b-type').value = o.dataset.type; $('#b-ab').value = o.dataset.ab; });

  f.addEventListener('change', e => {
    const n = e.target.name;
    if (n === 'type') applyMode();
    else if (n === 'doctor_id' || n === 'date') { if (mode() === 'slot') loadSlots(); summary(); }
    else summary();
  });

  f.addEventListener('submit', e => {
    if (mode() === 'slot' && !time.value) { e.preventDefault(); toast(APP.t.pickDate, false); slotsBox.scrollIntoView({ behavior: 'smooth', block: 'center' }); }
  });

  applyMode();
})();

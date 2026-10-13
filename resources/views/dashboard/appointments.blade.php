@extends('dashboard.layout')
@section('title', __('Appointments'))
@section('dash')
<div class="card" style="overflow-x:auto;overflow-y:visible"><div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px"><h3>{{ __('My Appointments') }}</h3><a class="btn btn-p" style="padding:9px 16px" href="{{ route('appointment.create') }}">+ <span>{{ __('New') }}</span></a></div>
@include('dashboard._appt_table', ['actions' => true])
{{ $appointments->links() }}</div>
@endsection
@push('scripts')<script>
document.querySelectorAll('form[data-resched]').forEach(f=>{const d=f.querySelector('[name=date]'),t=f.querySelector('select[name=time]');if(!t||!f.dataset.slotsUrl)return;
const load=async()=>{t.innerHTML='<option value="">'+APP.t.loading+'</option>';const r=await fetchJSON(f.dataset.slotsUrl+'?date='+d.value);t.innerHTML='';(r.data.slots||[]).filter(s=>s.available).forEach(s=>t.add(new Option(s.label,s.time)));if(!t.options.length)t.add(new Option(APP.t.noSlots,''))};
d.addEventListener('change',load);f.closest('details').addEventListener('toggle',load,{once:true});});
</script>@endpush

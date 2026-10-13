@props(['t'])
<div class="card testi"><i class="fa fa-quote-right q"></i><div class="stars">{{ str_repeat('★', $t->rating) }}</div><p style="margin-top:10px"><span>{{ $t->text }}</span></p><div class="who"><img src="{{ $t->photo_url }}" alt="{{ $t->name }}" loading="lazy"><div><b style="color:var(--n)">{{ $t->name }}</b><br><small>{{ $t->subtitle }}</small></div></div></div>

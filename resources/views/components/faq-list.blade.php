@props(['faqs'])
<div class="faq" data-accordion>@foreach($faqs as $faq)<details @if($loop->first) open @endif><summary><span>{{ $faq->question }}</span></summary><p>{{ $faq->answer }}</p></details>@endforeach</div>

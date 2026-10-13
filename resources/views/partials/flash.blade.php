@if(session('ok'))<div class="alert alert-ok"><i class="fa fa-circle-check"></i> {{ session('ok') }}</div>@endif
@if(session('err'))<div class="alert alert-err"><i class="fa fa-circle-exclamation"></i> {{ session('err') }}</div>@endif
@if($errors->any())<div class="alert alert-err"><i class="fa fa-circle-exclamation"></i> {{ $errors->first() }}</div>@endif

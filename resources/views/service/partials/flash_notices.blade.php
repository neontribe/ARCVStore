@if (count($errors) > 0)
<ul class="error dismissable">
    @foreach ($errors->all() as $error)
    <li>{!! $error !!}</li>
    @endforeach
</ul>
@endif

@if (session('error'))
<div class="error">
    {{ session('error') }}
</div>
@endif

@if (session('message'))
<div class="message">
    {{ session('message') }}
</div>
@endif

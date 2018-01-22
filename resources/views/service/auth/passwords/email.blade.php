@extends('layouts.service_master')

@section('title', 'Request Reset')

@section('content')
<div class="login">
    <h2>Reset Password</h2>
    @if (session('status'))
        <div>
            {{ session('status') }}
        </div>
    @endif

    <form role="form" method="POST" action="{{ route('password.email') }}">
        {{ csrf_field() }}
        <label for="email">E-Mail Address</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required>

        @if ($errors->has('email'))
            <span class="help-block">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif
        <button type="submit">
            Send Password Reset Link
        </button>
    </form>
</div>
@endsection

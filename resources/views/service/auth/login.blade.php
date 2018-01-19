@extends('layouts.service_master')

@section('title', 'Login')

@section('content')
<div class="login">
    <h2>Login</h2>
    @if ($errors->has('error_message'))
        <div class="alert alert-danger">
            <strong>{{ $errors->first('error_message') }}</strong>
        </div>
    @endif
    <form role="form" method="POST" action="{{ route('service.login') }}">
        {{ csrf_field() }}
        <div>
            <label for="email">E-Mail Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
            @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>
        <div>
            <label for="password">Password</label>
                <input id="password" type="password" name="password" required>
                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
            </label>
        </div>
        <button type="submit">Login</button>
        <a href="{{ route('password.request') }}">Forgot Your Password?</a>
    </form>
</div>

@endsection

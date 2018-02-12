@extends('layouts.service_master')

@section('title', 'Login')

@section('hoist-head')
    <!-- specifically to avoid login page timeout -->
    <meta http-equiv="refresh" content="{{ (config('session.lifetime') * 60) - 30 }};url={{ route('service.login') }}" />
@endsection

@section('content')
    <div class="content">
        <div class="login">
            <h2>Log In</h2>
            @if ($errors->has('error_message'))
                <div class="alert alert-danger">
                    <strong>{{ $errors->first('error_message') }}</strong>
                </div>
            @endif
            <form role="form" method="POST" action="{{ route('service.login') }}">
                {{ csrf_field() }}
                <div>
                    <label for="email">Email Address</label>
                    <input id="email" type="email" class="login-input" name="email" value="{{ old('email') }}" required
                           autofocus>
                    @if ($errors->has('email'))
                        <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
                    @endif
                </div>
                <div>
                    <label for="password">Password</label>
                    <input id="password" class="login-input" type="password" name="password" required>
                    @if ($errors->has('password'))
                        <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                    @endif
                </div>
                <div class="remember user-control">
                    <input type="checkbox" id="remember-me" class="styled-checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember-me">Remember Me</label>
                </div>
                <button type="submit">Log In</button>
                <a href="{{ route('password.request') }}">Forgot Your Password?</a>
            </form>
        </div>
    </div>
@endsection

<div class="header">
    <img src="{{ asset('assets/logo.png') }}">
    <h1>Rose Vouchers</h1>
    @if (!Auth::guest())
    <ul>
        <li>User: {{ Auth::user()->name }} </li>
        <li>Centre: @isset( Auth::user()->centre ) {{ Auth::user()->centre->name }} @endisset</li>
        <a href="{{ route('service.logout') }}"
           onclick="event.preventDefault();
           document.getElementById('logout-form').submit();">
            Logout
        </a>
        <form id="logout-form" action="{{ route('service.logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
    </ul>
    @endif
</div>
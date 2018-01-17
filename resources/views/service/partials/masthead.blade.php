<div class="header">
    <img src="{{ asset('assets/logo.png') }}">
    <h1>Rose Vouchers</h1>
    @if (!Auth::guest())
    <ul>
        <li>User: {{ Auth::user()->name }}</li>
        <li>Centre: {{ Auth::user()->centre->name }}</li>
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
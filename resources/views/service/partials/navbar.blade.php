<div class="subnav">
    <ul>
        <li class="left"><a href="{{ URL::route("service.base") }}"><i class="fa fa-arrow-left" aria-hidden="true"></i>Return to main menu</a></li>
        <h1>{{ $headerTitle }}</h1>
        @if ( Request::route()->getName() == 'service.registration.edit' )
            <li><a href="{{ URL::route("service.registration.index") }}"><i class="fa fa-search" aria-hidden="true"></i> Find another family</a></li>
        @else
        	<div class="right"></div>
        @endif
    </ul>
</div>

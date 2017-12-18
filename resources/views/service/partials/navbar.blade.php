<div class="subnav">
    <ul>
        <li><a href="{{ URL::route("service.base") }}"><i class="fa fa-arrow-left" aria-hidden="true"></i>Return to main menu</a></li>
        @if ( Request::route()->getName() == 'service.registration.edit' )
            <li><a href="{{ URL::route("service.registration.index") }}"><i class="fa fa-search" aria-hidden="true"></i> Find another family</a></li>
        @endif
    </ul>
</div>
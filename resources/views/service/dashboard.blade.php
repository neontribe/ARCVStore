@extends('layouts.service_master')

@section('title', 'Dashboard')

@section('content')
    <script type="text/javascript">
        function printCentreRegistrations()
        {
            window.open('{{ URL::route("service.centre.registrations.print", ["centre" => $centre_id]) }}');
            return false;
        }
    </script>

    <div class="content">
    <div class="navigation">
        <ul>
            <li>
                <a href="{{ URL::route('service.registration.create') }}">
                    <i class="fa fa-plus" aria-hidden="true"></i> Add a new family
                </a>
            </li>
            <li>
                <a href="{{ URL::route('service.registration.index') }}">
                    <i class="fa fa-user" aria-hidden="true"></i> Check, update or print information for one family
                </a>
            </li>
            <li>
                <a href="#" onclick="return printCentreRegistrations()">
                    <i class="fa fa-users" aria-hidden="true"></i> Print collection sheets for all families
                </a>
            </li>
        </ul>
    </div>
</div>
@endsection

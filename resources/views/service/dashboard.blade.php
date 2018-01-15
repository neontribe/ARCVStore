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
                <a href="{{ URL::route('service.registration.create') }}">
                    <li>
                        <i class="fa fa-plus" aria-hidden="true"></i> Add a new family
                    </li>
                </a>
                <a href="{{ URL::route('service.registration.index') }}">
                    <li>
                        <i class="fa fa-user" aria-hidden="true"></i> Check, update or print information for one family
                    </li>
                </a>
                <a href="#" onclick="return printCentreRegistrations()">
                    <li>
                        <i class="fa fa-users" aria-hidden="true"></i> Print collection sheets for all families
                    </li>
                </a>
            </ul>
        </div>
    </div>
@endsection

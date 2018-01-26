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
                        <i class="fa fa-search" aria-hidden="true"></i> Search for a family
                    </li>
                </a>
                <a href="#" onclick="return printCentreRegistrations()">
                    <li>
                        <i class="fa fa-users" aria-hidden="true"></i> Print collection sheets
                    </li>
                </a>
                @can( 'export', App\Registration::class )
                <a href="{{ URL::route('service.centres.registrations.summary') }}">
                    <li>
                        <i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Registrations
                    </li>
                </a>
                @endcan
            </ul>
        </div>
    </div>
@endsection

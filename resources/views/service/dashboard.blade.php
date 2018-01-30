@extends('layouts.service_master')

@section('title', 'Dashboard')

@section('content')

  @include('service.partials.navbar', ['headerTitle' => 'Main menu'])

    <script type="text/javascript">
        function printCentreRegistrations()
        {
            window.open('{{ URL::route("service.registrations.print") }}');
            return false;
        }
    </script>

    <div class="content">
        <div class="navigation">
            <ul>
                <a href="{{ URL::route('service.registration.create') }}">
                    <li>
                        <img src="{{ asset('assets/add-pregnancy-light.svg') }}" name="logo">
                        Add a new family
                    </li>
                </a>
                <a href="{{ URL::route('service.registration.index') }}">
                    <li>
                        <img src="{{ asset('assets/search-light.svg') }}" name="logo">
                        Search for a family
                    </li>
                </a>
                <a href="#" onclick="return printCentreRegistrations()">
                    <li>
                        <img src="{{ asset('assets/print-light.svg') }}" name="logo">
                        Print collection sheets
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

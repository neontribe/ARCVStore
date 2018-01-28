@extends('layouts.service_master')

@section('title', 'Dashboard')

@section('content')

  @include('service.partials.navbar', ['headerTitle' => 'Main menu'])

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
            </ul>
        </div>
    </div>
@endsection

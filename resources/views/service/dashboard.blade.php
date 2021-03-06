@extends('layouts.service_master')

@section('title', 'Dashboard')

@section('content')

  @include('service.partials.navbar', ['headerTitle' => 'Main menu'])

    <div class="content navigation">
        <ul>
            <a href="{{ URL::route('service.registration.create') }}">
                <li>
                    <img src="{{ asset('assets/add-pregnancy-light.svg') }}" name="add-family">
                    Add a new family
                </li>
            </a>
            <a href="{{ URL::route('service.registration.index') }}">
                <li>
                    <img src="{{ asset('assets/search-light.svg') }}" name="search">
                    Search for a family
                </li>
            </a>
            <a href="{{ $print_route }}" target="_blank" >
                <li>
                    <img src="{{ asset('assets/print-light.svg') }}" name="print-registrations">
                    {{ $print_button_text }}
                </li>
            </a>
            @can( 'export', App\Registration::class )
            <a href="{{ URL::route('service.centres.registrations.summary') }}">
                <li>
                    <img src="{{ asset('assets/export-light.svg') }}" name="export-registrations">
                    Export Registrations
                </li>
            </a>
            @endcan
        </ul>
    </div>
@endsection

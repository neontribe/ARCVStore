@extends('layouts.service_master')

@section('title', 'Dashboard')

@section('content')

  @include('service.partials.navbar', ['headerTitle' => 'Main menu'])

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
                @if ( $print_button_text == "Print all family sheets")
                    <a href="{{ URL::route('service.registrations.print') }}" target="_blank" >
                @else
                    <a href="{{ URL::route('service.centre.registrations.collection', ['id' => $centre_id ]) }}" target="_blank" >
                @endif
                    <li>
                        <img src="{{ asset('assets/print-light.svg') }}" name="logo">
                        {{ $print_button_text }}
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

@extends('layouts.service_master')

@section('title', 'Check / Update Registration')

@section('content')

    @include('service.partials.flash_notices')

    @include('service.partials.navbar')
    <div class="content search">
        <div class="row">
            <div class="col">
                <form action="{{ URL::route('service.registration.index') }}" method="GET" id="searchform">
                    {!! csrf_field() !!}
                    <div>
                        <h2>Search for a family name</h2>
                        <div class="small-button-container">
                            <input type="search" name="family_name">
                            <button>
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h2>Results:</h2>
                <table>
                    <thead>
                    <tr>
                        <td>Name</td>
                        <td>CC ID</td>
                        <td>Amount of eligible children</td>
                        <td></td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($registrations as $registration)
                        <tr>
                            <td>{{ $registration->family->carers->first()->name }}</td>
                            <td>{{ $registration->cc_reference }}</td>
                            <td>{{ $registration->family->entitlement }}</td>
                            <td>
                                <button onclick="window.location.href={{ URL::route('service.registration.edit', ['id' => $registration->family->id ]) }}">select</button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

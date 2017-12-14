@extends('layouts.service_master')

@section('title', 'Check / Update Registration')

@section('content')

    @include('service.partials.flash_notices')

    @include('service.partials.navbar')
    <div class="content search">
        <div class="row">
            <div class="col">
                <div>
                    <h2>Search for a family name:</h2>
                    <div class="small-button-container">
                        <input type="search">
                        <button>
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
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
                            <button>select</button>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


@endsection

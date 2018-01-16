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
                    <div class="input">
                        <h2>Search for a family name</h2>
                        <div class="small-button-container">
                            <input type="search" name="family_name" autocomplete="off" autocorrect="off" spellcheck="false">
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
                <table>
                    <thead>
                    <tr>
                        <td>Name</td>
                        <td>Voucher Entitlement</td>
                        <td>RV-ID</td>
                        <td></td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($registrations as $registration)
                        <tr>
                            <td>{{ $registration->family->carers->first()->name }}</td>
                            <td>{{ $registration->family->entitlement }}</td>
                            <td>{{ $registration->family->rvid }}</td>
                            <td>
                                <button onclick="window.location.href='{{ URL::route('service.registration.edit', ['id' => $registration->id ]) }}'">select</button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class = "row">
            <div class="col">
                {{ $registrations->links() }}
            </div>
        </div>
    </div>
@endsection

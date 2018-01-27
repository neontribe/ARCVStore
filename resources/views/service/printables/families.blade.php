@extends('layouts.printable_master')


<link rel="stylesheet" type="text/css" href="{{ asset('css/families.css') }}">

@section('title', $sheet_title)

@section('content')

    <div class="content">
        <table>
            <tr>
                <th>CC: {{ $centre->name }}</th>
                <th>CC Ref.</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            @foreach ($registrations as $registration)
            <tr>
                <td>
                    <!-- Error prone but ok for now. Controller should do more of this work once we know it's what we want.!-->
                    <p>{{ $registration->family->carers->first()->name }}</p>
                    <span>{{ $registration->family->rvid }}</span>
                    <span><i class="fa fa-ticket" aria-hidden="true"></i> {{ $registration->family->entitlement }}</span>
                </td>
                <td>{{ $registration->cc_reference }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            @endforeach
        </table>
    </div>
@endsection

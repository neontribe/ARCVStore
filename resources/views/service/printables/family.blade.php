@extends('layouts.printable_master')

<link rel="stylesheet" type="text/css" href="{{ asset('css/print.css') }}">

@section('title', $sheet_title)

@section('content')

    <div class="content">
        <div>
            <h2>Main Carer</h2>
            <p>{{ $pri_carer->name }}</p>
        </div>
        <div>
            <h2>Voucher Allocations</h2>
            <p>{{ $family->entitlement }}</p>
        </div>
        <div>
            <h2>Other Collectors</h2>
            <ul>
                @foreach ($sec_carers as $carer)
                <li>{{ $carer->name }}</li>
                @endforeach
            </ul>
        </div>
        <div>
            <h2>Children</h2>
            <ul>
            @foreach( $family->getCreditReasons() as $credits)
                <li>{{ $credits['reason_vouchers'] }} {{ str_plural('voucher', $credits['reason_vouchers']) }} as {{ $credits['count'] }} {{ str_plural($credits['entity'], $credits['count']) }} currently "{{ $credits['reason'] }}"</li>
            @endforeach
            </ul>
        </div>
        <table>
            <tr>
                <th>Collection Date</th>
                <th>Amount of vouchers given</th>
                <th>Collector Signature</th>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>
        <div class="notes">
            <h2>Notices</h2>
            @forelse( $family->getNoticeReasons() as $notices)
                <p><i class="fa fa-exclamation-circle" aria-hidden="true"></i>{{ $notices['count'] }} {{ str_plural($notices['entity'], $notices['count']) }} currently "{{ $notices['reason'] }}"</p>
            @empty
                <ul>
                    <li>Nothing of concern.</li>
                </ul>
            @endforelse
        </div>
    </div>
@endsection

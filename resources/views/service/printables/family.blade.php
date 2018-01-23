@extends('layouts.printable_master')

<link href="https://fonts.googleapis.com/css?family=Roboto+Slab" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">

<link rel="stylesheet" type="text/css" href="{{ asset('css/print.css') }}">

@section('title', $sheet_title)

@section('content')

    <div class="content">
        <h1>Family Voucher Collection Sheet</h1>
        <!-- <div>
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
        </div> -->
        <table>
            <tr>
                <th colspan="5">
                    <h2>Main Carer's Name</h2>
                </th>
                <td rowspan="2">Date Printed:</td>
            </tr>
            <tr>
                <th colspan="5">
                    <h3>Children's Centre Name</h3>
                </th>
            </tr>
            <tr>
                <td>RV-ID</td>
                <td>Voucher allocation</td>
                <td>Vouchers given out</td>
                <td>Voucher numbers</td>
                <td>Date collected</td>
                <td>Signature</td>
            </tr>
            <tr>
                <td rowspan="4">ARC00123</td>
                <td rowspan="4">(ICON) 6</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>
        <div class="allocation">
        </div>
        <div class="notices">
            <div>
                <h3>Hints &amp; Tips</h3>
            </div>
            <div>
                <h3>Reminder</h3>
                @forelse( $family->getNoticeReasons() as $notices)
                    <p><i class="fa fa-exclamation-circle" aria-hidden="true"></i>{{ $notices['count'] }} {{ str_plural($notices['entity'], $notices['count']) }} currently "{{ $notices['reason'] }}"</p>
                @empty
                    <ul>
                        <li>No reminders for this family.</li>
                    </ul>
                @endforelse
            </div>
        </div>
    </div>
@endsection

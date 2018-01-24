@extends('layouts.printable_master')

<link href="https://fonts.googleapis.com/css?family=Roboto+Slab" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">

<link rel="stylesheet" type="text/css" href="{{ asset('css/print.css') }}">

@section('title', $sheet_title)

@section('content')

    <div class="content">
        <h1>Family Voucher Collection Sheet</h1>
        <table>
            <tr>
                <th colspan="5">
                    <h2>Main Carer's Name:</h2>
                    <p>{{ $pri_carer->name }}</p>
                </th>
                <td rowspan="2" class="colspan">
                    <p>Date Printed:<p>
                    <p> {{ \Carbon\Carbon::now()->toFormattedDateString() }} </p>
                </td>
            </tr>
            <tr>
                <th colspan="5" >
                    <h3>Children's Centre Name:</h3>
                    <p>{{ $centre->name }}</p>
                </th>
            </tr>
            <tr>
                <td class="med-cell">RV-ID</td>
                <td class="sml-cell">Voucher allocation</td>
                <td class="sml-cell">Vouchers given out</td>
                <td>Voucher numbers</td>
                <td>Date collected</td>
                <td class="lrg-cell">Signature</td>
            </tr>
            <tr>
                <td rowspan="4" class="colspan">{{ $family->rvid }}</td>
                <td rowspan="4" class="colspan vouchers"><i class="fa fa-ticket" aria-hidden="true"></i> {{ $family->entitlement }}</td>
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
          <p>This family should collect <strong>{{ $family->entitlement }}</strong> vouchers per week:</p>
          <ul>
            @foreach( $family->getCreditReasons() as $credits)
                <li>{{ $credits['reason_vouchers'] }} {{ str_plural('voucher', $credits['reason_vouchers']) }} as {{ $credits['count'] }} {{ str_plural($credits['entity'], $credits['count']) }} currently "{{ $credits['reason'] }}"</li>
            @endforeach
          </ul>
          <p>Their RV-ID is: <strong>{{ $family->rvid }}</strong></p>
        </div>
        <div class="notices">
            <div>
                <h3><i class="fa fa-question-circle" aria-hidden="true"></i> Hints &amp; Tips</h3>
                <p>Have you completed the food diary and pie chart for this family?</p>
                <p>When did you last chat to them about how they're finding shopping at the market?</p>
            </div>
            <div>
                <h3><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Reminder</h3>
                @forelse( $family->getNoticeReasons() as $notices)
                    <p> {{ $notices['count'] }} {{ str_plural($notices['entity'], $notices['count']) }} currently "{{ $notices['reason'] }}"</p>
                @empty
                    <p>No reminders for this family.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@extends('layouts.printable_master')

@section('title', $sheet_title)

@section('content')

    @foreach ( $regs as $index => $reg )
    @include('service.printables.partials.masthead', ['specificPrintNote' => 'Print a new form every 4 weeks so you have the most up to date information available.'])

    <div class="content">
        <h1>{{ $reg["pri_carer"]->name }}</h1>
        <table>
            <tr class="titles">
                <th colspan="5">
                    <h2>Main Carer's Name:</h2>
                    <p>{{ $reg["pri_carer"]->name }}</p>
                </th>
                <td rowspan="2" class="colspan">
                    <p>Date Printed:<p>
                    <p> {{ \Carbon\Carbon::now()->toFormattedDateString() }} </p>
                </td>
            </tr>
            <tr class="titles">
                <th colspan="5" >
                    <h3>Children's Centre Name:</h3>
                    <p>{{ $reg["centre"]->name }}</p>
                </th>
            </tr>
            <tr class="titles">
                <td class="med-cell">RV-ID</td>
                <td class="sml-cell">Voucher allocation</td>
                <td class="sml-cell">Vouchers given out</td>
                <td>Voucher numbers</td>
                <td>Date collected</td>
                <td class="lrg-cell">Signature</td>
            </tr>
            <tr>
                <td rowspan="4" class="colspan">{{ $reg["family"]->rvid }}</td>
                <td rowspan="4" class="colspan vouchers"><i class="fa fa-ticket" aria-hidden="true"></i> {{ $reg["family"]->entitlement }}</td>
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
        <table class="more-info {{ ($index + 1) == count($regs) ? 'no-page-break' : '' }}">
            <tr>
                <td rowspan="2">
                    <p>This family should collect <strong>{{ $reg["family"]->entitlement }}</strong> vouchers per week:</p>
                    <ul>
                    @foreach( $reg["family"]->getCreditReasons() as $credits)
                        <li>{{ $credits['reason_vouchers'] }} {{ str_plural('voucher', $credits['reason_vouchers']) }} as {{ $credits['count'] }} {{ str_plural($credits['entity'], $credits['count']) }} currently "{{ $credits['reason'] }}"</li>
                    @endforeach
                    </ul>
                    <p>Their RV-ID is: <strong>{{ $reg["family"]->rvid }}</strong></p>
                </td>
                <td>
                    <div>
                        <h3><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Reminder</h3>
                        @if ( count($reg["reminders"]) > 0 || count($reg["family"]->getNoticeReasons()) > 0  )
                        @foreach ( $reg["reminders"] as $reminder )
                            <br>{{ $reminder['entity'] }} has {{ $reminder['reason'] }}</br>
                        @endforeach
                        @foreach( $reg["family"]->getNoticeReasons() as $notices)
                            <br> {{ $notices['count'] }} {{ str_plural($notices['entity'], $notices['count']) }} currently "{{ $notices['reason'] }}"</br>
                        @endforeach
                        @else
                            <p>No reminders for this family.</p>
                        @endif
                    </div>
                </td>
            </tr>
        </table>
    </div>    
    @endforeach
@endsection

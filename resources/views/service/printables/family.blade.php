@extends('layouts.printable_master')

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
            <h2>Eligible Children</h2>
            <ul>
                <!-- TODO I htink these should be counts and strings of Pregnancy, child under 1, child between 1 and school age.
                 But not sure. Leaving as is for now. --!>
                @foreach ($children as $child)
                <li><strong>1</strong> {{$child->getStatusString() }}</li>
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
            <h2>Comments</h2>
            @foreach ($family->notes as $note)
            <p><i class="fa fa-exclamation-circle" aria-hidden="true"></i> $note</p>
            @endforeach
        </div>
    </div>
@endsection

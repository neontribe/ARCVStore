@extends('layouts.printable_master')

@section('title', $sheet_title)

@section('content')
    <div class="content">
    @foreach ($reg_chunks as $chunk)
      <h1>Weekly Voucher Collection Sheet</h1>
      <div>
        <h2>Children's Centre name: {{ $centre->name }}</h2>
        <p>Week commencing</p>
      </div>
      <table>
        <tr>
          <th>Main carer's name</th>
          <th>RV-ID</th>
          <th>Voucher allocation</th>
          <th>Vouchers given out</th>
          <th>Voucher numbers</th>
          <th>Date collected</th>
          <th>Signature</th>
          <th>Attention</th>
        </tr>
        @foreach ($chunk as $registration)
        <tr>
          <td>{{ $registration->family->carers->first()->name }}</td>
          <td>{{ $registration->family->rvid }}</td>
          <td><i class="fa fa-ticket" aria-hidden="true"></i> {{ $registration->family->entitlement }}</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        @endforeach
      </table>
      <div>
        <div class="notices">
          <h3><i class="fa fa-question-circle" aria-hidden="true"></i> Hints &amp; Tips</h3>
          <p>Have you completed the food diary and pie chart for all your families?</p>
          <p>When did you last chat to them about how they're finding shopping at the market?</p>
        </div>
        <div class="attention">
          <h3><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Attention</h3>
          <p>When this icon is displayed for a family, next month the number of vouchers the family can collect will change because of a child's birthday. Please help them to get ready for this. You can find more information about the change if you search for the family in the Rose Voucher app.</p>
        </div>
        @endforeach
    </div>
@endsection

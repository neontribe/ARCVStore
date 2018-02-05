@extends('layouts.printable_master')

@section('title', $sheet_title)

@section('content')

  @foreach ($reg_chunks as $chunk)

  @include('service.printables.partials.masthead', ['specificPrintNote' => 'Ideally you should print this form every week to keep voucher allocations as up to date as possible.'])

    <div class="content">
      <h1>Weekly Voucher Collection Sheet</h1>
      <div class="subhead">
        <h2>Children's Centre: {{ $centre->name }}</h2>
        <div>
          <p>Week commencing</p>
          <img src="{{ asset('assets/date-field.svg') }}">
        </div>
      </div>
      <table class="families_table">
        <tr>
          <th>Main carer's name</th>
          <th>RV-ID</th>
          <th class="sml-cell">Voucher allocation</th>
          <th class="sml-cell">Vouchers given out</th>
          <th>Voucher numbers</th>
          <th>Date collected</th>
          <th class="lrg-cell">Signature</th>
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
        </tr>
        @endforeach
      </table>
      <div class="notices">
        <h3><i class="fa fa-question-circle" aria-hidden="true"></i> Hints &amp; Tips</h3>
        <p>Have you completed the food diary and pie chart for all your families?</p>
        <p>When did you last chat to them about how they're finding shopping at the market?</p>
      </div>
      <div class="attention">
        <h3><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Attention</h3>
        <p>When this icon is displayed for a family, next month the number of vouchers the family can collect will change because of a child's birthday. Please help them to get ready for this. You can find more information about the change if you search for the family in the Rose Voucher app.</p>
      </div>
    </div>

  @endforeach

@endsection

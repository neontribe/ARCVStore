@extends('layouts.printable_master')

@section('title', $sheet_title)

@section('content')

  @include('service.printables.partials.masthead', ['specificPrintNote' => 'Ideally you should print this form every week to keep voucher allocations as up to date as possible.'])

    <div class="content families">
      <h1>Weekly Voucher Collection Sheet</h1>
      <table class="info centre">
        <tr>
          <td>
            <h2>Children's Centre: {{ $centre->name }}</h2>
          </td>
          <td class="week-commencing">
            <p>Week commencing</p>
            <img src="{{ asset('assets/date-field.svg') }}">
          </td>
        </tr>
      </table>
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
        @foreach ($registrations as $registration)
        <tr>
          <td>
            @if(!empty($registration->family->getNoticeReasons()))
              <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
            @endif
            {{ $registration->family->pri_carer }}
          </td>
          <td>{{ $registration->family->rvid }}</td>
          <td><i class="fa fa-ticket" aria-hidden="true"></i> {{ $registration->family->entitlement }}</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        @endforeach
      </table>
      <table class="info general">
        <tr>
          <td valign="top">
            <h3><i class="fa fa-question-circle" aria-hidden="true"></i> Hints &amp; Tips</h3>
            <p>Have you completed the food diary and pie chart for all your families?</p>
            <p>Have you sent privacy statements for all your families?</p>
            <p>When did you last chat to them about how they're finding shopping at the market?</p>
          </td>
          <td valign="top">
            <h3><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Attention</h3>
            <p>When this icon is displayed for a family, next month the number of vouchers the family can collect will change because of a child's birthday. Please help them to get ready for this. You can find more information about the change if you search for the family in the Rose Voucher app.</p>
          </td>
        </tr>
      </table>
    </div>


@endsection

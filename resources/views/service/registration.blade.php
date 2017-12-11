@extends('layouts.service_master')

@section('title', 'Add a new family')

@section('content')

    @include('service.partials.flash_notices')

    <div class="content">
        <form action="{{ URL::route("service.registration") }}" method="post" >
            {!! csrf_field() !!}
            <div class="col">
                <div>
                    <h2>
                        <label for="carer">
                            <i class="fa fa-user" aria-hidden="true"></i> Main Carer's full name:
                        </label>
                    </h2>
                    <input id="carer" name="carer" type="text">
                </div>
                <div class="add">
                    <h2>
                        <label for="sec-carers">
                            <i class="fa fa-user" aria-hidden="true"></i> Other voucher collectors:
                        </label>
                    </h2>
                    <input id="sec-carers" name="carers" type="text">
                    <button id="add-collector">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="collectors">
                    <p>These people may collect vouchers:</p>
                </div>
            </div>
            <div class="col">
                <div class="add">
                    <h2>
                        <i class="fa fa-user-plus" aria-hidden="true"></i>
                        Add Children <span><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                    </h2>
                    <h3>
                        <label for="birth-month">Month + Year of birth (or due date for pregnancy)</label>
                    </h3>
                    <input id="birth-month" name="children" type="month" min="1998-01" >
                    <button id="add-collector">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </button>
                </div>
                <div>
                    <h2>Children signed up:</h2>
                    <table>
                        <tr>
                            <td>Age</td>
                            <td>Month/Year</td>
                            <td>Info</td>
                        </tr>
                        <tr>
                            <td>7</td>
                            <td>Sep 09</td>
                            <td>At School</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Jan 14</td>
                            <td>On Scheme</td>
                        </tr>
                        <tr>
                            <td>P</td>
                            <td>Jun 18</td>
                            <td>Pregnancy</td>
                        </tr>
                    </table>
                </div>
                <div class="reminder">
                    <p>Reminder: don't forget to complete food diary and chart.</p>
                </div>
            </div>
            <div class="col">
                <div>
                    <label for="cc-id">CC ID number:</label>
                    <input type="text" id="cc-id" name="cc_reference">
                </div>
                <div>
                    <h2>Reason for receiving Rose Vouchers <span><i class="fa fa-info-circle"
                                                                    aria-hidden="true"></i></span></h2>
                    <div class="user-control">
                        <input type="radio" id="healthy-start" value="healthy-start" name="eligibility" checked="checked"/>
                        <label for="healthy-start">Healthy Start</label>
                    </div>
                    <div class="user-control">
                        <input type="radio" id="other" value="other" name="eligibility"/>
                        <label for="other">Other Local Criteria</label>
                    </div>
                </div>
                <div class="user-control">
                    <input type="checkbox" id="privacy-statement" name="consent"/>
                    <label for="privacy-statement">Have you got the signed privacy statement for the family?</label>
                </div>
                <button type="Submit">Submit</button>
            </div>
        </form>
    </div>
@endsection
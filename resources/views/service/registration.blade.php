@extends('layouts.service_master')

@section('title', 'Dashboard')

@section('content')
    <div class="content">
        <form>
            <div class="col">
                <div>
                    <h2>
                        <label for="pri_carer">
                            <i class="fa fa-user" aria-hidden="true"></i> Main Carer's full name:
                        </label>
                    </h2>
                    <input id="pri_carer" type="text">
                </div>
                <div class="add">
                    <h2>
                        <label for="sec_carers">
                            <i class="fa fa-user" aria-hidden="true"></i> Other voucher collectors:
                        </label>
                    </h2>
                    <input id="sec_carers" type="text">
                    <button name="add-collector">
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
                        <label for="birth_month">Month + Year of birth (or due date for pregnancy)</label>
                    </h3>
                    <input id="birth_month" type="month" min="1998-01">
                    <button name="add-collector">
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
                    <input type="text" id="cc-id">
                </div>
                <div>
                    <h2>Reason for receiving Rose Vouchers <span><i class="fa fa-info-circle"
                                                                    aria-hidden="true"></i></span></h2>
                    <div class="user-control">
                        <input type="radio" id="healthy-start" name="voucher-reason" checked="checked"/>
                        <label for="healthy-start">Healthy Start</label>
                    </div>
                    <div class="user-control">
                        <input type="radio" id="other" name="voucher-reason"/>
                        <label for="other">Other Local Criteria</label>
                    </div>
                </div>
                <div class="user-control">
                    <input type="checkbox" id="privacy-statement"/>
                    <label for="privacy-statement">Have you got the signed privacy statement for the family?</label>
                </div>
                <button type="Submit">Submit</button>
            </div>
        </form>
    </div>
@endsection
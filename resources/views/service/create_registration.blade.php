@extends('layouts.service_master')

@section('title', 'Add a new Family')

@section('content')

    @include('service.partials.flash_notices')

    <div class="content">
        <form action="{{ URL::route("service.registration.store") }}" method="post" >
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
                        <label for="birth-date">Month + Year of birth (or due date for pregnancy)</label>
                    </h3>
                    <select aria-labelledby="birth-date">
                        <option value="january">January</option>
                        <option value="february">February</option>
                        <option value="march">March</option>
                        <option value="april">April</option>
                        <option value="may">May</option>
                        <option value="june">June</option>
                        <option value="july">July</option>
                        <option value="august">August</option>
                        <option value="september">September</option>
                        <option value="october">October</option>
                        <option value="november">November</option>
                        <option value="december">December</option>
                    </select>
                    <select aria-labelledby="birth-date">
                        <option value="1996">1996</option>
                        <option value="1997">1997</option>
                        <option value="1998">1998</option>
                        <option value="1999">1999</option>
                        <option value="2000">2000</option>
                        <option value="2001">2001</option>
                        <option value="2002">2002</option>
                        <option value="2003">2003</option>
                        <option value="2004">2004</option>
                        <option value="2005">2005</option>
                        <option value="2006">2006</option>
                        <option value="2007">2007</option>
                        <option value="2008">2008</option>
                        <option value="2009">2009</option>
                        <option value="2010">2010</option>
                        <option value="2011">2011</option>
                        <option value="2012">2012</option>
                        <option value="2013">2013</option>
                        <option value="2014">2014</option>
                        <option value="2015">2015</option>
                        <option value="2016">2016</option>
                        <option value="2017">2017</option>
                        <option value="2018">2018</option>
                        <option value="2019">2019</option>
                    </select>
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
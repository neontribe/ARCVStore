@extends('layouts.service_master')

@section('title', 'Check / Update Registration')

@section('content')

    @include('service.partials.navbar')

    @include('service.partials.flash_notices')

    <div class="content check">
        <div class="row">
            <form action="{{ URL::route("service.registration.edit",['id' => $registration->id]) }}" method="post">
                {{ method_field('PUT') }}
                {!! csrf_field() !!}
                <input type="hidden" name="registration" value="{{ $registration->id }}">
                <div class="col">
                    <div>
                        <h2>CC Reference:</h2>
                        <div class="small-button-container">
                            <input type="text" name="cc_reference" value="{{ $registration->cc_reference }}">
                        </div>
                    </div>
                    <div>
                        <h2>Main Carer:</h2>
                        <div class="small-button-container">
                            <input type="text" name="carer" value="{{ $pri_carer->name }}">
                        </div>
                    </div>
                    <div class="other-carers-update edit">
                        <h2>
                            <label for="carer_adder_input">
                                <i class="fa fa-user" aria-hidden="true"></i> Other Collectors:
                            </label>
                        </h2>
                            <table id="carer_wrapper">
                            @foreach ($sec_carers as $sec_carer)
                                <tr>
                                    <td><input name="carers[]" type="hidden" value="{{ $sec_carer->name }}" >{{ $sec_carer->name }}</td>
                                    <td><button class="remove_field"><i class="fa fa-minus" aria-hidden="true"></i></button></td>
                                </tr>
                            @endforeach
                            </table>
                        <div id="carer_adder" class="small-button-container">
                            <input id="carer_adder_input" name="carer_adder_input" type="text">
                            <button id="add_collector" class="addButton">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="edit">
                        <h2>Children Signed Up:</h2>
                        <button class="edit-button">
                            <i class="fa fa-pencil" aria-hidden="true"></i>
                        </button>
                        <table>
                            <thead>
                            <tr>
                                <td>Age</td>
                                <td>Month/Year</td>
                                <td>Info</td>
                                <td>Edit</td>
                            </tr>
                            </thead>
                            <tbody id="existing_wrapper">
                            @foreach ($children as $child)
                                <tr>
                                    <td>{{ $child->getAgeString() }}</td>
                                    <td>{{ $child->getDobAsString() }}</td>
                                    <td>{{ $child->getStatusString() }}</td>
                                    <td>
                                        <input type="hidden" name="children[]" value="{{ Carbon\Carbon::parse($child->dob)->format('Y-m') }}">
                                        <button class="remove_date_field"><i class="fa fa-minus" aria-hidden="true"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div>
                        <h2><i class="fa fa-user-plus" aria-hidden="true"></i> Add Children <span><i class="fa fa-info-circle" aria-hidden="true"></i></span></h2>
                        <h3>
                            <label for="birth-date">Month + Year of birth (or due date for pregnancy)</label>
                        </h3>
                        <table>
                            <tbody id="child_wrapper">

                            </tbody>
                        </table>
                        <select id="month_adder_input" aria-labelledby="birth-date">
                            <option value="01">January</option>
                            <option value="02">February</option>
                            <option value="03">March</option>
                            <option value="04">April</option>
                            <option value="05">May</option>
                            <option value="06">June</option>
                            <option value="07">July</option>
                            <option value="08">August</option>
                            <option value="09">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                        <select id="year_adder_input" aria-labelledby="birth-date">
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
                        <button id="add_dob" class="addDateButton">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </button>
                    </div>
                    <button type="Submit">Save Changes</button>
                </div>
            </form>

            <div class="col collect">
                <p>ARC ID: {{ $family->rvid }}</p>
                <div>
                    <h2>This family may collect <strong>{{ $family->entitlement }}</strong> per week:</h2>
                    <ul>
                        @foreach( $family->getCreditReasons() as $credits)
                            <li><strong>{{ $credits['reason_vouchers'] }} {{ str_plural('voucher', $credits['reason_vouchers']) }}</strong> as {{ $credits['count'] }} {{ str_plural($credits['entity'], $credits['count']) }} currently "{{ $credits['reason'] }}"</li>
                        @endforeach
                    </ul>
                </div>
                <div class="warning">
                    @foreach( $family->getNoticeReasons() as $notices)
                    <p><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Warning: {{ $notices['count'] }} {{ str_plural($notices['entity'], $notices['count']) }} currently "{{ $notices['reason'] }}"</p>
                    @endforeach
                </div>
                <div class="attention">
                    <p><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Reminder: Food Matters have not
                        received the food diary and chart for this family yet.</p>
                </div>
                <div class="print-button">
                    <button onclick="window.open( '{{ URL::route( "service.registration.print", ["id" => $registration->id]) }}' ); return false">Print a voucher collection sheet for this family</button>
                </div>
            </div>
        </div>
    </div>

    <script>

        $(document).ready(
            function () {
                var maxFields = 10;
                var el = $("#carer_wrapper");
                var carer_el = $('#carer_adder_input');
                var addButton = $("#add_collector");
                var fields = 1;
                $(addButton).click(function (e) {
                    e.preventDefault();
                    if (carer_el.val().length <= 1) {
                        return false;
                    }
                    if (fields < maxFields) {
                        fields++;
                        $(el).append('<tr><td><input name="carers[]" type="hidden" value="' + carer_el.val() + '" >' + carer_el.val() + '</td><td><button class="remove_field"><i class="fa fa-minus" aria-hidden="true"></i></button></td></tr>');
                    }
                });

                $(el).on("click", ".remove_field", function (e) {
                    e.preventDefault();
                    $(this).closest('tr').remove();
                    fields--;
                })
            }
        );

        $(document).ready(
            function() {
                var el = $("#child_wrapper");
                var monthEl = $('#month_adder_input');
                var yearEl = $('#year_adder_input');
                var addDateButton = $("#add_dob");

                $(addDateButton).click(function (e) {
                    e.preventDefault();
                    var dateString = yearEl.val() +'-'+ monthEl.val();
                    $(el).append('<tr><td><input name="children[]" type="hidden" value="' +dateString+ '" >' + dateString + '</td><td><button class="remove_date_field"><i class="fa fa-minus" aria-hidden="true"></i></button></td></tr>');
                });

                $(el).on("click", ".remove_date_field", function (e) {
                    e.preventDefault();
                    console.log("clicked");
                    $(this).closest('tr').remove();
                    return false;
                });
            }


        );

        $(document).ready(
            function() {
                var el = $("#existing_wrapper");
                $(el).on("click", ".remove_date_field", function (e) {
                    e.preventDefault();
                    console.log("clicked");
                    $(this).closest('tr').remove();
                    return false;
                });
            }
        );

        </script>
@endsection

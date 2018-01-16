@extends('layouts.service_master')

@section('title', 'Add a new Family')

@section('content')

    @include('service.partials.navbar')

    @include('service.partials.flash_notices')
    <div class="content">
        <form action="{{ URL::route("service.registration.store") }}" method="post">
            {!! csrf_field() !!}
            <div class="col">
                <div>
                    <h2>
                        <label for="carer">
                            <i class="fa fa-user" aria-hidden="true"></i> Main Carer's full name:
                        </label>
                    </h2>
                    <input id="carer" name="carer" type="text" value="{{ old('carer') }}">
                </div>
                <div class="add">
                    <h2>
                        <label for="carer_adder_input">
                            <i class="fa fa-user" aria-hidden="true"></i> Other people who can collect:
                        </label>
                    </h2>
                    <table id="carer_wrapper">
                        @if(is_array(old('carers')) || (!empty(old('carers'))))
                            @foreach (old('carers') as $old_sec_carer )
                                <tr>
                                    <td><input name="carers[]" type="hidden" value="{{ $old_sec_carer }}">{{ $old_sec_carer }}</td>
                                    <td><button type="button" class="remove_field"><i class="fa fa-minus" aria-hidden="true"></i></button></td>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                    <div id="carer_adder" class="small-button-container">
                        <input id="carer_adder_input" name="carer_adder_input" type="text">
                        <button id="add_collector" class="addButton">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
                <div class="added">
                    <h2>You have added:</h2>
                </div>
            </div>
            <div class="col">
                <div class="add">
                    <h2>
                        <i class="fa fa-user-plus" aria-hidden="true"></i>
                        Adding children or pregnancy:
                    </h2>
                    <h3>
                        <label for="birth-date">To add a child, complete the boxes below with their month and year of birth in numbers, e.g. '06 2017' for June 2017.</label>
                    </h3>
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
                <div class="added">
                    <h2>You have added:</h2>
                    <table>
                        <tbody id="child_wrapper">
                            @if(is_array(old('children')) || (!empty(old('children'))))
                                @foreach (old('children') as $old_child )
                                <tr>
                                    <td><input name="children[]" type="hidden" value="{{ $old_child }}" > {{ $old_child }}</td>
                                    <td><button class="remove_date_field"><i class="fa fa-minus" aria-hidden="true"></i></button></td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col">
                <div>
                    <label for="cc-id">CC Reference:</label>
                    <input type="text" id="cc-id" name="cc_reference" value="{{ old('cc_reference') }}">
                </div>
                <div>
                    <h2>Reason for receiving Rose Vouchers <span><i class="fa fa-info-circle"
                                                                    aria-hidden="true"></i></span></h2>
                    <div class="user-control">
                        <input type="radio" id="healthy-start" value="healthy-start" name="eligibility"
                                @if(old('eligibility') == "healthy-start") checked="checked" @endif
                                @if(empty(old('eligibility'))) checked="checked" @endif
                        />
                        <label for="healthy-start">Entitled to Healthy Start</label>
                    </div>
                    <div class="user-control">
                        <input type="radio" id="other" value="other" name="eligibility"
                               @if(old('eligibility') == "other") checked="checked" @endif
                        />
                        <label for="other">Other Local Criteria</label>
                    </div>
                </div>
                <div>
                    <h2>Have you got the signed privacy statement for the family?</h2>
                    <div class="user-control">
                        <input type="checkbox" id="privacy-statement" name="consent" @if( old('consent') ) checked @endif/>
                        <label for="privacy-statement">Yes</label>
                    </div>
                </div>
                <div class="reminder">
                    <p>Reminder: don't forget to complete food diary and chart.</p>
                </div>
                <button type="Submit">Save Family</button>
            </div>
        </form>
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
                        $(el).append('<tr><td><input name="carers[]" type="hidden" value="' + carer_el.val() + '" >' + carer_el.val() + '</td><td><button type="button" class="remove_field"><i class="fa fa-minus" aria-hidden="true"></i></button></td></tr>');
                        carer_el.val('');
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
                    var dateString = yearEl.val() + '-' + monthEl.val();
                    var humanDateString = new Date(dateString).toLocaleDateString('en-gb',{month:"short"}) +" "+ yearEl.val();
                    $(el).append('<tr><td><input name="children[]" type="hidden" value="' +dateString+ '" >' + humanDateString + '</td><td><button type="button" class="remove_date_field"><i class="fa fa-minus" aria-hidden="true"></i></button></td></tr>');
                });

                $(el).on("click", ".remove_date_field", function (e) {
                    e.preventDefault();
                    $(this).closest('tr').remove();
                    return false;
                });
            }
        );
    </script>

@endsection

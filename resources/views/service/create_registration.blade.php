@extends('layouts.service_master')

@section('title', 'Add a new Family')

@section('content')

    @include('service.partials.navbar', ['headerTitle' => 'New family sign up'])

    @include('service.partials.flash_notices')

    <div class="content">
        <form action="{{ URL::route("service.registration.store") }}" method="post">
            {!! csrf_field() !!}
            <div class="col">
                <div>
                    <img src="{{ asset('assets/group-light.svg') }}" name="logo">
                    <h2>Adding voucher collectors</h2>
                </div>
                <div>
                    <label for="carer">Main carer's full name</label>
                    <input id="carer" name="carer" type="text" autocomplete="off" autocorrect="off" spellcheck="false" value="{{ old('carer') }}">
                </div>
                <div>
                    <label for="carer_adder_input">Other people who can collect: <span>(optional)</span></label>
                    <div id="carer_adder" class="small-button-container">
                        <input id="carer_adder_input" name="carer_adder_input" type="text" autocomplete="off" autocorrect="off" spellcheck="false">
                        <button id="add_collector" class="addButton">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </button>
                    </div>
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
                </div>
                <div class="added">
                    <h2>You have added:</h2>
                </div>
            </div>
            <div class="col">
                <div>
                    <img src="{{ asset('assets/pregnancy-light.svg') }}" name="logo">
                    <h2>Adding children or pregnancies</h2>
                </div>
                <div>
                    <p>To add a child, complete the boxes below with their month and year of birth in numbers, e.g. '06 2017' for June 2017.</h3>
                    </p>
                    @include('service.partials.add_child_form')
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
                    <img src="{{ asset('assets/info-light.svg') }}" name="logo">
                    <h2>Other information</h2>
                </div>
                <div>
                    <label for="eligibility-reason">
                        Reason for receiving Rose Vouchers
                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                    </label>
                    <div class="user-control" id="eligibility-reason">
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
                    <div class="user-control">
                        <input type="checkbox" class="styled-checkbox" id="privacy-statement" name="consent" @if( old('consent') ) checked @endif/>
                        <label for="privacy-statement">Have you got the signed privacy statement for the family?</label>
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

        // If enter is pressed, keyboard is hidden on iPad and form submit is disabled
        $('#carer').on('keyup keypress', function(e) {
            if(e.which == 13) {
                e.preventDefault();
                document.activeElement.blur();
                $("input").blur();
                return false;
            }
        });
    </script>

@endsection

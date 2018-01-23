@extends('layouts.service_master')

@section('title', 'Check / Update Registration')

@section('content')

    @include('service.partials.navbar')

    @include('service.partials.flash_notices')

    <div class="content check">
        <div class="row">
            <form action="{{ URL::route("service.registration.update",['id' => $registration->id]) }}" method="post">
                {{ method_field('PUT') }}
                {!! csrf_field() !!}
                <input type="hidden" name="registration" value="{{ $registration->id }}">
                <div class="col">
                    <div>
                        <h2>Main Carer:</h2>
                        <div>
                            <input id="carer" type="text" name="carer" value="{{ $pri_carer->name }}" autocomplete="off" autocorrect="off" spellcheck="false">
                        </div>
                    </div>
                    <div class="other-carers-update">
                        <div class="added">
                            <h2>
                                <label for="carer_adder_input">
                                    Other voucher collectors signed up:
                                </label>
                            </h2>
                        </div>
                        <table id="carer_wrapper">
                            @foreach ($sec_carers as $sec_carer)
                                <tr>
                                    <td><input name="carers[]" type="hidden"
                                               value="{{ $sec_carer->name }}">{{ $sec_carer->name }}</td>
                                    <td>
                                        <button type="button" class="remove_field"><i class="fa fa-minus" aria-hidden="true"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                        <h2>Adding new collectors:</h2>
                        <div id="carer_adder" class="small-button-container">
                            <input id="carer_adder_input" name="carer_adder_input" type="text" autocomplete="off" autocorrect="off" spellcheck="false">
                            <button id="add_collector" class="addButton">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="added">
                        <h2>Children or pregnancy signed up:</h2>
                        <table>
                            <thead>
                            <tr>
                                <td>Age</td>
                                <td>Month/Year</td>
                                <td>Info</td>
                                <td></td>
                            </tr>
                            </thead>
                            <tbody id="existing_wrapper">
                            @foreach ($children as $child)
                                <tr>
                                    <td>{{ $child->getAgeString() }}</td>
                                    <td>{{ $child->getDobAsString() }}</td>
                                    <td>{{ $child->getStatusString() }}</td>
                                    <td>
                                        <input type="hidden" name="children[]"
                                               value="{{ Carbon\Carbon::parse($child->dob)->format('Y-m') }}">
                                        <button class="remove_date_field"><i class="fa fa-minus" aria-hidden="true"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div>
                        <h2>Adding children or pregnancy:</h2>
                        <table>
                            <tbody id="child_wrapper">

                            </tbody>
                        </table>
                        @include('service.partials.add_child_form')
                    </div>
                    <button type="Submit">Save Changes</button>
                </div>
            </form>

            <div class="col collect">
                <p>ARC ID: {{ $family->rvid }}</p>
                <div>
                    <h2>This family may collect <strong>{{ $family->entitlement }}</strong> per week:</h2>
                    <ul>
                        @foreach( $family->getCreditReasons() as $credits )
                            <li>
                                <strong>{{ $credits['reason_vouchers'] }} {{ str_plural('voucher', $credits['reason_vouchers']) }}</strong>
                                as {{ $credits['count'] }} {{ str_plural($credits['entity'], $credits['count']) }}
                                currently "{{ $credits['reason'] }}"
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="warning">
                    @foreach( $family->getNoticeReasons() as $notices )
                        <p><i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                            Warning: {{ $notices['count'] }} {{ str_plural($notices['entity'], $notices['count']) }}
                            currently "{{ $notices['reason'] }}"</p>
                    @endforeach
                </div>
                <div class="print-button">
                    <button onclick="window.open( '{{ URL::route( "service.registration.print", ["id" => $registration->id]) }}' ); return false">
                        Print a 4 week collection sheet for this family
                    </button>
                </div>
                @if ( count($registration->getReminderReasons()) > 0 )
                <div class="attention">
                    <h2>Reminders:</h2>
                    @foreach ( $registration->getReminderReasons() as $reminder )
                        <p><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $reminder['entity'] }} has {{ $reminder['reason'] }}</p>
                    @endforeach
                </div>
                @endif
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
            function () {
                var el = $("#existing_wrapper");
                $(el).on("click", ".remove_date_field", function (e) {
                    e.preventDefault();
                    $(this).closest('tr').remove();
                    return false;
                });
            }
        );

        // If enter is pressed, keyboard is hidden on iPad and form submit is disabled
        $('#carer').on('keyup keypress', function(e) {
            if(e.which === 13) {
                e.preventDefault();
                document.activeElement.blur();
                $("input").blur();
                return false;
            }
        });

    </script>
@endsection

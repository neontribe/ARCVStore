@extends('layouts.service_master')

@section('title', 'Check / Update Registration')

@section('content')

    @include('service.partials.flash_notices')

    @include('service.partials.navbar')

    <div class="content check">
        <div class="row">
            <form action="{{ URL::route("service.registration.edit",['id' => $registration->id]) }}" method="post">
                {{ method_field('PUT') }}
                {!! csrf_field() !!}
                <input type="hidden" name="registration" value="{{ $registration->id }}">
                <div class="col">
                    <div>
                        <h2>CC ID:</h2>
                        <div class="small-button-container">
                            <input type="text" name="cc_reference" disabled value="{{ $registration->cc_reference }}">
                            <button>
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <h2>Main Carer:</h2>
                        <div class="small-button-container">
                            <input type="text" name="carer" disabled value="{{ $pri_carer->name }}">
                            <button>
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                    <div class="other-carers-update edit">
                        <h2>Other Collectors:</h2>
                        <button class="edit-button">
                            <i class="fa fa-pencil" aria-hidden="true"></i>
                        </button>
                        <ul>
                            @foreach ($sec_carers as $sec_carer)
                                <li>{{ $sec_carer->name }}</li>
                            @endforeach
                        </ul>
                        <!-- V-show when edit button is clicked
                        <div class="small-button-container">
                            <input type="text">
                            <button name="add-collector">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </button>
                        </div> -->
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
                            <tbody>
                            @foreach ($children as $child)
                                <tr>
                                    <td>{{ $child->getAgeString() }}</td>
                                    <td>{{ $child->getDobAsString() }}</td>
                                    <td>{{ $child->getStatusString() }}</td>
                                    <td>
                                        <button>-</button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div>
                        <h2><i class="fa fa-user-plus" aria-hidden="true"></i> Add Children <span><i
                                        class="fa fa-info-circle" aria-hidden="true"></i></span></h2>
                        <h3>Month + Year of birth (or due date for pregnancy)</h3>
                        <div class="small-button-container">
                            <input id="month" type="month" min="1998-01">
                            <button name="add-collector">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                    <button type="Submit">Submit</button>
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
                    <button>Print a voucher collection sheet for this family</button>
                </div>
            </div>
        </div>
    </div>
@endsection
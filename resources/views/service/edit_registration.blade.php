@extends('layouts.service_master')

@section('title', 'Check / Update Registration')

@section('content')

    @include('service.partials.flash_notices')

    @include('service.partials.navbar')

    <div class="content check">
        <div class="row">
            <form action="{{ URL::route("service.edit_registration",['id' => $registration->id]) }}" method="post">
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
                        <li><strong>1 voucher</strong> for children under 1 year old</li>
                    </ul>
                </div>
                <div class="warning">
                    <p><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Warning: Next month one child will
                        have a birthday that changes voucher allocation.</p>
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
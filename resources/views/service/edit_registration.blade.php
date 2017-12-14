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
                        <h2><i class="fa fa-user-plus" aria-hidden="true"></i> Add Children <span><i class="fa fa-info-circle" aria-hidden="true"></i></span></h2>
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
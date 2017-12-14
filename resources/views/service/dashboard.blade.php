@extends('layouts.service_master')

@section('title', 'Dashboard')

@section('content')
<div class="content">
    <div class="navigation">
        <ul>
            <li>
                <a href="{{ URL::route('service.registration') }}">
                    <i class="fa fa-plus" aria-hidden="true"></i> Add a new family
                </a>
            </li>
            <li>
                <a href="">
                    <i class="fa fa-user" aria-hidden="true"></i> Check, update or print information for one family
                </a>
            </li>
            <li>
                <a href="">
                    <i class="fa fa-users" aria-hidden="true"></i> Print collection sheets for all families
                </a>
            </li>
        </ul>
    </div>
</div>
@endsection

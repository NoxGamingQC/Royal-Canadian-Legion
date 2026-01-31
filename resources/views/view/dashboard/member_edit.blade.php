@extends('layout.app')
@section('content')

<div class="container">
    <br />
    <div class="card card-body">
        <h1 class="text-primary">Modification du membre: {{ $member->firstname }} {{ $member->lastname }}</h1>
    </div>
    <br />
    <div class="card card-body">
        <form method="POST" class="form-floating" action="/member/update/{{ $member->id }}">
            @csrf
            <div class="row">
                <div class="form-floating col-6">
                    <input type="text" class="form-control" id="member_id" name="member_id" value="{{ $member->member_id }}">
                    <label for="member_id">No de membre</label>
                </div>
                <div class="form-floating input-group col-6">
                    <input type="number" class="form-control" id="last_year_paid" name="last_year_paid" value="{{ $member->last_year_paid }}" required>
                    <span class="text-danger input-group-text" style="font-size:28px"><b>*</b></span>
                    <label for="last_year_paid">Dernière année payée</label>
                </div>
                <div class="col-12"><br /></div>
            </div>
            <div class="row">
                <div class="form-floating input-group col-6">
                    <input type="text" class="form-control" id="firstname" name="firstname" value="{{ $member->firstname }}" required>
                    <span class="text-danger input-group-text" style="font-size:28px"><b>*</b></span>
                    <label for="firstname">Prénom</label>
                </div>
                <div class="form-floating input-group col-6">
                    <input type="text" class="form-control" id="lastname" name="lastname" value="{{ $member->lastname }}" required>
                    <span class="text-danger input-group-text" style="font-size:28px"><b>*</b></span>
                    <label for="lastname">Nom</label>
                </div>
                <div class="col-12"><br /></div>
            </div>
            <div class="row">
                <div class="form-floating input-group col-6">
                    <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ $member->phone_number }}" required>
                    <span class="text-danger input-group-text" style="font-size:28px"><b>*</b></span>
                    <label for="phone_number">Téléphone</label>
                </div>
                <div class="form-floating col-6">
                    <input type="email" class="form-control" id="email_address" name="email_address" value="{{ $member->email_address }}">
                    <label for="email_address">Courriel</label>
                </div>
                <div class="col-12"><br /></div>
            </div>
            <div class="row">
                <div class="offset-11 col-1">
                    <br />
                    <button type="submit" class="btn btn-success form-control"><i class="fa fa-save" aria-hidden="true"></i></button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
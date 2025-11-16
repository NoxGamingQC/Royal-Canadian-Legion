<?php use Carbon\Carbon; ?>

@extends('layout.app')
@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <br />
            <h1>Liste des transactions</h1>
            <hr />
            <br />
        </div>
        <div class="col-md-12">
            <h3>Filtres</h3>
            
            <div class="col-md-12">
                <div class="input-daterange input-group" id="datepicker">
                    <button class="btn btn-primary" type="button" id="generateReport">Rapport</button>
                    <input type="text" class="form-control" id="firstDay" name="start" placeholder="YYYY-MM-DD" value="{{isset($firstDay) ? $firstDay->format('Y-m-d') : ''}}" />
                    <span class="input-group-text">à</span>
                    <input type="text" class="form-control" id="secondDay" name="end" placeholder="YYYY-MM-DD" value="{{isset($secondDay) ? $secondDay->format('Y-m-d') : ''}}"/>
                    <button class="btn btn-success" type="button" id="searchDate"><i class="fa fa-search"></i></button>
                </div>
                <hr />
                <h3>Total: {{Number_format($transactionsTotalCount, 2)}} $</h4>
                <hr />

            </div>
            <br />
        </div>
        <table class="table">
            <tr class="text-center">
                <th class="text-center">Client</th>
                <th class="text-center">Quantité</th>
                <th class="text-center">Catégorie</th>
                <th class="text-center">Article</th>
                <th class="text-center">Prix</th>
                <th class="text-center">Date</th>
                <th class="text-center">Caissier</th>
                <th class="text-center">Type de payment</th>
                <th class="text-center">Annuler la transaction</th>

            </tr>
            @foreach($transactions as $key => $transaction)
                <tr class="text-center {{$transaction->is_cancel_validated ? 'table-danger' : ''}} {{$transaction->is_canceled ? 'table-warning' : ''}} {!! Carbon::parse($transaction->created_at, 'America/Toronto')->format('Y-m-d') == Carbon::today('America/Toronto')->format('Y-m-d') ? 'table-info' : '' !!} {!! Carbon::parse($transaction->created_at, 'America/Toronto')->format('Y-m-d') == Carbon::yesterday('America/Toronto')->format('Y-m-d') ? 'table-success' : '' !!}">
                    <td>{{$transaction->customer_id ? App\Models\Customer::where('id', $transaction->customer_id)->first()->firstname . ' ' .  App\Models\Customer::where('id', $transaction->customer_id)->first()->lastname : 'N/A'}}</td>
                    <td>{{$transaction->quantity}}</td>
                    <td>{{$transaction->getCategoryName()}}</td>
                    <td>{{$transaction->getItemName()}}</td>
                    <td style="{{$transaction->is_promotion ? 'text-decoration: line-through;text-decoration-color: red;' : ''}}">{{Number_format($transaction->price, 2)}} $</td>
                    <td>{!! Carbon::parse($transaction->created_at, 'America/Toronto') !!}</td>
                    <td>{{$transaction->getCashier()}}</td>
                    <td>{{$transaction->is_promotion ? 'N/A' : ($transaction->payment_type === 'cash' ? 'Argent' : 'Carte')}}</td>
                    <td><a class="btn btn-danger  {{$transaction->is_cancel_validated ? 'hidden disabled' : ''}}" {{$transaction->is_cancel_validated ? 'hidden disabled' : ''}}><i class="fa fa-remove"></i></a></td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
<script type="text/javascript">
$('.input-daterange').datepicker({
    format: "yyyy-mm-dd",
    maxViewMode: 0,
    todayBtn: true,
    clearBtn: true,
    language: "fr",
    autoclose: true,
    todayHighlight: true
});

$('#searchDate').on('click', function() {
    if($('.input-daterange').data().datepicker.dates[0]) {
        window.location.href = "/{{Auth::user()->getUserCommand() . '-' . Auth::user()->getUserBranch()}}/transactions/" + moment($('.input-daterange').data().datepicker.dates[0]).utcOffset(5).format('YYYY-MM-DD') + '/' + moment($('.input-daterange').data().datepicker.dates[1]).utcOffset(5).format('YYYY-MM-DD');
    }
})

$('#generateReport').on('click', function() {
    if($('.input-daterange').data().datepicker.dates[0]) {
        window.location.href = "/{{Auth::user()->getUserCommand() . '-' . Auth::user()->getUserBranch()}}/reports/" + moment($('.input-daterange').data().datepicker.dates[0]).utcOffset(5).format('YYYY-MM-DD') + '/' + moment($('.input-daterange').data().datepicker.dates[1]).utcOffset(5).format('YYYY-MM-DD');
    }
})

</script>
@endsection
<?php 
    use Carbon\Carbon;
    use App\Models\Transaction;
    use App\Models\Branches;
?>
@extends('layout.app')
@section('content')
<style>
@page {
    @top-center { 
        content: '{{Branches::where("command", Auth::user()->getUserCommand())->where("branch_id", Auth::user()->getUserBranch())->first()->name}} - Rapport de ventes'; 
    }
    @bottom-left { 
        content: 'Généré par Services Tech. J.Bédard'
    }
    @bottom-right { 
        content: counter(page)  '/' counter(pages)
    }
}
</style>
<div class="container-fluid">
    <br />
    <h3><img src="/images/logo.png" width="75px"> <span>Ventes du {{$firstDay->format('Y-m-d')}} au {{$secondDay->format('Y-m-d')}}</span></h3>
    <br />
    <br />
    <div class="row">
        @foreach($transaction_categories as $category)
            @if(Transaction::whereBetween('created_at', [Carbon::parse($firstDay)->format('Y-m-d')." 04:00:00", Carbon::parse($secondDay)->addDays(1)->format('Y-m-d') ." 03:59:59"])->quantityByCategory($category->id) > 0)
                <div class="col-sm-6">
                    <h4>{{$category->fullname}}: {{Number_format(Transaction::whereBetween('created_at', [Carbon::parse($firstDay)->format('Y-m-d')." 04:00:00", Carbon::parse($secondDay)->addDays(1)->format('Y-m-d') ." 03:59:59"])->countByCategory($category->id),2)}}$ ({{Number_format(Transaction::whereBetween('created_at', [Carbon::parse($firstDay)->format('Y-m-d')." 04:00:00", Carbon::parse($secondDay)->addDays(1)->format('Y-m-d') ." 03:59:59"])->countByCategory($category->id, true),2)}}$)</h4>
                    <hr />
                    @if(count($category->getVariations()) > 0)
                        @foreach($category->getVariations() as $item)
                            @if(Transaction::whereBetween('created_at', [Carbon::parse($firstDay)->format('Y-m-d')." 04:00:00", Carbon::parse($secondDay)->addDays(1)->format('Y-m-d') ." 03:59:59"])->quantityByItem($item->id) > 0)
                            <b>{{Transaction::whereBetween('created_at', [Carbon::parse($firstDay)->format('Y-m-d')." 04:00:00", Carbon::parse($secondDay)->addDays(1)->format('Y-m-d') ." 03:59:59"])->quantityByItem($item->id)}} x {{$item->name}}:&nbsp</b>{{Number_format(Transaction::whereBetween('created_at', [Carbon::parse($firstDay)->format('Y-m-d')." 04:00:00", Carbon::parse($secondDay)->addDays(1)->format('Y-m-d') ." 03:59:59"])->countByItem($item->id),2)}}$ ({{Number_format(Transaction::whereBetween('created_at', [Carbon::parse($firstDay)->format('Y-m-d')." 04:00:00", Carbon::parse($secondDay)->addDays(1)->format('Y-m-d') ." 03:59:59"])->countByItem($item->id, true),2)}}$)
                            <br />
                            @endif
                        @endforeach
                    @else
                        @if(Transaction::whereBetween('created_at', [Carbon::parse($firstDay)->format('Y-m-d')." 04:00:00", Carbon::parse($secondDay)->addDays(1)->format('Y-m-d') ." 03:59:59"])->quantityByCategory($category->id) > 0)
                            <b>{{Transaction::whereBetween('created_at', [Carbon::parse($firstDay)->format('Y-m-d')." 04:00:00", Carbon::parse($secondDay)->addDays(1)->format('Y-m-d') ." 03:59:59"])->quantityByCategory($category->id)}} x Article de base:&nbsp</b>{{Number_format(Transaction::whereBetween('created_at', [Carbon::parse($firstDay)->format('Y-m-d')." 04:00:00", Carbon::parse($secondDay)->addDays(1)->format('Y-m-d') ." 03:59:59"])->countByCategory($category->id),2)}}$ ({{Number_format(Transaction::whereBetween('created_at', [Carbon::parse($firstDay)->format('Y-m-d')." 04:00:00", Carbon::parse($secondDay)->addDays(1)->format('Y-m-d') ." 03:59:59"])->countByCategory($category->id, true),2)}}$)
                            <br />
                        @endif
                    @endif
                    <br />
                </div>
            @endif
        @endforeach
    </div>
    <hr />
    <br />
    <h4><b>Grand total: {{Number_format($transactionsTotalCount,2)}}$ ({{Number_format($promotionTotalCount,2)}}$)</b></h4>
    <br class="no-print" />
    <p class="no-print"><i>N.B. Les montant des articles passé en promotion sont entre parenthèse.</i></p>
    <br class="no-print" />
</div>
@endsection
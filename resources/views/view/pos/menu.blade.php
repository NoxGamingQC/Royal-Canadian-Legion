@extends('layout.pos')
@section('content')
<?php
    use App\Models\Branches;

    $fullBranchID = explode('/', $_SERVER['REQUEST_URI'])[1];
    $branchCommand = explode('-', explode('/', $_SERVER['REQUEST_URI'])[1])[0];
    $branchNumber = explode('-', explode('/', $_SERVER['REQUEST_URI'])[1])[1];
    $branch = Branches::where('command', $branchCommand)->where('branch_id', $branchNumber)->first();
    $name = $branch->name;
    $phone_number = $branch->phone;
    $address = nl2br($branch->address);
    $logo = $branch->logo;
?>

<div style="position:absolute;margin:20vh;margin-left:30vh;z-index:99">
    <h1 id="amount" class="text-success" value="0"></h1>
</div>
<div style="position:absolute;margin:20vh;margin-left:95vh;z-index:99;">
    <h2 id="givenAmount" value="" style="width:50vh"></h2>
</div>
<div class="row" style="margin:0px;padding:0px;">
    <div class="col-12 text-center" style="min-height:49vh;max-height:49vh;overflow:hidden;margin:0px;padding:0px;">
        <div class="row">
            <div class="col-12" style="background-color:#E51937;height:3vh;color:#FFF;border: 1px solid black">
                <div class="container">
                    <div class="row">
                        <div class="col-3 text-start">
                            {{ $cashierName }}
                        </div>
                        <div class="col-6 text-center">
                            {{$branch->name}} - {{$branch->phone}}
                        </div>
                        <div id="date-time" class="col-3 text-end">
                            {{date('Y-m-d H:i:s')}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-7" style="min-height:49vh;overflow:hidden;margin:0px;padding:0px">
                <div class="row">
                    <div class="col-12">
                        <h4 id="customerId" value=""></h4>
                        <input id="invoiceID" type="hidden" value="">
                    </div>
                    @if($invoices)
                        @foreach($invoices as $invoice)
                            <div class="col-4" style="{{Carbon\Carbon::create($invoice->created_at)->addWeeks(1)->lessThan(Carbon\Carbon::create()) ? 'background:#c41d1d;color:#FFF !important;' : 'color:#000 !important;'}}margin:0px !important;padding:0px !important;border: 1px solid black">
                                <a id="{{$invoice->id}}" customer-id="{{$invoice->customer_id}}" name="{{$invoice->getCustomerFullname()}}" class="invoices-list btn btn-lg" style="min-height:12vh;max-height:12vh;height:100%;width:100%; margin:0px !important;padding:0px !important;overflow:hidden;border-radius:0px;">
                                    <b class="h5"><li style="{{Carbon\Carbon::create($invoice->created_at)->addWeeks(1)->lessThan(Carbon\Carbon::create()) ? 'background:#c41d1d;color:#FFF !important;' : 'color:#000 !important;'}}list-style-type: none;overflow:hidden;padding:2px;border-radius: 5px;opacity: 0.85;">{{$invoice->getCustomerFirstName()}}</li></b>
                                    <b class="h5"><li style="{{Carbon\Carbon::create($invoice->created_at)->addWeeks(1)->lessThan(Carbon\Carbon::create()) ? 'background:#c41d1d;color:#FFF !important;' : 'color:#000 !important;'}}list-style-type: none;overflow:hidden;padding:2px;border-radius: 5px;opacity: 0.85;">{{$invoice->getCustomerLastName()}}</li></b>
                                    <b class="h5"><li style="color:#000;list-style-type: none;overflow:hidden;padding:2px;border-radius: 5px;opacity: 0.85;">{{$invoice->getTotalPrice()}}$<br /></li></b>
                                </a>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="col-5">
                <div id="shoppingCart" class="col-12" style="min-height:42vh;max-height:42vh;background:#F8F8F8;padding:0px;overflow:hidden !important;">
                </div>
                <div class="col-12 text-start" style="min-height:3vh;">
                    <div class="row">
                        <div class="col-6 text-start">
                            <h4><b>Total</b></h4>
                        </div>
                        <div class="col-6 text-end">
                        <div class="col-4">
                        </div>
                            <h4 class="text-danger text-end"><b id="totalPrice" value="">0,00 $</b></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12" style="min-height:48vh;max-height:48vh;overflow:hidden;margin:0px;padding:0px">
        <div class="row">
            <div id="items" class="col-8 text-center" style="overflow:hidden;margin:0px;padding:0px;">
                <div class="row">
                    <div class="col-2" style="margin:0px !important;padding:0px !important;border: 0.5px solid black">
                        <a class="btn btn-lg" href="/{{$fullBranchID}}/pos?token={{$token}}" style="min-height:12vh;max-height:12vh;height:100%;width:100%; margin:0px !important;padding:0px !important;overflow:hidden;border-radius:0px;">
                            <li style="margin-top:3vh;list-style-type: none;overflow:hidden;padding-top:0px !important;padding:2px;color: #f00;border-radius: 5px;opacity: 0.85;">Fermer<br />session</li>
                        </a>
                    </div>                        
                    @foreach($catalog as $item)
                        <div class="col-2" style="margin:0px !important;padding:0px !important;border: 0.5px solid black;">
                            <a id="{{$item->id}}" {{$item->getQuantity() == 0 ? ('price=' . $item->price. ' name=' . $item->name) : ''}} class="{{$item->getQuantity() == 0 ? 'items' : ''}} btn btn-lg" data-bs-toggle="modal" data-bs-target="#{{$item->name}}Modal" style="min-height:12vh;height:100%;width:100%; margin:0px !important;padding:0px !important;min-height:12vh;max-height:12vh;background-image: url({{$item->image}}); background-color: #ffffff;background-size: cover;background-repeat: no-repeat;background-position: center; border: none;border-radius:0px;">
                                @if(!is_null($item->inventory) && $item->inventory == 0)
                                    <span class="text-danger" style="z-index:99;position:absolute;margin:-25px;margin-top:-12px;padding:0px"><h1 style="font-size: 70px;color:#F00;text-shadow:1px 1px 0 #000, -1px 1px 0 #000, -1px -1px 0 #000, 1px -1px 0 #000;">X</h1></span>
                                @elseif(!is_null($item->inventory) && $item->inventory <= $item->alert_threshold)
                                    <span class="text-warning" style="z-index:99;position:absolute;margin:-12px;margin-top:-12px;padding:0px"><h1 style="font-size: 70px;color:#FF0;text-shadow:1px 1px 0 #000, -1px 1px 0 #000, -1px -1px 0 #000, 1px -1px 0 #000;">!</h1></span>
                                @endif
                                <li style="font-weight: bold;padding-top:50px;list-style-type:none;overflow:hidden;padding-top:4vh;{{$item->image ? 'color: #FFF; text-shadow: -2px 0 #000, 0 2px #000, 2px 0 #000, 0 -2px #000;' : 'color:#000;'}}">{{$item->name}}</li>
                                @if($item->getQuantity() == 0)
                                    <span style="margin-top:2px;padding:2px;{{$item->image ? 'color: #FFF; text-shadow: -2px 0 #000, 0 2px #000, 2px 0 #000, 0 -2px #000;' : 'color:#000;'}}border-radius: 5px;opacity: 0.85;">{{$item->price}} $</span>
                                @endif
                            </a>
                        </div>
                        @if($item->getQuantity() > 0)
                        <!-- Modal start-->
                            <div id="{{$item->name}}Modal" class="modal fade" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-fullscreen modal-dialog modal-fullscreen-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">{{$item->name}}</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                @foreach($item->getVariations() as $variation)
                                                    <div class="col-2" style="margin:0px !important;padding:0px !important;border: 1px solid black">
                                                        <a id="{{$item->id}};{{$variation->id}}" name="{{$variation->name}}" price="{{$variation->price}}" class="items btn btn-lg" data-bs-dismiss="modal" style="min-height:12vh;height:100%;width:100%; margin:0px !important;padding:0px !important;min-height:20vh;max-height:20vh;background-image: url({{$variation->image}}); background-color: #ffffff;background-size: cover;background-repeat: no-repeat;background-position: center;">
                                                            @if(!is_null($variation->inventory) && $variation->inventory == 0)
                                                                <span class="text-danger" style="z-index:99;position:absolute;margin:-25px;margin-top:10px;padding:0px"><h1 style="font-size: 70px;color:#F00;text-shadow:1px 1px 0 #000, -1px 1px 0 #000, -1px -1px 0 #000, 1px -1px 0 #000;">X</h1></span>
                                                            @elseif(!is_null($variation->inventory) && $variation->inventory <= $variation->alert_threshold)
                                                                <span class="text-warning" style="z-index:99;position:absolute;margin:-12px;margin-top:12px;padding:0px"><h1 style="font-size: 70px;color:#FF0;text-shadow:1px 1px 0 #000, -1px 1px 0 #000, -1px -1px 0 #000, 1px -1px 0 #000;">!</h1></span>
                                                            @endif
                                                            <li style="font-weight: bold;;margin:8vh;margin-left:2vh !important;margin-bottom:2px;list-style-type: none;{{ $variation->image ? 'background-color:#000;color:#FFF;': 'color: #000;'}}border-radius: 5px;opacity: 0.85;">{{$variation->name}}</li>
                                                            <span style="margin-top:2px;padding:2px;{{ $variation->image ? 'background-color:#000;color:#FFF;': 'color: #000;'}}border-radius: 5px;opacity: 0.85;">{{$variation->price}} $</span>
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- Modal end-->
                        @endif
                    @endforeach
                    <!-- Button trouver client -->
                    <div class="col-2" style="margin:0px !important;padding:0px !important;border: 0.5px solid black;">
                        <a id="" class="btn btn-lg" data-bs-toggle="modal" data-bs-target="#customerModal" style="min-height:12vh;height:100%;width:100%; margin:0px !important;padding:0px !important;min-height:12vh;max-height:12vh;">
                            <li style="font-weight: bold;padding-top:50px;list-style-type:none;overflow:hidden;padding-top:3vh;color: #000;">Trouver<br />client</li>
                        </a>
                    </div>
                    <!-- Modal start-->
                            <div id="customerModal" class="modal fade" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-fullscreen modal-dialog modal-fullscreen-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">Assigner client</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-2" style="border:0.5px solid black;margin;0px;padding:0px;">
                                                    <a class="customer btn btn-lg" style="color:red;min-height:75px !important;max-height:75px !important; height:100%;width:100%;" data-bs-dismiss="modal" value="remove">
                                                        <b>Enlever client</b>
                                                    </a>
                                                </div>
                                                @foreach($customers as $customer)
                                                    <div class="col-2" style="border:0.5px solid black;margin;0px;padding:0px;">
                                                        <a class="customer btn btn-lg" style="color:black;min-height:75px !important;max-height:75px !important; height:100%;width:100%;" data-bs-dismiss="modal" value="{{$customer->id}}" name="{{$customer->firstname}} {{$customer->lastname}}">
                                                            <b>{{$customer->firstname}}<br />{{$customer->lastname}}</b>
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- Modal end-->
                            @if($hasKitshopAccess)
                                <div class="col-2" style="margin:0px !important;padding:0px !important;border: 0.5px solid black;">
                                    <a id="kitshop" class="kitshop variable-price btn btn-lg disabled" style="min-height:12vh;height:100%;width:100%; margin:0px !important;padding:0px !important;min-height:12vh;max-height:12vh;background-image: url(); background-color: #ffffff;background-size: cover;background-repeat: no-repeat;background-position: center; border: none" disabled>
                                        <span class="text-danger" style="z-index:99;position:absolute;margin:-25px;margin-top:5px;padding:0px"><h1 style="font-size: 70px;color:#F00;text-shadow:1px 1px 0 #000, -1px 1px 0 #000, -1px -1px 0 #000, 1px -1px 0 #000;">X</h1></span>
                                        <li style="font-weight: bold;padding-top:50px;list-style-type:none;overflow:hidden;padding-top:4vh;{{null /*image*/? 'color: #FFF; text-shadow: -2px 0 #000, 0 2px #000, 2px 0 #000, 0 -2px #000;' : 'color:#000;'}}">Kitshop</li>
                                        <span style="margin-top:2px;padding:2px;{{null /*image*/ ? 'color: #FFF; text-shadow: -2px 0 #000, 0 2px #000, 2px 0 #000, 0 -2px #000;' : 'color:#000;'}}border-radius: 5px;opacity: 0.85;">Variable</span>
                                    </a>
                                </div>
                            @endif
                            <!-- Fill out the rest of the blank square with empty button -->
                            @for($i = 0; $i < 24; $i++)
                                <div class="col-2" style="margin:0px !important;padding:0px !important;border: 0.5px solid black">
                                    <a class="btn btn-lg disabled" style="min-height:12vh;height:100%;width:100%; margin:0px !important;padding:0px !important; border: none">
                                        
                                    </a>
                                </div>
                            @endfor
                        </div>
                    </div>
            <div id="numpad" class="col-3 text-center" style="min-height:49vh;max-height:49vh;overflow:hidden;margin:0px;padding:0px">
                <div class="row" style="margin:0px;padding:0px">
                    <div class="col-4" style="margin:0px !important;padding:0px !important;border: 1px solid black;">
                        <a class="numpad btn btn-lg btn-default" style="min-height:12vh;height:100%;width:100%; margin:0px !important;padding:4vh;height:12vh;" value="7">
                            7
                        </a>
                    </div>
                    <div class="col-4" style="margin:0px !important;padding:0px !important;border: 1px solid black">
                        <a class="numpad btn btn-lg btn-default" style="min-height:12vh;height:100%;width:100%; margin:0px !important;padding:4vh;height:12vh;" value="8">
                            8
                        </a>
                    </div>
                    <div class="col-4" style="margin:0px !important;padding:0px !important;border: 1px solid black">
                        <a class="numpad btn btn-lg btn-default" style="min-height:12vh;height:100%;width:100%; margin:0px !important;padding:4vh;height:12vh;" value="9">
                            9
                        </a>
                    </div>
                    <div class="col-4" style="margin:0px !important;padding:0px !important;border: 1px solid black">
                        <a class="numpad btn btn-lg btn-default" style="min-height:12vh;height:100%;width:100%; margin:0px !important;padding:4vh;height:12vh;" value="4">
                            4
                        </a>
                    </div>
                    <div class="col-4" style="margin:0px !important;padding:0px !important;border: 1px solid black">
                        <a class="numpad btn btn-lg btn-default" style="min-height:12vh;height:100%;width:100%; margin:0px !important;padding:4vh;height:12vh;" value="5">
                            5
                        </a>
                    </div>
                    <div class="col-4" style="margin:0px !important;padding:0px !important;border: 1px solid black">
                        <a class="numpad btn btn-lg btn-default" style="min-height:12vh;height:100%;width:100%; margin:0px !important;padding:4vh;height:12vh;" value="6">
                            6
                        </a>
                    </div>
                    <div class="col-4" style="margin:0px !important;padding:0px !important;border: 1px solid black">
                        <a class="numpad btn btn-lg btn-default" style="min-height:12vh;height:100%;width:100%; margin:0px !important;padding:4vh;height:12vh;" value="1">
                            1
                        </a>
                    </div>
                    <div class="col-4" style="margin:0px !important;padding:0px !important;border: 1px solid black">
                        <a class="numpad btn btn-lg btn-default" style="min-height:12vh;height:100%;width:100%; margin:0px !important;padding:4vh;height:12vh;" value="2">
                            2
                        </a>
                    </div>
                    <div class="col-4" style="margin:0px !important;padding:0px !important;border: 1px solid black">
                        <a class="numpad btn btn-lg btn-default" style="min-height:12vh;height:100%;width:100%; margin:0px !important;padding:4vh;height:12vh;" value="3">
                            3
                        </a>
                    </div>
                    <div class="col-8" style="margin:0px !important;padding:0px !important;border: 1px solid black">
                        <a class="numpad btn btn-lg btn-default" style="min-height:12vh;height:100%;width:100%; margin:0px !important;padding:4vh;height:12vh;" value="0">
                            0
                        </a>
                    </div>
                    <div class="col-4" style="margin:0px !important;padding:0px !important;border: 1px solid black">
                        <a class="numpad-backspace btn btn-lg btn-default" style="min-height:12vh;height:100%;width:100%; margin:0px !important;padding:4vh;height:12vh;">
                            DEL
                        </a>
                    </div>
                </div>
            </div>
        <div id="total-menu" class="col-1 text-center" style="min-height:49vh;max-height:49vh;overflow:hidden;margin:0px;padding:0px;writing-mode: vertical-rl;">
            <div class="row">
                <div class="col-12" style="margin:0px !important;padding:0px !important;border: 1px solid black">
                        <a id="total" class="btn btn-lg btn-default" style="min-height:16vh;max-height:12vh;height:100%;width:100%;padding-left:5vh;padding-right:5vh">
                            Total
                        </a>
                    </div>
                    <div class="col-12" style="margin:0px !important;padding:0px !important;border: 1px solid black">
                        <a id="createInvoice" class="btn btn-lg btn-default" style="min-height:16vh;max-height:12vh;height:100%;width:100%; margin:0px !important;padding-left:5vh;padding-right:5vh">
                            Facture 
                        </a>
                    </div>
                    <div class="col-12" style="margin:0px !important;padding:0px !important;border: 1px solid black">
                        <a id="promotion" class="btn btn-lg btn-default" style="min-height:16vh;max-height:12vh;height:100%;width:100%; margin:0px !important;padding-left:5vh;padding-right:5vh">
                            Promotion
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 text-center" style="background-color:#E51937;height:3vh;color:#FFF;border:1px solid black;">
        Créé et maintenu par Service Technologique J.Bédard - 819-852-8705
    </div>
</div>
<script>
$(document).ready(function() {
    setInterval(function() {
        var currentDate = new Date();
        var formattedDate = currentDate.getFullYear() + '-' +
            String(currentDate.getMonth() + 1).padStart(2, '0') + '-' +
            String(currentDate.getDate()).padStart(2, '0') + ' ' +
            String(currentDate.getHours()).padStart(2, '0') + ':' +
            String(currentDate.getMinutes()).padStart(2, '0') + ':' +
            String(currentDate.getSeconds()).padStart(2, '0');
        $('#date-time').text(formattedDate);
    }, 1000);

    $('.items').on('click', function(){
        $('#givenAmount').html('');
        $('#givenAmount').attr('value', '');
        var realAmount = $('#amount').attr('value');
        var amount = (Number($('#amount').attr('value')) == 0) ? '1' : $('#amount').attr('value')
        $('#amount').html('');
        $('#amount').attr('value', '')
        var total = 0;
        var html = $('#shoppingCart').html();
        if($(this).hasClass('variable-price')) {
            console.log(true)
            html += '<a class="cart-item btn btn-lg" style="width:100%;border:1px solid #CCC; min-height:3vh;max-height:5vh;border-radius:5px;padding:0px;color:#000;">'+
                    '<div class="row">'+
                        '<div class="col-6 text-start">'+
                            '<h5><b>1 x ' + $(this).attr('name') + '</b></h5>'+
                        '</div>'+
                        '<div class="col-6 text-end">'+
                            '<h5><b class="item-price" value="' + Number(realAmount.slice(0, realAmount.length-2) + '.' + realAmount.slice(realAmount.length -2, realAmount.length)) + '">' + Number(realAmount.slice(0, realAmount.length-2) + '.' + realAmount.slice(realAmount.length -2, realAmount.length)).toLocaleString('fr-CA', { style: 'currency', currency: 'CAD'}) + '</b></h5>'+
                        '</div>'+
                    '</div>'+
                '</a>';
        } else {
            html += '<a class="cart-item btn btn-lg" category="' + $(this).attr('id').split(';')[0] + '" item="' + $(this).attr('id').split(';')[1] + '" quantity="' + Number(amount) + '" price="' + Number($(this).attr('price')) + '" style="width:100%;border:1px solid #CCC; min-height:3vh;max-height:5vh;border-radius:5px;padding:0px;color:#000;">'+
                        '<div class="row">'+
                            '<div class="col-6 text-start">'+
                                '<h5><b>' + Number(amount) + ' x ' + $(this).attr('name') + '</b></h5>'+
                            '</div>'+
                            '<div class="col-6 text-end">'+
                                '<h5><b class="item-price" value="' + Number($(this).attr('price')) * Number(amount) + '">' + (Number($(this).attr('price')) * Number(amount)).toLocaleString('fr-CA', { style: 'currency', currency: 'CAD'}) + '</b></h5>'+
                            '</div>'+
                        '</div>'+
                    '</a>';
        }
        $('#shoppingCart').html(html);
        $('.item-price').each(function(key, item) {
            total += Number(item.getAttribute('value'));
        });
        $('#totalPrice').attr('value',total);
        $('#totalPrice').html(total.toLocaleString('fr-CA', { style: 'currency', currency: 'CAD'}));

        $('.cart-item').on('click', function() {
            var total = 0;
            $(this).remove();
            $('.item-price').each(function(key, item) {
                total += Number(item.getAttribute('value'));
            });
            $('#totalPrice').attr('value', total);
            $('#totalPrice').html(total.toLocaleString('fr-CA', { style: 'currency', currency: 'CAD'}));
        }); 
    });

    $('.numpad').on('click', function() {
        var html = "";
        var oldValue = $('#amount').attr('value');
        html = oldValue + $(this).attr('value')
        $('#amount').attr('value', oldValue + $(this).attr('value'));
        $('#amount').html(Number(html.slice(0, html.length-2) + '.' + html.slice(html.length -2, html.length)).toLocaleString('fr-CA', { style: 'currency', currency: 'CAD'}));
    }); 
    $('.numpad-backspace').on('click', function() {
        var value = 0;
        value = $('#amount').attr('value').substring(0, $('#amount').attr('value').length -1);
        $('#amount').attr('value', Number(value));
        if(Number(value) == 0) {
            $('#amount').html('')
        } else {
            $('#amount').html(Number(value.slice(0, value.length-2) + '.' + value.slice(value.length -2, value.length)).toLocaleString('fr-CA', { style: 'currency', currency: 'CAD'}));
        }
    }); 
    $('#total').on('click', function() {
        var value = 0;
        var givenBack = 0;
        var exactPrice = 0;
        value = $('#amount').attr('value')
        givenBack = (Number(value.slice(0, value.length-2) + '.' + value.slice(value.length -2, value.length)) - Number($('#totalPrice').attr('value')));
        if (isNaN(givenBack)) {
            registerPayment(false);
            $('#givenAmount').html('Remise: ' + exactPrice.toLocaleString('fr-CA', { style: 'currency', currency: 'CAD'}));
            $('#givenAmount').addClass('text-success');
            $('#givenAmount').removeClass('text-danger');
            $('#amount').attr('value', '0');
            $('#amount').html('');
            $('#totalPrice').attr('value', '0');
            $('#totalPrice').html(exactPrice.toLocaleString('fr-CA', { style: 'currency', currency: 'CAD'}));
            $('.cart-item').each(function() {
                $(this).remove();
            })
        }else if(givenBack < 0) {
            $('#givenAmount').html('Remise invalide');
            $('#givenAmount').addClass('text-danger');
            $('#givenAmount').removeClass('text-success');
            $('#amount').attr('value', '0');
            $('#amount').html('');
        } else {
            registerPayment(false);
            $('#givenAmount').html('Remise: ' + givenBack.toLocaleString('fr-CA', { style: 'currency', currency: 'CAD'}));
            $('#givenAmount').addClass('text-success');
            $('#givenAmount').removeClass('text-danger');
            $('#amount').attr('value', '0');
            $('#amount').html('');
            $('#totalPrice').attr('value', '0');
            $('#totalPrice').html(exactPrice.toLocaleString('fr-CA', { style: 'currency', currency: 'CAD'}));
            $('.cart-item').each(function() {
                $(this).remove();
            })
        }
    });

    $('#createInvoice').on('click', function() {
        if($('#customerId').attr('value') === '') {
            $('#givenAmount').html('Client obligatoire pour facture');
            $('#givenAmount').addClass('text-danger');
            $('#givenAmount').removeClass('text-success');
        } else {
            var cartItems = [];
            var customerID = $('#customerId').attr('value');
            var invoiceID = $('#invoiceID').attr('value');
            $('.cart-item').each(function(key, item) {
                cartItems.push({
                    'category_id': $(this).attr('category'),
                    'item_id': $(this).attr('item'),
                    'price' : $(this).attr('price'),
                    'quantity': $(this).attr('quantity'),
                });
            });
            $.ajax({
                url: "/{{$fullBranchID}}/pos/invoice/edit?token={{$token}}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'customer_id': customerID,
                    'items': cartItems,
                    'cashier_id': {{$cashier_id}},
                    'is_promotion' : false,
                    'invoice_id': invoiceID,
                    'menu': 'menu'
                },
                success: function (result) {
                    $('.cart-item').each(function(key, item) {
                        $('.cart-item').each(function() {
                            $(this).remove();
                        })
                    });
                    console.log('success');
                },
                error: function (error) {
                    console.log(error);
                },
                complete: function() {
                    window.location.reload();
                }
            });
        }
    });


    $('#promotion').on('click', function() {
        var value = 0;
        var givenBack = 0;
        var exactPrice = 0;
        registerPayment(true);
        $('#givenAmount').html('Remise: ' + givenBack.toLocaleString('fr-CA', { style: 'currency', currency: 'CAD'}));
        $('#givenAmount').addClass('text-success');
        $('#givenAmount').removeClass('text-danger');
        $('#amount').attr('value', '0');
        $('#amount').html('');
        $('#totalPrice').attr('value', '0');
        $('#totalPrice').html(exactPrice.toLocaleString('fr-CA', { style: 'currency', currency: 'CAD'}));
        $('.cart-item').each(function() {
            $(this).remove();
        })
    });

    $('.physical-count').each(function() {
        var item = $(this);
        $.ajax({
            url: "/{{$fullBranchID}}/pos/getInventory/" + $(this).attr('id') + "?token={{$token}}",
            type: "GET",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (result) {
                if(Number(result) == 0) {
                    item.html('<h1 style="font-size: 70px;color:#f00;text-shadow:1px 1px 0 #000, -1px 1px 0 #000, -1px -1px 0 #000, 1px -1px 0 #000;margin-top:5px;">X</h1>');
                } else if(Number(result) <= Number(item.attr('warning'))) {
                    item.html('<h1 style="font-size: 70px;color:#fF0;text-shadow:1px 1px 0 #000, -1px 1px 0 #000, -1px -1px 0 #000, 1px -1px 0 #000;margin-top:5px;">!</h1>');
                }
            }
        })
    });

    function registerPayment(isPromotion) {
        var cartItems = [];
        var customerID = $('#customerId').attr('value');
        var invoiceID = $('#invoiceID').attr('value');
        $('.cart-item').each(function(key, item) {
            cartItems.push({
                'category_id': $(this).attr('category'),
                'item_id': $(this).attr('item'),
                'price' : $(this).attr('price'),
                'quantity': $(this).attr('quantity'),
            });
        });
        $.ajax({
            url: "/{{$fullBranchID}}/pos/pay?token={{$token}}",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                'invoice_id': invoiceID,
                'items': cartItems,
                'cashier_id': {{$cashier_id}},
                'is_promotion' : isPromotion ? true : null,
                'menu': 'menu',
                'customer_id': $('#customerId').attr('value'),
            },
            success: function (result) {
                $('.cart-item').each(function(key, item) {
                    adjustInventory(item);
                });
                console.log('success');
            },
            error: function (error) {
                console.log(error);
            },
            complete: function() {
                window.location.reload();
            }
        });
    }

    function adjustInventory(item) {
        $.ajax({
            url: "/{{$fullBranchID}}/pos/inventory?token={{$token}}",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                'category_id': $(item).attr('category'),
                'item_id': $(item).attr('item'),
                'quantity': $(item).attr('quantity'),

            },
            success: function (result) {
                console.log('success');
            },
            error: function (error) {
                console.log(error);
            }
        })
    }

    $('.customer').on('click', function() {
        if($(this).attr('value') === 'remove') {
            $('#customerId').attr('value', '');
            $('#customerId').html('')
        } else {
        $('#customerId').attr('value', $(this).attr('value'));
        $('#customerId').html($(this).attr('name'))
        }
    });

    $('.invoices-list').on('click', function() {
        var newInvoiceID = $(this).attr('id');
        if($('#invoiceID').attr('value') == newInvoiceID) {
            $('#invoiceID').attr('value', '');
            $('#customerId').attr('value', '');
            $('#customerId').html('');
            $('.cart-item').each(function() {
                $(this).remove();
                var total = 0;
                $('#totalPrice').attr('value', total);
                $('#totalPrice').html(total.toLocaleString('fr-CA', { style: 'currency', currency: 'CAD'}));
            });
        } else {
            var customerName = $(this).attr('name');
            var customerID = $(this).attr('customer-id');
            var invoiceID = $(this).attr('id');
            $('#customerId').attr('value', customerID);
            $('#customerId').html(customerName);
            $('#invoiceID').attr('value', invoiceID);
            var html = '';
            @foreach($transactions as $item)
                if({{$item->invoice_id}} == invoiceID) {
                    html += '<a class="cart-item btn btn-lg" category="{{$item->category_id}}" item="{{$item->item_id}}" quantity="' + Number({{$item->quantity}}) + '" price="' + Number({{$item->price}}) + '" style="width:100%;border:1px solid #CCC; min-height:3vh;max-height:5vh;border-radius:5px;padding:0px;color:#000;">'+
                                '<div class="row">'+
                                    '<div class="col-6 text-start">'+
                                        '<h5><b>' + Number({{$item->quantity}}) + ' x {{$item->getItemName() ? $item->getItemName() : $item->getCategoryName()}}</b></h5>'+
                                    '</div>'+
                                    '<div class="col-6 text-end">'+
                                        '<h5><b class="item-price" value="' + Number({{$item->price  * $item->quantity}}) + '">' + (Number({{$item->price}}) * Number({{$item->quantity}})).toLocaleString('fr-CA', { style: 'currency', currency: 'CAD'}) + '</b></h5>'+
                                    '</div>'+
                                '</div>'+
                            '</a>';
                }
            @endforeach
            $('#shoppingCart').html(html);
            var total = 0;
            $('.item-price').each(function(key, item) {
                total += Number(item.getAttribute('value'));
            });
            $('#totalPrice').attr('value', total);
            $('#totalPrice').html(total.toLocaleString('fr-CA', { style: 'currency', currency: 'CAD'}));

            $('.cart-item').on('click', function() {
                var total = 0;
                $(this).remove();
                $('.item-price').each(function(key, item) {
                    total += Number(item.getAttribute('value'));
                });
                $('#totalPrice').attr('value', total);
                $('#totalPrice').html(total.toLocaleString('fr-CA', { style: 'currency', currency: 'CAD'}));
            }); 
        }
    });
});

$(document).ready(function() {
    window.onInactive();
});
 
function onInactive(){
    var wait = setTimeout(doInactive, 300000); 
    document.onmousemove = document.mousedown = document.mouseup = document.onkeydown = document.onkeyup = document.focus = function(){
        clearTimeout(wait);
        wait = setTimeout(doInactive, 300000);
    };
}

function doInactive() {
    document.location.href = '/{{$fullBranchID}}/pos?token={{$token}}'
}
</script>
@endsection
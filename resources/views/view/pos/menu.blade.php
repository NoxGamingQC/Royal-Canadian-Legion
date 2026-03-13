@extends('layout.pos')
@section('content')

<?php
use App\Models\Branches;

$fullBranchID = explode('/', $_SERVER['REQUEST_URI'])[1];
$branchCommand = explode('-', explode('/', $_SERVER['REQUEST_URI'])[1])[0];
$branchNumber = explode('-', explode('/', $_SERVER['REQUEST_URI'])[1])[1];
$branch = Branches::where('command', $branchCommand)
    ->where('branch_id', $branchNumber)
    ->first();
?>

<div class="container-fluid p-0" style="height:100vh;background:#1a1a1a;">

    {{-- TOP BAR --}}
    <div class="row m-0 align-items-center" style="height:5%;background:#111;color:#fff;border-bottom:1px solid #333;">
        <div class="col-2 d-flex align-items-center">
            <h5 class="m-0">{{ $cashierName }}</h5>
        </div>
        <div class="col-8 d-flex justify-content-center align-items-center">
            <h5 class="m-0">{{ $branch->name }} - {{ $branch->phone }}</h5>
        </div>
        <div class="col-2 d-flex align-items-center">
            <a class="btn btn-danger w-100 h-100 d-flex align-items-center justify-content-center"
               href="/{{ $fullBranchID }}/pos?token={{$token}}"
               style="border-radius:0;border:none;">
                Déconnexion
            </a>
        </div>
    </div>

    {{-- CATEGORIES --}}
    <div class="row m-0" style="height:10%;background:#1a1a1a;">
        <div class="d-flex overflow-auto px-1 py-1">
            @foreach($catalog as $category)
                @if(isset($category->is_active) && $category->is_active === true)
                    <button class="category-btn flex-shrink-0 m-1 px-5 py-2"
                            category-id="category-{{ $category->id }}"
                            style="
                                background-image:linear-gradient(rgba(0,0,0,0.5),rgba(0,0,0,0.5)),url({{ $category->image }});
                                background-size:cover;
                                background-position:center;
                                min-width:100px;
                                color:#fff;
                                font-weight:bold;
                                border-radius:5px;
                                border:none;
                                text-shadow:1px 1px 0 #000,-1px 1px 0 #000,1px -1px 0 #000,-1px -1px 0 #000;">
                        {{ $category->name }}
                    </button>
                @endif
            @endforeach
        </div>
    </div>

    {{-- MAIN --}}
    <div class="row m-0" style="height:72%;">
        {{-- ITEMS --}}
        <div class="col-9 p-2" style="background:#222;overflow-y:auto;border:1px solid #444;">
            <div class="row row-cols-4 g-2" id="items-container">
                @foreach($catalog as $category)
                    @php
                        $variations = $category->getVariations();
                        if(count($variations) === 0){
                            $variations = [(object)[
                                'name'=>$category->name,
                                'price'=>$category->price,
                                'image'=>$category->image,
                                'is_active'=>$category->is_active,
                                'id'=>null
                            ]];
                        }
                    @endphp
                    @foreach($variations as $item)
                        @if(isset($item->is_active) && $item->is_active === true)
                            <div class="col item-card category-{{ $category->id }}" data-item-id="{{ $item->id ?? '' }}" style="display: {{ $loop->parent->first ? 'block':'none' }};cursor:pointer;">
                                <div class="card text-white bg-dark h-100"
                                    style="background-image:linear-gradient(rgba(0,0,0,0.5),rgba(0,0,0,0.5)),url({{ $item->image }});background-size:cover;background-position:center;border-radius:5px;border:none;">
                                    <div class="card-body p-2 text-center" style="display:flex;flex-direction:column;justify-content:center;border:1px solid #444;border-radius:5px;">
                                        <h6 style="text-shadow:1px 1px 0 #000,-1px 1px 0 #000,1px -1px 0 #000,-1px -1px 0 #000;">
                                            {{ $item->name }}
                                        </h6>
                                        <p style="text-shadow:1px 1px 0 #000,-1px 1px 0 #000,1px -1px 0 #000,-1px -1px 0 #000;">
                                            {{ number_format($item->price,2) }} $
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endforeach
            </div>
        </div>

        {{-- ORDER --}}
        <div class="col-3 p-2 d-flex flex-column" style="background:#151515;color:#fff;border:1px solid #444;border-left:none;height:100%;">
            <div id="order-items" style="flex:1 1 auto;overflow-y:auto;margin-bottom:10px;">
                <h5 style="color:#FF9800">COMMANDE:</h5>
                <hr>
            </div>
            <div id="order-summary" style="flex:0 0 auto;border-top:1px solid #555;padding-top:10px;">
                <div><strong style="color:#FF9800">Total:</strong> <span id="order-total">0.00</span> $</div>
                <div><strong style="color:#FF9800">Montant reçu:</strong> <span id="amount-received">0.00</span> $</div>
                <div><strong style="color:#FF9800">Change:</strong> <span id="change-amount">0.00</span> $</div>
            </div>
        </div>
    </div>

    {{-- PAYMENT --}}
    <div class="row m-0" style="height:10%;border:1px solid #444;border-top:none;">
        <div class="col-3 p-1">
            <button id="cash-btn" class="w-100 h-100" style="background:#4CAF50;color:#fff;border:none;">ARGENT COMPTANT</button>
        </div>
        <div class="col-3 p-1">
            <button class="w-100 h-100" style="background:#213653;color:#888;border:none;" disabled>DÉBIT</button>
        </div>
        <div class="col-3 p-1">
            <button id="clear-order" class="w-100 h-100" style="background:#FF9800;color:#fff;border:none;">EFFACER LA COMMANDE</button>
        </div>
        <div class="col-3 p-1">
            <button id="cancel-order" class="w-100 h-100" style="background:#E51937;color:#fff;border:none;">ANNULER LA TRANSACTION</button>
        </div>
    </div>

    {{-- FOOTER --}}
    <div class="row m-0" style="height:3%;background:#202020;border:1px solid #444;border-top:none;">
        <h6 class="text-center text-white">Créé et maintenu par Cde Jimmy Béland-Bédard - 819-852-8705</h6>
    </div>

    {{-- MODALS --}}
    @include('view.pos.modals')
</div>

<script>
let orderList = document.getElementById('order-items');
let totalElem = document.getElementById('order-total');
let amountReceivedElem = document.getElementById('amount-received');
let changeElem = document.getElementById('change-amount');
let isMember = null;
let memberFee = 0.50;

// Gestion catégories
document.querySelectorAll('.category-btn').forEach(btn=>{
    btn.addEventListener('click', ()=>{
        const id = btn.getAttribute('category-id');
        document.querySelectorAll('.item-card').forEach(i=>i.style.display='none');
        document.querySelectorAll('.'+id).forEach(i=>i.style.display='block');
    });
});

// Ajouter item
document.getElementById('items-container').addEventListener('click', e=>{
    let card = e.target.closest('.item-card');
    if(!card) return;
    const name = card.querySelector('h6').textContent;
    let price = parseFloat(card.querySelector('p').textContent.replace(' $',''));
    if(isMember===false) price+=memberFee;

    let existing = [...orderList.querySelectorAll('.order-item')].find(i=>i.dataset.name===name);
    if(existing){
        let qty = existing.querySelector('.qty');
        qty.textContent=parseInt(qty.textContent)+1;
        existing.style.background="#2e7d32";
        setTimeout(()=>existing.style.background="#333",150);
    } else {
        const div = document.createElement('div');
        div.classList.add('order-item');
        div.dataset.name = name;
        div.dataset.price = price;
        div.dataset.category = card.className.match(/category-(\d+)/)[1] || null;
        div.dataset.item = card.dataset.itemId || null;

        div.style.padding='10px 5px';
        div.style.borderBottom='1px solid #555';
        div.style.cursor='pointer';
        div.style.transition='background 0.2s';
        div.style.borderRadius='2px';
        div.innerHTML=`
            <h6 style="width:100%;display:flex;justify-content:space-between;margin:0;">
                <span><span class="qty">1</span> x ${name}</span>
                <span>${price.toFixed(2)} $</span>
            </h6>
        `;
        div.addEventListener('click', ()=>{
            div.style.background="#c62828";
            setTimeout(()=>{
                let qtyElem = div.querySelector('.qty');
                let qty = parseInt(qtyElem.textContent)-1;
                if(qty<=0) div.remove();
                else { qtyElem.textContent = qty; div.style.background="#333"; }
                updateTotal();
            },120);
        });
        orderList.appendChild(div);
        div.style.background="#2e7d32";
        setTimeout(()=>div.style.background="#333",150);
    }
    updateTotal(); // NE PAS toucher amountReceivedElem ou changeElem ici
});

// TOTAL
function updateTotal(){
    let total=0;
    orderList.querySelectorAll('.order-item').forEach(item=>{
        let qty=parseInt(item.querySelector('.qty').textContent);
        let price=parseFloat(item.dataset.price);
        total+=qty*price;
    });
    totalElem.textContent=total.toFixed(2);
}

// Fonction pour mettre à jour change uniquement depuis keypad ou membre/non-membre
function updateChange(amountReceived){
    let total = parseFloat(totalElem.textContent);
    let change = amountReceived - total;
    if(change < 0) change = 0;
    changeElem.textContent = change.toFixed(2);
}

// CLEAR / CANCEL
document.getElementById("clear-order").addEventListener("click", ()=>{
    document.querySelectorAll(".order-item").forEach(item=>item.remove());
    updateTotal();
    // Ne pas réinitialiser Montant reçu et Change ici
});
document.getElementById("cancel-order").addEventListener("click", ()=>{
    document.querySelectorAll(".order-item").forEach(item=>item.remove());
    updateTotal();
    $('#memberModal').modal('show');
    // Ne pas réinitialiser Montant reçu et Change ici
});

// Ouvrir modal membre
$(document).ready(()=>{
    $('#memberModal').modal({backdrop:'static',keyboard:false});
    $('#memberModal').modal('show');
    $('#member-btn').click(()=>{
        isMember=true;
        $('#memberModal').modal('hide');
        amountReceivedElem.textContent = "0.00";
        changeElem.textContent = "0.00";
    });
    $('#non-member-btn').click(()=>{
        isMember=false;
        $('#memberModal').modal('hide');
        amountReceivedElem.textContent = "0.00";
        changeElem.textContent = "0.00";
    });
});

// Ouvrir modal numpad
$('#cash-btn').click(()=>{ 
    $('#keypadModal').modal('show'); 
    $('#inputAmount').val('');
});

// CONFIRMER depuis numpad
$('#keypad-confirm').click(()=>{
    let amount = parseFloat($('#inputAmount').val());
    if(isNaN(amount)) amount = 0;
    amountReceivedElem.textContent = amount.toFixed(2);
    updateChange(amount);

    let cartItems = [];
    $('.order-item').each(function(){
        cartItems.push({
            category_id: $(this).data('category')||null,
            item_id: $(this).data('item')||null,
            price: $(this).data('price'),
            quantity: parseInt($(this).find('.qty').text())
        });
    });

    $.ajax({
        url: "/{{$fullBranchID}}/pos/pay?token={{$token}}",
        type:"POST",
        headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')},
        data:{
            items: cartItems,
            cashier_id: {{$cashier_id}},
            menu:'menu',
            customer_id: $('#customerId').val(),
            amount: amount,
        },
        success:function(){
            $('.order-item').each(function(){ adjustInventory(this); });
            $('.order-item').remove();
            updateTotal();
            $('#keypadModal').modal('hide');
        },
        error:function(err){ console.log(err); }
    });
});

// ADJUST INVENTORY
function adjustInventory(item){
    $.ajax({
        url: "/{{$fullBranchID}}/pos/inventory?token={{$token}}",
        type:"POST",
        headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')},
        data:{
            category_id: $(item).data('category'),
            item_id: $(item).data('item'),
            quantity: $(item).find('.qty').text()
        },
        success:function(){ console.log('success'); },
        error:function(err){ console.log(err); }
    });
}
</script>

@endsection
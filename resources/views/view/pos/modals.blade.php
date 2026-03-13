{{-- MEMBER MODAL --}}
<div class="modal fade" id="memberModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header">
        <h5 class="modal-title">Est-ce que le client est membre?</h5>
      </div>
      <div class="modal-body">
        <button id="member-btn" class="btn btn-lg btn-success form-control px-4 py-3">Membre</button>
        <br /><br />
        <button id="non-member-btn" class="btn btn-lg btn-warning form-control px-4 py-3">Non-Membre</button>
      </div>
    </div>
  </div>
</div>

{{-- NUMPAD MODAL --}}
<div class="modal fade" id="keypadModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-white p-3">
      <input type="text" id="inputAmount" class="form-control mb-2 text-end" placeholder="0.00">
      <div class="d-grid gap-2" style="grid-template-columns: repeat(3, 1fr);">
        <button class="key-btn btn btn-secondary">1</button>
        <button class="key-btn btn btn-secondary">2</button>
        <button class="key-btn btn btn-secondary">3</button>
        <button class="key-btn btn btn-secondary">4</button>
        <button class="key-btn btn btn-secondary">5</button>
        <button class="key-btn btn btn-secondary">6</button>
        <button class="key-btn btn btn-secondary">7</button>
        <button class="key-btn btn btn-secondary">8</button>
        <button class="key-btn btn btn-secondary">9</button>
        <button class="key-btn btn btn-secondary">0</button>
        <button class="key-btn btn btn-secondary">.</button>
        <button id="keypad-clear" class="btn btn-danger">C</button>
      </div>
      <button id="keypad-confirm" class="btn btn-success w-100 mt-2">CONFIRMER</button>
    </div>
  </div>
</div>

<script>
$('.key-btn').click(function(){
    let val=$(this).text();
    let input=$('#inputAmount');
    if(val==='.' && input.val().includes('.')) return;
    input.val(input.val() + val);
});
$('#keypad-clear').click(()=>$('#inputAmount').val(''));

$('#keypad-confirm').click(function(){
    let amount = parseFloat($('#inputAmount').val());
    if (isNaN(amount)) {
        amount = 0;
    }
    // Mettre à jour le Montant reçu et le Change ici
    $('#amountReceived').text(amount.toFixed(2));
    let total = parseFloat($('#totalAmount').text()) || 0;
    let change = amount - total;
    $('#changeAmount').text(change.toFixed(2));
    $('#keypadModal').modal('hide');
});

$('#keypadModal').on('hidden.bs.modal', function () {
    $('#inputAmount').val(''); // réinitialise le montant
});
</script>
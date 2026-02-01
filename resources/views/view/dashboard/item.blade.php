@extends('layout.app')
@section('content')

<div class="container">
    <br />
    <div class="card card-body">
        <div class="row">
            <div class="col-md-6">
                <h1>Détails de l'article</h1>
            </div>
            <div class="col-md-6 text-end">
                <a href="/item/{{$item->id}}/remove" class="btn btn-outline-danger btn-lg disabled" disabled>Supprimer l'article</a>
            </div>
        </div>
    </div>
    <br />
    <div class="card card-body">
        <form>
            @csrf
            <input type="hidden" id="item_id" value="{{$item->id}}" />
            <div class="row">
                <div class="col-md-6 text-center">
                    <img src="{{$item->image}}" alt="{{$item->name}}" class="img-fluid img-thumbnail"/>
                    <input type="file" id="image" name="image" class="form-control form-control-lg mt-2" accept="image/*" />
                </div>
                <div class="col-md-6">
                    <div class="form-floating input-group input-group-lg">
                        <input id="name" type="text" class="form-control form-control-lg" value="{{$item->name}}" required>
                        <label for="name" class="form-label"><b>Nom de l'article</b></label>
                        <span class="input-group-text text-danger"><i class="fa fa-asterisk"></i></span>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-md-6">
                            <label for="category" class="form-label"><b>Catégorie:</b></label>
                            <a id="category" type="button" class="btn btn-outline-primary disabled" href="/category/{{$item->category_id}}" disabled>{{App\Models\Catalog::find($item->category_id)->fullname}} <i class="fa fa-external-link" aria-hidden="true"></i></a>
                            <br />
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="isActive" {{$item->is_active ? 'checked' : ''}}>
                                <label class="form-check-label" for="isActive">Actif</label>
                            </div>
                            <br />
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input id="inventory" type="number" class="form-control form-control-sm" value="{{$item->inventory}}">
                                <label for="inventory" class="form-label"><b>Inventaire</b></label>
                                <br />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input id="alert_threshold" type="number" class="form-control form-control-sm" value="{{$item->alert_threshold}}">
                                <label for="alert_threshold" class="form-label"><b>Seuil d'alerte</b></label>
                                <br />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating input-group input-group-lg">
                            <input id="price" type="number" class="form-control form-control-lg" value="{{$item->price}}" required>
                            <label for="price" class="form-label"><b>Prix</b></label> 
                            <span class="input-group-text">$</span>
                            <span class="input-group-text text-danger"><i class="fa fa-asterisk"></i></span>

                        </div>
                        <br />
                    </div>
                    <div class="col-md-12">
                        <div class="text-end">
                            <input type="reset" class="btn btn-secondary btn-lg" value="Réinitialiser" />
                            <input id="submit" type="submit" class="btn btn-primary btn-lg" value="Soumettre" />
                        </div> 
                    </div> 
                </div>
            </div>
        </div>
    </form>
</div>
<br />
<script type="text/javascript">
    document.getElementById('image').addEventListener('change', function(event) {
        const [file] = event.target.files;
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.querySelector('img').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
    $('#submit').click(function(e) {
        e.preventDefault();
        var data = {
            name: $('#name').val(),
            price: $('#price').val(),
            is_active: $('#isActive').is(':checked') ? 1 : 0,
            inventory: $('#inventory').val(),
            alert_threshold: $('#alert_threshold').val()
        };
        $.ajax({
            url: '/item/update/' + $('#item_id').val(),
            type: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                alert('Article mis à jour avec succès!');
                location.reload();
            },
            error: function(xhr) {
                alert('Erreur lors de la mise à jour de l\'article. ');
            }
        });
    });
</script>
@endsection
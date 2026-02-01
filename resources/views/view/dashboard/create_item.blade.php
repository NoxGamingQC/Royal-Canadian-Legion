@extends('layout.app')
@section('content')

<div class="container">
    <br />
    <div class="card card-body">
        <div class="row">
            <div class="col-md-6">
                <h1>Création de l'article</h1>
            </div>
        </div>
    </div>
    <br />
    <div class="card card-body">
        <form>
            @csrf
            <div class="row">
                <div class="col-md-6 text-center">
                    <img id="image_preview" src="/images/picture_coming_soon.png" alt="" class="img-fluid img-thumbnail"/>
                    <input type="file" id="image" name="image" class="form-control form-control-lg mt-2" accept="image/*" />
                </div>
                <div class="col-md-6">
                    <div class="form-floating input-group input-group-lg">
                        <input id="name" type="text" class="form-control form-control-lg" value="" required>
                        <label for="name" class="form-label"><b>Nom de l'article</b></label>
                        <span class="input-group-text text-danger"><i class="fa fa-asterisk"></i></span>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select id="category" class="form-select" aria-label="Default select example">
                                        <option class="category-item" value="new" selected>Ceci est une nouvelle catégorie</option>
                                    @foreach($categories as $category)
                                        <option class="category-item" value="{{$category->id}}">{{$category->fullname}}</option>
                                    @endforeach
                                </select>
                                <label for="category" class="form-label"><b>Catégorie</b></label>
                                <br />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="isActive" checked>
                                <label class="form-check-label" for="isActive">Actif</label>
                            </div>
                            <br />
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input id="inventory" type="number" class="form-control form-control-sm" value="">
                                <label for="inventory" class="form-label"><b>Inventaire</b></label>
                                <br />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input id="alert_threshold" type="number" class="form-control form-control-sm" value="">
                                <label for="alert_threshold" class="form-label"><b>Seuil d'alerte</b></label>
                                <br />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating input-group input-group-lg">
                            <input id="price" type="number" class="form-control form-control-lg" value="" required>
                            <label for="price" class="form-label"><b>Prix</b></label> 
                            <span class="input-group-text">$</span>
                            <span class="input-group-text text-danger"><i class="fa fa-asterisk"></i></span>

                        </div>
                        <br />
                    </div>
                    <div class="col-md-12">
                        <div class="text-end">
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
                $('#image_preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(file);
        }
    });
    $('#submit').click(function(e) {
        e.preventDefault();
        var data = {
            name: $('#name').val(),
            price: $('#price').val(),
            image: $('#image_preview').attr('src'),
            is_active: $('#isActive').is(':checked') ? 1 : 0,
            inventory: $('#inventory').val(),
            alert_threshold: $('#alert_threshold').val(),
            category: $('#category').val()
        };
        console.log(data);
        $.ajax({
            url: '/item/new/store',
            type: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                alert('Article crée avec succès!');
                location.href = '/'.concat('{{ Auth::user()->getUserCommand() }}', '-', '{{ Auth::user()->getUserBranch() }}', '/inventory');
            },
            error: function(xhr) {
                alert('Erreur lors de la création de l\'article. ');
            }
        });
    });
</script>
@endsection
@extends('layout.app')
@section('content')

<div class="container">
    <br />
    <div class="card card-body">
        <h1>Détails de l'article</h1>
    </div>
    <br />
    <div class="card card-body">
        <div class="row">
            <div class="col-md-6">
                <img src="{{$item->image}}" alt="{{$item->name}}" class="img-fluid img-thumbnail"/>
            </div>
            <div class="col-md-6">
                <h1>{{$item->name}}</h1>
                <div class="row">
                    <div class="col-md-6">
                       <p><b>Catégorie:</b> {{App\Models\Catalog::find($item->category_id)->fullname}}</p>
                    </div>
                    <div class="col-md-6">
                        <p><b>Statut:</b> {{$item->is_active ? 'Actif' : 'Inactif'}}</p>
                    </div>
                    <div class="col-md-6">
                        <p><b>Inventaire:</b> {{is_null($item->inventory) ? 'N/A' : $item->inventory}}</p>
                    </div>
                    <div class="col-md-6">
                        <p><b>Seuil d'alerte:</b> {{is_null($item->alert_threshold) ? 'N/A' : $item->alert_threshold}}</p>
                    </div>
                </div>
                <h1><b>${{$item->price}}</h1>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layout.app')
@section('content')

<div class="container">
    <br />
    <div class="card card-body">
        <div class="row">
            <div class="col-md-4">
                <h1>Inventaire</h1>
            </div>
            <div class="col-md-8 text-end">
                <a href="/{{Auth::user()->getUserCommand() . '-' . Auth::user()->getUserBranch()}}/inventory/count" class="btn btn-outline-primary btn-lg">Calcul de l'inventaire</a>
                <a href="/{{Auth::user()->getUserCommand() . '-' . Auth::user()->getUserBranch()}}/inventory/print" class="btn btn-outline-primary btn-lg">Liste de commandes imprimable</a>
                <a href="/item/new/create" class="btn btn-outline-success btn-lg">Créer un nouvel article</a>
            </div>
    </div>
    <br />
    <div class="card card-body">
        <div class="row gx-4 gy-4">
        @foreach($catalog as $category)
                    @if(count($category->getVariations()) > 0)
                        @foreach ($category->getVariations() as $item)
                            <div class="col-md-4">
                                <a href="/item/{{$item->id}}" class="text-start btn btn-light card {{!$item->is_active ? 'border-secondary' : (is_null ($item->inventory) ? 'border-success' : ($item->inventory > $item->alert_threshold ? 'border-success' : ($item->inventory == 0 ? 'border-danger text-bg-danger' : 'border-warning text-bg-warning')))}}">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-3">
                                                <div class="img-thumbnail" style="width:75px;height:75px;max-width:75px;max-height:75px;overflow:hidden;background-size: cover;background-repeat: no-repeat;background-position: center;background-image:url({{$item->image ? $item->image : '/images/picture_coming_soon.png'}});"></div>
                                            </div>
                                            <div class="col-6">
                                                <span><b>{{$item->name}}</b></span>
                                                <br />
                                                <small>Catégorie: {{$category->fullname}}</small>
                                                <br />
                                                @if($item->inventory !== null)
                                                    <p class="badge {{is_null($item->inventory) ? 'text-bg-secondary' : ($item->inventory > $item->alert_threshold ? 'text-bg-success' : ($item->inventory == 0 ? 'text-bg-danger' : 'text-bg-warning'))}}">Disponible: {{$item->inventory}}</p>
                                                @endif
                                                    <p class="badge {{!$item->is_active ? 'text-bg-danger' : 'text-bg-success'}}">{{!$item->is_active ? 'Inactif' : 'Actif'}}</p>
                                            </div>
                                            <div class="col-3">
                                                ${{$item->price}}
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    @else
                        <div class="col-md-4">
                            <a href="/category/{{$category->id}}" class="text-start btn btn-light card {{!$category->is_active ? 'border-secondary' : (is_null ($category->inventory) ? 'border-success' : ($category->inventory > $category->alert_threshold ? 'border-success' : ($category->inventory == 0 ? 'border-danger text-bg-danger' : 'border-warning text-bg-warning')))}}">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-3">
                                            <div class="img-thumbnail" style="width:75px;height:75px;max-width:75px;max-height:75px;overflow:hidden;background-size: cover;background-repeat: no-repeat;background-position: center;background-image:url({{$category->image ? $category->image : '/images/picture_coming_soon.png'}});"></div>
                                        </div>
                                        <div class="col-6">
                                            <span><b>Articles de base</b></span>
                                            <br />
                                            <small>Catégorie: {{$category->fullname}}</small>
                                            <br />
                                            @if($category->inventory !== null)
                                                    <p class="badge {{is_null($category->inventory) ? 'text-bg-secondary' : ($category->inventory > $category->alert_threshold ? 'text-bg-success' : ($category->inventory == 0 ? 'text-bg-danger' : 'text-bg-warning'))}}">Disponible: {{$category->inventory}}</p>
                                            @endif
                                            <p class="badge {{!$category->is_active ? 'text-bg-danger' : 'text-bg-success'}}">{{!$category->is_active ? 'Inactif' : 'Actif'}}</p>
                                        </div>
                                        <div class="col-3">
                                            ${{$category->price}}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif
        @endforeach
                </div>
    </div>
    <br /><br /><br /><br />
</div>

@endsection
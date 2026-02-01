@extends('layout.app')
@section('content')

<div class="container">
    <br /><br />
    <h1>Listes des articles</h1>
    <hr />
    <div class="card card-body">
        <div class="row gx-4 gy-4">
        @foreach($catalog as $category)
                    @if(count($category->getVariations()) > 0)
                        @foreach ($category->getVariations() as $item)
                            <div class="col-md-4">
                                <a href="/item/{{$item->id}}" class="text-start btn btn-light card {{!$item->is_active ? 'text-bg-secondary' : (is_null ($item->inventory) ? 'border-secondary' : ($item->inventory > $item->alert_threshold ? 'border-success' : ($item->inventory == 0 ? 'border-danger text-bg-danger' : 'border-warning text-bg-warning')))}}">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-3">
                                                <div class="img-thumbnail" style="width:75px;height:75px;max-width:75px;max-height:75px;overflow:hidden;background-size: cover;background-repeat: no-repeat;background-position: center;background-image:url({{$item->image}});"></div>
                                            </div>
                                            <div class="col-6">
                                                <span><b>{{$item->name}}</b></span>
                                                <br />
                                                <small>Catégorie: {{$category->fullname}}</small>
                                                @if($item->inventory !== null)
                                                    <p class="badge {{is_null($item->inventory) ? 'text-bg-secondary' : ($item->inventory > $item->alert_threshold ? 'text-bg-success' : 'text-bg-danger')}}">Disponible: {{$item->inventory}}</p>
                                                @endif
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
                            <a href="/category/{{$category->id}}" class="text-start btn btn-light card {{!$category->is_active ? 'text-bg-secondary' : (is_null ($category->inventory) ? 'border-secondary' : ($category->inventory > $category->alert_threshold ? 'border-success' : ($category->inventory == 0 ? 'border-danger text-bg-danger' : 'border-warning text-bg-warning')))}}">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-3">
                                            <div class="img-thumbnail" style="width:75px;height:75px;max-width:75px;max-height:75px;overflow:hidden;background-size: cover;background-repeat: no-repeat;background-position: center;background-image:url({{$category->image}});"></div>
                                        </div>
                                        <div class="col-6">
                                            <span><b>Articles de base</b></span>
                                            <br />
                                            <small>Catégorie: {{$category->fullname}}</small>
                                            @if($category->inventory !== null)
                                                <p class="badge {{is_null($category->inventory) ? 'text-bg-secondary' : ($category->inventory > $category->alert_threshold ? 'text-bg-success' : 'text-bg-danger')}}">Disponible: {{$category->inventory}}</p>
                                            @endif
                                        </div>
                                        <div class="col-3">
                                            ${{$item->price}}
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
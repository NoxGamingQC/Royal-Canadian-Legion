@extends('layout.app')
@section('content')
<div class="container">
    <br />
    <div class="card card-body">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1>Inventaire - Liste imprimable</h1>
            </div>
        </div>
    </div>
    <br />
        @foreach($categories as $category)
            @if($category->inventory == 0 && $category->inventory !== null && $category->is_active)
                <?php
                    $outofstockCount = isset($outOfStockCount) ? $outOfStockCount + 1 : 1;
                ?>
            @endif
        @endforeach
        @foreach($items as $item)
            @if($item->inventory == 0 && $item->inventory !== null && $item->is_active)
                <?php
                    $outofstockCount = isset($outOfStockCount) ? $outOfStockCount + 1 : 1;
                ?>
            @endif
        @endforeach
        @foreach($categories as $category)
            @if($category->inventory < $category->alert_threshold && $category->inventory !== null && $category->is_active && $category->inventory != 0)
                <?php
                    $lowInventoryCount = isset($lowInventoryCount) ? $lowInventoryCount + 1 : 1;
                ?>
            @endif
        @endforeach
        @foreach($items as $item)
            @if($item->inventory < $item->alert_threshold && $item->inventory !== null && $item->is_active && $item->inventory != 0)
                <?php
                    $lowInventoryCount = isset($lowInventoryCount) ? $lowInventoryCount + 1 : 1;
                ?>
            @endif
        @endforeach
        @if(isset($outofstockCount) && $outofstockCount > 0)
            
        <div class="card card-body">
            <h2>Articles en rupture de stock</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Article</th>
                        <th>Catégorie</th>
                        <th>Disponible</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        @if($category->inventory == 0 && $category->inventory !== null && $category->is_active)
                            <tr>
                                <td>{{$category->fullname}}</td>
                                <td>N/D</td>
                                <td>{{is_null($category->inventory) ? 'N/D' : $category->inventory}}</td>
                                <td>{{is_null($category->alert_threshold) ? 'N/D' : $category->alert_threshold}}</td>
                            </tr>
                        @endif
                    @endforeach
                    @foreach($items as $item)
                        @if($item->inventory == 0 && $item->inventory !== null && $item->is_active)
                            <tr>
                                <td>{{$item->name}}</td>
                                <td>{{App\Models\Catalog::find($item->category_id)->fullname ?? 'N/D'}}</td>
                                <td>{{is_null($item->inventory) ? 'N/D' : $item->inventory}}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        <br />
    @endif
    @if(isset($lowInventoryCount) && $lowInventoryCount > 0)
        <div class="card card-body">
            <h2>Articles à faible inventaire</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Article</th>
                        <th>Catégorie</th>
                        <th>Disponible</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        @if($category->inventory < $category->alert_threshold && $category->inventory !== null && $category->is_active && $category->inventory != 0)
                            <tr>
                                <td>{{$category->fullname}}</td>
                                <td>N/D</td>
                                <td>{{is_null($category->inventory) ? 'N/D' : $category->inventory}}</td>
                            </tr>
                        @endif
                    @endforeach
                    @foreach($items as $item)
                        @if($item->inventory < $item->alert_threshold && $item->inventory !== null && $item->is_active && $item->inventory != 0)
                            <tr>
                                <td>{{$item->name}}</td>
                                <td>{{App\Models\Catalog::find($item->category_id)->fullname ?? 'N/D'}}</td>
                                <td>{{is_null($item->inventory) ? 'N/D' : $item->inventory}}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
    @if((!isset($outofstockCount) || $outofstockCount == 0) && (!isset($lowInventoryCount) || $lowInventoryCount == 0))
        <h2>Aucun article n'a besoin d'être réapprovisionné.</h2>
    @endif
</div>
@endsection
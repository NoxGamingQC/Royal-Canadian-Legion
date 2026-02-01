@extends('layout.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Calcul de l'inventaire</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Catégorie</th>
                            <th>Quantité</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ App\Models\Catalog::find($item->category_id)->fullname }}</td>
                                <td></td>
                            </tr>
                        @endforeach
                        @foreach($categories as $category)
                            <tr>
                                <td>{{ $category->fullname }}</td>
                                <td>N/D</td>
                                <td></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
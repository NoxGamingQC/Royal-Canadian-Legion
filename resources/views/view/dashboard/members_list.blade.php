@extends('layout.app')
@section('content')
<style>
@page {
    @top-center { 
        content: '{{env('NAME')}} - Liste des membres'; 
    }
    @bottom-left { 
        content: 'Généré par Services Tech. J.Bédard'
    }
    @bottom-right { 
        content: counter(page)  '/' counter(pages)
    }
}
</style>
<div class="container-fluid">
    <br />
    <h1>Liste des membres en règle ({{date('Y')}}) &nbsp;<a type="button" class="no-print btn btn-lg btn-success" href="#"><i class="fa fa-plus" aria-hidden="true"></i></a></h1>
    <br />
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No de membre</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Téléphone</th>
                <th>Courriel</th>
                <th>Année payé</th>
                <th class="no-print">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($members as $member)
                @if(!$member->archive)
                    <tr class="{{ $member->last_year_paid < date('Y') ? 'table-danger' : ($member->last_year_paid == date('Y') ? 'table-warning' : ($member->last_year_paid > date('Y') ? 'table-success' : '')) }}">
                        <td>{{ $member->member_id }}</td>
                        <td>{{ $member->lastname }}</td>
                        <td>{{ $member->firstname }}</td>
                        <td>{{ $member->phone_number }}</td>
                        <td>{{ $member->email_address }}</td>
                        <td>{{ $member->last_year_paid }}</td>
                        <td class="no-print text-center"><a type="button" class="btn btn-sm btn-warning" href="/member/edit/{{$member->id}}"><i class="fa fa-pencil" aria-hidden="true"></i></a>&nbsp;&nbsp;<a type="button" class="btn btn-sm btn-danger" href="/member/remove/{{$member->id}}"><i class="fa fa-trash" aria-hidden="true"></i></a></td>

                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>
@endsection
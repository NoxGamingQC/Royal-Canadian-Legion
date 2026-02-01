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
    <div class="card card-body">
        <h1 id="allMembersTitle">Liste des membres ({{date('Y')}}) &nbsp;
            @if(Auth::user()->hasPermission('edit_members'))
            <a type="button" class="no-print btn btn-lg btn-success" href="/member/create"><i class="fa fa-plus" aria-hidden="true"></i></a>
            @endif
            </h1>
        <h1 id="activeMembersTitle" hidden>Liste des membres actifs ({{date('Y')}}) &nbsp;
            @if(Auth::user()->hasPermission('edit_members'))
            <a type="button" class="no-print btn btn-lg btn-success" href="/member/create"><i class="fa fa-plus" aria-hidden="true"></i></a>
            @endif
        </h1>
        <h1 id="inactiveMembersTitle" hidden>Liste des membres inactifs ({{date('Y')}}) &nbsp;
            @if(Auth::user()->hasPermission('edit_members'))
            <a type="button" class="no-print btn btn-lg btn-success" href="/member/create"><i class="fa fa-plus" aria-hidden="true"></i></a>
            @endif
        </h1>
    </div>
    <br class="no-print"/>
    <div id="filter" class="card card-body no-print">
        <div class="btn-group" role="group" aria-label="Filter">
        <button id="allMembers" class="btn btn-primary" style="border:1px solid #CCCCCC;">Tous les membres</button>
        <button id="activeMembers" class="btn btn-light" style="border:1px solid #CCCCCC;">Membres actifs</button>
        <button id="inactiveMembers" class="btn btn-light" style="border:1px solid #CCCCCC;">Membres inactifs</button>
        </div>
    </div>
    <br />
    <div class="card">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No de membre</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Téléphone</th>
                    <th>Courriel</th>
                    <th>Année payé</th>
                    @if(Auth::user()->hasPermission('edit_members'))
                    <th class="no-print">Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($members as $member)
                    @if(!$member->archive)
                        <tr class="{{ $member->last_year_paid < date('Y') ? 'inactive-member' : 'active-member'}} {{ $member->last_year_paid < date('Y') ? 'table-danger' : ($member->last_year_paid == date('Y') ? 'table-warning' : ($member->last_year_paid > date('Y') ? 'table-success' : '')) }}">
                            <td>{{ $member->member_id }}</td>
                            <td>{{ $member->lastname }}</td>
                            <td>{{ $member->firstname }}</td>
                            <td>{{ $member->phone_number }}</td>
                            <td>{{ $member->email_address }}</td>
                            <td>{{ $member->last_year_paid }}</td>
                            @if(Auth::user()->hasPermission('edit_members'))
                            <td class="no-print text-center"><a type="button" class="btn btn-sm btn-warning" href="/member/edit/{{$member->id}}"><i class="fa fa-pencil" aria-hidden="true"></i></a>&nbsp;&nbsp;<a type="button" class="btn btn-sm btn-danger" href="/member/remove/{{$member->id}}"><i class="fa fa-trash" aria-hidden="true"></i></a></td>
                            @endif
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript">
    document.getElementById('allMembers').addEventListener('click', function() {
        var rows = document.querySelectorAll('tbody tr');
        $('#activeMembers').removeClass('btn-primary').addClass('btn-light');
        $('#allMembers').removeClass('btn-light').addClass('btn-primary');
        $('#inactiveMembers').removeClass('btn-primary').addClass('btn-light');
        rows.forEach(function(row) {
            row.style.display = '';
            document.getElementById('allMembersTitle').hidden = false;
            document.getElementById('activeMembersTitle').hidden = true;
            document.getElementById('inactiveMembersTitle').hidden = true;
        });
    });

    document.getElementById('activeMembers').addEventListener('click', function() {
        var rows = document.querySelectorAll('tbody tr');
        $('#activeMembers').removeClass('btn-light').addClass('btn-primary');
        $('#allMembers').removeClass('btn-primary').addClass('btn-light');
        $('#inactiveMembers').removeClass('btn-primary').addClass('btn-light');
        rows.forEach(function(row) {
            if (row.classList.contains('active-member')) {
                row.style.display = '';
                document.getElementById('allMembersTitle').hidden = true;
                document.getElementById('activeMembersTitle').hidden = false;
                document.getElementById('inactiveMembersTitle').hidden = true;
            } else {
                row.style.display = 'none';
            }
        });
    });

    document.getElementById('inactiveMembers').addEventListener('click', function() {
        var rows = document.querySelectorAll('tbody tr');
        $('#activeMembers').removeClass('btn-primary').addClass('btn-light');
        $('#allMembers').removeClass('btn-primary').addClass('btn-light');
        $('#inactiveMembers').removeClass('btn-light').addClass('btn-primary');
        rows.forEach(function(row) {
            if (row.classList.contains('inactive-member')) {
                row.style.display = '';
                document.getElementById('allMembersTitle').hidden = true;
                document.getElementById('activeMembersTitle').hidden = true;
                document.getElementById('inactiveMembersTitle').hidden = false;
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
@endsection
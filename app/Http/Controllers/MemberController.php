<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;

class MemberController extends Controller
{
    public function edit($id)
    {
        if (Auth::check()) {
            $member = Customer::find($id);

            if ($member) {
                return view('view.dashboard.member_edit', ['member' => $member]);
            } else {
                return redirect()->back()->with('errors', ['Membre non trouvé.']);
            }
        } else {
            return redirect('/login')->with('errors', ['Vous devez être connecté pour effectuer cette action.']);
        }
    }

    public function create() 
    {
        if (Auth::check()) {
            return view('view.dashboard.member_create');
        } else {
            return redirect('/login')->with('errors', ['Vous devez être connecté pour effectuer cette action.']);
        }
    }

    public function store()
    {
        if (Auth::check()) {
            $member = new Customer();
            $member->member_id = request()->input('member_id');
            $member->firstname = request()->input('firstname');
            $member->lastname = request()->input('lastname');
            $member->email_address = request()->input('email_address');
            $member->phone_number = request()->input('phone_number');
            $member->last_year_paid = request()->input('last_year_paid');
            $member->save();

            return redirect('/' . Auth::user()->getUserCommand() . '-' . Auth::user()->getUserBranch() . '/members')->with('success', ['Membre créé avec succès.']);
        } else {
            return redirect('/login')->with('errors', ['Vous devez être connecté pour effectuer cette action.']);
        }
    }

    public function update($id)
    {
        if (Auth::check()) {
            $member = Customer::find($id);

            if ($member) {
                $member->member_id = request()->input('member_id');
                $member->firstname = request()->input('firstname');
                $member->lastname = request()->input('lastname');
                $member->email_address = request()->input('email_address');
                $member->phone_number = request()->input('phone_number');
                $member->last_year_paid = request()->input('last_year_paid');
                $member->save();

                return redirect('/' . Auth::user()->getUserCommand() . '-' . Auth::user()->getUserBranch() . '/members')->with('success', ['Membre mis à jour avec succès.']);
            } else {
                return redirect()->back()->with('errors', ['Membre non trouvé.']);
            }
        } else {
            return redirect('/login')->with('errors', ['Vous devez être connecté pour effectuer cette action.']);
        }
    }

    public function remove($id)
    {
        if (Auth::check()) {
            $member = Customer::find($id);

            if ($member) {
                $member->delete();
            } else {
                return redirect()->back()->with('errors', ['Membre non trouvé.']);
            }
            return redirect()->back()->with('success', ['Membre supprimé avec succès.']); 
        } else {
            return redirect('/login')->with('errors', ['Vous devez être connecté pour effectuer cette action.']);
        }
    }
}
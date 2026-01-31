<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;

class MemberController extends Controller
{
    public function remove($id)
    {
        dd(Auth::check());
        if (Auth::check()) {
            $member = Customer::find($id);

            if ($member) {
                $member->delete();
            } else {
                return redirect()->back()->with('errors', ['Membre non trouvé.']);
            }
            return redirect()->back()->with('successes', ['Membre supprimé avec succès.']); 
        } else {
            return redirect('/login')->with('errors', ['Vous devez être connecté pour effectuer cette action.']);
        }
    }
}
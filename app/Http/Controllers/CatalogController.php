<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\Catalog;
use App\Models\Item;
use Carbon\Carbon;
use App\Models\Finances;
use App\Models\Customer;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function updateItem(Request $request, $item_id)
    {
        $item = Item::find($item_id);
        if (!$item) {
            return redirect()->back()->with('error', 'Item not found.');
        }
        $item->name = $request->name;
        $item->price = $request->price;
        $item->inventory = $request->inventory;
        $item->alert_threshold = $request->alert_threshold;
        $item->is_active = $request->is_active ? true : false;
        $item->save();

        return redirect()->back()->with('success', 'Item updated successfully.');
    }
}
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
    public function category($category_id)
    {
        $category = Catalog::find($category_id);
        if (!$category) {
            return redirect()->back()->with('error', 'Category not found.');
        }
        return view('view.dashboard.category')->with([
            'category' => $category,
        ]);
    }

    public function updateCategory(Request $request, $category_id)
    {
        $category = Catalog::find($category_id);
        if (!$category) {
            return redirect()->back()->with('error', 'Category not found.');
        }
        $category->fullname = $request->fullname;
        $category->name = $request->name;
        $category->is_active = $request->is_active ? true : false;
        $category->inventory = $request->inventory;
        $category->alert_threshold = $request->alert_threshold;
        $category->price = $request->price;
        $category->image = $request->image;
        $category->save();

        return redirect()->back()->with('success', 'Category updated successfully.');
    }

    public function removeCategory($category_id)
    {
        $category = Catalog::find($category_id);
        if (!$category) {
            return redirect()->back()->with('error', 'Category not found.');
        }
        $category->delete();

        return redirect('/' . Auth::user()->getUserCommand() . '-' . Auth::user()->getUserBranch() . '/inventory')->with('success', 'Category removed successfully.');
    }

    public function updateItem(Request $request, $item_id)
    {
        $item = Item::find($item_id);
        if (!$item) {
            return redirect()->back()->with('error', 'Item not found.');
        }
        if($request->image) {
            $item->image = $request->image;
        }
        $item->name = $request->name;
        $item->price = $request->price;
        $item->inventory = $request->inventory;
        $item->alert_threshold = $request->alert_threshold;
        $item->is_active = $request->is_active ? true : false;
        $item->save();

        return redirect()->back()->with('success', 'Item updated successfully.');
    }

    public function removeItem($item_id)
    {
        $item = Item::find($item_id);
        if (!$item) {
            return redirect()->back()->with('error', 'Item not found.');
        }
        $item->delete();

        return redirect('/' . Auth::user()->getUserCommand() . '-' . Auth::user()->getUserBranch() . '/inventory')->with('success', 'Item removed successfully.');
    }
}
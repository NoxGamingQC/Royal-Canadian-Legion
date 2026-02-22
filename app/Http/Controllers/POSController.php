<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Model\API\ApiKey;
use Illuminate\Support\Facades\DB;
use Square\Exceptions\ApiException;
use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\InvoiceItems;
use App\Models\User;
use App\Models\Catalog;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\KitshopItem;
use App\Models\OauthToken;
use App\Models\KitshopTransaction;
use Illuminate\Http\Request;

class POSController extends Controller
{
    public function index(Request $request) {
        OauthToken::validate($request);
        
        return view('view.pos.lock')->with([
            'token' => $request->query()['token'],
            'name' => env('NAME'),
            'image' => env('IMAGE'),
            'phone_number' => env('PHONE_NUMBER'),
            'address'=> env('ADDRESS')
        ]);
    }

    public function validateCashier(Request $request,$branch, $pin, $option) {
        OauthToken::validate($request);
        $cashier = User::where('pin', '=', $pin)->first();
        if(!isset($cashier)) {
            $cashier = User::where('pin', '=', $pin)->where('pin', '=', 'all')->first();
        }
        if($cashier) {
            $hasMenuAccess = in_array('bar_menu', explode(';', $cashier->access)) || $cashier->access == 'bar_all' ? true : false;
            $hasKitshopAccess = in_array('kitshop', explode(';', $cashier->access)) || $cashier->access == 'all' ? true : false;
            if($option == 'menu') {
                if(in_array('menu', $request->options) || in_array('kitshop', $request->options) || $cashier->access == 'all') {
                    return response()->json([
                        'id' => $cashier->id,
                        'name' => isset($cashier->lastname) ? ($cashier->firstname . ' ' . $cashier->lastname[0] . '.') : $cashier->firstname,
                        'hasAllAccess' => $cashier->access == 'bar_all',
                        'hasMenuAccess' =>  $hasMenuAccess,
                        'hasKitshopAccess' => $hasKitshopAccess,
                    ]);
                } else {
                    abort(403);
                }
            } else {
                //Future tab
            }
        } else {
            abort(403, 'access_denied');
        }
        abort(403, 'pin_error');
    }

    public function menu(Request $request,$branch, $cashierID)
    {
            OauthToken::validate($request);
        
            $cashier = User::find($cashierID);   
            $category = Catalog::all();
            $invoices = Invoice::where('status','=', 'unpaid')->get();
            $customers = Customer::all()->sortBy('firstname');
            $kitshopItems = KitshopItem::all()->sortBy('name');
            $transactions = Transaction::where('payment_type', null)->get();
            $hasMenuAccess = in_array('bar_menu', explode(';', $cashier->access)) || $cashier->access == 'all' ? true : false;
            $hasKitshopAccess = in_array('kitshop', explode(';', $cashier->access)) || $cashier->access == 'all' ? true : false;
            if($cashier) {
                return view('view.pos.menu')->with([
                    'token' => $request->query()['token'],
                    'cashier_id' => $cashier->id,
                    'name' => env('NAME'),
                    'image' => env('LOGO'),
                    'phone_number'=> env('PHONE_NUMBER'),
                    'catalog' => $category->sortBy('id'),
                    'customers' => $customers,
                    'transactions' => $transactions,
                    'hasAllAccess' => $cashier->access == 'all',
                    'hasMenuAccess' =>  $hasMenuAccess,
                    'hasKitshopAccess' => $hasKitshopAccess,
                    'kitshopItems' => $kitshopItems,
                    'catalogImages' => isset($catalogImages) ? $catalogImages->getObjects() : [],
                    'invoices' => isset($invoices) ? $invoices : [],
                    'cashierName' => isset($cashier->lastname) ? ($cashier->firstname . ' ' . $cashier->lastname) : $cashier->firstname
                ]);
            }
        return redirect('/pos/');
    }

    public function kitshop(Request $request,$branch) {
        OauthToken::validate($request);
        
        $items = KitshopItem::all()->sortBy('name');
        return view('view.pos.kitshop')->with([
            'token' => $request->query()['token'],
            'items' => $items
        ]);
    }

    public function getInvoiceItems(Request $request, $invoiceID) {
        OauthToken::validate($request);
        
        return Transaction::where('invoice_id', $invoiceID)->get();
    }

    public function save(Request $request) {
        OauthToken::validate($request);
        
        $cashier = User::find($request->cashier_id);
        if($cashier) {
             if(is_null($request->invoice_id)) {
                $invoice = new Invoice;
            } else {
                $invoice = Invoice::find($request->invoice_id);
                Transaction::where('invoice_id', $invoice->id)->delete();
            }
            $invoice->customer_id = $request->customer_id;
            $invoice->status = 'paid';
            $invoice->save();
            foreach($request->items as $item) {
                $transaction = new Transaction;
                $transaction->category_id = $item['category_id'];
                $transaction->item_id = ($item['item_id'] === 'undefined' ? null : $item['item_id']);
                $transaction->price = $item['price'];
                $transaction->quantity = $item['quantity'];
                $transaction->cashier_id = $request->cashier_id;
                $transaction->customer_id = $request->customer_id;
                $transaction->is_promotion =  (is_null($request->is_promotion) ? false : $request->is_promotion);
                $transaction->invoice_id = $invoice->id;
                $transaction->created_at;
                $transaction->updated_at;
                $transaction->payment_type = null;
                $transaction->save();
            }
            return 200;
        }
        abort(403);
    }

    public function sellInventory(Request $request) {
        OauthToken::validate($request);
        
        $category = Catalog::where('id', $request->category_id)->first();
        try {
            $item = Item::where('id', $request->item_id)->first();
    
            if(isset($item)) {
                if($item && $item !== 'undefined' && !is_null($item)) {
                    if($item->inventory > 0 && !is_null($item->inventory)) {
                        $item->inventory -= $request->quantity;
                        $item->save();
                    }
                    if($item->inventory < 0) {
                        $item->inventory = 0;
                        $item->save();
                    }
                    return 200;
                }
            }
        } catch(\Exception $error) {}
        
        if($category && $category !== 'undefined' && !is_null($category)) {
            if($category->inventory > 0 && !is_null($category->inventory)) {
                $category->inventory -= $request->quantity;
                $category->save();
                if($category->inventory < 0) {
                    $category->inventory = 0;
                    $category->save();
                }
            }
        }

    }
    public function saveInvoice(Request $request) {
        OauthToken::validate($request);
        $cashier = Pin::find($request->cashier_id);

        if($cashier) {
            if(is_null($request->invoice_id)) {
                $invoice = new Invoice;
            } else {
                $invoice = Invoice::find($request->invoice_id);
                Transaction::where('invoice_id', $invoice->id)->delete();
            }
            $invoice->customer_id = $request->customer_id;
            $invoice->status = 'unpaid';
            $invoice->save();
            foreach($request->items as $item) {
                $transaction = new Transaction;
                $transaction->category_id = $item['category_id'];
                $transaction->item_id = ($item['item_id'] === 'undefined' ? null : $item['item_id']);
                $transaction->price = $item['price'];
                $transaction->quantity = $item['quantity'];
                $transaction->cashier_id = $request->cashier_id;
                $transaction->customer_id = $request->customer_id;
                $transaction->is_promotion =  (is_null($request->is_promotion) ? false : $request->is_promotion);
                $transaction->invoice_id = $invoice->id;
                $transaction->created_at;
                $transaction->updated_at;
                $transaction->payment_type = null;
                $transaction->save();
            }
            return 200;
        }
        abort(403);
    }

    public function inventoryMenu (Request $request, $branch, $cashierID) {
        OauthToken::validate($request);
        
        $cashier = Pin::find($cashierID);  
        return view('view.pos.inventory_menu')->with([
            'token' => $request->query()['token'],
            'cashier_id' => $cashier->id,
        ]);
    }

    public function fullInventoryCount (Request $request, $branch, $cashierID) {
        OauthToken::validate($request);
        $cashier = Pin::find($cashierID);  
        return view('view.pos.inventory')->with([
            'token' => $request->query()['token'],
            'cashier_id' => $cashier->id,
            'items' => Catalog::allItemList(),
        ]);
    }
}
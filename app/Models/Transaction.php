<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Transaction extends Model
{
    protected $table = 'bar_transactions';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at'; 

    public function getCashier() {
        $cashier = DB::table('pin')
                ->where('id', '=', $this->cashier_id)
                ->first();
        return $cashier->firstname . ' ' . $cashier->lastname;
    }

    public function getItemName () {
        if ($this->item_id) {
            $itemSearch = DB::table('bar_items')
                    ->where('id', '=', $this->item_id)
                    ->first();
            if (isset($itemSearch)) {
                $item = $itemSearch->name;
            } else {
                $item = 'Article supprimé';
            }

        } else {
            $itemSearch = DB::table('bar_item_category')
                    ->where('id', '=', $this->category_id)
                    ->first();
            if (isset($itemSearch)) {
                $item = $itemSearch->fullname;
            } else {
                $item = 'Catégorie supprimée';
            }
        }
        return $item;
    }

    public function getCategoryName() {
        $itemSearch = DB::table('bar_item_category')
                ->where('id', '=', $this->category_id)
                ->first();
        if (isset($itemSearch)) {
                $item = $itemSearch->fullname;
        } else {
            $item = 'Catégorie supprimée';
        }
        return $item;
    }

    public function scopeTotalCount($query) {
        $totalPrice = 0;
        foreach($query->where('is_canceled', false)->get() as $transaction) {
            $totalPrice += ($transaction->price * $transaction->quantity);
        }
        
        return $totalPrice;
    }

    public function scopeCountByCategory($query, $categoryId, $isPromotion = false) {
        $totalPrice = 0;
        foreach($query->where('is_canceled', false)->where('category_id', $categoryId)->where('is_promotion', $isPromotion)->get() as $transaction) {
            $totalPrice += ($transaction->price * $transaction->quantity);
        }
        
        return $totalPrice;
    }

    public function scopeCountByItem($query, $itemId, $isPromotion = false) {
        $totalPrice = 0;
        foreach($query->where('is_canceled', false)->where('item_id', $itemId)->where('is_promotion', $isPromotion)->get() as $transaction) {
            $totalPrice += ($transaction->price * $transaction->quantity);
        }
        
        return $totalPrice;
    }

    public function scopeQuantityByCategory($query, $categoryId) {
        $quantity = 0;
        foreach($query->where('is_canceled', false)->where('category_id', $categoryId)->get() as $transaction) {
            $quantity += $transaction->quantity;
        }
        
        return $quantity;
    }

    public function scopeQuantityByItem($query, $itemId) {
        $quantity = 0;
        foreach($query->where('is_canceled', false)->where('item_id', $itemId)->get() as $transaction) {
            $quantity += $transaction->quantity;
        }
        
        return $quantity;
    }
}

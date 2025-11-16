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

class DashboardController extends Controller
{
    public function index() {
        if(Auth::check()) {
            if(Auth::user()->is_authorized) {
                $user = Auth::user();
                $transactionsSumByMonth = [];
                $transactionsSumByMonthLastYear = [];
                $transactionsSumByMonth2YearAgo = [];
                $transactionByCategories = [];
                $transactionColorsByMonth = [];
                $transactionByItems = [];
                $transactionColor = '';
                $totalTransactions = Transaction::where('is_canceled', false)->whereYear('created_at', date('Y'))->get();
                $totalTransactionsSum = $totalTransactions->sum('price');
                $finances = [
                    'chequing' => [],
                    'saving' => [] ,
                    'poppy' => [],
                ];
                
                for($month = 1; $month <= 12; $month++) {
                    if (Carbon::now()->month >= $month) {
                        if(Transaction::where('is_canceled', false)->whereYear('created_at', date('Y'))->whereMonth('created_at', $month)->get()->sum('price') > Transaction::where('is_canceled', false)->whereYear('created_at', date('Y')-1)->whereMonth('created_at', $month)->get()->sum('price')) {
                            $transactionColor = 'rgba(18, 196, 12, 1)';
                        } elseif(Transaction::where('is_canceled', false)->whereYear('created_at', date('Y'))->whereMonth('created_at', $month)->get()->sum('price') == Transaction::where('is_canceled', false)->whereYear('created_at', date('Y')-1)->whereMonth('created_at', $month)->get()->sum('price')) {
                            $transactionColor = 'rgba(250, 225, 83, 1)';
                        } else {
                            $transactionColor = 'rgba(233, 47, 14, 1)';
                        }
                        array_push( 
                            $transactionsSumByMonth,
                            Transaction::where('is_canceled', false)->whereYear('created_at', date('Y'))->whereMonth('created_at', $month)->get()->sum('price') ? Transaction::where('is_canceled', false)->whereYear('created_at', date('Y'))->whereMonth('created_at', $month)->get()->sum('price') : 0
                        );
                        array_push(
                            $transactionColorsByMonth,
                            '' .$transactionColor
                        );
                    }
                    array_push(
                        $transactionsSumByMonthLastYear,
                        Transaction::where('is_canceled', false)->whereYear('created_at', date('Y') -1 )->whereMonth('created_at', $month)->get()->sum('price')
                    );
                    array_push(
                        $transactionsSumByMonth2YearAgo,
                        Transaction::where('is_canceled', false)->whereYear('created_at', date('Y') -2 )->whereMonth('created_at', $month)->get()->sum('price')
                    );
                }
                $transactionCategories = Catalog::all()->sortBy('id');
                foreach($transactionCategories as $category) {
                    if($category->name !== 'Carte') {
                        array_push($transactionByCategories, [
                            'name' => $category->fullname,
                            'sum' =>  Transaction::where('is_canceled', false)->whereYear('created_at', date('Y'))->where('category_id', $category->id)->get()->sum('price'),
                            'quantity' =>  Transaction::where('is_canceled', false)->whereYear('created_at', date('Y'))->where('category_id', $category->id)->get()->sum('quantity'),
                        ]);
                    }
                }

                usort($transactionByCategories, function($a, $b) {
                    return $b['quantity'] <=> $a['quantity'];
                });
                $top10Categories = array_slice($transactionByCategories, 0, 10);

                $transactionItems = Item::all()->sortBy('id');
                foreach($transactionItems as $item) {
                    if($item->name !== 'Carte') {
                        array_push($transactionByItems, [
                            'name' => str_replace('\'', '\\\'', $item->name),
                            'sum' =>  Transaction::where('is_canceled', false)->whereYear('created_at', date('Y'))->where('item_id', $item->id)->get()->sum('price'),
                            'quantity' =>  Transaction::where('is_canceled', false)->whereYear('created_at', date('Y'))->where('item_id', $item->id)->get()->sum('quantity'),
                        ]);
                    }
                }
                usort($transactionByItems, function($a, $b) {
                    return $b['quantity'] <=> $a['quantity'];
                });
                $top10Items = array_slice($transactionByItems, 0, 10);

                for($i = 1; $i < 13; $i++) {
                    $chequingAccount =  Finances::where('account_type', 0)->whereYear('date', date('Y'))->whereMonth('date', $i)->first();
                    array_push($finances['chequing'],$chequingAccount ? $chequingAccount->amount : null);
                    $savingAccount =  Finances::where('account_type', 1)->whereYear('date', date('Y'))->whereMonth('date', $i)->first();
                    array_push($finances['saving'],$savingAccount ? $savingAccount->amount : null);
                    $poppyAccount =  Finances::where('account_type', 2)->whereYear('date', date('Y'))->whereMonth('date', $i)->first();
                    array_push($finances['poppy'],$poppyAccount ? $poppyAccount->amount : null);
                }
                $activeMemberCount = Customer::where('last_year_paid', date('Y'))->orWhere('last_year_paid', (date('Y') + 1))->get() ? count(Customer::where('archive', false)->where('last_year_paid', date('Y'))->orWhere('last_year_paid', (date('Y') + 1))->get() ) : 0;
                return view('view.dashboard.dashboard')->with([
                    'total_transactions' => $totalTransactions,
                    'total_transactions_sum' => $totalTransactionsSum,
                    'active_tab' => 'dashboard',
                    'user' => $user,
                    'categories_name' => collect($transactionByCategories)->pluck('name')->toArray(),
                    'categories_sum' => collect($transactionByCategories)->pluck('sum')->toArray(),
                    'items_name' => collect($transactionByItems)->pluck('name')->toArray(),
                    'items_sum' => collect($transactionByItems)->pluck('sum')->toArray(),
                    'transactions_sum_by_month' => $transactionsSumByMonth,
                    'transactions_sum_by_month_last_year' => $transactionsSumByMonthLastYear,
                    'transactions_sum_by_month_2_years_ago' => $transactionsSumByMonth2YearAgo,
                    'transactions_color_by_month' => $transactionColorsByMonth,
                    'top_10_categories' => $top10Categories,
                    'top_10_items' => $top10Items,
                    'finances' => $finances,
                    'active_member_count' => $activeMemberCount
                ]);
            } else {
                return redirect('/logout')->withErrors(['mtransactionCategoriesessage' => 'Accès non authorisé']);
            }
        }
        return redirect('/')->withErrors(['message' => 'Accès non authorisé']);
    }

    public function transactions() {
        if(Auth::check()) {
            if(Auth::user()->is_authorized) {
                $user = Auth::user();
                $transactions = Transaction::whereBetween('created_at', [Carbon::today('America/Toronto')->format('Y-m-') ."1 00:00:00", Carbon::today('America/Toronto')->format('Y-m-d')." 23:59:59"])->orderBy('created_at','DESC')->get();
                return view('view.dashboard.transactions')->with([
                    'active_tab' => 'transactions',
                    'user' => $user,
                    'transactions' => $transactions,
                    'transactionsTotalCount' => Transaction::whereBetween('created_at', [Carbon::today('America/Toronto')->format('Y-m-') ."1 00:00:00", Carbon::today('America/Toronto')->format('Y-m-d')." 23:59:59"])->totalCount(),
                ]);
            } else {
                return redirect('/logout')->withErrors(['message' => 'Accès non authorisé']);
            }
        }
        return redirect('/')->withErrors(['message' => 'Accès non authorisé']);
    }

    public function getTransactions($branch, $firstDay, $secondDay) {
        if(Auth::check()) {
            if(Auth::user()->is_authorized) {
                $user = Auth::user();
                $carbonStartDay = new Carbon($firstDay);
                $startDay = $carbonStartDay->format('Y-m-d') . " 07:00:00";
                $carbonEndDay = new Carbon($secondDay);
                $endDay = $carbonEndDay->addDays(1)->format('Y-m-d') ." 06:59:59";
                $transactions = Transaction::whereBetween('created_at', [$startDay, $endDay])->orderBy('created_at','DESC')->get();
                $transactionsTotalCount = Transaction::whereBetween('created_at', [$startDay, $endDay])->totalCount();

                return view('view.dashboard.transactions')->with([
                    'active_tab' => 'transactions',
                    'user' => $user,
                    'transactions' => $transactions,
                    'transactionsTotalCount' => $transactionsTotalCount,
                    'firstDay' => new Carbon($firstDay),
                    'secondDay' => new Carbon($secondDay)
                ]);
            } else {
                return redirect('/logout')->withErrors(['message' => 'Accès non authorisé']);
            }
        }
        return redirect('/')->withErrors(['message' => 'Accès non authorisé']);
    }

    public function getReports($branch, $firstDay, $secondDay) {
        if(Auth::check()) {
            if(Auth::user()->is_authorized) {
                $user = Auth::user();

                $carbonStartDay = new Carbon($firstDay);
                $startDay = $carbonStartDay->format('Y-m-d') . " 07:00:00";
                $carbonEndDay = new Carbon($secondDay);
                $endDay = $carbonEndDay->addDays(1)->format('Y-m-d') ." 06:59:59";

                $transactions = Transaction::whereBetween('created_at', [$startDay, $endDay])->orderBy('created_at','DESC')->get();
                $transactionsTotalCount = Transaction::whereBetween('created_at', [$startDay, $endDay])->where('is_promotion', false)->totalCount();
                $promotionTotalCount = Transaction::whereBetween('created_at', [$startDay, $endDay])->where('is_promotion', true)->totalCount();
                $transactionCategories = Catalog::all()->sortBy('id');
                return view('view.dashboard.reports')->with([
                    'active_tab' => 'transactions',
                    'user' => $user,
                    'transactions' => $transactions,
                    'transactionsTotalCount' => $transactionsTotalCount,
                    'promotionTotalCount' => $promotionTotalCount,
                    'firstDay' => new Carbon($firstDay),
                    'secondDay' => new Carbon($secondDay),
                    'transaction_categories' => $transactionCategories
                ]);
            } else {
                return redirect('/logout')->withErrors(['message' => 'Accès non authorisé']);
            }
        }
        return redirect('/')->withErrors(['message' => 'Accès non authorisé']);
    }

    public function getInventory() {
        $catalog = Catalog::all();
        return view('view.dashboard.inventory')->with([
            'active_tab' => 'inventory',
            'catalog' => $catalog->sortBy('id')
        ]);
    }

    public function items() {
        $catalog = Catalog::all();

        return view('view.dashboard.items')->with([
            'catalog' => $catalog->sortBy('id')
        ]);
    }
    public function memberList() {
        if(Auth::check()) {
            $members = Customer::all()->sortBy('lastname');
            return view('view.dashboard.members_list')->with([
                'active_tab' => 'members',
                'members' => $members
            ]);
        }
        return redirect('/')->withErrors(['message' => 'Accès non authorisé']);
    }
}
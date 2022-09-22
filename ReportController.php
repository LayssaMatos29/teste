<?php

namespace App\Http\Controllers\Dashboardv2\Web;
use Carbon\Carbon;


use App\Models\PagarMe;
use App\Models\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactions = (new PagarMe())->getTransactionsRecipient(tenant_setting_default_dashboard('payee_code'));
        $sortTransactions = [];
        $start1 = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime('-7days')));
        $end1 = Carbon::createFromFormat('Y-m-d', date('Y-m-d'));
        if (!empty(request()->input('filter_transactions'))) {
            $filter = explode('-', request()->input('filter_transactions'));

            $start1 = Carbon::createFromFormat('d/m/Y', trim($filter[0]));
            $end1 = Carbon::createFromFormat('d/m/Y', trim($filter[1]));
        }

        foreach ($transactions as $trans) {
            $date = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($trans->date_created)))->format('Y-m-d');

            if ($date >= $start1->format('Y-m-d') && $date <= $end1->format('Y-m-d')) {
                array_push($sortTransactions, $trans);
            }
        }
        return view('dashboard.reports.transfers.index', compact('sortTransactions', 'start1', 'end1'));
        }  
       
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

<?php

namespace Modules\Payment\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Payment\Modules\Payment;
use Yajra\DataTables\DataTables;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $userNameColumn = config('netcore.module-payment.datatables.user_id');

        if(!$userNameColumn) {
            $userNameColumn = 'name';
        }

        return view('payment::index', compact('userNameColumn'));
    }

    /**
     * Prepare data for jQuery datatable
     *
     * @return mixed
     */
    public function pagination()
    {
        $payments = Payment::with(['user']);

        return Datatables::of($payments)
            ->editColumn('user_id', function ($payment) {
                return $payment->user->first_name;
            })
            /*->editColumn('amount', function ($payment) {
                return $payment->amount . ' ' . get_currency($payment->user->language_iso_code);
            })*/
            ->make(true);
    }
}

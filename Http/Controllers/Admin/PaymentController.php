<?php

namespace Modules\Payment\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Modules\Payment\Modules\Payment;
use Yajra\DataTables\DataTables;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        if (!$userNameColumn = config('netcore.module-payment.datatables.name_column')) {
            $userNameColumn = 'name';
        }

        return view('payment::index', compact('userNameColumn'));
    }

    /**
     * Delete payment from database.
     *
     * @param \Modules\Payment\Modules\Payment $payment
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Payment $payment): JsonResponse
    {
        $payment->delete();

        return response()->json([
            'success' => 'Payment successfully deleted!',
        ]);
    }

    /**
     * Prepare data for DataTable.
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function pagination(): JsonResponse
    {
        $datatable = DataTables::of(
            Payment::with('user')
        );

        // Edit user column, show name.
        $datatable->editColumn('user', function (Payment $payment) {
            if ($route = config('netcore.module-payment.datatables.user_route')) {
                return link_to_route($route, $payment->user->fullName, $payment->user, ['target' => '_blank']);
            }

            return $payment->user->fullName;
        });

        // Add currency to amount.
        $datatable->editColumn('amount', function (Payment $payment) {
            return $payment->amount . ' ' . $payment->currency;
        });

        // Decorate state column.
        $datatable->editColumn('state', function (Payment $payment) {
            if ($payment->state == 'successful') {
                $className = 'text-success';
            } elseif ($payment->state == 'failed') {
                $className = 'text-danger';
            } else {
                $className = 'text-warning';
            }

            return "<span class=\"{$className}\">{$payment->state}</span>";
        });

        // Decorate status column.
        $datatable->editColumn('status', function (Payment $payment) {
            $className = $payment->status == 'active' ? 'text-success' : 'text-danger';

            return "<span class=\"{$className}\">{$payment->status}</span>";
        });

        // Add actions column.
        $datatable->addColumn('actions', function (Payment $payment) {
            return view('payment::tds.actions', compact('payment'))->render();
        });

        // Don't escape columns.
        $datatable->escapeColumns(false);

        return $datatable->make(true);
    }
}

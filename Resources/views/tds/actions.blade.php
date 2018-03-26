@php
    $route = config('netcore.module-payment.datatables.user_route');
@endphp

@if($route)
    <a href="{{ route($route, $payment->user) }}" class="btn btn-xs btn-primary" target="_blank">
        <i class="fa fa-user"></i> Show user
    </a>
@endif

@if($payment->isDeletable())
    <button class="btn btn-xs btn-danger delete-payment" data-route="{{ route('admin::payment.destroy', $payment) }}">
        <i class="fa fa-trash"></i> Delete
    </button>
@else
    <button class="btn btn-xs btn-danger" disabled>
        <i class="fa fa-trash"></i> Delete
    </button>
@endif
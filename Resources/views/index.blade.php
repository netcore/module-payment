@extends('admin::layouts.master')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">Payments</h4>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-bordered datatable">
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>User</th>
                                <th>Amount</th>
                                <th>State</th>
                                <th>Status</th>
                                <th>Method</th>
                                <th>Created</th>
                                <th>Updated</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            var dataTable = $('.datatable').dataTable({
                processing: true,
                serverSide: true,
                ajax: '/admin/payment/pagination',
                responsive: true,
                order: [[0, 'desc']],
                columns: [
                    {data: 'id', name: 'id', orderable: true, searchable: true},
                    {data: 'user', name: 'user.{{ $userNameColumn }}', orderable: true, searchable: true},
                    {data: 'amount', name: 'amount', orderable: true, searchable: true},
                    {data: 'state', name: 'state', orderable: true, searchable: true},
                    {data: 'status', name: 'status', orderable: true, searchable: true},
                    {data: 'method', name: 'method', orderable: true, searchable: true},
                    {data: 'created_at', name: 'created_at', orderable: true, searchable: true},
                    {data: 'updated_at', name: 'updated_at', orderable: true, searchable: true},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-right'}
                ]
            });

            dataTable.on('click', '.delete-payment', function (e) {
                var button = $(e.target);

                let successHandler = function () {
                    button.data('loading-text', '<i class="fa fa-spinner fa-spin"></i> Deleting...');
                    button.button('loading');

                    $.post(button.data('route'), {_method: 'DELETE'}, function (res) {
                        dataTable.fnDraw();

                        if (res.success) {
                            $.growl.success({
                                message: res.success
                            });
                        }
                    }).fail(function (err) {
                        $.growl.error({
                            message: 'Whoops, something went wrong..'
                        });
                    });
                };

                swal({
                    title: 'Are you sure?',
                    text: 'Once deleted, you will not be able to recover this imaginary file!',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Yes, delete it!'
                })
                    .then(successHandler)
                    .catch($.noop);
            });
        });
    </script>
@endsection
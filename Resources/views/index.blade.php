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
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            var dataTable = $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/admin/payment/pagination',
                responsive: true,
                order: [[0, "desc"]],
                columns: [
                    { data: 'id', name: 'id', orderable: true, searchable: true },
                    { data: 'user_id', name: 'user.{{ $userNameColumn }}', orderable: true, searchable: true },
                    { data: 'amount', name: 'amount', orderable: true, searchable: true },
                    { data: 'state', name: 'state', orderable: true, searchable: true },
                    { data: 'status', name: 'status', orderable: true, searchable: true },
                    { data: 'method', name: 'method', orderable: true, searchable: true },
                    { data: 'created_at', name: 'created_at', orderable: true, searchable: true }, // Display 'created_at' timestamp, but order by ID
                    { data: 'updated_at', name: 'updated_at', orderable: true, searchable: true }, // Display 'created_at' timestamp, but order by ID

                ],
            });
        });

    </script>
@endsection
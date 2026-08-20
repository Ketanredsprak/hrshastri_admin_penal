@extends('layouts.admin')

@section('page-title')
    {{ __("Manage Plan Order") }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __("Home") }}</a></li>
    <li class="breadcrumb-item">{{ __("Plan Order") }}</li>
@endsection
@section('content')
<div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    {{-- <h5></h5> --}}
                    <div class="table-responsive">
                        <table class="table data-table " id="pc-dt-simple12">
                            <thead>
                                <tr>
                                    <th>{{ __('Order Id') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Plan Name') }}</th>
                                    <th>{{ __('Price') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Coupon') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Invoice') }}</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script type="text/javascript">
  $(function () {
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        language: {
                    processing: '<i></i><span class="text-primary spinner-border"></span> '},
            ajax: "{{ route('order.index') }}",
            columns: [
                {data: 'order_id', name: 'order_id'},
                {data: 'user_name', name: 'user_name'},
                {data: 'plan_name', name: 'plan_name'},
                {data: 'price', name: 'price'},
                {data: 'payment_status', name: 'payment_status'},
                {data: 'created_at', name: 'created_at'},
                {data: 'total_coupon_used', name: 'total_coupon_used'},
                {data: 'payment_type', name: 'payment_type'},
                {data: 'receipt', name: 'receipt'},
            ]
        });
    });
</script>

@endsection

@extends('layouts.admin')
@push('script-page')
    <script>
        $(document).on('click', '.code', function() {
            var type = $(this).val();
            if (type == 'manual') {
                $('#manual').removeClass('d-none');
                $('#manual').addClass('d-block');
                $('#auto').removeClass('d-block');
                $('#auto').addClass('d-none');
            } else {
                $('#auto').removeClass('d-none');
                $('#auto').addClass('d-block');
                $('#manual').removeClass('d-block');
                $('#manual').addClass('d-none');
            }
        });

        $(document).on('click', '#code-generate', function() {
            var length = 10;
            var result = '';
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            var charactersLength = characters.length;
            for (var i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            $('#auto-code').val(result);
        });
    </script>
@endpush
@section('page-title')
    {{ __('Manage Coupon') }}
@endsection

@section('action-button')
    @can('create coupon')
        <a href="#" data-url="{{ route('coupons.create') }}" data-ajax-popup="true"
            data-title="{{ __('Create New Coupon') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
            data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Coupon List') }}</li>
@endsection
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-body table-border-style">
                {{-- <h5></h5> --}}
                <div class="table-responsive">
                    <table class="table data-table" id="pc-dt-simple12">
                        <thead>
                            <tr>
                                <th> {{ __('Name') }}</th>
                                <th> {{ __('Code') }}</th>
                                <th> {{ __('Discount (%)') }}</th>
                                <th> {{ __('Limit') }}</th>
                                <th> {{ __('Used') }}</th>
                                <th width="200px"> {{ __('Action') }}</th>
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
        ajax: "{{ route('coupons.index') }}",
        columns: [
            {data: 'name', name: 'name'},
            {data: 'code', name: 'code'},
            {data: 'discount', name: 'discount'},
            {data: 'limit', name: 'limit'},
            {data: 'used_coupon', name: 'used_coupon', orderable: false, searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

  });
</script>

@endsection

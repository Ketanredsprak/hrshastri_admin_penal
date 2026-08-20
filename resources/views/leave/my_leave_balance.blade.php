@extends('layouts.admin')

@section('page-title')
    {{ __('My Leave Balance') }}
@endsection

@if (\Auth::user()->type != 'company')
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
        <li class="breadcrumb-item">{{ __('Leave ') }}</li>
    @endsection
@endif
@push('script-page')
    <script type="text/javascript" src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>
    <script>
        var filename = $('#filename').val();

        function saveAsPDF() {
            var element = document.getElementById('printableArea');
            var opt = {
                margin: 0.3,
                filename: filename,
                image: {
                    type: 'jpeg',
                    quality: 1
                },
                html2canvas: {
                    scale: 4,
                    dpi: 72,
                    letterRendering: true
                },
                jsPDF: {
                    unit: 'in',
                    format: 'A4'
                }
            };
            html2pdf().set(opt).from(element).save();
        }
    </script>
    <script>
        $('input[name="type"]:radio').on('change', function(e) {
            var type = $(this).val();
            if (type == 'monthly') {
                $('.month').addClass('d-block');
                $('.month').removeClass('d-none');
                $('.year').addClass('d-none');
                $('.year').removeClass('d-block');
            } else {
                $('.year').addClass('d-block');
                $('.year').removeClass('d-none');
                $('.month').addClass('d-none');
                $('.month').removeClass('d-block');
            }
        });

        $('input[name="type"]:radio:checked').trigger('change');
    </script>
    <style>
        select#branch {
            width: 100% !important;
        }
    </style>
@endpush
@section('content')
    <div class="row">
        <div class="col-sm-12 col-lg-12 col-xl-12 col-md-12">
            <div class=" mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(['route' => ['leave.my_leave_balance'], 'method' => 'get', 'id' => 'report_leave']) }}

                        <div class="row align-items-center">
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-sm-6 col-lg-3 Month">
                                        <div class="btn-box">
                                            {{ Form::label('monthAndYear', __('Month'), ['class' => 'form-label']) }}
                                            <input type="month" class="form-control" id="monthAndYear" name="monthAndYear"
                                                value="{{ @$monthAndYear ?? date('Y-m') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 text-right pt-4">
                                <a href="#" class="btn btn-sm btn-primary"
                                    onclick="document.getElementById('report_leave').submit(); return false;"
                                    data-bs-toggle="tooltip" title="{{ __('Apply') }}"
                                    data-original-title="{{ __('apply') }}">
                                    <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                </a>
                                <a href="{{ route('leave.my_leave_balance') }}" class="btn btn-sm btn-danger "
                                    data-bs-toggle="tooltip" title="{{ __('Reset') }}"
                                    data-original-title="{{ __('Reset') }}">
                                    <span class="btn-inner--icon"><i class="ti ti-refresh text-white-off "></i></span>
                                </a>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table" id="pc-dt-simple">
                                <thead>
                                    <tr>
                                        <th>{{ __('Leave Type') }}</th>
                                        {{-- <th>{{ __('Year') }}</th> --}}
                                        {{-- <th>{{ __('Assigned Leave') }}</th> --}}
                                        <th>{{ __('Approved Leaves') }}</th>
                                        <th>{{ __('Balance Leave') }}</th>
                                    </tr>
                                </thead>
                                <?php $total_leave_data = 0; ?>
                                <tbody>
                                    @foreach ($leave_counts as $leave)
                                        <tr>
                                            <td>{{ $leave->title }} </td>
                                            {{-- <td>{{ date('Y') }} </td> --}}
                                            {{-- <td> <span class="badge bg-info p-2 px-3 rounded status-badge5">{{ $leave->days }} --}}
                                            </span></td>
                                            <td> <span class="badge bg-danger p-2 px-3 rounded status-badge5">
                                                    {{ formatNumber($leave->applied_leaves) }}
                                                </span></td>
                                            <td> <span class="badge bg-success p-2 px-3 rounded status-badge5">
                                                    {{ formatNumber($leave->used_leaved) }}
                                                </span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

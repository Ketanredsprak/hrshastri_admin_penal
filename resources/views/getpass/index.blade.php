@extends('layouts.admin')

@section('page-title')
    {{ __('Gate pass') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Gate pass') }}</li>
@endsection

@section('action-button')
    @if (\Auth::user()->type != 'employee')
        @can('Create Gate Pass')
            <a href="#" data-url="{{ route('getpass.create') }}" data-ajax-popup="true"
                data-title="{{ __('Create Gate pass') }}" data-size="lg" data-bs-toggle="tooltip" title=""
                class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Gate pass') }}">
                <i class="ti ti-plus"></i>
            </a>
        @endcan
    @else
        <a href="#" data-url="{{ route('getpass.create') }}" data-ajax-popup="true"
            data-title="{{ __('Create Gate pass') }}" data-size="lg" data-bs-toggle="tooltip" title=""
            class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Gate pass') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endif
@endsection

@section('content')
    <div class="row">
        @php
            $pendingCount = $getpass->where('status', 0)->count();
            $approvedCount = $getpass->where('status', 1)->count();
            $rejectedCount = $getpass->where('status', 2)->count();
        @endphp
        <div class="col-xxl-4 col-md-4 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mb-3 mb-sm-0">
                            <div class="d-flex align-items-center">
                                <div class="theme-avtar bg-warning">
                                    <i class="ti ti-circle-minus"></i>
                                    <i class="fa-solid fa-lights-holiday"></i>
                                </div>
                                <div class="ms-3">
                                    <small class="text-muted">{{ __('Total') }}</small>
                                    <h6 class="m-0">{{ __('Pending') }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto text-end">
                            <h4 class="m-0 text-warning">{{ $pendingCount }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-4 col-md-4 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mb-3 mb-sm-0">
                            <div class="d-flex align-items-center">
                                <div class="theme-avtar bg-success">
                                    <i class="ti ti-circle-check"></i>
                                </div>
                                <div class="ms-3">
                                    <small class="text-muted">{{ __('Total') }}</small>
                                    <h6 class="m-0">{{ __('Approved') }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto text-end">
                            <h4 class="m-0 text-success">{{ $approvedCount }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-4 col-md-4 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mb-3 mb-sm-0">
                            <div class="d-flex align-items-center">
                                <div class="theme-avtar bg-danger">
                                    <i class="ti ti-circle-x"></i>
                                </div>
                                <div class="ms-3">
                                    <small class="text-muted">{{ __('Total') }}</small>
                                    <h6 class="m-0">{{ __('Rejected') }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto text-end">
                            <h4 class="m-0 text-danger">{{ $rejectedCount }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    <th>{{ __('Created At') }}</th>
                                    <th>{{ __('Entry Date') }}</th>
                                    <th>{{ __('Authorized Person Name') }}</th>
                                    <th>{{ __('Approved / Rejected Date & Time') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Out Time') }}</th>
                                    <th>{{ __('In Time') }}</th>
                                    <th>{{ __('Reason') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th width="200px">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <?php $total_leave_data = 0; ?>
                            <tbody>
                                @foreach ($getpass as $pass)
                                    <tr>
                                        <td>{{ \Auth::user()->dateFormat($pass->getRawOriginal('created_at')) }}</td>
                                        <td>{{ $pass->date }}</td>
                                        <td>{{ $pass->approvedBy->name ?? '-' }}</td>
                                        <td>{{ !empty($pass->approved_at) ? $pass->approved_at : '-' }}</td>
                                        <td>
                                            @if ($pass->name == 1)
                                                {{ __('Personal work') }}
                                            @else
                                                {{ __('Office Work') }}
                                            @endif
                                        </td>
                                        <td>{{ $pass->out_time }}</td>
                                        <td>{{ $pass->in_time }}</td>
                                        <td>{{ substr($pass->resone, 0, 80) . '..' }}</td>
                                        <td>
                                            @if ($pass->status == 0)
                                                <div class="badge bg-warning p-2 px-3 rounded status-badge5">
                                                    {{ __('Pending') }}</div>
                                            @elseif($pass->status == 1)
                                                <div class="badge bg-success p-2 px-3 rounded status-badge5">
                                                    {{ __('Approved') }}</div>
                                            @elseif($pass->status == 2)
                                                <div class="badge bg-danger p-2 px-3 rounded status-badge5">
                                                    {{ __('Rejected') }}</div>
                                            @endif
                                        </td>
                                        <td class="Action">
                                            <span>
                                                @if ($pass->status == 0)
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                            data-size="lg"
                                                            data-url="{{ URL::to('getpass/' . $pass->id . '/edit') }}"
                                                            data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                            title="" data-title="{{ __('Edit Gate Pass') }}"
                                                            data-bs-original-title="{{ __('Edit') }}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>

                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open([
                                                            'method' => 'DELETE',
                                                            'route' => ['getpass.destroy', $pass->id],
                                                            'id' => 'delete-form-' . $pass->id,
                                                        ]) !!}
                                                        <a href="#"
                                                            class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                            data-bs-toggle="tooltip" title=""
                                                            data-bs-original-title="Delete" aria-label="Delete"><i
                                                                class="ti ti-trash text-white text-white"></i></a>
                                                        </form>
                                                    </div>
                                                @endif
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

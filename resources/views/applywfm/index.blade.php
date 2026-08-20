@extends('layouts.admin')

@section('page-title')
    {{ __('Apply wfh') }}
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Apply wfh') }}</li>
@endsection

@section('action-button')
    @if (\Auth::user()->type != 'employee')
        @can('Create Apply WFH')
            <a href="#" data-url="{{ route('applywfm.create') }}" data-ajax-popup="true"
                data-title="{{ __('Create Apply wfh') }}" data-size="lg" data-bs-toggle="tooltip" title=""
                class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Apply wfh') }}">
                <i class="ti ti-plus"></i>
            </a>
        @endcan
    @else
        <a href="#" data-url="{{ route('applywfm.create') }}" data-ajax-popup="true"
            data-title="{{ __('Create Apply wfh') }}" data-size="lg" data-bs-toggle="tooltip" title=""
            class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Apply wfh') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endif
@endsection

@section('content')
    <div class="row">
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
                            <h4 class="m-0 text-warning">{{ applywfh_pending() }}</h4>
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
                            <h4 class="m-0 text-success">{{ applywfh_approved() }}</h4>
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
                            <h4 class="m-0 text-danger">{{ applywfh_rejected() }}</h4>
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
                                    {{-- <th>{{ __('Employee') }}</th> --}}
                                    <th>{{ __('Start Date') }}</th>
                                    <th>{{ __('End Date') }}</th>
                                    <th>{{ __('Note') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th width="200px">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <?php $total_leave_data = 0; ?>
                            <tbody>
                                @foreach ($getpass as $pass)
                                    <tr>
                                        {{-- <td>
									@php 
										$data = App\Models\User::find($pass->employee_id);
									@endphp 
										{{ $data->name }}
								</td> --}}
                                        <td>{{ \Auth::user()->dateFormat($pass->start_date) }}</td>
                                        <td>{{ \Auth::user()->dateFormat($pass->end_date) }}</td>
                                        <td>{{ $pass->note }}</td>
                                        <td>
                                            @if ($pass->status == 0)
                                                <div class="badge bg-warning p-2 px-3 rounded status-badge5">
                                                    Pending</div>
                                            @elseif($pass->status == 1)
                                                <div class="badge bg-success p-2 px-3 rounded status-badge5">
                                                    Approved</div>
                                            @elseif($pass->status == 2)
                                                <div class="badge bg-danger p-2 px-3 rounded status-badge5">
                                                    Reject</div>
                                            @endif
                                        </td>
                                        <td class="Action">
                                            <span>


                                                @if ($pass->status == 0)
                                                    @if (\Auth::user()->type != 'employee')
                                                        @can('Edit Apply WFH')
                                                            <div class="action-btn bg-info ms-2">
                                                                <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                                    data-size="lg"
                                                                    data-url="{{ URL::to('applywfm/' . $pass->id . '/edit') }}"
                                                                    data-ajax-popup="true" data-size="md"
                                                                    data-bs-toggle="tooltip" title=""
                                                                    data-title="{{ __('Edit WFH') }}"
                                                                    data-bs-original-title="{{ __('Edit') }}">
                                                                    <i class="ti ti-pencil text-white"></i>
                                                                </a>
                                                            </div>
                                                        @endcan
                                                        @can('Delete Apply WFH')
                                                            <div class="action-btn bg-danger ms-2">
                                                                {!! Form::open([
                                                                    'method' => 'DELETE',
                                                                    'route' => ['applywfm.destroy', $pass->id],
                                                                    'id' => 'delete-form-' . $pass->id,
                                                                ]) !!}
                                                                <a href="#"
                                                                    class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                                    data-bs-toggle="tooltip" title=""
                                                                    data-bs-original-title="Delete" aria-label="Delete"><i
                                                                        class="ti ti-trash text-white text-white"></i></a>
                                                                </form>
                                                            </div>
                                                        @endcan
                                                    @else
                                                        <div class="action-btn bg-info ms-2">
                                                            <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                                data-size="lg"
                                                                data-url="{{ URL::to('applywfm/' . $pass->id . '/edit') }}"
                                                                data-ajax-popup="true" data-size="md"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-title="{{ __('Edit WFH') }}"
                                                                data-bs-original-title="{{ __('Edit') }}">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                        </div>



                                                        <div class="action-btn bg-danger ms-2">
                                                            {!! Form::open([
                                                                'method' => 'DELETE',
                                                                'route' => ['applywfm.destroy', $pass->id],
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

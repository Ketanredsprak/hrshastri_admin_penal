@extends('layouts.admin')
@section('page-title')
    {{ __('Reference List') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Reference List') }}</li>
@endsection


@section('content')
<div class="row">

    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-body table-border-style">
                <div class="table-responsive">
                    <table class="table" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th>{{ __('id') }}</th>
                                <th>{{ __('Job title') }}</th>
                                <th>{{ __('Document') }}</th>
                                <th>{{ __('Status') }}</th>
                                @if (Gate::check('Edit Job') || Gate::check('Delete Job') || Gate::check('Show Job'))
                                    <th width="200px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            
                            @foreach ($data as $key=>$job)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>
                                    @if(!empty($job->jobsData))
                                        {{ $job->jobsData->title }}
                                    @endif
                                </td>
                                <td>
                                    @if (!empty($job->refence_file))
                                    <div class="action-btn bg-primary ms-2">
                                        <a  class="mx-3 btn btn-sm align-items-center" href="{{ asset('referenceDoc/'.$job->refence_file)}}" download="">
                                            <i class="ti ti-download text-white"></i>
                                        </a>
                                    </div>
                                        <div class="action-btn bg-secondary ms-2">
                                            <a class="mx-3 btn btn-sm align-items-center" href="{{ asset('referenceDoc/'.$job->refence_file)}}" target="_blank"  >
                                                <i class="ti ti-crosshair text-white" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Preview') }}"></i>
                                            </a>
                                        </div>
                                    @else
                                        <p>-</p>
                                    @endif
                                </td>
                                <td>
                                    @if ($job->status == 0)
                                        <span class="badge bg-warning p-2 px-3 rounded status-badge">Pennding</span>
                                    @elseif($job->status == 1)
                                        <span class="badge bg-success p-2 px-3 rounded status-badge">Approved</span>
                                    @elseif($job->status == 2)
                                        <span class="badge bg-danger p-2 px-3 rounded status-badge">Reject</span>
                                    @else
                                        <span class="badge bg-dark p-2 px-3 rounded status-badge">Hired</span>
                                    @endif
                                </td>
                                
                                <td class="Action">
                                    <span>  
                                        @can('Show Job')
                                            <div class="action-btn bg-success ms-2">
                                                <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                    data-size="lg"
                                                    data-url="{{ URL::to('reference_action/' . $job->id.'') }}"
                                                    data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                    title="" data-title="{{ __('Reference Action') }}"
                                                    data-bs-original-title="{{ __('Status Reference') }}">
                                                    <i class="ti ti-caret-right text-white"></i>
                                                </a>
                                            </div>

                                        @endcan

                                        @can('Show Job')

                                        <div class="action-btn bg-danger ms-2">
                                            {!! Form::open(['method' => 'POST', 'route' => ['job_reference_delete', $job->id], 'id' => 'delete-form-' . $job->id]) !!}
                                            <a href="#!" class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                title="{{ __('Delete') }}">
                                                <i class="ti ti-trash text-white"></i></a>
                                            {!! Form::close() !!}
                                        </div>

                                        
                                    @endcan

                                        
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

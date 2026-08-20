@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Job') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Manage Job') }}</li>
@endsection


@push('script-page')
    <script>
        $('.copy_link').click(function(e) {
            e.preventDefault();
            var copyText = $(this).attr('href');

            document.addEventListener('copy', function(e) {
                e.clipboardData.setData('text/plain', copyText);
                e.preventDefault();
            }, true);

            document.execCommand('copy');
            show_toastr('Success', 'Url copied to clipboard', 'success');
        });
    </script>
@endpush
@section('action-button')
   
    @can('Create Job')
        <a href="{{ route('job.create') }}"  data-ajax-popup="true" data-size="md"
            data-title="{{ __('Create New Job') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
            data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
@endsection
@section('content')
<div class="row">

  




    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-body table-border-style">
                {{-- <h5> </h5> --}}
                <div class="table-responsive">
                    <table class="table" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th>{{ __('SR No.') }}</th>
                                <th>{{ __('Title') }}</th>
                                <th>{{ __('No. of opening') }}</th>
                                <th>{{ __('Skills') }}</th>
                                <th>{{ __('Branch') }}</th>
                                <th width="200px">{{ __('Apply') }}</th>
                                <th width="200px">{{ __('Reference') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                           
                        @foreach ($jobs as  $key => $job)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <th scope="row">
                                                    {{--<div>
                                                        <span class="avatar bg-primary text-white mr-4"
                                                            title="{{ __('Job Position') }}">{{ $job->position }}</span>
                                                    </div> --}}
                                                    <div class="media-body">
                                                        <a href="{{ route('job.requirement', [$job->code, !empty($job) ? (!empty($job->createdBy->lang) ? $job->createdBy->lang : 'en') : 'en']) }}"
                                                           >{{ $job->title }} </a>
                                                    </div>
                                            </th>
                                            <td>{{ $job->position }} </td>
                                            <td>

                                                @foreach (explode(',', $job->skill) as $skill)
                                                    <span class="badge bg-primary p-2 px-3 rounded text-white">{{ $skill }}</span>
                                                @endforeach
                                            </td>

                                            <td><span
                                                    class="h6">{{!empty($job->branches)?$job->branches->name:'-'}}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('job.requirement', [$job->code, !empty($job) ? (!empty($job->createdBy->lang) ? $job->createdBy->lang : 'en') : 'en']) }}" class="btn btn-primary btn-sm">Apply</a>
                                            </td>

                                            <td>
                                                <div class="action-btn bg-info ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                        data-size="lg"
                                                        data-url="{{ URL::to('job_reference/'.$job->id.'') }}"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                        title="" data-title="{{ __('Job Reference') }}"
                                                        data-bs-original-title="{{ __('Job Reference') }}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
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

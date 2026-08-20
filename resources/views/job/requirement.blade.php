@extends('layouts.admin')


@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Manage Job') }}</li>
@endsection



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

  




    <div class="col-sm-12">
        <div class="card">
            <div class="card-header card-body table-border-style">
                {{-- <h5> </h5> --}}
                <section class="slice slice-xl" data-offset-top="#header-main">
            <div class="row ">
                <div class="col-lg-8 col-sm-6">
                   <!-- <h6 class="text-sm text-uppercase ls-2 text-muted font-weight-700">{{__('Careers')}}</h6>
                    <h2 class="h1 mb-4">{{__('Job openings')}}</h2>   
                                     <p class="lead lh-180">{{$job->title}}</p>
-->
                    
                      <h2 class="h1 mb-4">{{$job->title}}</h2>
                    <p class="lead lh-180">
                        @foreach(explode(',',$job->skill) as $skill)
                            <span class="badge bg-primary p-2 px-3 rounded"> {{$skill}}</span>

                        @endforeach
                    </p>
                     </div>
                       <div class="col-lg-4 col-sm-6 text-right">
                    <p class="lead lh-180"><b>{{$job->position}} {{__('Position')}}</b></p>
                    <p class="lead lh-180"><i class="ti ti-map-marker-alt"></i> {{!empty($job->branches)?$job->branches->name:''}} </p>
                    
               
                
      @if($job->end_date >= date('Y-m-d'))
            <a href="{{route('job.apply',[$job->code,$currantLang])}}" class="btn btn-primary  mb-3">
                <span class="btn-inner--icon"><i class="ti ti-angle-right"></i></span>
                <span class="btn-inner--text">{{__('Apply now')}}</span>
            </a>
             @endif
        </div>
        </div>
   
   <div class="row ">
   <div class="col-lg-12">
                    <div class="card cardborder mb-0">
                        <div class="card-body ">
                           <h4 class="mb-0">{{__('Description')}}</h4>
                            {!! $job->description !!}
                              <h4 class="mb-0">{{__('Requirements')}}</h4>{!! $job->requirement !!}
                            
                            
                        </div>
                        
                    </div>
                    
                    </div>
                    </div>
       
    </section>
    
    
                
            </div>
        </div>
    </div>
</div>
@endsection

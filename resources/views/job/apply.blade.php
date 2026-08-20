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
<a href="{{ route('job.create') }}" data-ajax-popup="true" data-size="md" data-title="{{ __('Create New Job') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
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
                <section class="slice " data-offset-top="#header-main">
                    <div class="container pt-6">
                        <div class="row row-grid justify-content-center">
                            <div class="col-lg-8 text-center">

                                {{-- <div class="col-lg-10"> --}}
                                <h2 class="h1 mb-4">{{$job->title}}</h2>
                                <p class="lead lh-180 text-muted">
                                    @foreach(explode(',',$job->skill) as $skill)
                                    <span class="badge bg-primary p-2 px-3 rounded"> {{$skill}}</span>
                                    @endforeach
                                </p>

                                <!-- <p class="lead lh-180"><i class="fa fa-map-marker-alt"></i> {{!empty($job->branches)?$job->branches->name:''}} </p> -->
                                @if (!empty($job->branches) && !empty($job->branches->name))
                                <p class="lead lh-180"><i class="fa fa-map-marker-alt"></i> {{ $job->branches->name }}</p>
                                @endif


                                {{-- </div> --}}
                            </div>
                        </div>
                    </div>
                </section>





                <section class="slice slice-lg ">
                    <div class="container">
                        <div class="mb-5 text-center">
                            <h3 class=" mt-4">{{__('Apply for this job')}}</h3>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                {{Form::open(array('route'=>array('job.apply.data',$job->code),'method'=>'post', 'enctype' => "multipart/form-data"))}}
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        {{Form::label('name',__('Name'),['class'=>'form-label'])}} <span class="text-danger">*</span>
                                        {{Form::text('name',null,array('class'=>'form-control name','required'=>'required'))}}
                                    </div>
                                    <div class="form-group col-md-6">
                                        {{Form::label('email',__('Email'),['class'=>'form-label'])}} <span class="text-danger">*</span>
                                        {{Form::text('email',null,array('class'=>'form-control','required'=>'required'))}}
                                    </div>
                                    <div class="form-group col-md-6">
                                        {{Form::label('phone',__('Phone'),['class'=>'form-label'])}} <span class="text-danger">*</span>
                                        {{Form::text('phone',null,array('class'=>'form-control','required'=>'required'))}}
                                    </div>
                                    @if(!empty($job->applicant) && in_array('dob',explode(',',$job->applicant)))
                                    <div class="form-group col-md-6 ">
                                        {!! Form::label('dob', __('Date of Birth'),['class'=>'form-label']) !!}
                                        {!! Form::date('dob', old('dob'), ['class' => 'form-control ','required'=>'required']) !!}
                                    </div>
                                    @endif
                                    @if(!empty($job->applicant) && in_array('gender',explode(',',$job->applicant)))
                                    <div class="form-group col-md-6 ">
                                        {!! Form::label('gender', __('Gender'),['class'=>'form-label']) !!}
                                        <div class="d-flex radio-check">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="g_male" value="Male" name="gender" class="custom-control-input">
                                                <label class="custom-control-label" for="g_male">{{__('Male')}}</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="g_female" value="Female" name="gender" class="custom-control-input">
                                                <label class="custom-control-label" for="g_female">{{__('Female')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    @if(!empty($job->applicant) && in_array('address',explode(',',$job->applicant)))
                                    <div class="form-group col-md-12 ">
                                        {{ Form::label('address', __('Address'), ['class' => 'form-label']) }}
                                        {{ Form::textarea('address', null, ['class' => 'form-control' ,'placeholder'=>'Enter address','rows'=>'3']) }}
                                    </div>
                                    <div class="form-group col-md-6 address">
                                        {{Form::label('city',__('City'),['class'=>'form-label'])}}
                                        {{Form::text('city',null,array('class'=>'form-control','required'=>'required'))}}
                                    </div>
                                    <div class="form-group col-md-6 address">
                                        {{Form::label('state',__('State'),['class'=>'form-label'])}}
                                        {{Form::text('state',null,array('class'=>'form-control','required'=>'required'))}}
                                    </div>
                                    <div class="form-group col-md-6 address ">
                                        {{Form::label('country',__('Country'),['class'=>'form-label'])}}
                                        {{Form::text('country',null,array('class'=>'form-control','required'=>'required'))}}
                                    </div>
                                    <div class="form-group col-md-6 address">
                                        {{ Form::label('zip_code', __('Zip Code'), ['class' => 'form-label']) }}
                                        {{ Form::text('zip_code', null, ['class' => 'form-control' ,'placeholder'=>'Enter zip code']) }}
                                    </div>
                                    @endif

                                    @if(!empty($job->visibility) && in_array('profile',explode(',',$job->visibility)))
                                    <div class="form-group col-md-6">
                                        {{Form::label('profile',__('Profile Picture'),['class'=>'form-label'])}}
                                        <div class="choose-file form-group">
                                            <label for="profile" class="form-label">
                                                {{-- <div>{{__('Choose file here')}}
                                        </div> --}}
                                        <input type="file" class="form-control" name="profile" id="profile" data-filename="profile_create" onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">

                                        <img id="blah" src="" width="100" />
                                        </label>
                                        <p class="profile_create"></p>
                                    </div>
                                </div>
                                @endif

                                @if(!empty($job->visibility) && in_array('resume',explode(',',$job->visibility)))
                                <div class="form-group col-md-6  ">
                                    {{Form::label('resume',__('CV / Resume'),['class'=>'form-label'])}}
                                    <div class="choose-file form-group">
                                        <label for="resume" class="form-label">
                                            {{-- <div>{{__('Choose file here')}}
                                    </div> --}}
                                    <input type="file" class="form-control" name="resume" id="resume" data-filename="resume_create" onchange="document.getElementById('blah1').src = window.URL.createObjectURL(this.files[0])">

                                    <img id="blah1" width="100" src="" />
                                    </label>
                                    <p class="resume_create"></p>
                                </div>
                            </div>
                            @endif
                            @if(!empty($job->visibility) && in_array('letter',explode(',',$job->visibility)))
                            <div class="form-group col-md-12 ">
                                {{Form::label('cover_letter',__('Cover Letter'),['class'=>'form-label'])}}
                                {{Form::textarea('cover_letter',null,array('class'=>'form-control','rows'=>'3'))}}
                            </div>
                            @endif
                            @foreach($questions as $question)
                            <div class="form-group col-md-12  question question_{{$question->id}}">
                                {{Form::label($question->question,$question->question,['class'=>'form-label'])}}
                                <input type="text" class="form-control" name="question[{{$question->question}}]" {{($question->is_required=='yes')?'required':''}}>
                            </div>
                            @endforeach
                            <div class="col-12">
                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-primary">{{__('Submit your application')}}</button>
                                </div>
                            </div>
                        </div>
                        {{Form::close()}}

                    </div>
            </div>
        </div>
        </section>
    </div>
</div>
</div>
</div>
@endsection

@extends('layouts.admin')
@section('page-title')
    {{ __('Create Visit Form') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ url('employee') }}">{{ __('Visit Form') }}</a></li>
    <li class="breadcrumb-item">{{ __('Visit Form') }}</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            {{ Form::open(['route' => ['my_claim.store'], 'method' => 'post', 'id' => 'my_claim_store', 'enctype' => 'multipart/form-data']) }}
            <div class="card">
                <div class="card-body">
                    <hr>
                    
                    <div class="tab-content tab_ctn" id="nav-tabContent">
                        <div class="tab-pane fade active show" id="nav-Salary" role="tabpanel"
                            aria-labelledby="nav-Salary-tab">
                            <div
                                class="card                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ">
                                <div class="card-header">
                                    <h5>{{ __('Personal Detail') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">

                                        <div class="form-group col-lg-6 col-md-6">
                                            {{ Form::label('grade_type', __('visit Type'), ['class' => 'col-form-label']) }}<span class="text-danger"> *</span>
                                            <select id="visit_type" name="visit_type" class="form-control" onchange="getVisitType(this.value)">
                                                <option value="">Select visit type</option>
                                                    <option value="1">In City</option>
                                                    <option value="2">Out City</option>
                                             </select>
                                        </div>
                                        
                                        <div class="form-group col-lg-6 col-md-6">
                                            {{ Form::label('grade_type', __('Assign Grade'), ['class' => 'col-form-label']) }}<span class="text-danger"> *</span>
                                            <select id="grade_type" name="grade_type" class="form-control" onchange="getGradeType(this.value)">
                                                <option value="">Select Assign Type</option>
                                                
                                            </select>
                                        </div>


                                        <div class="form-group col-lg-6 col-md-6"> 
                                            {{ Form::label('category_type', __('Commuting Type'), ['class' => 'col-form-label']) }}<span class="text-danger"> *</span>
                                            <select id="commuting_type" name="commuting_type" class="form-control" onchange="getCommutingType(this.value)">
                                                <option value="">Select commuting type</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-6 col-md-6">
                                            {{ Form::label('city', __('City'), ['class' => 'col-form-label']) }}
                                            <select id="city_id" name="city_id" class="form-control">
                                                <option value="">Select City</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-6 col-md-6">
                                            {{ Form::label('city', __('Expenses'), ['class' => 'col-form-label']) }}
                                            <select id="getExpensesdData" name="getExpensesdData[]" class="form-control" multiple>
                                                <option value="">Select Expenses</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            {{ Form::label('amount', __('Amount'), ['class' => 'col-form-label']) }}
                                            {{ Form::number('amount', null, ['class' => 'form-control', 'placeholder' => __('Enter amount')]) }}
                                        </div>
                                        <div class="form-group col-lg-6">
                                            {{ Form::label('amount', __('Upload Image'), ['class' => 'col-form-label']) }}
                                            {{ Form::file('image', null, ['class' => 'form-control', 'placeholder' => __('Enter amount')]) }}
                                        </div>
                                        <div class="form-group col-lg-6">
                                            {{ Form::label('description', __('Description'), ['class' => 'col-form-label']) }}
                                            {{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => __('Enter Description'),'rows'=>'3']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <button type="submit" class="btn  btn-primary">{{ 'Submit' }}</button>
                                <a href="{{ route('my_claim.index')}}" class="btn  btn-light">{{ 'Cencal' }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
@endsection
<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
@push('script-page')
<script>
    //getVisitType
    function getVisitType(getVisitType) {
        $('#grade_type').html('');
        $('#grade_type').append('<option value="">Select Grade type</option>');

        $('#commuting_type').html('');
        $('#commuting_type').append('<option value="">Select commuting type</option>');
            $.ajax({
                type: "GET",
                url: "{{url('get_getGradeType')}}",
                data: {getvisit_data:getVisitType},
                cache: true,
                success: function(data){
                    $.each(data, function(key, value){   
                        $('#grade_type').append($("<option value="+ value.id +">"+ value.name +"</option>"));
                    });
                } 
            });
        }



        function getGradeType(selectObjectValue) {
            $('#commuting_type').html('');
            $('#commuting_type').append('<option value="">Select commuting type</option>');

            getCities(selectObjectValue)
            getExpensesdData(selectObjectValue)
            
                $.ajax({
                    type: "GET",
                    url: "{{url('get_commutingData')}}",
                    data: {gradetype:selectObjectValue},
                    cache: true,
                    success: function(data){
                    $.each(data, function(key, value){   
                        $('#commuting_type').append($("<option value="+ value.id +">"+ value.name +"</option>"));
                    });
                    } 
            });
        }

        //function cities
        function getCities(CittData) {
                $('#city_id').html('');
                $('#city_id').append('<option value="">Select City</option>');

                $.ajax({
                    type: "GET",
                    url: "{{url('get_getCitiesData')}}",
                    data: {getvisit_data:CittData},
                    cache: true,
                    success: function(data){
                        console.log(data);
                    $.each(data, function(key, value){   
                        $('#city_id').append($("<option value="+ value.id +">"+ value.name +"</option>"));
                    });
                    } 
            });
        }

        function getExpensesdData(ExpensesdData){
            
            $('#getExpensesdData').html('');
                $('#getExpensesdData').append('<option value="">Select City</option>');

                $.ajax({
                    type: "GET",
                    url: "{{url('get_getExpensesdData')}}",
                    data: {getvisit_data:ExpensesdData},
                    cache: true,
                    success: function(data){
                        console.log(data);
                    $.each(data, function(key, value){   
                        $('#getExpensesdData').append($("<option value="+ value.id +">"+ value.name +"</option>"));
                    });
                    } 
            });
        }
        

</script>
@endpush

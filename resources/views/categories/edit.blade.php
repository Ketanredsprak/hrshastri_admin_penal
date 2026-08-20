{{ Form::open(['route' => ['categories.update', $data->id], 'method' => 'PUT' ,'id' => 'categoriesModel']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('visit_type', __('Visit Type'), ['class' => 'col-form-label']) }}<span class="text-danger"> *</span>
            {{ Form::select('visit_type', $visittypes, $data->visit_type_id, ['class' => 'form-control select2' ,'placeholder' => __('Select a visit option')]) }}
        </div>
        <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('grade_type', __('Grade Type'), ['class' => 'col-form-label']) }}<span class="text-danger"> *</span>
            {{ Form::select('grade_type', $gradetypes,$data->grade_type_id, ['class' => 'form-control select2' ,'placeholder' => __('Select a grade option')]) }}
        </div>
        <div class="form-group col-lg-6 col-md-6"> 
            {{ Form::label('category_type', __('Category Type'), ['class' => 'col-form-label']) }}<span class="text-danger"> *</span>
            {{ Form::select('category_type', $CategoriesTypes, $data->category_id, ['class' => 'form-control select2' ,'placeholder' => __('Select a category option')]) }}
        </div>

        <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('department_id', __('Amount'), ['class' => 'col-form-label']) }}
            {{ Form::number('amount', $data->amount, ['class' => 'form-control' ,'placeholder' => __('Enter amount')]) }}
        </div>

        <div class="form-group col-lg-6 col-md-6">
            <input type="radio" id="html" @if($data->amount_status == 1) checked @endif class="form-check-input" name="option_select" value="1">
            <label for="html">Per kilometer rate</label><br>
            <input type="radio" id="css" @if(!empty($data->amount) && $data->amount_status == 0) checked @endif class="form-check-input" name="option_select" value="0">
            <label for="css">Fixed</label><br>
        </div>
        <div class="form-group col-lg-12">
            {{ Form::label('description', __('Description'), ['class' => 'col-form-label']) }}
            {{ Form::textarea('description', $data->description, ['class' => 'form-control', 'placeholder' => __('Enter Description'),'rows'=>'3']) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
</div>
{{ Form::close() }}


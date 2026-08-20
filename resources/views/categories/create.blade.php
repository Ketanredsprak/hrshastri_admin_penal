{{ Form::open(['url' => 'categories', 'method' => 'post' ,'id' => 'categoriesModel']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('visit_type', __('Visit Type'), ['class' => 'col-form-label']) }}<span class="text-danger"> *</span>
            {{ Form::select('visit_type', $visittypes, null, ['class' => 'form-control select2' ,'placeholder' => __('Select a visit option')]) }}
        </div>
        <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('grade_type', __('Grade Type'), ['class' => 'col-form-label']) }}<span class="text-danger"> *</span>
            {{ Form::select('grade_type', $gradetypes, null, ['class' => 'form-control select2' ,'placeholder' => __('Select a grade option')]) }}
        </div>
        <div class="form-group col-lg-6 col-md-6"> 
            {{ Form::label('category_type', __('Category Type'), ['class' => 'col-form-label']) }}<span class="text-danger"> *</span>
            {{ Form::select('category_type', $CategoriesTypes, null, ['class' => 'form-control select2' ,'placeholder' => __('Select a category option')]) }}
        </div>
        <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('amount', __('Amount'), ['class' => 'col-form-label']) }}
            {{ Form::number('amount', null, ['class' => 'form-control' ,'placeholder' => __('Enter amount')]) }}
        </div>

        <div class="form-group col-lg-6 col-md-6">
            <input type="radio" id="html" name="option_select" class="form-check-input" value="1">
            <label for="html" class="form-check-label ">Per kilometer rate</label><br>
            <input type="radio" id="css" name="option_select" class="form-check-input" value="0">
            <label for="css" class="form-check-label ">Fixed</label><br>
        </div>
        <div class="form-group col-lg-12">
            {{ Form::label('description', __('Description'), ['class' => 'col-form-label']) }}
            {{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => __('Enter Description'),'rows'=>'3']) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
</div>
{{ Form::close() }}


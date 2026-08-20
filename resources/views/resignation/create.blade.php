{{ Form::open(['url' => 'resignation', 'method' => 'post']) }}
<div class="modal-body">
    <div class="row">
        {{-- @if (\Auth::user()->type != 'employee')
            <div class="form-group col-lg-12">
                {{ Form::label('employee_id', __('Employee'), ['class' => 'col-form-label']) }}
                {{ Form::select('employee_id', $employees, null, ['class' => 'form-control select2',]) }}
            </div>
        @endif --}}
        <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('resignation_date', __('Resignation Date'), ['class' => 'col-form-label']) }}

            <input type="date" name="resignation_date" class='form-control' min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" readonly>

        </div>
        {{-- <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('notice_date', __('Last Working Day'), ['class' => 'col-form-label']) }}

            <input type="date" name="notice_date" class='form-control' min="{{ date('Y-m-d') }}">

        </div> --}}
        <div class="form-group col-lg-12"><span class="text-danger"> * </span>
            {{ Form::label('description', __('Reason'), ['class' => 'col-form-label']) }}
            {{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => __('Enter Reason'),'rows'=>'3' ,]) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
</div>

{{ Form::close() }}

<script>
    $(document).ready(function() {
        var now = new Date();
        var month = (now.getMonth() + 1);
        var day = now.getDate();
        if (month < 10) month = "0" + month;
        if (day < 10) day = "0" + day;
        var today = now.getFullYear() + '-' + month + '-' + day;
        $('.current_date').val(today);
    });
</script>
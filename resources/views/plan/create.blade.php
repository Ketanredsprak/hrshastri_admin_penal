{{ Form::open(['url' => 'plans', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group">
            {{ Form::label('name', __('Name'), ['class' => 'col-form-label']) }}
            {{ Form::text('name', null, ['class' => 'form-control font-style', 'placeholder' => __('Enter Plan Name'), 'required' => 'required']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('price', __('Price'), ['class' => 'col-form-label']) }}
            {{ Form::number('price', null, ['class' => 'form-control', 'placeholder' => __('Enter Plan Price')]) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('duration', __('Duration'), ['class' => 'col-form-label']) }}
            {!! Form::select('duration', $arrDuration, null, ['class' => 'form-control select2', 'required' => 'required']) !!}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('max_users', __('Maximum Users'), ['class' => 'col-form-label']) }}
            {{ Form::number('max_users', null, ['class' => 'form-control', 'required' => 'required']) }}
            <span class="small">{{ __('Note: "-1" for Unlimited') }}</span>
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('max_employees', __('Maximum Employees'), ['class' => 'col-form-label']) }}
            {{ Form::number('max_employees', null, ['class' => 'form-control', 'required' => 'required']) }}
            <span class="small">{{ __('Note: "-1" for Unlimited') }}</span>
        </div>
        <div class="form-group">
            {{ Form::label('description', __('Description'), ['class' => 'col-form-label']) }}
            {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => '2']) !!}
        </div>
        <div class="form-group">
            <table class="table  mb-0" id="dataTable-1">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" class="align-middle checkbox_middle form-check-input" name="checkall"
                                id="checkall">
                        </th>
                        <th>{{ __('Module') }} </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($modules as $key => $module)
                        <tr>
                            <td>{{ Form::checkbox('modules[]', $key, false, ['class' => 'form-check-input ischeck align-middle', 'id' => 'permission' . $key]) }}
                            </td>
                            <td><label class="ischeck form-label"
                                    data-id="{{ str_replace(' ', '', $module) }}">{{ ucfirst($module) }}</label></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary">

</div>
{{ Form::close() }}
<script>
    $(document).ready(function() {
        $("#checkall").click(function() {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
        // $("#is_hr").click(function() {
        //     $('input:checkbox').prop('checked', this.checked);
        // });
        // $(".ischeck").click(function() {
        //     var ischeck = $(this).data('id');
        //     $('.isscheck_' + ischeck).prop('checked', this.checked);
        // });
    });
</script>

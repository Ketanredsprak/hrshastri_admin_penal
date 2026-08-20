{{ Form::open(['route' => ['resignation.actionupdate', $data->id], 'method' => 'post']) }}
<div class="modal-body">
    <div class="row">

        <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('notice_date', __('Last Working Day'), ['class' => 'col-form-label']) }}
            <input type="date" name="notice_date" class='form-control' {{-- max="{{ date('Y-m-d') }}" --}} value="{{ date('Y-m-d') }}"
                required>
        </div>

        <div class="form-group col-lg-12">
            {{ Form::label('description', __('Note '), ['class' => 'col-form-label']) }} <span
                class="text-danger">*</span>
            {{ Form::textarea('description', $data->notes, ['class' => 'form-control', 'placeholder' => __('Enter notes'), 'rows' => '3', 'required' => 'required']) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <!-- 0 = pending, 1 = Approved, 2 = Rejected	 -->
    @if (in_array($data->status, [0]))
        <input type="hidden" value="{{ __('Approved') }}" class="btn btn-success rounded" name="status">
        <input type="submit" value="{{ __('Approve') }}" class="btn btn-success rounded" name="visit_statusData">
    @endif

    @if (in_array($data->status, [0]))
        <input type="submit" value="{{ __('Reject') }}" class="btn btn-danger rounded" name="status">
    @endif

</div>

{{ Form::close() }}

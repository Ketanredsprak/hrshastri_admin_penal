
{{ Form::open(['url' => 'reference_action_status/'.$data->id, 'method' => 'POST','enctype'=>"multipart/form-data"]) }}
<div class="modal-body">

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('days', __('Status'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    <select name="status" id="status" class="form-control" required>
                        <option value="">Select Status</option>
                        <option @if($data->status == 0) selected @endif value="0">Pennding</option>
                        <option @if($data->status == 1) selected @endif value="1">Approve</option>
                        <option @if($data->status == 2) selected @endif value="2">Reject</option>
                        <option @if($data->status == 3) selected @endif value="3">Hired</option>
                    </select>
                    <input type="hidden" value="{{ $data->id }}" name="job_id">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Upload') }}" class="btn btn-primary">
</div>
{{ Form::close() }}





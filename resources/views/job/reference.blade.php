
{{ Form::open(['url' => 'reference_upload/'.$data->id, 'method' => 'POST','enctype'=>"multipart/form-data"]) }}
<div class="modal-body">

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('days', __('Upload Document'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    <input type="file" class="form-control" name="refence_doc">
                    <input type="hidden" value="{{ $data->id }}" name="job_id">
                </div>
            </div>
        </div>
        <hr>

        @foreach ($refranceData as $row)
            
       
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                <div class="action-btn bg-secondary ms-2">
                    <a class="mx-3 btn btn-sm align-items-center" href="{{ asset('referenceDoc/'.$row->refence_file)}}" target="_blank"  >
                        <i class="ti ti-crosshair text-white" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Preview') }}"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                @if ($row->status == 0)
                    <span class="badge bg-warning p-2 px-3 rounded status-badge">Pennding</span>
                @elseif($row->status == 1)
                    <span class="badge bg-success p-2 px-3 rounded status-badge">Approved</span>
                @elseif($row->status == 2)
                    <span class="badge bg-danger p-2 px-3 rounded status-badge">Reject</span>
                @else
                    <span class="badge bg-dark p-2 px-3 rounded status-badge">Hired</span>
                @endif
            </div>
        </div>

        @endforeach
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Upload') }}" class="btn btn-primary">
</div>


{{ Form::close() }}





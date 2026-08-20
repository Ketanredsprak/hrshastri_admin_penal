<style>
    .align-height {
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

{{ Form::open(['route' => ['attendance.import'], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">

    <div class="row">

        <div class="col-md-12 mb-6">
            <label for="importMonth" class="form-label">{{ __('Select Month & Download Sample Import File') }}</label>
            <div class="row">
                <div class="col-md-6">

                    <input class="month-btn form-control" autocomplete="off" placeholder="Select month" name="importMonth"
                        type="month" value="{{ date('Y-m') }}" id="importMonth" required>
                </div>
                <div class="col-md-6">
                    <button type="button" class="btn btn-sm btn-primary w-100 align-height"
                        onclick="downloadSampleFile()">
                        <i class="ti ti-download"></i> {{ __('Download') }}
                    </button>
                </div>
            </div>
        </div>

        <div class="choose-files mt-3">
            <label for="file">
                <div class="bg-primary"> <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                </div>
                <input type="file" class="form-control file" name="file" id="file" data-filename="file"
                    required>
            </label>

        </div>


    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Upload') }}" class="btn btn-primary">
</div>
{{ Form::close() }}

<script>
    function downloadSampleFile() {
        var month = document.getElementById('importMonth').value;
        var url = "{{ route('attendance-import-sample-file-download') }}";
        window.open(url + '?importMonth=' + month, '_blank');
    }

    // Match button height with month picker
    window.addEventListener('load', function() {
        var monthPicker = document.getElementById('importMonth');
        var downloadButton = document.querySelector('.align-height');
        downloadButton.style.height = monthPicker.offsetHeight + 'px';
    });
</script>

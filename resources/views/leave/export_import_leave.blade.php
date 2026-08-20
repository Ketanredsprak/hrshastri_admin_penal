{{ Form::open(['route' => ['leave.export_import'], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12 mb-6">
            <label for="file" class="form-label">Download sample product CSV file</label>

            <button type="button" id="downloadSampleFileButton" class="btn btn-sm btn-primary"><i
                    class="ti ti-download"></i>
                {{ __('Download') }}</button>
            </a>
        </div>

        <div class="choose-files mt-3">
            <label for="file">
                <div class=" bg-primary "> <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                </div>
                <input type="file" class="form-control file" name="file" id="file" data-filename="file">
            </label>
        </div>


        <div class="modal-footer">
            <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
            <input type="submit" value="{{ __('Upload') }}" class="btn btn-primary">
        </div>


    </div>
</div>

<script>
    $(document).ready(function() {
        //    console.log("here");
        $('#downloadSampleFileButton').click(function() {

            $(this).prop('disabled', true);
            //   console.log("clicked");
            $.ajax({
                url: "{{ route('download-import-leave-sample-file') }}",
                type: 'GET',
                success: function(response) {
                    var link = document.createElement('a');
                    link.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent(
                        response);
                    link.download = 'LeaveImportSampleFile.csv';

                    document.body.appendChild(link);
                    link.click();

                    document.body.removeChild(link);
                },
                complete: function() {
                    $('#downloadSampleFileButton').prop('disabled', false);
                }
            });
        });
    });
</script>
{{ Form::close() }}

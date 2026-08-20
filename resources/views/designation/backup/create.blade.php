{{ Form::open(['url' => 'designation', 'method' => 'post']) }}
<div class="modal-body">

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('branch_id', __('Branch *'), ['class' => 'form-label']) }}
                <div class="form-icon-user">

                    <select class="form-control branch_id" name="branch_id" id="branch_id" required>
                        <option value="">{{ __('Select Branch') }}</option>
                        @foreach ($branch as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('department_id', __('Department *'), ['class' => 'form-label']) }}

                <div class="form-icon-user department_div">
                    <select class="form-control department_id" name="department_id" id="department_id">
                        <option value="">{{ __('Select Department') }}</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('name', __('Name *'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Designation Name')]) }}
                </div>
                @error('name')
                    <span class="invalid-name" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('status', __('status'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    <select name="status" id="status" class="form-control">
                        <option value="Active">Active</option>
                        <option value="In-Active">In-Active</option>
                    </select>
                </div>
            </div>
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
        //    var b_id = $('#branch_id').val();
        //    getDepartment(b_id);
    });


    $(document).on('change', '.branch_id', function() {
        var branch_id = $(this).val();
        getDepartment(branch_id);
    });

    function getDepartment(bid) {

        $.ajax({
            url: "{{ route('event.getdepartment') }}",
            type: 'POST',
            data: {
                "branch_id": bid,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {


                $('.department_id').empty();
                var emp_selct = ` <select class="form-control department_id" name="department_id">
                                            </select>`;
                $('.department_div').html(emp_selct);

                $('.department_id').append("<option value=''>{{ __('Select Department') }}</option>");
                $.each(data, function(key, value) {
                    $('.department_id').append('<option value="' + key + '">' + value +
                        '</option>');
                });

            }
        });
    }
</script>

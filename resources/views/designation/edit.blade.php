{{ Form::model($designation, ['route' => ['designation.update', $designation->id], 'method' => 'PUT', 'files' => true]) }}
<div class="modal-body">

    <div class="row">

        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('branch_id', __('Branch *'), ['class' => 'form-label']) }}
                <div class="form-icon-user">

                    <select class="form-control branch_id" name="branch_id" id="branch_id" required>
                        <option value="">{{ __('Select Branch') }}</option>
                        @foreach ($branch as $branch)
                            @php
                                $selected = '';
                                if ($designation->branch_id == $branch->id) {
                                    $selected = 'selected';
                                }
                            @endphp
                            <option value="{{ $branch->id }}" {{ $selected }}>{{ $branch->name }}</option>
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
                {{ Form::label('name', __('Name * '), ['class' => 'form-label']) }}
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
                {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => '3', 'placeholder' => __('Enter Description')]) }}
                </div>
                @error('description')
                    <span class="invalid-description" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('document', __('Document'), ['class' => 'form-label']) }}
                <small class="text-muted">{{__('(Max file size allowed: 2 MB)');}}</small>
                <div class="form-icon-user">
                    {{ Form::file('document', [
                        'class' => 'form-control',
                        'accept' => '.pdf,.doc,.docx'
                    ]) }}
                </div>
                @if(!empty($designation->document))
                    <div class="mt-2">
                        <a href="{{ $designation->document }}" 
                        target="_blank" 
                        class="btn btn-sm btn-outline-primary">                            
                            {{__('View Document')}}
                        </a>
                    </div>
                @endif
                @error('document')
                    <span class="invalid-document" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" id="submitBtn" value="{{ __('Update') }}" class="btn btn-primary">
</div>
{{ Form::close() }}


<script>
    $(document).ready(function() {
         $('form').on('submit', function () {
            const btn = $('#submitBtn');

            var plz_wait = "{{ __('Please wait...') }}";
            btn.prop('disabled', true);
            btn.val(plz_wait);
        });

        var b_id = $('#branch_id').val();
        getDepartment(b_id);
    });


    var currentDepartmentId = {{ $designation->department_id }};

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

                    var selected = "";
                    if (currentDepartmentId == key) {
                        selected = "selected";
                        currentDepartmentId = 0;
                    }
                    $('.department_id').append('<option value="' + key + '" ' + selected + '>' +
                        value +
                        '</option>');
                });

            }
        });
    }
</script>

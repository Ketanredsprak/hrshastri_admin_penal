{{ Form::model($announcement, ['route' => ['announcement.update', $announcement->id], 'method' => 'PUT']) }}
<div class="modal-body">
    <div class="row">

        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('branch_id', __('Branch'), ['class' => 'col-form-label']) }}
                <select class="form-control select2" name="branch_id[]" id="branch_id" placeholder="Select Branch" multiple>
                    <option value="">{{ __('Select Branch') }}</option>
                    <option value="0"
                        {{ in_array(0, json_decode($announcement->branch_id ?? '') ?? []) ? 'selected' : '' }}>
                        {{ __('All Branch') }}</option>

                    @foreach ($branch as $branchRow)
                        <option value="{{ $branchRow->id }}"
                            {{ in_array($branchRow->id, json_decode($announcement->branch_id ?? '') ?? []) ? 'selected' : '' }}>
                            {{ $branchRow->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('department_id', __('Department'), ['class' => 'col-form-label']) }}

                <div class="department_div">

                    <select class="form-control select2 department_id" name="department_id[]" id="department_id"
                        placeholder="Select Department" multiple>
                        <option value="">{{ __('Select Department') }}</option>
                        <option value="0"
                            {{ in_array(0, json_decode($announcement->department_id ?? '') ?? []) ? 'selected' : '' }}>
                            {{ __('All Department') }}</option>

                        @foreach ($departments as $departmentsRow)
                            <option value="{{ $departmentsRow->id }}"
                                {{ in_array($departmentsRow->id, json_decode($announcement->department_id ?? '') ?? []) ? 'selected' : '' }}>
                                {{ $departmentsRow->name }}</option>
                        @endforeach
                    </select>


                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('employee_id', __('Employee'), ['class' => 'col-form-label']) }}


                <div class="employee_div">

                    {{-- <select class="form-control select2  employee_id" name="employee_id[]" id="employee_id"
                        placeholder="Select Employee" multiple>
                    </select> --}}

                    <select class="form-control select2" name="employee_id[]" id="employee_id"
                        placeholder="Select Employee" multiple>
                        <option value="">{{ __('Select Employee') }}</option>
                        <option value="0"
                            {{ in_array(0, json_decode($announcement->employee_id ?? '') ?? []) ? 'selected' : '' }}>
                            {{ __('All Employee') }}</option>


                        @foreach ($employees as $employeesRow)
                            <option value="{{ $employeesRow->id }}"
                                {{ in_array($employeesRow->id, json_decode($announcement->employee_id ?? '') ?? []) ? 'selected' : '' }}>
                                {{ $employeesRow->name }}</option>
                        @endforeach
                    </select>

                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('title', __('Announcement Title'), ['class' => 'col-form-label']) }}
                {{ Form::text('title', null, ['class' => 'form-control', 'placeholder' => __('Enter Announcement Title'), 'required' => 'required']) }}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('start_date', __('Announcement start Date'), ['class' => 'col-form-label']) }}
                {{ Form::date('start_date', $announcement->getRawOriginal('start_date'), ['class' => 'form-control', 'onchange' => 'validateEndDate()', 'required' => 'required', 'id' => 'start_date']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('end_date', __('Announcement End Date'), ['class' => 'col-form-label']) }}
                {{ Form::date('end_date', $announcement->getRawOriginal('end_date'), ['class' => 'form-control', 'autocomplete' => 'off', 'required' => 'required', 'id' => 'end_date']) }}
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('description', __('Announcement Description'), ['class' => 'col-form-label']) }}
            {{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => __('Enter Announcement Title'), 'rows' => '3', 'required' => 'required']) }}
        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{ __('Update') }}" class="btn  btn-primary">

    </div>
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

    validateEndDate();

    function validateEndDate() {
        //    console.log("in validateEndDate -----");
        const startDate = document.getElementById('start_date').value;
        const endDateInput = document.getElementById('end_date');

        if (startDate) {
            const start = new Date(startDate);
            endDateInput.setAttribute('min', start.toISOString().split('T')[0]);
        }
    }
</script>



<script>
    //Branch Wise Deapartment Get
    $(document).ready(function() {
        var b_id = $('#branch_id').val();
        //    getDepartment(b_id);
    });

    $('#branch_id').on('change', function() {
        var branch_id = $(this).val();
        getDepartment(branch_id);

        clearEmployeesData();
    });

    function getDepartment(bid) {

        $.ajax({
            url: "{{ route('announcement.getdepartment') }}",
            type: 'POST',
            data: {
                "branch_id": bid,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {

                $('.department_id').empty();
                var emp_selct = ` <select class="form-control  department_id" name="department_id[]" id="department_id"
                                         placeholder="Select Department" multiple>
                                         </select>`;
                $('.department_div').html(emp_selct);

                var dataLength = Object.keys(data).length ?? 0;

                if (dataLength >= 2) {
                    $('.department_id').append('<option value="0"> {{ __('All') }} </option>');
                }

                $.each(data, function(key, value) {
                    $('.department_id').append('<option value="' + key + '">' + value +
                        '</option>');
                });
                new Choices('#department_id', {
                    removeItemButton: true,
                });

            }
        });
    }

    $(document).on('change', '.department_id', function() {

        var department_id = $(this).val();

        clearEmployeesData();

        getEmployee(department_id);
    });

    function clearEmployeesData() {

        //    alert("in clear employee data function");
        $('.employee_id').empty();
        var emp_selct = ` <select class="form-control employee_id" name="employee_id[]" id="employee_id"
                                            placeholder="Select Employee" multiple>
                                            </select>`;
        $('.employee_div').html(emp_selct);
        new Choices('#employee_id', {
            removeItemButton: true,
        });
    }

    function getEmployee(did) {


        //    alert("in get employee data function");
        $.ajax({
            url: "{{ route('announcement.getemployee') }}",
            type: 'POST',
            data: {
                "department_id": did,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                $('.employee_id').empty();
                var emp_selct = ` <select class="form-control employee_id" name="employee_id[]" id="employee_id"
                                         placeholder="Select Employee" multiple>
                                         </select>`;
                $('.employee_div').html(emp_selct);

                var dataLength = Object.keys(data).length ?? 0;
                if (dataLength >= 2) {
                    $('.employee_id').append("<option value='0'> {{ __('All') }} </option>");
                }
                $.each(data, function(key, value) {
                    $('.employee_id').append('<option value="' + key + '">' + value +
                        '</option>');
                });
                new Choices('#employee_id', {
                    removeItemButton: true,
                });
            }
        });
    }
</script>

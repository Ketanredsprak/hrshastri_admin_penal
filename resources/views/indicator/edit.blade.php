{{ Form::model($indicator, ['route' => ['indicator.update', $indicator->id], 'method' => 'PUT']) }}

<div class="modal-body">

    <div class="row">

        <div class="col-md-6">

            <div class="form-group">

                {{ Form::label('branch', __('Branch '), ['class' => 'col-form-label']) }} <span class="text-danger">*</span>
                {{ Form::select('branch', $brances, null, ['class' => 'form-control select2 branch' ,'placeholder'=>'Select Branch']) }}

            </div>

        </div>

        <div class="col-md-6">

            <div class="form-group">

                {{ Form::label('department', __('Department '), ['class' => 'col-form-label']) }} <span class="text-danger"> * </span>

                <div class="department_div">
                    {{ Form::select('department', $departments, null, ['class' => 'form-control select2 department_id','id'=>'department_id','placeholder'=>'Select Department']) }}
               </div>

            </div>

        </div>

        <div class="col-md-12">

            <div class="form-group">

                {{ Form::label('designation', __('Designation'), ['class' => 'col-form-label']) }} <span class="text-danger"> * </span>

                <div class="designation_div">
                    {{ Form::select('designation', $degisnation, null, ['class' => 'form-control select2 designation_id','id'=>'designation_id','placeholder'=>'Select Designation']) }}
                </div>



            </div>

        </div>



    </div>

    <div class="row">

        @foreach ($performance_types as $performances)

            <div class="col-md-12 mt-3">

                <h6>{{ $performances->name }}</h6>

                <hr class="mt-0">

            </div>

            @foreach ($performances->types as $types)

                <div class="col-6">

                    {{ $types->name }}

                </div>

                <div class="col-6">

                    <fieldset id='demo1' class="rate">

                        <input class="stars" type="radio" id="technical-5-{{ $types->id }}"

                            name="rating[{{ $types->id }}]" value="5"

                            {{ isset($ratings[$types->id]) && $ratings[$types->id] == 5 ? 'checked' : '' }}>

                        <label class="full" for="technical-5-{{ $types->id }}"

                            title="Awesome - 5 stars"></label>

                        <input class="stars" type="radio" id="technical-4-{{ $types->id }}"

                            name="rating[{{ $types->id }}]" value="4"

                            {{ isset($ratings[$types->id]) && $ratings[$types->id] == 4 ? 'checked' : '' }}>

                        <label class="full" for="technical-4-{{ $types->id }}"

                            title="Pretty good - 4 stars"></label>

                        <input class="stars" type="radio" id="technical-3-{{ $types->id }}"

                            name="rating[{{ $types->id }}]" value="3"

                            {{ isset($ratings[$types->id]) && $ratings[$types->id] == 3 ? 'checked' : '' }}>

                        <label class="full" for="technical-3-{{ $types->id }}"

                            title="Meh - 3 stars"></label>

                        <input class="stars" type="radio" id="technical-2-{{ $types->id }}"

                            name="rating[{{ $types->id }}]" value="2"

                            {{ isset($ratings[$types->id]) && $ratings[$types->id] == 2 ? 'checked' : '' }}>

                        <label class="full" for="technical-2-{{ $types->id }}"

                            title="Kinda bad - 2 stars"></label>

                        <input class="stars" type="radio" id="technical-1-{{ $types->id }}"

                            name="rating[{{ $types->id }}]" value="1"

                            {{ isset($ratings[$types->id]) && $ratings[$types->id] == 1 ? 'checked' : '' }}>

                        <label class="full" for="technical-1-{{ $types->id }}"

                            title="Sucks big time - 1 star"></label>

                    </fieldset>

                </div>

            @endforeach

        @endforeach

    </div>

</div>



<div class="modal-footer">

    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">

    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">

</div>

    {{ Form::close() }}



<script>

     $(document).on('change', 'select[name=branch]', function() {
          var branch_id = $(this).val() ?? 0;
          $('.department_id').empty();
          $('.designation_id').empty();
          getDepartment(branch_id);
          getDesignation(0);
     });

     $(document).on('change', 'select[name=department]', function() {
          var d_id = $(this).val();
          $('.designation_id').empty();
          getDesignation(d_id);
     });

     function getDepartment(bid)
     {
          if(bid <= 0 ||bid == null || bid == '')
          {
               $('.department_id').empty();
               var emp_selct = ` <select class="form-control  department_id" name="department" placeholder="Select Department" id="choices-multiple"></select>`;
               $('.department_div').html(emp_selct);
               $('.department_id').append("<option value=''> {{ __('Select Department') }} </option>");

               new Choices('#choices-multiple', {
                         removeItemButton: true,
                    });

               return;
          }
          $.ajax({
               url: "{{ route('event.getdepartment') }}",
               type: 'POST',
               data: {
                    "branch_id": bid,
                    "_token": "{{ csrf_token() }}",
               },
               success: function(data) {


                    $('.department_id').empty();
                    var emp_selct = ` <select class="form-control  department_id" name="department" placeholder="Select Department" id="choices-multiple"></select>`;
                    $('.department_div').html(emp_selct);
                    $('.department_id').append("<option value=''> {{ __('Select Department') }} </option>");

                    $.each(data, function(key, value) {
                         $('.department_id').append('<option value="' + key + '">' + value +
                              '</option>');
                    });
                    new Choices('#choices-multiple', {
                         removeItemButton: true,
                    });


               }
          });
     }

     function getDesignation(did) {

          if(did <= 0 ||did == null || did == '')
          {
               $('.designation_id').empty();
               var emp_selct = ` <select class="form-control designation_id select2" name="designation" id="choices-multiple-2" placeholder="Select Designation"></select>`;                    
               $('.designation_div').html(emp_selct);
               $('.designation_id').append("<option value=''> {{ __('Select Designation') }} </option>");

               new Choices('#choices-multiple-2', {
                         removeItemButton: true,
                    });
               
               return;
          }

          $.ajax({
               url: "{{ route('employee.json') }}",
               type: 'POST',
               data: {
                    "department_id": did,
                    "_token": "{{ csrf_token() }}",
               },
               success: function(data) {

                    $('.designation_id').empty();
                    var emp_selct = ` <select class="form-control designation_id select2" name="designation" id="choices-multiple-2" placeholder="Select Designation"></select>`;                    
                    $('.designation_div').html(emp_selct);
                    $('.designation_id').append("<option value=''> {{ __('Select Designation') }} </option>");

                    $.each(data, function(key, value) {
                         var select = '';
                         $('.designation_id').append('<option value="' + key + '"  ' + select + '>' + value + '</option>');
                    });

                    new Choices('#choices-multiple-2', {
                         removeItemButton: true,
                    });

               }
          });
     }

</script>
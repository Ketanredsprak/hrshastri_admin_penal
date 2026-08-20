{{ Form::model($transfer, ['route' => ['transfer.update', $transfer->id], 'method' => 'PUT']) }}
<div class="modal-body">
     <div class="row">
          <div class="form-group col-lg-6 col-md-6 ">
               {{ Form::label('employee_id', __('Employee'), ['class' => 'col-form-label']) }}
               <span style="color: red;">*</span>
               {{ Form::select('employee_id', $employees, null, ['class' => 'form-control select2']) }}
          </div>
          <div class="form-group col-lg-6 col-md-6">
               {{ Form::label('branch_id', __('Branch'), ['class' => 'col-form-label']) }}
               <span style="color: red;">*</span>

               @php
               $branches->prepend('Select Option', "");
               @endphp
               {{ Form::select('branch_id', $branches, null, ['class' => 'form-control select2']) }}

          </div>

          <div class="form-group col-lg-6 col-md-6">
               {{ Form::label('department_id', __('Department'), ['class' => 'col-form-label']) }}
               <span style="color: red;">*</span>

               <div class="department_div">
                    {{ Form::select('department_id', $departments, null, ['class' => 'form-control select2' ]) }}
               </div>
          </div>

          <div class="form-group col-lg-6 col-md-6">
               {{ Form::label('transfer_date', __('Transfer Date'), ['class' => 'col-form-label']) }}
               <span style="color: red;">*</span>
               {{ Form::text('transfer_date', $transfer->getRawOriginal('transfer_date'), ['class' => 'form-control d_week', 'autocomplete' => 'off' ]) }}
          </div>
          <div class="form-group col-lg-12">
               {{ Form::label('description', __('Description'), ['class' => 'col-form-label']) }}
               <span style="color: red;">*</span>
               {{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => __('Enter Description'),'rows'=>'3']) }}
          </div>
     </div>
</div>
<div class="modal-footer">
     <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
     <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>

{{ Form::close() }}


<script>
     $(document).on('change', 'select[name=branch_id]', function() {
          var branch_id = $(this).val();
          console.log("branch_id", branch_id);

          $('.department_id').empty();
          getDepartment(branch_id);
     });

     function getDepartment(bid) {

          if (bid <= 0 || bid == null || bid == '') {
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
                    var emp_selct = ` <select class="form-control  department_id" name="department_id" placeholder="Select Department" id="choices-multiple">
                                            </select>`;
                    $('.department_div').html(emp_selct);

                    $('.department_id').append("<option value=''> {{ __('Select Designation') }} </option>");

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
</script>
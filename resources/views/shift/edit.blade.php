{{ Form::model($workingShifts, ['route' => ['working-shifts.update', $workingShifts->id], 'method' => 'PUT']) }}

<div class="modal-body">

     <div class="row">

          <div class="col-lg-12 col-md-12 col-sm-12">
               <div class="form-group">
                    {{ Form::label('name', __('Name '), ['class' => 'form-label']) }} <span class="text-danger"> * </span>
                    <div class="form-icon-user">
                         {{ Form::text('name', old('name', $workingShifts->name), ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Shift Name')]) }}
                    </div>
               </div>
          </div>

          <div class="col-md-6">
               <div class="form-group">
                    {{ Form::label('start_time', __('Start Time'), ['class' => 'col-form-label']) }} <span class="text-danger pl-1">*</span>
                    <input type="time" name="start_time" id="start_time" class="form-control" value="{{ $workingShifts->start_time }}" step="1" required>
               </div>
          </div>

          <div class="col-md-6">
               <div class="form-group">
                    {{ Form::label('end_time', __('End Time'), ['class' => 'col-form-label']) }} <span class="text-danger pl-1">*</span>
                    <input type="time" name="end_time" id="end_time" class="form-control" value="{{ $workingShifts->end_time }}" step="1" required>
               </div>
          </div>

          <div class="col-md-6">
               <div class="form-group">
                    {{ Form::label('flexi_hours', __('Flexi Hours (In Minutes)'), ['class' => 'col-form-label']) }}
                    <input type="text" name="flexi_hours" id="flexi_hours" class="form-control numberValidation" value="{{ $workingShifts->flexi_hours }}" min="0.1">
               </div>
          </div>

          <div class="col-md-6">
               <div class="form-group">
                    {{ Form::label('flexi_days', __('Flexi Days'), ['class' => 'col-form-label']) }}
                    <input type="number" name="flexi_days" id="flexi_days" class="form-control" value="{{ $workingShifts->flexi_days }}" min="0" max="31">
               </div>
          </div>

          <div class="col-md-12">
               <div class="form-group">
                    {{ Form::label('flexi_days', __('Assign Employees'), ['class' => 'col-form-label']) }}
                    <select name="assign_employees[]" class="select2" id="assign_employees" multiple>
                         <option value="">Select employee</option>
                         @foreach ($employees as $emp)
                         <option value="{{ $emp->id }}" {{ in_array($emp->id, $assignedEmployees) ? 'selected' : '' }}>{{ $emp->name }}
                         </option>
                         @endforeach
                    </select>
               </div>
          </div>

     </div>

</div>

<div class="modal-footer">
     <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
     <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>

<script>
     $(document).ready(function() {

     });

     $(".numberValidation").on("keypress", function(event) {


          if (!isNumberDecimalKey(this, event)) {
               event.preventDefault();
          }
     });

     function isNumberDecimalKey(el, evt) {

          var charCode = (evt.which) ? evt.which : event.keyCode;
          var number = el.value.split('.');

          if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
               return false;
          }

          //just one dot
          if (number.length > 1 && charCode == 46) {
               return false;
          }

          //get the carat position
          var caratPos = getSelectionStart(el);
          var dotPos = el.value.indexOf(".");
          if (caratPos > dotPos && dotPos > -1 && (number[1].length > 1)) {
               return false;
          }

          return true;
     }

     $(".numberValidation").on("paste dragover", function(event) {
          // Prevent paste and drag-and-drop actions
          event.preventDefault();
     });
</script>

{{ Form::close() }}
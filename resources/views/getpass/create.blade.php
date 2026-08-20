@php

$setting = App\Models\Utility::settings();

@endphp

{{ Form::open(['url' => 'getpass', 'method' => 'post']) }}

<div class="modal-body">





     <div class="row">

          <div class="col-md-12">

               <div class="form-group">

                    {{ Form::label('leave_type_id', __('Type'), ['class' => 'col-form-label']) }} <span class="text-danger pl-1">*</span>

                    <select name="leave_type_id" id="leave_type_id" class="form-control select" required>

                         <option value=''>Select pass type</option>

                         <option value="1">Personal work</option>

                         <option value="2">Office Work</option>

                    </select>

               </div>

          </div>

     </div>

     <div class="row">

          <div class="col-md-4">

               <div class="form-group start_date_calender_date">

                    {{ Form::label('start_date', __('Start Date'), ['class' => 'col-form-label']) }} <span class="text-danger pl-1">*</span>
                    {{ Form::date('start_date', date("Y-m-d"), [ 'id' => 'start_date_calender','required' => 'required','class' => 'form-control',]) }}

               </div>

          </div>

          <div class="col-md-4">

               <div class="form-group start_date_calender_date">

                    {{ Form::label('out_time', __('Out Time'), ['class' => 'col-form-label']) }} <span class="text-danger pl-1">*</span>
                    <input type="time" name="out_time" id="out_time" class="form-control"  value="" step="1" required>
               </div>

          </div>

          <div class="col-md-4">

               <div class="form-group start_date_calender_date">

                    {{ Form::label('in_time', __('In Time'), ['class' => 'col-form-label']) }} <span class="text-danger pl-1">*</span>
                    <input type="time" name="in_time" id="in_time" class="form-control"  value="" step="1" required>

               </div>

          </div>

     </div>

     <div class="row">

          <div class="col-md-12">

               <div class="form-group">

                    {{ Form::label('leave_reason', __('Reason'), ['class' => 'col-form-label']) }} <span class="text-danger pl-1">*</span>

                    {{ Form::textarea('leave_reason', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Reason'), 'rows' => '3']) }}

               </div>

          </div>

     </div>

</div>

<div class="modal-footer">

     <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>

     <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary">

</div>

{{ Form::close() }}



<script>

</script>
{{ Form::model($leavetype, ['route' => ['leavetype.update', $leavetype->id], 'method' => 'PUT']) }}

<div class="modal-body">



     <div class="row">





          <div class="col-lg-12 col-md-12 col-sm-12">

               <div class="form-group">

                    {{ Form::label('days', __('Pay Slab'), ['class' => 'form-label']) }} <span class="text-danger"> * </span>

                    <div class="form-icon-user">

                         <select class="form-control" name="play_slab" id="play_slab" required>

                              <option value="">Select Pay Slab</option>

                              @foreach ($data as $row)

                              <option @if($leavetype->play_slab_id ==$row->id ) selected @endif value="{{$row->id}}">{{ $row->name }}</option>

                              @endforeach

                         </select>

                    </div>

               </div>

          </div>



          <div class="col-lg-12 col-md-12 col-sm-12">

               <div class="form-group">

                    {{ Form::label('title', __('Name'), ['class' => 'form-label']) }} <span class="text-danger"> * </span>

                    <div class="form-icon-user">

                         {{ Form::text('title', null, ['class' => 'form-control', 'required' => 'required','placeholder' => __('Enter Payment Type Name')]) }}

                    </div>

                    @error('title')

                    <span class="invalid-name" role="alert">

                         <strong class="text-danger">{{ $message }}</strong>

                    </span>

                    @enderror

               </div>

          </div>





          <div class="col-lg-12 col-md-12 col-sm-12">

               <div class="form-group">

                    {{ Form::label('days', __('Per Year'), ['class' => 'form-label']) }} <span class="text-danger"> * </span>

                    <div class="form-icon-user">

                         <select class="form-control" name="days" id="days" required>

                              <option value="">Select Total Leave</option>
                              <option value="0" @if($leavetype->days == 0) selected @endif>No Leave</option>
                              @foreach (getLeaveTypeDays() as $item)
                              <option value="{{$item}}" @if($leavetype->days == $item) selected @endif>{{$item}}</option>
                              @endforeach

                              {{-- <option @if($leavetype->days == 12) selected @endif value="12">12</option>

                        <option @if($leavetype->days == 18) selected @endif value="18">18</option>

                        <option @if($leavetype->days == 24) selected @endif value="24">24</option>

                        <option @if($leavetype->days == 30) selected @endif value="30">30</option> --}}

                         </select>

                    </div>

               </div>
               <span class="{{ ($leavetype->no_leave == 0) ?  'no_leave_note d-none text-danger' : 'no_leave_note text-danger' }}">Note: This leave consider as Leave Without Pay where the employee does not receive their salary for the duration of the leave.</span>

          </div>

          <div class="col-lg-12 col-md-12 col-sm-12">

               <div class="form-group">

                    {{ Form::label('days', __('Per Month'), ['class' => 'form-label']) }}

                    <div class="form-icon-user">

                         <input type="text" name="leave_per_month" class="form-control" id="leave_per_month" readonly>

                    </div>

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



          result1 = parseInt($('#days').find(":selected").val()) / parseInt(12);

          $('#leave_per_month').val(result1);

          $('#days').on('change', function() {

               result = parseInt(this.value) / parseInt(12);
               if(this.value == 0 ) {
                    $('.no_leave_note').removeClass('d-none');
               } else {
                    $('.no_leave_note').addClass('d-none');
               }
               $('.leave_per_month_class').removeClass('d-none');

               //   $('#leave_per_month').val(result);
               $('#leave_per_month').val(parseFloat(result).toFixed(2));

          });

     });
</script>

{{ Form::close() }}
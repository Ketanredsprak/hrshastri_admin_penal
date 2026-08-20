{{ Form::open(['route' => ['timesheet.store']]) }}
<div class="modal-body">
    <div class="row">

        {{-- @if (\Auth::user()->type != 'employee')
            <div class="form-group col-md-12">
                {{ Form::label('employee_id', __('Employee'), ['class' => 'col-form-label']) }} <span class="text-danger"> * </span>
                {!! Form::select('employee_id', $employees, null, ['class' => 'form-control  select2' , 'id'=>'choices-multiple', 'required' => 'required','placeholder'=>'Select employee']) !!}
            </div>
        @endif --}}
        <div class="form-group col-md-6">
            {{ Form::label('date', __('Date'), ['class' => 'col-form-label']) }} <span class="text-danger"> * </span>
            {{ Form::date('date', '', ['class' => 'form-control d_week current_date','id'=>'date', 'onblur'=>'date_currunt();', 'autocomplete' => 'off','placeholder'=>'Select date']) }}
            <label style="display:none" generated="true" class="errorAll" id="date_currunt_error" for="date_currunt_error">&nbsp;</label>
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('hours', __('Hours'), ['class' => 'col-form-label']) }} <span class="text-danger"> * </span>
            {{ Form::number('hours', '', ['class' => 'form-control','autocomplete' => 'off', 'id' => 'hours', 'onblur'=>'hours_date();', 'step' => '0.01' ,'placeholder'=>'Enter hours']) }}
            <label style="display:none" generated="true" class="errorAll" id="hours_error" for="hours_error">&nbsp;</label>
        </div>
        <div class="form-group  col-md-12">
            {{ Form::label('remark', __('Remark'), ['class' => 'col-form-label']) }}
            {!! Form::textarea('remark', null, ['class' => 'form-control', 'rows' => '2' ,'placeholder'=>'Enter remark']) !!}
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }} </button>
    {{-- <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary" onclick="return submit()";> --}}
    <button type="submit" onclick="return submit_data();"  name="submit" class="btn btn-primary">Create</button>
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
</script>


    <script>
        function submit_data(){
            var status = true;
            if(date_currunt() == false) {
                status = false;
            }
            if(hours_date() == false) {
                status = false;
            }
            return status;
        }
    
        function date_currunt(){
            var date_currunt = $("#date").val()
            if(date_currunt == ''){
                jQuery("#date_currunt").removeClass("right");
                jQuery("#date_currunt").next("span").removeClass("right");
                jQuery("#date_currunt").addClass("wrong");
                jQuery("#date_currunt").next("span").addClass("wrong");
                jQuery("#date_currunt").css("border","1px solid red");
                jQuery("#date_currunt_error").css("display","");
                jQuery("#date_currunt_error").css("color","red");
                jQuery("#date_currunt_error").html('This field is required');
                return false;
            }else{
                jQuery("#date_currunt").removeClass("wrong");
                jQuery("#date_currunt").next("span").removeClass("wrong");
                jQuery("#date_currunt").addClass("right");
                jQuery("#date_currunt").next("span").addClass("right");
                jQuery("#date_currunt").css("border","1px solid #ccc");
                jQuery("#date_currunt_error").css("display","none");
                return true;
            }
        }
    
        function hours_date(){
            var hours = $("#hours").val()
            if(hours == ''){
                jQuery("#hours").removeClass("right");
                jQuery("#hours").next("span").removeClass("right");
                jQuery("#hours").addClass("wrong");
                jQuery("#hours").next("span").addClass("wrong");
                jQuery("#hours").css("border","1px solid red");
                jQuery("#hours_error").css("display","");
                jQuery("#hours_error").css("color","red");
                jQuery("#hours_error").html('This field is required');
                return false;
            }else{
                jQuery("#hours").removeClass("wrong");
                jQuery("#hours").next("span").removeClass("wrong");
                jQuery("#hours").addClass("right");
                jQuery("#hours").next("span").addClass("right");
                jQuery("#hours").css("border","1px solid #ccc");
                jQuery("#hours_error").css("display","none");
                return true;
            }
        }
    </script>
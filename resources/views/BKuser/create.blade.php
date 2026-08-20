{!! Form::open(['route' => 'user.store', 'method' => 'post']) !!}
<div class="modal-body">
    <div class="row">
        <div class="form-group">
            {{ Form::label('name', __('Employee  '), ['class' => 'form-label']) }} <span class="text-danger"> * </span>
            <div class="form-icon-user">
                <select name="employee_id" class="form-control select2" id="employee_id">
                    <option value="">Please Select</option>
                    @foreach ($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                    @endforeach
                </select>
                {{-- {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required','placeholder'=>'Enter Name']) !!} --}}
            </div>
        </div>
        {{-- <div class="form-group">
            {{ Form::label('email', __('Email *'), ['class' => 'form-label']) }}
            <div class="form-icon-user">
                {!! Form::text('email', null, ['class' => 'form-control', 'required' => 'required', 'placeholder'=>'Enter Email']) !!}
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('password', __('Password *'), ['class' => 'form-label']) }}
            <div class="form-icon-user">
                {!! Form::password('password', ['class' => 'form-control', 'required' => 'required','placeholder'=>'Enter password']) !!}
            </div> --}}
        </div>
        @if (\Auth::user()->type != 'super admin')
            <div class="form-group">
                {{ Form::label('role', __('User Role *'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    <select name="role" class="form-control select_roles"required>
                        <option value="">Please Select</option>
                        @foreach($roles as $row)
                            <?php  $data = App\Models\RolesManagment::find($row); ?>
                            <option value="{{ $row }}" >{{ $data->name }}</option>
                        @endforeach
                    </select>
                </div>
                @error('role')
                    <span class="invalid-role" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        @endif

        @if (\Auth::user()->type != 'super admin')
            <div class="form-group">
            {{ Form::label('role', __('Select User Roles'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    <select name="not_req_assignrole" class="form-control select_roles" id="not_req_assignrole" required>
                        <option value="">Please Select</option>
                        @foreach($roles as $row)
                            <?php  $data = App\Models\RolesManagment::find($row); ?>
                            <option value="{{ $row }}" >{{ $data->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif

        <div class="form-group d-none" id="assign_user_hide">
            {{ Form::label('role', __('Assigen Staff'), ['class' => 'form-label']) }}
            <div class="form-icon-user">
                <select name="assign_user" class="form-control" id="assign_user">
                </select>
            </div>
        </div>
        {{-- @if (\Auth::user()->type != 'super admin')
            <div class="form-group">
                {{ Form::label('role', __('Assigen Employee'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                <select name="employees[]" class="select2"
                    id="employees" multiple>
                    @foreach ($employees as $emp)
                        <option value="">Select employee</option>
                        <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                    @endforeach
                </select>
                </div>
                @error('role')
                    <span class="invalid-role" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        @endif --}}

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary">

</div>
{!! Form::close() !!}
<script>
    // A $( document ).ready() block.
    $( document ).ready(function() {
        $(".select_roles").change(function() {
            $.ajax({
                url: "{{ route('get_users_data_this') }}" ,
                type: 'get',
                data: {data:$(this).val()},
                dataType: 'JSON',
                
                success: function (data) { 
                    $('#assign_user_hide').removeClass('d-none');
                    $('#assign_user').html("");
                    $('#assign_user').append("<option>Select option</option>");
                    $.each(data, function(key,val) {
                        $('#assign_user').append("<option value="+val.id+">"+val.name+"</option>");
                    });
                }
            }); 

        }); 
    });
    // select_roles
</script>

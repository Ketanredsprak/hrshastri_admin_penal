{{ Form::model($user, ['route' => ['user.update', $user->id], 'method' => 'PUT']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group">
            {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
            <div class="form-icon-user">
                {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder'=>'Enter Name']) !!}
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}
            <div class="form-icon-user">
                {!! Form::text('email', null, ['class' => 'form-control', 'required' => 'required','placeholder'=>'Enter Email']) !!}
            </div>
        </div>

        @if (\Auth::user()->type != 'super admin')
            <div class="form-group ">
                {{ Form::label('role', __('User Role'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    <select name="role" class="form-control select_roles"required>
                        <option value="">Please Select</option>
                        @foreach($roles as $row)
                        <?php 
                            if($user->roles_types == $row){
                                $select = "selected";
                            }else{
                                $select = null;
                            }
                        ?>
                            <?php  $data = App\Models\RolesManagment::find($row); ?>
                            <option {{$select}} value="{{ $data->id }}" >{{ $data->name }}</option>
                        @endforeach
                    </select>
                
                    <!-- {!! Form::select('role', $roles, $user->roles, ['class' => 'form-control select2 ', 'required' => 'required']) !!} -->
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
                {{ Form::label('role', __('Assigen Employee'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                <select name="employees[]" class="select2"
                    id="employees" multiple>
                    @foreach ($employees as $emp)
                        <option value="">Select employee</option>

                        
                        @if($user->id == $emp->users_types_id)
                            <option selected value="{{ $emp->id }}">{{ $emp->name }}</option>
                        @else
                            <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                        @endif
                    @endforeach
                </select>
                </div>
               
            </div>



        @endif

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn  btn-primary">

</div>
{!! Form::close() !!}



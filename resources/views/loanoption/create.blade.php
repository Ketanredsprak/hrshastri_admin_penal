



{{ Form::open(['url' => 'loanoption', 'method' => 'post']) }}
<div class="modal-body">

    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="form-group">
            {{ Form::label('days', __('Pay Slab *'), ['class' => 'form-label']) }} <span class="text-danger"> * </span>
            <div class="form-icon-user">
                <select class="form-control" name="play_slab" id="play_slab" required>
                    <option value="">Select Pay Slab</option>
                    @foreach ($data as $row)
                        <option value="{{$row->id}}">{{ $row->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('name', __('Name *'), ['class' => 'form-label']) }} <span class="text-danger"> * </span>
                <div class="form-icon-user">
                    {{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Loan Name')]) }}
                </div>
                @error('name')
                    <span class="invalid-name" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>

<!-- 
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('short_name', __('Short Name'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::text('short_name', null, ['class' => 'form-control', 'placeholder' => __('Enter Loan Short Name')]) }}
                </div>
                @error('short_name')
                    <span class="invalid-name" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div> -->

    <!-- <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('name', __('Type'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    <select class="form-control" name="type" id="type">
                        <option value="fixed">Fixed</option>
                        <option value="percentage">Percentage</option>
                    </select>
                </div>
                @error('name')
                    <span class="invalid-name" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div> -->

    

    <!-- <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('name', __('Amount'), ['class' => 'form-label']) }}
                <div class="form-icon-user">

                    {{ Form::number('amount', null, ['class' => 'form-control ', 'required' => 'required', 'step' => '0.01','placeholder'=>'Enter Amount','autocomplete'=>'off']) }}
                </div>
                @error('name')
                    <span class="invalid-name" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div> -->
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
</div>
{{ Form::close() }}

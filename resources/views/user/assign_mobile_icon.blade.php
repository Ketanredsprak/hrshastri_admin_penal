{{ Form::model($user, ['route' => ['assign_store_menu'], 'method' => 'post']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group">
            {{ Form::label('password', __('Company'), ['class' => 'form-label']) }}
            <div class="form-icon-user">
                <input id="company_name" type="text" class="form-control" name="company_name" value="{{ $user->name }}"
                    readonly>
                <input id="company_id" type="hidden" class="form-control" name="company_id" value="{{ $user->id }}"
                    readonly>
            </div>
        </div>

        <div class="form-group">
            {{ Form::label('mobile_menu', __('Mobile Menu'), ['class' => 'form-label']) }}
            <div class="form-icon-user">

                {!! $menuListHtml !!}

                {{-- @foreach ($data as $row)
                    @php
                        $check = App\Models\CompanyMobileMenu::where('menu_id', $row->id)
                            ->where('company_id', $user->id)
                            ->first();
                        if (!empty($check)) {
                            $check = 'checked';
                        }
                    @endphp
                    <div>
                        <input type="checkbox" id="menu_{{ $row->id }}" name="menu_id[]"
                            value="{{ $row->id }}" {{ $check }} />
                        <label for="menu_{{ $row->id }}">{{ $row->name }}</label>
                    </div>
                @endforeach --}}

            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn  btn-primary">
</div>
{{ Form::close() }}

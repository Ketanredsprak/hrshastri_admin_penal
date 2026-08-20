{{ Form::model($role, ['route' => ['roles.update', $role->id], 'method' => 'PUT']) }}
<div class="modal-body">
    <div class="row">


        <div class="form-group">

            <div class="form-group">

                <div class="form-icon-user">
                    <label for="is_hr">Name </label><br>
                    <input type="text" class="form-control" readonly value="{{ $role->name }}">
                </div>
            </div>


            <div class="form-group">
                {{ Form::label('name', __('option'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    <input type="checkbox" disabled id="is_hr" @if (!empty($is_option->name) == '1') checked @endif
                        class="align-middle checkbox_middle form-check-input" name="is_hr" value="HR">
                    <label for="is_hr"> HR</label><br>
                </div>
            </div>

            @if (!empty($permissions))
                <h6 class="my-3">{{ __('Assign Permission to Roles') }} </h6>
                <table class="table  mb-0" id="dataTable-1">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" class="align-middle checkbox_middle form-check-input"
                                    name="checkall" id="checkall" disabled>
                            </th>
                            <th>{{ __('Module') }} </th>
                            <th>{{ __('Permissions') }} </th>
                        </tr>
                    </thead>
                    <tbody>


                        <!-- 'Plan', -->
                        {{-- @php
                            $modules = [
                                'User',
                                'Role',
                                'Award',
                                'Transfer',
                                'Resignation',
                                'Travel',
                                'Promotion',
                                'Complaint',
                                'Contract',
                                'Warning',
                                'Termination',
                                'Department',
                                'Designation',
                                'Document Type',
                                'Branch',
                                'Award Type',
                                'Termination Type',
                                'Employee',
                                'Payslip Type',
                                'Allowance Option',
                                'Loan Option',
                                'Deduction Option',
                                'Set Salary',
                                'Allowance',
                                'Commission',
                                'Loan',
                                'Saturation Deduction',
                                'Other Payment',
                                'Overtime',
                                'Pay Slip',
                                'Account List',
                                'Payee',
                                'Payer',
                                'Income Type',
                                'Expense Type',
                                'Payment Type',
                                'Deposit',
                                'Expense',
                                'Transfer Balance',
                                'Event',
                                'Announcement',
                                'Leave Type',
                                'Leave',
                                'Meeting',
                                'Ticket',
                                'Attendance',
                                'TimeSheet',
                                'Holiday',
                                'Assets',
                                'Document',
                                'Employee Profile',
                                'Employee Last Login',
                                'Indicator',
                                'Appraisal',
                                'Goal Tracking',
                                'Goal Type',
                                'Competencies',
                                'Company Policy',
                                'Trainer',
                                'Training',
                                'Training Type',
                                'Job Category',
                                'Job Stage',
                                'Job',
                                'Job Application',
                                'Job OnBoard',
                                'Job Application Note',
                                'Job Application Skill',
                                'Custom Question',
                                'Interview Schedule',
                                'Career',
                                'Report',
                                'Performance Type',
                                'Gate Pass',
                                'Apply WFH',
                                'Early & Late Comming',
                                // 'Timesheet Request',
                                // 'Leave Request',
                                'Apply WFH Request',
                                'Early & Late Leave Request',
                                'Gate Pass Request',
                                'Resignation Request',
                                'Visit Request',
                                'Claim Request',
                            ];
                            if (Auth::user()->type == 'super admin') {
                                $modules[] = 'Language';
                            }
                        @endphp --}}
                        @foreach ($modules as $module)
                            <tr>
                                <td><input type="checkbox" class="align-middle ischeck  form-check-input"
                                        name="checkall" data-id="{{ str_replace(' ', '', $module) }}" disabled></td>
                                <td><label class="ischeck"
                                        data-id="{{ str_replace(' ', '', $module) }}">{{ ucfirst($module) }}</label>
                                </td>
                                <td>
                                    <div class="row">
                                        @if (in_array('Manage ' . $module, (array) $permissions))
                                            @if ($key = array_search('Manage ' . $module, $permissions))
                                                <div class="col-md-3 custom-control custom-checkbox">
                                                    {{ Form::checkbox('permissions[]', $key, $role->permission, ['class' => 'form-check-input isscheck isscheck_' . str_replace(' ', '', $module), 'id' => 'permission' . $key, 'disabled' => 'disabled']) }}
                                                    {{ Form::label('permission' . $key, 'Manage', ['class' => 'form-label font-weight-500']) }}<br>
                                                </div>
                                            @endif
                                        @endif
                                        @if (in_array('Create ' . $module, (array) $permissions))
                                            @if ($key = array_search('Create ' . $module, $permissions))
                                                <div class="col-md-3 custom-control custom-checkbox">
                                                    {{ Form::checkbox('permissions[]', $key, $role->permission, ['class' => 'form-check-input isscheck isscheck_' . str_replace(' ', '', $module), 'id' => 'permission' . $key, 'disabled' => 'disabled']) }}
                                                    {{ Form::label('permission' . $key, 'Create', ['class' => 'form-label font-weight-500']) }}<br>
                                                </div>
                                            @endif
                                        @endif
                                        @if (in_array('Edit ' . $module, (array) $permissions))
                                            @if ($key = array_search('Edit ' . $module, $permissions))
                                                <div class="col-md-3 custom-control custom-checkbox">
                                                    {{ Form::checkbox('permissions[]', $key, $role->permission, ['class' => 'form-check-input isscheck isscheck_' . str_replace(' ', '', $module), 'id' => 'permission' . $key, 'disabled' => 'disabled']) }}
                                                    {{ Form::label('permission' . $key, 'Edit', ['class' => 'form-label font-weight-500']) }}<br>
                                                </div>
                                            @endif
                                        @endif
                                        @if (in_array('Delete ' . $module, (array) $permissions))
                                            @if ($key = array_search('Delete ' . $module, $permissions))
                                                <div class="col-md-3 custom-control custom-checkbox">
                                                    {{ Form::checkbox('permissions[]', $key, $role->permission, ['class' => 'form-check-input isscheck isscheck_' . str_replace(' ', '', $module), 'id' => 'permission' . $key, 'disabled' => 'disabled']) }}
                                                    {{ Form::label('permission' . $key, 'Delete', ['class' => 'form-label font-weight-500']) }}<br>
                                                </div>
                                            @endif
                                        @endif
                                        @if (in_array('Show ' . $module, (array) $permissions))
                                            @if ($key = array_search('Show ' . $module, $permissions))
                                                <div class="col-md-3 custom-control custom-checkbox">
                                                    {{ Form::checkbox('permissions[]', $key, $role->permission, ['class' => 'form-check-input isscheck isscheck_' . str_replace(' ', '', $module), 'id' => 'permission' . $key, 'disabled' => 'disabled']) }}
                                                    {{ Form::label('permission' . $key, 'Show', ['class' => 'form-label font-weight-500']) }}<br>
                                                </div>
                                            @endif
                                        @endif
                                        @if (in_array('Move ' . $module, (array) $permissions))
                                            @if ($key = array_search('Move ' . $module, $permissions))
                                                <div class="col-md-3 custom-control custom-checkbox">
                                                    {{ Form::checkbox('permissions[]', $key, $role->permission, ['class' => 'form-check-input isscheck isscheck_' . str_replace(' ', '', $module), 'id' => 'permission' . $key, 'disabled' => 'disabled']) }}
                                                    {{ Form::label('permission' . $key, 'Move', ['class' => 'form-label font-weight-500']) }}<br>
                                                </div>
                                            @endif
                                        @endif
                                        @if (in_array('client permission ' . $module, (array) $permissions))
                                            @if ($key = array_search('client permission ' . $module, $permissions))
                                                <div class="col-md-3 custom-control custom-checkbox">
                                                    {{ Form::checkbox('permissions[]', $key, $role->permission, ['class' => 'form-check-input isscheck isscheck_' . str_replace(' ', '', $module), 'id' => 'permission' . $key, 'disabled' => 'disabled']) }}
                                                    {{ Form::label('permission' . $key, 'Client Permission', ['class' => 'form-label font-weight-500']) }}<br>
                                                </div>
                                            @endif
                                        @endif
                                        @if (in_array('invite user ' . $module, (array) $permissions))
                                            @if ($key = array_search('invite user ' . $module, $permissions))
                                                <div class="col-md-3 custom-control custom-checkbox">
                                                    {{ Form::checkbox('permissions[]', $key, $role->permission, ['class' => 'form-check-input isscheck isscheck_' . str_replace(' ', '', $module), 'id' => 'permission' . $key, 'disabled' => 'disabled']) }}
                                                    {{ Form::label('permission' . $key, 'Invite User ', ['class' => 'form-label font-weight-500']) }}<br>
                                                </div>
                                            @endif
                                        @endif

                                        @if (in_array('buy ' . $module, (array) $permissions))
                                            @if ($key = array_search('buy ' . $module, $permissions))
                                                <div class="col-md-3 custom-control custom-checkbox">
                                                    {{ Form::checkbox('permissions[]', $key, $role->permission, ['class' => 'form-check-input isscheck isscheck_' . str_replace(' ', '', $module), 'id' => 'permission' . $key, 'disabled' => 'disabled']) }}
                                                    {{ Form::label('permission' . $key, 'Buy', ['class' => 'form-label font-weight-500']) }}<br>
                                                </div>
                                            @endif
                                        @endif

                                        @if (in_array('Add ' . $module, (array) $permissions))
                                            @if ($key = array_search('Add ' . $module, $permissions))
                                                <div class="col-md-3 custom-control custom-checkbox">
                                                    {{ Form::checkbox('permissions[]', $key, $role->permission, ['class' => 'form-check-input isscheck isscheck_' . str_replace(' ', '', $module), 'id' => 'permission' . $key, 'disabled' => 'disabled']) }}
                                                    {{ Form::label('permission' . $key, 'Add ', ['class' => 'form-label font-weight-500']) }}<br>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

{{ Form::close() }}


<script>
    $(document).ready(function() {
        $("#checkall").click(function() {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
        $(".ischeck").click(function() {
            var ischeck = $(this).data('id');
            $('.isscheck_' + ischeck).prop('checked', this.checked);
        });
    });
</script>

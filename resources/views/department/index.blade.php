@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Department') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Department') }}</li>
@endsection

@section('action-button')
    @can('Create Department')
        <a href="#" data-url="{{ route('department.create') }}" data-ajax-popup="true"
            data-title="{{ __('Create New Department') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
            data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
@endsection

@section('content')
    <div class="row">
        {{-- <div class="col-3">
        @include('layouts.hrm_setup')
    </div> --}}
        <div class="col-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table" id="departments-table">
                            <thead>
                                <tr>
                                    <th>{{ __('No') }}</th>
                                    <th>{{ __('Branch') }}</th>
                                    <th>{{ __('Department') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th width="200px">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @foreach ($departments as $department)
                            <tr>
                                <td>{{ !empty($department->branch) ? $department->branch->name : '' }}</td>
                                <td>{{ $department->name }}</td>

                                <td>

                                    @if ($department->status == 'Active')

                                    <div class="action-btn bg-success ms-2">
                                        {!! Form::open(['method' => 'get','route' => ['department_in_active',
                                        $department->id], 'id' => 'delete-form-' . $department->id]) !!}
                                        <a href="#" class=" btn btn-sm  align-items-center bs-pass-para"
                                            data-bs-toggle="tooltip" title="" data-bs-original-title="InActive"
                                            aria-label="InActive"><i class="ti ti-check  text-white text-white"></i></a>
                                        </form>
                                    </div>

                                    <!-- <a href="{{ route('department_in_active',$department->id)}}" class="btn btn-success btn-sm"onclick="return confirm('Are you sure?')">Active</a> -->
                                    @else

                                    <div class="action-btn bg-danger ms-2">
                                        {!! Form::open(['method' => 'get','route' => ['department_active',
                                        $department->id], 'id' => 'delete-form-' . $department->id]) !!}
                                        <a href="#" class=" btn btn-sm  align-items-center bs-pass-para"
                                            data-bs-toggle="tooltip" title="" data-bs-original-title="Active"
                                            aria-label="Active"><i class="ti ti-check  text-white text-white"></i></a>
                                        </form>
                                    </div>

                                    <!-- <a href="{{ route('department_active',$department->id)}}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">In-Active</a> -->
                                    @endif
                                </td>

                                <td class="Action">
                                    <span>
                                        @can('Edit Department')
                                        <div class="action-btn bg-info ms-2">
                                            <a href="#" class=" btn btn-sm  align-items-center"
                                                data-url="{{  URL::to('department/' . $department->id . '/edit') }}"
                                                data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                data-title="{{ __('Edit Department') }}"
                                                data-bs-original-title="{{ __('Edit') }}">
                                                <i class="ti ti-pencil text-white"></i>
                                            </a>
                                        </div>
                                        @endcan


                                        @can('Delete Department')
                                        <div class="action-btn bg-danger ms-2">
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['department.destroy',
                                            $department->id], 'id' => 'delete-form-' . $department->id]) !!}
                                            <a href="#" class=" btn btn-sm  align-items-center bs-pass-para"
                                                data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                aria-label="Delete"><i
                                                    class="ti ti-trash text-white text-white"></i></a>
                                            </form>
                                        </div>
                                        @endcan
                                    </span>
                                </td>
                            </tr>
                            @endforeach --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#departments-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('department.index') }}",
                order: [
                    [2, 'desc']
                ],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'branch_name',
                        name: 'branch_name'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'status_badge',
                        name: 'status',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
            });
        });
    </script>
@endsection

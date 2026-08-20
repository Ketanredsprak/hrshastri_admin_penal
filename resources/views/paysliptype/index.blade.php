@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Pay Slab Type') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Pay Slab') }}</li>
@endsection

@section('action-button')
    @can('Create Payslip Type')
        <a href="#" data-url="{{ route('paysliptype.create') }}" data-ajax-popup="true"
            data-title="{{ __('Create Pay Slab Type') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
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
                        <table class="table" id="paysliptype-datatable">
                            <thead>
                                <tr>
                                    <th width="50px">{{ __('No.') }}</th>
                                    <th>{{ __('Pay Slab Type') }}</th>
                                    <th width="200px">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- @foreach ($paysliptypes as $paysliptype)
    <tr>
                                            <td>{{ $paysliptype->name }}</td>
                                            <td class="Action">
                                                 <span>
                                                      @can('Edit Payslip Type')
        <div class="action-btn bg-info ms-2">
                                                               <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                                    data-url="{{ URL::to('paysliptype/' . $paysliptype->id . '/edit') }}"
                                                                    data-ajax-popup="true" data-size="md"
                                                                    data-bs-toggle="tooltip" title=""
                                                                    data-title="{{ __('Edit Pay Slab Type') }}"
                                                                    data-bs-original-title="{{ __('Edit') }}">
                                                                    <i class="ti ti-pencil text-white"></i>
                                                               </a>
                                                          </div>
    @endcan

                                                      @can('Delete Payslip Type')
        <div class="action-btn bg-danger ms-2">
                                                               {!! Form::open([
                                                                   'method' => 'DELETE',
                                                                   'route' => ['paysliptype.destroy', $paysliptype->id],
                                                                   'id' => 'delete-form-' . $paysliptype->id,
                                                               ]) !!}
                                                               <a href="#"
                                                                    class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                                    data-bs-toggle="tooltip" title=""
                                                                    data-bs-original-title="Delete" aria-label="Delete"><i
                                                                         class="ti ti-trash text-white text-white"></i></a>
                                                               </form>
                                                          </div>
    @endcan
                                                 </span>
                                            </td>
                                       </tr>
    @endforeach -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#paysliptype-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('paysliptype.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });
    </script>
@endsection

@extends('layouts.admin')

@section('page-title')
{{ __('Manage Loan ') }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
<li class="breadcrumb-item">{{ __('Loan ') }}</li>
@endsection

@section('action-button')
@can('Create Allowance Option')
<a href="#" data-url="{{ route('loans.create') }}" data-ajax-popup="true" data-title="{{ __('Create New Loan ') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}"><i class="ti ti-plus"></i>
</a>
@endcan
@endsection

@section('content')
<div class="row">

     <div class="col-12">
          <div class="card">
               <div class="card-body table-border-style">

                    <div class="table-responsive">
                         <table class="table" id="pc-dt-simple">
                              <thead>
                                   <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('Employee') }}</th>
                                        <th>{{ __('Loan Options') }}</th>
                                        <th>{{ __('Title') }}</th>
                                        <th>{{ __('Loan Amount') }}</th>
                                        <th>{{ __('Start Date') }}</th>
                                        <th>{{ __('End Date') }}</th>
                                        <th width="200px">{{ __('Action') }}</th>
                                   </tr>
                              </thead>
                              <tbody>
                                   @foreach($loans as $loan)
                                   <tr>
                                        <td>{{ $loan->id}}</td>
                                        <td>
                                             <?php
                                             $data = App\Models\Employee::find($loan->employee_id);
                                             echo $data->name;
                                             ?>
                                        </td>
                                        <td>
                                             <?php
                                             $data = App\Models\LoanOption::find($loan->loan_option);
                                             echo $data->name;
                                             ?>

                                        </td>
                                        <td>{{ $loan->title }}</td>
                                        <td>{{ $loan->amount }}</td>
                                        <td>{{ $loan->start_date }}</td>
                                        <td>{{ $loan->end_date }}</td>

                                        <td class="Action">


                                                  @can('Edit Loan')
                                                  <div class="action-btn bg-info ms-2">
                                                  <a href="#" data-url="{{ URL::to('loan/'.$loan->id.'/edit') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Edit Loan')}}" class="action-btn btn-primary me-1 btn btn-sm d-inline-flex align-items-center" data-toggle="tooltip" data-original-title="{{__('Edit')}}"><i class="ti ti-pencil"></i></a>
                                                  </div>
                                                  @endcan

                                                  {{-- @can('View Loan') --}}
                                                  <div class="action-btn bg-info ms-2">
                                                  <a href="#" data-url="{{ route('loan.show', ['loan' => $loan->id]) }}" data-size="lg" data-ajax-popup="true" data-title="{{__('View')}}" class="action-btn btn-info me-1 btn btn-sm d-inline-flex align-items-center" data-toggle="tooltip" data-original-title="{{__('View')}}"><i class="ti ti-eye"></i></a>
                                                  </div>
                                                  {{-- @endcan --}}


                                                  <div class="action-btn bg-danger ms-2">
                                                       @if ($loan->loan_status == 0)     
                                                            {{ Form::model($loan, ['route' => ['loan.destroy', $loan->id], 'method' => 'DELETE']) }}
                                                                 <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="" 
                                                                 data-bs-original-title="Delete" aria-label="Delete"><i class="ti ti-trash text-white text-white"></i></a>
                                                            {!! Form::close() !!}
                                                       @endif
                                                  </div>
                                                  
                                        </td>

                                   </tr>
                                   @endforeach

                              </tbody>
                         </table>
                    </div>
               </div>
          </div>
     </div>
</div>
@endsection
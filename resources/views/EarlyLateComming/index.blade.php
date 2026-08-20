@extends('layouts.admin')


<!-- Include Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

<!-- Include Bootstrap Timepicker CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css">


@section('page-title')
{{ __('Short Leave') }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>

<li class="breadcrumb-item">{{ __('Short Leave') }}</li>
@endsection



@section('action-button')
@if (\Auth::user()->type != 'employee')
@can('Create Early & Late Comming')
<a href="#" data-url="{{ route('earlyandlate_leave.create') }}" data-ajax-popup="true" data-title="{{ __('Create Short Leave') }}" data-size="lg" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Short Leave') }}">

     <i class="ti ti-plus"></i>

</a>
@endcan
@else
<a href="#" data-url="{{ route('earlyandlate_leave.create') }}" data-ajax-popup="true" data-title="{{ __('Create Short Leave') }}" data-size="lg" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Short Leave') }}">

     <i class="ti ti-plus"></i>

</a>
@endif
@endsection



@section('content')
<div class="row">

     @php
     $pendingCount = $leaves->where('status', 0)->count();
     $approvedCount = $leaves->where('status', 1)->count();
     $rejectedCount = $leaves->where('status', 2)->count();
     @endphp

     <div class="col-xxl-4 col-md-4 col-sm-6">

          <div class="card">

               <div class="card-body">

                    <div class="row align-items-center justify-content-between">

                         <div class="col-auto mb-3 mb-sm-0">

                              <div class="d-flex align-items-center">

                                   <div class="theme-avtar bg-warning">

                                        <i class="ti ti-circle-minus"></i>

                                        <i class="fa-solid fa-lights-holiday"></i>

                                   </div>

                                   <div class="ms-3">

                                        <small class="text-muted">{{ __('Total') }}</small>

                                        <h6 class="m-0">{{ __('Pending') }}</h6>

                                   </div>

                              </div>

                         </div>

                         <div class="col-auto text-end">

                              <h4 class="m-0 text-warning">{{ $pendingCount }}</h4>

                         </div>

                    </div>

               </div>

          </div>

     </div>



     <div class="col-xxl-4 col-md-4 col-sm-6">

          <div class="card">

               <div class="card-body">

                    <div class="row align-items-center justify-content-between">

                         <div class="col-auto mb-3 mb-sm-0">

                              <div class="d-flex align-items-center">

                                   <div class="theme-avtar bg-success">

                                        <i class="ti ti-circle-check"></i>

                                   </div>

                                   <div class="ms-3">

                                        <small class="text-muted">{{ __('Total') }}</small>

                                        <h6 class="m-0">{{ __('Approved') }}</h6>

                                   </div>

                              </div>

                         </div>

                         <div class="col-auto text-end">

                              <h4 class="m-0 text-success">{{ $approvedCount }}</h4>

                         </div>

                    </div>

               </div>

          </div>

     </div>





     <div class="col-xxl-4 col-md-4 col-sm-6">

          <div class="card">

               <div class="card-body">

                    <div class="row align-items-center justify-content-between">

                         <div class="col-auto mb-3 mb-sm-0">

                              <div class="d-flex align-items-center">

                                   <div class="theme-avtar bg-danger">

                                        <i class="ti ti-circle-x"></i>

                                   </div>

                                   <div class="ms-3">

                                        <small class="text-muted">{{ __('Total') }}</small>

                                        <h6 class="m-0">{{ __('Rejected') }}</h6>

                                   </div>

                              </div>

                         </div>

                         <div class="col-auto text-end">

                              <h4 class="m-0 text-danger">{{ $rejectedCount }}</h4>

                         </div>

                    </div>

               </div>

          </div>

     </div>



     <div class="col-xl-12">

          <div class="card">

               <div class="card-header card-body table-border-style">

                    <div class="table-responsive">

                         <table class="table" id="pc-dt-simple">

                              <thead>

                                   <tr>

                                        <th>{{ __('Employee') }}</th>

                                        <th>{{ __('Date') }}</th>

                                        <th>{{ __('title') }}</th>

                                        <!-- <th>{{ __('In Time') }}</th>

                                        <th>{{ __('Out Time') }}</th> -->

                                        <th>{{ __('Reason') }}</th>

                                        <th>{{ __('Status') }}</th>



                                        <th width="200px">{{ __('Action') }}</th>

                                   </tr>

                              </thead>

                              <?php $total_leave_data = 0; ?>

                              <tbody>

                                   @foreach ($leaves as $leave)
                                   <tr>

                                        <td>

                                             {{ $leave->employee->name }}

                                        </td>

                                        <td>

                                             {{ \Auth::user()->dateFormat($leave->date) }}

                                        </td>

                                        <td>

                                             @if ($leave->name == 1)
                                             Early Leave
                                             @else
                                             Late coming
                                             @endif

                                        </td>

                                        <!-- <td>{{ $leave->in_time }}</td>

                                        <td>{{ $leave->out_time }}</td> -->

                                        <td>{{ substr($leave->resone, 0, 80) . '..' }}</td>










                                        <td>

                                             @if ($leave->status == 0)
                                             <div class="badge bg-warning p-2 px-3 rounded status-badge5">

                                                  Pending</div>
                                             @elseif($leave->status == 1)
                                             <div class="badge bg-success p-2 px-3 rounded status-badge5">

                                                  Approved</div>
                                             @elseif($leave->status == 2)
                                             <div class="badge bg-danger p-2 px-3 rounded status-badge5">

                                                  Reject</div>
                                             @endif

                                        </td>

                                        <td class="Action">

                                             <span>





                                                  @if (\Auth::user()->type != 'employee')
                                                  @if ($leave->status == 0)
                                                  @can('Edit Early & Late Comming')
                                                  <div class="action-btn bg-info ms-2">

                                                       <a href="#" class="mx-3 btn btn-sm  align-items-center" data-size="lg" data-url="{{ URL::to('earlyandlate_leave/' . $leave->id . '/edit') }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="" data-title="{{ __('Edit Short Leave') }}" data-bs-original-title="{{ __('Edit Short Leave') }}">

                                                            <i class="ti ti-pencil text-white"></i>

                                                       </a>

                                                  </div>

                                                  @endcan

                                                  @can('Delete Early & Late Comming')
                                                  

                                                       {!! Form::open([
                                                       'method' => 'DELETE',

                                                       'route' => ['earlyandlate_leave.destroy', $leave->id],

                                                       'id' => 'delete-form-' . $leave->id,
                                                       ]) !!}

                                                            <div class="action-btn bg-danger ms-2">

                                                       <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="" data-bs-original-title="Delete Short Leave" aria-label="Delete"><i class="ti ti-trash text-white text-white"></i></a>

                                                       </div>
                                                  </form>

                                                  @endcan
                                                  @endif
                                                  @else
                                                  @if ($leave->status == 0)
                                                  <div class="action-btn bg-info ms-2">

                                                       <a href="#" class="mx-3 btn btn-sm  align-items-center" data-size="lg" data-url="{{ URL::to('earlyandlate_leave/' . $leave->id . '/edit') }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="" data-title="{{ __('Edit Short Leave') }}" data-bs-original-title="{{ __('Edit Short Leave') }}">

                                                            <i class="ti ti-pencil text-white"></i>

                                                       </a>

                                                  </div>


                                                  <div class="action-btn bg-danger ms-2">

                                                       {!! Form::open([
                                                       'method' => 'DELETE',

                                                       'route' => ['earlyandlate_leave.destroy', $leave->id],

                                                       'id' => 'delete-form-' . $leave->id,
                                                       ]) !!}

                                                       <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="" data-bs-original-title="Delete Short Leave" aria-label="Delete"><i class="ti ti-trash text-white text-white"></i></a>

                                                       </form>

                                                  </div>
                                                  @endif
                                                  @endif





                                             </span>

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

</div>
@endsection
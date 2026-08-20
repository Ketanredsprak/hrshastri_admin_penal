@extends('layouts.admin')

@section('page-title')
{{ __('Manage Resignation') }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
<li class="breadcrumb-item">{{ __('Resignation') }}</li>
@endsection

@if (\Auth::user()->type != 'company')
@section('action-button')
@can('Create Resignation')
<a href="#" data-url="{{ route('resignation.create') }}" data-ajax-popup="true" data-size="lg" data-title="{{ __('Create New Resignation') }}" data-size="lg" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
     <i class="ti ti-plus"></i>
</a>
@endcan
@endsection
@endif

@section('content')
<div class="row">

     <div class="col-xl-12">
          <div class="card">
               <div class="card-header card-body table-border-style">
                    {{-- <h5> </h5> --}}
                    <div class="table-responsive">
                         <table class="table" id="pc-dt-simple">
                              <thead>
                                   <tr>
                                        @role('company')
                                        <th>{{ __('Employee Name') }}</th>
                                        @endrole
                                        <th>{{ __('Resignation Date') }}</th>
                                        <th>{{ __('Last Working Day') }}</th>
                                        <th>{{ __('Reason') }}</th>
                                        <th>{{ __('Notes') }}</th>
                                        <th>{{ __('status') }}</th>


                                        @if (Gate::check('Edit Resignation') || Gate::check('Delete Resignation'))
                                        <th width="200px">{{ __('Action') }}</th>
                                        @endif
                                   </tr>
                              </thead>
                              <tbody>


                                   @foreach ($resignations as $resignation)
                                   <tr>
                                        @role('company')
                                        <td>{{ !empty($resignation->employee()) ? $resignation->employee()->name : '' }}</td>
                                        @endrole
                                        <td>{{ \Auth::user()->dateFormat($resignation->resignation_date) }}</td>
                                        <td>{{ \Auth::user()->dateFormat($resignation->notice_date) }}</td>

                                        <td>
                                             {{-- data-bs-toggle="tooltip"  --}}
                                             <p title="{{$resignation->description}}">
                                                  {{ substr($resignation->description, 0, 60) . '..' }}
                                             </p>
                                        </td>

                                        <td>
                                             <p title="{{$resignation->notes}}">
                                                  {{ substr($resignation->notes, 0, 60) . '..' }}
                                             </p>
                                        </td>

                                        <td>
                                             @if ($resignation->status == 0)
                                                  <div class="badge bg-warning p-2 px-3 rounded status-badge5">pending</div>
                                             @elseif ($resignation->status == 1)
                                                  <div class="badge bg-success p-2 px-3 rounded status-badge5">Approve</div>
                                             @elseif ($resignation->status == 2)
                                                  <div class="badge bg-danger p-2 px-3 rounded status-badge5">Rejected</div>
                                             @else
                                                  <div class="badge bg-danger p-2 px-3 rounded status-badge5"></div>
                                             @endif

                                        </td>

                                        @if (Gate::check('Edit Resignation') || Gate::check('Delete Resignation'))

                                        <td class="Action">
                                             <span>
                                                  @can('Edit Resignation')
                                                  @if ($resignation->status == 0)
                                                  <div class="action-btn bg-info ms-2">
                                                       <a href="#" class="mx-3 btn btn-sm  align-items-center" data-size="lg" data-url="{{ URL::to('resignation/' . $resignation->id . '/edit') }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="" data-title="{{ __('Edit Resignation') }}" data-bs-original-title="{{ __('Edit') }}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                       </a>
                                                  </div>
                                                  @endif
                                                  @endcan

                                                  @can('Delete Resignation')
                                                  @if ($resignation->status == 0)
                                                  <div class="action-btn bg-danger ms-2">
                                                       {!! Form::open([
                                                       'method' => 'DELETE',
                                                       'route' => ['resignation.destroy', $resignation->id],
                                                       'id' => 'delete-form-' . $resignation->id,
                                                       ]) !!}
                                                       <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="" data-bs-original-title="Delete" aria-label="Delete"><i class="ti ti-trash text-white text-white"></i></a>
                                                       </form>
                                                  </div>
                                                  @endif
                                                  @endcan
                                             </span>
                                        </td>
                                        @endif
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

{{-- @push('script-page')
<script>
    $(document).ready(function() {
        
            $("#commonModal").on("submit", 'form', function(event) {
            
  
                event.preventDefault();
                var form_cust = this;
                var formData = new FormData(form_cust);
                var url = $(this).attr('action');
                var type = "POST";
                $.ajax({
                    context: 'commonModal',
                    type: type,
                    url: url,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status == 1) {
                            show_toastr('Success', response.message, 'success');
                            location.reload();
                        }
                        if (response.status == 0) {
                            $.each(response.message, function(key, value) {
                                toastr.error(value)
                            });
                        }
                    },
                })
                return false;
            });
    });
</script>
@endpush --}}
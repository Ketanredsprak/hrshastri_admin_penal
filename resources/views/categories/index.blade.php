@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Categories') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Categories') }}</li>
@endsection


@section('action-button')
    @can('Create Transfer')
        <a href="#" data-url="{{ route('categories.create') }}" data-ajax-popup="true"
            data-title="{{ __('Create New categorie') }}" data-size="lg" data-bs-toggle="tooltip" title=""
            class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
@endsection

@section('content')
<div class="row">

    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-body table-border-style">
                <div class="table-responsive">
                    <table class="table" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th>{{ __('Visit Type') }}</th>
                                <th>{{ __('Grade Type') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Amount status') }}</th>
                                <th>{{ __('Description') }}</th>
                                <th width="200px">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="">

                            @foreach ($Categories as $row)
                                <tr>
                                    <td>
                                        @if(!empty($row->visittypeData))
                                            {{ $row->visittypeData->name }}
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($row->gradetypeData))
                                            {{ $row->gradetypeData->name }}
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($row->CategorytypeData))
                                            {{ $row->CategorytypeData->name  }}
                                        @endif
                                    </td>
                                    <td>{{ $row->amount}}</td>
                                    <td>
                                        @if(!empty($row->amount_status) == 1)
                                            Per kilometer rate
                                        @elseif($row->amount_status == null)
                                        @else
                                            Fixed
                                        @endif
                                    </td>
                                    <td class="dispwrap">{{ $row->description }}</td>
                                    
                                    <td class="Action">
                                            <span>
                                                @can('Edit Transfer')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm  align-items-center" data-size="lg"
                                                            data-url="{{ URL::to('categories/' . $row->id . '/edit') }}"
                                                            data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                            title="" data-title="{{ __('Edit Categorie') }}"
                                                            data-bs-original-title="{{ __('Edit') }}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endcan

                                                @can('Delete Transfer')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['categories.destroy', $row->id], 'id' => 'delete-form-' . $row->id]) !!}
                                                        <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                            data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                            aria-label="Delete"><i
                                                                class="ti ti-trash text-white text-white"></i></a>
                                                        </form>
                                                    </div>
                                                @endcan
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
@endsection

@push('script-page')
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
@endpush
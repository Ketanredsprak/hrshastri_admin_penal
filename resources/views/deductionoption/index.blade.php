@extends('layouts.admin')

@section('page-title')
 {{ __('Manage Deduction') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Deduction ') }}</li>
@endsection

@section('action-button')
    @can('Create Deduction Option')
        <a href="#" data-url="{{ route('deductionoption.create') }}" data-ajax-popup="true"
            data-title="{{ __('Create New Deduction ') }}" data-bs-toggle="tooltip" title=""
            class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
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
                                <th>{{ __('Pay Slab') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Short Name') }}</th>
                                <th>{{ __('Type') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Employee') }}</th>
                                <th>{{ __('Display') }}</th>
                                <th>{{ __('Display if 0') }}</th>
                                <th width="200px">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($deductionoptions as $deductionoption)
                                <tr>

                                    <td>
                                        @if(!empty($deductionoption->playslab))
                                            {{ $deductionoption->playslab->name }}
                                        @endif
                                    </td>

                                    <td>{{ $deductionoption->name }}</td>
                                    <td>{{ $deductionoption->short_name }}</td>

                                    <td>
                                        {{$deductionoption->type}}
                                         
                                    </td>
                                    <td>
                                        @if($deductionoption->type == 'percentage')
                                            {{ $deductionoption->amount }} %
                                        @else
                                            {{ $deductionoption->amount }}
                                        @endif    
                                    
                                    </td>
                                    <td>
                                        <?php 
                                            if($deductionoption->employees_id == '0'){
                                                echo'All';
                                            }else{
                                                $dataOption = App\Models\Employee::find($deductionoption->employees_id);
                                                if(!empty($dataOption)){
                                                    echo $dataOption->name;
                                                }
                                            }
                                            
                                        ?>
                                    </td>  
                                    <td>
                                        @if($deductionoption->is_display == 'Yes')
                                            <span class="text-success">{{$deductionoption->is_display }}</span>
                                        @else
                                            <span class="text-danger">{{$deductionoption->is_display }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($deductionoption->is_display_slary_slip == 1)
                                            <span class="text-success">Yes</span>
                                        @else

                                            @if($deductionoption->is_display_slary_slip == 1)
                                                <span class="text-success">Yes</span>
                                            @else
                                                @if($deductionoption->amount != 0 || $deductionoption->amount != 00)
                                                    <span class="text-success">Yes</span>
                                                @else
                                                    <span class="text-danger">No</span>
                                                @endif
                                            @endif
                                        @endif
                                    </td>

                                    <td class="Action">
                                        <span>
                                            @can('Edit Deduction Option')
                                                <div class="action-btn bg-info ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                        data-url="{{ URL::to('deductionoption/' . $deductionoption->id . '/edit')}}"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                        data-title="{{ __('Edit Deduction ') }}"
                                                        data-bs-original-title="{{ __('Edit') }}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                            @endcan

                                            @can('Delete Deduction Option')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['deductionoption.destroy', $deductionoption->id], 'id' => 'delete-form-' . $deductionoption->id]) !!}
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

@extends('layouts.admin')

@section('page-title')
   {{ __('Manage Commission') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Commission ') }}</li>
@endsection

@section('action-button')
    @can('Create Allowance Option')
        <a href="#" data-url="{{ route('commission_option.create') }}" data-ajax-popup="true"
            data-title="{{ __('Create New commission ') }}" data-bs-toggle="tooltip" title=""
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
                                <th>{{ __('Short Name') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Type') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Employee') }}</th>
                                <th>{{ __('Commision of Payslab') }}</th>
                                <th width="200px">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($allowanceoptions as $allowanceoption)
                                <tr>
                                    <td>
                                        @if(!empty($allowanceoption->playslab))
                                            {{ $allowanceoption->playslab->name }}
                                        @endif
                                    </td>
                                    <td>{{ $allowanceoption->short_name }}</td>
                                    <td>{{ $allowanceoption->name }}</td>
                                    <td>{{$allowanceoption->type}}
                                          
                                    </td>
                                    <td>
                                        
                                    
                                        @if($allowanceoption->type == 'percentage')
                                            {{ $allowanceoption->amount }} %
                                        @else
                                            {{ $allowanceoption->amount }}
                                        @endif
                                        
                                        

                                    <td>
                                        <?php 
                                            if($allowanceoption->employees_id == '0'){
                                                echo'All';
                                            }else{
                                                $dataOption = App\Models\Employee::find($allowanceoption->employees_id);
                                                if(!empty($dataOption)){
                                                    echo $dataOption->name;
                                                }
                                            }
                                            
                                        ?>
                                    </td>  
                                    <td>
                                        <?php 
                                            if($allowanceoption->commision_types == '0'){
                                                echo'Basic';
                                            }else{
                                                $dataOption = App\Models\Commission_option::find($allowanceoption->commision_types);
                                                if(!empty($dataOption)){
                                                    echo $dataOption->name;
                                                }
                                            }
                                            
                                        ?>
                                    </td>  

                                    <td class="Action">
                                        <span>
                                            @can('Edit Allowance Option')
                                                <div class="action-btn bg-info ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                        data-url="{{ URL::to('commission_option/' . $allowanceoption->id . '/edit') }}"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                        data-title="{{ __('Edit commission ') }}"
                                                        data-bs-original-title="{{ __('Edit') }}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                            @endcan

                                            @can('Delete Allowance Option')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['commission_option.destroy', $allowanceoption->id], 'id' => 'delete-form-' . $allowanceoption->id]) !!}
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

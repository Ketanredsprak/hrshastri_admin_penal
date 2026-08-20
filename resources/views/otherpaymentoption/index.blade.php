@extends('layouts.admin')

@section('page-title')
   {{ __('Manage Other Payment ') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Other Payment ') }}</li>
@endsection

@section('action-button')
    @can('Create Allowance Option')
        <a href="#" data-url="{{ route('otherpaymentotion.create') }}" data-ajax-popup="true"
            data-title="{{ __('Create New Other Payment Option') }}" data-bs-toggle="tooltip" title=""
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
                                <th>{{ __('Allowance Option') }}</th>
                                <th>{{ __('Type') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Employee') }}</th>
                                <th width="200px">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($OtherPaymentotion as $Other)
                                <tr>
                                    <td>
                                        @if(!empty($Other->playslab))
                                            {{ $Other->playslab->name }}
                                        @endif
                                    </td>

                                    <td>{{ $Other->name }}</td>
                                    <td>
                                       {{$Other->type}}
                                    </td>
                                    <td>
                                        @if($Other->type == 'percentage')
                                            {{ $Other->amount }} %
                                        @else
                                            {{ $Other->amount }}
                                        @endif  

                                    </td>
                                    <td>
                                        <?php 
                                            if($Other->employees_id == '0'){
                                                echo'All';
                                            }else{
                                                $dataOption = App\Models\Employee::find($Other->employees_id);
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
                                                        data-url="{{ URL::to('otherpaymentotion/' . $Other->id . '/edit') }}"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                        data-title="{{ __('Edit Other Payment') }}"
                                                        data-bs-original-title="{{ __('Edit') }}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                            @endcan

                                            @can('Delete Allowance Option')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['otherpaymentotion.destroy', $Other->id], 'id' => 'delete-form-' . $Other->id]) !!}
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

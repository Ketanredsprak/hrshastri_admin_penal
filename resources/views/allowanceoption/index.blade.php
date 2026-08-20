@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Allowance ') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Allowance ') }}</li>
@endsection

@section('action-button')
    @can('Create Allowance Option')
        <a href="#" data-url="{{ route('allowanceoption.create') }}" data-ajax-popup="true"
            data-title="{{ __('Create New Allowance ') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
            data-bs-original-title="{{ __('Create') }}">
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
                                    <!-- <th>{{ __('Percentage of Payslab') }}</th> -->
                                    <th>{{ __('Display') }}</th>
                                    <th>{{ __('Display if 0') }}</th>
                                    <th width="200px">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($allowanceoptions as $allowanceoption)
                                    <tr>
                                        <td>
                                            @if (!empty($allowanceoption->playslab))
                                                {{ $allowanceoption->playslab->name }}
                                            @endif
                                        </td>
                                        <td>{{ $allowanceoption->short_name }}</td>
                                        <td>{{ $allowanceoption->name }}</td>
                                        <td>
                                            {{ $allowanceoption->type }}
                                        </td>
                                        <td>
                                            @if ($allowanceoption->type == 'percentage')
                                                {{ $allowanceoption->amount }} %
                                            @else
                                                {{ $allowanceoption->amount }}
                                            @endif
                                        </td>
                                        {{-- <td> --}}
                                        <?php
                                        //     if($allowanceoption->allowance_types == '0'){
                                        //         echo'Basic';
                                        //     }else{
                                        //         $dataOption = App\Models\AllowanceOption::find($allowanceoption->allowance_types);
                                        //         if(!empty($dataOption)){
                                        //             echo $dataOption->name;
                                        //         }
                                        //     }
                                        ?>
                                        {{-- </td>   --}}
                                        <td>
                                            @if ($allowanceoption->is_display == 'Yes')
                                                <span class="text-success">{{ $allowanceoption->is_display }}</span>
                                            @else
                                                <span class="text-danger">{{ $allowanceoption->is_display }}</span>
                                            @endif

                                        </td>
                                        <td>
                                            @if ($allowanceoption->is_display_slary_slip == 1)
                                                <span class="text-success">Yes</span>
                                            @else
                                                @if ($allowanceoption->amount != 0 || $allowanceoption->amount != 00)
                                                    <span class="text-success">Yes</span>
                                                @else
                                                    <span class="text-danger">No</span>
                                                @endif
                                            @endif

                                        </td>
                                        <td class="Action">
                                            <span>
                                                @can('Edit Allowance Option')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                            data-url="{{ URL::to('allowanceoption/' . $allowanceoption->id . '/edit') }}"
                                                            data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                            title="" data-title="{{ __('Edit Allowance ') }}"
                                                            data-bs-original-title="{{ __('Edit') }}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endcan

                                                @can('Delete Allowance Option')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open([
                                                            'method' => 'DELETE',
                                                            'route' => ['allowanceoption.destroy', $allowanceoption->id],
                                                            'id' => 'delete-form-' . $allowanceoption->id,
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

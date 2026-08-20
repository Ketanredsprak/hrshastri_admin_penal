@extends('layouts.admin')

@section('page-title')
    {{ __('Gate pass') }}
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Gate pass') }}</li>
@endsection

@section('action-button')
    @can('Create Leave')
        <a href="#" data-url="{{ route('getpass.create') }}" data-ajax-popup="true"
            data-title="{{ __('Create Gate pass') }}" data-size="lg" data-bs-toggle="tooltip" title=""
            class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Gate pass') }}">
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
                                    <th>{{ __('Employee') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('title') }}</th>
                                    <th>{{ __('In Time') }}</th>
                                    <th>{{ __('Out Time') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th width="200px">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <?php $total_leave_data = 0;?>
                            <tbody>
                                @foreach ($getpass as $pass)
                                    <tr>
                                        <td>
                                            @php 
                                                $data = App\Models\User::find($pass->employee_id);
                                            @endphp   
                                            {{ $data->name }}
                                        </td>
                                        <td>{{ \Auth::user()->dateFormat($pass->date ) }}</td>
                                        
                                        <td>
                                           @if($pass->name == 1)
                                                Personal work
                                           @else    
                                                Office Work
                                           @endif
                                        </td>
                                        <td>{{ $pass->in_time }}</td>
                                        <td>{{ $pass->out_time }}</td>
                                        <td>
                                            @if ($pass->status == 0)
                                                <div class="badge bg-warning p-2 px-3 rounded status-badge5">
                                                    Pending</div>
                                            @elseif($pass->status == 1)
                                                <div class="badge bg-success p-2 px-3 rounded status-badge5">
                                                    Approved</div>
                                            @elseif($pass->status  == 2)
                                                <div class="badge bg-danger p-2 px-3 rounded status-badge5">
                                                    Reject</div>
                                            @endif
                                        </td>
                                        <td class="Action">
                                            <span>
                                               

                                                @can('Edit Leave')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                            data-size="lg"
                                                            data-url="{{ URL::to('getpass/' . $pass->id . '/edit') }}"
                                                            data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                            title="" data-title="{{ __('Edit Gate Pass') }}"
                                                            data-bs-original-title="{{ __('Edit') }}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endcan
                                                @can('Delete Leave')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open([
                                                            'method' => 'DELETE',
                                                            'route' => ['getpass.destroy', $pass->id],
                                                            'id' => 'delete-form-' . $pass->id,
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
    </div>
@endsection


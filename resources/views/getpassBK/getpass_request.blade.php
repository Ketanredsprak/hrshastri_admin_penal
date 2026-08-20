@extends('layouts.admin')

@section('page-title')
    {{ __('Gate pass request') }}
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Gate pass request') }}</li>
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
                                @if(!empty($getpass))
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
                                               
                                                        
                                                <div class="action-btn bg-success ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                        data-size="lg"
                                                        data-url="{{ URL::to('getpass_changeaction/'. $pass->id.'') }}"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                        title="" data-title="{{ __('Gate Pass Action') }}"
                                                        data-bs-original-title="{{ __('Manage Gate Pass') }}">
                                                        <i class="ti ti-caret-right text-white"></i>
                                                    </a>
                                                </div>
                                                       
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
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection


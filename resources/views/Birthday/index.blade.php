
@extends('layouts.admin')

@section('page-title')
   {{ __('Notification') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Notification') }}</li>
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
                                <th>{{ __('title') }}</th>
                                <th>{{ __('details') }}</th>
                                <th>{{ __('Date') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>{{ $Employee->name }}</td>
                                <td>{{ $notification->title }}</td>
                                <td>{{ $notification->details }}</td>
                                <td>{{ $notification->date }}</td>
                            </tr>
                           

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

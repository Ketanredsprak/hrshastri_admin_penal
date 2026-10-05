@extends('layouts.admin')

@section('page-title')
    {{ __('Form 16 Preview') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="alert alert-warning">
            {{ __('Employer-prepared Form 16 Part B / Annexure preview. Official Part A (if uploaded) is a separate TRACES PDF.') }}
        </div>
        @include('form16.pdf.part_b', ['record' => $record, 'partB' => $partB])
        <a href="{{ route('form16.show', $record->id) }}" class="btn btn-secondary mt-3">{{ __('Back') }}</a>
    </div>
@endsection

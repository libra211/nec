@extends('admin.layouts.app')
@section('title', 'Create Country')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="fas fa-earth-africa text-success me-2"></i>Create Country</h1>
    <a href="{{ route('admin.countries.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

@include('admin.countries._form', [
    'action' => route('admin.countries.store'),
    'method' => 'POST',
    'button' => 'Create Country',
])
@endsection
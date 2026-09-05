@extends('admin.layouts.app')
@section('title', 'Edit Country')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="fas fa-earth-africa text-success me-2"></i>Edit Country: {{ $country->name }}</h1>
    <a href="{{ route('admin.countries.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

@include('admin.countries._form', [
    'action' => route('admin.countries.update', $country->id),
    'method' => 'PUT',
    'button' => 'Update Country',
    'country' => $country,
])
@endsection
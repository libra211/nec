@extends('admin.layouts.app')
@section('title', 'Edit Diaspora Mission')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="fas fa-plane-departure text-success me-2"></i>Edit Mission: {{ $mission->name }}</h1>
    <a href="{{ route('admin.diaspora-missions.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

@include('admin.diaspora-missions._form', [
    'action' => route('admin.diaspora-missions.update', $mission->id),
    'method' => 'PUT',
    'button' => 'Update Mission',
    'mission' => $mission,
])
@endsection
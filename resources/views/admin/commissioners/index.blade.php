@extends('admin.layouts.app', ['title' => 'Manage Commissioners'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Commissioners Management</h2>
    <a href="{{ route('admin.commissioners.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Add Commissioner</a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-hover" id="commissionersTable">
            <thead>
                <tr><th>#</th><th>Photo</th><th>Name</th><th>Position</th><th>State</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @isset($commissioners)
                    @foreach($commissioners as $c)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><img src="{{ asset($c->photo ?? 'assets/images/default-avatar.png') }}" alt="" width="40" height="40" class="rounded-circle"></td>
                        <td>{{ e($c->name) }}</td>
                        <td>{{ e($c->position) }}</td>
                        <td>{{ e($c->state ?? 'N/A') }}</td>
                        <td style="white-space:nowrap;">
                            <a href="{{ route('admin.commissioners.edit', $c->id) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></a>
                            <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete('{{ route('admin.commissioners.destroy', $c->id) }}')" title="Delete"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    @endforeach
                @endisset
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('extra_scripts')
<script>$(document).ready(function () { $('#commissionersTable').DataTable({ responsive: true }); });</script>
@endsection

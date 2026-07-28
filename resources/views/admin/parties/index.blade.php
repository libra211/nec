@extends('admin.layouts.app', ['title' => 'Manage Parties'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Political Parties Management</h2>
    <a href="{{ route('admin.parties.create') }}" class="btn btn-nec-green"><i class="fas fa-plus me-1"></i> Add Party</a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-hover" id="partiesTable">
            <thead>
                <tr><th>#</th><th>Logo</th><th>Party Name</th><th>Abbreviation</th><th>Leader</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @isset($parties)
                    @foreach($parties as $party)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><img src="{{ asset($party->logo ?? 'assets/images/party-default.png') }}" alt="" width="40" height="40" class="rounded"></td>
                        <td>{{ e($party->name) }}</td>
                        <td>{{ e($party->abbreviation ?? 'N/A') }}</td>
                        <td>{{ e($party->leader ?? 'N/A') }}</td>
                        <td><span class="badge bg-success">Registered</span></td>
                        <td>
                            <a href="{{ route('admin.parties.edit', $party->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                            <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete('{{ route('admin.parties.destroy', $party->id) }}')"><i class="fas fa-trash"></i></button>
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
<script>$(document).ready(function () { $('#partiesTable').DataTable({ responsive: true }); });</script>
@endsection

@extends('admin.layouts.app', ['title' => 'Manage Education Materials'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Education Materials</h2>
    <a href="{{ route('admin.education.create') }}" class="btn btn-nec-green"><i class="fas fa-plus me-1"></i> Add Material</a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Search title, type..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    @foreach(['published','draft','trash'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Language</th>
                    <th>Audience</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($materials as $item)
                <tr>
                    <td>{{ $loop->iteration + ($materials->currentPage() - 1) * $materials->perPage() }}</td>
                    <td>{{ e($item->title) }}</td>
                    <td><span class="badge bg-info">{{ e(ucfirst(str_replace('_', ' ', $item->content_type ?? 'document'))) }}</span></td>
                    <td>{{ e($item->language ?? 'English') }}</td>
                    <td>{{ e($item->target_audience ?? 'general') }}</td>
                    <td>
                        @if($item->status === 'published')
                            <span class="badge bg-success">Published</span>
                        @elseif($item->status === 'draft')
                            <span class="badge bg-secondary">Draft</span>
                        @else
                            <span class="badge bg-danger">Trash</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.education.edit', $item->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete('{{ route('admin.education.destroy', $item->id) }}')"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted">No education materials found.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $materials->withQueryString()->links() }}
    </div>
</div>
@endsection

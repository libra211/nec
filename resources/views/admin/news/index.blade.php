@extends('admin.layouts.app', ['title' => 'Manage News'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">News Management</h2>
    <a href="{{ route('admin.news.create') }}" class="btn btn-nec-green"><i class="fas fa-plus me-1"></i> Add Article</a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-hover" id="newsTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Author</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @isset($news)
                    @foreach($news as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ e($item->title) }}</td>
                        <td><span class="badge bg-info">{{ e($item->category ?? 'General') }}</span></td>
                        <td>{{ e($item->author ?? 'Admin') }}</td>
                        <td>
                            @if($item->is_published)
                                <span class="badge bg-success">Published</span>
                            @else
                                <span class="badge bg-secondary">Draft</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.news.edit', $item->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                            <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete('{{ route('admin.news.destroy', $item->id) }}')"><i class="fas fa-trash"></i></button>
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
<script>
    $(document).ready(function () { $('#newsTable').DataTable({ responsive: true }); });
</script>
@endsection

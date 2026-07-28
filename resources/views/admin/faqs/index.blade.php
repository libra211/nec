@extends('admin.layouts.app', ['title' => 'Manage FAQs'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">FAQs</h2>
    <a href="{{ route('admin.faqs.create') }}" class="btn btn-nec-green"><i class="fas fa-plus me-1"></i> Add FAQ</a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search question, answer..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
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
        <table class="table table-bordered table-hover" id="faqTable">
            <thead class="table-dark">
                <tr>
                    <th style="width:40px;">#</th>
                    <th>Order</th>
                    <th>Question</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($faqs as $item)
                <tr data-id="{{ $item->id }}">
                    <td>{{ $loop->iteration + ($faqs->currentPage() - 1) * $faqs->perPage() }}</td>
                    <td><input type="number" class="form-control form-control-sm faq-order" value="{{ $item->sort_order }}" data-id="{{ $item->id }}" style="width:70px;"></td>
                    <td>{{ e(Str::limit($item->question, 80)) }}</td>
                    <td><span class="badge bg-info">{{ e($item->category ?? 'General') }}</span></td>
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
                        <a href="{{ route('admin.faqs.edit', $item->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete('{{ route('admin.faqs.destroy', $item->id) }}')"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted">No FAQs found.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $faqs->withQueryString()->links() }}
    </div>
</div>
@endsection

@section('extra_scripts')
<script>
    $(document).on('change', '.faq-order', function() {
        var rows = [];
        $('#faqTable tbody tr').each(function() {
            var id = $(this).data('id');
            var order = $(this).find('.faq-order').val();
            if (id) rows.push({ id: id, order: order });
        });
        rows.sort(function(a, b) { return a.order - b.order; });
        var orderIds = rows.map(function(r) { return r.id; });
        $.post('{{ route("admin.faqs.reorder") }}', { order: orderIds, _token: '{{ csrf_token() }}' });
    });
</script>
@endsection

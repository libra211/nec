@extends('admin.layouts.app')
@section('title', 'Election Petitions')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="fas fa-gavel text-danger me-2"></i>Election Petitions</h1>
    <a href="{{ route('admin.petitions.create') }}" class="btn" style="background:var(--nec-green);color:#fff;"><i class="fas fa-plus me-1"></i> File Petition</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4"><input type="text" name="search" class="form-control" placeholder="Search petitioner, respondent, constituency..." value="{{ request('search') }}"></div>
            <div class="col-md-2">
                <select name="status" class="form-select"><option value="">All Status</option>
                    @foreach(['filed','hearing','decided','dismissed','withdrawn'] as $s)<option {{ request('status')===$s?'selected':'' }} value="{{ $s }}">{{ ucfirst($s) }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-1"><button class="btn btn-primary w-100"><i class="fas fa-filter"></i></button></div>
            <div class="col-md-2"><a href="{{ route('admin.petitions.index') }}" class="btn btn-outline-secondary w-100">Clear</a></div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr><th>#</th><th>Petition No.</th><th>Petitioner</th><th>Respondent</th><th>Constituency</th><th>Filing Date</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($petitions as $p)
                        <tr>
                            <td>{{ $p->id }}</td>
                            <td><strong>{{ $p->petition_number }}</strong></td>
                            <td>{{ $p->petitioner_name }}</td>
                            <td>{{ $p->respondent_name }}</td>
                            <td>{{ $p->constituency ?? '-' }}</td>
                            <td><small>{{ $p->filing_date ? \Carbon\Carbon::parse($p->filing_date)->format('d M Y') : '-' }}</small></td>
                            <td>
                                @php $colors=['filed'=>'info','hearing'=>'warning','decided'=>'success','dismissed'=>'secondary','withdrawn'=>'dark']; @endphp
                                <span class="badge bg-{{ $colors[$p->status] ?? 'secondary' }}">{{ ucfirst($p->status) }}</span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.petitions.edit', $p) }}" class="btn btn-outline-primary"><i class="fas fa-edit"></i></a>
                                    <form method="POST" action="{{ route('admin.petitions.destroy', $p) }}" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                                        <button class="btn btn-outline-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4"><i class="fas fa-inbox fa-2x mb-2 d-block"></i>No petitions found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $petitions->links() }}
    </div>
</div>
@endsection

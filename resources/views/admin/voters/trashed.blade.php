@extends('admin.layouts.app')
@section('title', 'Trashed Voters')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="fas fa-trash-alt text-danger me-2"></i>Trashed Voters</h1>
    <a href="{{ route('admin.voters.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 8px 10px 16px;font-size:0.75rem;letter-spacing:0.3px;">#</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">VOTER ID</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">FULL NAME</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">STATE</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">DELETED AT</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 16px 10px 12px;text-align:right;font-size:0.75rem;letter-spacing:0.3px;">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($voters as $voter)
                    <tr style="border-bottom:1px solid #f1f3f5;">
                        <td style="padding:10px 8px 10px 16px;color:#64748b;">{{ $loop->iteration }}</td>
                        <td style="padding:10px 12px;color:#475569;"><code>{{ e($voter->voter_id) }}</code></td>
                        <td style="padding:10px 12px;color:#1e293b;">{{ e($voter->full_name) }}</td>
                        <td style="padding:10px 12px;color:#475569;">{{ e($voter->state ?? 'N/A') }}</td>
                        <td style="padding:10px 12px;color:#64748b;">{{ $voter->deleted_at ? $voter->deleted_at->format('d M Y H:i') : 'N/A' }}</td>
                        <td style="padding:10px 16px 10px 12px;text-align:right;">
                            <form action="{{ route('admin.voters.restore', $voter->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(46,139,87,0.08);color:#2E8B57;border:none;" title="Restore"><i class="fas fa-undo"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center">
                                <div class="d-flex align-items-center justify-content-center mb-3" style="width:52px;height:52px;border-radius:14px;background:rgba(239,68,68,0.08);">
                                    <i class="fas fa-trash-alt" style="color:#ef4444;font-size:1.25rem;"></i>
                                </div>
                                <p class="text-muted mb-0">No trashed voters found.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($voters->hasPages() || $voters->total() > 0)
    <div class="card-footer bg-white border-top d-flex flex-wrap justify-content-between align-items-center gap-2 px-4 py-3">
        <span style="font-size:0.75rem;color:#64748b;">Showing {{ $voters->firstItem() }} to {{ $voters->lastItem() }} of {{ $voters->total() }} trashed voters</span>
        {{ $voters->links() }}
    </div>
    @endif
</div>
@endsection

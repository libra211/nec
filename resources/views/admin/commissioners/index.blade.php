@extends('admin.layouts.app', ['title' => 'Manage Commissioners'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Commissioners Management</h2>
    @if($can('commissioners.create'))
    <a href="{{ route('admin.commissioners.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Add Commissioner</a>
    @endif
</div>

<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle mb-0" id="commissionersTable">
            <thead>
                <tr>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 8px 10px 16px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">#</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Photo</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Name</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Position</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">State</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 16px 10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @isset($commissioners)
                    @foreach($commissioners as $c)
                    <tr style="border-bottom:1px solid #f1f3f5;">
                        <td style="padding:10px 8px 10px 16px;color:#1e293b;">{{ $loop->iteration }}</td>
                        <td style="padding:10px 12px;">
                            <div style="width:36px;height:36px;border-radius:50%;overflow:hidden;border:1px solid #eee;display:flex;align-items:center;justify-content:center;background:#fafafa;">
                                <img src="{{ asset($c->photo ?? 'assets/images/default-avatar.png') }}" alt="" style="width:100%;height:100%;object-fit:cover;">
                            </div>
                        </td>
                        <td style="padding:10px 12px;color:#1e293b;font-weight:600;">{{ $c->name }}</td>
                        <td style="padding:10px 12px;color:#475569;">{{ $c->position }}</td>
                        <td style="padding:10px 12px;color:#475569;">{{ $c->state ?? 'N/A' }}</td>
                        <td style="padding:10px 16px 10px 12px;white-space:nowrap;text-align:right;">
                            @if($can('commissioners.update'))
                            <a href="{{ route('admin.commissioners.edit', $c->id) }}" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(59,130,246,0.08);color:#3b82f6;border:none;" title="Edit"><i class="fas fa-edit"></i></a>
                            @endif
                            @if($can('commissioners.delete'))
                            <button class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(239,68,68,0.08);color:#ef4444;border:none;" onclick="confirmDelete('{{ route('admin.commissioners.destroy', $c->id) }}')" title="Delete"><i class="fas fa-trash"></i></button>
                            @endif
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

@extends('admin.layouts.app', ['title' => 'User Management'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">User Management</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.users.create') }}" class="btn" style="background:var(--nec-green);color:#fff;border:none;">
            <i class="fas fa-plus me-1"></i> Add New User
        </a>
    </div>
</div>

{{-- Filter Bar --}}
<div class="card border-0 shadow-sm rounded-3 mb-4" style="background:#f8fafc;border:1px solid #e9edf2;">
    <div class="card-body">
        <div class="d-flex align-items-center gap-2 mb-3">
            <span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:8px;background:rgba(46,139,87,0.1);color:#2E8B57;"><i class="fas fa-filter" style="font-size:0.75rem;"></i></span>
            <span style="font-size:0.85rem;font-weight:600;color:#1e293b;">Filters & Search</span>
            @if(request('search') || request('role') || request('status') || request('department'))
            <a href="{{ route('admin.users.index') }}" class="btn btn-sm ms-auto" style="font-size:0.75rem;color:#6b7280;text-decoration:none;"><i class="fas fa-times me-1"></i>Clear</a>
            @endif
        </div>
        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#6b7280;margin-bottom:4px;display:block;">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Name or email..." value="{{ request('search') }}" style="border-radius:8px;">
            </div>
            <div class="col-md-2">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#6b7280;margin-bottom:4px;display:block;">Role</label>
                <select name="role" class="form-select" style="border-radius:8px;">
                    <option value="">All Roles</option>
                    @foreach(['super_admin','admin','state_coordinator','constituency_officer','registration_officer','polling_officer','data_entry','content_editor','viewer'] as $r)
                        <option value="{{ $r }}" {{ request('role') === $r ? 'selected' : '' }}>{{ ucwords(str_replace('_', ' ', $r)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#6b7280;margin-bottom:4px;display:block;">Status</label>
                <select name="status" class="form-select" style="border-radius:8px;">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="trash" {{ request('status') === 'trash' ? 'selected' : '' }}>Trash</option>
                </select>
            </div>
            <div class="col-md-2">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#6b7280;margin-bottom:4px;display:block;">Department</label>
                <select name="department" class="form-select" style="border-radius:8px;">
                    <option value="">All Departments</option>
                    <option value="IT" {{ request('department') === 'IT' ? 'selected' : '' }}>IT</option>
                    <option value="Operations" {{ request('department') === 'Operations' ? 'selected' : '' }}>Operations</option>
                    <option value="Legal" {{ request('department') === 'Legal' ? 'selected' : '' }}>Legal</option>
                    <option value="Finance" {{ request('department') === 'Finance' ? 'selected' : '' }}>Finance</option>
                    <option value="Communications" {{ request('department') === 'Communications' ? 'selected' : '' }}>Communications</option>
                    <option value="Administration" {{ request('department') === 'Administration' ? 'selected' : '' }}>Administration</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-success w-100" style="border-radius:8px;"><i class="fas fa-search me-1"></i> Filter</button>
            </div>
            <div class="col-md-1">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary w-100" style="border-radius:8px;"><i class="fas fa-times"></i></a>
            </div>
        </form>
    </div>
</div>

{{-- Bulk Actions --}}
<form id="bulkActionForm" method="POST" action="{{ route('admin.users.bulk-action') }}">
    @csrf
    <input type="hidden" name="action" id="bulkActionType">
    <input type="hidden" name="ids" id="bulkActionIds">
</form>

<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="usersTable">
                <thead>
                    <tr>
                        <th width="30" style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 8px 10px 16px;font-size:0.75rem;letter-spacing:0.3px;"><input type="checkbox" id="selectAll"></th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">#</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Name</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Email</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Phone</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Role</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Department</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">State</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Status</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Last Login</th>
                        <th style="width:auto;background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 16px 10px 12px;text-align:right;font-size:0.75rem;letter-spacing:0.3px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @isset($users)
                        @foreach($users as $user)
                        <tr style="border-bottom:1px solid #f1f3f5;">
                            <td style="padding:10px 8px 10px 16px;"><input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="bulk-check"></td>
                            <td style="padding:10px 12px;color:#475569;">{{ $loop->iteration }}</td>
                            <td style="padding:10px 12px;color:#1e293b;">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('assets/images/default-avatar.png') }}" alt="" class="rounded-circle me-2" width="32" height="32">
                                    <strong>{{ $user->name }}</strong>
                                </div>
                            </td>
                            <td style="padding:10px 12px;color:#475569;">{{ $user->email }}</td>
                            <td style="padding:10px 12px;color:#475569;">{{ $user->phone ?? '-' }}</td>
                            <td style="padding:10px 12px;color:#475569;">
                                @php
                                    $roleBadges = [
                                        'super_admin' => 'danger',
                                        'admin' => 'primary',
                                        'state_coordinator' => 'success',
                                        'constituency_officer' => 'info',
                                        'registration_officer' => 'warning',
                                        'polling_officer' => 'secondary',
                                        'data_entry' => 'light',
                                        'content_editor' => 'purple',
                                        'viewer' => 'secondary'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $roleBadges[$user->role] ?? 'secondary' }}">{{ ucwords(str_replace('_', ' ', $user->role)) }}</span>
                            </td>
                            <td style="padding:10px 12px;color:#475569;">{{ $user->department ?? '-' }}</td>
                            <td style="padding:10px 12px;color:#475569;">{{ $user->state ?? '-' }}</td>
                            <td style="padding:10px 12px;color:#475569;">
                                @if($user->is_active ?? true)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td style="padding:10px 12px;color:#64748b;">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</td>
                            <td style="padding:10px 16px 10px 12px;text-align:right;white-space:nowrap;">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(59,130,246,0.08);color:#3b82f6;border:none;" title="Edit"><i class="fas fa-edit"></i></a>
                                    <button type="button" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba({{ ($user->is_active ?? true) ? '234,179,8' : '46,139,87' }},0.08);color:{{ ($user->is_active ?? true) ? '#ca8a04' : '#2E8B57' }};border:none;" title="{{ ($user->is_active ?? true) ? 'Deactivate' : 'Activate' }}" onclick="toggleStatus('{{ route('admin.users.status', $user->id) }}', {{ ($user->is_active ?? true) ? 'false' : 'true' }})"><i class="fas fa-{{ ($user->is_active ?? true) ? 'ban' : 'check' }}"></i></button>
                                    <button type="button" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(6,182,212,0.08);color:#0891b2;border:none;" title="Reset Password" onclick="resetPassword('{{ route('admin.users.reset-password', $user->id) }}')"><i class="fas fa-key"></i></button>
                                    <button type="button" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(239,68,68,0.08);color:#ef4444;border:none;" title="Delete" onclick="confirmDelete('{{ route('admin.users.destroy', $user->id) }}')"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    @endisset
                </tbody>
            </table>
        </div>
    </div>
    @isset($users)
        @if(method_exists($users, 'hasPages') && $users->hasPages())
        <div class="card-footer bg-white border-top d-flex flex-wrap justify-content-between align-items-center gap-2 px-4 py-3">
            <div style="font-size:0.75rem;color:#64748b;">Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users</div>
            <div>{{ $users->withQueryString()->links() }}</div>
        </div>
        @endif
    @endisset
</div>

{{-- Bulk Actions --}}
<div class="d-flex justify-content-between align-items-center mt-3">
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(46,139,87,0.08);color:#2E8B57;border:none;font-size:0.75rem;" onclick="bulkAction('activate')"><i class="fas fa-check me-1"></i> Activate Selected</button>
        <button type="button" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(234,179,8,0.08);color:#ca8a04;border:none;font-size:0.75rem;" onclick="bulkAction('deactivate')"><i class="fas fa-ban me-1"></i> Deactivate Selected</button>
        <button type="button" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(239,68,68,0.08);color:#ef4444;border:none;font-size:0.75rem;" onclick="bulkAction('delete')"><i class="fas fa-trash me-1"></i> Delete Selected</button>
    </div>
    <div>
        @isset($users)
            {{ $users->withQueryString()->links() }}
        @endisset
    </div>
</div>

{{-- Edit User Modal --}}
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-user-edit me-2"></i>Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" id="edit_email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" id="edit_phone" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role *</label>
                            <select name="role" id="edit_role" class="form-select" required>
                                @foreach(['super_admin','admin','state_coordinator','constituency_officer','registration_officer','polling_officer','data_entry','content_editor','viewer'] as $r)
                                    <option value="{{ $r }}">{{ ucwords(str_replace('_', ' ', $r)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Department</label>
                            <select name="department" id="edit_department" class="form-select">
                                <option value="">Select Department</option>
                                <option value="IT">IT</option>
                                <option value="Operations">Operations</option>
                                <option value="Legal">Legal</option>
                                <option value="Finance">Finance</option>
                                <option value="Communications">Communications</option>
                                <option value="Administration">Administration</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">State</label>
                            <input type="text" name="state" id="edit_state" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Position</label>
                            <input type="text" name="position" id="edit_position" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Employee ID</label>
                            <input type="text" name="employee_id" id="edit_employee_id" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">New Password <small class="text-muted">(leave blank to keep current)</small></label>
                            <input type="password" name="password" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" id="edit_notes" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn" style="background:var(--nec-green);color:#fff;border:none;">Update User</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('extra_scripts')
<style>.badge.bg-purple { background-color: #6f42c1 !important; }</style>
<script>
$(document).ready(function () {
    $('#usersTable').DataTable({ responsive: true, order: [[1, 'asc']] });

    $('#selectAll').on('change', function () {
        $('.bulk-check').prop('checked', this.checked);
    });

    window.editUser = function (id, name, email, phone, role, department, state, position, employeeId, notes) {
        $('#editUserForm').attr('action', '/admin/users/' + id);
        $('#edit_name').val(name);
        $('#edit_email').val(email);
        $('#edit_phone').val(phone);
        $('#edit_role').val(role);
        $('#edit_department').val(department);
        $('#edit_state').val(state);
        $('#edit_position').val(position);
        $('#edit_employee_id').val(employeeId);
        $('#edit_notes').val(notes);
        new bootstrap.Modal(document.getElementById('editUserModal')).show();
    };

    window.resetPassword = function (url) {
        Swal.fire({
            title: 'Reset Password?', text: 'A new password will be generated and sent to the user.',
            icon: 'question', showCancelButton: true,
            confirmButtonColor: '#2E8B57', cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, reset it'
        }).then(function (result) {
            if (result.isConfirmed) {
                $.ajax({ url: url, type: 'POST', success: function () {
                    Swal.fire('Done!', 'Password has been reset.', 'success');
                }});
            }
        });
    };

    window.bulkAction = function (action) {
        var checked = [];
        $('.bulk-check:checked').each(function () { checked.push($(this).val()); });
        if (checked.length === 0) {
            Swal.fire('No selection', 'Please select at least one user.', 'warning');
            return;
        }
        var labels = { activate: 'activate', deactivate: 'deactivate', delete: 'delete' };
        Swal.fire({
            title: 'Bulk ' + labels[action] + '?',
            text: 'This will ' + labels[action] + ' ' + checked.length + ' user(s).',
            icon: 'warning', showCancelButton: true,
            confirmButtonColor: action === 'delete' ? '#d33' : '#2E8B57',
            confirmButtonText: 'Confirm'
        }).then(function (result) {
            if (result.isConfirmed) {
                $('#bulkActionType').val(action);
                $('#bulkActionIds').val(checked.join(','));
                $('#bulkActionForm').submit();
            }
        });
    };
});
</script>
@endsection
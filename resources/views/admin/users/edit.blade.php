@extends('admin.layouts.app', ['title' => 'Edit User'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0">Edit User</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
                <li class="breadcrumb-item active">Edit - {{ $user->name }}</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-4">
                {{-- Personal Information --}}
                <div class="col-12">
                    <h5 class="border-bottom pb-2" style="color:var(--nec-green)"><i class="fas fa-user me-2"></i>Personal Information</h5>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}">
                    @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Avatar</label>
                    <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror" accept="image/*">
                    @if($user->avatar)
                        <div class="mt-2"><img src="{{ asset('storage/' . $user->avatar) }}" alt="" class="rounded-circle" width="40" height="40"></div>
                    @endif
                    @error('avatar') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- Account Information --}}
                <div class="col-12 mt-4">
                    <h5 class="border-bottom pb-2" style="color:var(--nec-green)"><i class="fas fa-key me-2"></i>Account Information</h5>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Password <small class="text-muted">(leave blank to keep current)</small></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                    @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Role *</label>
                    <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                        @foreach(['super_admin','admin','state_coordinator','constituency_officer','registration_officer','polling_officer','data_entry','content_editor','viewer'] as $r)
                            <option value="{{ $r }}" {{ old('role', $user->role) === $r ? 'selected' : '' }}>{{ ucwords(str_replace('_', ' ', $r)) }}</option>
                        @endforeach
                    </select>
                    @error('role') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- Employment Information --}}
                <div class="col-12 mt-4">
                    <h5 class="border-bottom pb-2" style="color:var(--nec-green)"><i class="fas fa-building me-2"></i>Employment Information</h5>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Department</label>
                    <select name="department" class="form-select @error('department') is-invalid @enderror">
                        <option value="">Select Department</option>
                        @foreach(['IT','Operations','Legal','Finance','Communications','Administration'] as $d)
                            <option value="{{ $d }}" {{ old('department', $user->department) === $d ? 'selected' : '' }}>{{ $d }}</option>
                        @endforeach
                    </select>
                    @error('department') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">State</label>
                    <input type="text" name="state" class="form-control @error('state') is-invalid @enderror" value="{{ old('state', $user->state) }}">
                    @error('state') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Position</label>
                    <input type="text" name="position" class="form-control @error('position') is-invalid @enderror" value="{{ old('position', $user->position) }}">
                    @error('position') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Employee ID</label>
                    <input type="text" name="employee_id" class="form-control @error('employee_id') is-invalid @enderror" value="{{ old('employee_id', $user->employee_id) }}">
                    @error('employee_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- Notes --}}
                <div class="col-12 mt-4">
                    <h5 class="border-bottom pb-2" style="color:var(--nec-green)"><i class="fas fa-sticky-note me-2"></i>Notes</h5>
                </div>
                <div class="col-12">
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $user->notes) }}</textarea>
                    @error('notes') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- Actions --}}
                <div class="col-12 mt-4">
                    <hr>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn" style="background:var(--nec-green);color:#fff;border:none;">
                            <i class="fas fa-save me-1"></i> Update User
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

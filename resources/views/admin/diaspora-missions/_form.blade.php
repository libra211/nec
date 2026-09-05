@php $mission = $mission ?? null; @endphp
<div class="card">
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
        @endif

        <form action="{{ $action }}" method="POST">
            @csrf
            @method($method)
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Country *</label>
                    <select name="country_id" class="form-select" required>
                        <option value="">Select country...</option>
                        @foreach($countries as $c)
                            <option value="{{ $c->id }}" {{ old('country_id', $mission->country_id ?? '') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Mission / Premises Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $mission->name ?? '') }}" placeholder="e.g. Embassy of South Sudan" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">City *</label>
                    <input type="text" name="city" class="form-control" value="{{ old('city', $mission->city ?? '') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Code</label>
                    <input type="text" name="code" class="form-control" maxlength="20" value="{{ old('code', $mission->code ?? '') }}" placeholder="e.g. NAIROBI-01">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-select" required>
                        <option value="active" {{ old('status', $mission->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $mission->status ?? 'active') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address', $mission->address ?? '') }}" placeholder="Street, building, district">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $mission->phone ?? '') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $mission->email ?? '') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Latitude</label>
                    <input type="number" step="any" name="latitude" class="form-control" value="{{ old('latitude', $mission->latitude ?? '') }}" placeholder="-90 to 90">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Longitude</label>
                    <input type="number" step="any" name="longitude" class="form-control" value="{{ old('longitude', $mission->longitude ?? '') }}" placeholder="-180 to 180">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i> {{ $button }}</button>
                    <a href="{{ route('admin.diaspora-missions.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
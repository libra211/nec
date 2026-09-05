@php $country = $country ?? null; @endphp
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
                <div class="col-md-8">
                    <label class="form-label">Country Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $country->name ?? '') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">ISO Code (2 letter) *</label>
                    <input type="text" name="code" class="form-control" maxlength="2" value="{{ old('code', $country->code ?? '') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">ISO3 Code (3 letter)</label>
                    <input type="text" name="iso3" class="form-control" maxlength="3" value="{{ old('iso3', $country->iso3 ?? '') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nationality</label>
                    <input type="text" name="nationality" class="form-control" value="{{ old('nationality', $country->nationality ?? '') }}" placeholder="e.g. South Sudanese">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Calling Code</label>
                    <input type="text" name="calling_code" class="form-control" value="{{ old('calling_code', $country->calling_code ?? '') }}" placeholder="e.g. 211">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Continent</label>
                    <select name="continent" class="form-select">
                        <option value="">Select continent...</option>
                        @foreach(['Africa','Asia','Europe','North America','South America','Oceania','Antarctica'] as $c)
                            <option value="{{ $c }}" {{ old('continent', $country->continent ?? '') === $c ? 'selected' : '' }}>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-select" required>
                        <option value="active" {{ old('status', $country->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $country->status ?? 'active') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i> {{ $button }}</button>
                    <a href="{{ route('admin.countries.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
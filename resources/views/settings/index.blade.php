@extends('layouts.app')

@section('title', 'Settings | Restaurant Reservation CRM')
@section('page_title', 'Settings')

@section('content')
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card card-soft">
                <div class="card-header bg-white border-0 rounded-top-4 p-4">
                    <h5 class="mb-0">Add setting</h5>
                    <div class="small text-muted">Create custom values for this installation.</div>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('settings.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="meta_key" class="form-label">Field</label>
                            <select name="meta_key" id="meta_key" class="form-select rounded-4" required>
                                @foreach ($settingKeys as $key => $label)
                                    <option value="{{ $key }}" @selected(old('meta_key') === $key)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="meta_value" class="form-label">Value</label>
                            <input
                                type="text"
                                name="meta_value"
                                id="meta_value"
                                class="form-control rounded-4"
                                value="{{ old('meta_value') }}"
                                placeholder="Example: USD or Downtown Grill"
                                required
                            >

                            <div class="form-text">
                                Currency values are automatically saved in uppercase.
                            </div>
                        </div>

                        <button type="submit" class="btn btn-accent rounded-pill px-4">
                            <i class="bi bi-plus-lg me-1"></i> Add setting
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card card-soft">
                <div class="card-header bg-white border-0 rounded-top-4 p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">All settings</h5>
                        <div class="small text-muted">Manage reservation dropdown values in one list.</div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>Field</th>
                            <th>Meta key</th>
                            <th>Value</th>
                            <th class="text-end">Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse ($settings as $setting)
                            <tr>
                                <td>
                                    <span class="badge text-bg-light">
                                        {{ $settingKeys[$setting->meta_key] ?? $setting->meta_key }}
                                    </span>
                                </td>

                                <td>
                                    <code>{{ $setting->meta_key }}</code>
                                </td>

                                <td class="fw-semibold">
                                    {{ $setting->meta_value }}
                                </td>

                                <td class="text-end">
                                    <form
                                        method="POST"
                                        action="{{ route('settings.destroy', $setting) }}"
                                        class="d-inline"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-danger rounded-pill"
                                            data-confirm="Delete this setting?"
                                        >
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">
                                    No settings configured yet.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($settings->hasPages())
                    <div class="card-footer bg-white border-0 rounded-bottom-4 px-4 py-3">
                        {{ $settings->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
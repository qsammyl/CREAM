@extends('layouts.dashboard')

@section('title', 'Edit '.$user->name)
@section('heading', 'Edit account')
@section('sub', $user->email)

@section('actions')
    <a class="btn btn-ghost" href="{{ route('staff.users.index') }}">Back to users</a>
@endsection

@push('head')
<style>
.form-narrow{max-width:520px}
.form-narrow .field{margin-bottom:16px}
.form-narrow label{display:block;font-family:var(--mono);font-size:10.5px;letter-spacing:.1em;text-transform:uppercase;color:var(--ink-3);margin-bottom:6px}
.form-narrow input,.form-narrow select{width:100%;padding:10px 12px;border:1px solid var(--line-2);border-radius:var(--r-s);font:inherit}
.check-row{display:flex;align-items:center;gap:9px}
.check-row input{width:auto}
.err{color:var(--err-ink);font-size:12px;margin-top:4px}
</style>
@endpush

@section('content')
    @php($isSelf = $user->id === auth()->id())

    <form method="POST" action="{{ route('staff.users.update', $user) }}" class="form-narrow">
        @csrf @method('PUT')

        <div class="field">
            <label>Full name</label>
            <input name="name" value="{{ old('name', $user->name) }}" required>
            @error('name')<div class="err">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
            @error('email')<div class="err">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label>Role</label>
            <select name="role" {{ $isSelf ? 'disabled' : '' }}>
                @foreach ($roles as $value => $label)
                    <option value="{{ $value }}" {{ old('role', $user->role->value) === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @if ($isSelf)<input type="hidden" name="role" value="{{ $user->role->value }}"><p class="muted" style="font-size:12px;margin-top:5px">You cannot change your own role.</p>@endif
            @error('role')<div class="err">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label>Status</label>
            <label class="check-row">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} {{ $isSelf ? 'disabled' : '' }}>
                Account is active (can sign in)
            </label>
            @if ($isSelf)<input type="hidden" name="is_active" value="1"><p class="muted" style="font-size:12px;margin-top:5px">You cannot deactivate your own account.</p>@endif
        </div>

        <div style="display:flex;gap:10px;align-items:center;margin-top:8px">
            <button type="submit" class="btn btn-primary">Save changes</button>
            @unless ($isSelf)
                <button type="submit" form="delete-user" class="btn btn-ghost" style="color:var(--err-ink);border-color:var(--err-bd)" data-confirm="Delete {{ $user->name }}? This cannot be undone.">Delete account</button>
            @endunless
        </div>
    </form>

    @unless ($isSelf)
        <form id="delete-user" method="POST" action="{{ route('staff.users.destroy', $user) }}">@csrf @method('DELETE')</form>
    @endunless
@endsection

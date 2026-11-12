@extends('layouts.dashboard')

@section('title', 'Settings')
@section('heading', 'Settings')
@section('sub', 'Contact details, social links, the featured project popup and data retention. Changes apply across the public site immediately.')

@push('head')
<style>
.settings-group{border:1px solid var(--line);border-radius:var(--r-m);background:var(--paper);margin-bottom:18px}
.settings-group > h2{font-family:var(--serif);font-weight:400;font-size:20px;padding:16px 20px;border-bottom:1px solid var(--line);margin:0}
.settings-group .body{padding:18px 20px;display:grid;grid-template-columns:1fr 1fr;gap:16px}
.sfield{display:flex;flex-direction:column}
.sfield.full{grid-column:1 / -1}
.sfield label{font-family:var(--mono);font-size:10.5px;letter-spacing:.1em;text-transform:uppercase;color:var(--ink-3);margin:0 0 6px}
.sfield input,.sfield textarea{padding:10px 12px;border:1px solid var(--line-2);border-radius:var(--r-s);background:#fff;font:inherit}
.sfield textarea{resize:vertical;min-height:64px;line-height:1.5}
.sfield .err{color:var(--err-ink);font-size:12px;margin-top:4px}
.sfield.toggle .switch{display:flex;align-items:center;gap:10px;cursor:pointer;margin:0}
.sfield.toggle .switch input{width:18px;height:18px;accent-color:var(--emerald);flex:none}
.sfield.toggle .switch span{font-family:var(--mono);font-size:10.5px;letter-spacing:.1em;text-transform:uppercase;color:var(--ink-2)}
@media(max-width:720px){.settings-group .body{grid-template-columns:1fr}}
</style>
@endpush

@section('content')
    @if ($errors->any())
        <div class="alert" style="background:#FEF2F2;border:1px solid var(--err-bd);color:var(--err-ink)">Please check the highlighted fields.</div>
    @endif

    <form method="POST" action="{{ route('staff.settings.update') }}">
        @csrf @method('PUT')
        @foreach ($schema as $group => $fields)
            <div class="settings-group">
                <h2>{{ $group }}</h2>
                <div class="body">
                    @foreach ($fields as $field)
                        @php($full = in_array($field['type'], ['textarea', 'toggle']) || str_contains($field['label'], 'address'))
                        @if ($field['type'] === 'toggle')
                            <div class="sfield toggle full">
                                <label class="switch" for="s-{{ $field['key'] }}">
                                    <input type="checkbox" id="s-{{ $field['key'] }}" name="{{ $field['key'] }}" value="1"
                                        @checked(filter_var(old($field['key'], $current[$field['key']]), FILTER_VALIDATE_BOOLEAN))>
                                    <span>{{ $field['label'] }}</span>
                                </label>
                                @error($field['key'])<div class="err">{{ $message }}</div>@enderror
                            </div>
                        @elseif ($field['type'] === 'textarea')
                            <div class="sfield full">
                                <label for="s-{{ $field['key'] }}">{{ $field['label'] }}</label>
                                <textarea id="s-{{ $field['key'] }}" name="{{ $field['key'] }}" rows="3">{{ old($field['key'], $current[$field['key']]) }}</textarea>
                                @error($field['key'])<div class="err">{{ $message }}</div>@enderror
                            </div>
                        @else
                            <div class="sfield {{ $full ? 'full' : '' }}">
                                <label for="s-{{ $field['key'] }}">{{ $field['label'] }}</label>
                                <input id="s-{{ $field['key'] }}" name="{{ $field['key'] }}"
                                    type="{{ $field['type'] === 'number' ? 'number' : ($field['type'] === 'email' ? 'email' : ($field['type'] === 'url' ? 'url' : 'text')) }}"
                                    @if ($field['type'] === 'number') min="0" @endif
                                    value="{{ old($field['key'], $current[$field['key']]) }}">
                                @error($field['key'])<div class="err">{{ $message }}</div>@enderror
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endforeach

        <button type="submit" class="btn btn-primary">Save settings</button>
    </form>
@endsection

@extends('layouts.dashboard')

@php
    $editing = $article->exists;
    $val = fn ($f, $d = null) => old($f, data_get($article, $f) ?? $d);
@endphp

@section('title', $editing ? 'Edit article' : 'New article')
@section('heading', $editing ? 'Edit article' : 'New article')
@section('sub', $editing ? $article->title : 'Write a blog post')

@section('actions')
    @if ($editing && $article->status->value === 'published')
        <a href="{{ $article->url() }}" target="_blank" class="btn btn-ghost">View live</a>
    @endif
@endsection

@push('head')
<link rel="stylesheet" href="/assets/vendor/trix.css">
<style>
.fgrid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.fgrid .full{grid-column:1 / -1}
trix-editor{min-height:320px;background:#fff;border:1px solid var(--line-2);border-radius:var(--r-s);font-family:var(--sans);font-size:16px}
trix-toolbar .trix-button-group{border-color:var(--line)}
.savebar{position:sticky;bottom:0;background:var(--paper);border-top:1px solid var(--line);padding:16px 0;margin-top:8px;display:flex;gap:10px;flex-wrap:wrap}
@media(max-width:720px){.fgrid{grid-template-columns:1fr}}
</style>
@endpush

@push('scripts')
<script src="/assets/vendor/trix.umd.min.js"></script>
<script src="/assets/vendor/trix-config.js"></script>
@endpush

@section('content')
    @if ($errors->any())
        <div class="alert alert-err">@foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
    @endif

    <form method="POST" action="{{ $editing ? route('staff.articles.update', $article) : route('staff.articles.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($editing)@method('PUT')@endif

        <div class="section-card">
            <h2>Article</h2>
            <div class="fgrid">
                <div class="field full"><label>Title *</label><input name="title" class="input" value="{{ $val('title') }}" required></div>
                <div class="field"><label>Slug</label><input name="slug" class="input" value="{{ $val('slug') }}" placeholder="auto from title"></div>
                <div class="field"><label>Category</label><input name="category" class="input" value="{{ $val('category') }}" placeholder="e.g. Market notes"></div>
                <div class="field full"><label>Excerpt</label><textarea name="excerpt" class="input" style="min-height:70px">{{ $val('excerpt') }}</textarea></div>
                <div class="field full">
                    <label>Cover image</label>
                    @if ($editing && $article->coverUrl())<div style="margin-bottom:8px"><img src="{{ $article->coverUrl() }}" alt="" style="max-height:120px;border-radius:8px"></div>@endif
                    <input type="file" name="cover" accept="image/jpeg,image/png,image/webp">
                    <div class="hint">Re-encoded to WebP on upload. Leave empty to keep the current image.</div>
                </div>
            </div>
        </div>

        <div class="section-card">
            <h2>Body</h2>
            <input id="articleBody" type="hidden" name="body" value="{{ $val('body') }}">
            <trix-editor input="articleBody"></trix-editor>
            <div class="hint" style="margin-top:8px">Formatting is sanitised to a safe allow-list on save.</div>
        </div>

        <div class="section-card">
            <h2>Publishing</h2>
            <div class="fgrid">
                <div class="field"><label>Status</label><select name="status" class="input">@foreach ($statuses as $v => $l)<option value="{{ $v }}" @selected($val('status') instanceof \App\Enums\ArticleStatus ? $val('status')->value === $v : $val('status') === $v)>{{ $l }}</option>@endforeach</select></div>
                <div class="field"><label class="check" style="margin-top:28px"><input type="hidden" name="allow_comments" value="0"><input type="checkbox" name="allow_comments" value="1" @checked($val('allow_comments', true))> Allow comments</label></div>
            </div>
        </div>

        <div class="savebar">
            <button type="submit" name="publish" value="0" class="btn btn-ghost">Save draft</button>
            <button type="submit" name="publish" value="1" class="btn btn-primary">Save &amp; publish</button>
        </div>
    </form>
@endsection

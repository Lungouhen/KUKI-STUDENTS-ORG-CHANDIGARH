@extends('layouts.admin')

@section('title', 'Document Templates | KSO CMS')

@section('content')
<div class="admin-member-document-templates-page container-fluid py-3">
    <h1 class="h3 fw-bold">Document Templates</h1>
    <p class="text-muted">Each save creates a new immutable version. Existing issued certificates keep their original wording and layout.</p>
    @if(session('success')) <div class="alert alert-success" role="status" aria-live="polite">{{ session('success') }}</div> @endif
    @if($errors->any())
        <div class="alert alert-danger" role="alert" aria-labelledby="templateErrorsHeading">
            <h2 id="templateErrorsHeading" class="h6 fw-bold">Review the template details</h2>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-xl-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h2 class="h5">Create a template version</h2>
                    <form action="{{ route('admin.memberDocumentTemplates.preview') }}" method="POST" class="document-template-action-form">
                        @csrf
                        <label class="form-label" for="document-type">Document type</label>
                        <select id="document-type" name="document_type" class="form-select mb-3" required>
                            @foreach($types as $type => $definition)
                                <option value="{{ $type }}" @selected(old('document_type') === $type)>{{ $definition['label'] }}</option>
                            @endforeach
                        </select>
                        <label class="form-label" for="template-title">Certificate title</label>
                        <input id="template-title" name="title" class="form-control mb-3" maxlength="150" value="{{ old('title') }}" required>
                        <label class="form-label" for="template-statement">Statement</label>
                        <textarea id="template-statement" name="statement" class="form-control mb-3" rows="5" maxlength="2000" aria-describedby="template-placeholder-help" required>{{ old('statement') }}</textarea>
                        <label class="form-label" for="template-style">Visual style</label>
                        <select id="template-style" name="style" class="form-select mb-3">
                            @foreach($styles as $style) <option value="{{ $style }}" @selected(old('style', 'classic') === $style)>{{ ucfirst($style) }}</option> @endforeach
                        </select>
                        <p id="template-placeholder-help" class="small text-muted">Supported placeholders: @foreach($placeholders as $placeholder)<code>{{ '{' . '{' . $placeholder . '}' . '}' }}</code>@if(!$loop->last), @endif @endforeach. HTML is not accepted; values are always escaped in the output.</p>
                        <button class="btn btn-primary" type="submit">Preview with sample data</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xl-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white"><h2 class="h5 mb-0">Version history</h2></div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <caption class="visually-hidden">Immutable document template versions</caption>
                        <thead><tr><th scope="col">Type</th><th scope="col">Version</th><th scope="col">Title</th><th scope="col">Style</th><th scope="col">State</th><th scope="col">Created</th></tr></thead>
                        <tbody>
                        @forelse($templates as $template)
                            <tr>
                                <td>{{ $types[$template->document_type]['label'] ?? $template->document_type }}</td>
                                <td>v{{ $template->version }}</td>
                                <td>{{ $template->title }}</td>
                                <td>{{ ucfirst($template->style) }}</td>
                                <td><span class="badge {{ $template->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $template->is_active ? 'Active' : 'Archived' }}</span></td>
                                <td>{{ $template->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-muted text-center py-4">No templates available.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

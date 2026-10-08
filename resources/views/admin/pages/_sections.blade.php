@php
    $sectionRows = old('sections', isset($page) ? ($page->sections ?? []) : []);
    if (!is_array($sectionRows)) {
        $sectionRows = [];
    }
    if (empty($sectionRows)) {
        $sectionRows = [['type' => 'heading', 'heading' => '', 'text' => '', 'image' => '', 'link_label' => '', 'link_url' => '']];
    }
    $sectionTypes = [
        'heading' => 'Heading',
        'text' => 'Text',
        'image' => 'Image',
        'callout' => 'Callout',
        'button' => 'Button',
    ];
@endphp

<div class="col-12">
    <fieldset>
        <legend class="form-label fw-bold">Structured page sections</legend>
        <p class="small text-muted">Add reusable, safely rendered blocks. Existing HTML content remains supported.</p>
        <input type="hidden" name="sections_present" value="1">
        <div id="page-sections">
            @foreach($sectionRows as $index => $section)
                @php($section = is_array($section) ? $section : [])
                <div class="card border mb-3 p-3 page-section">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label for="section-type-{{ $index }}" class="form-label fw-bold mb-0">Section type</label>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-section">Remove</button>
                    </div>
                    <select id="section-type-{{ $index }}" name="sections[{{ $index }}][type]" class="form-select mb-3 section-type">
                        @foreach($sectionTypes as $value => $label)
                            <option value="{{ $value }}" @selected(($section['type'] ?? 'heading') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label small">Heading</label>
                            <input type="text" name="sections[{{ $index }}][heading]" class="form-control" value="{{ $section['heading'] ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Button label</label>
                            <input type="text" name="sections[{{ $index }}][link_label]" class="form-control" value="{{ $section['link_label'] ?? '' }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label small">Text</label>
                            <textarea name="sections[{{ $index }}][text]" class="form-control" rows="3">{{ $section['text'] ?? '' }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Image from media library</label>
                            <select name="sections[{{ $index }}][image]" class="form-select">
                                <option value="">No image</option>
                                @foreach($assets as $asset)
                                    <option value="{{ $asset->path }}" @selected(($section['image'] ?? '') === $asset->path)>{{ $asset->original_name }} — {{ $asset->alt_text }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Button URL</label>
                            <input type="text" name="sections[{{ $index }}][link_url]" class="form-control" value="{{ $section['link_url'] ?? '' }}" placeholder="https://… or /internal-path">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <button type="button" class="btn btn-outline-primary btn-sm" id="add-page-section">Add section</button>
    </fieldset>
</div>

<template id="page-section-template">
    <div class="card border mb-3 p-3 page-section">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <label for="section-type-__INDEX__" class="form-label fw-bold mb-0">Section type</label>
            <button type="button" class="btn btn-sm btn-outline-danger remove-section">Remove</button>
        </div>
        <select id="section-type-__INDEX__" name="sections[__INDEX__][type]" class="form-select mb-3 section-type">
            @foreach($sectionTypes as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </select>
        <div class="row g-2">
            <div class="col-md-6">
                <label class="form-label small">Heading</label>
                <input type="text" name="sections[__INDEX__][heading]" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label small">Button label</label>
                <input type="text" name="sections[__INDEX__][link_label]" class="form-control">
            </div>
            <div class="col-12">
                <label class="form-label small">Text</label>
                <textarea name="sections[__INDEX__][text]" class="form-control" rows="3"></textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label small">Image from media library</label>
                <select name="sections[__INDEX__][image]" class="form-select">
                    <option value="">No image</option>
                    @foreach($assets as $asset)
                        <option value="{{ $asset->path }}">{{ $asset->original_name }} — {{ $asset->alt_text }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label small">Button URL</label>
                <input type="text" name="sections[__INDEX__][link_url]" class="form-control" placeholder="https://… or /internal-path">
            </div>
        </div>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const sections = document.getElementById('page-sections');
    const template = document.getElementById('page-section-template');
    let nextIndex = sections.querySelectorAll('.page-section').length;

    document.getElementById('add-page-section').addEventListener('click', () => {
        const html = template.innerHTML.replaceAll('__INDEX__', nextIndex++);
        sections.insertAdjacentHTML('beforeend', html);
    });

    sections.addEventListener('click', event => {
        if (event.target.closest('.remove-section')) {
            event.target.closest('.page-section').remove();
        }
    });
});
</script>

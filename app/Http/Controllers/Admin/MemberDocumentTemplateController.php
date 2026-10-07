<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Member;
use App\Models\MemberDocumentTemplate;
use App\Services\MemberDocumentTemplateRenderer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MemberDocumentTemplateController extends Controller
{
    public function index()
    {
        return view('admin.member-document-templates.index', [
            'types' => config('member_documents.types'),
            'templates' => MemberDocumentTemplate::orderBy('document_type')->orderByDesc('version')->get(),
            'styles' => MemberDocumentTemplateRenderer::STYLES,
            'placeholders' => [
                'member_name', 'member_id', 'institution', 'course', 'certificate_details',
                'issued_date', 'issuer', 'certificate_number', 'verification_url',
            ],
        ]);
    }

    public function preview(Request $request, MemberDocumentTemplateRenderer $renderer)
    {
        $validated = $request->validate([
            'document_type' => ['required', 'string', 'in:'.implode(',', array_keys(config('member_documents.types')))],
            'title' => 'required|string|max:150',
            'statement' => 'required|string|max:2000',
            'style' => ['required', 'string', 'in:'.implode(',', MemberDocumentTemplateRenderer::STYLES)],
        ]);
        $renderer->validate($validated['title'], $validated['statement']);
        $request->session()->put('member_document_template_preview', $this->previewHash($validated));

        $template = new MemberDocumentTemplate($validated);
        $sample = new Member([
            'id' => 'KSO-CHD-SAMPLE-0001',
            'full_name' => 'Sample Member',
            'institution' => 'Panjab University',
            'course' => 'BSc',
            'year_of_study' => '2nd Year',
        ]);
        $preview = $renderer->preview(
            $template,
            $sample,
            'Participation in the annual student welfare programme',
            secure_url(route('documents.verify', 'PREVIEW-CERTIFICATE-NUMBER', false)),
            now()
        );

        return view('admin.member-document-templates.preview', [
            'templateData' => $validated,
            'preview' => $preview,
        ]);
    }

    public function store(Request $request, MemberDocumentTemplateRenderer $renderer)
    {
        $validated = $request->validate([
            'document_type' => ['required', 'string', 'in:'.implode(',', array_keys(config('member_documents.types')))],
            'title' => 'required|string|max:150',
            'statement' => 'required|string|max:2000',
            'style' => ['required', 'string', 'in:'.implode(',', MemberDocumentTemplateRenderer::STYLES)],
        ]);
        $renderer->validate($validated['title'], $validated['statement']);
        $previewHash = $request->session()->pull('member_document_template_preview');
        if (! is_string($previewHash) || ! hash_equals($previewHash, $this->previewHash($validated))) {
            return back()->withInput()->withErrors(['statement' => 'Preview this exact template before saving its version.']);
        }

        $template = DB::transaction(function () use ($validated) {
            MemberDocumentTemplate::where('document_type', $validated['document_type'])
                ->lockForUpdate()
                ->get();

            MemberDocumentTemplate::where('document_type', $validated['document_type'])
                ->where('is_active', true)
                ->update(['is_active' => false]);

            $version = ((int) MemberDocumentTemplate::where('document_type', $validated['document_type'])->max('version')) + 1;

            return MemberDocumentTemplate::create([
                ...$validated,
                'version' => $version,
                'is_active' => true,
                'created_by' => auth()->id(),
            ]);
        });

        AuditLog::log('MEMBER_DOCUMENT_TEMPLATE_VERSION_CREATED', [
            'template_id' => $template->id,
            'document_type' => $template->document_type,
            'version' => $template->version,
        ]);

        return redirect()->route('admin.memberDocumentTemplates.index')
            ->with('success', "{$template->document_type} template version {$template->version} is now active.");
    }

    private function previewHash(array $values): string
    {
        return hash_hmac('sha256', json_encode($values, JSON_THROW_ON_ERROR), (string) config('app.key'));
    }
}

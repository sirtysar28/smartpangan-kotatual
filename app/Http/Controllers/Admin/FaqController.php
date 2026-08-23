<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $query = Faq::query()->when($request->category, fn($q, $c) => $q->where('category', $c))
            ->when($request->search, fn($q, $s) => $q->where('question', 'like', "%{$s}%")->orWhere('answer', 'like', "%{$s}%"));

        $faqs = (clone $query)->ordered()->paginate(15)->withQueryString();
        $stats = [
            'total' => Faq::count(),
            'nib'   => Faq::nib()->count(),
            'psat'  => Faq::psat()->count(),
            'active' => Faq::active()->count(),
        ];

        return view('admin.faqs.index', compact('faqs', 'stats'));
    }

    public function create()
    {
        return view('admin.faqs.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category'   => 'required|in:nib,psat_pduk',
            'question'   => 'required|string|max:500',
            'answer'     => 'required|string|max:5000',
            'items'      => 'nullable|string',
            'is_active'  => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data['items'] = $this->parseItems($request->items);
        $data['is_active'] = $request->has('is_active');
        $data['sort_order'] = $request->sort_order ?? (Faq::max('sort_order') + 1);

        $faq = Faq::create($data);
        ActivityLogService::log('create', 'faq', "Menambah FAQ [{$faq->category_label}]: {$faq->question}", ['faq_id' => $faq->id]);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ berhasil ditambahkan.');
    }

    public function edit(Faq $faq)
    {
        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        $data = $request->validate([
            'category'   => 'required|in:nib,psat_pduk',
            'question'   => 'required|string|max:500',
            'answer'     => 'required|string|max:5000',
            'items'      => 'nullable|string',
            'is_active'  => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data['items'] = $this->parseItems($request->items);
        $data['is_active'] = $request->has('is_active');

        $faq->update($data);
        ActivityLogService::log('update', 'faq', "Mengupdate FAQ [{$faq->category_label}]: {$faq->question}", ['faq_id' => $faq->id]);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ berhasil diperbarui.');
    }

    public function destroy(Faq $faq)
    {
        $label = "[{$faq->category_label}] {$faq->question}";
        $faq->delete();
        ActivityLogService::log('delete', 'faq', "Menghapus FAQ: {$label}");

        return back()->with('success', 'FAQ berhasil dihapus.');
    }

    /** Konversi textarea (satu bullet per baris) menjadi array. */
    private function parseItems(?string $text): ?array
    {
        if (! $text) return null;
        $items = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $text)), fn($l) => $l !== '');
        return count($items) ? array_values($items) : null;
    }
}

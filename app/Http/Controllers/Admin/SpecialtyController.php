<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HasBulkMessages;
use App\Http\Controllers\Admin\Concerns\ParsesLineLists;
use App\Http\Controllers\Controller;
use App\Models\Specialty;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SpecialtyController extends Controller
{
    use HasBulkMessages, ParsesLineLists;

    /**
     * List every specialty alongside its specialist count.
     */
    public function index(Request $request): View
    {
        $search = $request->string('q')->toString();

        return view('admin.specialties.index', [
            'specialties' => Specialty::query()
                ->withCount('specialists')
                ->when($search !== '', function ($query) use ($search) {
                    $like = '%'.$search.'%';

                    $query->where(function ($query) use ($like) {
                        $query->where('name', 'like', $like)
                            ->orWhere('short_description', 'like', $like);
                    });
                })
                ->ordered()
                ->paginate(12)
                ->withQueryString(),

            'search' => $search,
        ]);
    }

    /**
     * Show the "add specialty" form.
     */
    public function create(): View
    {
        return view('admin.specialties.create', [
            'specialty' => new Specialty(['is_active' => true, 'icon' => 'brain']),
        ]);
    }

    /**
     * Persist a new specialty.
     */
    public function store(Request $request): RedirectResponse
    {
        $specialty = Specialty::create($this->validated($request));

        return redirect()
            ->route('admin.specialties.index')
            ->with('success', "{$specialty->name} was added to the website.");
    }

    /**
     * Show the "edit specialty" form.
     */
    public function edit(Specialty $specialty): View
    {
        return view('admin.specialties.edit', [
            'specialty' => $specialty,
        ]);
    }

    /**
     * Update an existing specialty.
     */
    public function update(Request $request, Specialty $specialty): RedirectResponse
    {
        $specialty->update($this->validated($request, $specialty));

        return redirect()
            ->route('admin.specialties.index')
            ->with('success', "{$specialty->name} was updated.");
    }

    /**
     * Remove a specialty from the website.
     *
     * Specialists and appointment requests that referenced it are preserved;
     * their foreign keys are set to null.
     */
    public function destroy(Specialty $specialty): RedirectResponse
    {
        $name = $specialty->name;

        $specialty->delete();

        return redirect()
            ->route('admin.specialties.index')
            ->with('success', "{$name} was removed. Related specialists and requests were kept.");
    }

    /**
     * Show, hide or remove several specialties at once.
     *
     * Specialists assigned to a removed specialty are kept and become
     * unassigned, matching the single-record behaviour.
     */
    public function bulk(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer'],
            'action' => ['required', Rule::in(['activate', 'deactivate', 'delete'])],
        ], [
            'ids.required' => 'Select at least one specialty first.',
        ]);

        $specialties = Specialty::query()->whereKey($validated['ids']);

        if ($validated['action'] === 'delete') {
            $deleted = $specialties->delete();

            return back()->with('success', $this->bulkMessage($deleted, 'specialty', 'removed from the website'));
        }

        $visible = $validated['action'] === 'activate';

        $updated = $specialties->update(['is_active' => $visible]);

        $verb = $visible ? 'shown on the website' : 'hidden from the website';

        return back()->with('success', $this->bulkMessage($updated, 'specialty', $verb));
    }

    /**
     * Validate and normalise the submitted specialty payload.
     *
     * @return array<string, mixed>
     */
    protected function validated(Request $request, ?Specialty $specialty = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'icon' => ['required', Rule::in(array_keys(Specialty::ICONS))],
            'short_description' => ['required', 'string', 'max:500'],
            'full_description' => ['nullable', 'string', 'max:4000'],
            'conditions' => ['nullable', 'string', 'max:4000'],
            'diagnostic_services' => ['nullable', 'string', 'max:4000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);

        return [
            ...Arr::except($validated, ['conditions', 'diagnostic_services', 'sort_order']),
            'conditions' => $this->parseLineList($validated['conditions'] ?? null),
            'diagnostic_services' => $this->parseLineList($validated['diagnostic_services'] ?? null),
            'sort_order' => $validated['sort_order'] ?? $specialty?->sort_order ?? Specialty::max('sort_order') + 1,
            'is_active' => $request->boolean('is_active'),
        ];
    }
}

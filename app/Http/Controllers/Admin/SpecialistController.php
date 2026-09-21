<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HasBulkMessages;
use App\Http\Controllers\Admin\Concerns\ParsesLineLists;
use App\Http\Controllers\Controller;
use App\Models\Specialist;
use App\Models\Specialty;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SpecialistController extends Controller
{
    use HasBulkMessages, ParsesLineLists;

    /**
     * List every specialist, active or not.
     */
    public function index(Request $request): View
    {
        $search = $request->string('q')->toString();

        return view('admin.specialists.index', [
            'specialists' => Specialist::query()
                ->with('specialty')
                ->when($search !== '', function ($query) use ($search) {
                    $like = '%'.$search.'%';

                    $query->where(function ($query) use ($like) {
                        $query->where('name', 'like', $like)
                            ->orWhere('role', 'like', $like)
                            ->orWhere('qualifications', 'like', $like);
                    });
                })
                ->ordered()
                ->paginate(12)
                ->withQueryString(),

            'search' => $search,
        ]);
    }

    /**
     * Show the "add specialist" form.
     */
    public function create(): View
    {
        return view('admin.specialists.create', [
            'specialist' => new Specialist(['is_active' => true]),
            'specialties' => Specialty::ordered()->get(),
        ]);
    }

    /**
     * Persist a new specialist.
     */
    public function store(Request $request): RedirectResponse
    {
        $specialist = Specialist::create($this->validated($request));

        return redirect()
            ->route('admin.specialists.index')
            ->with('success', "{$specialist->name} was added to the website.");
    }

    /**
     * Show the "edit specialist" form.
     */
    public function edit(Specialist $specialist): View
    {
        return view('admin.specialists.edit', [
            'specialist' => $specialist,
            'specialties' => Specialty::ordered()->get(),
        ]);
    }

    /**
     * Update an existing specialist.
     */
    public function update(Request $request, Specialist $specialist): RedirectResponse
    {
        $specialist->update($this->validated($request, $specialist));

        return redirect()
            ->route('admin.specialists.index')
            ->with('success', "{$specialist->name} was updated.");
    }

    /**
     * Remove a specialist from the website.
     *
     * Appointment requests are preserved: the foreign key is nulled rather
     * than cascading, so booking history survives.
     */
    public function destroy(Specialist $specialist): RedirectResponse
    {
        $name = $specialist->name;

        $specialist->delete();

        return redirect()
            ->route('admin.specialists.index')
            ->with('success', "{$name} was removed. Their appointment requests were kept.");
    }

    /**
     * Show, hide or remove several specialists at once.
     */
    public function bulk(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer'],
            'action' => ['required', Rule::in(['activate', 'deactivate', 'delete'])],
        ], [
            'ids.required' => 'Select at least one specialist first.',
        ]);

        $specialists = Specialist::query()->whereKey($validated['ids']);

        if ($validated['action'] === 'delete') {
            $deleted = $specialists->delete();

            return back()->with('success', $this->bulkMessage($deleted, 'specialist', 'removed from the website'));
        }

        $visible = $validated['action'] === 'activate';

        $updated = $specialists->update(['is_active' => $visible]);

        $verb = $visible ? 'shown on the website' : 'hidden from the website';

        return back()->with('success', $this->bulkMessage($updated, 'specialist', $verb));
    }

    /**
     * Validate and normalise the submitted specialist payload.
     *
     * @return array<string, mixed>
     */
    protected function validated(Request $request, ?Specialist $specialist = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'qualifications' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
            'specialty_id' => ['nullable', 'integer', Rule::exists('specialties', 'id')],

            // Headshots are either a hosted URL or a file under public/, so a
            // plain "url" rule would reject the images that ship with the site.
            'image' => ['nullable', 'string', 'max:2048', 'regex:/^(https?:\/\/|\/)[^\s]*$/i'],

            'bio' => ['nullable', 'string', 'max:4000'],
            'consulting_days' => ['nullable', 'string', 'max:255'],
            'special_interests' => ['nullable', 'string', 'max:2000'],

            'profile_sections' => ['nullable', 'array', 'max:20'],
            'profile_sections.*.heading' => ['nullable', 'string', 'max:255'],
            'profile_sections.*.type' => ['nullable', Rule::in(['list', 'paragraphs'])],
            'profile_sections.*.items' => ['nullable', 'string', 'max:8000'],

            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ], [
            'image.regex' => 'Enter a full image URL (https://…) or a path starting with a forward slash, e.g. /images/specialists/dr-sadasivan.png.',
        ]);

        return [
            ...Arr::except($validated, ['special_interests', 'profile_sections', 'sort_order']),
            'special_interests' => $this->parseLineList($validated['special_interests'] ?? null),
            'profile_sections' => $this->parseProfileSections($validated['profile_sections'] ?? []),
            'sort_order' => $validated['sort_order'] ?? $specialist?->sort_order ?? Specialist::max('sort_order') + 1,
            'is_active' => $request->boolean('is_active'),
        ];
    }

    /**
     * Normalise the profile page blocks submitted by the repeatable editor.
     *
     * Blocks with no content are dropped so an accidentally added empty row
     * never renders a stray heading on the public page.
     *
     * @param  array<int, array<string, mixed>>  $sections
     * @return array<int, array{heading: string, type: string, items: array<int, string>}>
     */
    protected function parseProfileSections(array $sections): array
    {
        return collect($sections)
            ->map(function (array $section) {
                $type = ($section['type'] ?? 'list') === 'paragraphs' ? 'paragraphs' : 'list';

                return [
                    'heading' => trim((string) ($section['heading'] ?? '')),
                    'type' => $type,
                    'items' => $this->parseSectionItems($section['items'] ?? null, $type),
                ];
            })
            ->filter(fn (array $section) => $section['items'] !== [])
            ->values()
            ->all();
    }

    /**
     * Split a section's textarea into paragraphs or bullet items.
     *
     * Paragraphs are separated by a blank line, so a long paragraph that wraps
     * inside the textarea is still stored as one paragraph. Lists use a single
     * line per item.
     *
     * @return array<int, string>
     */
    protected function parseSectionItems(?string $value, string $type): array
    {
        $value = trim((string) $value);

        if ($value === '') {
            return [];
        }

        if ($type !== 'paragraphs') {
            return $this->parseLineList($value);
        }

        return collect(preg_split('/\n\s*\n/', $value))
            ->map(fn (string $paragraph) => trim((string) preg_replace('/\s*\n\s*/', ' ', $paragraph)))
            ->filter()
            ->values()
            ->all();
    }
}

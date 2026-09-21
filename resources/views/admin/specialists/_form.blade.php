@php
    $input = 'w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-800 transition focus:border-transparent focus:ring-2 focus:ring-brand-teal focus:outline-none';
    $labelClass = 'mb-1 block text-xs font-semibold text-slate-700';
    $hintClass = 'mt-1 text-[11px] text-slate-400';
    $errorClass = 'mt-1 text-[11px] text-red-600';

    // Profile blocks are edited as strings and converted back to arrays by the
    // controller. Paragraphs round-trip as blank-line separated blocks; lists
    // as one item per line.
    $sectionData = collect(old('profile_sections', $specialist->profileBlocks()))
        ->map(fn (array $block) => [
            'heading' => $block['heading'] ?? '',
            'type' => ($block['type'] ?? 'list') === 'paragraphs' ? 'paragraphs' : 'list',
            'items' => is_array($block['items'] ?? null)
                ? implode($block['type'] === 'paragraphs' ? "\n\n" : "\n", $block['items'])
                : (string) ($block['items'] ?? ''),
        ])
        ->values()
        ->all();
@endphp

<form method="POST" action="{{ $action }}" class="space-y-6">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    {{-- Name --}}
    <div>
        <label for="name" class="{{ $labelClass }}">Full name <span class="text-red-500">*</span></label>
        <input id="name" type="text" name="name" required value="{{ old('name', $specialist->name) }}"
               placeholder="e.g. Dr Sarah Tanusha Thomas"
               class="{{ $input }} @error('name') border-red-400 @enderror">
        @error('name')
            <p class="{{ $errorClass }}">{{ $message }}</p>
        @enderror
    </div>

    {{-- Qualifications + role --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div>
            <label for="qualifications" class="{{ $labelClass }}">Qualifications <span class="text-red-500">*</span></label>
            <input id="qualifications" type="text" name="qualifications" required
                   value="{{ old('qualifications', $specialist->qualifications) }}"
                   placeholder="e.g. MBBS, MMed (Clin Epi), FRACP"
                   class="{{ $input }} @error('qualifications') border-red-400 @enderror">
            @error('qualifications')
                <p class="{{ $errorClass }}">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="role" class="{{ $labelClass }}">Role / title <span class="text-red-500">*</span></label>
            <input id="role" type="text" name="role" required value="{{ old('role', $specialist->role) }}"
                   placeholder="e.g. Neurologist – Stroke &amp; Cognitive Neurology"
                   class="{{ $input }} @error('role') border-red-400 @enderror">
            @error('role')
                <p class="{{ $errorClass }}">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Specialty + consulting days --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div>
            <label for="specialty_id" class="{{ $labelClass }}">Specialty department</label>
            <select id="specialty_id" name="specialty_id" class="{{ $input }} bg-white @error('specialty_id') border-red-400 @enderror">
                <option value="">Unassigned</option>
                @foreach ($specialties as $specialty)
                    <option value="{{ $specialty->id }}" @selected((string) old('specialty_id', $specialist->specialty_id) === (string) $specialty->id)>
                        {{ $specialty->name }}
                    </option>
                @endforeach
            </select>
            @error('specialty_id')
                <p class="{{ $errorClass }}">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="consulting_days" class="{{ $labelClass }}">Consulting days</label>
            <input id="consulting_days" type="text" name="consulting_days"
                   value="{{ old('consulting_days', $specialist->consulting_days) }}"
                   placeholder="e.g. Mondays, Wednesdays, Fridays"
                   class="{{ $input }} @error('consulting_days') border-red-400 @enderror">
            @error('consulting_days')
                <p class="{{ $errorClass }}">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Headshot --}}
    <div>
        <label for="image" class="{{ $labelClass }}">Headshot image</label>
        <input id="image" type="text" name="image" value="{{ old('image', $specialist->image) }}"
               placeholder="/images/specialists/dr-sadasivan.png"
               class="{{ $input }} font-mono text-xs @error('image') border-red-400 @enderror">
        <p class="{{ $hintClass }}">
            A hosted URL (<span class="font-mono">https://…</span>) or a path under
            <span class="font-mono">public/</span>, e.g. <span class="font-mono">/images/specialists/dr-sadasivan.png</span>.
            A square image works best; leave blank for a placeholder avatar.
        </p>
        @error('image')
            <p class="{{ $errorClass }}">{{ $message }}</p>
        @enderror
    </div>

    {{-- Biography --}}
    <div>
        <label for="bio" class="{{ $labelClass }}">Professional biography (introduction)</label>
        <textarea id="bio" name="bio" rows="4" placeholder="Shown as the introduction on the specialist's profile page."
                  class="{{ $input }} @error('bio') border-red-400 @enderror">{{ old('bio', $specialist->bio) }}</textarea>
        @error('bio')
            <p class="{{ $errorClass }}">{{ $message }}</p>
        @enderror
    </div>

    {{-- Interests --}}
    <div>
        <label for="special_interests" class="{{ $labelClass }}">Areas of clinical focus</label>
        <textarea id="special_interests" name="special_interests" rows="4"
                  placeholder="One area of focus per line, e.g.&#10;Stroke Prevention &amp; Post-Stroke Care&#10;Cognitive Assessment &amp; Memory Disorders"
                  class="{{ $input }} @error('special_interests') border-red-400 @enderror">{{ old('special_interests', implode("\n", $specialist->special_interests ?? [])) }}</textarea>
        <p class="{{ $hintClass }}">Enter one item per line — each becomes a card under "Special Interests" on the profile page.</p>
        @error('special_interests')
            <p class="{{ $errorClass }}">{{ $message }}</p>
        @enderror
    </div>

    {{-- Profile page sections --}}
    <div x-data="profileSections(@js($sectionData))" class="space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <span class="{{ $labelClass }}">Profile page sections</span>
                <p class="{{ $hintClass }} !mt-0">
                    Extra headed blocks for this doctor's page, in the order shown. Leave the
                    heading blank to continue the previous section.
                </p>
            </div>

            <button type="button" @click="add()"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-brand-teal/40 bg-white px-3.5 py-2 text-xs font-semibold text-brand-teal transition-colors hover:bg-slate-50">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true">
                    <path d="M12 5v14M5 12h14" />
                </svg>
                <span>Add section</span>
            </button>
        </div>

        <p x-show="blocks.length === 0" x-cloak
           class="rounded-xl border border-dashed border-slate-300 bg-slate-50/60 px-4 py-6 text-center text-xs text-slate-500">
            No extra sections yet — the page will show the biography and areas of clinical focus only.
        </p>

        <div class="space-y-4">
            <template x-for="(block, index) in blocks" :key="block.uid">
                <div class="rounded-xl border border-slate-200 bg-slate-50/60 p-4">
                    <div class="mb-3 flex items-center justify-between gap-3">
                        <span class="text-[11px] font-bold tracking-wider text-slate-400 uppercase"
                              x-text="'Section ' + (index + 1)"></span>

                        <div class="flex items-center gap-1">
                            <button type="button" @click="move(index, -1)" :disabled="index === 0"
                                    class="rounded-md p-1.5 text-slate-500 transition-colors hover:bg-white hover:text-brand-teal disabled:cursor-not-allowed disabled:opacity-30"
                                    aria-label="Move section up">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M12 19V5M5 12l7-7 7 7" />
                                </svg>
                            </button>

                            <button type="button" @click="move(index, 1)" :disabled="index === blocks.length - 1"
                                    class="rounded-md p-1.5 text-slate-500 transition-colors hover:bg-white hover:text-brand-teal disabled:cursor-not-allowed disabled:opacity-30"
                                    aria-label="Move section down">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M12 5v14M5 12l7 7 7-7" />
                                </svg>
                            </button>

                            <button type="button" @click="remove(index)"
                                    class="rounded-md p-1.5 text-slate-500 transition-colors hover:bg-white hover:text-red-600"
                                    aria-label="Remove section">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                                    <path d="M6 6l12 12M18 6L6 18" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="{{ $labelClass }}" :for="'section-heading-' + block.uid">Heading</label>
                            <input type="text" :id="'section-heading-' + block.uid"
                                   :name="`profile_sections[${index}][heading]`"
                                   x-model="block.heading"
                                   placeholder="e.g. Training and Experience"
                                   class="{{ $input }} bg-white">
                        </div>

                        <div>
                            <label class="{{ $labelClass }}" :for="'section-type-' + block.uid">Layout</label>
                            <select :id="'section-type-' + block.uid"
                                    :name="`profile_sections[${index}][type]`"
                                    x-model="block.type"
                                    class="{{ $input }} bg-white">
                                <option value="list">Bulleted list</option>
                                <option value="paragraphs">Paragraphs</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="{{ $labelClass }}" :for="'section-items-' + block.uid">Content</label>
                        <textarea :id="'section-items-' + block.uid"
                                  :name="`profile_sections[${index}][items]`"
                                  x-model="block.items"
                                  rows="4"
                                  class="{{ $input }} bg-white"
                                  :placeholder="block.type === 'paragraphs'
                                      ? 'One paragraph per line. Leave a blank line between paragraphs.'
                                      : 'One bullet per line.'"></textarea>
                        <p class="{{ $hintClass }}"
                           x-text="block.type === 'paragraphs'
                               ? 'Each line becomes its own paragraph.'
                               : 'Each line becomes a bullet point.'"></p>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- Order + visibility --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div>
            <label for="sort_order" class="{{ $labelClass }}">Display order</label>
            <input id="sort_order" type="number" name="sort_order" min="0" max="9999"
                   value="{{ old('sort_order', $specialist->sort_order ?? '') }}"
                   placeholder="Auto"
                   class="{{ $input }} @error('sort_order') border-red-400 @enderror">
            <p class="{{ $hintClass }}">Lower numbers appear first. Leave blank to append to the end.</p>
            @error('sort_order')
                <p class="{{ $errorClass }}">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-start pt-6">
            <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-slate-200 p-3.5 transition-colors hover:bg-slate-50">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $specialist->is_active ?? true))
                       class="mt-0.5 rounded border-slate-300 text-brand-teal focus:ring-brand-teal">
                <span>
                    <span class="block text-xs font-semibold text-slate-800">Show on the website</span>
                    <span class="mt-0.5 block text-[11px] text-slate-500">Uncheck to hide this specialist from the public directory.</span>
                </span>
            </label>
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-6">
        <a href="{{ route('admin.specialists.index') }}"
           class="rounded-lg px-4 py-2.5 text-xs font-semibold text-slate-600 transition-colors hover:bg-slate-100">
            Cancel
        </a>

        <button type="submit"
                class="rounded-lg bg-brand-teal px-5 py-2.5 text-xs font-semibold text-white shadow-sm transition-all hover:bg-brand-dark active:scale-[0.98]">
            {{ $submitLabel }}
        </button>
    </div>
</form>

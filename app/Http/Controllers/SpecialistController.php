<?php

namespace App\Http\Controllers;

use App\Models\Specialist;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;

class SpecialistController extends Controller
{
    /**
     * Display a specialist's full public profile.
     *
     * The route binds on the slug, so a hidden specialist 404s rather than
     * being reachable by guessing the URL.
     */
    public function show(Specialist $specialist): View
    {
        abort_unless($specialist->is_active, 404);

        $specialist->load('specialty');

        return view('specialists.show', [
            'specialist' => $specialist,
            'colleagues' => $this->colleagues($specialist),
            'structuredData' => $this->structuredData($specialist),
        ]);
    }

    /**
     * Schema.org Physician data for the page's JSON-LD block.
     *
     * Built here rather than inline in the Blade view: a literal "@context"
     * key in a template is parsed by Blade as a directive and would be
     * replaced with compiled PHP.
     *
     * @return array<string, mixed>
     */
    protected function structuredData(Specialist $specialist): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Physician',
            'name' => $specialist->name,
            'medicalSpecialty' => $specialist->specialty?->name,
            'jobTitle' => $specialist->role,
            'description' => $specialist->bio,
            'image' => $specialist->image,
            'telephone' => config('clinic.phone'),
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => config('clinic.address.line1'),
                'addressLocality' => 'Coomera',
                'addressRegion' => 'QLD',
                'postalCode' => '4209',
                'addressCountry' => 'AU',
            ],
        ];
    }

    /**
     * Other visible specialists working in the same department.
     *
     * @return Collection<int, Specialist>
     */
    protected function colleagues(Specialist $specialist)
    {
        if ($specialist->specialty_id === null) {
            return Specialist::query()->whereKeyNot($specialist->getKey())->limit(0)->get();
        }

        return Specialist::query()
            ->with('specialty')
            ->active()
            ->where('specialty_id', $specialist->specialty_id)
            ->whereKeyNot($specialist->getKey())
            ->ordered()
            ->get();
    }
}

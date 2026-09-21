<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Specialist;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    /**
     * Seed a handful of realistic booking requests so the admin panel has
     * something to display on a fresh install.
     */
    public function run(): void
    {
        if (Appointment::query()->exists()) {
            return;
        }

        $specialists = Specialist::with('specialty')->active()->ordered()->get();

        $appointments = [
            [
                'full_name' => 'Marcus Whitfield',
                'phone' => '0412 884 219',
                'email' => 'marcus.whitfield@example.com',
                'specialty' => 'Neurology',
                'referral_status' => 'yes',
                'preferred_date' => now()->addDays(6)->toDateString(),
                'preferred_time' => 'Morning (9:00am - 12:00pm)',
                'notes' => 'Referred by Dr Nguyen at Coomera Family Practice. Ongoing migraine review with aura.',
                'status' => Appointment::STATUS_NEW,
            ],
            [
                'full_name' => 'Priya Raman',
                'phone' => '0455 210 774',
                'email' => 'priya.raman@example.com',
                'specialty' => 'Endocrinology',
                'referral_status' => 'yes',
                'preferred_date' => now()->addDays(9)->toDateString(),
                'preferred_time' => 'Afternoon (1:00pm - 5:00pm)',
                'notes' => 'Type 2 diabetes review, most recent HbA1c forwarded by GP.',
                'status' => Appointment::STATUS_CONTACTED,
            ],
            [
                'full_name' => 'Daniel Osei',
                'phone' => '0401 336 908',
                'email' => null,
                'specialty' => 'Nephrology',
                'referral_status' => 'pending',
                'preferred_date' => now()->addDays(13)->toDateString(),
                'preferred_time' => 'Next available',
                'notes' => 'GP arranging referral this week. History of resistant hypertension.',
                'status' => Appointment::STATUS_NEW,
            ],
            [
                'full_name' => 'Helen Prescott',
                'phone' => '0433 771 045',
                'email' => 'helen.prescott@example.com',
                'specialty' => 'Psychology',
                'referral_status' => 'yes',
                'preferred_date' => now()->addDays(3)->toDateString(),
                'preferred_time' => 'Morning (9:00am - 12:00pm)',
                'notes' => 'Mental Health Care Plan in place. Prefers afternoon sessions where possible.',
                'status' => Appointment::STATUS_BOOKED,
            ],
            [
                'full_name' => 'Tomasz Kowalski',
                'phone' => '0421 909 553',
                'email' => 't.kowalski@example.com',
                'specialty' => 'Psychiatry',
                'referral_status' => 'no',
                'preferred_date' => null,
                'preferred_time' => 'Next available',
                'notes' => 'Patient unsure how to obtain an Item 291 referral. Requested a call back.',
                'status' => Appointment::STATUS_CANCELLED,
                'admin_notes' => 'Patient rescheduled to next month — referral still outstanding.',
            ],
        ];

        foreach ($appointments as $appointment) {
            $specialist = $specialists->firstWhere(
                fn (Specialist $candidate) => $candidate->specialty?->name === $appointment['specialty'],
            );

            Appointment::create([
                ...$appointment,
                'specialist_id' => $specialist?->id,
            ]);
        }
    }
}

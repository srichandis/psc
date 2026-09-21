<?php

namespace Database\Seeders;

use App\Models\Specialty;
use Illuminate\Database\Seeder;

class SpecialtySeeder extends Seeder
{
    /**
     * Seed the clinical specialties in the order they appear on the homepage.
     *
     * The array order and the sort_order column are kept in step so the file
     * reads the same way as the rendered website.
     */
    public function run(): void
    {
        $specialties = [
            [
                'name' => 'Psychiatry',
                'icon' => 'psychiatry',
                'short_description' => 'Comprehensive assessment and treatment for adult mental health conditions.',
                'full_description' => 'Medical psychiatric diagnostic consultations, pharmacological reviews, biological treatment planning, and collaborative care alongside general practitioners and allied health teams.',
                'conditions' => [
                    'Major Depressive Disorder',
                    'Bipolar Affective Disorder',
                    'Adult Attention Deficit Hyperactivity Disorder (ADHD)',
                    'Obsessive Compulsive Disorder',
                    'Post-Traumatic Stress Disorder',
                ],
                'diagnostic_services' => [
                    'Item 291 Specialist Assessments',
                    'Comprehensive Diagnostic Formulation',
                    'Second Opinion Medication Reviews',
                ],
                'sort_order' => 1,
            ],
            [
                'name' => 'Neurology',
                'icon' => 'brain',
                'short_description' => 'Stroke, cognitive neurology, memory disorders, dementia and brain health.',
                'full_description' => 'Comprehensive neurological evaluation, non-invasive neurovascular assessments, memory clinics, and clinical follow-up for stroke, epilepsy, neuromuscular conditions, and chronic headaches.',
                'conditions' => [
                    'Stroke & TIA',
                    'Cognitive Impairment & Dementia',
                    'Migraine & Chronic Headaches',
                    "Parkinson's Disease & Tremor",
                    'Peripheral Neuropathy',
                ],
                'diagnostic_services' => [
                    'Cognitive Screening',
                    'Neurovascular Workup',
                    'EEG & Nerve Conduction Referrals',
                    'Brain MRI Review',
                ],
                'sort_order' => 2,
            ],
            [
                'name' => 'Geriatric Medicine',
                'icon' => 'geriatrics',
                'short_description' => 'Healthy ageing, memory concerns, falls, frailty and medication reviews.',
                'full_description' => 'Comprehensive assessment and management of complex health issues in older adults, focused on maintaining independence, preventing falls, and coordinating care across multiple long-term conditions.',
                'conditions' => [
                    'Memory Concerns & Cognitive Decline',
                    'Falls & Gait Instability',
                    'Frailty & Functional Decline',
                    'Polypharmacy & Medication Review',
                    'Osteoporosis & Fracture Prevention',
                ],
                'diagnostic_services' => [
                    'Comprehensive Geriatric Assessment',
                    'Falls & Balance Risk Review',
                    'Medication Rationalisation',
                    'Aged Care Assessment Referrals',
                ],
                'sort_order' => 3,
            ],
            [
                'name' => 'Nephrology',
                'icon' => 'nephrology',
                'short_description' => 'Chronic kidney disease, dialysis, hypertension and transplantation.',
                'full_description' => 'Advanced renal care focused on decelerating kidney disease progression, diagnosing glomerulonephritis, managing renovascular hypertension, and preparing for renal replacement therapy.',
                'conditions' => [
                    'Chronic Kidney Disease (Stages 1-5)',
                    'Treatment-Resistant Hypertension',
                    'Proteinuria & Haematuria',
                    'Polycystic Kidney Disease',
                    'Post-Transplant Medical Care',
                ],
                'diagnostic_services' => [
                    'eGFR & Albuminuria Trajectory Analysis',
                    '24-Hour Ambulatory Blood Pressure Review',
                    'Renal Ultrasound & Biopsy Correlation',
                ],
                'sort_order' => 4,
            ],
            [
                'name' => 'Endocrinology',
                'icon' => 'endocrinology',
                'short_description' => 'Diabetes, thyroid disorders, osteoporosis, adrenal and pituitary disorders.',
                'full_description' => 'Specialised hormonal and metabolic care encompassing continuous glucose monitoring, thyroid ultrasound correlation, bone density interpretation, and endocrine gland investigations.',
                'conditions' => [
                    'Type 1 & Type 2 Diabetes',
                    'Hypothyroidism & Hyperthyroidism',
                    'Osteoporosis & Metabolic Bone Disease',
                    'Adrenal Insufficiency & Incidentalomas',
                    'Pituitary Adenomas',
                ],
                'diagnostic_services' => [
                    'Continuous Glucose Monitoring (CGM)',
                    'Dynamic Endocrine Function Tests',
                    'DEXA Bone Mineral Review',
                ],
                'sort_order' => 5,
            ],
            [
                'name' => 'Psychology',
                'icon' => 'psychology',
                'short_description' => 'Assessment, therapy and mental health support for all ages.',
                'full_description' => 'Holistic clinical psychology assessments and individualised psychological therapies addressing life transitions, mood difficulties, occupational stress, and trauma.',
                'conditions' => [
                    'Depression & Persistent Low Mood',
                    'Generalised & Social Anxiety',
                    'Work-Related Stress & Burnout',
                    'Adjustment Disorders',
                    'Grief & Loss',
                ],
                'diagnostic_services' => [
                    'Psychological Symptom Inventories',
                    'CBT & ACT Therapeutic Sessions',
                    'Stress & Coping Profiling',
                ],
                'sort_order' => 6,
            ],
        ];

        foreach ($specialties as $specialty) {
            Specialty::updateOrCreate(
                ['slug' => str($specialty['name'])->slug()->value()],
                $specialty,
            );
        }
    }
}

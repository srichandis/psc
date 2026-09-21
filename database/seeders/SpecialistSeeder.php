<?php

namespace Database\Seeders;

use App\Models\Specialist;
use App\Models\Specialty;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class SpecialistSeeder extends Seeder
{
    /**
     * Seed the multidisciplinary team listed on the clinic homepage.
     *
     * Uploaded headshots live in public/images/specialists and are named after
     * the specialist slug. Doctors without an uploaded photo keep a remote
     * placeholder until clinic photography is supplied.
     *
     * `bio` is the short summary used in the page hero and meta description;
     * `profile_sections` holds the longer, doctor-specific blocks rendered
     * further down the profile page.
     */
    public function run(): void
    {
        $specialties = Specialty::pluck('id', 'name');

        $specialists = [
            [
                'name' => 'Dr Sadasivan',
                'qualifications' => 'MBBS, MRCPsych (UK), FRANZCP',
                'role' => 'Consultant Psychiatrist – Adult Mental Health',
                'specialty' => 'Psychiatry',
                'image' => '/images/specialists/dr-sadasivan.png',
                'bio' => 'Dr Sadasivan is an experienced consultant psychiatrist with over 22 years of clinical expertise across the United Kingdom and Australia. She offers evidence-based, compassionate care for adults experiencing a wide range of mental health concerns.',
                'consulting_days' => 'Monday to Thursday',
                'special_interests' => [
                    'Treatment-resistant mood and anxiety disorders',
                    'Substance-use–related conditions',
                    'Integrating mindfulness, lifestyle, and stress-management strategies with pharmacological and psychotherapeutic care',
                ],
                'profile_sections' => [
                    [
                        'heading' => 'Approach to Care',
                        'type' => 'paragraphs',
                        'items' => [
                            'Her approach is holistic and person-centred, recognising that mental wellbeing arises from the balance of biological, psychological, and social factors. She strives to create a therapeutic relationship built on trust, respect, and collaboration.',
                            'Dr Sadasivan follows a biopsychosocial model, combining medical expertise with psychological insight and practical lifestyle support. She works closely with patients, families, and GPs to deliver integrated, individualised care that supports recovery and long-term wellbeing.',
                        ],
                    ],
                    [
                        'heading' => 'Training and Experience',
                        'type' => 'list',
                        'items' => [
                            'Trained in Mersey Deanery (UK); Member of the Royal College of Psychiatrists (MRCPsych, London)',
                            'Fellow, Royal Australian and New Zealand College of Psychiatrists (FRANZCP)',
                            'Over eight years at Gold Coast University Hospital in senior clinical roles',
                            'Former Associate Professor and Clinical Lead in Psychiatry at Bond University',
                        ],
                    ],
                    [
                        'heading' => 'Clinical Expertise',
                        'type' => 'list',
                        'items' => [
                            'Depression and Anxiety Disorders',
                            'OCD and PTSD',
                            'Autism Spectrum Disorder (ASD)',
                            'Bipolar Disorder and Schizophrenia',
                            'Borderline Personality Disorder (BPD)',
                            'Alcohol and Addiction Disorders (excluding opiate-related conditions)',
                        ],
                    ],
                    [
                        'heading' => 'Leadership and Community Work',
                        'type' => 'paragraphs',
                        'items' => [
                            'In addition to her clinical practice, Dr Sadasivan is the founder of Shakti Global, a not-for-profit organisation dedicated to promoting preventive health and wellbeing. Through initiatives such as health education workshops, meditation and yoga sessions, and community cooking programs, Shakti Global aims to bring preventive health to the forefront, fostering social connection, awareness, and early intervention in the community.',
                        ],
                    ],
                    [
                        'heading' => 'Affiliations',
                        'type' => 'list',
                        'items' => [
                            'Fellow, Royal Australian and New Zealand College of Psychiatrists',
                            'Member, Royal College of Psychiatrists (UK)',
                            'Former Associate Professor, Bond University',
                        ],
                    ],
                ],
                'sort_order' => 1,
            ],
            [
                'name' => 'Dr Sarah Tanusha Thomas',
                'qualifications' => 'MBBS, MMed (Clin Epi), FRACP (Neurology)',
                'role' => 'Neurologist – Stroke & Cognitive Neurology',
                'specialty' => 'Neurology',
                'image' => '/images/specialists/dr-sarah-tanusha-thomas.jpeg',
                'bio' => 'Dr Sarah Thomas is a neurologist with subspecialty expertise in stroke and cognitive neurology. She completed her medical and neurology training in Queensland and undertook advanced fellowships in Melbourne at leading centres, including the Royal Melbourne Hospital, Box Hill Hospital and The Alfred Hospital. She was awarded Fellowship of the Royal Australasian College of Physicians (FRACP) in 2024.',
                'consulting_days' => 'Mondays, Wednesdays, Fridays',
                'special_interests' => [
                    'Stroke care',
                    'Cognitive neurology and memory disorders',
                    'Dementia and Alzheimer\'s disease',
                    'Advanced neuroimaging',
                    'Brain health and prevention',
                ],
                'profile_sections' => [
                    [
                        'heading' => 'Research and Clinical Trials',
                        'type' => 'paragraphs',
                        'items' => [
                            'Dr Thomas has been involved in national and international clinical trials, with experience in emerging diagnostics and treatments for stroke and neurodegenerative conditions, including Alzheimer\'s disease. She maintains research affiliations with the Royal Melbourne Hospital and the University of Melbourne, focusing on advanced brain imaging in acute ischaemic stroke.',
                            'In addition to private practice, she works with the Queensland Telestroke Service, providing urgent stroke care via telemedicine to regional and remote hospitals.',
                        ],
                    ],
                    [
                        'heading' => 'Clinical Focus',
                        'type' => 'paragraphs',
                        'items' => [
                            'Dr Sarah Thomas has a strong interest in brain health, prevention, and early detection of cognitive change.',
                        ],
                    ],
                ],
                'sort_order' => 2,
            ],
            [
                'name' => 'Dr Subakumar',
                'qualifications' => 'MBBS, MD, FRACP',
                'role' => 'Endocrinologist',
                'specialty' => 'Endocrinology',
                'image' => '/images/specialists/dr-subakumar.jpg',
                'bio' => 'Dr Subakumar delivers empathetic, evidence-based care across all facets of endocrinology, with a strong focus on complex diabetes management, thyroid disorders, and metabolic health.',
                'consulting_days' => 'Mondays, Thursdays',
                'special_interests' => [
                    'Type 1 & Type 2 Diabetes Management',
                    'Thyroid Nodules & Dysfunction',
                    'Osteoporosis & Calcium Metabolism',
                ],
                'sort_order' => 3,
            ],
            [
                'name' => 'Dr Thomas Titus',
                'qualifications' => 'MBBS, MD, MRCP, D.Phil, FRACP',
                'role' => 'Nephrologist',
                'specialty' => 'Nephrology',
                'image' => 'https://images.unsplash.com/photo-1537368910025-700350fe46c7?auto=format&fit=crop&w=600&q=80',
                'bio' => 'Dr Titus is a Consultant Nephrologist working at Gold Coast University Hospital and in the private sector, as well as an Associate Professor of Nephrology at Bond University.',
                'consulting_days' => 'Monday and Friday (afternoon)',
                'special_interests' => [
                    'Chronic kidney disease',
                    'Dialysis',
                    'Hypertension',
                    'Transplantation',
                ],
                'profile_sections' => [
                    [
                        'heading' => 'Training and Career',
                        'type' => 'paragraphs',
                        'items' => [
                            'Dr Titus completed his MBBS and MD in General Medicine in India in 1991. He relocated to the UK in 1993, obtained MRCP from the Royal College of Physicians, and undertook specialist training in General Medicine and Nephrology in teaching hospitals in Northern England and Oxford. Before relocating to Australia, he worked as a Consultant Physician in Boston, Lincolnshire.',
                            'He has extensive nephrology experience and sees patients aged over 18. Alongside his clinical training, he undertook basic-science research at the University of Oxford and completed his doctorate.',
                        ],
                    ],
                    [
                        'heading' => 'Affiliations',
                        'type' => 'list',
                        'items' => [
                            'Associate Professor, Bond University',
                            'Royal Australasian College of Physicians',
                            'International Society of Nephrology',
                            'American Society of Nephrology',
                        ],
                    ],
                ],
                'sort_order' => 4,
            ],
            [
                'name' => 'Dr Harish Venugopal',
                'qualifications' => 'MBBS, FRACP',
                'role' => 'Endocrinologist',
                'specialty' => 'Endocrinology',
                'image' => '/images/specialists/dr-harish-venugopal.jpg',
                'bio' => 'Dr Harish Venugopal is a Consultant Endocrinologist providing comprehensive care across the breadth of endocrine and metabolic medicine, with particular expertise in diabetes care including insulin-pump management.',
                'consulting_days' => 'Tuesdays, Fridays',
                'special_interests' => [
                    'Thyroid disorders',
                    'Osteoporosis',
                    'Adrenal and pituitary disorders',
                    'Insulin resistance',
                    'Dyslipidaemia',
                    'Hypertension',
                    'Type 1 and Type 2 diabetes',
                    'Insulin-pump management',
                ],
                'sort_order' => 5,
            ],
            [
                'name' => 'Gagandeep Singh',
                'qualifications' => 'Master of Professional Psychology, Bachelor of Psychological Sciences (Honours)',
                'role' => 'Registered Psychologist',
                'specialty' => 'Psychology',
                'image' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=600&q=80',
                'bio' => 'Gagandeep is a Registered Psychologist with experience working across private practice and community mental health settings, supporting children, adolescents, and adults. He has worked within multidisciplinary GP clinics, delivering evidence-based psychological care and collaborating closely with medical professionals.',
                'consulting_days' => 'Monday through Thursday',
                'special_interests' => [
                    'Depression and mood-related concerns',
                    'Anxiety disorders (including OCD and panic)',
                    'Trauma and PTSD',
                    'Emotional regulation difficulties',
                    'Relationship and interpersonal challenges',
                    'Stress, burnout, and workplace concerns',
                    'Adjustment-related issues',
                    'Grief and bereavement',
                ],
                'profile_sections' => [
                    [
                        'heading' => 'Clinical Approach',
                        'type' => 'paragraphs',
                        'items' => [
                            'His clinical approach focuses on structured assessment, clear case formulation, and practical interventions tailored to individual needs. Gagandeep has experience in conducting diagnostic interviews, risk assessments, and providing CBT-informed therapy.',
                            'He is committed to ethical, culturally responsive practice and aims to provide practical, structured, and evidence-based support to help individuals build skills, improve functioning, and navigate life\'s challenges effectively.',
                        ],
                    ],
                    [
                        'heading' => 'Services Offered',
                        'type' => 'list',
                        'items' => [
                            'Individual therapy (children, adolescents, adults)',
                            'Cognitive Behavioural Therapy (CBT)',
                            'Psychological assessment and screening',
                            'Mental health support (NDIS – self and plan managed)',
                            'Telehealth and face-to-face consultations',
                        ],
                    ],
                    [
                        'heading' => 'Qualifications',
                        'type' => 'list',
                        'items' => [
                            'Master of Professional Psychology – University of New England',
                            'Bachelor of Psychological Sciences (Honours) – University of Adelaide',
                        ],
                    ],
                ],
                'sort_order' => 6,
            ],
        ];

        foreach ($specialists as $specialist) {
            Specialist::updateOrCreate(
                ['slug' => str($specialist['name'])->slug()->value()],
                [
                    ...Arr::except($specialist, 'specialty'),
                    'specialty_id' => $specialties[$specialist['specialty']] ?? null,
                ],
            );
        }
    }
}

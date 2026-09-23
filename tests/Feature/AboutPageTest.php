<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AboutPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The About page is a standalone route, reachable from the homepage.
     */
    public function test_homepage_links_to_the_about_page(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee(route('about'), false);
    }

    /**
     * The page opens with the clinic introduction and its philosophy.
     */
    public function test_about_page_renders_the_introduction_and_philosophy(): void
    {
        $response = $this->get('/about');

        $response->assertSee('well-established multidisciplinary specialist medical clinic', false);
        $response->assertSee('make high-quality specialist healthcare more accessible', false);
        $response->assertSee('Suite 1, 4 Jowett Street, Coomera QLD 4209', false);
        $response->assertSee('Our current services include Psychiatry, Neurology', false);
        $response->assertSee('good healthcare begins with listening', false);
        $response->assertSee('Our philosophy');
        $response->assertSee('Specialist expertise. Personalised care. Close to home.');
        $response->assertSee('the individual behind the diagnosis', false);
    }

    /**
     * The page renders the location and parking information.
     */
    public function test_about_page_renders_location_and_parking(): void
    {
        $response = $this->get('/about');

        $response->assertOk();
        $response->assertSee('About Our Clinic');
        $response->assertSee('4 Jowett Street, Coomera QLD 4209');
        $response->assertSee('free but limited parking', false);
        $response->assertSee('designated spaces for patients with disabilities', false);
        $response->assertSee('street parking is also available along Jowett Street', false);
    }

    /**
     * The page lists what patients should bring to their appointment.
     */
    public function test_about_page_renders_what_to_bring(): void
    {
        $response = $this->get('/about');

        $response->assertSee('complete some essential patient forms', false);
        $response->assertSee('Your Medicare Card');
        $response->assertSee('Your referral, if not already provided');
    }

    /**
     * The page explains how to book and how referrals can be sent.
     */
    public function test_about_page_renders_booking_and_referral_information(): void
    {
        $response = $this->get('/about');

        $response->assertSee('Booking an appointment');
        $response->assertSee('call our friendly reception team on (07) 5500 0536', false);
        $response->assertSee('A referral from your GP or another specialist is required', false);

        $response->assertSee('Faxed');
        $response->assertSee('Sent electronically');
        $response->assertSee('Or brought with you on the day of your appointment');
    }

    /**
     * The page covers enquiries, waiting times and urgent appointments.
     */
    public function test_about_page_renders_contact_and_urgent_care_notice(): void
    {
        $response = $this->get('/about');

        $response->assertSee('contact us via our online form or give us a call', false);
        $response->assertSee('appointment availability and waiting times vary depending on the specialist', false);
        $response->assertSee('For urgent appointments, your referring doctor can arrange', false);
    }

    /**
     * The About page exposes the same shared navigation and booking modal
     * as the rest of the public site.
     */
    public function test_about_page_includes_the_booking_modal_and_navigation(): void
    {
        $response = $this->get('/about');

        $response->assertSee('Request an Appointment');
        $response->assertSee('name="full_name"', false);
        $response->assertSee(route('appointments.store'), false);
        $response->assertSee('Main navigation');
    }
}

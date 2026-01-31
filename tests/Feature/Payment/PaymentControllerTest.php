<?php

namespace Tests\Feature\Payment;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

/**
 * Feature tests for payment flow via PaymentController.
 *
 * Notes:
 * - We mock PaymentService to avoid external gateway calls.
 * - We rely on routes defined in routes/web.php for payment selection, initiation, and result pages.
 */
class PaymentControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_renders_payment_selection_for_authenticated_user()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        // Assuming route name 'payment.select' exists and shows select view
        $response = $this->get(route('payment.select'));

        $response->assertStatus(200);
        $response->assertSee('Metode Pembayaran'); // Expect a visible heading or text in select.blade.php
    }

    /** @test */
    public function it_validates_payment_request_and_returns_errors_for_invalid_payload()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Post with empty payload to trigger validation errors
        $response = $this->post(route('payment.process'), []);

        $response->assertSessionHasErrors();
    }

    /** @test */
    public function it_initiates_payment_and_redirects_to_gateway_on_success()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Mock PaymentService resolution
        $mock = Mockery::mock('overload:App\\Services\\PaymentService');
        $mock->shouldReceive('initiate')
            ->once()
            ->andReturn((object) [
                'redirect_url' => 'https://gateway.example/redirect-token'
            ]);

        // Provide minimal valid payload according to PaymentRequest rules
        $payload = [
            'amount' => 100000,
            'method' => 'bank_transfer',
        ];

        $response = $this->post(route('payment.process'), $payload);

        $response->assertRedirect('https://gateway.example/redirect-token');
    }

    /** @test */
    public function it_handles_failed_payment_initiation_and_redirects_back_with_error()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $mock = Mockery::mock('overload:App\\Services\\PaymentService');
        $mock->shouldReceive('initiate')
            ->once()
            ->andThrow(new \RuntimeException('Gateway down'));

        $payload = [
            'amount' => 100000,
            'method' => 'bank_transfer',
        ];

        $response = $this->from(route('payment.select'))->post(route('payment.process'), $payload);

        $response->assertRedirect(route('payment.select'));
        $response->assertSessionHas('error');
    }

    /** @test */
    public function it_shows_success_page_after_successful_payment_callback()
    {
        // No auth required if callback redirects user to success page by token/reference
        $response = $this->get(route('payment.success'));

        $response->assertStatus(200);
        $response->assertSee('Pembayaran Berhasil'); // Expect heading in success.blade.php
    }
}

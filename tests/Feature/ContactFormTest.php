<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Mail\Transport\ArrayTransport;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_sends_the_message_and_redirects_back(): void
    {
        $response = $this->from('/contact')->post('/contact', [
            'name' => 'Awa Diop',
            'email' => 'awa@example.com',
            'subject' => 'Question sur une offre',
            'message' => 'Bonjour, je souhaite des précisions sur le poste.',
        ]);

        $response->assertRedirect('/contact');
        $response->assertSessionHas('status');
    }

    public function test_the_sent_email_contains_the_visitor_message(): void
    {
        $this->post('/contact', [
            'name' => 'Awa Diop',
            'email' => 'awa@example.com',
            'subject' => 'Question sur une offre',
            'message' => 'Bonjour, je souhaite des précisions sur le poste.',
        ]);

        // `getSymfonyTransport()` est typé TransportInterface ; seul le
        // transport `array` des tests expose `messages()`. L'assertion dit
        // explicitement sur quoi le test s'appuie au lieu de le supposer.
        $transport = Mail::mailer()->getSymfonyTransport();
        $this->assertInstanceOf(ArrayTransport::class, $transport);

        $sent = $transport->messages();

        $this->assertCount(1, $sent);

        $body = $sent[0]->getOriginalMessage()->getHtmlBody();

        $this->assertStringContainsString('Awa Diop', $body);
        $this->assertStringContainsString('awa@example.com', $body);
        $this->assertStringContainsString('Bonjour, je souhaite des précisions sur le poste.', $body);
    }
}

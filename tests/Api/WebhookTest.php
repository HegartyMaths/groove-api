<?php

namespace Tests\Api;

use Tests\TestCase;
use Groove\Api\Webhook;

class WebhookTest extends TestCase
{
    /** @test */
    public function it_can_create_a_webhook()
    {
        $client = $this->getMockClient();
        $client
            ->shouldReceive('post')
            ->with('webhooks', ['event' => 'groove-event', 'url' => 'your-url'])
            ->once()
            ->andReturn(json_decode('{"webhook": {"event": "groove-event", "url": "your-url"}}'));

        $response = (new Webhook($client))->create('groove-event', 'your-url');

        $this->assertEquals($response->event, 'groove-event');
        $this->assertEquals($response->url, 'your-url');
    }
}
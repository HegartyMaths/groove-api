<?php

namespace Tests\Api;

use Tests\TestCase;
use Groove\Api\Ticket;
use Illuminate\Support\Collection;
use Groove\Models\Ticket as TicketModel;

class TicketTest extends TestCase
{
    const TICKET_BODY = 'hello world';
    const TICKET_FROM = 'from@email.com';
    const TICKET_TO = 'to@email.com';
    const TICKET_NUMBER = 123456;

    /** @test */
    public function it_can_create_a_ticket()
    {
        $client = $this->getMockClient();
        $client
            ->shouldReceive('post')
            ->with('tickets', ['body' => self::TICKET_BODY, 'from' => self::TICKET_FROM, 'to' => self::TICKET_TO])
            ->once()
            ->andReturn(json_decode('{"ticket": {"body": "'.self::TICKET_BODY.'"}}'));

        $ticket = (new Ticket($client))->create(self::TICKET_BODY, self::TICKET_FROM, self::TICKET_TO);

        $this->assertInstanceOf(TicketModel::class, $ticket);
        $this->assertEquals(self::TICKET_BODY, $ticket->body);
    }

    /** @test */
    public function it_can_find_a_list_of_tickets()
    {
        $client = $this->getMockClient();
        $client
            ->shouldReceive('get')
            ->with('tickets', [])
            ->once()
            ->andReturn(json_decode('{"tickets": [{}]}'));

        $tickets = (new Ticket($client))->list();

        $this->assertInstanceOf(Collection::class, $tickets);
        $this->assertInstanceOf(TicketModel::class, $tickets[0]);
    }

    /** @test */
    public function it_can_find_a_ticket()
    {
        $client = $this->getMockClient();
        $client
            ->shouldReceive('get')
            ->with('tickets/'.self::TICKET_NUMBER)
            ->once()
            ->andReturn(json_decode('{"ticket": {"number": "'.self::TICKET_NUMBER.'"}}'));

        $ticket = (new Ticket($client))->find(self::TICKET_NUMBER);

        $this->assertInstanceOf(TicketModel::class, $ticket);
        $this->assertEquals(self::TICKET_NUMBER, $ticket->number);
    }

    /** @test */
    public function it_can_update_a_tickets_assignee()
    {
        $client = $this->getMockClient();
        $client
            ->shouldReceive('put')
            ->with('tickets/'.self::TICKET_NUMBER.'/assignee', ['assignee' => 'assignee@email.com'])
            ->once();

        $updated = (new Ticket($client))->updateAssignee(self::TICKET_NUMBER, 'assignee@email.com');

        $this->assertTrue($updated);
    }

    /** @test */
    public function it_can_update_a_tickets_priority()
    {
        $client = $this->getMockClient();
        $client
            ->shouldReceive('put')
            ->with('tickets/'.self::TICKET_NUMBER.'/priority', ['priority' => 'urgent'])
            ->once();

        $updated = (new Ticket($client))->updatePriority(self::TICKET_NUMBER, 'urgent');

        $this->assertTrue($updated);
    }

    /** @test */
    public function it_can_update_a_tickets_group()
    {
        $client = $this->getMockClient();
        $client
            ->shouldReceive('put')
            ->with('tickets/'.self::TICKET_NUMBER.'/assigned_group', ['group' => 'groupID'])
            ->once();

        $updated = (new Ticket($client))->updateGroup(self::TICKET_NUMBER, 'groupID');

        $this->assertTrue($updated);
    }

    /** @test */
    public function it_can_find_a_tickets_state()
    {
        $client = $this->getMockClient();
        $client
            ->shouldReceive('get')
            ->with('tickets/'.self::TICKET_NUMBER.'/state')
            ->once()
            ->andReturn('unread');

        $status = (new Ticket($client))->ticketState(self::TICKET_NUMBER);

        $this->assertEquals('unread', $status);
    }

    /** @test */
    public function it_can_find_a_tickets_assignee()
    {
        $client = $this->getMockClient();
        $client
            ->shouldReceive('get')
            ->with('tickets/'.self::TICKET_NUMBER.'/assignee')
            ->once()
            ->andReturn('test@email.com');

        $assignee = (new Ticket($client))->ticketAssignee(self::TICKET_NUMBER);

        $this->assertEquals('test@email.com', $assignee);
    }

    /** @test */
    public function it_can_find_a_tickets_count()
    {
        $client = $this->getMockClient();
        $client
            ->shouldReceive('get')
            ->with('tickets/count')
            ->once()
            ->andReturn('123');

        $assignee = (new Ticket($client))->count();

        $this->assertEquals('123', $assignee);
    }
}

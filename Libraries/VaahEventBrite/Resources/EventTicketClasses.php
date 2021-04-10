<?php
declare(strict_types=1);

namespace WebReinvent\VaahExtend\Libraries\VaahEventBrite\Resources;

use WebReinvent\VaahExtend\Facades\VaahEventbrite;

class EventTicketClasses extends VaahEventbrite
{
    public function store(int $event_id, array $event): array
    {
        return VaahEventBrite::request('post', "/events/$event_id/ticket_classes", $event);
    }
}
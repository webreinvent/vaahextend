<?php
declare(strict_types=1);

namespace WebReinvent\VaahExtend\Libraries\VaahEventBrite\Resources;

use WebReinvent\VaahExtend\Facades\VaahEventbrite;

class Attendees extends VaahEventbrite
{
    public function get(int $event_id)
    {
        return VaahEventBrite::request('get', "/events/$event_id/attendees");
    }

    public function find(int $event_id, int $attendee_id)
    {
        return VaahEventBrite::request('get', "/events/$event_id/attendees/$attendee_id");
    }
}
<?php
declare(strict_types=1);

namespace WebReinvent\VaahExtend\Libraries\VaahEventBrite\Resources;

use WebReinvent\VaahExtend\Facades\VaahEventBrite;

class Events extends VaahEventBrite
{
    public function get($params = []): array
    {
        $organisationId = config('eventbrite.org');
        return VaahEventBrite::request('get', "/organizations/$organisationId/events",$params);
    }

    public function find(int $event_id): array
    {
        $event =  VaahEventBrite::request('get', "/events/$event_id");
        //fetching description for each event
        $event['updated_description']  =  VaahEventBrite::request('get', "/events/$event_id/description");
        return $event;
    }

    public function store(array $event): array
    {
        $organisationId = config('services.eventbrite.orgid');
        return VaahEventBrite::request('post', "/organizations/$organisationId/events", $event);
    }

    public function update(int $event_id, array $event): array
    {
        return VaahEventBrite::request('post', "/events/$event_id", $event);
    }

    public function cancel(array $event_id): array
    {
        return VaahEventBrite::request('post', "/events/$event_id/cancel");
    }

    public function publish(int $event_id): array
    {
        return VaahEventBrite::request('post', "/events/$event_id/publish");
    }

    public function delete(int $event_id): array
    {
        return VaahEventBrite::request('delete', "/events/$event_id");
    }
}

<?php
declare(strict_types=1);

namespace WebReinvent\VaahExtend\Libraries\VaahEventBrite\Resources;

use WebReinvent\VaahExtend\Facades\VaahEventbrite;

class Orders extends VaahEventbrite
{
    public function find(int $order_id): array
    {
        return VaahEventBrite::request('get', "/orders/$order_id");
    }
}

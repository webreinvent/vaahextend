<?php
declare(strict_types=1);

namespace WebReinvent\VaahExtend\Libraries\VaahEventBrite\Resources;

use WebReinvent\VaahExtend\Facades\VaahEventbrite;

class Organizations extends VaahEventbrite
{
    public function get(): array
    {
        return VaahEventBrite::request('get', "/users/me/organizations");
    }
}
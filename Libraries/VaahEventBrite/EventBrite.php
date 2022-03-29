<?php
declare(strict_types=1);

namespace WebReinvent\VaahExtend\Libraries\VaahEventBrite;

use Exception;
use WebReinvent\VaahExtend\Libraries\VaahEventBrite\Resources\Events;
use WebReinvent\VaahExtend\Libraries\VaahEventBrite\Resources\Orders;
use WebReinvent\VaahExtend\Libraries\VaahEventBrite\Resources\Attendees;
use WebReinvent\VaahExtend\Libraries\VaahEventBrite\Resources\organizations;

class EventBrite
{
    public function events(): object
    {
        return new Events;
    }

    public function attendees(): object
    {
        return new Attendees;
    }

    public function orders(): object
    {
        return new Orders;
    }

    public function organizations(): object
    {
        return new organizations;
    }

    public function __call(string $function, array $args)
    {
        $options = ['get', 'post', 'patch', 'put', 'delete'];
        $path = (isset($args[0])) ? $args[0] : '';
        $data = (isset($args[1])) ? $args[1] : null;
        $header = (isset($args[2])) ? $args[2] : null;

        if (in_array($function, $options)) {
            return self::request($function, $path, $data, $header);
        } else {
            //request verb is not in the $options array
            throw new Exception($function.' is not a valid HTTP Verb');
        }
    }
    
    public function request(string $type, string $endpoint, array $data = [])
    {
        $url = 'https://www.eventbriteapi.com/v3';
        $key = env('EVENTBRITE_KEY');

        $c = new Curl($url);
        return $c->$type($endpoint.'?token='.$key, $data);
    }
}

<?php namespace WebReinvent\VaahExtend\Libraries;


class VaahUrl{



    //-------------------------------------------------
    public function getHttpProtocol()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

        return $protocol;
    }
    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------
    public function getTopLevelDomain($url)
    {
        $parsedUrl = parse_url($url);
        $scheme = isset($parsedUrl['scheme']) ? $parsedUrl['scheme'] . '://' : 'http://';


        // Check for localhost
        if (stripos($url, 'localhost') !== false) {
            return $scheme .'localhost';
        }

        // Check for localhost
        if (stripos($url, '127.0.0.1') !== false) {
            return $scheme .'127.0.0.1';
        }


        $host = $parsedUrl['host'] ?? '';

        // Check for IP address
        if (filter_var($host, FILTER_VALIDATE_IP)) {
            return $scheme . $host;
        }

        $parts = explode('.', $host);

        // If we have 2 or fewer parts, return the whole host
        if (count($parts) <= 2) {
            return $scheme . $host;
        }

        return $scheme . implode('.', array_slice($parts, -2));
    }

    //-------------------------------------------------

}

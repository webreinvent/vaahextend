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
        $urlData = parse_url($url);
        $urlHost = isset($urlData['host']) ? $urlData['host'] : '';
        $isIP = (bool)ip2long($urlHost);
        if($isIP){ /** To check if it's ip then return same ip */
            return $urlHost;
        }
        /** Add/Edit you TLDs here */
        $urlMap = array('dev', 'com');

        $host = "";
        $hostData = explode('.', $urlHost);
        if(isset($hostData[1])){ /** To check "localhost" because it'll be without any TLDs */
            $hostData = array_reverse($hostData);

            if(array_search($hostData[1] . '.' . $hostData[0], $urlMap) !== FALSE) {
                $host = $hostData[2] . '.' . $hostData[1] . '.' . $hostData[0];
            } elseif(array_search($hostData[0], $urlMap) !== FALSE) {
                $host = $hostData[1] . '.' . $hostData[0];
            }
            return $this->getHttpProtocol().$host;
        }
        return ((isset($hostData[0]) && $hostData[0] != '') ? $hostData[0] : 'error no domain'); /* You can change this error in future */
    }

    //-------------------------------------------------

}

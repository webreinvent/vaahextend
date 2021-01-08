<?php namespace WebReinvent\VaahExtend\Libraries;


class VaahAssets{

    //-------------------------------------------------
    public function getStopWords()
    {
        return json_decode(file_get_contents(__DIR__.'/../Assets/stop-words.json'), true);
    }
    //-------------------------------------------------
    public function hasStopWord($word)
    {
        $list = $this->getStopWords();
        if(in_array($word, $list))
        {
            return true;
        }
        return false;
    }
    //-------------------------------------------------

}

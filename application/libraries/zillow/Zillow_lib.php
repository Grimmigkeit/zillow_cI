<?php

/**
 * Class Zillow_lib
 * @property zwsid
 * @property url
 */
class Zillow_lib
{
    /** @var string */
    private $zwsid = 'X1-ZWz18gp18q7ci3_5taq0';

    /** @var string */
    private $url = '';

    public function __construct($zwsid = '')
    {
        if ($zwsid) $this->zwsid = $zwsid;
    }

    /**
     * @param string $address
     * @param string $zip
     * @return object
     */
    public function getFirstSearchResult($address, $zip)
    {
        if (!$address || !$zip) return false;

        $url = sprintf('https://www.zillow.com/webservice/GetSearchResults.htm?zws-id=%s&address=%s&citystatezip=%s', $this->zwsid, $address, $zip);
        $xmlstr = file_get_contents($url);
        $xmlcont = new SimpleXMLElement($xmlstr);

        return $xmlcont->response->results ? $xmlcont->response->results->result[0] : false;
    }

    /**
     * @param int $zpid
     * @return object
     */
    public function getZestimate($zpid)
    {
        if (!$zpid) return false;

        $url = sprintf('https://www.zillow.com/webservice/GetZestimate.htm?zws-id=%s&zpid=%s', $this->zwsid, $zpid);
        $xmlstr = file_get_contents($url);
        $xmlcont = new SimpleXMLElement($xmlstr);

        return $xmlcont;
    }

    /**
     * @param int $zpid
     * @return object
     */
    public function getDetails($zpid)
    {
        if (!$zpid) return false;

        $url = sprintf('https://www.zillow.com/webservice/GetUpdatedPropertyDetails.htm?zws-id=%s&zpid=%s', $this->zwsid, $zpid);
        $xmlstr = file_get_contents($url);
        $xmlcont = new SimpleXMLElement($xmlstr);

        return $xmlcont;
    }

}
<?php

namespace Perspective\GeoLocation\Model;

class IpLocator
{
    protected $apiKey = 'dcc8ba51bf33967f494843716aa86c0a';

    /**
     * Get city by IP via ipstack
     *
     * @param string $ip
     * @return string|null
     */
    public function getCityByIp($ip)
    {
        $url = sprintf('http://api.ipstack.com/%s?access_key=%s&fields=city', $ip, $this->apiKey);
        $response = @file_get_contents($url);
        if ($response === false) {
            return null;
        }
        $data = json_decode($response, true);
        return $data['city'] ?? null;
    }
}

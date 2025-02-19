<?php

namespace Perspective\NewGeoLocation\Api;

/**
 * Interface GeoLocationInterface
 * Service contract for retrieving city by IP.
 */
interface GeoLocationInterface
{
    /**
     * Retrieve the city name by a given IP address.
     *
     * @param string $ip
     * @return string|null
     */
    public function getCityByIp($ip);
}

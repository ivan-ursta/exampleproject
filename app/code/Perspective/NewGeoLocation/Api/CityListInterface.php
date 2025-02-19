<?php

namespace Perspective\NewGeoLocation\Api;

/**
 * Interface CityListInterface
 * Service contract for retrieving a list of cities.
 */
interface CityListInterface
{
    /**
     * Retrieve a list of cities for the specified country code.
     *
     * @param string $countryCode
     * @return array
     */
    public function getCities($countryCode = 'UA');
}

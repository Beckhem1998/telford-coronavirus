<?php

namespace App\Services;

use Carbon\Carbon;
use GuzzleHttp\Client;

/**
 * Class GovAPIService
 *
 * @package App\Services
 *
 * @author Beckhem <beckhem.meredith@hotmail.co.uk>
 */
class GovAPIService
{

    /**
     * Format to expect from the CLI argument
     *
     * @var string DATE_FORMAT
     */
    private const DATE_FORMAT = 'Y-m-d';

    /**
     * Guzzle Client
     *
     * @var Client $_client
     */
    private Client $_client;

    /**
     * GovAPIService constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_client = new Client();
    }

    /**
     * Send request to API and return all new cases for given date,
     * if date is null then just use todays
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return int
     */
    public function send(): int
    {
        $date = $_SERVER['argv'][1] ?? Carbon::yesterday()->format(self::DATE_FORMAT);

        $response = $this->_client->get($this->getEndpoint($date));

        return json_decode($response->getBody()->getContents())->data[0]->newCasesByPublishDate;
    }

    /**
     * Get the endpoint for the Gov Coronavirus API
     *
     * @param string $date - Date given on CLI
     *
     * @return string
     */
    private function getEndpoint(string $date): string
    {
        return sprintf(
            '%s?filters=areaType=ltla;areaCode=%s;date=%s&structure=%s',
            $_ENV['GOV_API_URL'],
            $_ENV['GOV_TELFORD_CODE'],
            $date,
            json_encode(['newCasesByPublishDate' => 'newCasesByPublishDate'])
        );
    }
}
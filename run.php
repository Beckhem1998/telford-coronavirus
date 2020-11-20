<?php

use Symfony\Component\Dotenv\Dotenv;
use App\Services\GovAPIService;
require './vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');

$govApiService = new GovAPIService();

echo $govApiService->send();

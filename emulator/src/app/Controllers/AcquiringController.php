<?php

namespace App\Controllers;

use App\Fakers\ATFBankEcom;
use Symfony\Component\HttpFoundation\Request;

class AcquiringController
{
    private array $fakers = [
        'atf' => ATFBankEcom::class
    ];

    public function index(Request $request, string $driver)
    {
        $faker = new $this->fakers[$driver];


        return $driver;
    }
}
<?php

namespace App\Controllers\KapitalBank;

use App\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RocketGateController extends Controller
{
    /**
     * @Route("/fakers/kapitalBank/rg/auth/getToken")
     * @return Response
     */
    public function getToken(): Response
    {
        return $this->json([
            'data' => [
                'token' => 'eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJydXN0YW1fYWRtaW4iLCJyb2xlIjoiQ0xJRU5UIiwiaWQiOjEsInN
hbHQiOiJ1OXpzaXd3bGUiLCJpYXQiOjE2Mzk0OxMzMsImV4cCI6MTYzOTc0MzMzM30.NgLFr2-7XSjN7XIXev-j8ZRHHtBAuZGOUhgujKsH9_qgC6DJhYxMWtNpF6V8RvC5pF-vebGxKIxHf90feG8RA"',
                'refreshToken' => 'eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiIxIidXNlcm5hbWUiOiJydXN0YW1fYWRtaW4iLCJpZCI6MSwic2Fsd
CI6InU5enNpd3dsZSIsImlhdCI6MTYzOTQ4NDEzMywiZXhwIjoxNjcxMDIwMTMzfQ.Xbzgd9mROxE1qgufHVI
NUM9NjqV7h-ynCmfRRqiD42VoD_pXO1Ityp33wxkW1JGNU8cGQaOzC7VomIXKkBP0Eg'
            ],
            'errorMessage' => '',
            'timestamp' => time()
        ], Response::HTTP_OK);
    }

    /**
     * @Route("/fakers/kapitalBank/rg/uzcard/get-card-token")
     * @return Response
     */
    public function uzcardGetCardToken(): Response
    {
        return $this->json([
            'data' => [
                'id' => 'BB593F84BBEDFE03E053D30811AC7FE7',
                'username' => 'kapitalBank',
                'pan' => '860049******3449',
                'expiry' => '2402',
                'status' => 0,
                'phone' => '998931714617',
                'fullName' => 'RUSTAMJON XALMATOV OPERU',
                'balance' =>  54906019,
                'sms' => true,
                'pincnt' =>  0,
                'aacct' =>  "0115822618000991665272001",
                'cardtype' =>  'private',
                'holdAmount' =>  -850000
            ],
            'errorMessage' => '',
            'timestamp' => time(),
        ], Response::HTTP_OK);
    }

    /**
     * @Route("/fakers/kapitalBank/rg/operation/debit/uzcard")
     * @return Response
     */
    public function operationDebitUzcard(): Response
    {
        return $this->json([
            'data' => [
                'operationId' => '0e0c46bd-0029-4249-84c9-69475f3e5f73',
                'status' => 'SUCCESS',
            ],
            'errorMessage' => '',
            'timestamp' => time(),
        ], Response::HTTP_OK);
    }

    /**
     * @Route("/fakers/kapitalBank/rg/operation/debit/humo")
     * @return Response
     */
    public function operationDebitHumo(): Response
    {
        return $this->json([
            'data' => [
                'operationId' => 'e286045a-9486-44b2-821e-32e91384dd13',
                'status' => 'SUCCESS',
            ],
            'errorMessage' => '',
            'timestamp' => time(),
        ], Response::HTTP_OK);
    }

    /**
     * @Route("/fakers/kapitalBank/rg/operation/reverse")
     * @return Response
     */
    public function operationReverse(): Response
    {
        return $this->json([
            'data' => [
                'operationId' => '5333b036-4e51-497c-a0a5-3d4e6fa07183',
                'status' => 'SUCCESS',
            ],
            'errorMessage' => '',
            'timestamp' => time(),
        ], Response::HTTP_OK);
    }

    /**
     * @Route("/fakers/kapitalBank/rg/humo/get-smsInfo-number")
     * @return Response
     */
    public function humoGetSmsInfoNumber(): Response
    {
        return $this->json([
            'data' => [
                'smsInfoNumber' => '998931714617'
            ],
            'errorMessage' => '',
            'timestamp' => time(),
        ], Response::HTTP_OK);
    }
}

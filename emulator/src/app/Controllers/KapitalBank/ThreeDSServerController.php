<?php

namespace App\Controllers\KapitalBank;

use App\Controllers\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ThreeDSServerController extends Controller
{
    public string $theeDSServerTransId = 'a13eef97-7589-4c91-8976-fcf80e01c267';

    /**
     * @Route("/fakers/kapitalBank/3ds/preparation")
     * @return JsonResponse
     */
    public function preparation(): JsonResponse
    {
        return new JsonResponse([
            'threeDSServerTransID' => $this->theeDSServerTransId,
            'xid' => 'gFjqb8J7RKuARXBX3EFRjjNEUzI="',
            'protocolVersion' => '2.1.0',
            'protocolType' => 'visa',
        ], Response::HTTP_OK);
    }

    /**
     * @Route("/fakers/kapitalBank/3ds/authentication")
     * @return JsonResponse
     */
    public function authentication(): JsonResponse
    {
        $acsUrl = $this->generateUrl('acs_server', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $session = new Session();
        $session->set('notificationUrl', $acsUrl);
        $session->save();

        return new JsonResponse([
            'acsURL' => $acsUrl,
            'acsTransID' => '506d5e5d-4426-46fe-9c80-f8691023b3f9',
            'xid' => 'gFjqb8J7RKuARXBX3EFRjjNEUzI=',
            'dsTransID' => '1e11955f-cef6-4b0d-b313-e6f8c61e02b7',
            'protocolVersion' => '2.1.0',
            'protocolType' => 'visa',
            'authenticationType' => '02',
            'acsChallengeMandated' => 'Y',
            'transStatus' => 'C',
            'threeDSServerTransID' => $this->theeDSServerTransId,
        ], Response::HTTP_OK);
    }

    /**
     * @Route("/fakers/kapitalBank/3ds/result")
     * @return JsonResponse
     */
    public function result(): JsonResponse
    {
        return new JsonResponse([
            'threeDSServerTransID' => $this->theeDSServerTransId,
            'acsTransID' => '18893229-4e88-415a-b5a9-96835e52d6da',
            'xid' => 'e8smFAEcTwmlNznAg1D97jNEUzI=',
            'dsTransID' => '0bbe3265-9ab1-4092-8fc6-b429f6d9e046',
            'eci' => '05',
            'protocolVersion' => '2.1.0',
            'authenticationValue' => 'AAIBA5CDlAAAAAXhhAKZdBcxMHE=',
            'transStatus' => 'Y',
        ],Response::HTTP_OK);
    }
}

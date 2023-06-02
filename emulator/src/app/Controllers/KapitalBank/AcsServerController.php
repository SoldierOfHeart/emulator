<?php

namespace App\Controllers\KapitalBank;

use App\Controllers\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class AcsServerController extends Controller
{
    /**
     * @Route("/fakers/kapitalBank/acs", name="acs_server")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $cResRaw = [
            'acsTransID' => 'c83afa81-faba-432e-8c9e-2eb8ee11a716',
            'messageType' => 'CRes',
            'messageVersion' => '2.1.0',
            'threeDSServerTransID' => 'f3ab2a21-c5db-42da-b30a-271b24ea15a9',
            'transStatus' => 'Y',
        ];

        $cRes = base64_encode(json_encode($cResRaw, true));

        $session = new Session();
        $url = $session->get('notificationUrl');

        return $this->render('kapitalBank/acs.html.twig', [
            'termUrl' => $url,
            'cRes' => $cRes,
        ]);
    }
}

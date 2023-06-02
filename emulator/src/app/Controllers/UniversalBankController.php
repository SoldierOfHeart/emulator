<?php

namespace App\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class UniversalBankController extends Controller
{
    /**
     * @Route("/fakers/universal")
     */
    public function index(Request $request): Response
    {
        $jsonData = json_decode($request->getContent(), true);

        switch ($jsonData['method']){
            case 'login':
                $res = [
                    'jsonrpc' => '2.0',
                    'id' => 1,
                    'status' => true,
                    'origin' => 'login',
                    'result' => [
                        'access_token' => 'qwerty',
                        'message' => null
                    ]
                ];
                return new JsonResponse($res);
            case 'payment.create':
                $res = [
                    'jsonrpc' => '2.0',
                    'id' => 1,
                    'status' => true,
                    'origin' => 'payment',
                    'result' => [
                        'tr_id' => '58bd0f32-5007-4802-bafe-c093701faae9',
                        'state' => 0,
                        'description' => 'Initial',
                    ]
                ];
                return new JsonResponse($res);
            case 'payment.confirm':
                $res = [
                    'jsonrpc' => '2.0',
                    'id' => 1,
                    'status' => true,
                    'origin' => 'payment',
                    'result' => [
                        'tr_id' => '58bd0f32-5007-4802-bafe-c093701faae9',
                        'state' => 4,
                    ],
                ];

                return new JsonResponse($res);
            default:
                return new JsonResponse('ok');
        }
    }
}

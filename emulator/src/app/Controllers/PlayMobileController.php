<?php

namespace App\Controllers;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Эмулятор сервиса СМС уведомлений Play-Mobile (Узбекистан)
 */
class PlayMobileController extends Controller
{
    /**
     * @Route("/fakers/play-mobile")
     */
    public function index(Request $request): Response
    {

        $body = 'Request is received';

        $response = new Response($body);
        $response->headers->set('Content-Type', 'text/html; charset=utf-8');

        return $response;
    }
}

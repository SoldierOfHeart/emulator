<?php

namespace App\Controllers;

use App\Helpers\Helper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ThreeDomainSecureController extends Controller
{
    /**
     * @Route("/fakers/3ds", name="3ds")
     */
    public function threeDomainSecurePage(Request $request)
    {
        $data = [
            'termUrl' => $request->get('TermUrl'),
            'paRes'   => Helper::randomStr(1000),
        ];

        return $this->render('3ds.html.twig', $data);
    }
}
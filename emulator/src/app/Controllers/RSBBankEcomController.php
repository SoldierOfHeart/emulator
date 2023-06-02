<?php

namespace App\Controllers;

use App\Fakers\RSBBankEcom;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RSBBankEcomController extends Controller
{
    private RSBBankEcom $faker;

    public function __construct()
    {
        $this->faker = new RSBBankEcom();
    }

    /**
     * @Route("/fakers/rsb/acsRequest")
     */
    public function acsRequest(Request $request)
    {
        $data = [
            'md'      => uniqid(),
            'paReq'   => uniqid(),
            'termUrl' => '',
            'acsUrl'  => $this->generateUrl('3ds', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ];

        return $this->render('payment_systems/rsb_bank_ecom/3ds.html.twig', $data);
    }

    public function createDMSPayment()
    {
        $response = $this->faker->createDMSPayment();

        return $response;
    }

    public function acsDispatch()
    {
        return $this->render('payment_systems/rsb_bank_ecom/acs_dispatch.html.twig', $this->faker->acsDispatch());
    }
}
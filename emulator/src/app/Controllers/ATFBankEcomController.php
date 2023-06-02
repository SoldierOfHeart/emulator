<?php

namespace App\Controllers;

use App\Fakers\ATFBankEcom;
use App\MockResponder\MockResponder;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ATFBankEcomController extends Controller
{
    const
        TR_TYPE_PAY          = '0',
        TR_TYPE_AUTOCLEARING = '1',
        TR_TYPE_CLEARING     = '21',
        TR_TYPE_RECURRENT    = '171',
        TR_TYPE_REVERSE      = '22',
        TR_TYPE_REFUND       = '14'
    ;

    /**
     * @var ATFBankEcom $faker
     */
    private ATFBankEcom $faker;

    public function __construct()
    {
        $this->faker = new ATFBankEcom();

        //$this->mockResponder = (new MockResponder())->addResponder()->getResponder();
    }

    private function test(Request $request)
    {

    }

    /**
     * @Route("/fakers/atf")
     */
    public function request(Request $request)
    {
        $status = $request->get('f_status', 'success');

        if ($request->get('PaRes')) {
            return $this->paymentAcs($request, $status);
        }

        switch ($request->get('TRTYPE')) {
            case self::TR_TYPE_PAY:
            case self::TR_TYPE_AUTOCLEARING:
                return $this->pay($request, $status);

            case self::TR_TYPE_CLEARING:
                return $this->clearing($request, $status);

            case self::TR_TYPE_RECURRENT:
                return $this->recurrent($request, $status);

            case self::TR_TYPE_REFUND:
                return $this->refund($request, $status);

            case self::TR_TYPE_REVERSE:
                return $this->revoke($request, $status);

            default:
                return $this->pay($request, 'error');
        }
    }

    private function pay(Request $request, string $status)
    {
        if ($status === 'success') {
            return $this->response($this->faker->paySuccess($request));
        }

        if ($status === 'process') {
            $request->request->add([
                'acs_url' => $this->generateUrl('3ds', [], UrlGeneratorInterface::ABSOLUTE_URL)
            ]);

            return $this->response($this->faker->payProcess($request));
        }

        return $this->response($this->faker->payError());
    }

    private function paymentAcs(Request $request, string $status)
    {
        if ($status === 'success') {
            return $this->response($this->faker->paymentAcsSuccess($request));
        }

        return $this->response($this->faker->paymentAcsError());
    }

    private function clearing(Request $request, string $status)
    {
        if ($status === 'success') {
            return $this->response($this->faker->clearingSuccess($request));
        }

        return $this->response($this->faker->clearingError());
    }

    private function recurrent(Request $request, string $status)
    {
        if ($status === 'success') {
            return $this->response($this->faker->recurrentSuccess($request));
        }

        return $this->response($this->faker->recurrentError());
    }

    private function refund(Request $request, string $status)
    {
        if ($status === 'success') {
            return $this->response($this->faker->refundSuccess($request));
        }

        return $this->response($this->faker->refundError());
    }

    private function revoke(Request $request, string $status)
    {
        if ($status === 'success') {
            return $this->response($this->faker->reverseSuccess($request));
        }

        return $this->response($this->faker->reverseError());
    }

    private function response(array $data)
    {
        $xml = new \SimpleXMLElement('<response/>');

        foreach ($data as $key => $value) {
            $xml->addChild($key, $value);
        }

        $response = new Response($xml->asXML());
        $response->headers->set('Content-Type', 'xml');

        return $response;
    }
}
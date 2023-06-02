<?php

namespace App\Controllers\KapitalBank;

use App\Controllers\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EcomController extends Controller
{
    /**
     * @Route("/fakers/kapitalBank/ecom")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $command = $request->get('command');

        switch ($command) {
            case 'q':
                return new Response('TRANSACTION_ID: KfglhjrnayOoo8iKU8LWjDYIiSg= RESULT: OK RESULT_CODE: 000 3DSECURE:
AUTHENTICATED RRN: 136114861366 APPROVAL_CODE: 889680 CARD_NUMBER: 553691******1689', Response::HTTP_OK);
            case 'c':
                return new Response('RESULT: OK RESULT_CODE: 000 RRN: 201205864663 APPROVAL_CODE: 412311 CARD_NUMBER:
427831******9957', Response::HTTP_OK);
            case 'r':
                return new Response('RESULT: OK RESULT_CODE: 400', Response::HTTP_OK);
            case 'k':
                return new Response('RESULT: OK RESULT_CODE: 000 REFUND_TRANS_ID: PmIZc80TnEVP1pj7P1mNnG2TJT0=', Response::HTTP_OK);
            case 'b':
                return new Response('RESULT: OK RESULT_CODE: 500 FLD_074: 28 FLD_075: 12 FLD_076: 31 FLD_077: 9 FLD_086: 8900
FLD_087: 3201 FLD_088: 10099 LD_089: 1100', Response::HTTP_OK);
            default:
                return new Response(Response::HTTP_OK);
        }
    }
}

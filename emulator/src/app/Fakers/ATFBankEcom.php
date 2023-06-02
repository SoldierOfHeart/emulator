<?php

namespace App\Fakers;

use App\Helpers\Helper;
use Symfony\Component\HttpFoundation\Request;

class ATFBankEcom
{
    public function paySuccess(Request $request)
    {
        $amount       = $request->get('AMOUNT', 0);
        $order        = $request->get('ORDER');
        $currency     = $request->get('CURRENCY', 'KZT');
        $intreference = uniqid();
        $reference    = Helper::randomInt(12);
        $approvalCode = Helper::randomInt(6);

        return [
            'status_code'   => '00',
            'message'       => '',
            'amount'        => $amount,
            'currency_code' => $currency,
            'order_id'      => $order,
            'reference'     => $reference,
            'intreference'  => $intreference,
            'approval_code' => $approvalCode,
        ];
    }

    public function payProcess(Request $request)
    {
        return [
            'acs_url' => $request->get('acs_url'),
            'PaReq'   => Helper::randomStr(1000),
            'MD'      => Helper::randomStr(30),
            'TermUrl' => 'http://localhost'
        ];
    }

    public function payError()
    {
        return [
            'status_code' => 57,
            'message'     => 'Not permitted to client',
            'reference'   => Helper::randomInt(12),
        ];
    }

    public function paymentAcsSuccess(Request $request)
    {
        return [
            'status_code'   => '00',
            'message'       => '',
            'amount'        => '10000.00',
            'currency_code' => 'KZT',
            'order_id'      => '376378004',
            'reference'     => '030190112648',
            'intreference'  => 'FB0531E9312CAA3B',
            'approval_code' => '128957',
        ];
    }

    public function paymentAcsError()
    {
        return $this->payError();
    }

    public function clearingSuccess(Request $request)
    {
        $amount       = $request->get('AMOUNT');
        $orderId      = $request->get('ORDER');
        $currency     = $request->get('CURRENCY');
        $reference    = $request->get('RRN');
        $intreference = $request->get('INT_REF');
        $approvalCode = Helper::randomInt(6);

        return [
            'status_code'   => '00',
            'message'       => '',
            'amount'        => $amount,
            'currency_code' => $currency,
            'order_id'      => $orderId,
            'reference'     => $reference,
            'intreference'  => $intreference,
            'approval_code' => $approvalCode,
        ];
    }

    public function clearingError()
    {
        return $this->payError();
    }

    public function recurrentSuccess(Request $request)
    {
        $amount       = $request->get('AMOUNT');
        $orderId      = $request->get('ORDER');
        $currency     = $request->get('CURRENCY');
        $reference    = $request->get('RRN');
        $intreference = $request->get('INT_REF');
        $approvalCode = Helper::randomInt(6);

        return [
            'status_code'   => '00',
            'message'       => '',
            'amount'        => $amount,
            'currency_code' => $currency,
            'order_id'      => $orderId,
            'reference'     => $reference,
            'intreference'  => $intreference,
            'approval_code' => $approvalCode,
        ];
    }

    public function recurrentError()
    {
        return $this->payError();
    }

    public function refundSuccess(Request $request)
    {
        $amount       = $request->get('AMOUNT');
        $orderId      = $request->get('ORDER');
        $currency     = $request->get('CURRENCY');
        $reference    = $request->get('RRN');
        $intreference = $request->get('INT_REF');
        $approvalCode = Helper::randomInt(6);

        return [
            'status_code'   => '00',
            'message'       => '',
            'amount'        => $amount,
            'currency_code' => $currency,
            'order_id'      => $orderId,
            'reference'     => $reference,
            'intreference'  => $intreference,
            'approval_code' => $approvalCode,
        ];
    }

    public function refundError()
    {
        return $this->payError();
    }

    public function reverseSuccess(Request $request)
    {
        return $this->refundSuccess($request);
    }

    public function reverseError()
    {
        return $this->refundError();
    }
}
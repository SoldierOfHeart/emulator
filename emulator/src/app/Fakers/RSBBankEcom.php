<?php

namespace App\Fakers;

use App\Helpers\Helper;

class RSBBankEcom
{
    public function acsRequest()
    {

    }

    public function createDMSPaymentSuccess()
    {
        return [
            'TRANSACTION_ID' => Helper::randomStr(28)
        ];
    }

    public function acsDispatchSuccess()
    {
        return [
            'merchantID'    => '9295879704',
            'xid'           => 'MDAwMDAwMDAwMDAzNzU2NDk0OTg',
            'mdStatus'      => '1',
            'mdErrorMsg'    => 'Authenticated',
            'txstatus'      => 'Y',
            'eci'           => '05',
            'cavv'          => 'AAABAQUQAQAAAACUBRABAAAAAAA',
            'cavvAlgorithm' => '2',
            'PAResVerified' => 'true',
            'PAResSyntaxOK' => 'true',
            'digest'        => 'FwCT6n46X2QOq21PnG0Y59ZfbCQ',
            'sID'           => '1',
        ];
    }

    public function acsDispatchError()
    {
        return [
            'merchantID'    => '9295879704',
            'xid'           => 'MDAwMDAwMDAwMDAzNzU2NDk0OTg',
            'mdStatus'      => '0',
            'mdErrorMsg'    => 'Not authenticated',
            'txstatus'      => 'N',
            'eci'           => '',
            'cavv'          => '',
            'cavvAlgorithm' => '',
            'PAResVerified' => 'true',
            'PAResSyntaxOK' => 'true',
            'digest'        => 'FwCT6n46X2QOq21PnG0Y59ZfbCQ',
            'sID'           => '1',
        ];
    }

    public function execPaymentSuccess()
    {
        return [
            'trans_id' => 'jsdP5c1tvIKCWpf2zy2bSTaVarQ',
            'Ucaf_Cardholder_Confirm' => '0',
        ];
    }
}
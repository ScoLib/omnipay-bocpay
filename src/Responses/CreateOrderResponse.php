<?php

namespace Omnipay\BOCPay\Responses;

class CreateOrderResponse extends BaseAbstractResponse
{

    /**
     * @var CreateOrderRequest
     */
    protected $request;

    /**
     * @inheritDoc
     */
    public function isSuccessful()
    {
        $data = $this->getData();

        return $data['header']['hdlSts'] == 'A' && $data['header']['bdFlg'] == 0;
    }

    public function getAbcOrderNo()
    {
        $data = $this->getData();

        return $data['body']['abcOrderNo'] ?? null;
    }
}

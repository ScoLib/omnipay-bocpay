<?php

namespace Omnipay\BOCPay\Responses;

class B2CMobileRecvOrderResponse extends BaseAbstractResponse
{

    /**
     * @var \Omnipay\BOCPay\Requests\B2CMobileRecvOrderRequest
     */
    protected $request;

    /**
     * @inheritDoc
     */
    public function isSuccessful()
    {
        $data = $this->getData();

        return $data['html'] ? true : false;
    }
}

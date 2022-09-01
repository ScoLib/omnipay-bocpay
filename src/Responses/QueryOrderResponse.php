<?php

namespace Omnipay\BOCPay\Responses;

class QueryOrderResponse extends BaseAbstractResponse
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

        return !isset($data['header']['exception']) && $data['body']['orderTrans'];
    }

    public function isPaid()
    {
        $data = $this->getData();

        return $data['body']['orderTrans'] && $data['body']['orderTrans']['orderStatus'] == 1;
    }
}

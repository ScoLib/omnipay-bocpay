<?php

namespace Omnipay\BOCPay\Responses;

class RefundOrderResponse extends BaseAbstractResponse
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

        return $data['header']['returnActFlag'] == 3 && $data['header']['bodyFlag'] == 0;
    }
}

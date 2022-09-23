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

        // returnActFlag 3：退款结果通知
        // dealStatus 0：成功
        // bodyFlag 0：有包体数据
        return $data['header']['returnActFlag'] == 3 && $data['header']['dealStatus'] == 0 && $data['header']['bodyFlag'] == 0;
    }
}

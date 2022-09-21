<?php

namespace Omnipay\BOCPay;

use Omnipay\BOCPay\Requests\B2CMobileRecvOrderRequest;

class B2CMobileGateway extends BaseAbstractGateway
{
    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'BOC B2C Mobile Gateway';
    }

    /**
     * @param array $parameters
     * @return \Omnipay\BOCPay\Requests\CreateOrderRequest
     */
    public function purchase($parameters = array())
    {
        return $this->createRequest(B2CMobileRecvOrderRequest::class, $parameters);
    }
}

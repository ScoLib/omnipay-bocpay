<?php

namespace Omnipay\BOCPay;

class WapGateway extends BaseAbstractGateway
{
    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'BOC WAP Gateway';
    }
}

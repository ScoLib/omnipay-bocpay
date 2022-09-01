<?php


namespace Omnipay\BOCPay\Responses;


class CompletePurchaseResponse extends BaseAbstractResponse
{
    /**
     * @inheritDoc
     */
    public function isSuccessful()
    {
        return $this->isPaid();
    }

    public function isPaid()
    {
        $data = $this->getData();

        return $data && $data['orderStatus'] == 1;
    }
}

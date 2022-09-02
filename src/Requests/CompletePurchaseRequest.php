<?php


namespace Omnipay\BOCPay\Requests;



use Omnipay\BOCPay\Responses\CompletePurchaseResponse;
use Omnipay\Common\Exception\InvalidRequestException;

class CompletePurchaseRequest extends BaseAbstractRequest
{
    
    protected function getSignDataStr($data)
    {
        return "";
    }

    protected function getRequestUrl()
    {
        return '';
    }

    public function getData()
    {
        $this->validateParams();

        return $this->getParams();
    }


    public function validateParams()
    {
        $this->validate('params');
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->getParameter('params');
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function setParams($value)
    {
        return $this->setParameter('params', $value);
    }

    public function sendData($data)
    {
        // merchantNo|orderNo|orderSeq|cardTyp|payTime|orderStatus|payAmount
        $signStr = "{$data['merchantNo']}|{$data['orderNo']}|{$data['orderSeq']}"
            . "|{$data['cardTyp']}|{$data['payTime']}|{$data['orderStatus']}|{$data['payAmount']}";

        $match = $this->verify($signStr, $data['signData']);

        return $this->response = new CompletePurchaseResponse($this, $data);
    }


}

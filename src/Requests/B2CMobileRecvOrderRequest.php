<?php


namespace Omnipay\BOCPay\Requests;


use Omnipay\BOCPay\Common\Helper;
use Omnipay\BOCPay\Responses\B2CMobileRecvOrderResponse;
use Omnipay\Common\Exception\InvalidRequestException;

class B2CMobileRecvOrderRequest extends CreateOrderRequest
{
    /**
     * @throws InvalidRequestException
     */
    public function validateParams()
    {
        $this->validate(
            'merchantNo',
            'payType',
            'orderNo',
            'curCode',
            'orderAmount',
            'orderTime',
            'orderNote',
            'orderUrl',
            'terminalChnl',
            'payPattern',
            'body',
            'deviceInfo'
        );
    }

    /**
     * @return mixed
     */
    public function getPayPattern()
    {
        return $this->getParameter('payPattern');
    }


    /**
     * @param mixed $payPattern
     * @return $this
     */
    public function setPayPattern($payPattern)
    {
        return $this->setParameter('payPattern', $payPattern);
    }

    protected function getRequestUrl()
    {
        return $this->getEndpoint() . '/B2CMobileRecvOrder.do';
    }

    /**
     * @param mixed $data
     * @return array|mixed|\Omnipay\BOCPay\Responses\CreateOrderResponse|\Omnipay\Common\Message\ResponseInterface
     */
    public function sendData($data)
    {
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ];

        $url = $this->getRequestUrl();
        $body = http_build_query($data);

        $response = $this->httpClient->request('POST', $url, $headers, $body)->getBody();

        if (strpos($response, '</html>')) {
            $payload = ['html' => (string)$response];
        } else {
            $payload = Helper::xml2array($response);
        }

        return $this->response = new B2CMobileRecvOrderResponse($this, $payload);
    }
}

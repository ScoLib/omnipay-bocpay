<?php


namespace Omnipay\BOCPay\Requests;


use Omnipay\BOCPay\Responses\QueryOrderResponse;
use Omnipay\Common\Exception\InvalidRequestException;

class QueryOrderRequest extends BaseAbstractRequest
{

    /**
     * @throws InvalidRequestException
     */
    public function validateParams()
    {
        $this->validate(
            'merchantNo',
            'orderNos'
        );
    }

    /**
     * @return mixed
     */
    public function getOrderNos()
    {
        return $this->getParameter('orderNos');
    }

    /**
     * 商户系统产生的订单号，支持输入多个订单号进行查询，最多支持50个订单号的查询
     * 格式：orderNo|orderNo|orderNo
     *
     * @param mixed $orderNos
     * @return $this
     */
    public function setOrderNos($orderNos)
    {
        return $this->setParameter('orderNos', $orderNos);
    }

    protected function getSignDataStr($data)
    {
        // merchantNo:orderNos
        return "{$data['merchantNo']}:{$data['orderNos']}";
    }

    protected function getRequestUrl()
    {
        return $this->getEndpoint() . '/QueryOrder.do';
    }

    /**
     *
     * @param mixed $data
     * @return \Omnipay\BOCPay\Responses\QueryOrderResponse
     */
    public function sendData($data)
    {
        $payload  = parent::sendData($data);

        return $this->response = new QueryOrderResponse($this, $payload);
    }
}

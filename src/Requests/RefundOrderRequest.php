<?php


namespace Omnipay\BOCPay\Requests;


use Omnipay\BOCPay\Responses\QueryOrderResponse;
use Omnipay\BOCPay\Responses\RefundOrderResponse;
use Omnipay\Common\Exception\InvalidRequestException;

class RefundOrderRequest extends BaseAbstractRequest
{

    /**
     * @throws InvalidRequestException
     */
    public function validateParams()
    {
        $this->validate(
            'merchantNo',
            'mRefundSeq',
            'orderNo',
            'curCode',
            'refundAmount'
        );
    }

    /**
     * @return mixed
     */
    public function getMRefundSeq()
    {
        return $this->getParameter('mRefundSeq');
    }

    /**
     * 商户系统产生的交易流水号，格式要求为：商户号（merchantNo）后6位+商户按自己规则生成的退款交易流水号；
     * eg：221338123456789
     *
     * @param mixed $mRefundSeq
     * @return $this
     */
    public function setMRefundSeq($mRefundSeq)
    {
        return $this->setParameter('mRefundSeq', $mRefundSeq);
    }

    /**
     * @return mixed
     */
    public function getOrderNo()
    {
        return $this->getParameter('orderNo');
    }

    /**
     * 商户系统产生的订单号，原支付订单的商户订单号
     *
     * @param mixed $orderNo
     * @return $this
     */
    public function setOrderNo($orderNo)
    {
        return $this->setParameter('orderNo', $orderNo);
    }

    /**
     * @return mixed
     */
    public function getCurCode()
    {
        return $this->getParameter('curCode');
    }

    /**
     * 订单币种 固定填：001（人民币）
     *
     * @param mixed $curCode
     * @return $this
     */
    public function setCurCode($curCode)
    {
        return $this->setParameter('curCode', $curCode);
    }

    /**
     * @return mixed
     */
    public function getRefundAmount()
    {
        return $this->getParameter('refundAmount');
    }

    /**
     * 退款金额
     * 格式：整数位不前补零,小数位补齐2位
     * 即：不超过10位整数位+1位小数点+2位小数
     *
     * 无效格式如123，.10，1.1,有效格式如1.00，0.10
     *
     * @param mixed $refundAmount
     * @return $this
     */
    public function setRefundAmount($refundAmount)
    {
        return $this->setParameter('refundAmount', $refundAmount);
    }

    protected function getSignDataStr($data)
    {
        // merchantNo|mRefundSeq|curCode|refundAmount|orderNo
        return "{$data['merchantNo']}|{$data['mRefundSeq']}|{$data['curCode']}|{$data['refundAmount']}|{$data['orderNo']}";
    }

    protected function getRequestUrl()
    {
        return $this->getEndpoint() . '/RefundOrder.do';
    }

    /**
     *
     * @param mixed $data
     * @return \Omnipay\BOCPay\Responses\RefundOrderResponse
     */
    public function sendData($data)
    {
        $payload  = parent::sendData($data);

        return $this->response = new RefundOrderResponse($this, $payload);
    }
}

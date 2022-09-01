<?php


namespace Omnipay\BOCPay\Requests;


use Omnipay\BOCPay\Responses\CreateOrderResponse;
use Omnipay\Common\Exception\InvalidRequestException;

class CreateOrderRequest extends BaseAbstractRequest
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
            'tradeType',
            'body',
            'deviceInfo'
        );
    }

    /**
     * @return mixed
     */
    public function getPayType()
    {
        return $this->getParameter('payType');
    }

    /**
     * 商户支付服务类型 1：网上购物

     * @param mixed $payType
     *
     * @return $this
     */
    public function setPayType($payType)
    {
        return $this->setParameter('payType', $payType);
    }

    /**
     * @return mixed
     */
    public function getOrderNo()
    {
        return $this->getParameter('orderNo');
    }

    /**
     * 商户系统产生的订单号，格式要求为：
     * 商户号（merchantNo）后6位+商户按自己规则生成的订单号（仅支持字母或数字）；
     * eg：221338123456789
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
    public function getOrderAmount()
    {
        return $this->getParameter('orderAmount');
    }


    /**
     * 订单金额 格式：整数位不前补零,小数位补齐2位
     * 即：不超过10位整数位+1位小数点+2位小数
     * 无效格式如123，.10，1.1,有效格式如1.00，0.10
     *
     * @param mixed $orderAmount
     * @return $this
     */
    public function setOrderAmount($orderAmount)
    {
        return $this->setParameter('orderAmount', $orderAmount);
    }

    /**
     * @return mixed
     */
    public function getOrderTime()
    {
        return $this->getParameter('orderTime');
    }


    /**
     * 订单时间 格式：YYYYMMDDHHMISS
     * 其中时间为24小时格式，例:2010年3月2日下午4点5分28秒表示为20100302160528
     *
     * @param mixed $orderTime
     * @return $this
     */
    public function setOrderTime($orderTime)
    {
        return $this->setParameter('orderTime', $orderTime);
    }

    /**
     * @return mixed
     */
    public function getOrderNote()
    {
        return $this->getParameter('orderNote');
    }


    /**
     * 订单说明
     * 订单描述，要求如果全中文最多允许60个汉字长度
     * @param $orderNote
     * @return $this
     */
    public function setOrderNote($orderNote)
    {
        return $this->setParameter('orderNote', $orderNote);
    }

    /**
     * @return mixed
     */
    public function getOrderUrl()
    {
        return $this->getParameter('orderUrl');
    }


    /**
     * 商户接收通知URL
     *
     * 用户在银行APP/H5支付完成后，跳转回商户的地址。
     *
     * @param mixed $orderUrl
     * @return $this
     */
    public function setOrderUrl($orderUrl)
    {
        return $this->setParameter('orderUrl', $orderUrl);
    }

    /**
     * @return mixed
     */
    public function getOrderTimeoutDate()
    {
        return $this->getParameter('orderTimeoutDate');
    }


    /**
     * 订单超时时间 格式：YYYYMMDDHHMISS
     * 其中时间为24小时格式，例:2010年3月2日下午4点5分28秒表示为20100302160528
     *
     * @param mixed $orderTimeoutDate
     * @return $this
     */
    public function setOrderTimeoutDate($orderTimeoutDate)
    {
        return $this->setParameter('orderTimeoutDate', $orderTimeoutDate);
    }

    /**
     * @return mixed
     */
    public function getTerminalChnl()
    {
        return $this->getParameter('terminalChnl');
    }

    /**
     * 交易终端类型 08： 手机
     *
     * @param mixed $terminalChnl
     * @return $this
     */
    public function setTerminalChnl($terminalChnl)
    {
        return $this->setParameter('terminalChnl', $terminalChnl);
    }

    /**
     * @return mixed
     */
    public function getTradeType()
    {
        return $this->getParameter('tradeType');
    }


    /**
     * @param mixed $tradeType
     * @return $this
     */
    public function setTradeType($tradeType)
    {
        return $this->setParameter('tradeType', $tradeType);
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->getParameter('body');
    }

    /**
     * @param mixed $body
     * @return $this
     */
    public function setBody($body)
    {
        return $this->setParameter('body', $body);
    }

    /**
     * @return mixed
     */
    public function getDeviceInfo()
    {
        return $this->getParameter('deviceInfo');
    }


    /**
     * 终端设备号，商户自定义，如门店编号

     * @param mixed $deviceInfo
     *
     * @return $this
     */
    public function setDeviceInfo($deviceInfo)
    {
        return $this->setParameter('deviceInfo', $deviceInfo);
    }

    protected function getSignDataStr($data)
    {
        // orderNo|orderTime|curCode|orderAmount|merchantNo
        return "{$data['orderNo']}|{$data['orderTime']}|{$data['curCode']}|{$data['orderAmount']}|{$data['merchantNo']}";
    }

    protected function getRequestUrl()
    {
        return $this->getEndpoint() . '/B2CRecvOrder.do';
    }

    /**
     * @param mixed $data
     * @return array|mixed|\Omnipay\BOCPay\Responses\CreateOrderResponse|\Omnipay\Common\Message\ResponseInterface
     */
    public function sendData($data)
    {
        $payload  = parent::sendData($data);

        return $this->response = new CreateOrderResponse($this, $payload);
    }
}

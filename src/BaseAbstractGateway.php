<?php

namespace Omnipay\BOCPay;

use Omnipay\BOCPay\Requests\CompletePurchaseRequest;
use Omnipay\BOCPay\Requests\CreateOrderRequest;
use Omnipay\BOCPay\Requests\QueryOrderRequest;
use Omnipay\BOCPay\Requests\RefundOrderRequest;
use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;

abstract class BaseAbstractGateway extends AbstractGateway
{
    protected $endpoints = [
        'production' => 'https://ebspay.boc.cn/PGWPortal',
        'test'       => 'https://101.231.206.170:443/PGWPortal',
    ];

    /**
     * @return mixed
     */
    public function getMerchantNo()
    {
        return $this->getParameter('merchantNo');
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function setMerchantNo($value)
    {
        return $this->setParameter('merchantNo', $value);
    }

    /**
     * @return mixed
     */
    public function getCertPath()
    {
        return $this->getParameter('cert_path');
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function setCertPath($value)
    {
        return $this->setParameter('cert_path', $value);
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function setCertPassword($value)
    {
        return $this->setParameter('cert_password', $value);
    }

    /**
     * @return mixed
     */
    public function getCertPassword()
    {
        return $this->getParameter('cert_password');
    }

    /**
     * @return mixed
     */
    public function getJavaPath()
    {
        return $this->getParameter('java_path');
    }

    /**
     * @param $path
     *
     * @return $this
     */
    public function setJavaPath($path)
    {
        return $this->setParameter('java_path', $path);
    }

    /**
     * @return mixed
     */
    public function getPkcs7JarPath()
    {
        return $this->getParameter('PKCS7_jar_path');
    }

    /**
     * @param $path
     *
     * @return $this
     */
    public function setPkcs7JarPath($path)
    {
        return $this->setParameter('PKCS7_jar_path', $path);
    }

    /**
     * @return mixed
     */
    public function getPrivateKeyPath()
    {
        return $this->getParameter('private_key_path');
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function setPrivateKeyPath($value)
    {
        return $this->setParameter('private_key_path', $value);
    }

    /**
     * @return $this
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function production()
    {
        return $this->setEnvironment('production');
    }


    /**
     * @param $value
     *
     * @return $this
     * @throws InvalidRequestException
     */
    public function setEnvironment($value)
    {
        $env = strtolower($value);

        if (!isset($this->endpoints[$env])) {
            throw new InvalidRequestException('The environment is invalid');
        }

        $this->setEndpoint($this->endpoints[$env]);

        return $this;
    }


    /**
     * @param $value
     *
     * @return $this
     */
    public function setEndpoint($value)
    {
        return $this->setParameter('endpoint', $value);
    }


    /**
     * @return $this
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function test()
    {
        return $this->setEnvironment('test');
    }

    /**
     * @param array $parameters
     * @return \Omnipay\BOCPay\Requests\CreateOrderRequest
     */
    public function purchase($parameters = array())
    {
        return $this->createRequest(CreateOrderRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     * @return \Omnipay\BOCPay\Requests\CompletePurchaseRequest
     */
    public function completePurchase(array $parameters = [])
    {
        return $this->createRequest(CompletePurchaseRequest::class, $parameters);
    }

    /**
     *
     * @param array $parameters
     *
     * @return \Omnipay\BOCPay\Requests\QueryOrderRequest
     */
    public function query($parameters = array())
    {
        return $this->createRequest(QueryOrderRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     *
     * @return \Omnipay\BOCPay\Requests\RefundOrderRequest
     */
    public function refund($parameters = array())
    {
        return $this->createRequest(RefundOrderRequest::class, $parameters);
    }
}

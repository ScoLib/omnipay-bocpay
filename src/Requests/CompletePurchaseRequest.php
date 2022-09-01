<?php


namespace Omnipay\BOCPay\Requests;



use Omnipay\BOCPay\Responses\CompletePurchaseResponse;
use Omnipay\Common\Exception\InvalidRequestException;

class CompletePurchaseRequest extends BaseAbstractRequest
{

    private $verifyCertPath;

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


    /**
     * @return mixed
     */
    public function getVerifyCertPath()
    {
        return $this->verifyCertPath;
    }

    /**
     * @param $path
     * @return $this
     */
    public function setVerifyCertPath($path)
    {
        $this->verifyCertPath = $path;

        return $this;
    }

    public function sendData($data)
    {
        // merchantNo|orderNo|orderSeq|cardTyp|payTime|orderStatus|payAmount
        $signStr = "{$data['merchantNo']}|{$data['orderNo']}|{$data['orderSeq']}"
            . "|{$data['cardTyp']}|{$data['payTime']}|{$data['orderStatus']}|{$data['payAmount']}";

        $match = $this->verify($signStr, $data['signData']);

        if (! $match) {
            throw new InvalidRequestException('The signature is not match');
        }

        return $this->response = new CompletePurchaseResponse($this, $data);
    }

    protected function verify($signStr, $sign)
    {
        $sourceFile = $this->getTmpFile('source');
        $targetFile = $this->getTmpFile('target');

        file_put_contents($sourceFile, $sign);
        file_put_contents($targetFile, $signStr);

        $process = $this->createProcess(
            'com.bocnet.common.security.P7Verify',
            $this->getVerifyCertPath(),
            $sourceFile,
            $targetFile
        );
        $process->start()->join()->stop();

        $res = strpos($process->getStdout(), 'VERIFY OK') !== false;
        @unlink($sourceFile);
        @unlink($targetFile);

        return $res;
    }
}

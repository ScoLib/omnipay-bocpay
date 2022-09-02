<?php


namespace Omnipay\BOCPay\Requests;

use Omnipay\BOCPay\Common\Helper;
use Omnipay\BOCPay\Common\Process;
use Omnipay\Common\Message\AbstractRequest;

abstract class BaseAbstractRequest extends AbstractRequest
{
    private $certPath;
    private $certPassword;
    private $verifyCertPath;

    protected $endpoint = 'https://ebspay.boc.cn/PGWPortal';
    private   $javaPath = 'java';

    private   $PKCS7JarPath;

    abstract protected function getSignDataStr($data);
    abstract protected function getRequestUrl();

    /**
     * @return array|mixed
     * @throws \Exception
     */
    public function getData()
    {
        $this->validateParams();

        $data = $this->parameters->all();

        $data['signData'] = $this->sign($this->getSignDataStr($data));

        return $data;
    }

    protected function getTmpFile($prefix)
    {
        return tempnam(sys_get_temp_dir(), $prefix);
    }

    protected function sign($str)
    {
        $sourceFile = $this->getTmpFile('source');
        $targetFile = $this->getTmpFile('target');

        if (!($sourceFile && $targetFile)) {
            throw new \Exception('临时文件写入失败');
        }

        file_put_contents($sourceFile, $str);

        $process = $this->createProcess(
            'com.bocnet.common.security.P7Sign',
            $this->getCertPath(),
            $this->getCertPassword(),
            $sourceFile,
            $targetFile
        );
        $process->start()->join()->stop();

        $signData = file_get_contents($targetFile);
        @unlink($sourceFile);
        @unlink($targetFile);

        if (!$signData) {
            throw new \Exception(
                "加签失败，请检查证书配置\n"
                . $process->getCommand() . "\n"
                . json_encode($process->getOutput(), JSON_UNESCAPED_UNICODE)
            );
        }

        return $signData;
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

        if (!$res) {
            throw new \Exception(
                "验签失败，请检查证书配置\n"
                . $process->getCommand() . "\n"
                . json_encode($process->getOutput(), JSON_UNESCAPED_UNICODE)
            );
        }
        return $res;
    }

    protected function createProcess($package, $args)
    {
        $pkcs7JarPath = $this->getPKCS7JarPath() ?: dirname(__DIR__) . '/Common/pkcs7.jar';
        $args = [
            $this->getJavaPath(),
            '-classpath',
            $pkcs7JarPath,
            implode(' ', func_get_args()),
        ];

        return Process::factory(implode(' ', $args));
    }

    /**
     * @param mixed $data
     * @return array|mixed|\Omnipay\Common\Message\ResponseInterface
     */
    public function sendData($data)
    {
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ];

        $url = $this->getRequestUrl();
        $body = http_build_query($data);

        $response = $this->httpClient->request('POST', $url, $headers, $body)->getBody();
        $payload  = Helper::xml2array($response);

        return $payload;
    }

    /**
     * @return mixed
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function setEndpoint($value)
    {
        $this->endpoint = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCertPath()
    {
        return $this->certPath;
    }

    /**
     * @param $path
     *
     * @return $this
     */
    public function setCertPath($path)
    {
        $this->certPath = $path;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getJavaPath()
    {
        return $this->javaPath;
    }

    /**
     * @param $path
     *
     * @return $this
     */
    public function setJavaPath($path)
    {
        $this->javaPath = $path;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPkcs7JarPath()
    {
        return $this->PKCS7JarPath;
    }

    /**
     * @param $path
     *
     * @return $this
     */
    public function setPkcs7JarPath($path)
    {
        $this->PKCS7JarPath = $path;

        return $this;
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function setCertPassword($value)
    {
        $this->certPassword = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCertPassword()
    {
        return $this->certPassword;
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

}

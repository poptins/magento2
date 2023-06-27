<?php

namespace Poptin\Magento2\Api;

use Magento\Framework\HTTP\Adapter\CurlFactory;
use Psr\Log\LoggerInterface;

class Poptinapi
{

    private const POPTIN_REGISTER_API_URL = 'https://app.popt.in/api/marketplace/register';
    private const POPTIN_AUTH_API_URL = 'https://app.popt.in/api/marketplace/auth';

    /**
     * @var \Magento\Framework\HTTP\Adapter\CurlFactory
     */
    protected $curlFactory;
    protected $_remoteAddress;

    /**
     * @var LoggerInterface
     */
    protected $log;

    /**
     * Api constructor.
     * @param CurlFactory $curlFactory
     * @param LoggerInterface $log
     */
    public function __construct(

        CurlFactory $curlFactory,
        \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress $remoteAddress,
        LoggerInterface $log
    ) {
    

        $this->curlFactory = $curlFactory;
        $this->_remoteAddress = $remoteAddress;
        $this->log = $log;
    }

    public function createAccount($userEmail)
    {
        try {
            $client = $this->curlFactory->create();

            $url = self::POPTIN_REGISTER_API_URL;
            $headers = [
                "Cache-Control: no-cache",
                "Content-Type: application/x-www-form-urlencoded"];
            $body = http_build_query(
                [
                    'email' => $userEmail,
                'marketplace' => 'Mgnto2']
            );

            // $client->write(\Zend_Http_Client::POST, $url, '1.1', $headers, $body);
            $client->write(\Laminas\Http\Request::METHOD_POST, $url, '1.1', $headers, $body);

            $response = $client->read();
            $client->close();

            // Parse response
            // $responseBody = \Zend_Http_Response::extractBody($response);
            $responseBody = \Laminas\Http\Response::extractBody($response);

            // $responseCode = \Zend_Http_Response::extractCode($response);
            $responseCode = \Laminas\Http\Response::extractCode($response);


            $apiResult = $this->jsonHelperUnserialize($responseBody);
            return $apiResult;
        } catch (\Exception $e) {
            $this->log->error($e->getMessage());
            return false;
        }
    }


    public function authorizeAccount($token, $user_id)
    {
        try {
            $client = $this->curlFactory->create();

            $url = self::POPTIN_AUTH_API_URL;
            $headers = [
                "Cache-Control: no-cache",
                "Content-Type: application/x-www-form-urlencoded"];
            $body = http_build_query(['token' => $token, 'user_id' => $user_id]);

            // $client->write(\Zend_Http_Client::POST, $url, '1.1', $headers, $body);
            $client->write(\Laminas\Http\Request::METHOD_POST, $url, '1.1', $headers, $body);
            $response = $client->read();
            $client->close();

            // Parse response
            // $responseBody = \Zend_Http_Response::extractBody($response);
            $responseBody = \Laminas\Http\Response::extractBody($response);

            // $responseCode = \Zend_Http_Response::extractCode($response);
            $responseCode = \Laminas\Http\Response::extractCode($response);



            $apiResult = $this->jsonHelperUnserialize($responseBody);
            return $apiResult;
        } catch (\Exception $e) {
            $this->log->error($e->getMessage());
            return false;
        }
    }


    /**
     * @param mixed $data
     * @return string
     */
    private function jsonHelperUnserialize($data)
    {
        if (class_exists(\Magento\Framework\Serialize\Serializer\Json::class)) {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $jsonHelper = $objectManager->create(\Magento\Framework\Serialize\Serializer\Json::class);

            return $jsonHelper->unserialize($data);
        }
        return \json_decode($data, true);
    }
}

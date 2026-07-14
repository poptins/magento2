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

            // Use plain HTTP method string to avoid Laminas class coupling on Magento 2.4.9+
            $client->write('POST', $url, '1.1', $headers, $body);

            $response = $client->read();
            $client->close();

            $responseBody = $this->extractResponseBody($response);
            if ($responseBody === '') {
                $this->log->error('Poptin register API returned an empty or unparseable response.');
                return false;
            }

            $apiResult = $this->jsonHelperUnserialize($responseBody);
            return is_array($apiResult) ? $apiResult : false;
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

            // Use plain HTTP method string to avoid Laminas class coupling on Magento 2.4.9+
            $client->write('POST', $url, '1.1', $headers, $body);
            $response = $client->read();
            $client->close();

            $responseBody = $this->extractResponseBody($response);
            if ($responseBody === '') {
                $this->log->error('Poptin auth API returned an empty or unparseable response.');
                return false;
            }

            $apiResult = $this->jsonHelperUnserialize($responseBody);
            return is_array($apiResult) ? $apiResult : false;
        } catch (\Exception $e) {
            $this->log->error($e->getMessage());
            return false;
        }
    }

    /**
     * Split raw HTTP response into body safely (supports intermediate headers / LF-only separators).
     *
     * @param string|false $response
     * @return string
     */
    private function extractResponseBody($response)
    {
        if (!is_string($response) || $response === '') {
            return '';
        }

        $parts = preg_split("/\r\n\r\n|\n\n/", $response);
        if (!is_array($parts) || $parts === []) {
            return '';
        }

        $body = end($parts);
        return is_string($body) ? trim($body) : '';
    }

    /**
     * @param mixed $data
     * @return array|bool|float|int|mixed|string|null
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

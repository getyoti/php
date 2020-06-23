<?php

declare(strict_types=1);

namespace Yoti\DocScan\Exception;

use Psr\Http\Message\ResponseInterface;
use Yoti\Util\Json;

class DocScanException extends \Exception
{
    /**
     * @var ResponseInterface|null
     */
    private $response;

    /**
     * DocScanException constructor.
     * @param string $message
     * @param ResponseInterface|null $response
     * @param \Throwable|null $previous
     */
    public function __construct($message = "", ?ResponseInterface $response = null, \Throwable $previous = null)
    {
        parent::__construct($this->formatMessage($message, $response), 0, $previous);

        $this->response = $response;
    }

    /**
     * Returns the HTTP response object returned
     * from the Doc Scan API.
     *
     * @return ResponseInterface|null
     */
    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }

    /**
     * @param string $message
     * @param ResponseInterface|null $response
     *
     * @return string
     */
    private function formatMessage(string $message, ?ResponseInterface $response): string
    {
        if (
            $response === null ||
            !$response->hasHeader('Content-Type') ||
            $response->getHeader('Content-Type')[0] !== 'application/json'
        ) {
            return $message;
        }

        $jsonData = Json::decode((string) $response->getBody(), false);
        $formattedResponse = $this->formatResponse($jsonData);
        if ($formattedResponse !== null) {
            return sprintf('%s - %s', $message, $formattedResponse);
        }

        return $message;
    }

    /**
     * @param \stdClass $jsonData
     *
     * @return string|null
     */
    private function formatResponse(\stdClass $jsonData): ?string
    {
        if (!isset($jsonData->message) || !isset($jsonData->code)) {
            return null;
        }

        $responseMessage = sprintf('%s - %s', $jsonData->code, $jsonData->message);

        $propertyErrors = $this->formatPropertyErrors($jsonData);
        if (count($propertyErrors) > 0) {
            return sprintf('%s: %s', $responseMessage, implode(', ', $propertyErrors));
        }

        return $responseMessage;
    }

    /**
     * @param \stdClass $jsonData
     *
     * @return string[]
     */
    private function formatPropertyErrors(\stdClass $jsonData): array
    {
        if (!isset($jsonData->errors) || !is_array($jsonData->errors)) {
            return [];
        }

        return array_filter(array_map(
            function ($error): ?string {
                if (isset($error->property) && isset($error->message)) {
                    return sprintf('%s "%s"', $error->property, $error->message);
                }
                return null;
            },
            $jsonData->errors
        ));
    }
}

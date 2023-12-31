<?php

declare(strict_types=1);

namespace Quantum\Kernel\Http;

use InvalidArgumentException;

use function array_keys;
use function in_array;

/**
 * Abstraction layer for an HTTP Response.
 */
class Response
{
    use RequestTargetTrait;

    private const PHRASES = [

        // Informational
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        103 => 'Checkpoint',

        // Successful
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',

        // Redirection
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Switch Proxy',
        307 => 'Temporary Redirect',

        // Client Error
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        449 => 'Retry With',
        450 => 'Blocked by Windows Parental Controls',

        // Server Error
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        509 => 'Bandwidth Limit Exceeded',
        510 => 'Not Extended'
    ];

    private int $statusCode;
    private string $reasonPhrase;
    private string $contentType = 'text/html';
    private ?string $redirect = null;
    private string $body = '';

    public function __construct(int $statusCode = 200)
    {
        $this->validateStatusCode($statusCode);

        $this->statusCode = $statusCode;
        $this->reasonPhrase = self::PHRASES[$statusCode];
    }

    /**
     * Throws an exception if the status code is not valid.
     */
    private function validateStatusCode(int $statusCode): void
    {
        if (!in_array($statusCode, array_keys(self::PHRASES))) {
            throw new InvalidArgumentException(
                'Invalid status code ' . $statusCode . '.'
            );
        }
    }

    /**
     * Returns the status code of the response.
     */
    final public function getStatusCode(): int {
        return $this->statusCode;
    }

    /**
     * Returns the reason phrase of the response.
     */
    final public function getReasonPhrase(): string {
        return $this->reasonPhrase;
    }

    /**
     * Get the response content type.
     */
    final public function getContentType(): string {
        return $this->contentType;
    }

    /**
     * Get the redirect, if it was defined. Otherwise, return null.
     */
    final public function getRedirect(): ?string {
        return $this->redirect;
    }

    /**
     * Returns the request body.
     */
    final public function getBody(): string {
        return $this->body;
    }

    /**
     * Save the content type. By default, this will be text/html.
     */
    final public function setContentType(string $contentType): void {
        $this->contentType = $contentType;
    }

    /**
     * Set the redirect target for the response.
     */
    final public function setRedirect(string $redirect): void {
        $this->redirect = $this->normalizeRequestTarget($redirect);
    }

    /**
     * Add a new request body to the object.
     */
    final public function setBody(string $body): void {
        $this->body = $body;
    }
}
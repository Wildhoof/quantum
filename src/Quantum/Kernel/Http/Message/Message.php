<?php

declare(strict_types=1);

namespace Quantum\Kernel\Http\Message;

use function array_merge;
use function implode;
use function is_array;
use function strtolower;

/**
 * Representation of an HTTP Message. HTTP messages consist of requests from a
 * client to a server and responses from a server to a client. This class
 * defines methods common to both of these.
 */
abstract class Message
{
    private string $protocol = '1.1';
    protected array $headers = [];
    protected array $headerNames = [];
    protected Body $body;

    /**
     * Returns the HTTP protocol version number as a string.
     */
    public function getProtocolVersion(): string {
        return $this->protocol;
    }

    /**
     * Return an instance with the specified HTTP protocol version.
     */
    public function withProtocolVersion(string $version): static
    {
        if ($this->protocol === $version) {
            return $this;
        }

        $clone = clone $this;
        $clone->protocol = $version;
        return $clone;
    }

    /**
     * Returns the entire header array.
     */
    public function getHeaders(): array {
        return $this->headers;
    }

    /**
     * Returns the values of a single header.
     */
    public function getHeader(string $header): mixed
    {
        // Normalize header to lower case letters.
        $header = strtolower($header);

        // Make sure an empty array is returned even when there is no header.
        if (!isset($this->headerNames[$header])) {
            return [];
        }

        $header = $this->headerNames[$header];
        return $this->headers[$header];
    }

    /**
     * Returns a single header line as a string.
     */
    public function getHeaderLine(string $header): string {
        return implode(', ', $this->getHeader($header));
    }

    /**
     * Returns whether a header with a given name has been set.
     */
    public function hasHeader(string $header): bool {
        return isset($this->headerNames[strtolower($header)]);
    }

    /**
     * Return an instance of a message with the provided value replacing the
     * specified header.
     */
    public function withHeader(string $header, mixed $value): static
    {
        // Normalize header to lower case letters.
        $normalized = strtolower($header);

        $clone = clone $this;

        if (isset($clone->headerNames[$normalized])) {
            unset($clone->headers[$clone->headerNames[$normalized]]);
        }

        $clone->headerNames[$normalized] = $header;
        $clone->headers[$header] = !is_array($value) ? [$value] : $value;

        return $clone;
    }

    /**
     * Return an instance of a message with the specified header appended with
     * the given value.
     */
    public function withAddedHeader(string $header, mixed $value): static
    {
        // Normalize header to lower case letters.
        $normalized = strtolower($header);

        $clone = clone $this;

        if (isset($clone->headerNames[$normalized])) {
            $header = $this->headerNames[$normalized];
            $new = array_merge($this->headers[$header], $value);
            $clone->headers[$header] = $new;
        } else {
            $clone->headerNames[$normalized] = $header;
            $clone->headers[$header] = $value;
        }

        return $clone;
    }

    /**
     * Return an instance of a message without the specified header.
     */
    public function withoutHeader(string $header): static
    {
        // Normalize header to lower case letters.
        $normalized = strtolower($header);

        // Only create new Message if header can be removed.
        if (!isset($this->headerNames[$normalized])) {
            return $this;
        }

        $header = $this->headerNames[$normalized];

        $clone = clone $this;
        unset($clone->headers[$header], $clone->headerNames[$normalized]);
        return $clone;
    }

    /**
     * Return the Body object containing the message body.
     */
    public function getBody(): Body {
        return $this->body;
    }

    /**
     * Return an instance with the specified message body.
     */
    public function withBody(Body $body): static
    {
        if (isset($this->body)) {
            if ($body === $this->body) {
                return $this;
            }
        }

        $clone = clone $this;
        $clone->body = $body;
        return $clone;
    }
}
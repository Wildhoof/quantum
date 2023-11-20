<?php

declare(strict_types=1);

namespace Quantum\Kernel\Http\Message;

use function strlen;
use function substr;

use const SEEK_CUR;
use const SEEK_END;
use const SEEK_SET;

/**
 * Minimal string based stream.
 */
class Body
{
    private ?string $data;
    private int $pointer = 0;
    private int $size;

    public function __construct(string $data = '')
    {
        $this->data = $data;
        $this->size = strlen($data);
    }

    /**
     * Reads all data from the stream into a string, from the beginning to end.
     */
    public function __toString(): string {
        return $this->data;
    }

    /**
     * Get the size of the stream if known.
     */
    public function getSize(): int {
        return $this->size;
    }

    /**
     * Returns the current position of the pointer.
     */
    public function tell(): int {
        return $this->pointer;
    }

    /**
     * Returns whether the pointer is at the end of the stream.
     */
    public function eof(): bool {
        return $this->pointer >= $this->size;
    }

    /**
     * Seek to the beginning of the stream.
     */
    public function rewind(): void {
        $this->pointer = 0;
    }

    /**
     * Seek to a position in the stream.
     */
    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        switch ($whence) {
            case SEEK_SET:
                $this->pointer = $offset;
                return;
            case SEEK_CUR:
                $this->pointer = $this->pointer + $offset;
                return;
            case SEEK_END:
                $this->pointer = $this->size + $offset;
                return;
        }
    }

    /**
     * Write data to the stream.
     */
    public function write(string $string): int
    {
        $length = strlen($string);

        // If we are at the end of the file, just append.
        if ($this->eof()) {
            $this->size = $this->size + $length;
            $this->data = $this->data . $string;
            $this->pointer = $this->size;

            return $length;
        }

        // Otherwise overwrite data.
        $this->data = substr($this->data, 0, $this->pointer) .
            $string . substr($this->data, $this->pointer + $length);

        // Recalculate pointer and size.
        $this->size = strlen($this->data);
        $this->pointer = $this->size;

        return $length;
    }

    /**
     * Read data from the stream.
     */
    public function read(int $length): string
    {
        $data = substr($this->data, $this->pointer, $length);
        $this->pointer = $this->pointer + $length;
        return $data;
    }

    /**
     * Returns the remaining contents in the string.
     */
    public function getContents(): string {
        return $this->read($this->size - $this->pointer);
    }

    /**
     * Closes the stream and all the underlying resources.
     */
    public function close(): void
    {
        $this->data = null;
        $this->pointer = 0;
        $this->size  = 0;
    }
}
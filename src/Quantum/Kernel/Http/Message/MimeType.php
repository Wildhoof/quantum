<?php

declare(strict_types=1);

namespace Quantum\Kernel\Http\Message;

/**
 * Enum containing all supported response mime types.
 */
enum MimeType: string
{
    case CSS = 'text/css';
    case HTML = 'text/html';
    case JS = 'text/javascript';
    case JSON = 'application/json';
    case SVG = 'image/svg+xml';
    case TEXT = 'text/plain';
    case XML = 'text/xml';
}
<?php

declare(strict_types=1);

namespace Quantum;

use LogicException;
use RuntimeException;

use function array_key_exists;
use function array_merge;
use function array_replace;
use function extract;
use function file_exists;
use function ob_get_clean;
use function ob_start;
use function rtrim;
use function sprintf;

/**
 * Template rendering engine using plain php as a template language.
 */
class View
{
    private string $path;
    private array $data = [];

    protected array $sections = [];
    protected ?string $layout;
    protected ?string $section;

    public function __construct(string $path)
    {
        // Normalizes the path to the template files
        $this->path = rtrim($path, '/') . '/';
    }

    /**
     * Adds parameters in the format ['name' => 'Value'] to the
     * internal data array.
     */
    public function addData(array $data): void {
        $this->data = array_merge($this->data, $data);
    }

    /**
     * Combines the path to the template files and the filename.
     */
    private function resolveTemplatePath(string $file): string {
        return $this->path . $file . '.phtml';
    }

    /**
     * Renders a template file and returns the rendered result as a string.
     */
    public function render(string $file): string
    {
        ob_start();
        $this->includeFile($file, $this->data);
        $content = ob_get_clean();

        if (isset($this->layout)) {
            $layout = new View($this->path);
            $layout->sections = array_merge($this->sections, ['content' => $content]);
            $layout->addData($this->data);
            return $layout->render($this->layout);
        }

        return $content;
    }

    /**
     * Tries to include a template source file.
     */
    protected function includeFile(string $file, array $data = []): void
    {
        extract($data);

        if (!file_exists($this->resolveTemplatePath($file))) {
            $message = sprintf('Template file "%s" not found', $file);
            throw new RuntimeException($message);
        }

        include $this->resolveTemplatePath($file);
    }

    /**
     * Insert one template file into another template file.
     */
    protected function insert(string $file, array $data = []): void
    {
        $mergedData = array_replace($this->data, $data);
        $this->includeFile($file, $mergedData);
    }

    /**
     * Set a layout for a template.
     */
    public function layout(string $name): void {
        $this->layout = $name;
    }

    /**
     * Start a section to be displayed in a layout file.
     */
    public function start(string $name): void
    {
        if ($name === 'content') {
            throw new LogicException(
                'The section name content is reserved.'
            );
        }

        if (!empty($this->section)) {
            throw new LogicException(
                'You cannot nest sections within other sections.'
            );
        }

        $this->section = $name;
        ob_start();
    }

    /**
     * End a custom section.
     */
    public function end(string $name): void
    {
        if (empty($this->section)) {
            throw new LogicException(
                'You must start a section before you can stop it.'
            );
        }

        $this->sections[$this->section] = ob_get_clean();
        $this->section = null;
    }

    /**
     * Displays a custom section in a layout file.
     */
    public function section(string $name): void
    {
        if (array_key_exists($name, $this->sections)) {
            echo $this->sections[$name];
        }
    }
}
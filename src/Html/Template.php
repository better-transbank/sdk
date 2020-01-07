<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK\Html;

use RuntimeException;

/**
 * Class Template.
 */
abstract class Template
{
    private const DEFAULT_PATH = __DIR__.'/../../template';

    /**
     * @var string
     */
    private $templatePath;

    /**
     * Template constructor.
     *
     * @param string $templatePath
     */
    public function __construct(string $templatePath = self::DEFAULT_PATH)
    {
        $this->templatePath = $templatePath;
    }

    /**
     * @param string $templateName
     * @param array  $data
     *
     * @return string
     */
    protected function render(string $templateName, array $data = []): string
    {
        if (false === strpos($templateName, '.php')) {
            $templateName .= '.php';
        }
        $filename = sprintf('%s%s%s', $this->templatePath, \DIRECTORY_SEPARATOR, $templateName);
        if (!file_exists($filename)) {
            throw new RuntimeException(sprintf('Template file "%s" not found at "%s"', $templateName, $this->templatePath));
        }
        extract($data, EXTR_OVERWRITE);
        ob_start();
        include $filename;

        return ob_get_clean();
    }

    /**
     * @return string
     */
    public function getContentType(): string
    {
        return 'text/html; charset=utf8';
    }

    public function send(): void
    {
        header('Content-Type: '.$this->getContentType());
        http_response_code(200);
        echo $this->toString();
    }

    abstract public function toString(): string;
}

<?php

/**
 * @see       https://github.com/event-engine/php-code-generator-cartridge-event-engine for the canonical source repository
 * @copyright https://github.com/event-engine/php-code-generator-cartridge-event-engine/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-code-generator-cartridge-event-engine/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\CodeGenerator\Cartridge\EventEngine;

use OpenCodeModeling\CodeGenerator\Workflow\Description;

final class DescriptionFileMethodFactory
{
    /**
     * @var Config\DescriptionFileMethod
     **/
    private $config;

    public function __construct(Config\DescriptionFileMethod $config)
    {
        $this->config = $config;
    }

    public function config(): Config\DescriptionFileMethod
    {
        return $this->config;
    }

    public static function withDefaultConfig(): self
    {
        return new self(new Config\DescriptionFileMethod());
    }

    public function workflowComponentDescription(
        string $inputCode,
        string $output
    ): Description {
        return DescriptionFileMethod::workflowComponentDescription(
            $this->config->getParser(),
            $this->config->getPrinter(),
            $inputCode,
            $output
        );
    }
}

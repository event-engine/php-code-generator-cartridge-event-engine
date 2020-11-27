<?php

/**
 * @see       https://github.com/event-engine/php-code-generator-cartridge-event-engine for the canonical source repository
 * @copyright https://github.com/event-engine/php-code-generator-cartridge-event-engine/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-code-generator-cartridge-event-engine/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\CodeGenerator\Cartridge\EventEngine;

use EventEngine\CodeGenerator\EventEngineAst;
use OpenCodeModeling\CodeGenerator;
use OpenCodeModeling\CodeGenerator\Transformator;
use OpenCodeModeling\CodeGenerator\Workflow;

final class FunctionalWorkflowFactory
{
    /**
     * Configures the workflow for event engine functional flavour with common options.
     *
     * @param CodeGenerator\Workflow\WorkflowContext $workflowContext
     * @param string $inputSlotEventSourcingAnalyzer
     * @param string $commandPath
     * @param string $eventPath
     * @param string $valueObjectPath
     * @param callable $filterConstName
     * @param callable $filterConstValue
     * @param callable $filterDirectoryToNamespace
     * @return CodeGenerator\Config\WorkflowConfig
     */
    public static function functionalConfig(
        CodeGenerator\Workflow\WorkflowContext $workflowContext,
        string $inputSlotEventSourcingAnalyzer,
        string $commandPath,
        string $eventPath,
        string $valueObjectPath,
        callable $filterConstName,
        callable $filterConstValue,
        callable $filterDirectoryToNamespace
    ): CodeGenerator\Config\WorkflowConfig {
        $eeCommandFactory = EventEngineAst\CommandFactory::withDefaultConfig(
            $filterConstName,
            $filterConstValue,
            $filterDirectoryToNamespace
        );

        $eeEventFactory = EventEngineAst\EventFactory::withDefaultConfig(
            $filterConstName,
            $filterConstValue,
            $filterDirectoryToNamespace
        );

        $eeValueObjectFactory = EventEngineAst\ValueObjectFactory::withDefaultConfig(
            $filterConstName,
            $filterConstValue,
            $filterDirectoryToNamespace
        );

        $workflowContext->put(Slot::SLOT_COMMAND_PATH, $commandPath);
        $workflowContext->put(Slot::SLOT_EVENT_PATH, $eventPath);
        $workflowContext->put(Slot::SLOT_VALUE_OBJECT_PATH, $valueObjectPath);

        $componentDescription = [
            new Workflow\ComponentDescriptionWithSlot(
                $eeValueObjectFactory->componentFile(),
                Slot::SLOT_VALUE_OBJECT,
                $inputSlotEventSourcingAnalyzer,
                Slot::SLOT_VALUE_OBJECT_PATH,
            ),
            // Configure Event Engine command generation
            new Workflow\ComponentDescriptionWithSlot(
                $eeCommandFactory->componentFile(),
                Slot::SLOT_COMMAND,
                $inputSlotEventSourcingAnalyzer,
                Slot::SLOT_COMMAND_PATH
            ),
            // Configure Event Engine command generation
            new Workflow\ComponentDescriptionWithSlot(
                $eeCommandFactory->componentFile(),
                Slot::SLOT_COMMAND,
                $inputSlotEventSourcingAnalyzer,
                Slot::SLOT_COMMAND_PATH
            ),
            new Workflow\ComponentDescriptionWithSlot(
                $eeCommandFactory->componentProperty(),
                Slot::SLOT_COMMAND,
                $inputSlotEventSourcingAnalyzer,
                Slot::SLOT_COMMAND
            ),
            // Configure Event Engine event generation
            new Workflow\ComponentDescriptionWithSlot(
                $eeEventFactory->componentFile(),
                Slot::SLOT_EVENT,
                $inputSlotEventSourcingAnalyzer,
                Slot::SLOT_EVENT_PATH
            ),
            new Workflow\ComponentDescriptionWithSlot(
                $eeEventFactory->componentProperty(),
                Slot::SLOT_EVENT,
                $inputSlotEventSourcingAnalyzer,
                Slot::SLOT_EVENT,
            ),
        ];

        return new CodeGenerator\Config\Workflow(...$componentDescription);
    }

    /**
     * Configures a workflow to save the generated code of prototypeConfig() to files.
     *
     * @return CodeGenerator\Config\WorkflowConfig
     */
    public static function codeToFilesForFunctionalConfig(): CodeGenerator\Config\WorkflowConfig
    {
        $stringToFile = new Transformator\StringToFile();

        return new CodeGenerator\Config\Workflow(
            Transformator\CodeListToFiles::workflowComponentDescription($stringToFile, Slot::SLOT_COMMAND),
            Transformator\CodeListToFiles::workflowComponentDescription($stringToFile, Slot::SLOT_EVENT),
            Transformator\CodeListToFiles::workflowComponentDescription($stringToFile, Slot::SLOT_VALUE_OBJECT),
        );
    }
}

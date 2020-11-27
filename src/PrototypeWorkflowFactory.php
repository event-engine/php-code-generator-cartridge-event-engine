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

final class PrototypeWorkflowFactory
{
    /**
     * Configures the workflow for event engine prototype flavour with common options.
     *
     * @param CodeGenerator\Workflow\WorkflowContext $workflowContext
     * @param string $inputSlotEventSourcingAnalyzer
     * @param string $domainModelPath Path to save domain models like aggregates
     * @param string $apiDescriptionPath Path to save Event Engine descriptions e.g. command, event, aggregate
     * @param callable $filterConstName
     * @param callable $filterConstValue
     * @param callable $filterDirectoryToNamespace
     * @return CodeGenerator\Config\WorkflowConfig
     */
    public static function prototypeConfig(
        CodeGenerator\Workflow\WorkflowContext $workflowContext,
        string $inputSlotEventSourcingAnalyzer,
        string $domainModelPath,
        string $apiDescriptionPath,
        callable $filterConstName,
        callable $filterConstValue,
        callable $filterDirectoryToNamespace
    ): CodeGenerator\Config\WorkflowConfig {
        $eeAggregateStateFactory = EventEngineAst\AggregateStateFactory::withDefaultConfig(
            $filterConstName,
            $filterConstValue,
            $filterDirectoryToNamespace
        );
        $eeAggregateBehaviourFactory = EventEngineAst\AggregateBehaviourFactory::withDefaultConfig(
            $filterConstName,
            $filterConstValue,
            $filterDirectoryToNamespace,
            $eeAggregateStateFactory->config()
        );
        $eeAggregateDescriptionFactory = EventEngineAst\AggregateDescriptionFactory::withDefaultConfig(
            $filterConstName,
            $filterConstValue,
            $filterDirectoryToNamespace
        );
        $eeCommandDescriptionFactory = EventEngineAst\CommandDescriptionFactory::withDefaultConfig(
            $filterConstName,
            $filterConstValue
        );
        $eeEventDescriptionFactory = EventEngineAst\EventDescriptionFactory::withDefaultConfig(
            $filterConstName,
            $filterConstValue
        );
        $eeDescriptionFileMethodFactory = EventEngineAst\DescriptionFileMethodFactory::withDefaultConfig();
        $phpEmptyClassFactory = EventEngineAst\EmptyClassFactory::withDefaultConfig($filterDirectoryToNamespace);

        $workflowContext->put(Slot::SLOT_AGGREGATE_PATH, $domainModelPath);
        $workflowContext->put(Slot::SLOT_AGGREGATE_STATE_PATH, $domainModelPath);
        $workflowContext->put(Slot::SLOT_EE_API_COMMAND_FILENAME, $apiDescriptionPath . DIRECTORY_SEPARATOR . 'Command.php');
        $workflowContext->put(Slot::SLOT_EE_API_EVENT_FILENAME, $apiDescriptionPath . DIRECTORY_SEPARATOR . 'Event.php');
        $workflowContext->put(Slot::SLOT_EE_API_AGGREGATE_FILENAME, $apiDescriptionPath . DIRECTORY_SEPARATOR . 'Aggregate.php');
        $workflowContext->put(Slot::SLOT_COMMAND_METADATA_SCHEMA_PATH, $apiDescriptionPath . DIRECTORY_SEPARATOR . '_schema');
        $workflowContext->put(Slot::SLOT_EVENT_METADATA_SCHEMA_PATH, $apiDescriptionPath . DIRECTORY_SEPARATOR . '_schema');

        $componentDescription = [
            // Configure Event Engine description file generation
            new Workflow\ComponentDescriptionWithSlot(
                $phpEmptyClassFactory->component(),
                Slot::SLOT_EE_API_COMMAND_FILE,
                Slot::SLOT_EE_API_COMMAND_FILENAME
            ),
            new Workflow\ComponentDescriptionWithSlot(
                $phpEmptyClassFactory->component(),
                Slot::SLOT_EE_API_EVENT_FILE,
                Slot::SLOT_EE_API_EVENT_FILENAME
            ),
            new Workflow\ComponentDescriptionWithSlot(
                $phpEmptyClassFactory->component(),
                Slot::SLOT_EE_API_AGGREGATE_FILE,
                Slot::SLOT_EE_API_AGGREGATE_FILENAME
            ),
            // Configure Event Engine description method generation
            new Workflow\ComponentDescriptionWithSlot(
                $eeDescriptionFileMethodFactory->component(),
                Slot::SLOT_EE_API_COMMAND_FILE,
                Slot::SLOT_EE_API_COMMAND_FILE
            ),
            new Workflow\ComponentDescriptionWithSlot(
                $eeDescriptionFileMethodFactory->component(),
                Slot::SLOT_EE_API_EVENT_FILE,
                Slot::SLOT_EE_API_EVENT_FILE
            ),
            new Workflow\ComponentDescriptionWithSlot(
                $eeDescriptionFileMethodFactory->component(),
                Slot::SLOT_EE_API_AGGREGATE_FILE,
                Slot::SLOT_EE_API_AGGREGATE_FILE
            ),
            // Configure Event Engine description generation
            new Workflow\ComponentDescriptionWithSlot(
                $eeCommandDescriptionFactory->componentMetadataSchema(),
                Slot::SLOT_COMMAND_METADATA_SCHEMA,
                $inputSlotEventSourcingAnalyzer,
                Slot::SLOT_COMMAND_METADATA_SCHEMA_PATH
            ),
            new Workflow\ComponentDescriptionWithSlot(
                $eeCommandDescriptionFactory->component(),
                Slot::SLOT_EE_API_COMMAND_FILE,
                $inputSlotEventSourcingAnalyzer,
                Slot::SLOT_EE_API_COMMAND_FILE,
                Slot::SLOT_COMMAND_METADATA_SCHEMA
            ),
            new Workflow\ComponentDescriptionWithSlot(
                $eeEventDescriptionFactory->componentMetadataSchema(),
                Slot::SLOT_EVENT_METADATA_SCHEMA,
                $inputSlotEventSourcingAnalyzer,
                Slot::SLOT_EVENT_METADATA_SCHEMA_PATH,
            ),
            new Workflow\ComponentDescriptionWithSlot(
                $eeEventDescriptionFactory->component(),
                Slot::SLOT_EE_API_EVENT_FILE,
                $inputSlotEventSourcingAnalyzer,
                Slot::SLOT_EE_API_EVENT_FILE,
                Slot::SLOT_EVENT_METADATA_SCHEMA
            ),
            new Workflow\ComponentDescriptionWithSlot(
                $eeAggregateDescriptionFactory->component(),
                Slot::SLOT_EE_API_AGGREGATE_FILE,
                $inputSlotEventSourcingAnalyzer,
                Slot::SLOT_EE_API_AGGREGATE_FILE,
                Slot::SLOT_AGGREGATE_PATH
            ),
            // Configure Event Engine aggregate state generation
            new Workflow\ComponentDescriptionWithSlot(
                $eeAggregateStateFactory->componentFile(),
                Slot::SLOT_AGGREGATE_STATE,
                $inputSlotEventSourcingAnalyzer,
                Slot::SLOT_AGGREGATE_STATE_PATH
            ),
            new Workflow\ComponentDescriptionWithSlot(
                $eeAggregateStateFactory->componentModifyMethod(),
                Slot::SLOT_AGGREGATE_STATE,
                $inputSlotEventSourcingAnalyzer,
                Slot::SLOT_AGGREGATE_STATE
            ),
            new Workflow\ComponentDescriptionWithSlot(
                $eeAggregateStateFactory->componentDescriptionImmutableRecordOverride(),
                Slot::SLOT_AGGREGATE_STATE,
                $inputSlotEventSourcingAnalyzer,
                Slot::SLOT_AGGREGATE_STATE
            ),
            // Configure Event Engine aggregate behaviour generation
            new Workflow\ComponentDescriptionWithSlot(
                $eeAggregateBehaviourFactory->componentFile(),
                Slot::SLOT_AGGREGATE_BEHAVIOUR,
                $inputSlotEventSourcingAnalyzer,
                Slot::SLOT_AGGREGATE_PATH,
                Slot::SLOT_AGGREGATE_STATE_PATH,
                Slot::SLOT_EE_API_EVENT_FILENAME,
            ),
            new Workflow\ComponentDescriptionWithSlot(
                $eeAggregateBehaviourFactory->componentEventMethod(),
                Slot::SLOT_AGGREGATE_BEHAVIOUR,
                $inputSlotEventSourcingAnalyzer,
                Slot::SLOT_AGGREGATE_BEHAVIOUR
            ),
            new Workflow\ComponentDescriptionWithSlot(
                $eeAggregateBehaviourFactory->componentCommandMethod(),
                Slot::SLOT_AGGREGATE_BEHAVIOUR,
                $inputSlotEventSourcingAnalyzer,
                Slot::SLOT_AGGREGATE_BEHAVIOUR
            ),
        ];

        return new CodeGenerator\Config\Workflow(...$componentDescription);
    }

    /**
     * Configures a workflow to save the generated code of prototypeConfig() to files.
     *
     * @return CodeGenerator\Config\WorkflowConfig
     */
    public static function codeToFilesForPrototypeConfig(): CodeGenerator\Config\WorkflowConfig
    {
        $stringToFile = new Transformator\StringToFile();

        return new CodeGenerator\Config\Workflow(
            Transformator\StringToFile::workflowComponentDescription(Slot::SLOT_EE_API_COMMAND_FILE, Slot::SLOT_EE_API_COMMAND_FILENAME),
            Transformator\StringToFile::workflowComponentDescription(Slot::SLOT_EE_API_EVENT_FILE, Slot::SLOT_EE_API_EVENT_FILENAME),
            Transformator\StringToFile::workflowComponentDescription(Slot::SLOT_EE_API_AGGREGATE_FILE, Slot::SLOT_EE_API_AGGREGATE_FILENAME),
            Transformator\CodeListToFiles::workflowComponentDescription($stringToFile, Slot::SLOT_AGGREGATE_BEHAVIOUR),
            Transformator\CodeListToFiles::workflowComponentDescription($stringToFile, Slot::SLOT_AGGREGATE_STATE),
            Transformator\CodeListToFiles::workflowComponentDescription($stringToFile, Slot::SLOT_COMMAND_METADATA_SCHEMA),
            Transformator\CodeListToFiles::workflowComponentDescription($stringToFile, Slot::SLOT_EVENT_METADATA_SCHEMA),
        );
    }
}

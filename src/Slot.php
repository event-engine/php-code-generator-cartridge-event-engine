<?php

/**
 * @see       https://github.com/event-engine/php-code-generator-cartridge-event-engine for the canonical source repository
 * @copyright https://github.com/event-engine/php-code-generator-cartridge-event-engine/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-code-generator-cartridge-event-engine/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\CodeGenerator\Cartridge\EventEngine;

final class Slot
{
    public const SLOT_COMMAND_PATH = 'event_engine-command_path';
    public const SLOT_EVENT_PATH = 'event_engine-command_path';
    public const SLOT_AGGREGATE_PATH = 'event_engine-aggregate_path';
    public const SLOT_AGGREGATE_STATE_PATH = 'event_engine-aggregate_state_path';
    public const SLOT_VALUE_OBJECT_PATH = 'event_engine-value_object_path';

    public const SLOT_EE_API_COMMAND_FILENAME = 'event_engine-ee_api_command_filename';
    public const SLOT_EE_API_EVENT_FILENAME = 'event_engine-ee_api_event_filename';
    public const SLOT_EE_API_AGGREGATE_FILENAME = 'event_engine-ee_api_aggregate_filename';

    public const SLOT_EE_API_COMMAND_FILE = 'event_engine-ee_api_command_file';
    public const SLOT_EE_API_EVENT_FILE = 'event_engine-ee_api_event_file';
    public const SLOT_EE_API_AGGREGATE_FILE = 'event_engine-ee_api_aggregate_file';

    public const SLOT_AGGREGATE_STATE = 'event_engine-aggregate_state';
    public const SLOT_AGGREGATE_BEHAVIOUR = 'event_engine-aggregate_behaviour';

    public const SLOT_COMMAND = 'event_engine-command';
    public const SLOT_EVENT = 'event_engine-event';
    public const SLOT_VALUE_OBJECT = 'event_engine-value_object';

    public const SLOT_COMMAND_METADATA_SCHEMA_PATH = 'event_engine-command_metadata_schema_path';
    public const SLOT_COMMAND_METADATA_SCHEMA = 'event_engine-command_metadata_schema';
    public const SLOT_EVENT_METADATA_SCHEMA_PATH = 'event_engine-event_metadata_schema_path';
    public const SLOT_EVENT_METADATA_SCHEMA = 'event_engine-event_metadata_schema';
}

<?php

namespace RLuders\Patternfly\Presets;

use Illuminate\Foundation\Console\Presets\Preset;

abstract class AbstractPreset extends Preset
{
    abstract public static function createMacros();
}
<?php

namespace RLuders\Patternfly;

use Illuminate\Support\ServiceProvider;

class PatternflyPresetServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Presets\PatternflyPreset::createMacros();
    }
}
<?php

namespace RLuders\Patternfly\Presets;

use Artisan;
use Illuminate\Support\Arr;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\PresetCommand;

class PatternflyPresent extends AbstractPreset
{
    public static function createMacros()
    {
        PresetCommand::macro(
            'patterfly',
            function ($command) {
                PatternFlyPresent::install();
                $command->info('Patternfly scaffolding installed successfully.');
                $command->info('Please run "npm install && npm run dev" to compile your fresh scaffloding.');
            }
        );
    }

    public static function install()
    {
        static::updatePackages();
        static::updateSass();
        static::installPatternfly();

        if ($withAuth) {
            static::addAuthTemplate();
        } else {
            static::updateWelcomePage();
        }

        static::removeNodeModules();
    }

    protected static function updatePackageArray(array $packages)
    {
        // packages to add to the package.json
        $packagesToAdd = ['patternfly' => '^3.37.10'];
        // packages to remove from the package.json
        $packagesToRemove = ['bootstrap'];
        return $packagesToAdd + Arr::except($packages, $packagesToRemove);
    }

    protected static function updateSass()
    {
        // clean up all the files in the sass folder
        $orphan_sass_files = glob(resource_path('/assets/sass/*.*'));
        foreach($orphan_sass_files as $sass_file)
        {
            (new Filesystem)->delete($sass_file);
        }
        // copy files from the stubs folder
        copy(__DIR__.'/skeleton-stubs/app.scss', resource_path('assets/sass/app.scss'));
    }

    protected static function installPatternfly()
    {
        // remove exisiting bootstrap.js file
        (new Filesystem)->delete(
            resource_path('assets/js/bootstrap.js')
        );
        // copy a new bootstrap.js file from your stubs folder
        copy(__DIR__.'/skeleton-stubs/bootstrap.js', resource_path('assets/js/bootstrap.js'));
    }

    protected static function addAuthTeamplte()
    {
        // Add Home controller
        copy(__DIR__.'/stubs-stubs/Controllers/HomeController.php', app_path('Http/Controllers/HomeController.php'));
        // Add Auth routes in 'routes/web.php'
        $auth_route_entry = "Auth::routes();\n\nRoute::get('/home', 'HomeController@index')->name('home');\n\n";
        file_put_contents('./routes/web.php', $auth_route_entry, FILE_APPEND);
        // Copy Skeleton auth views from the stubs folder
        (new Filesystem)->copyDirectory(__DIR__.'/foundation-stubs/views', resource_path('views'));
    }

    protected static function updateWelcomePage()
    {
        // remove default welcome page
        (new Filesystem)->delete(
            resource_path('views/welcome.blade.php')
        );
        // copy new one from your stubs folder
        copy(__DIR__.'/skeleton-stubs/views/welcome.blade.php', resource_path('views/welcome.blade.php'));
    }

    protected static function removeNodeModules()
    {

    }
}
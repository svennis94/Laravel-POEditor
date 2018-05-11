<?php

namespace SeBuDesign\PoEditor\Test;

use Orchestra\Testbench\TestCase as Orchestra;
use SeBuDesign\PoEditor\PoEditorServiceProvider;

abstract class TestCase extends Orchestra
{
    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        $this->loadDotEnv();

        return [
            PoEditorServiceProvider::class,
        ];
    }

    /**
     * Load the .env file with the POEDITOR_API_TOKEN, POEDITOR_PROJECT_ID keys
     */
    protected function loadDotEnv()
    {
        $dotenv = new \Dotenv\Dotenv(__DIR__);
        $dotenv->load();
    }
}

<?php

namespace Bukita\SageBoilerplate;

use Illuminate\Support\Arr;
use Roots\Acorn\Application;

class BuildTemplates
{
    /**
     * The application instance.
     *
     * @var \Roots\Acorn\Application
     */
    protected $app;

    /**
     * Create a new BuildTemplates instance.
     *
     * @param  \Roots\Acorn\Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

}

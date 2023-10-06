<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class Login extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var string[]
     */
    protected static $views = [
        'page-login'
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [];
    }
}

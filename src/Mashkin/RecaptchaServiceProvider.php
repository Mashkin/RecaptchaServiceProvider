<?php
/**
 * This file is part of RecaptchaServiceProvider
 *
 * Copyright (c) 2015 Mashkin
 * For license information please read the LICENSE file provided with this source code
 */

namespace Mashkin;

use Silex\Application;
use Silex\ServiceProviderInterface;

class RecaptchaServiceProvider implements ServiceProviderInterface
{

    public function register (Application $app)
    {
        $app['recaptcha.language'] = 'en';
        $app['recaptcha.streamContext'] = null;

        $app['recaptcha'] = $app->share(function () use ($app) {
            return new Recaptcha($app['recaptcha.sitekey'], $app['recaptcha.secret'],
                $app['recaptcha.language'], $app['recaptcha.streamContext']);
        });
    }

    public function boot (Application $app)
    {
        $app['recaptcha.language'] = $app['locale'];
    }

}

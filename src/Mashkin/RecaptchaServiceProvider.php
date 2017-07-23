<?php
/**
 * This file is part of RecaptchaServiceProvider
 *
 * Copyright (c) 2015 Mashkin
 * For license information please read the LICENSE file provided with this source code
 */

namespace Mashkin;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class RecaptchaServiceProvider implements ServiceProviderInterface
{

    public function register(Container $app)
    {
        $app['recaptcha.streamContext'] = null;

        $app['recaptcha'] = function () use ($app) {
            return new Recaptcha($app['recaptcha.sitekey'], $app['recaptcha.secret'],
                $app['locale'], $app['recaptcha.streamContext']);
        };
    }

    public function boot (Container $app)
    {}

}

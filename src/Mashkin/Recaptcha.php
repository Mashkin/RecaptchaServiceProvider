<?php
/**
 * This file is part of RecaptchaServiceProvider
 *
 * Copyright (c) 2015 Mashkin
 * For license information please read the LICENSE file provided with this source code
 */

namespace Mashkin;


class Recaptcha {

    const API_URL = 'https://www.google.com/recaptcha/api/siteverify';
    const API_VERSION = 2;

    protected $siteKey;
    protected $secret;
    protected $language;

    protected $streamContext;

    public function __construct ($siteKey, $secret, $language = 'en', $streamContext = null)
    {
        $this->siteKey = $siteKey;
        $this->secret = $secret;
        $this->language = $language;

        $this->streamContext = $streamContext;
    }

    public function getHtmlElement ($parameters)
    {
        $parameters = array_replace(array(
            'sitekey' => $this->siteKey
        ), $parameters);

        $extras = '';
        foreach ($parameters as $key => $val) {
            $extras .= sprintf(' data-%s="%s"', $key, $val);
        }

        return sprintf('<div class="g-recaptcha"%s></div>', $extras);
    }

    public function getHtmlScript (array $parameters = array())
    {
        $parameters = array_replace(array( 'hl' => $this->language ), $parameters);
        $url = 'https://www.google.com/recaptcha/api.js';
        $qs = http_build_query($parameters);

        if(strlen($qs)) {
            $url .= '?' . $qs;
        }
        return sprintf('<script src="%s" async defer></script>', $url);
    }

    public function checkResponse ($response, $ip = null)
    {
        $parameters = array( 'response' => $response );
        if(null !== $ip) {
            $parameters['remoteip'] = $ip;
        }
        $url = $this->buildApiCall($parameters);
        return $this->doApiCall($url);
    }

    protected function buildApiCall (array $parameters)
    {
        $parameters = array_replace(array( 'secret' => $this->secret ), $parameters);
        $qs = http_build_query($parameters);

        return self::API_URL . '?' . $qs;
    }

    protected function doApiCall ($url)
    {
        $result = file_get_contents($url, false, $this->streamContext);
        $result = json_decode($result, true);
        if(!isset($result['error-codes'])) {
            $result['error-codes'] = array();
        }
        return $result;
    }

}

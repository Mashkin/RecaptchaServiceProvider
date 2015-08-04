# RecaptchaServiceProvider
Silex ServiceProvider integrating Google's ReCaptcha service

## Installation

## Through Composer

Add this package to your `composer.json` file and `composer update`

    {
      ...
      "require": {
        ...
        "mashkin/recaptcha-serviceprovider": "dev-master"
      }
    }
    

Or simply do `composer require "mashkin/recaptcha-serviceprovider" "dev-master"`.

## Usage

### As ServiceProvider

Register the `RecaptchaServiceProvider` and provide your configuration:

    $app->register(new Mashkin\RecaptchaServiceProvider(), array(
      'recaptcha.sitekey' => 'YOUR_SITE_KEY',
      'recaptcha.secret'  => 'YOUR_SITE_SECRET'
    ));
    
    // Optional:
    // Set language parameter that will be passed to ReCaptcha (default: en)
    $app['recaptcha.language'] = 'de';
    // Set stream context for API call (file_get_contents()) (default: null)
    $app['recaptcha.streamContext'] = ...;
    
On `Application::boot()`, `$app['recaptcha.language']` will be set to `$app['locale']`.
    
The ServiceProvider provides an instance of `Mashkin\Recaptcha` as `$app['recaptcha']`.  
Use it as described below.

### Standalone

    // Do your configuration
    // Required:
    $siteKey    = 'YOUR_SITE_KEY';
    $siteSecret = 'YOUR_SITE_SECRET';
    
    // Optional:
    $language = 'de';
    $streamContext = ...; // Passed to file_get_contents()

    // Create an instance of Mashkin\Recaptcha
    $recaptcha = new Recaptcha($siteKey, $siteSecret, $language, $streamContext)
    
    // Get ReCpatcha widget code
    // Get target element
    echo $recaptcha->getHtmlElement();
    // Get JavaScript
    echo $recaptcha->getHtmlScript();
    
    // Verify Captcha response
    if (isset($_POST['g-recaptcha-response'])) {
      $result = $recaptcha->checkResponse($_POST['g-recaptcha-response']);
      if ($result['success']) {
        echo "Success";
      } else {
        echo "Some errors occured: ";
        echo implode(', ', $result['error-codes']);
        die();
      }
    } else {
      die('No captcha response submitted');
    }
    
  

<?php

namespace App;

use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\YamlFileLoader;

class I18n
{
    private static $instance;
    private $translator;

    private function __construct()
    {
        // Default to English if no language is set
        $locale = $_SESSION['lang'] ?? 'en';

        $this->translator = new Translator($locale);
        $this->translator->addLoader('yaml', new YamlFileLoader());
        $this->translator->addResource('yaml', __DIR__ . '/../translations/en.yaml', 'en');
        $this->translator->addResource('yaml', __DIR__ . '/../translations/ar.yaml', 'ar');
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getTranslator()
    {
        return $this->translator;
    }

    public static function setLocale(string $locale)
    {
        $_SESSION['lang'] = $locale;
        // You might need to re-initialize the translator or handle this differently
        // depending on your application's lifecycle.
        self::$instance = new self(); 
    }

    public static function getLocale()
    {
        return $_SESSION['lang'] ?? 'en';
    }
}

<?php

use App\I18n;

function __($key, $parameters = [], $domain = null, $locale = null)
{
    return I18n::getInstance()->getTranslator()->trans($key, $parameters, $domain, $locale);
}

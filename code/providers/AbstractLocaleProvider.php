<?php

abstract class AbstractLocaleProvider extends Object implements LocaleProvider {
    /**
     * Validates supplied locale and errors if not valid.
     *
     * @sideeffects user_errors and throws exceptions
     * @param $locale
     * @return bool
     * @throws InvalidArgumentException
     */
    public static function validate_locale($locale) {
        if($locale && !i18n::validate_locale($locale)) {
            throw new InvalidArgumentException(sprintf('Invalid locale "%s"', $locale));
        }
        $localeList = i18n::config()->all_locales ?: array();

        if(!isset($localeList[$locale])) {
            user_error(
                "LocaleProviderSession::set_default_locale(): '$locale' is not a valid locale.",
                E_USER_WARNING
            );
        }
        return true;
    }

    /**
     * Convenience method gets configured LocaleProvider from Injector and return get_locale result on it
     *
     * @return string|null|false see docs for individual LocaleProviders for more info
     */
    public static function get_locale() {
        return Injector::inst()->get('LocaleProvider')->get_locale();
    }

    /**
     * Convenience method gets configured LocalProvider from Injector and calls set_locale on it
     * @param string $locale to set
     * @return string|null|false see docs for individual LocaleProviders for more info
     */
    public static function set_locale($locale) {
        return Injector::inst()->get('LocaleProvider')->set_locale($locale);
    }
}
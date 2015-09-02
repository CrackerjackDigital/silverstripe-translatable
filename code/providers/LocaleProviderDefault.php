<?php
class LocaleProviderDefault extends AbstractLocaleProvider implements LocaleProvider {
    // default locale, set to SilverStripe default but should be changed to your site default before Translatable isntalled
    private static $default_locale = 'en_US';

    // override the default
    private static $set_locale;

    public static function get_stored() {
        return null;
    }

    /**
     * Return the default locale as set in config.default_locale
     *
     * @return string
     */
    public static function get_locale()
    {
        return isset(self::$set_locale) ? self::$set_locale : self::config()->get('default_locale');
    }

    /**
     * Save a new 'local' locale which can be retrieved subsequently but which doesn't persist across http requests.
     *
     * Returns:
     *  - true if locale was changed
     *  - false if didn't change
     *  - null if no previous locale was set.
     *
     * @param $locale
     * @return boolean|null
     */
    public static function set_locale($locale)
    {
        self::validate_locale($locale);

        $existing = null;
        if ($wasSet = isset(self::$set_locale)) {
            $existing = self::$set_locale;
        }
        self::$set_locale = $locale;
        return $wasSet ? ($existing !== $locale) : null;
    }
}
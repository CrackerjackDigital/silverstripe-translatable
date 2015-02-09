<?php
class LocaleProviderMulti extends Object implements LocaleProvider, LocaleStore {
    // array of provider class names to use in priority order they will be checked
    // e.g. [ 'LocaleProviderRequestVar', 'LocaleProviderSession', 'LocaleProviderDefault' ]
    private static $providers = [
        'LocaleProviderDefault'
    ];

    // array of 'stores' where locale should be saved to if it is updated, generally only one should be used!
    // NB: LocaleProviderDefault should not be one of these.
    private static $stores = [
        'LocaleProviderSession'
    ];

    // break on first match of a provider returning null or a value.
    private static $match_first = true;

    /**
     * Get the locale from the store, return the first locale found (i.e. get_locale returns !== false).
     * LocaleStores registered in config.stores are checked, but only if they also implement LocaleProvider
     * @return string|bool
     */
    public static function get_stored() {
        foreach (self::config()->stores as $className) {
            if (singleton($className) instanceof LocaleProvider) {
                if (false !== ($locale = $className::get_locale())) {
                    return $locale;
                }
            }
        }
        return false;
    }

    /**
     * Return the current locale from a set of registered locale providers via config.providers
     *
     * - null (missing data) if there was a request but no or invalid locale (e.g. locale= ) on query string.
     * - false (no field) if no value could be decoded by this provider (i.e. no locale= at all) on query string.
     * - locale string in e.g. en_US format.
     *
     * @return string|null|boolean
     */
    public static function get_locale()
    {
        $locale = false;
        $matchFirst = self::config()->get('match_first');

        if ($providers = self::config()->get('providers')) {
            foreach ($providers as $className) {

                $locale = $className::get_locale();

                // if result was found (not false or null) and we only match first then don't check other providers.
                if ($locale && $matchFirst) {
                    break;
                }
            }
        }
        return $locale;
    }

    /**
     * Save the locale into stores registered in config.stores.
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
        $changed = [];
        foreach (self::config()->get('stores') as $className) {
            if (singleton($className) instanceof LocaleStore) {
                $changed[] = $className::set_locale($locale);
            }
        }
        return array_reduce($changed, function($prev, $item) { return $prev || $item; });
    }
}
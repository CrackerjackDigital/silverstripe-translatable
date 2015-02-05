<?php

class LocaleProviderSession extends AbstractLocaleProvider implements LocaleProvider, LocaleStore {
    // case sensitive key into session where current locale is stored.
    private static $session_var_name = 'LocaleProviderLocale';
    /**
     * Return the current locale from the session using config.session_var_name as key
     *
     * - null if there was a request but no or invalid locale (e.g. locale= ) on query string.
     * - false if no value could be decoded by this provider (i.e. no locale=abc) on query string.
     * - locale string in e.g. en_US format.
     *
     * @return string|null|boolean
     */
    public static function get_locale()
    {
        $sessionVars = Session::get_all();
        $sessionVarName = self::config()->get('session_var_name');

        if (array_key_exists($sessionVarName, $sessionVars)) {
            return isset($sessionVars[$sessionVarName])
                ? $sessionVars[$sessionVarName]
                : null;
        }
        return false;
    }

    /**
     * Save the locale to the session for later retrieval.
     *
     * Returns:
     *  - true if locale was changed
     *  - false if didn't change
     *  - null if no previous locale was set.
     *
     * @sideeffect Updates and Saves Session!
     * @param $locale
     * @return boolean|null
     * @throws InvalidArgumentException
     */
    public static function set_locale($locale)
    {
        self::validate_locale($locale);

        $sessionVarName = self::config()->get('session_var_name');
        $sessionVars = Session::get_all();
        $wasSet = array_key_exists($sessionVarName, $sessionVars);

        Session::set($sessionVarName, $locale);
        Session::save();

        $existing = null;
        if ($wasSet) {
            $existing = $sessionVars[$sessionVarName];
        }
        return $wasSet ? ($existing !== $locale) : null;
    }
}
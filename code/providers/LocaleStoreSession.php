<?php
class LocaleStoreSession extends LocaleProviderSession implements LocaleStore {

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
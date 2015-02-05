<?php

/**
 * For classes which can store a locale for later retrieval by e.g. LocaleProvider::get_locale.
 */
interface LocaleStore {
    /**
     * Save the locale for later retrieval.
     *
     * Returns:
     *  - true if locale was changed
     *  - false if didn't change
     *  - null if no previous locale was set.
     *
     * @param $locale
     * @return boolean|null
     */
    public static function set_locale($locale);
}
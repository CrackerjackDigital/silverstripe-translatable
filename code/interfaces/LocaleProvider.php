<?php

interface LocaleProvider {
    /**
     * Return the current locale the site is running in as could be determined by this provider.
     *
     * - null (missing data value) if there was a request but no or invalid locale (e.g. locale= ) on query string.
     * - false (no available data) if no value could be decoded by this provider (i.e. no locale=abc) on query string.
     * - locale string in e.g. en_US format.
     *
     * @return string|null|boolean
     */
    public static function get_locale();

}
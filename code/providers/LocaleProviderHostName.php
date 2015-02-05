<?php

class LocaleProviderHostName extends AbstractLocaleProvider {

    // map of hostname => locale entries can use filesystem type wildcards as key (e.g. *.co.nz or server?.fred.com)
    private static $host_to_locale_map = [];

    /**
     * Return the locale as identified by config.host_to_locale_map
     *
     * - null (missing data value) nothing in map for this host
     * - false (no matching host) couldn't match the host to any in map
     * - locale string in e.g. en_US format.
     *
     * @return string|null|boolean
     */
    public static function get_locale()
    {
        if ($hostName = Controller::curr()->getRequest()->getHeader('Host')) {
            foreach (self::config()->get('host_to_locale_map') as $pattern => $locale) {
                if (fnmatch($pattern, $hostName)) {
                    return $locale;
                }
            }
            return null;
        }
        return false;
    }
}
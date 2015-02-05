<?php

class LocaleProviderClientGeoIP extends AbstractLocaleProvider {
    // path and filename of geoip data file relative to Director::baseFolder.
    private static $geoip_data_file = '';
    /**
     * Return the locale as found by GeoIP lookup of clients REMOTE_ADDR
     *
     * - null (missing data value) can't find remote address in lookup file
     * - false (no available data) no file or no remote address provided in request so can't do lookup
     * - locale string in e.g. en_US format.
     *
     * @return string|null|boolean
     */
    public static function get_locale()
    {
        if ($filePathAndName = self::config()->geoip_data_file) {
            $gi = geoip_open(Director::baseFolder() . $filePathAndName, GEOIP_STANDARD);
            $countryCode = geoip_country_code_by_addr($gi, $_SERVER['REMOTE_ADDR']);
            geoip_close($gi);

            return $countryCode ?: null;
        }
        return false;
    }
}
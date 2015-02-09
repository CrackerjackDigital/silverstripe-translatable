<?php

class LocaleProviderClientGeoIP extends AbstractLocaleProvider {
    // path and filename of geoip data file relative to Director::baseFolder.
    private static $geoip_data_file = '';

    // map country code to a Locale, if GeoIP address not found in map then null will result.
    private static $country_code_to_locale_map = [
        'AU' => 'en_AU',
        'GB' => 'en_GB',
        'US' => 'en_US',
        'NZ' => 'en_NZ'
    ];
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
            if ($gi = geoip_open(Director::baseFolder() . $filePathAndName, GEOIP_STANDARD)) {
                $countryCode = geoip_country_code_by_addr($gi, $_SERVER['REMOTE_ADDR']);
                geoip_close($gi);

                if ($countryCode) {
                    $map = self::config()->get('country_code_to_locale_map');
                    if (isset($map[$countryCode])) {
                        return $map[$countryCode];
                    }
                }
                return null;
            }
        }
        return false;
    }
}
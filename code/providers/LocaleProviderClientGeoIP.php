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

    private static $server_vars_for_remote_address = [
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'X-FORWARDED_FOR',
        'X-Forwarded-For',
        'REMOTE_ADDR',
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

	        if ($remoteAddress = static::remote_address()) {

		        if ($gi = geoip_open(Director::baseFolder() . $filePathAndName, GEOIP_STANDARD)) {

                    $countryCode = geoip_country_code_by_addr($gi, $remoteAddress);
                    geoip_close($gi);

                    if ($countryCode) {
                        $map = self::config()->get('country_code_to_locale_map');
                        if (isset($map[ $countryCode ])) {
                            return $map[ $countryCode ];
                        }
                    }
                    return null;
                }
            }
        }
        return false;
    }

    /**
     * Find the remote client ip from server variable names as configured.
     * @param string $configName
     * @return string|void IP address or void
     */
    private static function remote_address($configName = 'server_vars_for_remote_address') {
        $varNames = static::config()->get($configName) ?: [];

        foreach ($varNames as $varName) {
            if (isset($_SERVER[$varName])) {
                return $_SERVER[$varName];
            }
        }
    }
}
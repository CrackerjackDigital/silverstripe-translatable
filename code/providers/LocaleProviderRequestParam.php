<?php

class LocaleProviderRequestParam extends AbstractLocaleProvider {
    // case sensitive request parameter name to match against name from SS url handling rules,
    // e.g. '/$RegionCode/$ID/$Action/' where we would have RegionCode here.
    private static $request_param_name = '';

    // map in format [ 'nz' => 'en_NZ' ];
    private static $request_param_map = [];

    /**
     * Return the current locale from a request parameter as per SilverStripe url handling rules.
     *
     * - null if there was a request but no or invalid locale (e.g. locale= ) on query string.
     * - false if no value could be decoded by this provider (i.e. no locale=abc) on query string.
     * - locale string in e.g. en_US format.
     *
     * To provide for more friendly urls config.request_param_map will be checked to see if the
     * value on the url is a key and if so return the corresponding value from the map.
     *
     * @return string|null|boolean
     */
    public static function get_locale()
    {
        $requestParams = Controller::curr()->getRequest()->allParams();
        $requestParamName = self::config()->get('request_param_name');

        if (array_key_exists($requestParamName, $requestParams)) {
            if (!isset($requestParams[$requestParamName])) {
                return null;
            }
            $requestParamValue = $requestParams[$requestParamName];

            // check to see if we map the url value to a locale internally.
            if (isset(self::$request_param_map[$requestParamValue])) {
                return self::$request_param_map[$requestParamValue];
            } else {
                return $requestParamValue;
            }
        }
        return false;
    }
}
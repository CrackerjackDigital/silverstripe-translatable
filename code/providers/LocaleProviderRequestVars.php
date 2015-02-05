<?php
class LocaleProviderRequestVars extends AbstractLocaleProvider {

    // registry of strings to match in request variable names e.g. 'locale'.
    private static $request_vars = [];

    // break on matching first request variable.
    private static $match_first = true;

    // do match with case sensitivity.
    private static $match_case = false;
    /**
     * Return the current locale from request variables passed matched to config.request_vars.
     *
     * - null if there was a request but no or invalid locale (e.g. locale= ) on query string.
     * - false if no value could be decoded by this provider (i.e. no locale=abc) on query string.
     * - locale string in e.g. en_US format.
     *
     * @return string|null|boolean
     */
    public static function get_locale()
    {
        $matchFirst = self::config()->get('match_first');
        $matchCase = self::config()->get('match_case');

        // this will be set to null or the found value if value is found in last loop below.
        $value = false;

        if ($incomingRequestVars = Controller::curr()->getRequest()->requestVars()) {

            if (!$matchCase) {
                // make array of lowercase keys so can compute intersect.
                $checkVars = array_fill_keys(
                    array_map('strtolower', self::config()->get('request_vars')),
                    ''
                );
                // lowercase incoming request keys and then intersect with what we're looking for
                $requestVars = array_intersect_key(
                    array_combine(
                        array_map('strtolower', array_keys($incomingRequestVars)),
                        array_values($incomingRequestVars)
                    ),
                    $checkVars
                );
            } else {
                // make array of keys so can compute intersect.
                $checkVars = array_fill_keys(
                    self::config()->get('request_vars'),
                    ''
                );
                // get matching request var names to ones we're looking for
                $requestVars = array_intersect_key(
                    $incomingRequestVars,
                    $checkVars
                );
            };
            if ($requestVars) {
                foreach ($checkVars as $checkName => $notused) {
                    // finally found a matching value or maybe empty so set to null
                    if (array_key_exists($checkName, $requestVars)) {
                        $value = $requestVars[$checkName] ?: null;

                        if ($matchFirst) {
                            // break out on first found if matchFirst
                            break;
                        }
                    }
                }
            }
        }
        return $value;
    }
}
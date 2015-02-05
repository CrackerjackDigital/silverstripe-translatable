<?php
/**
 * This store configures other modules such as i18n with the current chosen locale. Don't put anything that calls a
 * multipled provider in here or you will end up in recursive loop e.g if Translatable in here will call Multi which
 * will call this which will call Translatable etc.
 */
class LocaleStoreConfigureRuntime extends Object implements LocaleStore {
    // A map of modules and the method names to call on them to set the locale.
    // Methods may start with '::' for static or other -> for instance methods.
    private static $modules_and_method_map = [
        'i18n' => '::set_locale'
    ];
    /**
     * Calls method on modules listed in config.module_and_method_map passing locale.
     *
     * Returns:
     *  - true if locale was changed
     *  - false if didn't change
     *
     * @sideeffect sets Translation and i18n locales
     * @param $locale
     * @return boolean|null
     */
    public static function set_locale($locale)
    {
        $changed = [];
        foreach (self::config()->get('modules_and_method_map') as $module => $method) {
            $type = substr($method, 0, 2);
            $method = substr($method, 2);
            if ($type == '::') {
                $changed[] = $module::$method($locale);
            } else {
                $changed[] = singleton($module)->$method($locale);
            }
        }
        return array_reduce($changed, function($prev, $item) { return $prev || $item; });
    }
}
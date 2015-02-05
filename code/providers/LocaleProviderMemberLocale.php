<?php
class LocaleProviderMemberLocale extends AbstractLocaleProvider {

    /**
     * Return the Locale setting for the current member.
     *
     * - null if member has no Locale
     * - false if no logged in Member
     * - Member Locale
     *
     * @return string|null|boolean
     */
    public static function get_locale()
    {
        if ($member = Member::currentUser()) {
            return $member->Locale ?: null;
        }
        return false;
    }
}
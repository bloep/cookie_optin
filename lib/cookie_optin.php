<?php

class cookie_optin {

    const STATUS_OFF = 0;
    const STATUS_SIMPLE = 1;
    const STATUS_NAMECHECK = 2;
    const STATUS_VALUECHECK = 3;

    const CONFIG_NAMESPACE = 'cookie_option';

    /**
     * @param rex_extension_point $ep
     */
    public static function ep($ep) {
        if(!rex::isBackend() && self::isActivated()) {
            if(self::getStatus() == self::STATUS_SIMPLE) {
                if(sizeof($_COOKIE) !== 0) {
                    self::removeCookie();
                }
            } elseif(self::getStatus() == self::STATUS_NAMECHECK) {
                $cookieName = self::getConfig('cookie_name');
                if(!isset($_COOKIE[$cookieName])) {
                    self::removeCookie();
                }
            } elseif(self::getStatus() == self::STATUS_VALUECHECK) {
                $cookieName = self::getConfig('cookie_name');
                $cookieValue = self::getConfig('cookie_value');
                if(!isset($_COOKIE[$cookieName]) && $_COOKIE[$cookieName] != $cookieValue) {
                    self::removeCookie();
                }
            }
        }
    }

    /**
     * @param rex_yform_action_callback $cb
     * @return rex_yform_action_callback
     */
    public static function saveConfigForm($cb) {
        $vars = $cb->params['value_pool']['sql'];
        foreach($vars as $key => $value) {
            rex_config::set(self::CONFIG_NAMESPACE, $key, $value);
        }
        return $cb;
    }

    public static function getConfig($key, $default = '') {
        return rex_config::get(self::CONFIG_NAMESPACE, $key, $default);
    }

    public static function isActivated() {
        $status = self::getStatus();
        return $status == self::STATUS_SIMPLE || $status == self::STATUS_NAMECHECK || $status == self::STATUS_VALUECHECK;
    }

    public static function getStatus() {
        return self::getConfig('cookie_enabled', 0);
    }

    public static function removeCookie() {
        header_remove('Set-Cookie');
    }
}
<?php
if(!rex_config::has(cookie_optin::CONFIG_NAMESPACE,'cookie_name')) {
    rex_config::set(cookie_optin::CONFIG_NAMESPACE,'cookie_name', 'cookieconsent_dismissed');
}
if(!rex_config::has(cookie_optin::CONFIG_NAMESPACE,'cookie_value')) {
    rex_config::set(cookie_optin::CONFIG_NAMESPACE,'cookie_value', 'yes');
}
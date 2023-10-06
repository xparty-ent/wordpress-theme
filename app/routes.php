<?php

/**
 * Theme routes.
 */

function xp_rewrite_rules() {
}

function xp_rest_api_init() {
}

function xp_login_url($login_url) {
    return site_url('/login');
}

function xp_lostpassword_url($lostpassword_url) {
    return site_url('/password-lost');
}

function xp_register_url($register_url) {
    return site_url('/register');
}

function xp_disable_wp_login() {
    global $pagenow;
    if($pagenow == 'wp-login.php') {
        return wp_redirect(xp_login_url('/login'));
    }
}

add_action('login_url', 'xp_login_url');
add_action('lostpassword_url', 'xp_lostpassword_url');
add_action('register_url', 'xp_register_url');
add_action('init', 'xp_disable_wp_login');
add_action('init', 'xp_rewrite_rules');
add_action('rest_api_init', 'xp_rest_api_init');

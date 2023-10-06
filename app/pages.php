<?php

/**
 * Theme pages.
 */

 function xp_template_redirects() {
    $post = get_post();

    if(!$post)
        return;

    switch($post->post_name) {
        case 'login':
            if(is_user_logged_in()) {
                wp_redirect(get_home_url());
                return;
            }
            break;
    }
}
 
add_action('template_redirect', 'xp_template_redirects');
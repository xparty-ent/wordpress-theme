<?php

/**
 * Theme menus.
 */

// Guest primary navigation
register_nav_menus([
    'guest_primary_navigation' => __('Guest Primary Navigation', 'sage'),
]);

// User primary navigation
register_nav_menus([
    'user_primary_navigation' => __('User Primary Navigation', 'sage'),
]);

 function add_user_menu_items($menu_objects, $args) {
    $menu_locations = get_nav_menu_locations();
    
    $user_menu_id = is_user_logged_in() ? 
        $menu_locations['user_primary_navigation']
        : $menu_locations['guest_primary_navigation'];
        
    $user_menu_objects = wp_get_nav_menu_items($user_menu_id);

    foreach ($user_menu_objects as $key => $menu_object) {
        switch($menu_object->post_name) {
            case 'admin':
                if(!current_user_can('manage_options'))
                    continue 2;
                $menu_object->url = get_admin_url();
                break;
            case 'logout':
                $menu_object->url = wp_logout_url(get_permalink());
                break;
            default:
                break;
        }
    }

    return array_merge($menu_objects, $user_menu_objects);
}
add_filter('wp_nav_menu_objects', 'add_user_menu_items', 10, 2);
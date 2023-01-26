<?php
/*
  Plugin Name: FPW Synchronize Shared Users
  Author: Frank P. Walentynowicz
  Author URI: https://fw2s.com
  Disclaimer: Use at your own risk. No warranty expressed or implied is provided.
 */

// Users synchronization on admin login
function fpw_synchronize_admins_on_admin_login($user_login, $user)
{
    if (array_key_exists('administrator', $user->caps)) {
        global $wpdb;
        $site_prefix = $wpdb->prefix;
        $admins_only = true;

        $other_prefixes = array(
            'xy_',
        );

        $args = array(
            'fields'    => 'ID',
        );
        if ($admins_only)
            $args['role'] = 'administrator';

        $users = get_users($args);

        foreach ($users as $id) {
            $cap = get_user_meta($id, $site_prefix . 'capabilities', true);

            foreach ($other_prefixes as $prefix)
                update_user_meta($id, $prefix . 'capabilities', $cap);
        }
    }
}
add_action('wp_login', 'fpw_synchronize_admins_on_admin_login', 10, 2);

// User synchronization on admin create user
function fpw_synchronize_user_on_admin_register($id)
{
    $me = wp_get_current_user();
    if (array_key_exists('administrator', $me->caps)) {
        $other_prefixes = array(
            'xy_',
        );
        $user = get_user_by('id', $id);
        $cap = $user->caps;
        foreach ($other_prefixes as $prefix)
            update_user_meta($id, $prefix . 'capabilities', $cap);
    }
}
add_action('user_register', 'fpw_synchronize_user_on_admin_register', 10, 1);

// User synchronization on profile update
function fpw_synchronize_user_on_profile_update($user_id)
{
    if (current_user_can('edit_user', $user_id)) {
        $other_prefixes = array(
            'xy_',
        );
        $cap = array($_POST['role'] => true,);
        foreach ($other_prefixes as $prefix)
            update_user_meta($user_id, $prefix . 'capabilities', $cap);
    }
}
add_action('edit_user_profile_update', 'fpw_synchronize_user_on_profile_update');
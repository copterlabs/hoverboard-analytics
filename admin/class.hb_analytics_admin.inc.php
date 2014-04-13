<?php

/*
 * Fails if this class wasn't loaded by the plugin boot script 
 */
if (!defined('HBGA_VERSION')) {
    header( 'HTTP/1.0 403 Forbidden' );
    die;
}

/**
 * A sample plugin class for building Dropsite things
 */
class HB_Analytics_Admin
{
    /**
     * Constructor
     */
    public function __construct(  ) {
        add_action('hoverboard/init', array($this, 'init'), 20);
        add_action('hoverboard/admin_head', array($this, 'nag'));
    }

    /**
     * Performs plugin setup tasks, such as registering custom fields
     * @return void
     */
    public function init (  ) {
        add_action('acf/register_fields', array($this, 'register_fields'));
    }

    /**
     * Registers custom fields for the plugin
     * @return void
     */
    public function register_fields(  ) {
        // Adds sub-pages for the various theme settings
        if (function_exists('acf_add_options_sub_page')) {
            acf_add_options_sub_page(array(
                'title' => 'Google Analytics',
                'parent' => 'options-general.php',
                'capability' => 'manage_options'
            ));
        }

        // Registers the custom fields for each custom post type and options page
        if (function_exists('register_field_group')) {
            register_field_group(array (
                'id' => 'acf_analytics-info',
                'title' => 'Analytics Info',
                'fields' => array (
                    array (
                        'key' => 'field_52684db90b82a',
                        'label' => 'Google Analytics',
                        'name' => 'google_analytics_id',
                        'type' => 'text',
                        'instructions' => 'Your Analytics ID will look something like this: UA-12345678-9',
                        'default_value' => '',
                        'placeholder' => 'UA-XXXXXXXX-X',
                        'prepend' => '',
                        'append' => '',
                        'formatting' => 'none',
                        'maxlength' => '',
                    ),
                ),
                'location' => array (
                    array (
                        array (
                            'param' => 'options_page',
                            'operator' => '==',
                            'value' => 'acf-options-google-analytics',
                            'order_no' => 0,
                            'group_no' => 0,
                        ),
                    ),
                ),
                'options' => array (
                    'position' => 'normal',
                    'layout' => 'default',
                    'hide_on_screen' => array (
                    ),
                ),
                'menu_order' => 20,
            ));
        }
    }

    public function nag(  ) {
        if (!current_user_can('manage_options')) {
            return FALSE;
        }

        if (!get_field('google_analytics_id', 'options')) {
            $views_path = HBGA_PATH . '/admin/views';
            $variables = array(
                'setting_url' => admin_url('options-general.php?page=acf-options-google-analytics'),
            );
            echo Hoverboard::get_mustache($views_path)->render('nag-alert', $variables);
        }
    }
}

<?php

/*
 * Fails if this class wasn't loaded by the plugin boot script 
 */
if (!defined('HBGA_VERSION')) {
    header( 'HTTP/1.0 403 Forbidden' );
    die;
}

/**
 * A HoverBoard add-on for integrating Google Analytics
 */
class HB_Analytics_Frontend
{
    /**
     * Constructor
     */
    public function __construct(  ) {
        add_action('hoverboard/init', array($this, 'init'), 20);
    }

    /**
     * Registers required actions for the plugin
     * @return void
     */
    public function init (  ) {
        add_action('hoverboard/footer', array($this, 'display_tracking_code'));
    }

    /**
     * Outputs Google Analytics tracking code
     * @return void
     */
    public function display_tracking_code(  ) {
        $ga = get_option('options_google_analytics_id');
        if(!empty($ga)) {
            $variables = array(
                'domain' => preg_replace('/^www\./', '', $_SERVER['SERVER_NAME']),
                'ga'  => $ga,
            );

            $views_path = HBGA_PATH . 'frontend/views';
            echo Hoverboard::get_mustache($views_path)->render('google-analytics', $variables);
        }
    }
}

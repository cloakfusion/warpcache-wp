<?php

/**
* WARPCACHE_Enabler
*
* @since 0.0.1
*/

class WARPCACHE_Enabler
{


	/**
	* pseudo-constructor
	*
	* @since   0.0.1
	* @change  0.0.1
	*/

	public static function instance()
	{
		new self();
	}


	/**
	* constructor
	*
	* @since   0.0.1
	* @change  0.0.1
	*/

	public function __construct()
	{

        /* CDN rewriter hook */
        add_action(
            'template_redirect',
            array(
                __CLASS__,
                'handle_rewrite_hook'
            )
        );

		/* Filter */
		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) OR (defined('DOING_CRON') && DOING_CRON) OR (defined('DOING_AJAX') && DOING_AJAX) OR (defined('XMLRPC_REQUEST') && XMLRPC_REQUEST) ) {
			//return;
		}

		/* BE only */
		if ( ! is_admin() ) {
			//return;
		}

		/* Hooks */
		add_action(
			'admin_init',
			array(
				'WARPCACHE_Enabler_Settings',
				'register_settings'
			)
		);
		add_action(
			'admin_menu',
			array(
				'WARPCACHE_Enabler_Settings',
				'add_settings_page'
			)
		);
        add_filter(
            'plugin_action_links_' .WARPCACHE_ENABLER_BASE,
            array(
                __CLASS__,
                'add_action_link'
            )
        );

        /* admin notices */
        add_action(
            'all_admin_notices',
            array(
                __CLASS__,
                'warpcache_enabler_requirements_check'
            )
        );

	}



	/**
	* add action links
	*
	* @since   0.0.1
	* @change  0.0.1
	*
	* @param   array  $data  alreay existing links
	* @return  array  $data  extended array with links
	*/

	public static function add_action_link($data)
	{
		// check permission
		if ( ! current_user_can('manage_options') ) {
			return $data;
		}

		return array_merge(
			$data,
			array(
				sprintf(
					'<a href="%s">%s</a>',
					add_query_arg(
						array(
							'page' => 'warpcache_enabler'
						),
						admin_url('options-general.php')
					),
					__("Settings")
				)
			)
		);
	}


	/**
	* run uninstall hook
	*
	* @since   0.0.1
	* @change  0.0.1
	*/

	public static function handle_uninstall_hook()
	{
        delete_option('warpcache_enabler');
	}


	/**
	* run activation hook
	*
	* @since   0.0.1
	* @change  0.0.1
	*/

	public static function handle_activation_hook() {
        add_option(
            'warpcache_enabler',
            array(
                'url' => '',
                'alias' => '',
                'dirs' => 'wp-content,wp-includes',
                'excludes' => '.php',
                'relative' => '1',
                'https' => ''
            )
        );
	}


	/**
	* check plugin requirements
	*
	* @since   0.0.1
	* @change  0.0.1
	*/

	public static function warpcache_enabler_requirements_check() {
		// WordPress version check
		if ( version_compare($GLOBALS['wp_version'], WARPCACHE_ENABLER_MIN_WP.'alpha', '<') ) {
			show_message(
				sprintf(
					'<div class="error"><p>%s</p></div>',
					sprintf(
						__("This Warpcache plugin is optimized for WordPress %s. Please disable the plugin or upgrade your WordPress installation (recommended).", "cdn"),
						WARPCACHE_ENABLER_MIN_WP
					)
				)
			);
		}
	}


	/**
	* return plugin options
	*
	* @since   0.0.1
	* @change  0.0.1
	*
	* @return  array  $diff  data pairs
	*/

	public static function get_options()
	{
		return wp_parse_args(
			get_option('warpcache_enabler'),
			array(
                'url' => '',
                'alias' => '',
                'dirs' => 'wp-content,wp-includes',
                'excludes' => '.php',
                'relative' => 1,
                'https' => 0
			)
		);
	}


    /**
	* run rewrite hook
	*
	* @since   0.0.1
	* @change  0.0.1
	*/

    public static function handle_rewrite_hook()
    {
        $options = self::get_options();

        // check if origin equals cdn url
        if (get_option('siteurl') == $options['url']) {
    		return;
    	}

        // Override alias if it's set, otherwise just use warpcache url
        $cdn_url = '';

        if (!empty($options['alias']) && $options['alias'] != '') {
            $cdn_url = $options['alias'];
        } else if( $options['url'] != '') {
            $cdn_url = $options['url'] . ".cdn.warpcache.com";
        }

	if ($options['https'] && $cdn_url != '') {
		$cdn_url = 'https://' . $cdn_url;
	} else if ($cdn_url != '') {
		$cdn_url = 'http://' . $cdn_url;
	}

        $excludes = array_map('trim', explode(',', $options['excludes']));

    	$rewriter = new WARPCACHE_Enabler_Rewriter(
    		get_option('siteurl'),
            $cdn_url,
    		$options['dirs'],
    		$excludes,
    		$options['relative'],
    		$options['https']
    	);
    	ob_start(
            array(&$rewriter, 'rewrite')
        );
    }

}

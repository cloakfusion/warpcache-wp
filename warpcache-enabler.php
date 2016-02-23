<?php
/*
Plugin Name: Warpcache CDN
Text Domain: cdn
Description: Easily integrate Warpcache into your WordPress site.
Author: Cloakfusion
Author URI: https://www.cloakfusion.com
License: GPLv2 or later
Version: 1.0.0
*/

/*
Copyright (C)  2016 Cloakfusion

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/


/* Check & Quit */
defined('ABSPATH') OR exit;


/* constants */
define('WARPCACHE_ENABLER_FILE', __FILE__);
define('WARPCACHE_ENABLER_DIR', dirname(__FILE__));
define('WARPCACHE_ENABLER_BASE', plugin_basename(__FILE__));
define('WARPCACHE_ENABLER_MIN_WP', '3.8');


/* loader */
add_action(
	'plugins_loaded',
	array(
		'WARPCACHE_Enabler',
		'instance'
	)
);


/* uninstall */
register_uninstall_hook(
	__FILE__,
	array(
		'WARPCACHE_Enabler',
		'handle_uninstall_hook'
	)
);


/* activation */
register_activation_hook(
	__FILE__,
	array(
		'WARPCACHE_Enabler',
		'handle_activation_hook'
	)
);


/* autoload init */
spl_autoload_register('WARPCACHE_ENABLER_autoload');

/* autoload funktion */
function WARPCACHE_ENABLER_autoload($class) {
	if ( in_array($class, array('WARPCACHE_Enabler', 'WARPCACHE_Enabler_Rewriter', 'WARPCACHE_Enabler_Settings')) ) {
		require_once(
			sprintf(
				'%s/inc/%s.class.php',
				WARPCACHE_ENABLER_DIR,
				strtolower($class)
			)
		);
	}
}

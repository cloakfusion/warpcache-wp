<?php

/**
* WARPCACHE_Enabler_Settings
*
* @since 0.0.1
*/

class WARPCACHE_Enabler_Settings
{


	/**
	* register settings
	*
	* @since   0.0.1
	* @change  0.0.1
	*/

	public static function register_settings()
	{
		register_setting(
			'warpcache_enabler',
			'warpcache_enabler',
			array(
				__CLASS__,
				'validate_settings'
			)
		);
	}


	/**
	* validation of settings
	*
	* @since   0.0.1
	* @change  0.0.1
	*
	* @param   array  $data  array with form data
	* @return  array         array with validated values
	*/

	public static function validate_settings($data)
	{
		if (sanitize_title($data['url']) != $data['url']) {
			add_settings_error( 'url', 'url', "The supplied label doesn't look like what we thought it would look like. Looking for a" );
			$data['url'] = '';
		}
		return array(
			'url'		=> sanitize_title($data['url']),
			'alias'		=> esc_attr($data['alias']),
			'dirs'		=> esc_attr($data['dirs']),
			'excludes'	=> esc_attr($data['excludes']),
			'relative'	=> (int)($data['relative']),
			'https'		=> (int)($data['https'])
		);
	}


	/**
	* add settings page
	*
	* @since   0.0.1
	* @change  0.0.1
	*/

	public static function add_settings_page()
	{
		$page = add_options_page(
			'Warpcache',
			'Warpcache CDN',
			'manage_options',
			'warpcache_enabler',
			array(
				__CLASS__,
				'settings_page'
			)
		);
	}


	/**
	* settings page
	*
	* @since   0.0.1
	* @change  0.0.1
	*
	* @return  void
	*/

	public static function settings_page()
	{ ?>
		<div class="wrap">
			<h2>
				<?php _e("Warpcache Settings", "cdn"); ?>
			</h2>

			<form method="post" action="options.php">
				<?php settings_fields('warpcache_enabler') ?>

				<?php $options = WARPCACHE_Enabler::get_options() ?>

				<table class="form-table">

					<tr valign="top">
						<th scope="row">
							<?php _e("Warpcache Label", "cdn"); ?>
						</th>
						<td>
							<fieldset>
								<label for="warpcache_enabler_url">
									<input type="text" name="warpcache_enabler[url]" id="warpcache_enabler_url" value="<?php echo $options['url']; ?>" size="64" class="regular-text code" />
									<?php _e("", "cdn"); ?>
								</label>

								<p class="description">
									<?php _e("Enter the label as found in the Cloakfusion control panel (https://my.cloakfusion.com). Leave empty to stop using a CDN.", "cdn"); ?>
								</p>
							</fieldset>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row">
							<?php _e("Custom Alias", "cdn"); ?>
						</th>
						<td>
							<fieldset>
								<label for="warpcache_enabler_alias">
									<input type="text" name="warpcache_enabler[alias]" id="warpcache_enabler_alias" value="<?php echo $options['alias']; ?>" size="64" class="regular-text code" />
									<?php _e("", "cdn"); ?>
								</label>

								<p class="description">
									<?php _e("Enter the alias as found in the Cloakfusion control panel (https://my.cloakfusion.com)", "cdn"); ?>
								</p>
							</fieldset>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row">
							<?php _e("Included Directories", "cdn"); ?>
						</th>
						<td>
							<fieldset>
								<label for="warpcache_enabler_dirs">
									<input type="text" name="warpcache_enabler[dirs]" id="warpcache_enabler_dirs" value="<?php echo $options['dirs']; ?>" size="64" class="regular-text code" />
									<?php _e("Default: <code>wp-content,wp-includes</code>", "cdn"); ?>
								</label>

								<p class="description">
									<?php _e("Assets in these directories will be pointed to the CDN URL. Enter the directories separated by", "cdn"); ?> <code>,</code>
								</p>
							</fieldset>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row">
							<?php _e("Excluded Extensions", "cdn"); ?>
						</th>
						<td>
							<fieldset>
								<label for="warpcache_enabler_excludes">
									<input type="text" name="warpcache_enabler[excludes]" id="warpcache_enabler_excludes" value="<?php echo $options['excludes']; ?>" size="64" class="regular-text code" />
									<?php _e("Default: <code>.php</code>", "cdn"); ?>
								</label>

								<p class="description">
									<?php _e("Enter the exclusions separated by", "cdn"); ?> <code>,</code>
								</p>
							</fieldset>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row">
							<?php _e("Relative Path", "cdn"); ?>
						</th>
						<td>
							<fieldset>
								<label for="warpcache_enabler_relative">
									<input type="checkbox" name="warpcache_enabler[relative]" id="warpcache_enabler_relative" value="1" <?php checked(1, $options['relative']) ?> />
									<?php _e("Enable CDN for relative paths (default: enabled).", "cdn"); ?>
								</label>

								<p class="description">
									<?php _e("", "cdn"); ?>
								</p>
							</fieldset>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row">
							<?php _e("CDN HTTPS", "cdn"); ?>
						</th>
						<td>
							<fieldset>
								<label for="warpcache_enabler_https">
									<input type="checkbox" name="warpcache_enabler[https]" id="warpcache_enabler_https" value="1" <?php checked(1, $options['https']) ?> />
									<?php _e("Enable CDN for HTTPS connections (default: disabled).", "cdn"); ?>
								</label>

								<p class="description">
									<?php _e("", "cdn"); ?>
								</p>
							</fieldset>
						</td>
					</tr>
				</table>

				<?php submit_button() ?>
			</form>
		</div><?php
	}
}

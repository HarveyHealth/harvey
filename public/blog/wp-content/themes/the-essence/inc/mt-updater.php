<?php
/**
 * Easy Digital Downloads Theme Updater
 */

define( 'MT_UPDATES_REMOTE_API_URL', 'http://meridianthemes.net' );
define( 'MT_UPDATES_ITEM_NAME', 'The Essence' );
define( 'MT_UPDATES_THEME_SLUG', 'the-essence' );
define( 'MT_UPDATES_AUTHOR', 'MeridianThemes' );
define( 'MT_UPDATES_DOWNLOAD_ID', '31' );

// Includes the files needed for the theme updater
if ( !class_exists( 'The_Essence_Updater_Admin' ) ) {
	include( get_template_directory() . '/inc/mt-updater-admin.php' );
}

// Loads the updater classes
$updater = new The_Essence_Updater_Admin(

	// Config settings
	$config = array(
		'remote_api_url' => MT_UPDATES_REMOTE_API_URL,
		'item_name'      => MT_UPDATES_ITEM_NAME,
		'theme_slug'     => MT_UPDATES_THEME_SLUG,
		'version'        => THE_ESSENCE_THEME_VER,
		'author'         => MT_UPDATES_AUTHOR,
		'download_id'    => MT_UPDATES_DOWNLOAD_ID,
		'renew_url'      => '',
		'beta'           => false,
	),

	// Strings
	$strings = array(
		'theme-license'             => esc_html__( 'Theme License', 'the-essence' ),
		'enter-key'                 => esc_html__( 'Enter your theme license key.', 'the-essence' ),
		'license-key'               => esc_html__( 'License Key', 'the-essence' ),
		'license-action'            => esc_html__( 'License Action', 'the-essence' ),
		'deactivate-license'        => esc_html__( 'Deactivate License', 'the-essence' ),
		'activate-license'          => esc_html__( 'Activate License', 'the-essence' ),
		'status-unknown'            => esc_html__( 'License status is unknown.', 'the-essence' ),
		'renew'                     => esc_html__( 'Renew?', 'the-essence' ),
		'unlimited'                 => esc_html__( 'unlimited', 'the-essence' ),
		'license-key-is-active'     => esc_html__( 'License key is active.', 'the-essence' ),
		'expires%s'                 => esc_html__( 'Expires %s.', 'the-essence' ),
		'expires-never'             => esc_html__( 'Lifetime License.', 'the-essence' ),
		'%1$s/%2$-sites'            => esc_html__( 'You have %1$s / %2$s sites activated.', 'the-essence' ),
		'license-key-expired-%s'    => esc_html__( 'License key expired %s.', 'the-essence' ),
		'license-key-expired'       => esc_html__( 'License key has expired.', 'the-essence' ),
		'license-keys-do-not-match' => esc_html__( 'License keys do not match.', 'the-essence' ),
		'license-is-inactive'       => esc_html__( 'License is inactive.', 'the-essence' ),
		'license-key-is-disabled'   => esc_html__( 'License key is disabled.', 'the-essence' ),
		'site-is-inactive'          => esc_html__( 'Site is inactive.', 'the-essence' ),
		'license-status-unknown'    => esc_html__( 'License status is unknown.', 'the-essence' ),
		'update-notice'             => esc_html__( "Updating this theme will lose any customizations you have made. 'Cancel' to stop, 'OK' to update.", 'the-essence' ),
		'update-available'          => '<strong>%1$s %2$s</strong> is available. <a href="%3$s" class="thickbox" title="%4s">Check out what\'s new</a> or <a href="%5$s"%6$s>update now</a>.',
	)

);

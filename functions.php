<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'CREATORCENTRAL_PLUGIN_admin_enqueue_script' ) ) {
	function CREATORCENTRAL_PLUGIN_admin_enqueue_script() {
		wp_enqueue_style( 'buddyboss-addon-admin-css', plugin_dir_url( __FILE__ ) . 'style.css' );
	}

	add_action( 'admin_enqueue_scripts', 'CREATORCENTRAL_PLUGIN_admin_enqueue_script' );
}

if ( ! function_exists( 'CREATORCENTRAL_PLUGIN_get_settings_sections' ) ) {
	function CREATORCENTRAL_PLUGIN_get_settings_sections() {

		$settings = array(
			'CREATORCENTRAL_PLUGIN_settings_section' => array(
				'page'  => 'addon',
				'title' => __( 'BuddyBoss Creator Central Addon Settings', 'buddyboss-creator-central-addon' ),
			),
		);

		return (array) apply_filters( 'CREATORCENTRAL_PLUGIN_get_settings_sections', $settings );
	}
}

if ( ! function_exists( 'CREATORCENTRAL_PLUGIN_get_settings_fields_for_section' ) ) {
	function CREATORCENTRAL_PLUGIN_get_settings_fields_for_section( $section_id = '' ) {

		// Bail if section is empty
		if ( empty( $section_id ) ) {
			return false;
		}

		$fields = CREATORCENTRAL_PLUGIN_get_settings_fields();
		$retval = isset( $fields[ $section_id ] ) ? $fields[ $section_id ] : false;

		return (array) apply_filters( 'CREATORCENTRAL_PLUGIN_get_settings_fields_for_section', $retval, $section_id );
	}
}

if ( ! function_exists( 'CREATORCENTRAL_PLUGIN_get_settings_fields' ) ) {
	function CREATORCENTRAL_PLUGIN_get_settings_fields() {

		$fields = array();

		$fields['CREATORCENTRAL_PLUGIN_settings_section'] = array(

			'CREATORCENTRAL_PLUGIN_field' => array(
				'title'             => __( 'BuddyBoss Creator Central Addon', 'buddyboss-creator-central-addon' ),
				'callback'          => 'CREATORCENTRAL_PLUGIN_settings_callback_field',
				'sanitize_callback' => 'absint',
				'args'              => array(),
			),

		);

		return (array) apply_filters( 'CREATORCENTRAL_PLUGIN_get_settings_fields', $fields );
	}
}

if ( ! function_exists( 'CREATORCENTRAL_PLUGIN_settings_callback_field' ) ) {
	function CREATORCENTRAL_PLUGIN_settings_callback_field() {
		?>
        <input name="CREATORCENTRAL_PLUGIN_field"
               id="CREATORCENTRAL_PLUGIN_field"
               type="checkbox"
               value="1"
			<?php checked( CREATORCENTRAL_PLUGIN_is_addon_field_enabled() ); ?>
        />
        <label for="CREATORCENTRAL_PLUGIN_field">
			<?php _e( 'Enable this option', 'buddyboss-creator-central-addon' ); ?>
        </label>
		<?php
	}
}

if ( ! function_exists( 'CREATORCENTRAL_PLUGIN_is_addon_field_enabled' ) ) {
	function CREATORCENTRAL_PLUGIN_is_addon_field_enabled( $default = 1 ) {
		return (bool) apply_filters( 'CREATORCENTRAL_PLUGIN_is_addon_field_enabled', (bool) get_option( 'CREATORCENTRAL_PLUGIN_field', $default ) );
	}
}

/***************************** Add section in current settings ***************************************/

/**
 * Register fields for settings hooks
 * bp_admin_setting_general_register_fields
 * bp_admin_setting_xprofile_register_fields
 * bp_admin_setting_groups_register_fields
 * bp_admin_setting_forums_register_fields
 * bp_admin_setting_activity_register_fields
 * bp_admin_setting_media_register_fields
 * bp_admin_setting_friends_register_fields
 * bp_admin_setting_invites_register_fields
 * bp_admin_setting_search_register_fields
 */
if ( ! function_exists( 'CREATORCENTRAL_PLUGIN_bp_admin_setting_general_register_fields' ) ) {
    function CREATORCENTRAL_PLUGIN_bp_admin_setting_general_register_fields( $setting ) {
	    // Main General Settings Section
	    $setting->add_section( 'CREATORCENTRAL_PLUGIN_addon', __( 'Add-on Settings', 'buddyboss-creator-central-addon' ) );

	    $args          = array();
	    $setting->add_field( 'bp-enable-my-addon', __( 'My Field', 'buddyboss-creator-central-addon' ), 'CREATORCENTRAL_PLUGIN_admin_general_setting_callback_my_addon', 'intval', $args );
    }

	add_action( 'bp_admin_setting_general_register_fields', 'CREATORCENTRAL_PLUGIN_bp_admin_setting_general_register_fields' );
}

if ( ! function_exists( 'CREATORCENTRAL_PLUGIN_admin_general_setting_callback_my_addon' ) ) {
	function CREATORCENTRAL_PLUGIN_admin_general_setting_callback_my_addon() {
		?>
        <input id="bp-enable-my-addon" name="bp-enable-my-addon" type="checkbox"
               value="1" <?php checked( CREATORCENTRAL_PLUGIN_enable_my_addon() ); ?> />
        <label for="bp-enable-my-addon"><?php _e( 'Enable my option', 'buddyboss-creator-central-addon' ); ?></label>
		<?php
	}
}

if ( ! function_exists( 'CREATORCENTRAL_PLUGIN_enable_my_addon' ) ) {
	function CREATORCENTRAL_PLUGIN_enable_my_addon( $default = false ) {
		return (bool) apply_filters( 'CREATORCENTRAL_PLUGIN_enable_my_addon', (bool) bp_get_option( 'bp-enable-my-addon', $default ) );
	}
}


/**************************************** MY PLUGIN INTEGRATION ************************************/

/**
 * Set up the my plugin integration.
 */
function CREATORCENTRAL_PLUGIN_register_integration() {
	require_once dirname( __FILE__ ) . '/integration/buddyboss-integration.php';
	buddypress()->integrations['addon'] = new CREATORCENTRAL_PLUGIN_BuddyBoss_Integration();
}
add_action( 'bp_setup_integrations', 'CREATORCENTRAL_PLUGIN_register_integration' );

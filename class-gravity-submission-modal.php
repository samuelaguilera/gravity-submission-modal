<?php
/**
 * Gravity Submission Modal
 *
 * @since     1.0
 * @package Gravity Submission Modal
 * @author    Samuel Aguilera
 * @copyright Copyright (c) 2022 Samuel Aguilera
 */

defined( 'ABSPATH' ) || die();

// Include the Gravity Forms Add-On Framework.
GFForms::include_addon_framework();

/**
 * Class Gravity_Submission_Modal
 *
 * Primary class to manage the Gravity Submission Modal Add-On.
 *
 * @since 1.0
 *
 * @uses GFAddOn
 */
class Gravity_Submission_Modal extends GFAddOn {

	/**
	 * Contains an instance of this class, if available.
	 *
	 * @since  1.0
	 * @var    Gravity_Submission_Modal $_instance If available, contains an instance of this class
	 */
	private static $_instance = null;

	/**
	 * Defines the version of the Gravity Submission Modal Add-On.
	 *
	 * @since  1.0
	 * @var    string $_version Contains the version.
	 */
	protected $_version = GRAVITY_SUBMISSION_MODAL_VERSION;

	/**
	 * Defines the minimum Gravity Forms version required.
	 *
	 * @since  1.0
	 * @var    string $_min_gravityforms_version The minimum version required.
	 */
	protected $_min_gravityforms_version = GRAVITY_SUBMISSION_MODAL_MIN_GF_VERSION;

	/**
	 * Defines the plugin slug.
	 *
	 * @since  1.0
	 * @var    string $_slug The slug used for this plugin.
	 */
	protected $_slug = 'gravity-submission-modal';

	/**
	 * Defines the main plugin file.
	 *
	 * @since  1.0
	 * @var    string $_path The path to the main plugin file, relative to the plugins folder.
	 */
	protected $_path = 'gravity-submission-modal/gravity-submission-modal.php';

	/**
	 * Defines the full path to this class file.
	 *
	 * @since  1.0
	 * @var    string $_full_path The full path.
	 */
	protected $_full_path = __FILE__;

	/**
	 * Defines the URL where this add-on can be found.
	 *
	 * @since  1.0
	 * @var    string The URL of the Add-On.
	 */
	protected $_url = 'https://www.samuelaguilera.com';

	/**
	 * Defines the title of this add-on.
	 *
	 * @since  1.0
	 * @var    string $_title The title of the add-on.
	 */
	protected $_title = 'Gravity Submission Modal Add-On';

	/**
	 * Defines the short title of the add-on.
	 *
	 * @since  1.0
	 * @var    string $_short_title The short title.
	 */
	protected $_short_title = 'Submission Modal';

	/**
	 * Defines the capabilities needed for the Gravity Submission Modal Add-On
	 *
	 * @since  1.0
	 * @access protected
	 * @var    array $_capabilities The capabilities needed for the Add-On
	 */
	protected $_capabilities = array( 'gravity-submission-modal', 'gravity-submission-modal_uninstall' );

	/**
	 * Defines the capability needed to uninstall the Add-On.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    string $_capabilities_uninstall The capability needed to uninstall the Add-On.
	 */
	protected $_capabilities_uninstall = 'gravity-submission-modal_uninstall';

	/**
	 * Returns an instance of this class, and stores it in the $_instance property.
	 *
	 * @since  1.0
	 *
	 * @return Gravity_Submission_Modal $_instance An instance of the Gravity Submission Modal class
	 */
	public static function get_instance() {

		if ( null === self::$_instance ) {
			self::$_instance = new Gravity_Submission_Modal();
		}

		return self::$_instance;

	}

	/**
	 * Register initialization hooks.
	 *
	 * @since  1.0
	 */
	public function init() {
		parent::init();
		add_filter( 'gform_get_form_filter', array( $this, 'add_modal_html' ), 10, 2 );
		add_filter( 'gform_submit_button', array( $this, 'add_modal_onclick' ), 10, 2 );
		add_filter( 'gform_next_button', array( $this, 'add_modal_onclick' ), 10, 2 );
		add_filter( 'gform_previous_button', array( $this, 'add_modal_onclick' ), 10, 2 );
	}


	/**
	 * Register scripts.
	 *
	 * @return array
	 */
	public function scripts() {

		$modal_js = 'https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.2/jquery.modal.min.js';

		$scripts = array(
			array(
				'handle'    => 'gsm_modal_js',
				'src'       => $modal_js,
				'version'   => $this->_version,
				'deps'      => array( 'jquery' ),
				'in_footer' => true,
				'enqueue'   => array(
					array( $this, 'frontend_script_callback' ),
				),
			),
		);

		return array_merge( parent::scripts(), $scripts );

	}

	/**
	 * Register styles.
	 *
	 * @return array
	 */
	public function styles() {

		$select_js_css = 'https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.2/jquery.modal.min.css';

		$styles = array(
			array(
				'handle'  => 'gsm_modal_css',
				'src'     => $select_js_css,
				'version' => $this->_version,
				'enqueue' => array(
					array( $this, 'frontend_script_callback' ),
				),
			),
			array(
				'handle'  => 'gsm_spinner_css',
				'src'     => $this->get_base_url() . '/css/gravity-submission-modal.min.css',
				'version' => $this->_version,
				'enqueue' => array(
					array( $this, 'frontend_script_callback' ),
				),
			),
		);

		return array_merge( parent::styles(), $styles );

	}

	// # FORM SETTINGS

	/**
	 * The settings page icon.
	 *
	 * @since 1.0
	 * @return string
	 */
	public function get_menu_icon() {
		return 'dashicons-update-alt';
	}

	/**
	 * Register a form settings tab.
	 *
	 * @param array $form The form data.
	 *
	 * @return array
	 */
	public function form_settings_fields( $form ) {
		return array(
			array(
				'title'  => 'Gravity Submission Modal Settings',
				'fields' => array(
					array(
						'type'    => 'checkbox',
						'name'    => 'gsm-enable-modal',
						'choices' => array(
							array(
								'name'          => 'gsm-enable-modal',
								'label'         => __( 'Enable Submission Modal', 'gravity-submission-modal' ),
								'default_value' => 0,
							),
						),
						'tooltip' => 'The change will take effect after the next page load.',
					),
				),
			),
		);
	}

	// # HELPER METHODS ------------------------------------------------------------------------------------------------

	/**
	 * Callback to determine whether to render the frontend modal resources.
	 *
	 * @param array $form The form array.
	 *
	 * @return bool
	 */
	public function frontend_script_callback( $form ) {
		return ! empty( $form['id'] ) && '1' === rgar( $this->get_form_settings( $form ), 'gsm-enable-modal' );
	}

	/**
	 * Adds the modal HTML after the form.
	 *
	 * @param string $form_string The form markup, including the init scripts (unless the gform_init_scripts_footer filter was used to move them to the footer).
	 * @param array  $form The form currently being processed.
	 *
	 * @return string
	 */
	public function add_modal_html( $form_string, $form ) {
		if ( $this->frontend_script_callback( $form ) ) {
			$form_string .= '<div class="modal" id="gsm-modal"><div id="gsm-spinner"></div><div id="gsm-txt"><h3 id="gsm-wait-text">Please wait...</h3></div></div>';
			$this->log_debug( __METHOD__ . '(): Modal HTML added to form.' );
		}

		return $form_string;
	}

	/**
	 * Adds JS call for the modal to the submit button.
	 *
	 * @param string $button The string containing the <input> tag to be filtered.
	 * @param array  $form The form currently being processed.
	 *
	 * @return string
	 */
	public function add_modal_onclick( $button, $form ) {
		if ( $this->frontend_script_callback( $form ) ) {
			$this->log_debug( __METHOD__ . '(): Adding action to submit button.' );
			$dom = new DOMDocument();
			$dom->loadHTML( '<?xml encoding="utf-8" ?>' . $button );
			$input    = $dom->getElementsByTagName( 'input' )->item( 0 );
			$onclick  = $input->getAttribute( 'onclick' );
			$onclick .= ' jQuery("#gsm-modal").modal({ escapeClose: false, clickClose: false,showClose: false,});';
			$input->setAttribute( 'onclick', $onclick );
			return $dom->saveHtml( $input );
		}
		return $button;
	}

}

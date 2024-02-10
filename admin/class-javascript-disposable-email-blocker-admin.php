<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://tomba.io/
 * @since      1.0.0
 *
 * @package    Javascript_Disposable_Email_Blocker
 * @subpackage Javascript_Disposable_Email_Blocker/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Javascript_Disposable_Email_Blocker
 * @subpackage Javascript_Disposable_Email_Blocker/admin
 * @author     tomba.io <b.mohamed@tomba.io>
 */

class Javascript_Disposable_Email_Blocker_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function the_form_response()
    {
        if (isset($_POST['reset_options']) && wp_verify_nonce($_POST['reset_options_nonce'], 'reset_options_action')) {
            activate_javascript_disposable_email_blocker();
            // Add success message
            add_settings_error('reset_options_success', 'reset_options_success', __('Settings reset successfully!', $this->plugin_name), 'updated');
        }
    }
    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Javascript_Disposable_Email_Blocker_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Javascript_Disposable_Email_Blocker_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/javascript-disposable-email-blocker-admin.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Javascript_Disposable_Email_Blocker_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Javascript_Disposable_Email_Blocker_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/disposable-email-blocker.min.js', [], $this->version, false);
    }

    /**
     * Add a new menu item in the WordPress dashboard
     *
     * @since    1.0.0
     */
    public function register_settings_page()
    {

        add_submenu_page(
            'options-general.php',                             // parent slug
            __('Javascript Disposable Email Blocker', 'javascript-disposable-email-blocker'),      // page title
            __('Javascript Disposable Email Blocker', 'javascript-disposable-email-blocker'),      // menu title
            'manage_options',                        // capability
            'javascript-disposable-email-blocker',                           // menu_slug
            array($this, 'display_settings_page')  // callable function
        );
    }
    /**
     * Display the settings page content for the page we have created.
     *
     * @since    1.0.0
     */
    public function display_settings_page()
    {
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/javascript-disposable-email-blocker-admin-display.php';
    }

    public function javascript_disposable_email_blocker_disposable_message_input($args)
    {
        $field_id = $args['id'];
        $field_name = $args['name'];
        $field_value = $args['value'];
?>
        <input class="regular-text" type="text" name="<?php echo esc_attr($field_name); ?>" id="<?php echo esc_attr($field_id); ?>" value="<?php echo esc_attr($field_value); ?>" />


    <?php
    }
    public function javascript_disposable_email_blocker_webmail_message_input($args)
    {

        $field_id = $args['id'];
        $field_name = $args['name'];
        $field_value = $args['value'];
    ?>
        <input class="regular-text" type="text" name="<?php echo esc_attr($field_name); ?>" id="<?php echo esc_attr($field_id); ?>" value="<?php echo esc_attr($field_value); ?>" />

    <?php
    }

    public function javascript_disposable_email_blocker_webmail_block_checkbox($args)
    {
        $field_id = $args['id'];
        $field_name = $args['name'];
        $field_description = $args['description'];
        $checked = $args['checked'];
    ?>

        <label>
            <input type="checkbox" id="<?php echo esc_attr($field_id); ?>" name="<?php echo esc_attr($field_name); ?>" value="on" <?php echo checked('on', $checked, false); ?> />
            <span class="description"><?php echo esc_html($field_description); ?></span>
        </label>

<?php
    }

    /**
     * Registers settings fields with WordPress
     */
    public function register_fields()
    {
        // add_settings_field( $id, $title, $callback, $menu_slug, $section, $args );

        $options = get_option('javascript_disposable_email_blocker');
        $disposable_message = $options['disposable_message'];
        $webmail_message = $options['webmail_message'];
        $webmail_checkbox = isset($options['webmail_block']) ? $options['webmail_block'] : 'off';

        add_settings_field(
            'javascript_disposable_email_blocker_disposable_message',
            apply_filters($this->plugin_name . 'label-disposable', esc_html__('Disposable Error Message', $this->plugin_name)),
            array($this, 'javascript_disposable_email_blocker_disposable_message_input'),
            $this->plugin_name,
            'javascript_disposable_email_blocker',
            array(
                'description'   => 'This message displays on the input if the email is Disposable.',
                'name'          => 'javascript_disposable_email_blocker[disposable_message]',
                'id'            => 'disposable_message',
                'value'             => $disposable_message,
            )
        );

        add_settings_field(
            'javascript_disposable_email_blocker_webmail_message',
            apply_filters($this->plugin_name . 'label-webmail', esc_html__('Webmail Error Message', $this->plugin_name)),
            array($this, 'javascript_disposable_email_blocker_webmail_message_input'),
            $this->plugin_name,
            'javascript_disposable_email_blocker',
            array(
                'description'   => 'This message displays on the input if the email is Webmail.',
                'name'          => 'javascript_disposable_email_blocker[webmail_message]',
                'id'            => 'webmail_message',
                'value'             => $webmail_message,
            )
        );

        add_settings_field(
            'javascript_disposable_email_blocker_webmail_block',
            apply_filters($this->plugin_name . 'label-block', esc_html__('Webmail Block', $this->plugin_name)),
            array($this, 'javascript_disposable_email_blocker_webmail_block_checkbox'),
            $this->plugin_name,
            'javascript_disposable_email_blocker',
            array(
                'description'   => 'This Detect and Block webmail emails.',
                'name'          => 'javascript_disposable_email_blocker[webmail_block]',
                'id'            => 'webmail_block',
                'checked'         => $webmail_checkbox,
            )
        );
    }
    /**
     * Registers settings sections with WordPress
     *
     * @since    1.0.0
     */
    public function register_sections()
    {

        // add_settings_section( $id, $title, $callback, $menu_slug );
        add_settings_section(
            'javascript_disposable_email_blocker',
            apply_filters($this->plugin_name . 'section-title-options', esc_html__('Configuration', $this->plugin_name)),
            null,
            $this->plugin_name
        );
    }
    /**
     * Register the settings for our settings page.
     *
     * @since    1.0.0
     */
    public function register_settings()
    {

        register_setting(
            'javascript_disposable_email_blocker',
            'javascript_disposable_email_blocker',
        );
    }
    public function validate_options($input)
    {
    }
}

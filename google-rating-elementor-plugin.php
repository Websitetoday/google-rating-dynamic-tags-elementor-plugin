<?php
/**
 * Plugin Name:     Google Rating Dynamic Tags Elementor
 * Plugin URI:      https://github.com/Websitetoday/google-rating-dynamic-tags-elementor-plugin
 * GitHub Plugin URI: https://github.com/Websitetoday/google-rating-dynamic-tags-elementor-plugin
 * Description:     Toon eenvoudig de Google Bedrijfsbeoordelingen (gemiddelde score, aantal reviews en link naar reviews) als Elementor Dynamic Tag en via shortcode. Volledig meetbaar en stijlbaar.
 * Version:         3.1.0
 * Author:          Websitetoday.nl
 * Author URI:      https://www.websitetoday.nl/
 * Text Domain:     gre
 * Domain Path:     /languages
 * GitHub Branch:   main
 * Requires at least: 5.0
 * Tested up to:    6.5
 * Requires PHP:    7.4
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// --------------------------
// Plaats je 'use'-statements BOVEN de rest van de code!
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

// --------------------------
//  Plugin Update Checker v5.5 Integration (YahnisElsts PUC)
// --------------------------
if ( file_exists( __DIR__ . '/plugin-update-checker/plugin-update-checker.php' ) ) {
    require_once __DIR__ . '/plugin-update-checker/plugin-update-checker.php';
    if ( ! class_exists( 'Parsedown' ) && file_exists( __DIR__ . '/plugin-update-checker/Puc/v5p5/Parsedown.php' ) ) {
        require_once __DIR__ . '/plugin-update-checker/Puc/v5p5/Parsedown.php';
    }

    $updateChecker = PucFactory::buildUpdateChecker(
        'https://github.com/Websitetoday/google-rating-dynamic-tags-elementor-plugin/',
        __FILE__,
        'google-rating-dynamic-tags-elementor-plugin'
    );
    $updateChecker->setBranch( 'main' );

    add_filter( 'plugins_api', function( $res, $action, $args ) {
        if (
            'plugin_information' === $action
            && is_object( $res )
            && ! empty( $args->slug )
            && 'google-rating-dynamic-tags-elementor-plugin' === $args->slug
        ) {
            $res->icons = [
                '1x' => plugin_dir_url( __FILE__ ) . 'icon-128x128.png',
                '2x' => plugin_dir_url( __FILE__ ) . 'icon-256x256.png',
            ];
        }
        return $res;
    }, 10, 3 );
}


// ──────────────
//  Constants
// ──────────────
define('GRE_OPT_API_KEY',   'gre_api_key');
define('GRE_OPT_PLACE_ID',  'gre_place_id');
define('GRE_OPT_LAST_DATA', 'gre_last_data');

// ──────────────
//  Render functies
// ──────────────
if ( ! function_exists('gre_api_key_render') ) {
    function gre_api_key_render() {
        $val = get_option( GRE_OPT_API_KEY, '' );
        printf(
            '<input type="password" id="%1$s" name="%1$s" value="%2$s" class="regular-text" />'
            . ' <span class="toggle-visibility dashicons dashicons-visibility" data-field="%1$s" style="cursor:pointer; margin-left:4px;"></span>'
            . ' <span id="gre-api-status" class="gre-status-icon dashicons"></span>',
            esc_attr( GRE_OPT_API_KEY ),
            esc_attr( $val )
        );
    }
}
if ( ! function_exists('gre_place_id_render') ) {
    function gre_place_id_render() {
        $val = get_option( GRE_OPT_PLACE_ID, '' );
        printf(
            '<input type="password" id="%1$s" name="%1$s" value="%2$s" class="regular-text" />'
            . ' <span class="toggle-visibility dashicons dashicons-visibility" data-field="%1$s" style="cursor:pointer; margin-left:4px;"></span>'
            . ' <span id="gre-place-status" class="gre-status-icon dashicons"></span>',
            esc_attr( GRE_OPT_PLACE_ID ),
            esc_attr( $val )
        );
        // Toon bedrijfsnaam indien bekend
        $last = get_option( GRE_OPT_LAST_DATA, false );
        if ( is_array( $last ) && ! empty( $last['name'] ) ) {
            printf(
                '<p class="description" style="margin-top:8px;">%s <strong>%s</strong></p>',
                esc_html__( 'Verbonden met:', 'gre' ),
                esc_html( $last['name'] )
            );
        }
    }
}
if ( ! function_exists('gre_enable_shortcode_render') ) {
    function gre_enable_shortcode_render() {
        $val = get_option( 'gre_enable_shortcode', 1 );
        printf(
            '<input type="checkbox" id="gre_enable_shortcode" name="gre_enable_shortcode" value="1" %s /> <label for="gre_enable_shortcode">%s</label>',
            checked( 1, $val, false ),
            esc_html__( 'Enable [google_rating] shortcode', 'gre' )
        );
    }
}
if ( ! function_exists('gre_test_connection_render') ) {
    function gre_test_connection_render() {
        echo '<button type="button" class="button button-secondary" id="gre-test-connection-button">'
             . esc_html__( 'Controleer verbinding', 'gre' )
             . '</button>';
        echo '<span id="gre-test-connection-result" style="margin-left:12px; vertical-align:middle;"></span>';
    }
}
if ( ! function_exists('gre_force_refresh_render') ) {
    function gre_force_refresh_render() {
        echo '<button type="button" class="button button-secondary" id="gre-refresh-data-button">'
             . esc_html__( 'Ververs data', 'gre' )
             . '</button>';
        echo '<span id="gre-refresh-data-result" style="margin-left:12px; vertical-align:middle;"></span>';
    }
}

// ──────────────
//  Includes
// ──────────────
require_once __DIR__ . '/includes/admin/page-settings.php';
require_once __DIR__ . '/includes/admin/page-explainer.php';
require_once __DIR__ . '/includes/admin/page-changelog.php';

// ──────────────
//  Admin menu + tabs
// ──────────────
if ( ! function_exists('gre_options_page') ) {
    function gre_options_page() {
        $active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'settings';
        echo '<div class="wrap">';
        echo '<h1>' . esc_html__('Google Rating Settings', 'gre') . '</h1>';
        echo '<h2 class="nav-tab-wrapper">';
        echo '<a href="?page=gre_options&tab=settings" class="nav-tab' . ($active_tab == 'settings' ? ' nav-tab-active' : '') . '">' . esc_html__('Instellingen', 'gre') . '</a>';
        echo '<a href="?page=gre_options&tab=explainer" class="nav-tab' . ($active_tab == 'explainer' ? ' nav-tab-active' : '') . '">' . esc_html__('Uitleg', 'gre') . '</a>';
        echo '<a href="?page=gre_options&tab=changelog" class="nav-tab' . ($active_tab == 'changelog' ? ' nav-tab-active' : '') . '">' . esc_html__('Changelog', 'gre') . '</a>';
        echo '</h2>';
        switch ($active_tab) {
            case 'explainer':
                if(function_exists('gre_render_explainer_page')) gre_render_explainer_page();
                break;
            case 'changelog':
                if(function_exists('gre_render_changelog_page')) gre_render_changelog_page();
                break;
            case 'settings':
            default:
                if(function_exists('gre_render_settings_page')) gre_render_settings_page();
                break;
        }
        echo '</div>';
    }
}

if ( ! function_exists('gre_add_admin_menu') ) {
    function gre_add_admin_menu() {
        add_menu_page(
            __('Google Rating', 'gre'),
            __('Google Rating', 'gre'),
            'manage_options',
            'gre_options',
            'gre_options_page',
            'dashicons-star-filled',
            80
        );
    }
}
add_action('admin_menu', 'gre_add_admin_menu');

if ( ! function_exists('gre_settings_init') ) {
    function gre_settings_init() {
        register_setting( 'gre_api_settings', GRE_OPT_API_KEY, array(
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        register_setting( 'gre_api_settings', GRE_OPT_PLACE_ID, array(
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        register_setting( 'gre_plugin_settings', 'gre_enable_shortcode', array(
            'sanitize_callback' => 'absint',
            'default' => 1,
        ) );
    }
}
add_action('admin_init', 'gre_settings_init');

if ( ! function_exists('gre_admin_notices') ) {
    function gre_admin_notices() {
        if ( ! current_user_can( 'manage_options' ) ) return;
        if ( empty( get_option( GRE_OPT_API_KEY ) ) || empty( get_option( GRE_OPT_PLACE_ID ) ) ) {
            echo '<div class="notice notice-error"><p>'
                . esc_html__( 'Google Rating: Vul API Key en Place ID in instellingen.', 'gre' )
                . '</p></div>';
        }
    }
}
add_action('admin_notices', 'gre_admin_notices');

// ──────────────
//  AJAX Test verbinding handler
// ──────────────
add_action( 'wp_ajax_gre_test_connection', 'gre_test_connection_callback' );
if ( ! function_exists('gre_test_connection_callback') ) {
    function gre_test_connection_callback() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( __( 'Geen toestemming.', 'gre' ) );
        }
        $api_key  = isset($_POST['api_key']) ? sanitize_text_field( $_POST['api_key'] ) : '';
        $place_id = isset($_POST['place_id']) ? sanitize_text_field( $_POST['place_id'] ) : '';
        if ( empty( $api_key ) || empty( $place_id ) ) {
            wp_send_json_error( __( 'Vul API Key en Place ID in.', 'gre' ) );
        }

        $url = sprintf(
            'https://maps.googleapis.com/maps/api/place/details/json?place_id=%s&fields=name,rating,user_ratings_total,opening_hours&key=%s',
            urlencode( $place_id ),
            $api_key
        );
        $response = wp_remote_get( $url, array( 'timeout' => 10 ) );
        if ( is_wp_error( $response ) ) {
            wp_send_json_error( sprintf(
                __( 'HTTP-fout: %s', 'gre' ),
                $response->get_error_message()
            ) );
        }

        $json          = json_decode( wp_remote_retrieve_body( $response ), true );
        $status        = $json['status']        ?? 'UNKNOWN';
        $error_message = $json['error_message'] ?? '';

        if ( empty( $json['result'] ) || $status !== 'OK' ) {
            wp_send_json_error( sprintf(
                __( 'Status: %s. Bericht: %s', 'gre' ),
                esc_html( $status ),
                esc_html( $error_message ?: __( 'Geen extra informatie.', 'gre' ) )
            ) );
        }

        // Sla bedrijfsnaam op voor visuele feedback!
        update_option( GRE_OPT_LAST_DATA, $json['result'] );

        wp_send_json_success( sprintf(
            __( 'Verbonden! Place ID gevonden: %s (%s)', 'gre' ),
            esc_html( $json['result']['place_id'] ),
            esc_html( $json['result']['name'] )
        ) );
    }
}

// ──────────────
//  CRON & DATA
// ──────────────
register_activation_hook(__FILE__, 'gre_activation');
function gre_activation() {
    if (!wp_next_scheduled('gre_weekly_update_event')) {
        wp_schedule_event(time(), 'weekly', 'gre_weekly_update_event');
    }
}
register_deactivation_hook(__FILE__, 'gre_deactivation');
function gre_deactivation() {
    wp_clear_scheduled_hook('gre_weekly_update_event');
}
add_filter('cron_schedules', function ($schedules) {
    $schedules['weekly'] = array(
        'interval' => WEEK_IN_SECONDS,
        'display'  => __('Elke week')
    );
    return $schedules;
});
add_action('gre_weekly_update_event', 'gre_do_google_place_fetch');

function gre_do_google_place_fetch() {
    $api_key  = get_option('gre_api_key');
    $place_id = get_option('gre_place_id');
    if (empty($api_key) || empty($place_id)) return false;
    if (get_transient('gre_cached_place_data') !== false) return true;
    $response = wp_remote_get(sprintf(
        'https://maps.googleapis.com/maps/api/place/details/json?place_id=%s&fields=name,rating,user_ratings_total,opening_hours&language=nl&key=%s',
        urlencode($place_id),
        $api_key
    ), array('timeout' => 10));
    if (is_wp_error($response)) return false;
    $json = json_decode(wp_remote_retrieve_body($response), true);
    if (empty($json['result'])) return false;
    update_option('gre_last_data', $json['result']);
    set_transient('gre_cached_place_data', $json['result'], WEEK_IN_SECONDS);
    return true;
}
function gre_fetch_google_place_data() {
    $cached = get_transient('gre_cached_place_data');
    if ($cached !== false) return $cached;
    return get_option('gre_last_data') ?: false;
}

// ──────────────
//  AJAX force refresh
// ──────────────
add_action('wp_ajax_gre_force_refresh', 'gre_force_refresh_callback');
function gre_force_refresh_callback() {
    if (!current_user_can('manage_options')) wp_send_json_error(__('Geen toestemming.', 'gre'));
    check_ajax_referer('gre_force_refresh', 'security');
    $fetched = gre_do_google_place_fetch();
    if (!$fetched) wp_send_json_error(__('Vernieuwen mislukt.', 'gre'));
    wp_send_json_success(__('Data ververst!', 'gre'));
}

// ──────────────
//  SHORTCODE
// ──────────────
function gre_shortcode_google_rating($atts) {
    $atts = shortcode_atts(array('field' => 'rating_star'), $atts, 'google_rating');
    $data = gre_fetch_google_place_data();
    if (!$data) return esc_html__('Geen data beschikbaar.', 'gre');
    $rating = floatval($data['rating']);
    $count  = intval($data['user_ratings_total']);
    switch ($atts['field']) {
        case 'rating_number':
            return esc_html($rating);
        case 'rating_star':
            return esc_html(sprintf('%.1f ★', $rating));
        case 'count_number':
            return esc_html($count);
        default:
            return sprintf('<strong>%s</strong> ★ %s reviews', esc_html(number_format_i18n($rating, 1)), esc_html($count));
    }
}
if (get_option('gre_enable_shortcode', 1)) {
    add_shortcode('google_rating', 'gre_shortcode_google_rating');
}

// ──────────────
//  ELEMENTOR DYNAMIC TAGS
// ──────────────
add_action('elementor/dynamic_tags/register_tags', function ($tags) {
    if (!class_exists('ElementorPro\\Plugin')) return;
    require_once __DIR__ . '/dynamic-tags/class-google-rating-tag.php';
    $tags->register(new \GRE\DynamicTags\Google_Rating_Tag());
}, 50);

add_action('elementor/dynamic_tags/register_tags', function ($tags) {
    if (!class_exists('ElementorPro\\Plugin')) return;
    require_once __DIR__ . '/dynamic-tags/class-google-opening-hours-tag.php';
    $tags->register(new \GRE\DynamicTags\Google_Opening_Hours_Tag());
}, 51);

// ──────────────
//  ADMIN SCRIPTS & STYLES
// ──────────────
add_action('admin_enqueue_scripts', function ($hook) {
    if ('toplevel_page_gre_options' !== $hook) return;
    wp_enqueue_style('gre-admin-style', plugin_dir_url(__FILE__) . 'assets/css/admin-style.css', array(), filemtime(plugin_dir_path(__FILE__) . 'assets/css/admin-style.css'));
    wp_enqueue_script('gre-admin-js', plugin_dir_url(__FILE__) . 'assets/js/gre-admin.js', array('jquery'), '1.2.0', true);
    wp_localize_script('gre-admin-js', 'greSettings', array(
        'ajaxUrl'      => admin_url('admin-ajax.php'),
        'apiKeyField'  => 'gre_api_key',
        'placeIdField' => 'gre_place_id',
        'refreshNonce' => wp_create_nonce('gre_force_refresh'),
    ));
});
add_action('admin_head', function () {
    echo '<style>
        .gre-status-icon { font-size: 18px; vertical-align: middle; margin-left: 6px; }
        .gre-status-icon.green { color: #28a745; }
        .gre-status-icon.red   { color: #dc3545; }
    </style>';
});

// ──────────────
//  TEXTDOMAIN LADEN
// ──────────────
add_action('plugins_loaded', function () {
    load_plugin_textdomain('gre', false, dirname(plugin_basename(__FILE__)) . '/languages/');
});

// ──────────────
//  "INSTELLINGEN" LINK OP PLUGIN-PAGINA
// ──────────────
add_filter('plugin_action_links_' . plugin_basename(__FILE__), function ($links) {
    $settings_link = '<a href="' . admin_url('admin.php?page=gre_options') . '">'
                   . esc_html__('Instellingen', 'gre')
                   . '</a>';
    array_unshift($links, $settings_link);
    return $links;
});

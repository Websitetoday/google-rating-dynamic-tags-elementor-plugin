<?php
/**
 * Plugin Name:     Google Rating Dynamic Tags Elementor
 * Plugin URI:      https://github.com/Websitetoday/google-rating-dynamic-tags-elementor-plugin
 * GitHub Plugin URI: https://github.com/Websitetoday/google-rating-dynamic-tags-elementor-plugin
 * Description:     Toon eenvoudig de Google Bedrijfsbeoordelingen (gemiddelde score, aantal reviews en link naar reviews) als Elementor Dynamic Tag en via shortcode (meetbaar en stylebaar).
 * Version:         3.0.2
 * Author:          Websitetoday.nl
 * Author URI:      https://www.websitetoday.nl/
 * Text Domain:     gre
 * Domain Path:     /languages
 * GitHub Branch:   main
 * Requires at least: 5.0
 * Tested up to:    6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// ──────────────────────────────────────────────────────────
//  Plugin Update Checker v5.5 Integration (YahnisElsts PUC)
// ──────────────────────────────────────────────────────────

require_once __DIR__ . '/plugin-update-checker/plugin-update-checker.php';
if ( ! class_exists( 'Parsedown' ) ) {
    require_once __DIR__ . '/plugin-update-checker/Puc/v5p5/Parsedown.php';
}

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

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

// ──────────────────────────────────────────────────────────
//  Schedule background fetch via WP-Cron
// ──────────────────────────────────────────────────────────

register_activation_hook( __FILE__, 'gre_activation' );
function gre_activation() {
    if ( ! wp_next_scheduled( 'gre_prefetch_event' ) ) {
        wp_schedule_single_event( time(), 'gre_prefetch_event' );
    }
}

register_deactivation_hook( __FILE__, 'gre_deactivation' );
function gre_deactivation() {
    wp_clear_scheduled_hook( 'gre_prefetch_event' );
}

add_action( 'gre_prefetch_event', 'gre_do_google_place_fetch' );
function gre_do_google_place_fetch() {
    $api_key  = get_option( GRE_OPT_API_KEY );
    $place_id = get_option( GRE_OPT_PLACE_ID );
    if ( empty( $api_key ) || empty( $place_id ) ) {
        return false;
    }

    $response = wp_remote_get( sprintf(
        'https://maps.googleapis.com/maps/api/place/details/json'
        . '?place_id=%s'
        . '&fields=name,rating,user_ratings_total,opening_hours'
        . '&language=nl'
        . '&key=%s',
        urlencode( $place_id ),
        $api_key
    ), array( 'timeout' => 10 ) );

    if ( is_wp_error( $response ) ) {
        return false;
    }

    $json = json_decode( wp_remote_retrieve_body( $response ), true );
    if ( empty( $json['result'] ) ) {
        return false;
    }

    // sla op in last_data en transient
    update_option( GRE_OPT_LAST_DATA, $json['result'] );
    $hours = absint( get_option( 'gre_cache_ttl', 12 ) );
    $ttl   = apply_filters( 'gre_transient_ttl', $hours * HOUR_IN_SECONDS );
    set_transient( 'gre_place_' . md5( $place_id ), $json['result'], $ttl );

    // plan de volgende fetch exact na TTL
    wp_schedule_single_event( time() + $ttl, 'gre_prefetch_event' );

	// **incrementeer teller**
    $count = (int) get_option( 'gre_api_call_count', 0 );
    update_option( 'gre_api_call_count', $count + 1 );
	
    return true;
}


// ──────────────────────────────────────────────────────────
//  Optie-namen
// ──────────────────────────────────────────────────────────

define( 'GRE_OPT_API_KEY',   'gre_api_key' );
define( 'GRE_OPT_PLACE_ID',  'gre_place_id' );
define( 'GRE_OPT_LAST_DATA', 'gre_last_data' );

// ──────────────────────────────────────────────────────────
//  Textdomain laden
// ──────────────────────────────────────────────────────────

add_action( 'plugins_loaded', function() {
    load_plugin_textdomain(
        'gre',
        false,
        dirname( plugin_basename( __FILE__ ) ) . '/languages/'
    );
} );

// ──────────────────────────────────────────────────────────
//  "Instellingen" link op plugin-pagina
// ──────────────────────────────────────────────────────────

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), function( $links ) {
    $settings_link = '<a href="' . admin_url( 'admin.php?page=gre_options' ) . '">'
                   . esc_html__( 'Instellingen', 'gre' )
                   . '</a>';
    array_unshift( $links, $settings_link );
    return $links;
} );

// ──────────────────────────────────────────────────────────
//  Admin-pagina’s modulair inladen
// ──────────────────────────────────────────────────────────

require_once __DIR__ . '/includes/admin/page-settings.php';
require_once __DIR__ . '/includes/admin/page-shortcode.php';
require_once __DIR__ . '/includes/admin/page-changelog.php';

// ──────────────────────────────────────────────────────────
//  1) Admin menu en settings
// ──────────────────────────────────────────────────────────

add_action( 'admin_menu',    'gre_add_admin_menu' );
add_action( 'admin_init',    'gre_settings_init' );
add_action( 'admin_notices', 'gre_admin_notices' );

function gre_add_admin_menu() {
    add_menu_page(
        __( 'Google Rating', 'gre' ),
        __( 'Google Rating', 'gre' ),
        'manage_options',
        'gre_options',
        'gre_options_page',
        'dashicons-star-filled',
        80
    );
}

function gre_settings_init() {
    register_setting( 'gre_settings', GRE_OPT_API_KEY,        array( 'sanitize_callback' => 'sanitize_text_field' ) );
    register_setting( 'gre_settings', GRE_OPT_PLACE_ID,       array( 'sanitize_callback' => 'sanitize_text_field' ) );
    register_setting( 'gre_settings', 'gre_enable_shortcode', array( 'sanitize_callback' => 'absint',       'default' => 1 ) );
    register_setting( 'gre_settings', 'gre_cache_ttl',        array( 'sanitize_callback' => 'absint',       'default' => 12 ) );

    add_settings_section(
        'gre_section',
        __( 'Instellingen', 'gre' ),
        function() {
            echo '<p>' . esc_html__( 'Configureer hier de toegang tot de Google API en plugin-opties.', 'gre' ) . '</p>';
        },
        'gre_settings'
    );

    add_settings_field( GRE_OPT_API_KEY,        __( 'API Key', 'gre' ),        'gre_api_key_render',        'gre_settings', 'gre_section' );
    add_settings_field( GRE_OPT_PLACE_ID,       __( 'Place ID', 'gre' ),       'gre_place_id_render',       'gre_settings', 'gre_section' );
    add_settings_field( 'gre_enable_shortcode', __( 'Shortcode inschakelen', 'gre' ),      'gre_enable_shortcode_render', 'gre_settings', 'gre_section' );
    add_settings_field( 'gre_test_connection',  __( 'Verbinding testen', 'gre' ),         'gre_test_connection_render',  'gre_settings', 'gre_section' );
    add_settings_field( 'gre_cache_ttl',        __( 'Cache duur', 'gre' ),                   'gre_cache_ttl_render',        'gre_settings', 'gre_section' );
    add_settings_field( 'gre_force_refresh',    __( 'Data verversen', 'gre' ),              'gre_force_refresh_render',    'gre_settings', 'gre_section' );
}

function gre_api_key_render() {
    $val = get_option( GRE_OPT_API_KEY, '' );
    printf(
        '<input type="password" id="%1$s" name="%1$s" value="%2$s" class="regular-text" />'
      . ' <span class="toggle-visibility dashicons dashicons-visibility" data-field="%1$s" style="cursor:pointer; margin-left:4px;"></span>'
      . ' <span id="gre-api-status" class="gre-status-icon dashicons"></span>',
        esc_attr( GRE_OPT_API_KEY ),
        esc_attr( $val )
    );
    echo '<p class="description">';
    echo '<span class="dashicons dashicons-editor-help" style="vertical-align:middle; margin-right:4px;" '
       . 'title="' . esc_attr__( 'Vraag een API Key aan via Google Cloud Console → APIs & Services → Credentials', 'gre' ) . '"></span>';
    echo '<a href="https://console.cloud.google.com/apis/credentials" target="_blank" rel="noopener">'
       . esc_html__( 'Google Cloud Console (Credentials)', 'gre' )
       . '</a> ';
    echo esc_html__( 'om je API Key aan te maken.', 'gre' );
    echo '</p>';
    echo '<p class="description">';
    echo '<span class="dashicons dashicons-editor-help" style="vertical-align:middle; margin-right:4px;" '
       . 'title="' . esc_attr__( 'Activeer de benodigde API via de Library', 'gre' ) . '"></span>';
    echo esc_html__( 'Zorg dat de ', 'gre' );
    echo '<a href="https://console.cloud.google.com/apis/library/places.googleapis.com" target="_blank" rel="noopener">'
       . esc_html__( 'Places API', 'gre' )
       . '</a>';
    echo esc_html__( ' ingeschakeld is via APIs & Services → Library.', 'gre' );
    echo '</p>';
}

function gre_place_id_render() {
    $val = get_option( GRE_OPT_PLACE_ID, '' );
    printf(
        '<input type="password" id="%1$s" name="%1$s" value="%2$s" class="regular-text" />'
      . ' <span class="toggle-visibility dashicons dashicons-visibility" data-field="%1$s" style="cursor:pointer; margin-left:4px;"></span>'
      . ' <span id="gre-place-status" class="gre-status-icon dashicons"></span>',
        esc_attr( GRE_OPT_PLACE_ID ),
        esc_attr( $val )
    );
    echo '<p class="description">';
    echo '<span class="dashicons dashicons-editor-help" style="vertical-align:middle; margin-right:4px;" '
       . 'title="' . esc_attr__( 'Vind je Place ID via de Google Place ID Finder', 'gre' ) . '"></span>';
    echo '<a href="https://developers.google.com/maps/documentation/places/web-service/place-id" target="_blank" rel="noopener">'
       . esc_html__( 'Google Place ID Finder', 'gre' )
       . '</a> ';
    echo esc_html__( 'om je Place ID te vinden.', 'gre' );
    echo '</p>';
    $last = get_option( GRE_OPT_LAST_DATA, false );
    if ( is_array( $last ) && ! empty( $last['name'] ) ) {
        printf(
            '<p class="description">%s</p>',
            sprintf(
                esc_html__( 'Verbonden met %s', 'gre' ),
                esc_html( $last['name'] )
            )
        );
    }
}

function gre_enable_shortcode_render() {
    $val = get_option( 'gre_enable_shortcode', 1 );
    printf(
        '<input type="checkbox" id="gre_enable_shortcode" name="gre_enable_shortcode" value="1" %s /> <label for="gre_enable_shortcode">%s</label>',
        checked( 1, $val, false ),
        esc_html__( 'Enable [google_rating] shortcode', 'gre' )
    );
}

function gre_test_connection_render() {
    echo '<button type="button" class="button button-secondary" id="gre-test-connection-button">'
         . esc_html__( 'Controleer verbinding', 'gre' )
         . '</button>';
    echo '<span id="gre-test-connection-result" style="margin-left:12px; vertical-align:middle;"></span>';
}

function gre_cache_ttl_render() {
    $val = get_option( 'gre_cache_ttl', 12 );
    ?>
    <select id="gre_cache_ttl" name="gre_cache_ttl">
        <option value="1"   <?php selected( $val, 1 );   ?>>1 uur</option>
        <option value="12"  <?php selected( $val, 12 );  ?>>12 uur</option>
        <option value="24"  <?php selected( $val, 24 );  ?>>24 uur</option>
        <option value="168" <?php selected( $val, 168 ); ?>>1 week</option>
    </select>
    <p class="description"><?php esc_html_e( 'Hoe lang de Google-data gecached blijft (in uren). Kies voor 1 week voor aan laag aantal API calls.', 'gre' ); ?></p>
    <?php
}

function gre_force_refresh_render() {
    echo '<button type="button" class="button button-secondary" id="gre-refresh-data-button">'
         . esc_html__( 'Ververs data', 'gre' )
         . '</button>';
    echo '<span id="gre-refresh-data-result" style="margin-left:12px; vertical-align:middle;"></span>';
}

function gre_admin_notices() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    if ( empty( get_option( GRE_OPT_API_KEY ) ) || empty( get_option( GRE_OPT_PLACE_ID ) ) ) {
        echo '<div class="notice notice-error"><p>'
            . esc_html__( 'Google Rating: Vul API Key en Place ID in instellingen.', 'gre' )
            . '</p></div>';
    }
}

function gre_options_page() {
    $active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'settings';

    echo '<div class="wrap">';
    echo '<h1>' . esc_html__( 'Google Rating Settings', 'gre' ) . '</h1>';

    printf(
        '<h2 class="nav-tab-wrapper">
            <a href="?page=gre_options&tab=settings" class="nav-tab %1$s">%2$s</a>
            <a href="?page=gre_options&tab=shortcode" class="nav-tab %3$s">%4$s</a>
            <a href="?page=gre_options&tab=changelog" class="nav-tab %5$s">%6$s</a>
        </h2>',
        $active_tab === 'settings'  ? 'nav-tab-active' : '',
        esc_html__( 'Instellingen', 'gre' ),
        $active_tab === 'shortcode' ? 'nav-tab-active' : '',
        esc_html__( 'Uitleg',        'gre' ),
        $active_tab === 'changelog' ? 'nav-tab-active' : '',
        esc_html__( 'Changelog',     'gre' )
    );

    switch ( $active_tab ) {
        case 'shortcode':
            gre_render_shortcode_page();
            break;
        case 'changelog':
            gre_render_changelog_page();
            break;
        default:
            gre_render_settings_page();
            break;
    }

    echo '</div>';
}

// ──────────────────────────────────────────────────────────
//  2) Data ophalen met caching en fallback (cron-only)
// ──────────────────────────────────────────────────────────

function gre_fetch_google_place_data() {
    $place_id = get_option( GRE_OPT_PLACE_ID );
    if ( empty( $place_id ) ) {
        return false;
    }

    $transient_key = 'gre_place_' . md5( $place_id );
    $data          = get_transient( $transient_key );
    $last_data     = get_option( GRE_OPT_LAST_DATA );

    if ( false !== $data ) {
        return $data;
    }

    // Cache verlopen: plan een éénmalige achtergronddownload
    if ( ! wp_next_scheduled( 'gre_prefetch_event' ) ) {
        wp_schedule_single_event( time() + 5, 'gre_prefetch_event' );
    }

    return $last_data ?: false;
}

// ──────────────────────────────────────────────────────────
//  3) AJAX-handlers
// ──────────────────────────────────────────────────────────

add_action( 'wp_ajax_gre_test_connection', 'gre_test_connection_callback' );
function gre_test_connection_callback() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( __( 'Geen toestemming.', 'gre' ) );
    }
    $api_key  = sanitize_text_field( wp_unslash( $_POST['api_key']  ?? '' ) );
    $place_id = sanitize_text_field( wp_unslash( $_POST['place_id'] ?? '' ) );
    if ( empty( $api_key ) || empty( $place_id ) ) {
        wp_send_json_error( __( 'Vul API Key en Place ID in.', 'gre' ) );
    }

    $url = sprintf(
        'https://maps.googleapis.com/maps/api/place/details/json'
        . '?place_id=%s'
        . '&fields=name,rating,user_ratings_total,opening_hours'
        . '&key=%s',
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

    update_option( GRE_OPT_LAST_DATA, $json['result'] );

    wp_send_json_success( sprintf(
        __( 'Verbonden! Place ID gevonden: %s', 'gre' ),
        esc_html( $json['result']['place_id'] )
    ) );
}

add_action( 'wp_ajax_gre_force_refresh', 'gre_force_refresh_callback' );
function gre_force_refresh_callback() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( __( 'Geen toestemming.', 'gre' ) );
    }
    check_ajax_referer( 'gre_force_refresh', 'security' );

    // Forceer directe fetch
    $fetched = gre_do_google_place_fetch();
    if ( ! $fetched ) {
        wp_send_json_error( __( 'Vernieuwen mislukt.', 'gre' ) );
    }

    wp_send_json_success( __( 'Data ververst!', 'gre' ) );
}


// ──────────────────────────────────────────────────────────
//  4) Admin-scripts & styles enqueue & inline CSS
// ──────────────────────────────────────────────────────────

add_action( 'admin_enqueue_scripts', function( $hook ) {
    if ( 'toplevel_page_gre_options' !== $hook ) {
        return;
    }

    // Moderne admin styling
    wp_enqueue_style(
        'gre-admin-style',
        plugin_dir_url( __FILE__ ) . 'assets/css/admin-style.css',
        array(),
        filemtime( plugin_dir_path( __FILE__ ) . 'assets/css/admin-style.css' )
    );

    // Plugin JavaScript
    wp_enqueue_script(
        'gre-admin-js',
        plugin_dir_url( __FILE__ ) . 'assets/js/gre-admin.js',
        array( 'jquery' ),
        '1.2.0',
        true
    );
    wp_localize_script( 'gre-admin-js', 'greSettings', array(
        'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
        'apiKeyField'  => GRE_OPT_API_KEY,
        'placeIdField' => GRE_OPT_PLACE_ID,
        'refreshNonce' => wp_create_nonce( 'gre_force_refresh' ),
    ) );
} );

add_action( 'admin_head', function() {
    echo '<style>
        .gre-status-icon { font-size: 18px; vertical-align: middle; margin-left: 6px; }
        .gre-status-icon.green { color: #28a745; }
        .gre-status-icon.red   { color: #dc3545; }
    </style>';
} );


// ──────────────────────────────────────────────────────────
//  5) Elementor Dynamic Tags registreren (alleen Pro)
// ──────────────────────────────────────────────────────────

add_action( 'elementor/dynamic_tags/register_tags', function( $tags ) {
    if ( ! class_exists( 'ElementorPro\Plugin' ) ) {
        return;
    }
    require_once __DIR__ . '/dynamic-tags/class-google-rating-tag.php';
    $tags->register( new \GRE\DynamicTags\Google_Rating_Tag() );
}, 50 );

add_action( 'elementor/dynamic_tags/register_tags', function( $tags ) {
    if ( ! class_exists( 'ElementorPro\Plugin' ) ) {
        return;
    }
    require_once __DIR__ . '/dynamic-tags/class-google-opening-hours-tag.php';
    $tags->register( new \GRE\DynamicTags\Google_Opening_Hours_Tag() );
}, 51 );

// ──────────────────────────────────────────────────────────
//  6) Shortcode functie voor Google Rating
// ──────────────────────────────────────────────────────────

function gre_shortcode_google_rating( $atts ) {
    $atts = shortcode_atts( array( 'field' => 'rating_star' ), $atts, 'google_rating' );
    $data = gre_fetch_google_place_data();
    if ( ! $data ) {
        return esc_html__( 'Geen data beschikbaar.', 'gre' );
    }
    $rating = floatval( $data['rating'] );
    $count  = intval( $data['user_ratings_total'] );
    switch ( $atts['field'] ) {
        case 'rating_number':
            return esc_html( $rating );
        case 'rating_star':
            return esc_html( sprintf( '%.1f ★', $rating ) );
        case 'count_number':
            return esc_html( $count );
        default:
            return sprintf(
                '<strong>%s</strong> ★ %s reviews',
                esc_html( number_format_i18n( $rating, 1 ) ),
                esc_html( $count )
            );
    }
}

if ( get_option( 'gre_enable_shortcode', 1 ) ) {
    add_shortcode( 'google_rating', 'gre_shortcode_google_rating' );
}

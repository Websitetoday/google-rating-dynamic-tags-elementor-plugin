<?php
/**
 * Plugin Name:     Google Rating Dynamic Tags Elementor
 * Plugin URI:      https://github.com/Websitetoday/google-rating-dynamic-tags-elementor-plugin.git
 * GitHub Plugin URI: https://github.com/Websitetoday/google-rating-dynamic-tags-elementor-plugin
 * Description:     Toon eenvoudig de Google Bedrijfsbeoordelingen (gemiddelde score, aantal reviews en link naar reviews) als Elementor Dynamic Tag en via shortcode (meetbaar en stylebaar).
 * Version:         2.0.2
 * Author:          Websitetoday.nl
 * Author URI:      https://www.websitetoday.nl/
 * Text Domain:     gre
 * Domain Path:     /languages
 * GitHub Branch:   main
 * Requires at least: 5.0
 * Tested up to:    6.4
 * Icon URI:        https://www.websitetoday.nl/wp-content/uploads/2025/05/icon-256x256-1.png
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Plugin Update Checker
require_once __DIR__ . '/plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;
$updateChecker = PucFactory::buildUpdateChecker(
    'https://github.com/Websitetoday/google-rating-dynamic-tags-elementor-plugin/',
    __FILE__,
    'google-rating-dynamic-tags-elementor-plugin'
);
$updateChecker->setBranch( 'main' );

// Constants
const GRE_OPT_API_KEY   = 'gre_api_key';
const GRE_OPT_PLACES    = 'gre_places';
const GRE_OPT_LAST_DATA = 'gre_last_data';

// Load Textdomain
add_action( 'plugins_loaded', function() {
    load_plugin_textdomain( 'gre', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
} );

// Migrate old single place_id
add_action( 'admin_init', function() {
    $old = get_option( 'gre_place_id' );
    if ( $old && ! get_option( GRE_OPT_PLACES ) ) {
        update_option( GRE_OPT_PLACES, [ [ 'label' => 'Hoofdlocatie', 'place_id' => sanitize_text_field( $old ) ] ] );
        delete_option( 'gre_place_id' );
    }
} );

// Admin menu & settings
require_once __DIR__ . '/includes/admin-settings.php';

// Admin scripts
add_action( 'admin_enqueue_scripts', function( $hook ) {
    global $plugin_page;

    if ( $hook === 'toplevel_page_gre_options' ) {
        wp_enqueue_script(
            'gre-admin-js',
            plugins_url( 'assets/js/gre-admin.js', __FILE__ ),
            [ 'jquery' ],
            filemtime( plugin_dir_path( __FILE__ ) . 'assets/js/gre-admin.js' ),
            true
        );

        wp_localize_script( 'gre-admin-js', 'greSettings', [
            'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
            'apiKeyField'   => GRE_OPT_API_KEY,
            'placeSelector' => 'input[name^="gre_places"][name$="[place_id]"]'
        ] );
    }
});



// AJAX test connection
add_action( 'wp_ajax_gre_test_connection', function() {
    if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'Geen toegang' );
    $api_key  = sanitize_text_field( $_POST['api_key'] ?? '' );
    $place_id = sanitize_text_field( $_POST['place_id'] ?? '' );
    if ( ! $api_key || ! $place_id ) wp_send_json_error( 'Vul API Key en Place ID in.' );

    $url = "https://maps.googleapis.com/maps/api/place/details/json?place_id={$place_id}&fields=rating&language=nl&key={$api_key}";
    $res = wp_remote_get( $url );

    if ( is_wp_error( $res ) ) wp_send_json_error( $res->get_error_message() );

    $data = json_decode( wp_remote_retrieve_body( $res ), true );
    if ( ! empty( $data['result'] ) ) wp_send_json_success( 'Verbonden!' );

    wp_send_json_error( 'Geen resultaten gevonden.' );
});


// Fetch Google Place Data
function gre_fetch_google_place_data( $place_id = null ) {
    $api_key = get_option( GRE_OPT_API_KEY );
    if ( ! $place_id ) {
        $places = get_option( GRE_OPT_PLACES, [] );
        if ( empty( $places ) ) return false;
        $place_id = $places[0]['place_id'];
    }
    $cache_key = 'gre_place_' . md5( $place_id );
    if ( ( $cached = get_transient( $cache_key ) ) !== false ) return $cached;
    $res = wp_remote_get( "https://maps.googleapis.com/maps/api/place/details/json?place_id={$place_id}&fields=rating,user_ratings_total,opening_hours&language=nl&key={$api_key}" );
    if ( is_wp_error( $res ) ) return get_option( GRE_OPT_LAST_DATA );
    $data = json_decode( wp_remote_retrieve_body( $res ), true );
    if ( ! empty( $data['result'] ) ) {
        update_option( GRE_OPT_LAST_DATA, $data['result'] );
        set_transient( $cache_key, $data['result'], 12 * HOUR_IN_SECONDS );
        return $data['result'];
    }
    return get_option( GRE_OPT_LAST_DATA );
}

// Helper: plaatsopties voor dropdowns
function gre_get_places_for_select() {
    $output = [ '' => __( 'Eerste bedrijf', 'gre' ), 'all' => __( 'Alle bedrijven', 'gre' ) ];
    $places = get_option( GRE_OPT_PLACES, [] );
    foreach ( $places as $p ) {
        if ( isset( $p['place_id'] ) ) $output[ $p['place_id'] ] = $p['label'] ?? $p['place_id'];
    }
    return $output;
}

// Elementor Dynamic Tags
add_action( 'elementor/dynamic_tags/register_tags', function( $tags ) {
    if ( ! class_exists( 'ElementorPro\Plugin' ) ) return;
    require_once __DIR__ . '/dynamic-tags/class-google-rating-tag.php';
    $tags->register( new \GRE\DynamicTags\Google_Rating_Tag() );
});

add_action( 'elementor/dynamic_tags/register_tags', function( $tags ) {
    if ( ! class_exists( 'ElementorPro\Plugin' ) ) return;
    require_once __DIR__ . '/dynamic-tags/class-google-opening-hours-tag.php';
    $tags->register( new \GRE\DynamicTags\Google_Opening_Hours_Tag() );
});

// Shortcode
function gre_shortcode_google_rating( $atts ) {
    $atts = shortcode_atts([
        'field' => 'rating_star',
        'place' => ''
    ], $atts);

    if ( $atts['place'] === 'all' ) {
        $output = '';
        $places = get_option( GRE_OPT_PLACES, [] );
        foreach ( $places as $p ) {
            $data = gre_fetch_google_place_data( $p['place_id'] );
            if ( ! $data ) continue;
            $r = floatval( $data['rating'] );
            $c = intval( $data['user_ratings_total'] );
            $output .= '<div><strong>' . esc_html( $p['label'] ) . ':</strong> ';
            switch ( $atts['field'] ) {
                case 'rating_number': $output .= $r; break;
                case 'rating_star': $output .= sprintf( '%.1f ★', $r ); break;
                case 'count_number': $output .= $c; break;
                default: $output .= sprintf( '%.1f ★ %d reviews', $r, $c );
            }
            $output .= '</div>';
        }
        return $output;
    }

    $data = gre_fetch_google_place_data( $atts['place'] );
    if ( ! $data ) return esc_html__( 'Geen data beschikbaar.', 'gre' );
    $r = floatval( $data['rating'] );
    $c = intval( $data['user_ratings_total'] );
    switch ( $atts['field'] ) {
        case 'rating_number': return $r;
        case 'rating_star': return sprintf( '%.1f ★', $r );
        case 'count_number': return $c;
        default: return sprintf( '<strong>%.1f</strong> ★ %d reviews', $r, $c );
    }
}
if ( get_option( 'gre_enable_shortcode', 1 ) ) {
    add_shortcode( 'google_rating', 'gre_shortcode_google_rating' );
}

<?php
/**
 * Plugin Name:     Google Rating Dynamic Tags Elementor
 * Plugin URI:      https://github.com/Websitetoday/google-rating-dynamic-tags-elementor-plugin.git
 * GitHub Plugin URI: https://github.com/Websitetoday/google-rating-dynamic-tags-elementor-plugin
 * Description:     Toon eenvoudig de Google Bedrijfsbeoordelingen (gemiddelde score, aantal reviews en link naar reviews) als Elementor Dynamic Tag en via shortcode (meetbaar en stylebaar).
 * Version:         1.5.6
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

// Load the PUC library and Parsedown parser for release notes
require_once __DIR__ . '/plugin-update-checker/plugin-update-checker.php';
if ( ! class_exists( 'Parsedown' ) ) {
    require_once __DIR__ . '/plugin-update-checker/Puc/v5p5/Parsedown.php';
}

// Use the PUC v5 factory to register your public GitHub repo
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$updateChecker = PucFactory::buildUpdateChecker(
    'https://github.com/Websitetoday/google-rating-dynamic-tags-elementor-plugin/',
    __FILE__,
    'google-rating-dynamic-tags-elementor-plugin'
);

// Point to the branch you use for stable releases
$updateChecker->setBranch( 'main' );

// Inject eigen plugin-icon in de "View Details" modal
add_filter( 'plugins_api', function( $res, $action, $args ) {
    if ( 'plugin_information' === $action
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

/** Optie-namen */
define( 'GRE_OPT_API_KEY',   'gre_api_key' );
define( 'GRE_OPT_PLACE_ID',  'gre_place_id' );
define( 'GRE_OPT_LAST_DATA', 'gre_last_data' );

/** Textdomain laden */
add_action( 'plugins_loaded', function() {
    load_plugin_textdomain( 'gre', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
} );

/** "Instellingen" link op plugin-pagina */
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), function( $links ) {
    $settings_link = '<a href="'. admin_url( 'admin.php?page=gre_options' ) .'">'. esc_html__( 'Instellingen', 'gre' ) .'</a>';
    array_unshift( $links, $settings_link );
    return $links;
} );

/** 1) Admin menu en settings */
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
    register_setting( 'gre_settings', GRE_OPT_API_KEY, array( 'sanitize_callback' => 'sanitize_text_field' ) );
    register_setting( 'gre_settings', GRE_OPT_PLACE_ID, array( 'sanitize_callback' => 'sanitize_text_field' ) );
    register_setting( 'gre_settings', 'gre_enable_shortcode', array( 'sanitize_callback' => 'absint', 'default' => 1 ) );

    add_settings_section(
        'gre_section',
        __( 'Instellingen', 'gre' ),
        function() {
            echo '<p>' . esc_html__( 'Configureer hier de toegang tot de Google API en plugin-opties.', 'gre' ) . '</p>';
        },
        'gre_settings'
    );

    add_settings_field( GRE_OPT_API_KEY,         __( 'API Key', 'gre' ),         'gre_api_key_render',         'gre_settings', 'gre_section' );
    add_settings_field( GRE_OPT_PLACE_ID,        __( 'Place ID', 'gre' ),        'gre_place_id_render',        'gre_settings', 'gre_section' );
    add_settings_field( 'gre_enable_shortcode',  __( 'Shortcode inschakelen', 'gre' ), 'gre_enable_shortcode_render', 'gre_settings', 'gre_section' );
    add_settings_field( 'gre_test_connection',   __( 'Verbinding testen', 'gre' ),    'gre_test_connection_render',  'gre_settings', 'gre_section' );
}

function gre_api_key_render() {
    $val = get_option( GRE_OPT_API_KEY, '' );
    printf(
        '<input type="password" id="%1$s" name="%1$s" value="%2$s" class="regular-text" />'
      . ' <span class="toggle-visibility" data-field="%1$s" style="cursor:pointer; margin-left:4px;">👁️</span>'
      . ' <span id="gre-api-status" class="gre-status-icon dashicons"></span>',
        esc_attr( GRE_OPT_API_KEY ),
        esc_attr( $val )
    );
}

function gre_place_id_render() {
    $val = get_option( GRE_OPT_PLACE_ID, '' );
    printf(
        '<input type="password" id="%1$s" name="%1$s" value="%2$s" class="regular-text" />'
      . ' <span class="toggle-visibility" data-field="%1$s" style="cursor:pointer; margin-left:4px;">👁️</span>'
      . ' <span id="gre-place-status" class="gre-status-icon dashicons"></span>',
        esc_attr( GRE_OPT_PLACE_ID ),
        esc_attr( $val )
    );
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
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Google Rating Settings', 'gre' ); ?></h1>
        <h2 class="nav-tab-wrapper">
            <a href="?page=gre_options&tab=settings" class="nav-tab <?php echo $active_tab==='settings'?'nav-tab-active':''; ?>">
                <?php esc_html_e('Instellingen','gre'); ?>
            </a>
            <a href="?page=gre_options&tab=shortcode" class="nav-tab <?php echo $active_tab==='shortcode'?'nav-tab-active':''; ?>">
                <?php esc_html_e('Uitleg','gre'); ?>
            </a>
            <a href="?page=gre_options&tab=changelog" class="nav-tab <?php echo $active_tab==='changelog'?'nav-tab-active':''; ?>">
                <?php esc_html_e('Changelog','gre'); ?>
            </a>
        </h2>

        <?php if ( $active_tab === 'settings' ) : ?>
            <form action="options.php" method="post">
                <?php
                settings_fields( 'gre_settings' );
                do_settings_sections( 'gre_settings' );
                submit_button();
                ?>
            </form>

        <?php elseif ( $active_tab === 'shortcode' ) : ?>
            <h2><?php esc_html_e( 'Uitleg', 'gre' ); ?></h2>
            <p><?php esc_html_e( 'Gebruik de shortcode met parameter <code>field</code> om output te bepalen. Mogelijke waarden:', 'gre' ); ?></p>
            <ul>
                <li><code>rating_number</code> – <?php esc_html_e( 'Gemiddelde score als nummer', 'gre' ); ?></li>
                <li><code>rating_star</code>   – <?php esc_html_e( 'Gemiddelde score + ster', 'gre' ); ?></li>
                <li><code>count_number</code>  – <?php esc_html_e( 'Aantal reviews als nummer', 'gre' ); ?></li>
                <li><code>both</code>          – <?php esc_html_e( 'Score + Aantal (tekst)', 'gre' ); ?></li>
            </ul>
            <p><?php esc_html_e( 'Voorbeeld:', 'gre' ); ?> <code>[google_rating field="rating_star"]</code></p>

            <hr/>
            <h2><?php esc_html_e( 'Elementor Dynamic Tags Uitleg', 'gre' ); ?></h2>
            <p><?php esc_html_e( 'Gebruik de Google Rating dynamic tag in Elementor Pro met de volgende stappen:', 'gre' ); ?></p>
            <ol>
                <li><?php esc_html_e( 'Open een widget die dynamic tags ondersteunt, zoals een Heading of Tekstbewerker.', 'gre' ); ?></li>
                <li><?php esc_html_e( 'Klik op het Dynamic Tags icoon naast het veld.', 'gre' ); ?></li>
                <li><?php esc_html_e( 'Selecteer "Google Rating" in de lijst.', 'gre' ); ?></li>
                <li><?php esc_html_e( 'Kies in de tag-instellingen het gewenste veld: Rating als nummer, Rating met ster, Aantal reviews of Beide.', 'gre' ); ?></li>
                <li><?php esc_html_e( 'Pas de styling aan via de widget-instellingen.', 'gre' ); ?></li>
            </ol>

            <hr/>
            <h2><?php esc_html_e( 'Google Openingstijden Dynamic Tag Uitleg', 'gre' ); ?></h2>
            <p><?php esc_html_e( 'Met deze tag kun je de openingstijden van je bedrijf tonen. Volg de stappen hieronder om de Openingstijden dynamic tag te gebruiken in Elementor Pro:', 'gre' ); ?></p>
            <ol>
                <li><?php esc_html_e( 'Open een widget die dynamic tags ondersteunt, zoals een Tekstbewerker of Lijst.', 'gre' ); ?></li>
                <li><?php esc_html_e( 'Klik op het Dynamic Tags icoon naast het veld.', 'gre' ); ?></li>
                <li><?php esc_html_e( 'Selecteer "Google Opening Hours" in de lijst.', 'gre' ); ?></li>
                <li><?php esc_html_e( 'In de tag-instellingen kun je de volgende weergave-opties kiezen:', 'gre' ); ?></li>
                <ul>
                    <li><?php esc_html_e( 'Volledige week (lijst met alle dagen en tijden)', 'gre' ); ?></li>
                    <li><?php esc_html_e( 'Vandaag (alleen openingstijden voor de huidige dag)', 'gre' ); ?></li>
                    <li><?php esc_html_e( 'Open/gesloten status (tekst)', 'gre' ); ?></li>
                </ul>
                <li><?php esc_html_e( 'Pas de styling en sjabloon aan via de widget-instellingen.', 'gre' ); ?></li>
            </ol>

        <?php else : ?>
            <h2><?php esc_html_e( 'Changelog', 'gre' ); ?></h2>
            <ul>
                <li><strong>1.5.6</strong> – <?php esc_html_e( 'Nieuwe functie: Google Openingstijden tonen via Elementor Dynamic Tags.', 'gre' ); ?></li>
                <li><strong>1.5.5</strong> – <?php esc_html_e( 'Tabblad Shortcode Uitleg hernoemd naar Uitleg, uitgebreide uitleg toegevoegd over Elementor dynamic tags inclusief de openingstijden tag.', 'gre' ); ?></li>
                <li><strong>1.5.4</strong> – <?php esc_html_e( 'Real-time statusicoontjes toegevoegd voor API Key & Place ID', 'gre' ); ?></li>
                <li><strong>1.5.3</strong> – <?php esc_html_e( 'Nieuwe verbindingscheck met icoon en foutmelding als API Key of Place ID onjuist is', 'gre' ); ?></li>
                <li><strong>1.5.2</strong> – <?php esc_html_e( 'Fix: verwijderde niet‑werkende Test/Ververs knoppen en bijbehorende AJAX‑code', 'gre' ); ?></li>
                <li><strong>1.5.1</strong> – <?php esc_html_e( 'Tweak: shortcode‑registratie hersteld zodat deze weer werkt', 'gre' ); ?></li>
                <li><strong>1.5.0</strong> – <?php esc_html_e( 'New: ondersteuning voor GitHub Releases via Update URI & plugin‑naam aangepast', 'gre' ); ?></li>
                <li><strong>1.4.2</strong> – <?php esc_html_e( 'New: Test verbinding & ververs data knoppen, UI & shortcode toggle tweaks', 'gre' ); ?></li>
                <li><strong>1.4.1</strong> – <?php esc_html_e( 'Fix: verwijder clear_cache hook', 'gre' ); ?></li>
                <li><strong>1.4.0</strong> – <?php esc_html_e( 'New: eigen top‑level menu en admin tabs', 'gre' ); ?></li>
                <li><strong>1.3.2</strong> – <?php esc_html_e( 'Fix: syntaxfouten in register_tags_group', 'gre' ); ?></li>
                <li><strong>1.3.1</strong> – <?php esc_html_e( 'Tweak: default rating_star, correct add_action', 'gre' ); ?></li>
                <li><strong>1.3.0</strong> – <?php esc_html_e( 'New: “Aantal reviews (nummer)” voor counter widgets', 'gre' ); ?></li>
                <li><strong>1.2.0</strong> – <?php esc_html_e( 'Tweak: groep Google Rating onder eigen kopje', 'gre' ); ?></li>
                <li><strong>1.1.1</strong> – <?php esc_html_e( 'Tweak: Author URI toegevoegd, remove Place ID control', 'gre' ); ?></li>
            </ul>
        <?php endif; ?>
    </div>
    <?php
}

/** 2) Data ophalen met caching en fallback */
function gre_fetch_google_place_data() {
    $api_key  = get_option( GRE_OPT_API_KEY );
    $place_id = get_option( GRE_OPT_PLACE_ID );
    if ( empty( $api_key ) || empty( $place_id ) ) {
        return false;
    }

    $transient_key = 'gre_place_' . md5( $place_id );
    $last_data     = get_option( GRE_OPT_LAST_DATA );
    if ( false !== ( $data = get_transient( $transient_key ) ) ) {
        return $data;
    }

    // API-call met Nederlandse taal voor weekday_text
    $response = wp_remote_get( sprintf(
        'https://maps.googleapis.com/maps/api/place/details/json'
        . '?place_id=%s'
        . '&fields=rating,user_ratings_total,opening_hours'
        . '&language=nl'        // ← hier de taal toevoegen
        . '&key=%s',
        urlencode( $place_id ),
        $api_key
    ) );

    if ( is_wp_error( $response ) ) {
        return $last_data ?: false;
    }

    $json = json_decode( wp_remote_retrieve_body( $response ), true );
    if ( ! empty( $json['result'] ) ) {
        update_option( GRE_OPT_LAST_DATA, $json['result'] );
        $ttl = apply_filters( 'gre_transient_ttl', 12 * HOUR_IN_SECONDS );
        set_transient( $transient_key, $json['result'], $ttl );
        return $json['result'];
    }

    return $last_data ?: false;
}


/** 3) AJAX-handler voor verbindingscheck */
add_action( 'wp_ajax_gre_test_connection', 'gre_test_connection_callback' );
function gre_test_connection_callback() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( __( 'Geen toestemming.', 'gre' ) );
    }
    $api_key  = sanitize_text_field( wp_unslash( $_POST['api_key']  ) );
    $place_id = sanitize_text_field( wp_unslash( $_POST['place_id'] ) );
    if ( empty( $api_key ) || empty( $place_id ) ) {
        wp_send_json_error( __( 'Vul API Key en Place ID in.', 'gre' ) );
    }
    $url      = sprintf(
        'https://maps.googleapis.com/maps/api/place/details/json?place_id=%s&fields=rating,user_ratings_total,opening_hours&key=%s',
        urlencode( $place_id ),
        $api_key
    );
    $response = wp_remote_get( $url, array( 'timeout' => 10 ) );
    if ( is_wp_error( $response ) ) {
        wp_send_json_error( $response->get_error_message() );
    }
    $json = json_decode( wp_remote_retrieve_body( $response ), true );
    if ( ! empty( $json['result'] ) ) {
        wp_send_json_success( __( 'Verbonden! ✔️', 'gre' ) );
    }
    wp_send_json_error( __( 'Geen resultaten gevonden voor deze Place ID.', 'gre' ) );
}

/** 4) Admin‑scripts enqueue & inline CSS */
add_action( 'admin_enqueue_scripts', function( $hook ) {
    if ( 'toplevel_page_gre_options' !== $hook ) {
        return;
    }
    wp_enqueue_script(
        'gre-admin-js',
        plugin_dir_url( __FILE__ ) . 'assets/js/gre-admin.js',
        array( 'jquery' ),
        '1.1.0',
        true
    );
    wp_localize_script( 'gre-admin-js', 'greSettings', array(
        'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
        'apiKeyField'  => GRE_OPT_API_KEY,
        'placeIdField' => GRE_OPT_PLACE_ID,
    ) );
} );

add_action( 'admin_head', function() {
    echo '<style>
        .gre-status-icon { font-size: 18px; vertical-align: middle; margin-left: 6px; }
        .gre-status-icon.green { color: #28a745; }
        .gre-status-icon.red   { color: #dc3545; }
    </style>';
} );

/** 5) Elementor Dynamic Tag registreren (alleen Pro) */
add_action( 'elementor/dynamic_tags/register_tags', function( $tags ) {
    if ( ! class_exists( 'ElementorPro\Plugin' ) ) {
        return;
    }
    require_once __DIR__ . '/dynamic-tags/class-google-rating-tag.php';
    // correcte namespacing: enkel één backslash
    $tags->register( new \GRE\DynamicTags\Google_Rating_Tag() );
}, 50 );

/** 5b) Google Openingstijden Dynamic Tag registreren (alleen Pro) */
add_action( 'elementor/dynamic_tags/register_tags', function( $tags ) {
    // Alleen als Elementor Pro actief is
    if ( ! class_exists( 'ElementorPro\Plugin' ) ) {
        return;
    }
    require_once __DIR__ . '/dynamic-tags/class-google-opening-hours-tag.php';
    // Registreer de tag
    $tags->register( new \GRE\DynamicTags\Google_Opening_Hours_Tag() );
}, 51 );

/** 6) Shortcode functie voor Google Rating */
function gre_shortcode_google_rating( $atts ) {
    $atts = shortcode_atts( array( 'field' => 'rating_star' ), $atts, 'google_rating' );
    $data = gre_fetch_google_place_data();
    if ( ! $data ) {
        return esc_html__( 'Geen data beschikbaar.', 'gre' );
    }
    $rating = floatval( $data['rating'] );
    $count  = intval( $data['user_ratings_total'] );
    switch ( $atts['field'] ) {
        case 'rating_number':  return $rating;
        case 'rating_star':    return sprintf( '%.1f ★', $rating );
        case 'count_number':   return $count;
        default:               return sprintf( '<strong>%.1f</strong> ★ %d reviews', $rating, $count );
    }
}

// 🚨 Alleen tijdelijk voor één keer: cache legen
/* 

add_action( 'init', function() {
    delete_transient( 'gre_place_' . md5( get_option( GRE_OPT_PLACE_ID ) ) );
    delete_option(     GRE_OPT_LAST_DATA );
} ); 
 
*/


/** 7) Shortcode registreren (optioneel) */
if ( get_option( 'gre_enable_shortcode', 1 ) ) {
    add_shortcode( 'google_rating', 'gre_shortcode_google_rating' );
}

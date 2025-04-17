<?php
/**
 * Plugin Name:     Google Rating Dynamic Tags Elementor
 * Plugin URI:      https://www.websitetoday.nl/
 * Update URI:      https://github.com/Websitetoday/google-rating-dynamic-tags-elementor-plugin
 * Description:     Toon Google rating & review-aantal als Dynamic Tags en shortcodes.
 * Version:         1.5.2
 * Author:          Websitetoday.nl
 * Author URI:      https://www.websitetoday.nl/
 * Text Domain:     gre
 * Domain Path:     /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Optie-namen
if ( ! defined( 'GRE_OPT_API_KEY' ) ) {
    define( 'GRE_OPT_API_KEY',   'gre_api_key' );
}
if ( ! defined( 'GRE_OPT_PLACE_ID' ) ) {
    define( 'GRE_OPT_PLACE_ID',  'gre_place_id' );
}
if ( ! defined( 'GRE_OPT_LAST_DATA' ) ) {
    define( 'GRE_OPT_LAST_DATA', 'gre_last_data' );
}

// Laden plugin tekstdomain
add_action( 'plugins_loaded', function() {
    load_plugin_textdomain( 'gre', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
} );

// Voeg "Instellingen" link toe in plugins-overzicht
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), function( $links ) {
    $settings_link = '<a href="'. admin_url( 'admin.php?page=gre_options' ) .'">'. esc_html__( 'Instellingen', 'gre' ) .'</a>';
    array_unshift( $links, $settings_link );
    return $links;
} );

// 1) Admin menu en instellingen
add_action( 'admin_menu',       'gre_add_admin_menu' );
add_action( 'admin_init',       'gre_settings_init' );
add_action( 'admin_notices',    'gre_admin_notices' );

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
    // Registreren van instellingen
    register_setting( 'gre_settings', GRE_OPT_API_KEY, array(
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    register_setting( 'gre_settings', GRE_OPT_PLACE_ID, array(
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    register_setting( 'gre_settings', 'gre_enable_shortcode', array(
        'sanitize_callback' => 'absint',
        'default' => 1,
    ) );

    // Settings sectie voor API Key, Place ID en shortcode toggle
    add_settings_section(
        'gre_section',
        __( 'Instellingen', 'gre' ),
        function() {
            echo '<p>' . esc_html__( 'Configureer hier de toegang tot de Google API en plugin-opties.', 'gre' ) . '</p>';
        },
        'gre_settings'
    );

    // API Key veld
    add_settings_field(
        GRE_OPT_API_KEY,
        __( 'API Key', 'gre' ),
        'gre_api_key_render',
        'gre_settings',
        'gre_section'
    );

    // Place ID veld
    add_settings_field(
        GRE_OPT_PLACE_ID,
        __( 'Place ID', 'gre' ),
        'gre_place_id_render',
        'gre_settings',
        'gre_section'
    );

    // Feature toggle shortcode
    add_settings_field(
        'gre_enable_shortcode',
        __( 'Shortcode inschakelen', 'gre' ),
        function() {
            $val = get_option( 'gre_enable_shortcode', 1 );
            printf(
                '<input type="checkbox" id="gre_enable_shortcode" name="gre_enable_shortcode" value="1" %s />',
                checked( 1, $val, false )
            );
            echo ' <label for="gre_enable_shortcode">' . esc_html__( 'Enable [google_rating] shortcode', 'gre' ) . '</label>';
        },
        'gre_settings',
        'gre_section'
    );
}

// Render callback voor API Key veld
function gre_api_key_render() {
    $val = get_option( GRE_OPT_API_KEY, '' );
    printf(
        '<input type="password" id="%1$s" name="%1$s" value="%2$s" class="regular-text" />'
        . '<span class="toggle-visibility" data-field="%1$s" style="cursor:pointer; margin-left:8px;">👁️</span>',
        esc_attr( GRE_OPT_API_KEY ),
        esc_attr( $val )
    );
}

// Render callback voor Place ID veld
function gre_place_id_render() {
    $val = get_option( GRE_OPT_PLACE_ID, '' );
    printf(
        '<input type="password" id="%1$s" name="%1$s" value="%2$s" class="regular-text" />'
        . '<span class="toggle-visibility" data-field="%1$s" style="cursor:pointer; margin-left:8px;">👁️</span>',
        esc_attr( GRE_OPT_PLACE_ID ),
        esc_attr( $val )
    );
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
            <a href="?page=gre_options&tab=settings" class="nav-tab <?php echo $active_tab==='settings'?'nav-tab-active':''; ?>"><?php esc_html_e('Instellingen','gre'); ?></a>
            <a href="?page=gre_options&tab=shortcode" class="nav-tab <?php echo $active_tab==='shortcode'?'nav-tab-active':''; ?>"><?php esc_html_e('Shortcode Uitleg','gre'); ?></a>
            <a href="?page=gre_options&tab=changelog" class="nav-tab <?php echo $active_tab==='changelog'?'nav-tab-active':''; ?>"><?php esc_html_e('Changelog','gre'); ?></a>
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
            <h2><?php esc_html_e( 'Shortcode Uitleg', 'gre' ); ?></h2>
            <p><?php esc_html_e( 'Gebruik de shortcode met parameter <code>field</code> om output te bepalen. Mogelijke waarden:', 'gre' ); ?></p>
            <ul>
                <li><code>rating_number</code> &ndash; <?php esc_html_e( 'Gemiddelde score als nummer', 'gre' ); ?></li>
                <li><code>rating_star</code> &ndash; <?php esc_html_e( 'Gemiddelde score + ster', 'gre' ); ?></li>
                <li><code>count_number</code> &ndash; <?php esc_html_e( 'Aantal reviews als nummer', 'gre' ); ?></li>
                <li><code>both</code> &ndash; <?php esc_html_e( 'Score + Aantal (tekst)', 'gre' ); ?></li>
            </ul>
            <p><?php esc_html_e( 'Voorbeeld:', 'gre' ); ?> <code>[google_rating field="rating_star"]</code></p>
        <?php else : ?>
            <h2><?php esc_html_e( 'Changelog', 'gre' ); ?></h2>
            <ul>
                <li><strong>1.4.2</strong> &ndash; <?php esc_html_e( 'New: Test verbinding & ververs data knoppen, UI & shortcode toggle tweaks', 'gre' ); ?></li>
                <li><strong>1.4.1</strong> &ndash; <?php esc_html_e( 'Fix: verwijder clear_cache hook', 'gre' ); ?></li>
                <li><strong>1.4.0</strong> &ndash; <?php esc_html_e( 'New: eigen top‑level menu en admin tabs', 'gre' ); ?></li>
                <li><strong>1.3.2</strong> &ndash; <?php esc_html_e( 'Fix: syntaxfouten in register_tags_group', 'gre' ); ?></li>
                <li><strong>1.3.1</strong> &ndash; <?php esc_html_e( 'Tweak: default rating_star, correct add_action', 'gre' ); ?></li>
                <li><strong>1.3.0</strong> &ndash; <?php esc_html_e( 'New: “Aantal reviews (nummer)” voor counter widgets', 'gre' ); ?></li>
                <li><strong>1.2.0</strong> &ndash; <?php esc_html_e( 'Tweak: groep Google Rating onder eigen kopje', 'gre' ); ?></li>
                <li><strong>1.1.1</strong> &ndash; <?php esc_html_e( 'Tweak: Author URI toegevoegd, remove Place ID control', 'gre' ); ?></li>
            </ul>
        <?php endif; ?>
    </div>
    <?php
}

// 2) Data ophalen met caching (TTL filterbaar) en fallback
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

    $response = wp_remote_get( sprintf(
        'https://maps.googleapis.com/maps/api/place/details/json?place_id=%s&fields=rating,user_ratings_total&key=%s',
        urlencode( $place_id ),
        $api_key
    ) );
    if ( is_wp_error( $response ) ) {
        return $last_data ?: false;
    }

    $body = wp_remote_retrieve_body( $response );
    $json = json_decode( $body, true );
    if ( ! empty( $json['result'] ) ) {
        update_option( GRE_OPT_LAST_DATA, $json['result'] );
        $ttl = apply_filters( 'gre_transient_ttl', 12 * HOUR_IN_SECONDS );
        set_transient( $transient_key, $json['result'], $ttl );
        return $json['result'];
    }
    return $last_data ?: false;
}

// 3) Elementor Dynamic Tag registreren (alleen als Pro)
add_action( 'elementor/dynamic_tags/register_tags', function( $tags ) {
    if ( ! class_exists( 'ElementorPro\Plugin' ) ) {
        return;
    }
    require_once __DIR__ . '/dynamic-tags/class-google-rating-tag.php';
    $tags->register( new \GRE\DynamicTags\Google_Rating_Tag() );
}, 50 );

// 4) Shortcode functie voor Google Rating
function gre_shortcode_google_rating( $atts ) {
    $atts = shortcode_atts( array(
        'field' => 'rating_star',
    ), $atts, 'google_rating' );

    $data = gre_fetch_google_place_data();
    if ( ! $data ) {
        return esc_html__( 'Geen data beschikbaar.', 'gre' );
    }
    $rating = floatval( $data['rating'] );
    $count  = intval( $data['user_ratings_total'] );

    switch ( $atts['field'] ) {
        case 'rating_number':
            return $rating;
        case 'rating_star':
            return sprintf( '%.1f ★', $rating );
        case 'count_number':
            return $count;
        case 'both':
        default:
            return sprintf( '<strong>%.1f</strong> ★ %d reviews', $rating, $count );
    }
}

// 5) Shortcode registreren (optioneel inschakelen)
if ( get_option( 'gre_enable_shortcode', 1 ) ) {
    add_shortcode( 'google_rating', 'gre_shortcode_google_rating' );
}

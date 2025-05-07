<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Constants
if ( ! defined( 'GRE_OPT_CACHE_INTERVAL' ) ) {
    define( 'GRE_OPT_CACHE_INTERVAL', 'gre_cache_interval' );
}

// Menu
add_action( 'admin_menu', function() {
    add_menu_page(
        __( 'Google Rating', 'gre' ),
        __( 'Google Rating', 'gre' ),
        'manage_options',
        'gre_options',
        'gre_render_admin_page',
        'dashicons-star-filled',
        80
    );
} );

// Register settings
add_action( 'admin_init', function() {
    register_setting( 'gre_settings', GRE_OPT_API_KEY, [ 'sanitize_callback' => 'sanitize_text_field' ] );
    register_setting( 'gre_settings', GRE_OPT_PLACES, [ 'sanitize_callback' => 'gre_sanitize_places', 'default' => [] ] );
    register_setting( 'gre_settings', 'gre_enable_shortcode', [ 'sanitize_callback' => 'absint', 'default' => 1 ] );
    register_setting( 'gre_settings', GRE_OPT_CACHE_INTERVAL, [ 'sanitize_callback' => 'absint', 'default' => 6 ] );

    add_settings_section( 'gre_section', __( 'Instellingen', 'gre' ), '__return_null', 'gre_settings' );

    add_settings_field( GRE_OPT_API_KEY, __( 'API Key', 'gre' ), 'gre_api_key_render', 'gre_settings', 'gre_section' );
    add_settings_field( GRE_OPT_PLACES, __( 'Bedrijven', 'gre' ), 'gre_places_render', 'gre_settings', 'gre_section' );
    add_settings_field( 'gre_enable_shortcode', __( 'Shortcode inschakelen', 'gre' ), 'gre_enable_shortcode_render', 'gre_settings', 'gre_section' );
    add_settings_field( GRE_OPT_CACHE_INTERVAL, __( 'Cache-interval', 'gre' ), 'gre_cache_interval_render', 'gre_settings', 'gre_section' );
} );

// Notices
add_action( 'admin_notices', function() {
    if ( ! current_user_can( 'manage_options' ) ) return;
    if ( empty( get_option( GRE_OPT_API_KEY ) ) || empty( get_option( GRE_OPT_PLACES ) ) ) {
        echo '<div class="notice notice-error"><p>' . esc_html__( 'Google Rating: Vul API Key en minimaal één bedrijf in instellingen.', 'gre' ) . '</p></div>';
    }
} );

// Helpers
function gre_sanitize_places( $input ) {
    $clean = [];
    if ( is_array( $input ) ) {
        foreach ( $input as $item ) {
            if ( empty( $item['place_id'] ) ) continue;
            $clean[] = [
                'label'    => sanitize_text_field( $item['label'] ?? '' ),
                'place_id' => sanitize_text_field( $item['place_id'] ?? '' ),
            ];
        }
    }
    return $clean;
}

function gre_api_key_render() {
    $val = get_option( GRE_OPT_API_KEY, '' );
    printf(
        '<input type="password" id="%1$s" name="%1$s" value="%2$s" class="regular-text" title="Vul hier je Google Places API-sleutel in (nodig voor data-opvraging)." />
         <span class="toggle-visibility" data-field="%1$s" title="Toon/verberg API Key">👁️</span>
         <span id="gre-api-status" class="gre-status-icon dashicons" title="Status van API-verbinding"></span>
         <p class="description">%3$s</p>',
        esc_attr( GRE_OPT_API_KEY ),
        esc_attr( $val ),
        esc_html__( 'Je Google Places API-sleutel, nodig om bedrijfsdata op te halen.', 'gre' )
    );
}

function gre_places_render() {
    $places = get_option( GRE_OPT_PLACES, [] );
    ?>
    <table id="gre-places-table" class="widefat">
        <thead>
            <tr>
                <th>
                    <?php esc_html_e( 'Label', 'gre' ); ?>
                    <span class="dashicons dashicons-editor-help" title="Een eigen herkenbare naam voor het bedrijf."></span>
                </th>
                <th>
                    <?php esc_html_e( 'Place ID', 'gre' ); ?>
                    <span class="dashicons dashicons-editor-help" title="Google Place ID (vind je in de Google Maps URL)."></span>
                </th>
                <th>
                    <?php esc_html_e( 'Acties', 'gre' ); ?>
                    <span class="dashicons dashicons-editor-help" title="Rij verwijderen of API-test uitvoeren."></span>
                </th>
            </tr>
        </thead>
        <tbody>
        <?php if ( empty( $places ) ) : ?>
            <tr>
                <td colspan="3"><em><?php esc_html_e( 'Geen bedrijven toegevoegd.', 'gre' ); ?></em></td>
            </tr>
        <?php endif; ?>
        <?php foreach ( $places as $i => $p ) : ?>
            <tr>
                <td>
                    <input name="gre_places[<?php echo $i; ?>][label]" value="<?php echo esc_attr( $p['label'] ); ?>" title="Vul hier de weergavenaam in" />
                    <span class="dashicons dashicons-editor-help" title="De naam die je in de frontend wilt tonen."></span>
                </td>
                <td>
                    <input name="gre_places[<?php echo $i; ?>][place_id]" value="<?php echo esc_attr( $p['place_id'] ); ?>" title="Plak hier de Google Place ID" />
                    <span class="dashicons dashicons-editor-help" title="Unieke identifier van je Google Bedrijfspagina."></span>
                </td>
                <td>
                    <button type="button" class="button gre-remove-row" title="Verwijder deze rij">×</button>
                    <button type="button" class="button gre-check-row" data-index="<?php echo $i; ?>" title="Test API-verbinding"><?php esc_html_e( 'Check', 'gre' ); ?></button>
                    <span class="gre-status-icon dashicons dashicons-no-alt red" title="Verbindingsstatus"></span>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">
                    <button class="button button-secondary" type="button" id="gre-add-row" title="Voeg nieuw bedrijf toe"><?php esc_html_e( 'Bedrijf toevoegen', 'gre' ); ?></button>
                </td>
            </tr>
        </tfoot>
    </table>
    <p class="description"><?php esc_html_e( 'Voeg hier één of meerdere Place ID’s toe, elk met een eigen label.', 'gre' ); ?></p>
    <?php
}

function gre_enable_shortcode_render() {
    $val = get_option( 'gre_enable_shortcode', 1 );
    printf(
        '<input type="checkbox" id="gre_enable_shortcode" name="gre_enable_shortcode" value="1" %1$s title="Schakel de [google_rating] shortcode in of uit" />
         <label for="gre_enable_shortcode">%2$s</label>
         <span class="dashicons dashicons-editor-help" title="Gebruik deze shortcode in content om rating te tonen."></span>
         <p class="description">%3$s</p>',
        checked( 1, $val, false ),
        esc_html__( 'Enable [google_rating] shortcode', 'gre' ),
        esc_html__( 'Schakel deze shortcode in om de rating in berichten of pagina’s te gebruiken.', 'gre' )
    );
}

function gre_cache_interval_render() {
    $val     = absint( get_option( GRE_OPT_CACHE_INTERVAL, 6 ) );
    $options = [
        1   => __( 'Elke 1 uur', 'gre' ),
        6   => __( 'Elke 6 uur', 'gre' ),
        12  => __( 'Elke 12 uur', 'gre' ),
        24  => __( 'Elke 24 uur', 'gre' ),
        168 => __( 'Elke week', 'gre' ),
    ];

    printf( '<select id="%1$s" name="%1$s" title="Kies hoe vaak de cache wordt vernieuwd">', esc_attr( GRE_OPT_CACHE_INTERVAL ) );
    foreach ( $options as $hours => $label ) {
        printf(
            '<option value="%1$d"%2$s>%3$s</option>',
            $hours,
            selected( $val, $hours, false ),
            esc_html( $label )
        );
    }
    echo '</select>';
    echo '<span class="dashicons dashicons-editor-help" title="Cache-interval bepaalt hoe vaak de plugin nieuwe data ophaalt."></span>';
    echo '<p class="description">' . esc_html__( 'Hoe vaak moet de plugin data bij Google verversen?', 'gre' ) . '</p>';
}

// Render volledige admin pagina met tabbladstructuur
function gre_render_admin_page() {
    $tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'settings';
    echo '<div class="wrap">';
    echo '<h1>' . esc_html__( 'Google Rating Settings', 'gre' ) . '</h1>';
    echo '<h2 class="nav-tab-wrapper">';
    echo '<a href="?page=gre_options&tab=settings" class="nav-tab ' . ( $tab === 'settings' ? 'nav-tab-active' : '' ) . '">' . esc_html__( 'Instellingen', 'gre' ) . '</a>';
    echo '<a href="?page=gre_options&tab=shortcode" class="nav-tab ' . ( $tab === 'shortcode' ? 'nav-tab-active' : '' ) . '">' . esc_html__( 'Uitleg', 'gre' ) . '</a>';
    echo '<a href="?page=gre_options&tab=changelog" class="nav-tab ' . ( $tab === 'changelog' ? 'nav-tab-active' : '' ) . '">' . esc_html__( 'Changelog', 'gre' ) . '</a>';
    echo '</h2>';

    if ( $tab === 'settings' ) {
        echo '<form action="options.php" method="post">';
        settings_fields( 'gre_settings' );
        do_settings_sections( 'gre_settings' );
        submit_button();
        echo '</form>';
    } elseif ( $tab === 'shortcode' ) {
        include __DIR__ . '/view-uitleg.php';
    } elseif ( $tab === 'changelog' ) {
        include __DIR__ . '/view-changelog.php';
    }
    echo '</div>';
}
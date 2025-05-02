<?php
if ( ! defined( 'ABSPATH' ) ) exit;

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

    add_settings_section( 'gre_section', __( 'Instellingen', 'gre' ), '__return_null', 'gre_settings' );

    add_settings_field( GRE_OPT_API_KEY, __( 'API Key', 'gre' ), 'gre_api_key_render', 'gre_settings', 'gre_section' );
    add_settings_field( GRE_OPT_PLACES, __( 'Bedrijven', 'gre' ), 'gre_places_render', 'gre_settings', 'gre_section' );
    add_settings_field( 'gre_enable_shortcode', __( 'Shortcode inschakelen', 'gre' ), 'gre_enable_shortcode_render', 'gre_settings', 'gre_section' );
    add_settings_field( 'gre_test_connection', __( 'Verbinding testen', 'gre' ), 'gre_test_connection_render', 'gre_settings', 'gre_section' );
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
    printf('<input type="password" id="%1$s" name="%1$s" value="%2$s" class="regular-text" /> <span class="toggle-visibility" data-field="%1$s">👁️</span> <span id="gre-api-status" class="gre-status-icon dashicons"></span>', esc_attr( GRE_OPT_API_KEY ), esc_attr( $val ) );
}

function gre_enable_shortcode_render() {
    $val = get_option( 'gre_enable_shortcode', 1 );
    printf('<input type="checkbox" id="gre_enable_shortcode" name="gre_enable_shortcode" value="1" %s /> <label for="gre_enable_shortcode">%s</label>', checked( 1, $val, false ), esc_html__( 'Enable [google_rating] shortcode', 'gre' ));
}

function gre_test_connection_render() {
    echo '<button type="button" class="button button-secondary" id="gre-test-connection-button">' . esc_html__( 'Controleer verbinding', 'gre' ) . '</button><span id="gre-test-connection-result" style="margin-left:12px; vertical-align:middle;"></span>';
}

function gre_places_render() {
    $places = get_option( GRE_OPT_PLACES, [] );
    ?>
    <table id="gre-places-table" class="widefat">
        <thead><tr><th><?php _e('Label','gre');?></th><th><?php _e('Place ID','gre');?></th><th></th></tr></thead>
        <tbody>
        <?php foreach ( $places as $i => $p ): ?>
            <tr>
                <td><input name="gre_places[<?php echo $i; ?>][label]" value="<?php echo esc_attr( $p['label'] ); ?>" /></td>
                <td><input name="gre_places[<?php echo $i; ?>][place_id]" value="<?php echo esc_attr( $p['place_id'] ); ?>" /></td>
                <td><button class="button gre-remove-row">×</button></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr><td colspan="3"><button class="button button-secondary" id="gre-add-row"><?php _e('Bedrijf toevoegen','gre');?></button></td></tr>
        </tfoot>
    </table>
    <script>
    (function($){
        $('#gre-add-row').on('click', function(e){
            e.preventDefault();
            var $tbody = $('#gre-places-table tbody');
            var index  = $tbody.find('tr').length;
            var row    = '<tr>' +
                '<td><input name="gre_places['+index+'][label]" /></td>' +
                '<td><input name="gre_places['+index+'][place_id]" /></td>' +
                '<td><button class="button gre-remove-row">×</button></td>' +
                '</tr>';
            $tbody.append(row);
        });
        $(document).on('click','.gre-remove-row',function(e){
            e.preventDefault();
            $(this).closest('tr').remove();
        });
    })(jQuery);
    </script>
    <?php
}

// Render volledige admin pagina met tabbladstructuur
function gre_render_admin_page() {
    $tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'settings';
    echo '<div class="wrap">';
    echo '<h1>' . esc_html__( 'Google Rating Settings', 'gre' ) . '</h1>';
    echo '<h2 class="nav-tab-wrapper">';
    echo '<a href="?page=gre_options&tab=settings" class="nav-tab ' . ($tab === 'settings' ? 'nav-tab-active' : '') . '">' . esc_html__('Instellingen','gre') . '</a>';
    echo '<a href="?page=gre_options&tab=shortcode" class="nav-tab ' . ($tab === 'shortcode' ? 'nav-tab-active' : '') . '">' . esc_html__('Uitleg','gre') . '</a>';
    echo '<a href="?page=gre_options&tab=changelog" class="nav-tab ' . ($tab === 'changelog' ? 'nav-tab-active' : '') . '">' . esc_html__('Changelog','gre') . '</a>';
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

<?php
/**
 * Renders the “Instellingen” tab content
 */
function gre_render_settings_page() {
    echo '<div class="wrap gre-admin-wrap">';

   // API & Place ID kaart
echo '<div class="gre-card">';
    echo '<h3>' . esc_html__( 'API & Place ID', 'gre' ) . '</h3>';
    echo '<form action="options.php" method="post">';
settings_fields( 'gre_api_settings' );

        echo '<div class="gre-setting">';
            echo '<label for="' . esc_attr( GRE_OPT_API_KEY ) . '">' 
                 . esc_html__( 'API Key', 'gre' ) 
                 . '</label>';
            gre_api_key_render();
        echo '</div>';

        echo '<div class="gre-setting">';
            echo '<label for="' . esc_attr( GRE_OPT_PLACE_ID ) . '">' 
                 . esc_html__( 'Place ID', 'gre' ) 
                 . '</label>';
            gre_place_id_render();
        echo '</div>';

        echo '<div class="gre-setting">';
            echo '<label>&nbsp;</label>';
            gre_test_connection_render();
        echo '</div>';

        // ✅ Toevoegen submit knop
        submit_button( __( 'Instellingen opslaan', 'gre' ) );

    echo '</form>';
echo '</div>';



   // Cache & Shortcode kaart
echo '<div class="gre-card">';
    echo '<h3>' . esc_html__( 'Cache & Shortcode', 'gre' ) . '</h3>';
    echo '<form action="options.php" method="post">';
settings_fields( 'gre_plugin_settings' );

        echo '<div class="gre-setting">';
            echo '<label for="gre_cache_ttl">' 
                 . esc_html__( 'Cache duur', 'gre' ) 
                 . '</label>';
            gre_cache_ttl_render();
        echo '</div>';

        echo '<div class="gre-setting">';
            echo '<label for="gre_enable_shortcode">' 
                 . esc_html__( 'Shortcode inschakelen', 'gre' ) 
                 . '</label>';
            gre_enable_shortcode_render();
        echo '</div>';

        // ✅ Toevoegen submit knop
        submit_button( __( 'Instellingen opslaan', 'gre' ) );

    echo '</form>';
echo '</div>';



    //
    // 🔄 Ververs & Teller kaart
    //
    echo '<div class="gre-card">';
        echo '<h3>' . esc_html__( 'Data verversen', 'gre' ) . '</h3>';
        // Ververs data knop + resultaat
        gre_force_refresh_render();

        // API-call teller
        $calls = (int) get_option( 'gre_api_call_count', 0 );
        echo '<p><strong>' 
             . esc_html__( 'API-calls via cron/fetch:', 'gre' ) 
             . '</strong> ' 
             . esc_html( $calls ) 
             . '</p>';

        // Reset teller knop
        echo '<form method="post">';
            wp_nonce_field( 'gre_reset_calls' );
            submit_button( 
                esc_html__( 'Reset API-call teller', 'gre' ), 
                'secondary', 
                'gre_reset_calls_button' 
            );
        echo '</form>';
    echo '</div>';


    echo '</div>'; // .wrap.gre-admin-wrap
}

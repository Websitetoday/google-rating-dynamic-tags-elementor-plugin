<?php
function gre_render_settings_page() {
    echo '<div class="wrap gre-admin-wrap">';

    // API & Place ID kaart
    echo '<div class="gre-card">';
        echo '<h3>' . esc_html__( 'API & Place ID', 'gre' ) . '</h3>';
        echo '<form action="options.php" method="post">';
            settings_fields( 'gre_api_settings' );

            // API Key
            echo '<div class="gre-setting">';
                echo '<label for="' . esc_attr( GRE_OPT_API_KEY ) . '">';
                    echo esc_html__( 'API Key', 'gre' );
                    echo ' <span class="gre-question-icon">?</span>';
                echo '</label>';
                gre_api_key_render();
                echo '<p class="gre-field-help">'
                    . esc_html__('Je API Key maak je aan in het Google Cloud Platform onder APIs & Services > Credentials. ', 'gre')
                    . '<a href="https://console.cloud.google.com/apis/credentials" target="_blank" rel="noopener" class="gre-help-link">'
                    . esc_html__('Bekijk de instructies', 'gre')
                    . '</a>'
                    . '</p>';
            echo '</div>';

            // Place ID
            echo '<div class="gre-setting">';
                echo '<label for="' . esc_attr( GRE_OPT_PLACE_ID ) . '">';
                    echo esc_html__( 'Place ID', 'gre' );
                    echo ' <span class="gre-question-icon">?</span>';
                echo '</label>';
                gre_place_id_render();
                echo '<p class="gre-field-help">'
                    . esc_html__('Je vindt jouw Place ID via de officiële ', 'gre')
                    . '<a href="https://developers.google.com/maps/documentation/places/web-service/place-id" target="_blank" rel="noopener" class="gre-help-link">'
                    . esc_html__('Google Place ID Finder', 'gre')
                    . '</a>'
                    . '</p>';
            echo '</div>';

            // Controleer verbinding
            echo '<div class="gre-setting">';
                echo '<label>&nbsp;</label>';
                gre_test_connection_render();
            echo '</div>';

            // Submit knop
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
                     . esc_html__( 'Cache informatie', 'gre' )
                     . '</label>';
                echo '<p style="margin-top:4px;">'
                    . esc_html__( 'De Google-data wordt maximaal één keer per week opgehaald en lokaal opgeslagen. Hiermee voorkom je hoge kosten door onnodige API-calls.', 'gre' )
                    . '</p>';
            echo '</div>';

            echo '<div class="gre-setting">';
                echo '<label for="gre_enable_shortcode">'
                     . esc_html__( 'Shortcode inschakelen', 'gre' )
                     . '</label>';
                gre_enable_shortcode_render();
            echo '</div>';

            // Submit knop
            submit_button( __( 'Instellingen opslaan', 'gre' ) );
        echo '</form>';
    echo '</div>';

    // Ververs data kaart
    echo '<div class="gre-card">';
        echo '<h3>' . esc_html__( 'Data verversen', 'gre' ) . '</h3>';
        gre_force_refresh_render();
    echo '</div>';

    echo '</div>'; // .wrap.gre-admin-wrap
}

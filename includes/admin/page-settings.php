<?php
function gre_render_settings_page() {
    echo '<div class="wrap gre-admin-wrap">';

    // API & Place ID kaart
    echo '<div class="gre-card">';
        echo '<h3>API &amp; Place ID</h3>';
        echo '<form action="options.php" method="post">';
            settings_fields( 'gre_api_settings' );

            // API Key
echo '<div class="gre-setting">';
    echo '<label for="' . esc_attr( GRE_OPT_API_KEY ) . '">';
        echo 'API Key';
        echo ' <span class="gre-question-icon">?</span>';
    echo '</label>';
    gre_api_key_render();
    echo '<p class="gre-field-help">'
        . 'Je API Key maak je aan in het Google Cloud Platform onder APIs &amp; Services &gt; Credentials. '
        . 'Let op: zorg ervoor dat je ook de "Places API" activeert in het API-beheer van je project. Zonder deze actieve API werkt de plugin niet correct.'
        . '<br><a href="https://console.cloud.google.com/apis/credentials" target="_blank" rel="noopener" class="gre-help-link">'
        . 'Bekijk de instructies of maak direct je sleutel aan'
        . '</a>'
        . '<br><a href="https://console.cloud.google.com/apis/library/places-backend.googleapis.com" target="_blank" rel="noopener" class="gre-help-link">'
        . 'Activeer hier direct de Places API'
        . '</a>'
        . '</p>';
echo '</div>';


            // Place ID
            echo '<div class="gre-setting">';
                echo '<label for="' . esc_attr( GRE_OPT_PLACE_ID ) . '">';
                    echo 'Place ID';
                    echo ' <span class="gre-question-icon">?</span>';
                echo '</label>';
                gre_place_id_render();
                echo '<p class="gre-field-help">'
                    . 'Je vindt jouw Place ID via de officiële '
                    . '<a href="https://developers.google.com/maps/documentation/places/web-service/place-id" target="_blank" rel="noopener" class="gre-help-link">'
                    . 'Google Place ID Finder'
                    . '</a>'
                    . '</p>';
            echo '</div>';

            // Controleer verbinding
            echo '<div class="gre-setting">';
                echo '<label>&nbsp;</label>';
                gre_test_connection_render();
            echo '</div>';

            // Submit knop
            submit_button( 'Instellingen opslaan' );
        echo '</form>';
    echo '</div>';

    // Cache & Shortcode kaart
    echo '<div class="gre-card">';
        echo '<h3>Cache &amp; Shortcode</h3>';
        echo '<form action="options.php" method="post">';
            settings_fields( 'gre_plugin_settings' );

            echo '<div class="gre-setting">';
                echo '<label for="gre_cache_ttl">Cache informatie</label>';
                echo '<p style="margin-top:4px;">'
                    . 'De Google-data wordt maximaal één keer per week opgehaald en lokaal opgeslagen. Hiermee voorkom je hoge kosten door onnodige API-calls.'
                    . '</p>';
            echo '</div>';

            echo '<div class="gre-setting">';
                echo '<label for="gre_enable_shortcode">Shortcode inschakelen</label>';
                gre_enable_shortcode_render();
            echo '</div>';

            // Submit knop
            submit_button( 'Instellingen opslaan' );
        echo '</form>';
    echo '</div>';

    // Ververs data kaart
    echo '<div class="gre-card">';
        echo '<h3>Data verversen</h3>';
        gre_force_refresh_render();
    echo '</div>';

    // Plugin updates kaart
    echo '<div class="gre-card">';
        echo '<h3>Plugin Updates</h3>';
        gre_check_updates_render();
    echo '</div>';

    echo '</div>'; // .wrap.gre-admin-wrap
}

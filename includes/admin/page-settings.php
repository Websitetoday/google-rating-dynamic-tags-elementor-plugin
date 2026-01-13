<?php
function gre_render_settings_page() {
    echo '<div class="wrap gre-admin-wrap">';

    // API Key kaart
    echo '<div class="gre-card">';
        echo '<h3>API Key</h3>';
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

            // Submit knop
            submit_button( 'API Key opslaan' );
        echo '</form>';
    echo '</div>';

    // Bedrijven beheer kaart
    echo '<div class="gre-card">';
        echo '<h3>Bedrijven</h3>';
        echo '<p class="description">Beheer hier de gekoppelde Google bedrijven. Je kunt meerdere bedrijven toevoegen en per widget/shortcode kiezen welk bedrijf getoond wordt.</p>';

        gre_render_businesses_list();

        echo '<hr style="margin: 20px 0;">';
        echo '<h4>Nieuw bedrijf toevoegen</h4>';
        gre_render_add_business_form();
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

    // Plugin updates kaart
    echo '<div class="gre-card">';
        echo '<h3>Plugin Updates</h3>';
        gre_check_updates_render();
    echo '</div>';

    echo '</div>'; // .wrap.gre-admin-wrap
}

/**
 * Render de lijst met bedrijven
 */
function gre_render_businesses_list() {
    $businesses = gre_get_businesses();

    if (empty($businesses)) {
        echo '<p class="gre-no-businesses">Nog geen bedrijven gekoppeld. Voeg hieronder je eerste bedrijf toe.</p>';
        return;
    }

    echo '<table class="wp-list-table widefat fixed striped gre-businesses-table">';
    echo '<thead><tr>';
    echo '<th style="width:30%;">Naam</th>';
    echo '<th style="width:25%;">Place ID</th>';
    echo '<th style="width:15%;">Rating</th>';
    echo '<th style="width:15%;">Reviews</th>';
    echo '<th style="width:15%;">Acties</th>';
    echo '</tr></thead>';
    echo '<tbody>';

    foreach ($businesses as $id => $business) {
        $data = $business['data'] ?? [];
        $rating = $data['rating'] ?? '-';
        $reviews = $data['user_ratings_total'] ?? '-';
        $name = $business['name'] ?? 'Onbekend';
        $place_id = $business['place_id'] ?? '-';
        $is_default = ($id === 'default');

        echo '<tr data-business-id="' . esc_attr($id) . '">';
        echo '<td><strong>' . esc_html($name) . '</strong>';
        if ($is_default) {
            echo ' <span class="gre-badge-default">Standaard</span>';
        }
        echo '</td>';
        echo '<td><code style="font-size:11px;">' . esc_html(substr($place_id, 0, 20)) . '...</code></td>';
        echo '<td>' . esc_html($rating) . ' &#9733;</td>';
        echo '<td>' . esc_html($reviews) . '</td>';
        echo '<td>';
        echo '<button type="button" class="button button-small gre-refresh-business" data-business-id="' . esc_attr($id) . '">Ververs</button> ';
        if (!$is_default) {
            echo '<button type="button" class="button button-small button-link-delete gre-delete-business" data-business-id="' . esc_attr($id) . '">Verwijder</button>';
        }
        echo '</td>';
        echo '</tr>';
    }

    echo '</tbody></table>';
}

/**
 * Render het formulier voor nieuw bedrijf
 */
function gre_render_add_business_form() {
    echo '<div class="gre-add-business-form">';

    echo '<div class="gre-setting">';
        echo '<label for="gre_new_place_id">Place ID</label>';
        echo '<input type="text" id="gre_new_place_id" name="gre_new_place_id" class="regular-text" placeholder="ChIJ..." />';
        echo '<p class="gre-field-help">'
            . 'Vind je Place ID via de '
            . '<a href="https://developers.google.com/maps/documentation/places/web-service/place-id" target="_blank" rel="noopener">Google Place ID Finder</a>'
            . '</p>';
    echo '</div>';

    echo '<div class="gre-setting">';
        echo '<label>&nbsp;</label>';
        echo '<button type="button" class="button button-primary" id="gre-add-business-button">Bedrijf toevoegen</button>';
        echo ' <span id="gre-add-business-result" style="margin-left:12px;"></span>';
    echo '</div>';

    echo '</div>';
}

<?php
/**
 * Renders the “Uitleg” tab content, met eerst de Dynamic Tags uitleg
 * en onderaan de Shortcode Uitleg, in een moderne kaart‐layout.
 */
function gre_render_shortcode_page() {
    echo '<div class="wrap gre-admin-wrap">';

    // Elementor Rating Dynamic Tag kaart
    echo '<div class="gre-card">';
        echo '<h3>' . esc_html__( 'Elementor Dynamic Tags Uitleg', 'gre' ) . '</h3>';
        echo '<p>' . esc_html__( 'Gebruik de Google Rating dynamic tag in Elementor Pro met de volgende stappen:', 'gre' ) . '</p>';
        echo '<ol>';
            echo '<li>' . esc_html__( 'Open een widget die dynamic tags ondersteunt, zoals een Heading of Tekstbewerker.', 'gre' ) . '</li>';
            echo '<li>' . esc_html__( 'Klik op het Dynamic Tags icoon naast het veld.', 'gre' ) . '</li>';
            echo '<li>' . esc_html__( 'Selecteer "Google Rating" in de lijst.', 'gre' ) . '</li>';
            echo '<li>' . esc_html__( 'Kies in de tag-instellingen het gewenste veld: Rating als nummer, Rating met ster, Aantal reviews of Beide.', 'gre' ) . '</li>';
            echo '<li>' . esc_html__( 'Pas de styling aan via de widget-instellingen.', 'gre' ) . '</li>';
        echo '</ol>';
    echo '</div>';

    // Elementor Opening Hours Dynamic Tag kaart
    echo '<div class="gre-card">';
        echo '<h3>' . esc_html__( 'Google Openingstijden Dynamic Tag Uitleg', 'gre' ) . '</h3>';
        echo '<p>' . esc_html__( 'Met deze tag kun je de openingstijden van je bedrijf tonen. Volg de stappen hieronder in Elementor Pro:', 'gre' ) . '</p>';
        echo '<ol>';
            echo '<li>' . esc_html__( 'Open een widget die dynamic tags ondersteunt, zoals een Tekstbewerker of Lijst.', 'gre' ) . '</li>';
            echo '<li>' . esc_html__( 'Klik op het Dynamic Tags icoon naast het veld.', 'gre' ) . '</li>';
            echo '<li>' . esc_html__( 'Selecteer "Google Opening Hours" in de lijst.', 'gre' ) . '</li>';
            echo '<li>' . esc_html__( 'In de tag-instellingen kun je de volgende weergave-opties kiezen:', 'gre' ) . '</li>';
                echo '<ul>';
                    echo '<li>' . esc_html__( 'Volledige week (lijst met alle dagen en tijden)', 'gre' ) . '</li>';
                    echo '<li>' . esc_html__( 'Vandaag (alleen openingstijden voor de huidige dag)', 'gre' ) . '</li>';
                    echo '<li>' . esc_html__( 'Open/gesloten status (tekst)', 'gre' ) . '</li>';
                echo '</ul>';
            echo '<li>' . esc_html__( 'Pas de styling en sjabloon aan via de widget-instellingen.', 'gre' ) . '</li>';
        echo '</ol>';
    echo '</div>';

    // Shortcode uitleg kaart
    echo '<div class="gre-card">';
        echo '<h3>' . esc_html__( 'Shortcode Uitleg', 'gre' ) . '</h3>';
        echo '<p>' . esc_html__( 'Gebruik de shortcode met parameter <code>field</code> om de gewenste output te bepalen. Mogelijke waarden:', 'gre' ) . '</p>';
        echo '<ul>';
            echo '<li><code>rating_number</code> – ' . esc_html__( 'Gemiddelde score als nummer', 'gre' ) . '</li>';
            echo '<li><code>rating_star</code>   – ' . esc_html__( 'Gemiddelde score + ster', 'gre' ) . '</li>';
            echo '<li><code>count_number</code>  – ' . esc_html__( 'Aantal reviews als nummer', 'gre' ) . '</li>';
            echo '<li><code>both</code>          – ' . esc_html__( 'Score + Aantal (tekst)', 'gre' ) . '</li>';
        echo '</ul>';
        echo '<p>' . esc_html__( 'Voorbeeld gebruik:', 'gre' ) . ' <code>[google_rating field="rating_star"]</code></p>';
    echo '</div>';

// Podium voor de ontwikkelaar
echo '<div class="gre-card" style="text-align:center;">';
    echo '<a href="https://www.websitetoday.nl/" target="_blank" rel="noopener">';
        echo '<img src="https://www.websitetoday.nl/wp-content/uploads/2021/09/Websitetoday-Logo-Oranje-PNG-100-100.png" '
            . 'alt="Websitetoday.nl" '
            . 'style="height:32px; vertical-align:middle; margin-right:8px;" />';
        echo esc_html__( 'Powered by Websitetoday.nl', 'gre' );
    echo '</a>';
echo '</div>';

    echo '</div>'; // .wrap.gre-admin-wrap
}
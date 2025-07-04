<?php
/**
 * Renders the “Uitleg” tab content, met eerst de Dynamic Tags uitleg
 * en onderaan de Shortcode Uitleg.
 */
function gre_render_shortcode_page() {
    ?>
    <!-- Elementor Rating Dynamic Tag -->
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

    <!-- Elementor Opening Hours Dynamic Tag -->
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

    <hr/>

    <!-- Shortcode uitleg onderaan -->
    <h2><?php esc_html_e( 'Shortcode Uitleg', 'gre' ); ?></h2>
    <p><?php esc_html_e( 'Gebruik de shortcode met parameter <code>field</code> om de gewenste output te bepalen. Mogelijke waarden:', 'gre' ); ?></p>
    <ul>
        <li><code>rating_number</code> – <?php esc_html_e( 'Gemiddelde score als nummer', 'gre' ); ?></li>
        <li><code>rating_star</code>   – <?php esc_html_e( 'Gemiddelde score + ster', 'gre' ); ?></li>
        <li><code>count_number</code>  – <?php esc_html_e( 'Aantal reviews als nummer', 'gre' ); ?></li>
        <li><code>both</code>          – <?php esc_html_e( 'Score + Aantal (tekst)', 'gre' ); ?></li>
    </ul>
    <p><?php esc_html_e( 'Voorbeeld gebruik:', 'gre' ); ?> <code>[google_rating field="rating_star"]</code></p>
    <?php
}

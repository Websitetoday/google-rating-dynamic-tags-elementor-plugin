<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<h2><?php esc_html_e( 'Uitleg', 'gre' ); ?></h2>
<p><?php esc_html_e( 'Gebruik de shortcode met parameter <code>field</code> om output te bepalen. Mogelijke waarden:', 'gre' ); ?></p>
<ul>
    <li><code>rating_number</code> – <?php esc_html_e( 'Gemiddelde score als nummer', 'gre' ); ?></li>
    <li><code>rating_star</code>   – <?php esc_html_e( 'Gemiddelde score + ster', 'gre' ); ?></li>
    <li><code>count_number</code>  – <?php esc_html_e( 'Aantal reviews als nummer', 'gre' ); ?></li>
    <li><code>both</code>          – <?php esc_html_e( 'Score + Aantal (tekst)', 'gre' ); ?></li>
</ul>
<p><?php esc_html_e( 'Voorbeeld:', 'gre' ); ?> <code>[google_rating field="rating_star"]</code></p>
<p><?php esc_html_e( 'Gebruik "place" attribuut om een specifiek bedrijf te tonen. Gebruik "place=\"all\"" om alle bedrijven weer te geven.', 'gre' ); ?></p>

<hr/>
<h2><?php esc_html_e( 'Elementor Dynamic Tags Uitleg', 'gre' ); ?></h2>
<ol>
    <li><?php esc_html_e( 'Open een widget die dynamic tags ondersteunt, zoals een Heading of Tekstbewerker.', 'gre' ); ?></li>
    <li><?php esc_html_e( 'Klik op het Dynamic Tags icoon naast het veld.', 'gre' ); ?></li>
    <li><?php esc_html_e( 'Selecteer "Google Rating" in de lijst.', 'gre' ); ?></li>
    <li><?php esc_html_e( 'Kies in de tag-instellingen het gewenste veld en het juiste bedrijf.', 'gre' ); ?></li>
    <li><?php esc_html_e( 'Pas de styling aan via de widget-instellingen.', 'gre' ); ?></li>
</ol>

<hr/>
<h2><?php esc_html_e( 'Google Openingstijden Dynamic Tag Uitleg', 'gre' ); ?></h2>
<ol>
    <li><?php esc_html_e( 'Open een widget die dynamic tags ondersteunt, zoals een Tekstbewerker of Lijst.', 'gre' ); ?></li>
    <li><?php esc_html_e( 'Klik op het Dynamic Tags icoon naast het veld.', 'gre' ); ?></li>
    <li><?php esc_html_e( 'Selecteer "Google Opening Hours" in de lijst.', 'gre' ); ?></li>
    <li><?php esc_html_e( 'Kies een weergavemodus (vandaag, volledige week, specifieke dag).', 'gre' ); ?></li>
    <li><?php esc_html_e( 'Stel optioneel kleuren en daglabels in.', 'gre' ); ?></li>
</ol>

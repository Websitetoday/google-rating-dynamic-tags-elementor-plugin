<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>

<h2><?php esc_html_e( 'Shortcode Uitleg', 'gre' ); ?></h2>
<p><?php esc_html_e( 'Gebruik de shortcode [google_rating] om Google-beoordelingen van je bedrijf te tonen.', 'gre' ); ?></p>

<h3><?php esc_html_e( 'Beschikbare attributen:', 'gre' ); ?></h3>
<ul>
    <li><code>field</code> – <?php esc_html_e( 'Welke informatie je wilt tonen:', 'gre' ); ?>
        <ul style="margin-top:4px;">
            <li><code>rating_number</code> – <?php esc_html_e( 'Gemiddelde score als nummer (bijv. 4.6)', 'gre' ); ?></li>
            <li><code>rating_star</code>   – <?php esc_html_e( 'Gemiddelde score met ster (bijv. 4.6 ★)', 'gre' ); ?></li>
            <li><code>count_number</code>  – <?php esc_html_e( 'Aantal reviews als nummer', 'gre' ); ?></li>
            <li><code>both</code>          – <?php esc_html_e( 'Gemiddelde + aantal (bijv. 4.6 ★ 128 reviews)', 'gre' ); ?></li>
        </ul>
    </li>
    <li><code>place</code> – <?php esc_html_e( 'Specificeer een bedrijf via Place ID (optioneel).', 'gre' ); ?>
        <ul style="margin-top:4px;">
            <li><code>place="..."</code> – <?php esc_html_e( 'Toont data van dat specifieke bedrijf', 'gre' ); ?></li>
            <li><code>place="all"</code> – <?php esc_html_e( 'Toont data van alle bedrijven (één per regel)', 'gre' ); ?></li>
        </ul>
    </li>
    <li><code>class</code> – <?php esc_html_e( 'Voeg een extra CSS class toe aan de wrapper (optioneel)', 'gre' ); ?></li>
</ul>

<h3><?php esc_html_e( 'Voorbeelden:', 'gre' ); ?></h3>
<ul>
    <li><code>[google_rating field="rating_star"]</code></li>
    <li><code>[google_rating field="count_number" place="all"]</code></li>
    <li><code>[google_rating field="both" place="ChIJ123abc456"]</code></li>
    <li><code>[google_rating field="rating_number" class="mijn-styling"]</code></li>
</ul>

<hr/>
<h2><?php esc_html_e( 'Elementor Dynamic Tags – Google Rating', 'gre' ); ?></h2>
<ol>
    <li><?php esc_html_e( 'Open een widget die Dynamic Tags ondersteunt (zoals Heading of Tekstbewerker).', 'gre' ); ?></li>
    <li><?php esc_html_e( 'Klik op het Dynamic Tags ⚙️-icoon naast het veld.', 'gre' ); ?></li>
    <li><?php esc_html_e( 'Kies "Google Rating" uit de lijst.', 'gre' ); ?></li>
    <li><?php esc_html_e( 'Selecteer het gewenste veld en kies het juiste bedrijf.', 'gre' ); ?></li>
    <li><?php esc_html_e( 'Stijl je output via Elementor zoals je wilt.', 'gre' ); ?></li>
</ol>

<hr/>
<h2><?php esc_html_e( 'Elementor Dynamic Tags – Google Openingstijden', 'gre' ); ?></h2>
<ol>
    <li><?php esc_html_e( 'Open een widget die Dynamic Tags ondersteunt, zoals een Tekstbewerker of Lijst.', 'gre' ); ?></li>
    <li><?php esc_html_e( 'Klik op het Dynamic Tags ⚙️-icoon naast het veld.', 'gre' ); ?></li>
    <li><?php esc_html_e( 'Selecteer "Google Opening Hours" in de lijst.', 'gre' ); ?></li>
    <li><?php esc_html_e( 'Kies een weergavemodus (vandaag, volledige week, specifieke dag).', 'gre' ); ?></li>
    <li><?php esc_html_e( 'Stel optioneel kleuren en daglabels in voor styling.', 'gre' ); ?></li>
</ol>

<hr/>
<h2><?php esc_html_e( 'API & Place ID instellen', 'gre' ); ?></h2>
<ul>
    <li>
        <?php esc_html_e( '📍 Vind de Place ID van je bedrijf via de officiële tool:', 'gre' ); ?>
        <a href="https://developers.google.com/maps/documentation/places/web-service/place-id" target="_blank" rel="noopener">Place ID Finder</a>
    </li>
    <li>
        <?php esc_html_e( '🔑 Nog geen Google API Key?', 'gre' ); ?>
        <a href="https://developers.google.com/maps/documentation/places/web-service/get-api-key" target="_blank" rel="noopener">Bekijk de instructies om een API Key aan te maken</a>
    </li>
</ul>

<?php
if ( ! function_exists('gre_render_explainer_page') ) {
    function gre_render_explainer_page() {
        echo '<div class="wrap gre-admin-wrap">';
        echo '<div class="gre-card">';
        echo '<h2>Uitleg &amp; Gebruik</h2>';
        ?>

        <h3>Shortcode: <code>[google_rating]</code></h3>
        <p>
            Toon eenvoudig je Google Bedrijfsbeoordeling waar je maar wilt in je site!<br>
            <strong>Voorbeeldgebruik:</strong>
        </p>
        <ul>
            <li><code>[google_rating]</code> (standaard: <strong>4.8 ★ 123 reviews</strong>)</li>
            <li><code>[google_rating field="rating_star"]</code> (alleen de sterrenbeoordeling)</li>
            <li><code>[google_rating field="rating_number"]</code> (alleen het cijfer)</li>
            <li><code>[google_rating field="count_number"]</code> (alleen het aantal reviews)</li>
            <li><code>[google_rating field="opening_hours"]</code> (weergeeft de huidige openingstijden)</li>
            <li><code>[google_rating field="badge"]</code> <strong>(NIEUW!)</strong> - Mooie badge met Google logo, sterren, score en aantal reviews</li>
        </ul>

        <h3>Google Rating Badge</h3>
        <p>
            Met <code>[google_rating field="badge"]</code> toon je een professionele Google Reviews badge:<br>
            <span style="display:inline-flex;align-items:center;gap:8px;padding:8px 16px;background:#fff;border-radius:24px;box-shadow:0 1px 3px rgba(0,0,0,0.12);font-size:14px;margin:10px 0;">
                <span style="color:#4285F4;font-weight:500;">Google</span>
                <span style="color:#FBBC04;">&#9733;&#9733;&#9733;&#9733;&#9733;</span>
                <strong>5,0</strong>
                <span style="color:#dadce0;">|</span>
                <span style="color:#5f6368;">24 reviews</span>
            </span>
        </p>
        <p>De badge bevat:</p>
        <ul>
            <li>Officieel Google logo</li>
            <li>Gekleurde sterren (vol, half, leeg) gebaseerd op je score</li>
            <li>Gemiddelde score</li>
            <li>Aantal reviews</li>
        </ul>

        <h3>Styling & Elementor</h3>
        <p>
            Deze shortcode is volledig te stylen met eigen CSS.<br>
            Ook beschikbaar als <strong>Dynamic Tag</strong> binnen Elementor PRO.
        </p>
        <ul>
            <li>Ga naar je Elementor widget > Dynamic Tags</li>
            <li>Kies onder "Websitetoday Google Rating" het gewenste veld</li>
            <li>Styling gebeurt via Elementor of met je eigen thema-CSS</li>
        </ul>

        <h3>Veelgestelde vragen</h3>
        <ul>
            <li><strong>Hoe vraag ik een API Key aan?</strong><br>
                Ga naar <a href="https://console.cloud.google.com/apis/credentials" target="_blank" rel="noopener">Google Cloud Console</a>, maak een project aan en genereer daar je API Key. Vergeet niet om ook de <strong>Places API</strong> te activeren voor dit project.</li>

            <li><strong>Hoe vind ik mijn Place ID?</strong><br>
                Gebruik de <a href="https://developers.google.com/maps/documentation/places/web-service/place-id" target="_blank" rel="noopener">Google Place ID Finder</a>. Zoek op jouw bedrijfsnaam en kopieer de Place ID.</li>

            <li><strong>Hoe gebruik ik de Dynamic Tags in Elementor?</strong><br>
                Selecteer in een Elementor-widget een Dynamic Tag en kies een veld onder <em>Websitetoday Google Rating</em>. Zo kun je bijvoorbeeld de sterrenbeoordeling of het aantal reviews tonen.</li>

            <li><strong>Welke data kan ik allemaal tonen?</strong><br>
                Je kunt o.a. de volgende gegevens tonen:
                <ul>
                    <li>Sterrencijfer</li>
                    <li>Aantal reviews</li>
                    <li>Volledige beoordelingstekst</li>
                    <li>Openingstijden (live)</li>
                    <li>Reviewgegevens (toekomstige uitbreiding)</li>
                </ul>
            </li>

            <li><strong>Hoe vaak wordt de data opgehaald van Google?</strong><br>
                De plugin maakt standaard maximaal <strong>één keer per week</strong> een API-call. De gegevens worden lokaal opgeslagen (gecached) om API-kosten te beperken.</li>

            <li><strong>Ik krijg een foutmelding. Wat nu?</strong><br>
                Controleer of je API Key en Place ID correct zijn ingevuld én of de <strong>Places API</strong> is geactiveerd in je Google Cloud project.</li>
        </ul>

        <h3>Ondersteuning</h3>
        <p>
            <a href="mailto:info@websitetoday.nl">info@websitetoday.nl</a><br>
            Of bekijk <a href="https://github.com/Websitetoday/google-rating-dynamic-tags-elementor-plugin" target="_blank" rel="noopener">de handleiding op GitHub</a>
        </p>

        <?php
        echo '</div>'; // einde .gre-card
        echo '</div>'; // einde .gre-admin-wrap
    }
}


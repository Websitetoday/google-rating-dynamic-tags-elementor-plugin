<?php
if ( ! function_exists('gre_render_explainer_page') ) {
    function gre_render_explainer_page() {
        echo '<div class="wrap gre-admin-wrap">';
        echo '<div class="gre-card">';
        echo '<h2>' . esc_html__( 'Uitleg & Gebruik', 'gre' ) . '</h2>';
        ?>

        <h3>Shortcode: <code>[google_rating]</code></h3>
        <p>
            Toon eenvoudig je Google Bedrijfsbeoordeling waar je maar wilt in je site!<br>
            <strong>Voorbeeldgebruik:</strong>
        </p>
        <ul>
            <li><code>[google_rating]</code> (standaard: <strong>4.8 ★ 123 reviews</strong>)</li>
            <li><code>[google_rating field="rating_star"]</code> (zelfde als standaard)</li>
            <li><code>[google_rating field="rating_number"]</code> (alleen het cijfer)</li>
            <li><code>[google_rating field="count_number"]</code> (alleen aantal reviews)</li>
        </ul>

        <h3>Styling & Elementor</h3>
        <p>
            Deze shortcode is volledig te stylen met eigen CSS.<br>
            Ook beschikbaar als <strong>Dynamic Tag</strong> binnen Elementor PRO!
        </p>

        <h3>Veelgestelde vragen</h3>
        <ul>
            <li><strong>Hoe vaak worden mijn gegevens vernieuwd?</strong><br>
                Maximaal één keer per week, dankzij caching en cronjob.</li>
            <li><strong>Hoe reset ik de teller?</strong><br>
                Gebruik de knop “Reset API-call teller” in het instellingen-tabblad.</li>
            <li><strong>Foutmelding?</strong><br>
                Controleer of je API-key en Place ID correct zijn ingevuld én of de juiste API’s aan staan in Google Cloud.</li>
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

<?php
/**
 * Renders the “Changelog” tab content
 */
function gre_render_changelog_page() {
    ?>
    <h2><?php esc_html_e( 'Changelog', 'gre' ); ?></h2>
    <ul>
        <li><strong>3.0.2</strong> – <?php esc_html_e( 'Feature: API-call teller toegevoegd in Instellingen tab met reset-knop; teller nu onderaan geplaatst; teller meet alleen cron- en handmatige ververs-data API-calls; Vermindering van live API calls door cron-only fetch-model, calls worden beperkt tot het gekozen cache-interval.', 'gre' ); ?></li>
        <li><strong>3.0.1</strong> – <?php esc_html_e( 'Fix: changelog-uitvoer verplaatst naar de admin Changelog-tab en verwijderd uit de front-end weergave.', 'gre' ); ?></li>
        <li><strong>3.0.0</strong> – <?php esc_html_e( 'Refactor: monolithische admin-pagina opgesplitst in modulair opgebouwde instellingen-, uitleg- en changelog-bestand; ondersteuning voor meerdere bedrijven verwijderd (nu enkel één Place ID); uninstall.php toegevoegd voor volledige cleanup bij deïnstallatie; info-tooltips bij API Key en Place ID velden toegevoegd.', 'gre' ); ?></li>
        <li><strong>2.1.0</strong> – <?php esc_html_e( 'Batch fetching van alle Place Details via WP-Cron taak op basis van de cache-interval instelling; Configurabele cache-interval dropdown (1, 6, 12, 24 uur of elke week); Admin CSS voor moderne tabelstyling en responsive weergave; Tooltips en subtiele beschrijvingen bij alle instellingen en tabelvelden; gre-admin.js geüpdatet met spinner-icoon, kleurclasses en dynamische TTL voor test-transient; CSS en JS geladen via admin_enqueue_scripts hook op juiste admin-pagina; gre_fetch_google_place_data() aangepast naar batch-transient (gre_all_places_data), waardoor paginalaag geen live API-calls meer uitvoert.', 'gre' ); ?></li>
        <li><strong>2.0.2</strong> – <?php esc_html_e( 'Controleer individuele bedrijven via een "Check"-knop met statusicoon. Resultaat wordt onthouden tot de Place ID wijzigt.', 'gre' ); ?></li>
        <li><strong>2.0.1</strong> – <?php esc_html_e( 'Bedrijfsnaam zichtbaar bij Elementor Dynamic Tag "Google Rating".', 'gre' ); ?></li>
        <li><strong>2.0.0</strong> – <?php esc_html_e( 'Ondersteuning voor meerdere bedrijven met eigen Place IDs via shortcode en Dynamic Tags.', 'gre' ); ?></li>
        <li><strong>1.5.6</strong> – <?php esc_html_e( 'Nieuwe functie: Elementor Dynamic Tag toegevoegd voor Google Openingstijden.', 'gre' ); ?></li>
        <li><strong>1.5.5</strong> – <?php esc_html_e( 'Uitleg-tab bijgewerkt met uitgebreide instructies over Openingstijden Dynamic Tag.', 'gre' ); ?></li>
        <li><strong>1.5.4</strong> – <?php esc_html_e( 'Real-time statusicoontjes voor API Key en Place ID toegevoegd.', 'gre' ); ?></li>
        <li><strong>1.5.3</strong> – <?php esc_html_e( 'Nieuwe verbindingscheck met foutmelding bij ongeldige invoer.', 'gre' ); ?></li>
        <li><strong>1.5.2</strong> – <?php esc_html_e( 'Niet-werkende knoppen voor test/refresh verwijderd, inclusief AJAX.', 'gre' ); ?></li>
        <li><strong>1.5.1</strong> – <?php esc_html_e( 'Shortcode-registratie hersteld voor correcte werking.', 'gre' ); ?></li>
        <li><strong>1.5.0</strong> – <?php esc_html_e( 'Plugin Update URI ingesteld; ondersteuning voor GitHub Releases toegevoegd.', 'gre' ); ?></li>
        <li><strong>1.4.2</strong> – <?php esc_html_e( 'Knoppen toegevoegd voor testverbinding & data verversen.', 'gre' ); ?></li>
        <li><strong>1.4.1</strong> – <?php esc_html_e( 'Verouderde clear_cache-hook verwijderd.', 'gre' ); ?></li>
        <li><strong>1.4.0</strong> – <?php esc_html_e( 'Nieuw admin-menu en tabstructuur.', 'gre' ); ?></li>
        <li><strong>1.3.2</strong> – <?php esc_html_e( 'Syntaxfouten opgelost in register_tags_group.', 'gre' ); ?></li>
        <li><strong>1.3.1</strong> – <?php esc_html_e( 'Standaard Dynamic Tag-instelling verbeterd.', 'gre' ); ?></li>
        <li><strong>1.3.0</strong> – <?php esc_html_e( 'Nieuw veld: aantal reviews als getal, voor gebruik in tellers.', 'gre' ); ?></li>
        <li><strong>1.2.0</strong> – <?php esc_html_e( 'Groepering Google Rating-tags onder eigen kop.', 'gre' ); ?></li>
        <li><strong>1.1.1</strong> – <?php esc_html_e( 'Author URI toegevoegd; verouderde Place ID-controle verwijderd.', 'gre' ); ?></li>
    </ul>
    <?php
}

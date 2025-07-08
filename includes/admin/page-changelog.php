<?php
/**
 * Renders the “Changelog” tab content
 */
function gre_render_changelog_page() {
    ?>
    <div class="wrap gre-admin-wrap">
        <h2><?php esc_html_e( 'Changelog', 'gre' ); ?></h2>
        <ul>
            <li>
                <span class="gre-badge gre-badge--new"><?php esc_html_e( 'New', 'gre' ); ?></span>
                <strong>3.0.2</strong> – <?php esc_html_e( 'Feature: API-call teller toegevoegd in Instellingen tab met reset-knop; teller nu onderaan geplaatst; teller meet alleen cron- en handmatige ververs-data API-calls; vermindering van live API calls door cron-only fetch-model (calls beperkt tot het gekozen cache-interval).', 'gre' ); ?>
            </li>
            <li>
                <span class="gre-badge gre-badge--fix"><?php esc_html_e( 'Fix', 'gre' ); ?></span>
                <strong>3.0.1</strong> – <?php esc_html_e( 'Quick fix: changelog-uitvoer verplaatst naar de admin Changelog-tab en verwijderd uit de frontend weergave.', 'gre' ); ?>
            </li>
            <li>
                <span class="gre-badge gre-badge--new"><?php esc_html_e( 'New', 'gre' ); ?></span>
                <strong>3.0.0</strong> – <?php esc_html_e( 'Admin-pagina’s modulair opgesplitst in includes/admin/; uninstall.php toegevoegd voor complete cleanup; multi-Place-ID ondersteuning verwijderd; info-tooltips bij API Key en Place ID velden toegevoegd; Cache duur dropdown & Ververs data knop toegevoegd.', 'gre' ); ?>
            </li>
            <li>
                <span class="gre-badge gre-badge--new"><?php esc_html_e( 'New', 'gre' ); ?></span>
                <strong>2.1.0</strong> – <?php esc_html_e( 'Batch fetching van alle Place Details via WP-Cron taak op basis van de cache-interval instelling.', 'gre' ); ?>
            </li>
            <li>
                <span class="gre-badge gre-badge--improvement"><?php esc_html_e( 'Improvement', 'gre' ); ?></span>
                <strong>2.1.0</strong> – <?php esc_html_e( 'Configurabele cache-interval dropdown (1, 6, 12, 24 uur of elke week); Admin CSS voor moderne tabelstyling; tooltips en beschrijvingen toegevoegd; gre-admin.js geüpdatet met spinner-icoon, kleurklassen en dynamische TTL.', 'gre' ); ?>
            </li>
            <li>
                <span class="gre-badge gre-badge--fix"><?php esc_html_e( 'Fix', 'gre' ); ?></span>
                <strong>2.1.0</strong> – <?php esc_html_e( 'gre_fetch_google_place_data() aangepast naar batch-transient (gre_all_places_data), waardoor paginalaag geen live API-calls meer uitvoert.', 'gre' ); ?>
            </li>
            <li>
                <span class="gre-badge gre-badge--improvement"><?php esc_html_e( 'Improvement', 'gre' ); ?></span>
                <strong>2.0.2</strong> – <?php esc_html_e( 'Controleer individuele bedrijven via een "Check"-knop met statusicoon; resultaat wordt onthouden tot de Place ID wijzigt.', 'gre' ); ?>
            </li>
            <li>
                <span class="gre-badge gre-badge--improvement"><?php esc_html_e( 'Improvement', 'gre' ); ?></span>
                <strong>2.0.1</strong> – <?php esc_html_e( 'Bedrijfsnaam zichtbaar bij Elementor Dynamic Tag "Google Rating".', 'gre' ); ?>
            </li>
            <li>
                <span class="gre-badge gre-badge--new"><?php esc_html_e( 'New', 'gre' ); ?></span>
                <strong>2.0.0</strong> – <?php esc_html_e( 'Ondersteuning voor meerdere bedrijven met eigen Place IDs via shortcode en Dynamic Tags.', 'gre' ); ?>
            </li>
            <li>
                <span class="gre-badge gre-badge--new"><?php esc_html_e( 'New', 'gre' ); ?></span>
                <strong>1.5.6</strong> – <?php esc_html_e( 'Nieuwe functie: Elementor Dynamic Tag voor Google Openingstijden.', 'gre' ); ?>
            </li>
            <li>
                <span class="gre-badge gre-badge--improvement"><?php esc_html_e( 'Improvement', 'gre' ); ?></span>
                <strong>1.5.5</strong> – <?php esc_html_e( 'Uitleg-tab uitgebreid met instructies voor Openingstijden Dynamic Tag.', 'gre' ); ?>
            </li>
            <li>
                <span class="gre-badge gre-badge--improvement"><?php esc_html_e( 'Improvement', 'gre' ); ?></span>
                <strong>1.5.4</strong> – <?php esc_html_e( 'Real-time statusicoontjes voor API Key en Place ID toegevoegd.', 'gre' ); ?>
            </li>
            <li>
                <span class="gre-badge gre-badge--fix"><?php esc_html_e( 'Fix', 'gre' ); ?></span>
                <strong>1.5.3</strong> – <?php esc_html_e( 'Verbindingscheck met icoon & foutmeldingen verbeterd.', 'gre' ); ?>
            </li>
            <li>
                <span class="gre-badge gre-badge--fix"><?php esc_html_e( 'Fix', 'gre' ); ?></span>
                <strong>1.5.2</strong> – <?php esc_html_e( 'Niet-werkende Test/Ververs knoppen en AJAX-logica verwijderd.', 'gre' ); ?>
            </li>
            <li>
                <span class="gre-badge gre-badge--fix"><?php esc_html_e( 'Fix', 'gre' ); ?></span>
                <strong>1.5.1</strong> – <?php esc_html_e( 'Shortcode-registratie hersteld voor correcte werking.', 'gre' ); ?>
            </li>
            <li>
                <span class="gre-badge gre-badge--new"><?php esc_html_e( 'New', 'gre' ); ?></span>
                <strong>1.5.0</strong> – <?php esc_html_e( 'Plugin Update URI ingesteld; ondersteuning voor GitHub Releases via PUC v5.', 'gre' ); ?>
            </li>
            <li>
                <span class="gre-badge gre-badge--new"><?php esc_html_e( 'New', 'gre' ); ?></span>
                <strong>1.4.2</strong> – <?php esc_html_e( 'Knoppen voor testverbinding & data verversen toegevoegd.', 'gre' ); ?>
            </li>
            <li>
                <span class="gre-badge gre-badge--fix"><?php esc_html_e( 'Fix', 'gre' ); ?></span>
                <strong>1.4.1</strong> – <?php esc_html_e( 'Verouderde clear_cache-hook verwijderd.', 'gre' ); ?>
            </li>
            <li>
                <span class="gre-badge gre-badge--new"><?php esc_html_e( 'New', 'gre' ); ?></span>
                <strong>1.4.0</strong> – <?php esc_html_e( 'Nieuw admin-menu en tabstructuur.', 'gre' ); ?>
            </li>
            <li>
                <span class="gre-badge gre-badge--fix"><?php esc_html_e( 'Fix', 'gre' ); ?></span>
                <strong>1.3.2</strong> – <?php esc_html_e( 'Syntaxfouten opgelost in register_tags_group.', 'gre' ); ?>
            </li>
            <li>
                <span class="gre-badge gre-badge--improvement"><?php esc_html_e( 'Improvement', 'gre' ); ?></span>
                <strong>1.3.1</strong> – <?php esc_html_e( 'Standaard Dynamic Tag-instelling verbeterd.', 'gre' ); ?>
            </li>
            <li>
                <span class="gre-badge gre-badge--new"><?php esc_html_e( 'New', 'gre' ); ?></span>
                <strong>1.3.0</strong> – <?php esc_html_e( 'Nieuw veld: aantal reviews als getal, voor gebruik in tellers.', 'gre' ); ?>
            </li>
            <li>
                <span class="gre-badge gre-badge--new"><?php esc_html_e( 'New', 'gre' ); ?></span>
                <strong>1.2.0</strong> – <?php esc_html_e( 'Groepering Google Rating-tags onder eigen kop.', 'gre' ); ?>
            </li>
            <li>
                <span class="gre-badge gre-badge--new"><?php esc_html_e( 'New', 'gre' ); ?></span>
                <strong>1.1.1</strong> – <?php esc_html_e( 'Author URI toegevoegd; verouderde Place ID-controle verwijderd.', 'gre' ); ?>
            </li>
        </ul>
    </div>
    <?php
}

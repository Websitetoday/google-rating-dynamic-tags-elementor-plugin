=== Google Rating Dynamic Tags Elementor ===
Contributors: Websitetoday.nl
Tags: elementor, google, rating, dynamic-tags, cache, place-id
Requires at least: 5.0
Tested up to: 6.4
Stable tag: main
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Description ==
Toon eenvoudig de Google Bedrijfsbeoordelingen (gemiddelde score, aantal reviews en link naar reviews) rechtstreeks in je content:

* **Elementor Dynamic Tag**: gebruik de Google Rating als tekst, nummer, ster of gecombineerde weergave.
* **Shortcode**: voeg de rating of het aantal reviews toe via `[google_rating field="rating_star"]`.
* **Cache & Force Refresh**: stel in hoe lang data gecached blijft (1 uur, 12 uur, 24 uur of 1 week) en ververs de data handmatig via de “Ververs data” knop.
* **API-call teller**: bekijk in de Instellingen-pagina hoeveel cron- en handmatige ververs-API-calls zijn gedaan en reset ze met één klik.
* **Uninstall Cleanup**: bij deïnstallatie verwijdert de plugin alle instellingen en transients, zodat je opnieuw met een schone lei kunt beginnen.
* **Automatische updates** via GitHub Releases (PUC v5).

== Installation ==
1. Upload de map `google-rating-dynamic-tags-elementor-plugin` naar de `/wp-content/plugins/` directory.  
2. Activeer de plugin via **Plugins → Geïnstalleerde plugins**.  
3. (Optioneel) Installeer Elementor Pro als je de Dynamic Tag-functionaliteit wilt gebruiken.  
4. Ga naar **Google Rating → Instellingen** en vul je Google Places **API Key** en **Place ID** in.  
5. Stel onder **Cache duur** de gewenste TTL in (1 uur, 12 uur, 24 uur of 1 week).  
6. Klik op **Ververs data** om direct de nieuwste gegevens uit Google op te halen.  
7. Bekijk onderaan de Instellingen-pagina het aantal API-calls en reset de teller indien gewenst.

== Screenshots ==
1. **Banner in modal**  
   ![Banner](screenshot-1.png)  
2. **Instellingenpagina** (tooltips, API Key, Place ID, cache dropdown & ververs knop + teller)  
   ![Instellingen](screenshot-2.png)  
3. **Elementor Dynamic Tag**  
   ![Elementor Dynamic Tag](screenshot-3.png)  
4. **Ververs data knop**  
   ![Ververs data](screenshot-4.png)  

== Changelog ==
= 3.0.2 =
* Feature: API-call teller toegevoegd in Instellingen tab met reset-knop; teller nu onderaan geplaatst; meet alleen cron- en handmatige ververs-data API-calls; vermindering van live API calls door cron-only fetch-model (calls beperkt tot het gekozen cache-interval).

= 3.0.1 =
* Fix: changelog-uitvoer verplaatst naar de admin Changelog-tab en verwijderd uit de front-end weergave.

= 3.0.0 =
* Opgeknipt: admin-pagina’s modulair via separate `includes/admin/*.php`.  
* Uninstall: `uninstall.php` verwijdert alle plugin-opties en transients bij deïnstallatie.  
* Ondersteuning voor meerdere bedrijven verwijderd – nu enkel één Place ID.  
* Info-tooltips en directe links toegevoegd bij **API Key** en **Place ID** velden.  
* **Cache duur** dropdown (1, 12, 24 uur of 1 week) en **Ververs data** knop toegevoegd.

= 2.1.0 =
* Batch fetching van alle Place Details via WP-Cron taak op basis van de cache-interval instelling.  
* Configurabele cache-interval dropdown (1, 6, 12, 24 uur of elke week).  
* Admin CSS voor moderne tabelstyling en responsive weergave.  
* Tooltips en subtiele beschrijvingen bij alle instellingen en tabelvelden.  
* `gre-admin.js` geüpdatet met spinner-icoon, kleurclasses en dynamische TTL voor test-transient.  
* CSS en JS geladen via `admin_enqueue_scripts` hook op juiste admin-pagina.  
* `gre_fetch_google_place_data()` aangepast naar batch-transient (`gre_all_places_data`), waardoor paginalaag geen live API-calls meer uitvoert.

= 2.0.2 =
* Controleer individuele bedrijven via een "Check"-knop met status-icoon. Resultaat wordt onthouden tot de Place ID wijzigt.

= 2.0.1 =
* Bedrijfsnaam zichtbaar bij Elementor Dynamic Tag "Google Rating".

= 2.0.0 =
* Ondersteuning voor meerdere bedrijven met eigen Place IDs via shortcode en Dynamic Tags.

= 1.5.6 =
* Integratie met Plugin Update Checker v5 voor GitHub Releases.  
* Eigen plugin-icoon en screenshots in de **View Details** modal.

= 1.5.4 =
* Real-time statusicoontjes voor API Key & Place ID.

= 1.5.3 =
* Verbeterde verbindingscheck met icoon & foutmeldingen.

= 1.5.2 =
* Fix: verwijderde niet-werkende Test/Ververs knoppen.

= 1.5.1 =
* Tweak: shortcode-registratie hersteld.

= 1.5.0 =
* Ondersteuning GitHub Releases via Update URI.

== Upgrade Notice ==
= 3.0.0 =
De admin-pagina’s zijn nu modulair opgebroken en er is een volledige uninstall-cleanup toegevoegd. Bij het upgraden worden oude transients verwijderd en moet je eenmalig je Place ID en cache-instellingen opnieuw controleren.

== Frequently Asked Questions ==
= Hoe maak ik een API Key aan? =
Klik op het info-icoon naast het API Key-veld of bezoek:  
https://console.cloud.google.com/apis/credentials

= Hoe vind ik mijn Place ID? =
Klik op het info-icoon naast het Place ID-veld of bezoek:  
https://developers.google.com/maps/documentation/places/web-service/place-id

= Hoe stel ik de cache-duur in? =
Gebruik de **Cache duur** dropdown in de instellingen (1 uur, 12 uur, 24 uur of 1 week).

= Hoe ververs ik de data handmatig? =
Klik op de **Ververs data** knop onderaan de Instellingen tab.

= Hoe meet ik API-call gebruik? =
Bekijk onderaan de Instellingen-pagina het aantal cron- en handmatige ververs-data API-calls. Reset de teller met de “Reset API-call teller” knop.

== Support ==
Rapporteer bugs of feature requests via GitHub Issues:  
https://github.com/Websitetoday/google-rating-dynamic-tags-elementor-plugin/issues  

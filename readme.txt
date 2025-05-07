\=== Google Rating Dynamic Tags Elementor ===
Contributors: Websitetoday.nl
Tags: elementor, google, rating, dynamic-tags
Requires at least: 5.0
Tested up to: 6.8.1
Stable tag: 2.1.0
License: GPLv2 or later
License URI: [https://www.gnu.org/licenses/gpl-2.0.html](https://www.gnu.org/licenses/gpl-2.0.html)

\== Description ==
Toon eenvoudig de Google Bedrijfsbeoordelingen (gemiddelde score, aantal reviews en openingstijden) rechtstreeks in je content:

* **Elementor Dynamic Tag** – Gebruik de Google Rating als tekst, nummer, ster of gecombineerde weergave.
* **Shortcode** – Voeg de rating of het aantal reviews toe via `[google_rating field="rating_star"]`.
* **Meerdere bedrijven** – Voeg meerdere locaties toe en kies per widget of shortcode welke getoond wordt.
* **Cache-interval** – Configureer hoe vaak data ververst wordt (1, 6, 12, 24 uur of elke week).
* **Batch fetching** – Geen live API-calls per paginalaag dankzij WP-Cron.
* **Verbindingscheck & statusicoontjes** – Visuele controle of je API werkt (Check-knop).
* **Modern admin UI** – Strakke tabelstyling, responsive weergave, tooltips en subtiele beschrijvingen.
* **Automatische updates** via GitHub Releases (PUC v5).

\== Installation ==

1. Upload de pluginmap `google-rating-dynamic-tags-elementor-plugin` naar `/wp-content/plugins/`.
2. Activeer via **Plugins → Geïnstalleerde plugins**.
3. (Optioneel) Installeer Elementor Pro voor Dynamic Tag-ondersteuning.
4. Ga naar **Google Rating → Instellingen** en voeg je **API Key**, **Place ID(s)** en **Cache-interval** toe.

\== Screenshots ==

1. Instellingenpagina met meerdere locaties, tooltips en statuscontrole
2. Voorbeeld batch fetching in Cron-log
3. Elementor bewerkingsomgeving met Dynamic Tag-selector

\== Changelog ==
\= 2.1.0 =

* ✨ **New**: Batch fetching van alle Place Details via WP-Cron taak op basis van de cache-interval instelling.
* 🛠️ **Improvement**: Configurabele cache-interval dropdown (1, 6, 12, 24 uur of elke week).
* 💄 **UI**: Moderne admin CSS voor tabelstyling en responsive weergave.
* 🛠️ **Improvement**: Tooltips en subtiele beschrijvingen toegevoegd bij alle instellingen en tabelvelden.
* 🛠️ **Improvement**: `gre-admin.js` geüpdatet met spinner-icoon, kleurclasses en dynamische TTL voor test-transient.
* 🛠️ **Improvement**: CSS en JS nu correct ingeladen via `admin_enqueue_scripts` hook op juiste admin-pagina.
* 🛠️ **Improvement**: `gre_fetch_google_place_data()` aangepast naar batch-transient (`gre_all_places_data`), waardoor paginalaag geen live API-calls meer uitvoert.

\= 2.0.2 =

* ✅ **Fix**: Check-knop werkt nu per bedrijfslocatie en onthoudt status.
* 🛠️ **Tweak**: Nieuwe bedrijven krijgen standaard een rood kruis tot gecontroleerd.
* 💄 **UI**: Statusicoontjes consistent rood/groen + stylingverbetering + bugfix dubbele rijen.

\= 2.0.1 =

* ✨ **Nieuw**: Toon alle locaties tegelijk in één Dynamic Tag (via “Alle”).
* 🔧 **Verbetering**: Locatielabel optioneel, code opgeschoond en betere validatie.
* 🐞 **Fix**: Dynamic tag dropdown werkt correct bij meerdere custom tags.

\= 2.0.0 =

* ✨ **Nieuw**: Ondersteuning voor meerdere bedrijven (Place IDs).
* 🔁 **Verbetering**: Volledige herstructurering van admin-settings.

\= 1.5.6 =

* 🧩 **Nieuw**: Plugin Update Checker v5 integratie (GitHub Updates).
* 💬 **Tweak**: Nieuwe uitleg-tab & changelog-tab.

\= 1.5.4 =

* ✅ **Nieuw**: Real-time statusicoontjes voor API Key & Place ID.

\= 1.5.3 =

* 🔐 **Verbetering**: Verbeterde verbindingscheck met icoon & foutmeldingen.

\= 1.5.2 =

* 🧹 **Fix**: Oude test/ververs knoppen verwijderd.

\= 1.5.1 =

* 🛠️ **Fix**: Shortcode correct opnieuw geregistreerd.

\= 1.5.0 =

* 🚀 **Nieuw**: GitHub Releases ondersteuning via Plugin URI.

\== Frequently Asked Questions ==
\=== Hoe toon ik de rating als ster? ===
Gebruik `[google_rating field="rating_star"]` of kies **Gemiddelde score + ster** via Elementor.

\=== Hoe voeg ik meerdere locaties toe? ===
Ga naar **Google Rating → Instellingen** en voeg daar meerdere bedrijfsnamen en Place IDs toe.

\=== Hoe krijg ik een Place ID en API Key? ===
• API Key aanvragen: [https://console.cloud.google.com/](https://console.cloud.google.com/)
• Place ID opzoeken: [https://developers.google.com/maps/documentation/places/web-service/place-id](https://developers.google.com/maps/documentation/places/web-service/place-id)

\== Upgrade Notice ==
\= 2.1.0 =
Overgestapt op batch fetching via WP-Cron en configurabele cache-interval; grote reductie in API-calls en stevige performanceverbetering.

\= 2.0.2 =
Belangrijke verbeteringen aan checkfunctionaliteit en validatie. Aanbevolen voor alle gebruikers.

\== Support ==
Meld bugs of verzoeken via GitHub:
[https://github.com/Websitetoday/google-rating-dynamic-tags-elementor-plugin/issues](https://github.com/Websitetoday/google-rating-dynamic-tags-elementor-plugin/issues)

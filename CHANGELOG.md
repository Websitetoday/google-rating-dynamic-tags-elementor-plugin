# Changelog

Alle belangrijke wijzigingen in de plugin worden hieronder gedocumenteerd.

## [3.1.1] - 2025-07-17

### 📝 Documentation

* Uitleg bij API Key aangepast: nu met duidelijke vermelding dat de **Places API geactiveerd moet zijn**.
* Veelgestelde vragen herschreven en uitgebreid:
  - Hoe vraag ik een API Key aan?
  - Hoe vind ik mijn Place ID?
  - Hoe gebruik ik de dynamic tags in Elementor?
  - Welke data kan ik tonen (inclusief openingstijden)?
  - Hoe vaak maakt de plugin een API-call?

---

## [3.1.0] - 2025-07-14

### ✨ New

* Uitleg bij de API Key en Place ID velden nu als duidelijk “?”-icoon met uitleg en link, direct naast het label.
* Changelog en uitleg-pagina’s volledig zonder externe Markdown-parser, direct vanuit `CHANGELOG.md`.

### 🛠 Improvements

* Verbeterde admin lay-out (cards), heldere labels en consistentere weergave van feedback.
* De plugin gebruikt nu alleen een enkele Place ID per installatie.
* Interne code opgeschoond; `gre-admin.js` en CSS verder geoptimaliseerd.
* Foutafhandeling en feedback bij verbindingstest verbeterd.

### 🗑 Removed

* API-call teller (overbodig door beperkt fetch-interval en duidelijke caching).

---

## [3.0.3] - 2025-07-08

### 🛠 Improvements

* Instellingen gescheiden in aparte `register_setting` groepen (`gre_api_settings` en `gre_plugin_settings`) om het overschrijven van API-gegevens te voorkomen.
* “Instellingen opslaan” knoppen toegevoegd in beide formulieren.
* `CHANGELOG.md` wordt nu automatisch weergegeven in de admin via `page-changelog.php`, zonder afhankelijkheid van een Markdown-parser.
* CSS en HTML verbeteringen voor compatibiliteit met Elementor admin layout.

---

## \[3.0.2] - 2025-07-08

### ✨ New

* API-call teller toegevoegd in de Instellingen tab met reset-knop; teller nu onderaan geplaatst en meet alleen cron- en handmatige ververs-data API-calls.
* Vermindering van live API-calls door het cron-only fetch-model: calls worden strikt beperkt tot het gekozen cache-interval.

## \[3.0.1] - 2025-07-04

* Quick Fix changelog bug

## \[3.0.0] - 2025-07-04

### ✨ New

* Admin-pagina’s volledig modulair in `includes/admin/` (Instellingen, Uitleg, Changelog).
* `uninstall.php` toegevoegd voor complete cleanup van alle plugin-opties en transients bij deïnstallatie.
* Cache-duur dropdown (1, 12, 24 uur of 1 week) en “Ververs data” knop in de Instellingen tab.
* Info-tooltips en directe links toegevoegd bij de API Key en Place ID velden.

### 🛠 Improvements

* Monolithische code opgesplitst voor betere onderhoudbaarheid.
* `gre-admin.js` bijgewerkt met spinner-icoon, knop-disabling en dynamische TTL voor refresh.
* Admin CSS en JS nu netjes ingeladen via `admin_enqueue_scripts`.

### 🗑 Removed

* Ondersteuning voor meerdere bedrijven (multi-Place IDs) verwijderd – nu enkel één Place ID.

## \[2.1.0] - 2025-05-07

### ✨ New

* Batch fetching van alle Place Details via een WP-Cron taak, gebaseerd op de cache-interval instelling.

### 🛠 Improvements

* Configurabele cache-interval dropdown toegevoegd (1, 6, 12, 24 uur of elke week).
* Admin-tabel volledig opnieuw gestyled met `css/admin.css` voor modern uiterlijk en responsiviteit.
* Tooltip-icoontjes en subtiele beschrijvingen (`<p class="description">`) toegevoegd bij alle instellingen en tabelvelden.
* `gre_fetch_google_place_data()` aangepast om data enkel uit de batch-transient te lezen, waardoor paginalaag geen directe API-calls meer doet.
* `gre-admin.js` geüpdatet met spinner-icoon, kleurclasses en dynamische TTL voor test-transient op basis van cache-interval.
* Admin CSS en JS worden nu correct ingeladen via de `admin_enqueue_scripts` hook.

### 🐞 Fixes

* AJAX test-verbinding TTL aangepast aan de gekozen cache-interval instelling.
* Verwijderde onnodige live API-calls in frontend en admin previews.

## \[2.0.2] - 2025-05-02

### 🛠 Improvements

* Statusicoontjes per bedrijf toegevoegd bij verbindingstest (groen/rood icoon).
* Resultaat van verbinding wordt opgeslagen en blijft zichtbaar bij herladen.
* Visuele feedback toegevoegd aan de instellingenpagina voor nieuwe bedrijven.

### 🐞 Fixes

* Fix: dubbele rijen bij “Bedrijf toevoegen” opgelost.
* Fix: styling en volgorde van actieknoppen per bedrijf verbeterd.

## \[2.0.1] - 2025-05-02

### ✨ New

* Dynamic Tag met ondersteuning voor alle bedrijven tegelijk.
* Verbeterde locatie-keuze in Elementor & shortcode.

### 🛠 Improvements

* Optioneel tonen van locatielabels.
* Plugin opgesplitst in modulaire bestanden.

### 🐞 Fixes

* Dynamic Tag toont correct bij meerdere custom tags.
* Validatie van lege locaties.
* Statusicoontjes updaten live bij invoer.

## \[2.0.0] - 2025-04-30

### ✨ New

* Ondersteuning voor meerdere bedrijven (Place IDs).
* Locatiekeuze toegevoegd aan shortcode en Elementor.

## \[1.5.6]

### ✨ New

* PUC v5 integratie voor GitHub Releases.
* Plugin icoon en screenshots in “Meer informatie”.

## \[1.5.4]

### 🛠 Improvements

* Real-time statusicoontjes voor API Key en Place ID.

## \[1.5.3]

### 🛠 Improvements

* Verbindingscheck met icoon & foutmeldingen.

## \[1.5.2]

### 🐞 Fixes

* Verwijderde verouderde knoppen en AJAX-logica.

## \[1.5.1]

### 🛠 Improvements

* Herstelde shortcode-registratie.

## \[1.5.0]

### ✨ New

* GitHub update-integratie (Update URI ondersteuning).

---

Voor oudere versies, zie de GitHub releases.

---

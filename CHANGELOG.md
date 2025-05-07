# Changelog

Alle belangrijke wijzigingen in de plugin worden hieronder gedocumenteerd.

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

* Dynamic tag toont correct bij meerdere custom tags.
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

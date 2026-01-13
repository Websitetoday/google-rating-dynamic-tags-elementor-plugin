# Changelog

## [3.8.0] - 2026-01-13

### Added
- **Multi-Business Support** - Koppel meerdere Google bedrijven aan de plugin
- Nieuw "Bedrijven" beheer in admin panel:
  - Overzichtelijke tabel met alle gekoppelde bedrijven
  - Naam, Place ID, Rating en Reviews per bedrijf
  - Ververs knop per bedrijf
  - Verwijder knop (standaard bedrijf kan niet verwijderd worden)
  - Formulier om nieuw bedrijf toe te voegen
- Bedrijf selectie in Elementor widgets:
  - Google Rating Badge Widget: dropdown om bedrijf te selecteren
  - Google Openingstijden Widget: dropdown om bedrijf te selecteren
- Shortcode uitgebreid met `business` attribuut:
  - `[google_rating business="biz_abc123"]` voor specifiek bedrijf
  - `[google_rating field="badge" business="default"]` voor standaard bedrijf
- Automatische migratie van bestaande single-business data naar multi-business systeem
- Backwards compatibiliteit met legacy configuratie

### Changed
- `gre_fetch_google_place_data()` accepteert nu optioneel een `$business_id` parameter
- Admin interface herstructureerd: API Key apart van bedrijven beheer

## [3.7.0] - 2026-01-13

### Added
- **Elementor Widget: Google Openingstijden** - Volledig aanpasbare openingstijden widget
- Weergave opties:
  - Volledige week overzicht
  - Alleen vandaag
  - Open/gesloten status
  - Specifieke dag (ma t/m zo)
- Layout opties: lijst, inline of tabel
- Markeer vandaag met aangepaste kleuren
- Optioneel klok icoon
- Styling controls voor:
  - Container achtergrond, padding, border, schaduw
  - Dagnaam kleur, typografie en breedte
  - Uren kleur en typografie
  - Gesloten status kleur en typografie
  - Vandaag markering (achtergrond, kleuren, padding, border-radius)
  - Icoon kleur, grootte en spacing
  - Rij afstand
- Aanpasbare teksten (gesloten, open prefix, etc.)
- Live preview in Elementor editor

## [3.6.0] - 2026-01-13

### Added
- **Elementor Widget: Google Rating Badge** - Volledig aanpasbare widget in Elementor editor
- Styling controls voor:
  - Badge achtergrondkleur, padding, border-radius, schaduw
  - Google logo grootte
  - Sterren kleur (vol en leeg), grootte en tussenruimte
  - Score tekstkleur en typografie
  - Reviews tekstkleur en typografie
  - Scheidingsteken stijl (|, -, /, bullet, geen)
- Content opties: toon/verberg Google logo, sterren, score, reviews
- Link naar Google Reviews optie
- Badge uitlijning (links, midden, rechts)
- Live preview in Elementor editor

### Changed
- Widget vereist nu alleen Elementor (niet meer Elementor Pro)

## [3.5.0] - 2026-01-13

### Added
- Nieuwe `[google_rating field="badge"]` shortcode voor professionele Google Reviews badge
- Google Rating Badge met officieel Google logo, gekleurde sterren, score en aantal reviews
- Frontend CSS styling voor de badge (responsive en dark mode ondersteuning)
- Documentatie voor de nieuwe badge functie in Uitleg pagina

### Changed
- Sterren worden nu dynamisch gegenereerd (vol, half, leeg) op basis van de daadwerkelijke score

## [3.4.3] - 2026-01-12

### Fixed
- WordPress 6.7.0 compatibiliteit: alle `esc_html__()` translation functions vervangen door hardcoded strings
- Text Domain en Domain Path headers verwijderd (geen vertaalbestanden aanwezig)
- `get_plugin_data()` calls nu met `false, false` parameters om auto-translate te voorkomen
- "Translation loading was triggered too early" waarschuwing volledig verholpen voor alle text domains
- Plugin gebruikt nu alleen Nederlandse tekst zonder translation layer

## [3.4.2] - 2026-01-12

### Added
- "Controleer op updates" knop in admin panel onder Instellingen
- Plugin Updates sectie met huidige versie en GitHub link
- AJAX handler voor handmatige update check (`gre_check_updates`)
- JavaScript functionaliteit voor update knop
- Auto-refresh pagina na update detectie
- Update cache wordt geleegd bij handmatige check

### Fixed
- Update checker werkt nu correct met GitHub releases
- Versie vergelijking tussen lokaal en GitHub

## [3.4.0] - 2026-01-12

### Added
- Nieuwe Simple GitHub Updater - zelfgemaakt en stabiel
- Lightweight update checker zonder externe dependencies
- 12 uur caching voor GitHub API calls
- Changelog support vanuit CHANGELOG.md

### Changed
- Vervangen complexe Plugin Update Checker door eigen oplossing
- Update checker nu 100% compatibel en foutvrij

### Removed
- Plugin Update Checker library (veroorzaakte fatal errors)

## [3.3.2] - 2026-01-12

### Changed
- Plugin Update Checker volledig uitgeschakeld
- Updates nu handmatig via WordPress plugin upload
- Commented code beschikbaar voor debugging indien nodig

### Fixed
- Kritieke fout bij "Check for updates" definitief opgelost
- Plugin nu 100% stabiel op productie

## [3.3.1] - 2026-01-12

### Fixed
- PHP syntax error "unexpected token use" opgelost
- Fully qualified namespace gebruikt voor PucFactory
- Verwijderd use statement binnen if-block

## [3.3.0] - 2026-01-12

### Added
- GitHub automatic updates via Plugin Update Checker
- Robuuste error handling voor update checker
- 30 seconden timeout voor GitHub API calls

### Fixed
- Fatal error bij "Controleer op updates" opgelost
- Parsedown library conflicten opgelost
- Dubbele functie definities verwijderd

## [3.1.4] - 2026-01-11

### Initial Release
- Elementor Dynamic Tags voor Google Rating
- Elementor Dynamic Tags voor Google Opening Hours
- Shortcode [google_rating]
- Admin panel met instellingen
- Google Places API integratie
- Automatic caching (7 dagen)

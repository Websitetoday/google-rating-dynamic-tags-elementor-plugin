# Changelog

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

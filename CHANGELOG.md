# Changelog

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

## [3.2.1] - 2026-01-12

### Fixed
- Kritieke fout door dubbele functie definities opgelost
- Conflicterende includes/admin-settings.php uitgeschakeld

## [3.2.0] - 2026-01-12

### Added
- Complete error handling voor update checker
- Admin notices voor update errors
- Try-catch wrappers voor robuustheid

### Fixed
- Plugin Update Checker fatal errors opgelost
- Update timeout verhoogd naar 30 seconden

## [3.1.6] - 2026-01-12

### Fixed
- Parsedown library conflicten in GitHubApi opgelost
- nl2br(esc_html()) gebruikt in plaats van Parsedown
- Use statements opgeschoond

## [3.1.5] - 2026-01-12

### Added
- gre_fetch_google_place_data() functie toegevoegd
- AJAX handler voor force refresh
- 7-dagen caching systeem
- PHP en WordPress minimum vereisten

### Fixed
- Ontbrekende core functie toegevoegd
- JavaScript AJAX parameter mismatch opgelost
- Response parsing verbeterd

## [3.1.4] - 2026-01-11

### Initial Release
- Elementor Dynamic Tags voor Google Rating
- Elementor Dynamic Tags voor Google Opening Hours
- Shortcode [google_rating]
- Admin panel met instellingen
- Google Places API integratie
- Automatic caching (7 dagen)

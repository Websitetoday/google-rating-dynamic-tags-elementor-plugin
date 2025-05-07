# Google Rating Dynamic Tags Elementor

**Contributors:** Websitetoday.nl
**Tags:** elementor, google, rating, dynamic-tags
**Requires at least:** 5.0
**Tested up to:** 6.4
**Stable tag:** main
**License:** GPLv2 or later
**License URI:** [https://www.gnu.org/licenses/gpl-2.0.html](https://www.gnu.org/licenses/gpl-2.0.html)

## Description

Toon eenvoudig de Google Bedrijfsbeoordelingen (gemiddelde score, aantal reviews en openingstijden) van jouw bedrijf op je WordPress-website.

* **Elementor Dynamic Tags** voor Pro-gebruikers (tekst & nummer)
* **Shortcode** met vier weergaveopties
* **Configurabele cache-interval** (1, 6, 12, 24 uur of elke week)
* **Batch fetching** via WP-Cron vermindert API-calls tot 0 per paginalaag
* **Modern admin UI** met tooltips en subtiele beschrijvingen
* **Automatische updates** via GitHub Releases (PUC v5)

## Installation

### 1. Via GitHub Releases

1. Ga naar de [Releases-pagina](https://github.com/Websitetoday/google-rating-dynamic-tags-elementor-plugin/releases).
2. Download de nieuwste `.zip`-versie.
3. Upload de `.zip` via **Plugins → Nieuwe plugin → Plugin uploaden**.
4. Activeer de plugin via **Plugins → Geïnstalleerde plugins**.

### 2. Handmatig

1. Clone of download deze repository:

   ```bash
   git clone https://github.com/Websitetoday/google-rating-dynamic-tags-elementor-plugin.git
   ```
2. Upload de map `google-rating-dynamic-tags-elementor-plugin` naar `/wp-content/plugins/`.
3. Activeer de plugin via **Plugins → Geïnstalleerde plugins**.

## Configuration

1. Navigeer in de WordPress admin naar **Google Rating** in de zijbalk.
2. Ga naar het tabblad **Instellingen**.
3. Vul in:

   * **API Key** — jouw Google Places API Key
   * **Bedrijven** — voeg labels en Place ID’s toe (tooltips helpen bij het invullen)
   * **Shortcode inschakelen** — schakel de `[google_rating]`-shortcode in of uit
   * **Cache-interval** — kies hoe vaak de plugin nieuwe data ophaalt
4. Klik op **Opslaan**.
5. Optioneel: test de verbinding via de **Check**-knop.

## Usage

### Elementor Dynamic Tag

1. Open een widget in **Elementor Pro** (tekst of nummer).
2. Klik op het dynamische tags-icoon (database-icoon).
3. Selecteer **Google Rating** onder **Site**.
4. Kies het **Weergaveveld**:

   * `rating_number` — gemiddelde score als puur getal
   * `rating_star` — gemiddelde score + ster (bijv. “4.5 ★”)
   * `count_number` — aantal reviews als puur getal
   * `both` — `<strong>4.5</strong> ★ 123 reviews`
5. Pas styling toe en publiceer.

### Shortcode

Gebruik de shortcode in een Gutenberg- of Tekstblok:

```shortcode
[google_rating field="rating_star"]
```

Beschikbare `field`-opties:

| field           | Output                           |
| --------------- | -------------------------------- |
| `rating_number` | Gemiddelde score als nummer      |
| `rating_star`   | Gemiddelde score + ster          |
| `count_number`  | Aantal reviews als nummer        |
| `both`          | Score + aantal reviews als tekst |

## Screenshots

1. **Instellingenpagina** — modern UI met tooltips en beschrijvingen
2. **Dynamic Tag in Elementor** — kies uit meerdere weergavevelden
3. **Batch fetching** — ziet geen directe API-calls tijdens paginaweergave

## Changelog

Zie [CHANGELOG.md](CHANGELOG.md) voor details over alle versies.

## Support

Rapporteer issues of feature requests op de [GitHub Issues-pagina](https://github.com/Websitetoday/google-rating-dynamic-tags-elementor-plugin/issues).

## License

GPLv2 of hoger. Zie het [LICENSE-bestand](LICENSE) voor details.

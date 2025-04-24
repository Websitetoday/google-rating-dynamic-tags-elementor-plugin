# Google Rating Dynamic Tags Elementor

**Contributors:** Websitetoday.nl  
**Tags:** elementor, google, rating, dynamic-tags  
**Requires at least:** 5.0  
**Tested up to:** 6.4  
**Stable tag:** main  
**License:** GPLv2 or later  
**License URI:** https://www.gnu.org/licenses/gpl-2.0.html

## Description

Toon eenvoudig de Google Bedrijfsbeoordelingen (gemiddelde score en aantal reviews) van jouw bedrijf op je WordPress-website.

- Elementor Dynamic Tags voor Pro-gebruikers (tekst & nummer)
- Shortcode met vier weergaveopties
- Eenvoudige plugin-instellingen voor API Key & Place ID
- Eigen admin-menu met tabbladen: Instellingen, Shortcode Uitleg, Changelog
- Automatische updates via GitHub Releases

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
   - **API Key**: jouw Google Places API Key  
   - **Place ID**: de Place ID van jouw bedrijf  
   - **Shortcode inschakelen** (optioneel): schakel de shortcode in of uit  
4. Klik op **Opslaan**.  
5. Optioneel: test de verbinding met de knop **Controleer verbinding**.

## Screenshots

1. **Banner & overzicht**  
   ![Banner](screenshot-1.png)
2. **Instellingenpagina**  
   ![Instellingen](screenshot-2.png)
3. **Dynamic Tag in Elementor**  
   ![Elementor Dynamic Tag](screenshot-3.png)

## Usage

### Elementor Dynamic Tag

1. Open een widget in **Elementor Pro** (tekst of nummer).  
2. Klik op het dynamische tags-icoon (database-icoon).  
3. Selecteer **Google Rating** onder het kopje **Site**.  
4. Stel het **Weergaveveld** in:  
   - `rating_number` → gemiddelde score als puur getal  
   - `rating_star` → gemiddelde score + ster (zoals “4.5 ★”)  
   - `count_number` → aantal reviews als puur getal  
   - `both` → `<strong>4.5</strong> ★ 123 reviews`  
5. Pas styling toe en publiceer.

### Shortcode

Gebruik de shortcode in een Gutenberg- of Tekstblok:

```shortcode
[google_rating field="rating_star"]
```

Beschikbare `field`-opties:

| field          | Output                                    |
| -------------- | ----------------------------------------- |
| `rating_number`| Gemiddelde score als nummer               |
| `rating_star`  | Gemiddelde score + ster                   |
| `count_number` | Aantal reviews als nummer                 |
| `both`         | Score + aantal reviews als tekst          |

## Changelog

### 1.5.6
* Nieuwe update-check via GitHub Releases (PUC-integratie)  
* Eigen plugin-icoon en screenshots in 'Meer details'-modal  

### 1.5.4
* Real-time statusicoontjes voor API Key & Place ID  

### 1.5.3
* Verbeterde verbindingscheck met icoon & foutmeldingen

### 1.5.2
* Fix: verwijderde niet-werkende Test/Ververs knoppen  

### 1.5.1
* Tweak: shortcode-registratie hersteld  

### 1.5.0
* Ondersteuning GitHub Releases via Update URI

## Support

Rapporteer issues of feature requests op de [GitHub Issues-pagina](https://github.com/Websitetoday/google-rating-dynamic-tags-elementor-plugin/issues).

## License

GPLv2 of hoger. Zie het [LICENSE-bestand](LICENSE) voor details.


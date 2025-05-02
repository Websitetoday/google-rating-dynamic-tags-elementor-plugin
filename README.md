# Google Rating Dynamic Tags Elementor

**Contributors:** Websitetoday.nl  
**Tags:** elementor, google, rating, dynamic-tags  
**Requires at least:** 5.0  
**Tested up to:** 6.4  
**Stable tag:** main  
**License:** GPLv2 or later  
**License URI:** https://www.gnu.org/licenses/gpl-2.0.html

---

## Description

Toon eenvoudig Google Bedrijfsbeoordelingen (gemiddelde score en aantal reviews) van jouw bedrijf op je WordPress-website.

✅ Elementor Dynamic Tags voor Pro-gebruikers  
✅ Shortcode met vier weergaveopties  
✅ Ondersteuning voor meerdere bedrijven  
✅ Real-time API check en statusicoontjes  
✅ Automatische updates via GitHub Releases  

---

## Features

- **Elementor Pro**: gebruik Google Rating als dynamic tag met keuze uit score, ster, aantal of beide
- **Shortcode**: voeg eenvoudig beoordelingen toe via `[google_rating field="rating_star"]`
- **Meerdere bedrijven**: beheer meerdere locaties met eigen Place ID
- **Modulair opgebouwd**: aparte bestanden voor admin, changelog, uitleg, etc.
- **Realtime feedback**: statusicoontjes voor geldige API Key en Place ID

---

## Installation

### 1. Via GitHub Releases

1. Ga naar de [Releases-pagina](https://github.com/Websitetoday/google-rating-dynamic-tags-elementor-plugin/releases)  
2. Download de nieuwste `.zip`  
3. Upload via **Plugins → Nieuwe plugin → Uploaden**  
4. Activeer via **Plugins → Geïnstalleerde plugins**

### 2. Handmatig

```bash
git clone https://github.com/Websitetoday/google-rating-dynamic-tags-elementor-plugin.git
```

1. Upload naar `/wp-content/plugins/`  
2. Activeer via je WordPress-dashboard

---

## Configuration

1. Ga naar **Google Rating → Instellingen**  
2. Vul in:
   - API Key (Google Places API)
   - Eén of meerdere Place IDs
3. Klik op **Opslaan**  
4. Gebruik de **Check**-knop om verbindingen per bedrijf te testen

🔗 [Place ID opzoeken](https://developers.google.com/maps/documentation/places/web-service/place-id)  
🔐 [Google API Key aanvragen](https://developers.google.com/maps/documentation/places/web-service/get-api-key)

---

## Screenshots

1. **Instellingenpagina**
   ![Instellingen](screenshot-1.png)
2. **Weergave in Elementor**
   ![Frontend](screenshot-2.png)
3. **Elementor Dynamic Tag instellingen**
   ![Dynamic Tag](screenshot-3.png)

---

## Usage

### Elementor Dynamic Tag

1. Open een widget in Elementor Pro (tekst of nummer)  
2. Klik op het database-icoon → kies **Google Rating**  
3. Stel je voorkeuren in:
   - `rating_number`: alleen gemiddelde score
   - `rating_star`: score + ster
   - `count_number`: aantal reviews
   - `both`: volledige tekst zoals “4.5 ★ 123 reviews”

### Shortcode

```shortcode
[google_rating field="rating_star"]
```

| Field           | Output                          |
| --------------- | ------------------------------- |
| `rating_number` | Gemiddelde score (bijv. 4.5)     |
| `rating_star`   | 4.5 ★                            |
| `count_number`  | Aantal reviews (bijv. 123)       |
| `both`          | 4.5 ★ 123 reviews                |

Gebruik `place="all"` voor alle bedrijven of `place="PLACE_ID"` voor specifiek bedrijf.

---

## Changelog

### 2.0.2 – 2025-05-02
- ✅ Nieuwe UI: statusicoontjes en check-knoppen per bedrijf
- 🧠 Intelligente validatie: onthoudt verbinding per bedrijf
- 🧩 Plugincode opgesplitst voor onderhoudbaarheid
- 🐞 Fix: dubbele rijen bij “Bedrijf toevoegen” opgelost

### 2.0.1
- ✨ Nieuw: Dynamic Tag ondersteunt nu meerdere bedrijven tegelijk (keuze “Alle”)
- 🔧 Verbetering: locatie-label is optioneel in de output
- 🐞 Bugfix: dynamic tag verschijnt nu correct bij meerdere custom tags

### 2.0.0
- ✨ Grootste update: ondersteuning voor meerdere bedrijven
- 🔌 Selecteer per locatie welke data je wilt tonen (shortcode of Elementor)
- 💡 Nieuwe tabbladen: uitleg, changelog, verbeterde UX

---

## Support

Meld issues of feature requests via [GitHub Issues](https://github.com/Websitetoday/google-rating-dynamic-tags-elementor-plugin/issues)

---

## License

GPLv2 of later. Zie het [LICENSE-bestand](LICENSE).

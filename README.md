# Google Rating Dynamic Tags Elementor

Een WordPress plugin om Google Bedrijfsbeoordelingen te tonen via Elementor Dynamic Tags en shortcodes.

## Features

- ✨ **Elementor Dynamic Tags** voor Google Rating
- 🕐 **Elementor Dynamic Tags** voor Google Openingstijden
- 📝 **Shortcode** `[google_rating]`
- ⚡ **Automatische caching** (7 dagen) om API kosten te besparen
- 🔄 **Automatische updates** via GitHub
- 🎨 **Volledig styleable** via Elementor of custom CSS

## Installatie

1. Download de nieuwste release van GitHub
2. Upload via WordPress Admin → Plugins → Add New → Upload Plugin
3. Activeer de plugin
4. Ga naar **Google Rating** in het WordPress menu
5. Vul je Google API Key en Place ID in

## Google API Setup

### 1. Google API Key aanmaken

1. Ga naar [Google Cloud Console](https://console.cloud.google.com/)
2. Maak een nieuw project aan (of selecteer bestaand project)
3. Ga naar **APIs & Services** → **Credentials**
4. Klik op **Create Credentials** → **API Key**
5. Kopieer de API Key

### 2. Places API activeren

1. Ga naar [Places API](https://console.cloud.google.com/apis/library/places-backend.googleapis.com)
2. Klik op **Enable**

### 3. Place ID vinden

1. Gebruik de [Place ID Finder](https://developers.google.com/maps/documentation/places/web-service/place-id)
2. Zoek je bedrijf
3. Kopieer de Place ID

## Gebruik

### Elementor Dynamic Tags

1. Open een pagina in Elementor
2. Selecteer een tekst widget
3. Klik op het **Dynamic** icoon
4. Kies **Google Rating** of **Google Opening Hours**
5. Selecteer het gewenste veld

### Shortcode

```
[google_rating field="rating_star"]
```

**Beschikbare velden:**
- `rating_number` - Alleen het cijfer (bijv. 4.8)
- `rating_star` - Cijfer met ster (bijv. 4.8 ★)
- `count_number` - Aantal reviews (bijv. 123)
- `both` - Alles (bijv. 4.8 ★ (123 reviews))

## Automatische Updates

De plugin gebruikt de [Plugin Update Checker](https://github.com/YahnisElsts/plugin-update-checker) library voor automatische updates vanuit GitHub.

**Updates checken:**
1. Ga naar WordPress Admin → Plugins
2. Klik op **Check for updates**
3. Nieuwe versies worden automatisch getoond

## API Kosten

De plugin gebruikt de **Google Places API** die betalend is. Om kosten te beperken:

- ✅ Data wordt **7 dagen gecached**
- ✅ Maximaal **1 API call per week** per bedrijf
- ✅ Handmatige refresh optie beschikbaar

**Geschatte kosten:** ~$0.50 per maand bij normaal gebruik

## Technische Details

- **Vereist:** WordPress 5.0+
- **Vereist:** PHP 7.4+
- **Optioneel:** Elementor Pro voor Dynamic Tags
- **API:** Google Places API (Details endpoint)

## Support

- **Issues:** [GitHub Issues](https://github.com/Websitetoday/google-rating-dynamic-tags-elementor-plugin/issues)
- **Email:** info@websitetoday.nl
- **Website:** [Websitetoday.nl](https://www.websitetoday.nl/)

## Changelog

Zie [CHANGELOG.md](CHANGELOG.md) voor volledige changelog.

## Licentie

Ontwikkeld door [Websitetoday.nl](https://www.websitetoday.nl/)

## Credits

- Plugin Update Checker door [Yahnis Elsts](https://github.com/YahnisElsts/plugin-update-checker)
- Google Places API door Google

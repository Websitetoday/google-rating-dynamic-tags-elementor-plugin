Google Rating Dynamic Tags Elementor

Met deze plugin kun je eenvoudig de Google Bedrijfsbeoordelingen (gemiddelde score en aantal reviews) van jouw bedrijf weergeven op je WordPress-website:

Elementor Dynamic Tags voor Pro-gebruikers (tekst & nummer)

Shortcode met vier weergaveopties

Simpele plugin-instellingen voor API Key & Place ID

Eigen admin-menu met tabbladen: Instellingen, Shortcode Uitleg, Changelog

Automatische updates via GitHub Releases

Vereisten

WordPress 5.0 of hoger

PHP 7.2 of hoger

Elementor Pro (voor Dynamic Tags)

Google Places API geactiveerd en API Key

Installatie

Er zijn twee manieren om de plugin te installeren:

1. Via GitHub Releases

Ga naar de releases-pagina op GitHub.

Download de nieuwste .zip-versie.

Upload de .zip via Plugins → Nieuwe plugin → Plugin uploaden.

Activeer de plugin via Plugins → Geïnstalleerde plugins.

2. Handmatig

Clone of download deze repository:



git clone https://github.com/Websitetoday/google-rating-dynamic-tags-elementor-plugin.git

2. Upload de map `google-rating-dynamic-tags-elementor-plugin` naar `/wp-content/plugins/`.
3. Activeer de plugin via **Plugins → Geïnstalleerde plugins**.

---

## Configuratie

1. Navigeer in de WordPress admin naar **Google Rating** in de zijbalk.
2. Ga naar het tabblad **Instellingen**.
3. Vul in:
   - **API Key**: jouw Google Places API Key
   - **Place ID**: de Place ID van jouw bedrijf
   - **Shortcode inschakelen** (optioneel): schakel de shortcode in of uit
4. Klik op **Opslaan**.
5. Optioneel: test de verbinding met de knop **Controleer verbinding**.

---

## Gebruik

### Elementor Dynamic Tag

1. Open een widget in **Elementor Pro** (tekst of nummer).
2. Klik op het dynamische tags-icoon (database-icoon).
3. Selecteer **Google Rating** onder het kopje **Site**.
4. Stel het **Weergaveveld** in:
   - `Gemiddelde score (nummer)` → puur getal
   - `Gemiddelde score + ster` → bijv. “4.5 ★”
   - `Aantal reviews (nummer)` → puur aantal
   - `Score + Aantal (tekst)` → bijv. “**4.5** ★ 123 reviews”
5. Pas styling toe en publiceer.

### Shortcode

Gebruik de shortcode in een Gutenberg- of Tekstblok:

```shortcode
[google_rating field="rating_star"]

Beschikbare field-opties:

field

Output

rating_number

Gemiddelde score als nummer

rating_star

Gemiddelde score + ster

count_number

Aantal reviews als nummer

both

Score + aantal reviews (tekst)

Changelog

Zie in de plugin-admin onder Changelog voor alle details. Kort overzicht:

1.5.4 – Real-time statusicoontjes voor API Key & Place ID

1.5.3 – Verbeterde verbindingscheck met icoon & foutmeldingen

1.5.2 – Fix: verwijderde niet-werkende Test/Ververs knoppen

1.5.1 – Tweak: shortcode-registratie hersteld

1.5.0 – Ondersteuning GitHub Releases via Update URI

Ondersteuning & Bugs

Rapporteer issues of feature requests op de GitHub Issues-pagina.

Licentie

GPLv2 of hoger. Zie het LICENSE-bestand voor details.


=== Google Rating Dynamic Tags Elementor ===
Contributors: Websitetoday.nl  
Tags: elementor, google, rating, dynamic-tags, cache, place-id  
Requires at least: 5.0  
Tested up to: 6.5  
Stable tag: main  
License: GPLv2 or later  
License URI: https://www.gnu.org/licenses/gpl-2.0.html  

== Description ==
Toon eenvoudig de Google Bedrijfsbeoordelingen (gemiddelde score, aantal reviews en link naar reviews) rechtstreeks in je content:

* **Elementor Dynamic Tag**: gebruik de Google Rating als tekst, nummer, ster of gecombineerde weergave.  
* **Shortcode**: voeg de rating of het aantal reviews toe via `[google_rating field="rating_star"]`.  
* **Cache & Force Refresh**: Google-data wordt maximaal 1x per week automatisch opgehaald en lokaal opgeslagen. Handmatig verversen kan via de “Ververs data” knop.  
* **Veilig & efficiënt**: Caching beperkt het aantal API-calls tot het minimum (max. 1 per week), dus geen onverwachte kosten!  
* **Volledig te stylen** via CSS en geschikt voor elke Elementor PRO website.  
* **Uninstall Cleanup**: bij deïnstallatie verwijdert de plugin alle instellingen en transients, zodat je opnieuw met een schone lei kunt beginnen.  
* **Automatische updates** via GitHub Releases (PUC v5).  

== Installation ==
1. Upload de map `google-rating-dynamic-tags-elementor-plugin` naar de `/wp-content/plugins/` directory.  
2. Activeer de plugin via **Plugins → Geïnstalleerde plugins**.  
3. (Optioneel) Installeer Elementor Pro als je de Dynamic Tag-functionaliteit wilt gebruiken.  
4. Ga naar **Google Rating → Instellingen** en vul je Google Places **API Key** en **Place ID** in.  
   - Klik op het **?** icoontje naast het veld voor uitleg en een rechtstreekse link.
   - Zorg dat de **Places API** geactiveerd is in je Google Cloud project.
5. De data wordt automatisch maximaal 1x per week opgehaald. Wil je direct verversen? Klik op **Ververs data**.  

== Screenshots ==
1. **Banner in modal**  
   ![Banner](screenshot-1.png)  
2. **Instellingenpagina** (API Key, Place ID, uitleg, cache & ververs knop)  
   ![Instellingen](screenshot-2.png)  
3. **Elementor Dynamic Tag**  
   ![Elementor Dynamic Tag](screenshot-3.png)  
4. **Ververs data knop**  
   ![Ververs data](screenshot-4.png)  

== Changelog ==
= 3.1.1 =
* Toegevoegde waarschuwing bij API Key dat de **Places API** geactiveerd moet zijn.
* Veelgestelde vragen volledig herschreven en uitgebreid:
  - API aanvragen
  - Place ID zoeken
  - Gebruik van Dynamic Tags in Elementor
  - Beschikbare data (incl. openingstijden)
  - API-call frequentie

= 3.1.0 =
* Uitleg nu als duidelijk **?**-icoon bij API Key en Place ID, direct naast het label.
* Changelog en uitleg-pagina’s tonen direct uit de pluginmap, zonder externe Markdown-parser.
* Admin-weergave, labels en feedback verder verbeterd.
* Plugin is verder opgeschoond; alleen één Place ID per site.
* API-call teller verwijderd (overbodig door maximaal 1x per week ophalen).
* Interne code en veiligheid verder verbeterd.

= 3.0.3 =
* Instellingen gescheiden in aparte groepen voor veiliger opslaan.
* Changelog-tab toont nu automatisch `CHANGELOG.md`.
* Diverse admin- en UX-verbeteringen.

= 3.0.0 =
* Volledig modulaire admin, uninstall cleanup, cache-duur dropdown.
* Info-tooltips en directe links bij API Key en Place ID velden.
* Alleen één Place ID per installatie.

Voor het volledige changelog: zie `CHANGELOG.md` of de Changelog-tab in de plugin.

== Upgrade Notice ==
= 3.1.1 =
Veelgestelde vragen en instructies bijgewerkt; Places API vereist. Aanbevolen update voor duidelijkere onboarding.

== Frequently Asked Questions ==
= Hoe vraag ik een API Key aan? =
Ga naar: https://console.cloud.google.com/apis/credentials  
Maak daar een sleutel aan en activeer de **Places API** voor je project.

= Hoe vind ik mijn Place ID? =
Gebruik de officiële Google Place ID Finder:  
https://developers.google.com/maps/documentation/places/web-service/place-id

= Hoe gebruik ik de Dynamic Tags in Elementor? =
Voeg een widget toe, klik op Dynamic Tags, kies "Websitetoday Google Rating" en selecteer het gewenste veld (bijv. sterren, cijfer of reviewaantal).

= Welke gegevens kan ik tonen? =
De plugin ondersteunt o.a.:
- Gemiddelde beoordeling (sterren)
- Cijfer (1-decimaal)
- Aantal reviews
- Openingstijden (huidige status)
- (Toekomst) reviewteksten

= Hoe vaak haalt de plugin nieuwe data op? =
Standaard maximaal 1x per week. Dit voorkomt hoge kosten. Je kunt de data ook handmatig verversen via de knop op de instellingenpagina.

== Support ==
Rapporteer bugs of feature requests via GitHub Issues:  
https://github.com/Websitetoday/google-rating-dynamic-tags-elementor-plugin/issues  

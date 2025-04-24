=== Google Rating Dynamic Tags Elementor ===
Contributors: Websitetoday.nl
Tags: elementor, google, rating, dynamic-tags
Requires at least: 5.0
Tested up to: 6.4
Stable tag: main
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Description ==
Toon eenvoudig de Google Bedrijfsbeoordelingen (gemiddelde score, aantal reviews en link naar reviews) rechtstreeks in je content:

* **Elementor Dynamic Tag**: gebruik de Google Rating als tekst, nummer, ster of gecombineerde weergave.
* **Shortcode**: voeg de rating of het aantal reviews toe via `[google_rating field="rating_star"]`.
* **Automatische updates** via GitHub Releases (PUC v5).

== Installation ==
1. Upload de pluginmap `google-rating-dynamic-tags-elementor-plugin` naar de `/wp-content/plugins/`-directory.
2. Activeer de plugin via **Plugins → Geïnstalleerde plugins**.
3. (Optioneel) Installeer Elementor Pro als je de Dynamic Tag-functionaliteit wilt gebruiken.
4. Ga naar **Google Rating → Instellingen** en vul je Google Places API Key en Place ID in.

== Screenshots ==
1. **Banner in modal**
   ![Banner](screenshot-1.png)
2. **Instellingenpagina**
   ![Instellingen](screenshot-2.png)
3. **Elementor Dynamic Tag**
   ![Elementor Dynamic Tag](screenshot-3.png)

== Changelog ==
= 1.5.6 =
* Integratie met Plugin Update Checker v5 voor GitHub Releases
* Eigen plugin-icoon en screenshots in de **View Details** modal

= 1.5.4 =
* Real-time statusicoontjes voor API Key & Place ID

= 1.5.3 =
* Verbeterde verbindingscheck met icoon & foutmeldingen

= 1.5.2 =
* Fix: verwijderde niet-werkende Test/Ververs knoppen

= 1.5.1 =
* Tweak: shortcode-registratie hersteld

= 1.5.0 =
* Ondersteuning GitHub Releases via Update URI

== Upgrade Notice ==
= 1.5.6 =
Gebruik nu de nieuwe update-checker; zorg dat `Parsedown.php` aanwezig is in `plugin-update-checker/Puc/v5p5/`.

== Frequently Asked Questions ==
= Hoe toon ik de rating als ster? =
Gebruik `[google_rating field="rating_star"]` of selecteer **Gemiddelde score + ster** in Elementor.

== Screens ==

== Support ==
Rapporteer bugs of feature-requests via de GitHub Issues-pagina: https://github.com/Websitetoday/google-rating-dynamic-tags-elementor-plugin/issues


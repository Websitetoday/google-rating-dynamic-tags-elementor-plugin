# Update Instructies - Versie 3.4.2

## Wat is er nieuw?

Versie 3.4.2 bevat een **"Controleer op updates" knop** in het admin panel.

## Installatie op je live website:

### Optie 1: Handmatige Upload (Aanbevolen voor nu)

1. **Pak de plugin directory in als ZIP:**
   - Ga naar: `c:\Users\rmeme\Local Sites\websitetodaynl\app\public\wp-content\plugins\`
   - Comprimeer de hele `google-rating-elementor-plugin` directory als ZIP
   - Of download de ZIP van je lokale site

2. **Upload naar live site:**
   - Log in op je WordPress admin (live site)
   - Ga naar: **Plugins → Add New → Upload Plugin**
   - Kies het ZIP bestand
   - Klik **Install Now**
   - Klik **Replace current with uploaded** (als gevraagd)
   - Activeer de plugin

3. **Verifieer installatie:**
   - Ga naar: **Google Rating → Instellingen**
   - Scroll naar beneden
   - Je zou nu 4 secties moeten zien:
     - API & Place ID
     - Cache & Shortcode
     - Data verversen
     - **Plugin Updates** ← NIEUW!

### Optie 2: Via GitHub Release (Voor toekomstige updates)

1. **Maak GitHub Release v3.4.2:**
   ```bash
   git add .
   git commit -m "Release v3.4.2 - Add manual update check button"
   git push origin main
   git tag v3.4.2
   git push origin v3.4.2
   ```

2. **Maak release op GitHub:**
   - Ga naar: https://github.com/Websitetoday/google-rating-dynamic-tags-elementor-plugin/releases
   - Klik **Draft a new release**
   - Tag: `v3.4.2`
   - Title: `Version 3.4.2`
   - Description: Kopieer uit CHANGELOG.md
   - Klik **Publish release**

3. **Test automatische update:**
   - Wacht 12 uur (of)
   - Ga naar je live site: **Google Rating → Instellingen**
   - Scroll naar **Plugin Updates**
   - Klik **Controleer op updates**
   - Je zou moeten zien: "Nieuwe versie beschikbaar: v3.4.2"
   - De pagina refresht automatisch
   - Ga naar **Plugins** → Update notificatie verschijnt
   - Klik **Update now**

## De "Controleer op updates" knop

**Locatie:** Google Rating → Instellingen → Plugin Updates (onderaan)

**Functionaliteit:**
- Toont huidige versie
- Link naar GitHub releases
- Knop om handmatig te checken
- Leegt update cache (12 uur cache)
- Checkt GitHub API voor laatste release
- Toont resultaat:
  - "Nieuwe versie beschikbaar: vX.X.X" + link
  - "Je hebt de laatste versie!"
- Auto-refresh pagina als update beschikbaar

**Waarom deze knop nodig is:**
De Simple GitHub Updater checkt automatisch elke 12 uur. Met deze knop kun je:
1. Direct checken zonder te wachten
2. Cache legen als updates niet verschijnen
3. WordPress update systeem forceren

## Troubleshooting

### "Ik zie de knop niet"

**Oplossing:**
1. Controleer plugin versie:
   - Ga naar **Plugins**
   - Check of je versie **3.4.2** hebt
   - Zo niet, upload de plugin opnieuw

2. Clear browser cache:
   - Druk Ctrl+F5 (Windows) of Cmd+Shift+R (Mac)
   - Of clear cache via browser settings

3. Check bestand bestaat:
   - Verificeer: `/wp-content/plugins/google-rating-elementor-plugin/includes/admin/page-settings.php`
   - Zou "Plugin Updates" sectie moeten bevatten (regel 90-94)

### "Update verschijnt niet in WordPress"

**Oplossing:**
1. Klik **Controleer op updates** knop in Google Rating settings
2. Als dat niet werkt:
   - Ga naar **Dashboard → Updates**
   - Klik **Check Again**
3. Forceer transient verwijderen via code:
   ```php
   delete_transient('gre_github_version');
   delete_site_transient('update_plugins');
   ```

### "Fout bij controleren van updates"

**Mogelijke oorzaken:**
- GitHub API rate limit (60 requests/uur zonder auth)
- Geen internet verbinding op server
- GitHub release bestaat niet

**Oplossing:**
1. Wacht 5 minuten en probeer opnieuw
2. Verificeer GitHub release bestaat: https://github.com/Websitetoday/google-rating-dynamic-tags-elementor-plugin/releases
3. Check server kan GitHub bereiken: `curl https://api.github.com`

## Versie Informatie

- **Huidige versie:** 3.4.2
- **Laatste GitHub release:** 3.4.1
- **Next step:** Maak v3.4.2 release op GitHub

## Support

- GitHub Issues: https://github.com/Websitetoday/google-rating-dynamic-tags-elementor-plugin/issues
- Email: info@websitetoday.nl

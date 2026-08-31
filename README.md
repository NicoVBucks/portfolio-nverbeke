# Portfolio Nicolas Verbeke : thème WordPress sur-mesure

Thème PHP natif. Aucune extension, aucun constructeur de page, aucun JavaScript.

## Installation

1. **Apparence → Thèmes → Activer** « Portfolio Nicolas Verbeke ».
   L'activation déclenche `after_switch_theme`, qui réécrit les permaliens :
   pas besoin de passer par Réglages → Permaliens.
2. **Réglages → Lecture** → page d'accueil : « Vos derniers articles »
   (`front-page.php` a la priorité, la page d'accueil s'affiche correctement).
3. Créer les projets dans **Projets → Ajouter**.

## Arborescence

```
functions.php            supports du thème, chargement des styles, deux helpers d'affichage
inc/cpt-projet.php       CPT « projet », taxonomie « technologie », ordre d'affichage
inc/meta-boxes.php       champs contexte / lien_code / lien_site
header.php footer.php    coquille commune ; le bloc contact vit dans le pied
front-page.php           accueil : hero, projets, parcours, compétences, ce site
single-projet.php        page dédiée d'un projet
archive.php              liste des projets et filtre par technologie
page.php                 page simple (mentions légales)
index.php                repli exigé par WordPress
404.php                  page d'erreur
template-parts/projet-bloc.php   bloc projet partagé accueil / archive
style.css                feuille unique, en-tête du thème inclus
assets/fonts/            Fraunces et IBM Plex Mono, subset latin, environ 95 Ko
```

## Choix techniques et justifications

**Un seul `archive.php` au lieu de `archive-projet.php` + `taxonomy-technologie.php`.**
La hiérarchie de templates WordPress fait retomber les deux cas sur `archive.php`.
Deux fichiers auraient été identiques à un titre près.

**Champ « Ordre » natif plutôt qu'un champ personnalisé de tri.**
`page-attributes` est déjà dans le cœur : `menu_order` sert de clé de tri
sur l'accueil et l'archive. Aucun code de tri à écrire.

**Un tableau unique décrit les champs personnalisés** (`nv_champs_projet()`).
Il alimente à la fois le formulaire et l'enregistrement : ajouter un champ
se fait à un seul endroit, et le formulaire ne peut pas diverger du `save`.

**Sécurité des champs personnalisés.**
Nonce, vérification d'autosave, contrôle de capacité `edit_post`, puis
`sanitize_text_field()` ou `esc_url_raw()` selon le type. À l'affichage,
`esc_html()` et `esc_url()`. Un champ vidé déclenche `delete_post_meta()`
plutôt que d'enregistrer une chaîne vide.

**Aucun JavaScript.**
Les ancres, la navigation et les états sont gérés en CSS. Le script de
compatibilité emoji injecté par défaut par WordPress est retiré dans
`nv_disable_emojis()`, sans quoi la promesse serait fausse.

**Polices auto-hébergées.**
Trois fichiers woff2, subset latin, environ 95 Ko. Aucune requête vers
Google Fonts : pas de transfert d'adresse IP vers un tiers, et une
requête réseau de moins. La police des titres est préchargée.

**Un lien vide n'affiche pas de bouton.**
`nv_liens_projet()` saute les champs non renseignés : pas de bouton inerte
si un projet n'a pas de démonstration en ligne.

## Accessibilité

- Lien d'évitement vers le contenu.
- Un seul `h1` par page, hiérarchie de titres continue.
- Focus visible assumé : contour vermillon 2 px avec décalage, sur fond clair
  comme sur fond sombre (`--accent-inverse` dans le pied).
- Liens ouvrant un nouvel onglet annoncés par un texte en `.sr-only`.
- Visuels de projet en `aria-hidden` + `tabindex="-1"` pour éviter un lien
  dupliqué vers la même destination.
- `prefers-reduced-motion` et `forced-colors` pris en charge.
- Contrastes : encre 15,2:1, texte secondaire 6,7:1, accent 5,2:1 (AA).

## États prévus

Aucun projet publié, aucun projet pour une technologie, lien de code ou de
démonstration absent, image mise en avant absente, contenu long, 404.

## À faire

- [ ] `screenshot.png` (1200 × 900) à la racine du thème, pour l'écran Apparence.
- [ ] Renseigner l'hébergeur dans la page Mentions légales.

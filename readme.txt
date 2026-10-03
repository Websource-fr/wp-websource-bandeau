=== WebsourceBandeau ===
Contributors: websource
Tags: bandeau, announcement bar, promo, bannière
Requires at least: 6.0
Tested up to: 6.6
Requires PHP: 8.1
Stable tag: 1.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Un bandeau promotionnel défilant, pleine largeur, au-dessus de l'en-tête du site.

== Description ==

WebsourceBandeau affiche un bandeau d'annonce pleine largeur au-dessus de l'en-tête de votre site, avec jusqu'à 3 messages configurables qui alternent automatiquement.

**Fonctionnalités :**

* Jusqu'à 3 messages : texte, lien optionnel (URL + libellé), et option de mise en gras.
* Sélecteur de couleurs (fond / texte) via le sélecteur de couleurs natif de WordPress.
* Activation/désactivation en un clic.
* Rotation automatique entre les messages, réalisée en CSS pur (animation `@keyframes`), avec un petit script JavaScript natif (sans jQuery, sans dépendance externe) qui calcule les délais d'animation et gère la fermeture.
* Bouton de fermeture optionnel : une fois fermé par le visiteur, le bandeau ne réapparaît pas pendant la durée configurée (mémorisation via `localStorage`).
* S'affiche via le hook `wp_body_open()` (standard WordPress 5.2+) ; repli automatique via le filtre `the_content` pour les thèmes qui ne l'appellent pas ; et hook manuel `do_action( 'websource_bandeau_render' )` disponible pour les thèmes très anciens.

== Installation ==

1. Copiez le dossier `websource-bandeau` dans `wp-content/plugins/` et activez le plugin.
2. Allez dans **Bandeau promo** pour configurer vos messages, couleurs et activer le bandeau.
3. Si le bandeau n'apparaît pas sur votre thème, ajoutez `<?php do_action( 'websource_bandeau_render' ); ?>` juste après `<body>` dans `header.php`.

== Frequently Asked Questions ==

= Le bandeau utilise-t-il jQuery ou une librairie externe en front ? =

Non, le script front-end (`assets/js/wb-bandeau.js`) est écrit en JavaScript natif, sans dépendance. Seul l'écran d'administration utilise le sélecteur de couleurs natif de WordPress (qui dépend de jQuery, déjà chargé dans l'admin).

= Combien de temps le bandeau reste-t-il caché après fermeture ? =

Configurable dans les réglages ("Ne pas réafficher pendant (jours)"), 1 jour par défaut. La valeur 0 le mémorise indéfiniment, jusqu'à ce que le visiteur vide le stockage local de son navigateur.

= Que fait l'encart « Besoin d'aller plus loin ? » dans l'administration ? =

Il propose aux administrateurs (capacité `manage_options`), uniquement sur l'écran principal du plugin, de contacter Websource, l'agence éditrice, pour un accompagnement sur mesure. Il n'apparaît jamais sur le site public ni dans les e-mails, se masque pour 30 jours par utilisateur (bouton « Masquer ») et ne fait aucune requête externe. Seul un clic sur « Nous contacter » ou « Prendre rendez-vous » ouvre le site websource.fr, avec dans l'adresse le nom du plugin, sa version, la version de WordPress et l'adresse de votre site (domaine uniquement) pour faciliter la réponse de Websource.

== Changelog ==

= 1.1.0 =
* Nouveau : encart « Besoin d'aller plus loin ? » (accompagnement Websource) sur l'écran principal du plugin, réservé aux administrateurs, masquable 30 jours par utilisateur. Au clic sur « Nous contacter » / « Prendre rendez-vous », le nom et la version du plugin, la version de WordPress et le domaine du site sont transmis à Websource via l'URL (paramètres utm_* et ws_*). Aucune requête externe automatique.

= 1.0.0 =
* Version initiale.

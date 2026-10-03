# Changelog

## 1.1.0
- Nouveau : encart « Besoin d'aller plus loin ? » (accompagnement Websource) sur l'écran principal du plugin : réservé aux administrateurs (`manage_options`), jamais en front ni dans les e-mails, une seule occurrence par page, masquable 30 jours par utilisateur (user meta, AJAX + nonce).
- Transparence : au clic sur « Nous contacter » ou « Prendre rendez-vous », le slug et la version du plugin, la version de WordPress et le domaine du site sont transmis à Websource via les paramètres de l'URL (`utm_*`, `ws_*`). Aucune requête externe automatique, aucun autre suivi.
- Composant autonome et préfixé (`class-*-support-box.php`), sans dépendance partagée avec les autres plugins Websource.

## 1.0.0
- Version initiale.

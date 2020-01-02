<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clés secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur
 * {@link http://codex.wordpress.org/fr:Modifier_wp-config.php Modifier
 * wp-config.php}. C’est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d’installation. Vous n’avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define( 'DB_NAME', 'wordpress' );

/** Utilisateur de la base de données MySQL. */
define( 'DB_USER', 'root' );

/** Mot de passe de la base de données MySQL. */
define( 'DB_PASSWORD', '' );

/** Adresse de l’hébergement MySQL. */
define( 'DB_HOST', 'localhost' );

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Type de collation de la base de données.
  * N’y touchez que si vous savez ce que vous faites.
  */
define('DB_COLLATE', '');

/**#@+
 * Clés uniques d’authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n’importe quel moment, afin d’invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'E/tXIGX`59EO$0GQ)_iDUD+2<hG-[,l)aI-u5}5^LD5+w]Qb-zf}}fkFf*xpts}6' );
define( 'SECURE_AUTH_KEY',  '9 EL^a`J`B/03me4?{`cR3MA<:Rl+Q:K@%Q~i0!.b8W#]|M*1*7>w#9f&^7]=w}u' );
define( 'LOGGED_IN_KEY',    'G!j$b4]lN(0bS:1Aopyv>yIyI=rU:YXtcAK(pn#XvXTj<u32WUXb9&fEJW|(2t!4' );
define( 'NONCE_KEY',        '7<@WjOW:at-=)|VE)Q0NA XDY5fy18DU>|pMzyJQ>YaotwFg`Kkh-b#:<yT(d0~X' );
define( 'AUTH_SALT',        ')FgwJ3f-Sx%#J.xDD7a#Ng>&hj/cLtX$CD>9()]5[H:7&wBGIeV&#Uf}}i{v1@mN' );
define( 'SECURE_AUTH_SALT', 'k*.$3<]>&G$%_5uwZ`qzxlofYtXMU%W|Fi$kU=,++;8A/xLwf[=NS]};w,l?W_7e' );
define( 'LOGGED_IN_SALT',   '9]N:L.,twHz][Xx[zC+!1uF_}mG=AS/1^vPD|qbs$Y}SJdZX5[GpHq3zGB=s6`4i' );
define( 'NONCE_SALT',       'aJr!/YH]d`JN>uJT{Uw;]z>hRW+>xqyZ%k2UBxRk3w)TF=Dkv+h<9i|YVj/Oe/.J' );
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N’utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés !
 */
$table_prefix = 'wp_';

/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l’affichage des
 * notifications d’erreurs pendant vos essais.
 * Il est fortemment recommandé que les développeurs d’extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d’information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur le Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* C’est tout, ne touchez pas à ce qui suit ! Bonne publication. */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');

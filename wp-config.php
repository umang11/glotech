<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'glotech-com');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'RX(:;Ft^{6QhoxYoD:(j<o)fM+MCF[++$Pj]ql+O)j^ZU%,-M:3bSU<C@rX|kWE[');
define('SECURE_AUTH_KEY',  '+7UE3oaXI,RLK9?#*$qY-1B$uFk^zp8x70r~A5z_M8-x+xG!}%,$tlZq0d6+^=v-');
define('LOGGED_IN_KEY',    ').u+Gjl~q/gRw5:=<l;Iq+6||q8lDMGe`=<,/hl<f|LWA@{+%K FKAFbxgxRm4--');
define('NONCE_KEY',        'N_~@i&q|Q;O|M|n.W&lbH7`,k>>Eg^2Z`O6~SuTrx$%@9!WdZ9@ |IDTC*<e?<++');
define('AUTH_SALT',        'Kwj.AeSjw|*I!33OWZUO6dlwf;<iXI++M;W_?|B-x)t#Hv,/rkGM7fT.VGJ`drX_');
define('SECURE_AUTH_SALT', 'k$)H%-i7M<+Uz>VE-wFqa)|;)UvI!ALSIE+KoK36d9GD7_vp5;yL@kr?4d7q]3YD');
define('LOGGED_IN_SALT',   'U&8ltO+S`r0OFu#hi+[kCxRRD2!X=j6%eF}]FU:tl?p.=f^:rOo%b7=h.JbF:H5h');
define('NONCE_SALT',       '<5}B#lm#5a{%26~G&+h-v>N-+fC?A7O-!%_q!+*1Gw&1bnPW+(qU`|rsc@T~xiOY');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'gl';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

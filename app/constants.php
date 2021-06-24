<?php

// Root directory
define('ROOT_DIR', __DIR__.DIRECTORY_SEPARATOR.'..');

// App directory
define('APP_DIR', __DIR__);

// Data directory location
defined('DATA_DIR') or define('DATA_DIR', getenv('DATA_DIR') ?: ROOT_DIR.DIRECTORY_SEPARATOR.'data');

// Files directory (attachments)
defined('FILES_DIR') or define('FILES_DIR', getenv('FILES_DIR') ?: DATA_DIR.DIRECTORY_SEPARATOR.'files');

// Available cache drivers are "file" and "memory"
defined('CACHE_DRIVER') or define('CACHE_DRIVER', getenv('CACHE_DRIVER') ?: 'memory');

// Cache folder (file driver)
defined('CACHE_DIR') or define('CACHE_DIR', getenv('CACHE_DIR') ?: DATA_DIR.DIRECTORY_SEPARATOR.'cache');

// Plugins settings
defined('PLUGINS_DIR') or define('PLUGINS_DIR', getenv('PLUGINS_DIR') ?: ROOT_DIR.DIRECTORY_SEPARATOR.'plugins');
defined('PLUGIN_API_URL') or define('PLUGIN_API_URL', getenv('PLUGIN_API_URL') ?: 'https://kanboard.org/plugins.json');
defined('PLUGIN_INSTALLER') or define('PLUGIN_INSTALLER', strtolower(getenv('PLUGIN_INSTALLER')) === 'true'); // Disabled by default for security reasons

// Enable/disable debug
defined('DEBUG') or define('DEBUG', strtolower(getenv('DEBUG')) === 'true');

// Logging drivers: syslog, stdout, stderr, system or file
defined('LOG_DRIVER') or define('LOG_DRIVER', getenv('LOG_DRIVER') ?: 'system');

// Logging file
defined('LOG_FILE') or define('LOG_FILE', getenv('LOG_FILE') ?: DATA_DIR.DIRECTORY_SEPARATOR.'debug.log');

// Application version
defined('APP_VERSION') or define('APP_VERSION', getenv('APP_VERSION') ?: build_app_version('$Format:%d$', '$Format:%H$'));

// Run automatically database migrations
defined('DB_RUN_MIGRATIONS') or define('DB_RUN_MIGRATIONS', getenv('DB_RUN_MIGRATIONS') ? strtolower(getenv('DB_RUN_MIGRATIONS')) === 'true' : true);

// Database driver: sqlite, mysql or postgres
defined('DB_DRIVER') or define('DB_DRIVER', getenv('DB_DRIVER') ?: 'sqlite');

// Sqlite configuration
defined('DB_FILENAME') or define('DB_FILENAME', getenv('DB_FILENAME') ?: DATA_DIR.DIRECTORY_SEPARATOR.'db.sqlite');

// Mysql/Postgres configuration
defined('DB_USERNAME') or define('DB_USERNAME', getenv('DB_USERNAME') ?: 'root');
defined('DB_PASSWORD') or define('DB_PASSWORD', getenv('DB_PASSWORD') ?: '');
defined('DB_HOSTNAME') or define('DB_HOSTNAME', getenv('DB_HOSTNAME') ?: 'localhost');
defined('DB_NAME') or define('DB_NAME', getenv('DB_NAME') ?: 'kanboard');
defined('DB_PORT') or define('DB_PORT', intval(getenv('DB_PORT')) ?: null);
defined('DB_SSL_KEY') or define('DB_SSL_KEY', getenv('DB_SSL_KEY') ?: null);
defined('DB_SSL_CERT') or define('DB_SSL_CERT', getenv('DB_SSL_CERT') ?: null);
defined('DB_SSL_CA') or define('DB_SSL_CA', getenv('DB_SSL_CA') ?: null);
defined('DB_VERIFY_SERVER_CERT') or define('DB_VERIFY_SERVER_CERT', getenv('DB_VERIFY_SERVER_CERT') ?: null);
defined('DB_TIMEOUT') or define('DB_TIMEOUT', intval(getenv('DB_TIMEOUT')) ?: null);

// Database backend group provider
defined('DB_GROUP_PROVIDER') or define('DB_GROUP_PROVIDER', getenv('DB_GROUP_PROVIDER') ? strtolower(getenv('DB_GROUP_PROVIDER')) === 'true' : true);
defined('DB_USER_PROVIDER') or define('DB_USER_PROVIDER', getenv('DB_USER_PROVIDER') ? strtolower(getenv('DB_USER_PROVIDER')) === 'true': true);

// LDAP configuration
defined('LDAP_AUTH') or define('LDAP_AUTH', strtolower(getenv('LDAP_AUTH')) === 'true');
defined('LDAP_SERVER') or define('LDAP_SERVER', getenv('LDAP_SERVER') ?: '');
defined('LDAP_PORT') or define('LDAP_PORT', intval(getenv('LDAP_PORT')) ?: 389);
defined('LDAP_SSL_VERIFY') or define('LDAP_SSL_VERIFY', getenv('LDAP_SSL_VERIFY') ? strtolower(getenv('LDAP_SSL_VERIFY')) === 'true' : true);
defined('LDAP_START_TLS') or define('LDAP_START_TLS', strtolower(getenv('LDAP_START_TLS')) === 'true');
defined('LDAP_USERNAME_CASE_SENSITIVE') or define('LDAP_USERNAME_CASE_SENSITIVE', strtolower(getenv('LDAP_USERNAME_CASE_SENSITIVE')) === 'true');

defined('LDAP_BIND_TYPE') or define('LDAP_BIND_TYPE', getenv('LDAP_BIND_TYPE') ?: 'anonymous');
defined('LDAP_USERNAME') or define('LDAP_USERNAME', getenv('LDAP_USERNAME') ?: null);
defined('LDAP_PASSWORD') or define('LDAP_PASSWORD', getenv('LDAP_PASSWORD') ?: null);

defined('LDAP_USER_BASE_DN') or define('LDAP_USER_BASE_DN', getenv('LDAP_USER_BASE_DN') ?: '');
defined('LDAP_USER_FILTER') or define('LDAP_USER_FILTER', getenv('LDAP_USER_FILTER') ?: '');
defined('LDAP_USER_ATTRIBUTE_USERNAME') or define('LDAP_USER_ATTRIBUTE_USERNAME', getenv('LDAP_USER_ATTRIBUTE_USERNAME') ?: 'uid');
defined('LDAP_USER_ATTRIBUTE_FULLNAME') or define('LDAP_USER_ATTRIBUTE_FULLNAME', getenv('LDAP_USER_ATTRIBUTE_FULLNAME') ?: 'cn');
defined('LDAP_USER_ATTRIBUTE_EMAIL') or define('LDAP_USER_ATTRIBUTE_EMAIL', getenv('LDAP_USER_ATTRIBUTE_EMAIL') ?: 'mail');
defined('LDAP_USER_ATTRIBUTE_GROUPS') or define('LDAP_USER_ATTRIBUTE_GROUPS', getenv('LDAP_USER_ATTRIBUTE_GROUPS') ?: 'memberof');
defined('LDAP_USER_ATTRIBUTE_PHOTO') or define('LDAP_USER_ATTRIBUTE_PHOTO', getenv('LDAP_USER_ATTRIBUTE_PHOTO') ?: '');
defined('LDAP_USER_ATTRIBUTE_LANGUAGE') or define('LDAP_USER_ATTRIBUTE_LANGUAGE', getenv('LDAP_USER_ATTRIBUTE_LANGUAGE') ?: '');
defined('LDAP_USER_CREATION') or define('LDAP_USER_CREATION', getenv('LDAP_USER_CREATION') ? strtolower(getenv('LDAP_USER_CREATION')) === 'true' : true);
defined('LDAP_USER_DEFAULT_ROLE_MANAGER') or define('LDAP_USER_DEFAULT_ROLE_MANAGER', getenv('LDAP_USER_DEFAULT_ROLE_MANAGER') ? strtolower(getenv('LDAP_USER_DEFAULT_ROLE_MANAGER')) === 'true' : false);

defined('LDAP_GROUP_ADMIN_DN') or define('LDAP_GROUP_ADMIN_DN', getenv('LDAP_GROUP_ADMIN_DN') ?: '');
defined('LDAP_GROUP_MANAGER_DN') or define('LDAP_GROUP_MANAGER_DN', getenv('LDAP_GROUP_MANAGER_DN') ?: '');

defined('LDAP_GROUP_PROVIDER') or define('LDAP_GROUP_PROVIDER', strtolower(getenv('LDAP_GROUP_PROVIDER')) === 'true');
defined('LDAP_GROUP_BASE_DN') or define('LDAP_GROUP_BASE_DN', getenv('LDAP_GROUP_BASE_DN') ?: '');
defined('LDAP_GROUP_FILTER') or define('LDAP_GROUP_FILTER', getenv('LDAP_GROUP_FILTER') ?: '');
defined('LDAP_GROUP_USER_FILTER') or define('LDAP_GROUP_USER_FILTER', getenv('LDAP_GROUP_USER_FILTER') ?: '');
defined('LDAP_GROUP_USER_ATTRIBUTE') or define('LDAP_GROUP_USER_ATTRIBUTE', getenv('LDAP_GROUP_USER_ATTRIBUTE') ?: 'username');
defined('LDAP_GROUP_ATTRIBUTE_NAME') or define('LDAP_GROUP_ATTRIBUTE_NAME', getenv('LDAP_GROUP_ATTRIBUTE_NAME') ?: 'cn');

// Proxy authentication
defined('REVERSE_PROXY_AUTH') or define('REVERSE_PROXY_AUTH', strtolower(getenv('REVERSE_PROXY_AUTH')) === 'true');
defined('REVERSE_PROXY_USER_HEADER') or define('REVERSE_PROXY_USER_HEADER', getenv('REVERSE_PROXY_USER_HEADER') ?: 'REMOTE_USER');
defined('REVERSE_PROXY_NAME_HEADER') or define('REVERSE_PROXY_NAME_HEADER', getenv('REVERSE_PROXY_NAME_HEADER') ?: 'REMOTE_NAME');
defined('REVERSE_PROXY_GET_FIELDS') or define('REVERSE_PROXY_GET_FIELDS', getenv('REVERSE_PROXY_GET_FIELDS') ? strtolower(getenv('REVERSE_PROXY_GET_FIELDS')) === 'false' : false);
defined('REVERSE_PROXY_EMAIL_HEADER') or define('REVERSE_PROXY_EMAIL_HEADER', getenv('REVERSE_PROXY_EMAIL_HEADER') ?: 'REMOTE_EMAIL');
defined('REVERSE_PROXY_DEFAULT_ADMIN') or define('REVERSE_PROXY_DEFAULT_ADMIN', getenv('REVERSE_PROXY_DEFAULT_ADMIN') ?: '');
defined('REVERSE_PROXY_DEFAULT_DOMAIN') or define('REVERSE_PROXY_DEFAULT_DOMAIN', getenv('REVERSE_PROXY_DEFAULT_DOMAIN') ?: '');

// Remember me authentication
defined('REMEMBER_ME_AUTH') or define('REMEMBER_ME_AUTH', getenv('REMEMBER_ME_AUTH') ? strtolower(getenv('REMEMBER_ME_AUTH')) === 'true' : true);

// Mail configuration
defined('MAIL_CONFIGURATION') or define('MAIL_CONFIGURATION', getenv('MAIL_CONFIGURATION') ? strtolower(getenv('MAIL_CONFIGURATION')) === 'true' : true);
defined('MAIL_FROM') or define('MAIL_FROM', getenv('MAIL_FROM') ?: 'notifications@kanboard.local');
defined('MAIL_BCC') or define('MAIL_BCC', getenv('MAIL_BCC') ?: '');
defined('MAIL_TRANSPORT') or define('MAIL_TRANSPORT', getenv('MAIL_TRANSPORT') ?: 'mail');
defined('MAIL_SMTP_HOSTNAME') or define('MAIL_SMTP_HOSTNAME', getenv('MAIL_SMTP_HOSTNAME') ?: '');
defined('MAIL_SMTP_PORT') or define('MAIL_SMTP_PORT', intval(getenv('MAIL_SMTP_PORT')) ?: 25);
defined('MAIL_SMTP_USERNAME') or define('MAIL_SMTP_USERNAME', getenv('MAIL_SMTP_USERNAME') ?: '');
defined('MAIL_SMTP_PASSWORD') or define('MAIL_SMTP_PASSWORD', getenv('MAIL_SMTP_PASSWORD') ?: '');
defined('MAIL_SMTP_HELO_NAME') or define('MAIL_SMTP_HELO_NAME', getenv('MAIL_SMTP_HELO_NAME') ?: null);
defined('MAIL_SMTP_ENCRYPTION') or define('MAIL_SMTP_ENCRYPTION', getenv('MAIL_SMTP_ENCRYPTION') ?: null);
defined('MAIL_SENDMAIL_COMMAND') or define('MAIL_SENDMAIL_COMMAND', getenv('MAIL_SENDMAIL_COMMAND') ?: '/usr/sbin/sendmail -bs');

// Enable or disable "Strict-Transport-Security" HTTP header
defined('ENABLE_HSTS') or define('ENABLE_HSTS', getenv('ENABLE_HSTS') ? strtolower(getenv('ENABLE_HSTS')) === 'true' : true);

// Enable or disable "X-Frame-Options: DENY" HTTP header
defined('ENABLE_XFRAME') or define('ENABLE_XFRAME', getenv('ENABLE_XFRAME') ? strtolower(getenv('ENABLE_XFRAME')) === 'true' : true);

// Escape html inside markdown text
defined('MARKDOWN_ESCAPE_HTML') or define('MARKDOWN_ESCAPE_HTML', getenv('MARKDOWN_ESCAPE_HTML') ? strtolower(getenv('MARKDOWN_ESCAPE_HTML')) === 'true' : true);

// API alternative authentication header, the default is HTTP Basic Authentication defined in RFC2617
defined('API_AUTHENTICATION_HEADER') or define('API_AUTHENTICATION_HEADER', getenv('API_AUTHENTICATION_HEADER') ?: '');

// Enable/disable url rewrite
defined('ENABLE_URL_REWRITE') or define('ENABLE_URL_REWRITE', getenv('ENABLE_URL_REWRITE') ?: isset($_SERVER['HTTP_MOD_REWRITE']));

// Hide login form
defined('HIDE_LOGIN_FORM') or define('HIDE_LOGIN_FORM', strtolower(getenv('HIDE_LOGIN_FORM')) === 'true');

// Disabling logout (for external SSO authentication)
defined('DISABLE_LOGOUT') or define('DISABLE_LOGOUT', strtolower(getenv('DISABLE_LOGOUT')) === true);

// Bruteforce protection
defined('BRUTEFORCE_CAPTCHA') or define('BRUTEFORCE_CAPTCHA', intval(getenv('BRUTEFORCE_CAPTCHA')) ?: 3);
defined('BRUTEFORCE_LOCKDOWN') or define('BRUTEFORCE_LOCKDOWN', intval(getenv('BRUTEFORCE_LOCKDOWN')) ?: 6);
defined('BRUTEFORCE_LOCKDOWN_DURATION') or define('BRUTEFORCE_LOCKDOWN_DURATION', intval(getenv('BRUTEFORCE_LOCKDOWN_DURATION')) ?: 15);

// Session duration in second (0 = until the browser is closed)
// See http://php.net/manual/en/session.configuration.php#ini.session.cookie-lifetime
defined('SESSION_DURATION') or define('SESSION_DURATION', intval(getenv('SESSION_DURATION')) ?: 0);

// Session handler: db or php
defined('SESSION_HANDLER') or define('SESSION_HANDLER', getenv('SESSION_HANDLER') ?: 'db');

// HTTP Client
defined('HTTP_TIMEOUT') or define('HTTP_TIMEOUT', intval(getenv('HTTP_TIMEOUT')) ?: 10);
defined('HTTP_MAX_REDIRECTS') or define('HTTP_MAX_REDIRECTS', intval(getenv('HTTP_MAX_REDIRECTS')) ?: 3);
defined('HTTP_PROXY_HOSTNAME') or define('HTTP_PROXY_HOSTNAME', getenv('HTTP_PROXY_HOSTNAME') ?: '');
defined('HTTP_PROXY_PORT') or define('HTTP_PROXY_PORT', getenv('HTTP_PROXY_PORT') ?: '3128');
defined('HTTP_PROXY_USERNAME') or define('HTTP_PROXY_USERNAME', getenv('HTTP_PROXY_USERNAME') ?: '');
defined('HTTP_PROXY_PASSWORD') or define('HTTP_PROXY_PASSWORD', getenv('HTTP_PROXY_PASSWORD') ?: '');
defined('HTTP_PROXY_EXCLUDE') or define('HTTP_PROXY_EXCLUDE', getenv('HTTP_PROXY_EXCLUDE') ?: 'localhost');
defined('HTTP_VERIFY_SSL_CERTIFICATE') or define('HTTP_VERIFY_SSL_CERTIFICATE', getenv('HTTP_VERIFY_SSL_CERTIFICATE') ? strtolower(getenv('HTTP_VERIFY_SSL_CERTIFICATE')) === 'true' : true);

defined('TOTP_ISSUER') or define('TOTP_ISSUER', getenv('TOTP_ISSUER') ?: 'Kanboard');

// Comma separated list of fields to not synchronize when using external authentication providers
defined('EXTERNAL_AUTH_EXCLUDE_FIELDS') or define('EXTERNAL_AUTH_EXCLUDE_FIELDS', getenv('EXTERNAL_AUTH_EXCLUDE_FIELDS') ?: 'username');

// Enable or disable displaying group-memberships in userlist (true by default)
defined('SHOW_GROUP_MEMBERSHIPS_IN_USERLIST') or define('SHOW_GROUP_MEMBERSHIPS_IN_USERLIST', getenv('SHOW_GROUP_MEMBERSHIPS_IN_USERLIST') ?: true);

// Limit number of groups to display in userlist (The full list of group-memberships is always shown, ...
// ... when hovering the mouse over the group-icon of a given user!)
// If set to 0 ALL group-memberships will be listed (7 by default)
defined('SHOW_GROUP_MEMBERSHIPS_IN_USERLIST_WITH_LIMIT') or define('SHOW_GROUP_MEMBERSHIPS_IN_USERLIST_WITH_LIMIT', intval(getenv('SHOW_GROUP_MEMBERSHIPS_IN_USERLIST_WITH_LIMIT')) ?: 7);

// Documentation URL
defined('DOCUMENTATION_URL_PATTERN') or define('DOCUMENTATION_URL_PATTERN', getenv('DOCUMENTATION_URL_PATTERN') ?: 'https://docs.kanboard.org/en/%s/user_guide/%s.html');

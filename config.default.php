<?php

/*******************************************************************/
/* Rename this file to config.php if you want to change the values */
/*                                                                 */
/* Make sure all paths are absolute by using __DIR__ where needed  */
/*******************************************************************/

// Data folder (must be writeable by the web server user and absolute)
define('DATA_DIR', __DIR__.DIRECTORY_SEPARATOR.'data');

// Enable/Disable debug
define('DEBUG', false);

// Available log drivers: syslog, stderr, stdout, system or file
define('LOG_DRIVER', 'system');

// Log filename if the log driver is "file"
define('LOG_FILE', DATA_DIR.DIRECTORY_SEPARATOR.'debug.log');

// Plugins directory
define('PLUGINS_DIR', __DIR__.DIRECTORY_SEPARATOR.'plugins');

// Plugins directory URL
define('PLUGIN_API_URL', 'https://kanboard.org/plugins.json');

// Enable/Disable plugin installer (Disabled by default for security reasons)
// There is no code review or any approval process to submit a plugin.
// This is up to the Kanboard instance owner to validate if a plugin is legit.
define('PLUGIN_INSTALLER', false);

// Available cache drivers are "file" and "memory"
define('CACHE_DRIVER', 'memory');

// Cache folder to use if cache driver is "file" (must be writeable by the web server user)
define('CACHE_DIR', DATA_DIR.DIRECTORY_SEPARATOR.'cache');

// Folder for uploaded files (must be writeable by the web server user)
define('FILES_DIR', DATA_DIR.DIRECTORY_SEPARATOR.'files');

// Enable/disable email configuration from the user interface
define('MAIL_CONFIGURATION', true);

// E-mail address used for the "From" header (notifications)
define('MAIL_FROM', 'replace-me@kanboard.local');

// E-mail address used for the "Bcc" header to send a copy of all notifications
define('MAIL_BCC', '');

// Mail transport available: "smtp", "sendmail", "mail" (PHP mail function), "postmark", "mailgun", "sendgrid"
define('MAIL_TRANSPORT', 'mail');

// SMTP configuration to use when the "smtp" transport is chosen
define('MAIL_SMTP_HOSTNAME', '');
define('MAIL_SMTP_PORT', 25);
define('MAIL_SMTP_USERNAME', '');
define('MAIL_SMTP_PASSWORD', '');
define('MAIL_SMTP_HELO_NAME', null); // valid: null (default), or FQDN
define('MAIL_SMTP_ENCRYPTION', null); // Valid values are null (not a string "null"), "ssl" or "tls"

// Sendmail command to use when the transport is "sendmail"
define('MAIL_SENDMAIL_COMMAND', '/usr/sbin/sendmail -bs');

// Run automatically database migrations
// If set to false, you will have to run manually the SQL migrations from the CLI during the next Kanboard upgrade
// Do not run the migrations from multiple processes at the same time (example: web page + background worker)
define('DB_RUN_MIGRATIONS', true);

// Database driver: sqlite, mysql or postgres (sqlite by default)
define('DB_DRIVER', 'sqlite');

// Mysql/Postgres username
define('DB_USERNAME', 'root');

// Mysql/Postgres password
define('DB_PASSWORD', '');

// Mysql/Postgres hostname
define('DB_HOSTNAME', 'localhost');

// Mysql/Postgres database name
define('DB_NAME', 'kanboard');

// Mysql/Postgres custom port (null = default port)
define('DB_PORT', null);

// Mysql SSL key
define('DB_SSL_KEY', null);

// Mysql SSL certificate
define('DB_SSL_CERT', null);

// Mysql SSL CA
define('DB_SSL_CA', null);

// Mysql SSL server verification, set to false if you don't want the Mysql driver to validate the certificate CN
define('DB_VERIFY_SERVER_CERT', null);

// Timeout value for PDO attribute
define('DB_TIMEOUT', null);

// Enable LDAP authentication (false by default)
define('LDAP_AUTH', false);

// LDAP server protocol, hostname and port URL (ldap[s]://hostname:port)
define('LDAP_SERVER', '');

// By default, require certificate to be verified for ldaps:// style URL. Set to false to skip the verification
define('LDAP_SSL_VERIFY', true);

// Enable LDAP START_TLS
define('LDAP_START_TLS', false);

// By default Kanboard lowercase the ldap username to avoid duplicate users (the database is case sensitive)
// Set to true if you want to preserve the case
define('LDAP_USERNAME_CASE_SENSITIVE', false);

// LDAP bind type: "anonymous", "user" or "proxy"
define('LDAP_BIND_TYPE', 'anonymous');

// LDAP username to use with proxy mode
// LDAP username pattern to use with user mode
define('LDAP_USERNAME', null);

// LDAP password to use for proxy mode
define('LDAP_PASSWORD', null);

// LDAP DN for users
// Example for ActiveDirectory: CN=Users,DC=kanboard,DC=local
// Example for OpenLDAP: ou=People,dc=example,dc=com
define('LDAP_USER_BASE_DN', '');

// LDAP pattern to use when searching for a user account
// Example for ActiveDirectory: '(&(objectClass=user)(sAMAccountName=%s))'
// Example for OpenLDAP: 'uid=%s'
define('LDAP_USER_FILTER', '');

// LDAP attribute for username
// Example for ActiveDirectory: 'sAMAccountName'
// Example for OpenLDAP: 'uid'
define('LDAP_USER_ATTRIBUTE_USERNAME', 'uid');

// LDAP attribute for user full name
// Example for ActiveDirectory: 'displayname'
// Example for OpenLDAP: 'cn'
define('LDAP_USER_ATTRIBUTE_FULLNAME', 'cn');

// LDAP attribute for user email
define('LDAP_USER_ATTRIBUTE_EMAIL', 'mail');

// LDAP attribute to find groups in user profile
define('LDAP_USER_ATTRIBUTE_GROUPS', 'memberof');

// LDAP attribute for user avatar image: thumbnailPhoto or jpegPhoto
define('LDAP_USER_ATTRIBUTE_PHOTO', '');

// LDAP attribute for user language, example: 'preferredlanguage'
// Put an empty string to disable language sync
define('LDAP_USER_ATTRIBUTE_LANGUAGE', '');

// Allow automatic LDAP user creation
define('LDAP_USER_CREATION', true);

// Set new user as Manager
define('LDAP_USER_DEFAULT_ROLE_MANAGER', false);

// LDAP DN for administrators
// Example: CN=Kanboard-Admins,CN=Users,DC=kanboard,DC=local
define('LDAP_GROUP_ADMIN_DN', '');

// LDAP DN for managers
// Example: CN=Kanboard Managers,CN=Users,DC=kanboard,DC=local
define('LDAP_GROUP_MANAGER_DN', '');

// Enable LDAP group provider for project permissions
// The end-user will be able to browse LDAP groups from the user interface and allow access to specified projects
define('LDAP_GROUP_PROVIDER', false);

// LDAP Base DN for groups
define('LDAP_GROUP_BASE_DN', '');

// LDAP group filter
// Example for ActiveDirectory: (&(objectClass=group)(sAMAccountName=%s*))
define('LDAP_GROUP_FILTER', '');

// LDAP user group filter
// If this filter is configured, Kanboard will search user groups in LDAP_GROUP_BASE_DN with this filter
// Example for OpenLDAP: (&(objectClass=posixGroup)(memberUid=%s))
define('LDAP_GROUP_USER_FILTER', '');

// LDAP attribute for the user in the group filter
// 'username' or 'dn'
define('LDAP_GROUP_USER_ATTRIBUTE', 'username');

// LDAP attribute for the group name
define('LDAP_GROUP_ATTRIBUTE_NAME', 'cn');

// Enable/disable the reverse proxy authentication
define('REVERSE_PROXY_AUTH', false);

// Enable/disable fetching user attributes from headers
define('REVERSE_PROXY_GET_FIELDS', FALSE);

// Header name to use for the username
define('REVERSE_PROXY_USER_HEADER', 'REMOTE_USER');

// Header name to use for the common name  ( need REVERSE_PROXY_GET_FIELDS to True)
define('REVERSE_PROXY_NAME_HEADER', 'REMOTE_CN');

// Username of the admin, by default blank
define('REVERSE_PROXY_DEFAULT_ADMIN', '');

// Header name to use for the username
define('REVERSE_PROXY_EMAIL_HEADER', 'REMOTE_EMAIL');

// Default domain to use for setting the email address
define('REVERSE_PROXY_DEFAULT_DOMAIN', '');

// Enable/disable remember me authentication
define('REMEMBER_ME_AUTH', true);

// Enable or disable "Strict-Transport-Security" HTTP header
define('ENABLE_HSTS', true);

// Enable or disable "X-Frame-Options: DENY" HTTP header
define('ENABLE_XFRAME', true);

// Escape html inside markdown text
define('MARKDOWN_ESCAPE_HTML', true);

// API alternative authentication header, the default is HTTP Basic Authentication defined in RFC2617
define('API_AUTHENTICATION_HEADER', '');

// Enable/disable url rewrite
define('ENABLE_URL_REWRITE', false);

// Hide login form, useful if all your users use Google/Github/ReverseProxy authentication
define('HIDE_LOGIN_FORM', false);

// Disabling logout (useful for external SSO authentication)
define('DISABLE_LOGOUT', false);

// Enable captcha after 3 authentication failure
define('BRUTEFORCE_CAPTCHA', 3);

// Lock the account after 6 authentication failure
define('BRUTEFORCE_LOCKDOWN', 6);

// Lock account duration in minute
define('BRUTEFORCE_LOCKDOWN_DURATION', 15);

// Session duration in second (0 = until the browser is closed)
// See http://php.net/manual/en/session.configuration.php#ini.session.cookie-lifetime
define('SESSION_DURATION', 0);

// Session handler: db or php
define('SESSION_HANDLER', 'db');

// HTTP client proxy
define('HTTP_PROXY_HOSTNAME', '');
define('HTTP_PROXY_PORT', '3128');
define('HTTP_PROXY_USERNAME', '');
define('HTTP_PROXY_PASSWORD', '');
define('HTTP_PROXY_EXCLUDE', 'localhost');

// Set to false to allow self-signed certificates
define('HTTP_VERIFY_SSL_CERTIFICATE', true);

// TOTP (2FA) issuer name
define('TOTP_ISSUER', 'Kanboard');

// Comma separated list of fields to not synchronize when using external authentication providers
define('EXTERNAL_AUTH_EXCLUDE_FIELDS', 'username');

// Enable or disable displaying group-memberships in userlist (true by default)
define('SHOW_GROUP_MEMBERSHIPS_IN_USERLIST', true);

// Limit number of groups to display in userlist (The full list of group-memberships is always shown, ...
// ... when hovering the mouse over the group-icon of a given user!)
// If set to 0 ALL group-memberships will be listed (7 by default)
define('SHOW_GROUP_MEMBERSHIPS_IN_USERLIST_WITH_LIMIT', 7);

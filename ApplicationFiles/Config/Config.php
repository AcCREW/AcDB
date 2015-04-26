<?php

/**
 * Setting default configs needed.
 */

Application::SetConfig('site_title', 'AcDB');
Application::SetConfig('template', 'Default');
Application::SetConfig('autoload_libraries', array('CUtf8', 'CSession'));
Application::SetConfig('autoload_helpers', array());
Application::SetConfig('charset', 'UTF-8');

Application::SetConfig('ENCRYPTION_KEY', 'AcDB');
Application::SetConfig('CSRF_PROTECTION', true);
Application::SetConfig('CSRF_TOKEN_NAME', 'ac_csrf_token');
Application::SetConfig('CSRF_COOKIE_NAME', 'ac_csrf_token');
Application::SetConfig('HASH_TYPE', 'sha1');
Application::SetConfig('ALLOW_GET_ARRAY', true);
Application::SetConfig('ENABLE_XSS_FILTERING', true);

Application::SetConfig('PROXY_IPS', '');

Application::SetConfig('COOKIE_PATH', '/');
Application::SetConfig('COOKIE_DOMAIN', null);
Application::SetConfig('COOKIE_SECURE', false);
Application::SetConfig('COOKIE_PREFIX', '');

Application::SetConfig('SESS_ENCRYPT_COOKIE', true);
Application::SetConfig('SESS_EXPIRATION', 7200);
Application::SetConfig('SESS_EXPIRE_ON_CLOSE', false);
Application::SetConfig('SESS_MATCH_IP', false);
Application::SetConfig('SESS_MATCH_USERAGENT', true);
Application::SetConfig('SESS_COOKIE_NAME', 'AC_SESSION');
Application::SetConfig('SESS_TIME_TO_UPDATE', 300);
Application::SetConfig('TIME_REFERENCE', 'time');

Application::SetConfig('MYSQL_HOST', 'localhost');
Application::SetConfig('MYSQL_USER', 'root');
Application::SetConfig('MYSQL_PASSWORD', '');
Application::SetConfig('MYSQL_DB', 'AcDB');
Application::SetConfig('MYSQL_PORT', '3306');
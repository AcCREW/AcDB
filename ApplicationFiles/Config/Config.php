<?php

/**
 * Setting default configs needed.
 */

Application::SetConfig('ENCRYPTION_KEY', 'AcDB');
Application::SetConfig('HASH_TYPE', 'sha1');

Application::SetConfig('site_title', 'AcDB');
Application::SetConfig('template', 'Default');
Application::SetConfig('autoload_libraries', array('CUtf8', 'CSession'));
Application::SetConfig('autoload_helpers', array());
Application::SetConfig('ENCRYPTION_KEY', 'AcDB');
Application::SetConfig('allow_get_array', true);
Application::SetConfig('global_xss_filtering', true);
Application::SetConfig('CSRF_PROTECTION', true);
Application::SetConfig('charset', 'UTF-8');

Application::SetConfig('COOKIE_PATH', '/');
Application::SetConfig('COOKIE_DOMAIN', null);
Application::SetConfig('COOKIE_SECURE', false);
Application::SetConfig('COOKIE_PREFIX', '');

Application::SetConfig('PROXY_IPS', '');

Application::SetConfig('SESS_ENCRYPT_COOKIE', true);
Application::SetConfig('SESS_EXPIRATION', 7200);
Application::SetConfig('SESS_EXPIRE_ON_CLOSE', false);

Application::SetConfig('SESS_MATCH_IP', false);
Application::SetConfig('SESS_MATCH_USERAGENT', true);
Application::SetConfig('SESS_COOKIE_NAME', 'AC_SESSION');
Application::SetConfig('SESS_TIME_TO_UPDATE', 300);
Application::SetConfig('TIME_REFERENCE', 'time');

Application::SetConfig('mysql_host', 'localhost');
Application::SetConfig('mysql_user', 'root');
Application::SetConfig('mysql_password', '');
Application::SetConfig('mysql_db', 'AcDB');
Application::SetConfig('mysql_port', '3306');
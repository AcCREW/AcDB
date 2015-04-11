<?php

/**
 * Setting default configs needed.
 */

Application::SetConfig('site_title', 'AcDB');
Application::SetConfig('template', 'Default');
Application::SetConfig('autoload_libraries', array('Session', 'Parser'));
Application::SetConfig('autoload_helpers', array());
Application::SetConfig('encryption_key', 'AcDB');
Application::SetConfig('allow_get_array', true);
Application::SetConfig('global_xss_filtering', true);
Application::SetConfig('csrf_protection', true);
Application::SetConfig('charset', true);
Application::SetConfig('sess_encrypt_cookie', true);

Application::SetConfig('mysql_host', 'localhost');
Application::SetConfig('mysql_user', 'root');
Application::SetConfig('mysql_password', '');
Application::SetConfig('mysql_db', 'AcDB');
Application::SetConfig('mysql_port', '3306');
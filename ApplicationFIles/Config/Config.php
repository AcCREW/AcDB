<?php

/**
 * Setting default configs needed.
 */

Application::SetConfig('autoload_libraries', array('URI', 'Session', 'Parser'));
Application::SetConfig('autoload_helpers', array());
Application::SetConfig('encryption_key', 'AcDB');
Application::SetConfig('allow_get_array', true);
Application::SetConfig('global_xss_filtering', true);
Application::SetConfig('csrf_protection', true);
Application::SetConfig('charset', true);
Application::SetConfig('sess_encrypt_cookie', true);

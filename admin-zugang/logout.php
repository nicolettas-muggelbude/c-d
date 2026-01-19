<?php
/**
 * Admin-Logout
 */

require_once __DIR__ . '/../core/config.php';
start_session_safe();

// Session beenden
session_destroy();

// Redirect zur Login-Seite
set_flash('success', 'Sie wurden erfolgreich abgemeldet.');
redirect(BASE_URL . '/admin/login.php');

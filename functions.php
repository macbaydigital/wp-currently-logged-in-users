<?php
// Spalte zur Benutzerliste hinzufügen
function add_logged_in_column($columns) {
    $columns['logged_in'] = 'Angemeldet';
    return $columns;
}
add_filter('manage_users_columns', 'add_logged_in_column');

// Spalte mit Status füllen
function show_logged_in_column($value, $column_name, $user_id) {
    if ('logged_in' === $column_name) {
        $is_logged_in = false;
        $session_tokens = get_user_meta($user_id, 'session_tokens', true);
        if ($session_tokens && is_array($session_tokens)) {
            foreach ($session_tokens as $token => $data) {
                if (is_array($data) && isset($data['expiration']) && $data['expiration'] > time()) {
                    $is_logged_in = true;
                    break;
                }
            }
        }
        return $is_logged_in ? '<span style="color: green;">&#9679;</span>' : '<span style="color: red;">&#9679;</span>';
    }
    return $value;
}
add_filter('manage_users_custom_column', 'show_logged_in_column', 10, 3);

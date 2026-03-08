<?php
/*
Plugin Name: API Edit & Delete
Plugin URI: https://github.com/min-hsao/yourls-api-edit
Description: Adds update and delete actions to the YOURLS API. Enables full CRUD support for external applications.
Version: 1.2
Author: Min-Hsao Chen
Author URI: https://github.com/min-hsao
*/

// Register our actions into the api_actions array
yourls_add_filter('api_actions', 'api_edit_register_actions');

function api_edit_register_actions($api_actions) {
    $api_actions['update'] = 'api_edit_update';
    $api_actions['delete'] = 'api_edit_delete';
    return $api_actions;
}

function api_edit_update() {
    $shorturl = isset($_REQUEST['shorturl']) ? yourls_sanitize_keyword($_REQUEST['shorturl']) : '';
    $url      = isset($_REQUEST['url']) ? yourls_sanitize_url($_REQUEST['url']) : '';
    $title    = isset($_REQUEST['title']) ? yourls_sanitize_title($_REQUEST['title']) : '';

    if (empty($shorturl) || empty($url)) {
        return array(
            'statusCode' => 400,
            'message'    => 'Missing shorturl or url parameter',
        );
    }

    if (!yourls_keyword_is_taken($shorturl)) {
        return array(
            'statusCode' => 404,
            'message'    => "Short URL '$shorturl' not found",
        );
    }

    // If no title provided, fetch it from the new URL
    if (empty($title)) {
        $title = yourls_get_remote_title($url);
    }

    $table = YOURLS_DB_TABLE_URL;
    $binds = array('url' => $url, 'title' => $title, 'keyword' => $shorturl);
    $result = yourls_get_db()->fetchAffected(
        "UPDATE `$table` SET `url` = :url, `title` = :title WHERE `keyword` = :keyword",
        $binds
    );

    if ($result !== false) {
        $base = YOURLS_SITE;
        return array(
            'statusCode' => 200,
            'message'    => 'URL updated successfully',
            'shorturl'   => "$base/$shorturl",
        );
    } else {
        return array(
            'statusCode' => 500,
            'message'    => 'Database update failed',
        );
    }
}

function api_edit_delete() {
    $shorturl = isset($_REQUEST['shorturl']) ? yourls_sanitize_keyword($_REQUEST['shorturl']) : '';

    if (empty($shorturl)) {
        return array(
            'statusCode' => 400,
            'message'    => 'Missing shorturl parameter',
        );
    }

    if (!yourls_keyword_is_taken($shorturl)) {
        return array(
            'statusCode' => 404,
            'message'    => "Short URL '$shorturl' not found",
        );
    }

    yourls_delete_link_by_keyword($shorturl);

    return array(
        'statusCode' => 200,
        'message'    => "Short URL '$shorturl' deleted",
    );
}

<?php

function xp_themes_update($res, $action) {
    // make sure we're triggering the filter on theme updates only
    if($action !== 'update_themes')
        return $res;

    if($res === false)
        return $res;
    
    // make sure we're checking for our theme
    if(!array_key_exists('xp-theme', $res->checked))
        return $res;

    // retrieve the latest release from github
    $latest_release_response = wp_remote_get('https://api.github.com/repos/xparty-ent/wordpress-theme/releases/latest', [
        'headers' => [
        ]
    ]);

    // make sure the request went thru correctly
    if(is_wp_error($latest_release_response))
        return $res;

    // decode the json response from github
    $latest_release = json_decode($latest_release_response['body']); 

    // if there's a message then the api errored, just return and try again later
    if(isset($latest_release->message))
        return $res;

    // filter the assets searching for the version.json file
    $release_version_asset = current(
        array_filter($latest_release->assets, function($asset) {
            return $asset->name === 'version.json';
        })
    );

    // if the version.json file could not be found the release is invalid, act if no update is present
    if(!$release_version_asset)
        return $res;

    // retrieve the version.json asset content
    $version_response = wp_remote_get($release_version_asset->url, [
        'headers' => [
            'Accept' => 'application/octet-stream'
        ]
    ]);

    // make sure the request went thru correctly
    if(is_wp_error($version_response))
        return $res;

    // decode the version.json content
    $version = json_decode($version_response['body'], true);

    // set the version.json content as the response of the transient
    $res->response[$version['slug']] = $version;
    
    // return the transient
    return $res;
}

add_filter('site_transient_update_themes', 'xp_themes_update', 20, 3);
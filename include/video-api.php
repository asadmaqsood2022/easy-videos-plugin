<?php

add_action('wp_ajax_nopriv_inert_video', 'inert_video');
add_action('wp_ajax_inert_video', 'inert_video');

function inert_video()
{
    $API_key    = get_option('api_key');
    $channelID  = get_option('channel_id');
    $maxResults = 10;
    $videoList = json_decode(file_get_contents('https://www.googleapis.com/youtube/v3/search?order=date&part=snippet&channelId=' . $channelID . '&maxResults=' . $maxResults . '&key=' . $API_key . ''));
    //echo '<pre>', print_r($videoList), '</pre>';

    $vides_cat = json_decode(file_get_contents('https://www.googleapis.com/youtube/v3/videoCategories?part=snippet&regionCode=US&key=' . $API_key . ''));
    $taxonomyName = 'vcategory';
    foreach ($vides_cat->items as $item) {

        $cat_id =  wp_insert_term($item->snippet->title, $taxonomyName);

        if (is_wp_error($cat_id)) {
            // echo $cat_id->get_error_message();
        } else {
            $term_id = $cat_id['term_id'];
            update_term_meta($term_id, 'vedioCat_id', $item->id, true);
        }
    }

    foreach ($videoList->items as $item) {

        if (isset($item->id->videoId)) {
            // create post object
            global $user_ID;
            $catID = json_decode(file_get_contents('https://www.googleapis.com/youtube/v3/videos?part=snippet&id=' . $item->id->videoId . '&key=' . $API_key . ''));
            // echo '<pre>';
            // print_r($catID->items[0]->snippet->categoryId);
            // echo '</pre>';

            $categoryId = $catID->items[0]->snippet->categoryId;

            $content = "<div class='youtube-video'><iframe width='500' height='300' src='https://www.youtube.com/embed/" . $item->id->videoId . "' frameborder='0' allowfullscreen></iframe></div>";
            $videos = array(
                'post_title'  => __($item->snippet->title),
                'post_content' =>  $content,
                'post_status' => 'publish',
                'post_author' => $user_ID,
                'post_type'   => 'videos',
            );

            // insert the post into the database
            global $wpdb;
            $return = $wpdb->get_row("SELECT ID FROM {$wpdb->prefix}posts WHERE post_title = '" . $item->snippet->title . "' && post_status = 'publish' && post_type = 'videos' ", 'ARRAY_N');
            if (empty($return)) {
                $id =  wp_insert_post($videos);
                update_post_meta($id, 'video_id', $item->id->videoId);

                $catId = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}termmeta WHERE meta_value = '" . $categoryId  . "'", 'ARRAY_N');

                $taxonomy = 'vcategory';
                wp_set_object_terms($id, intval($catId[1]), $taxonomy);
            }
        }
    }
    die;
}


// API Setting

function setting_sub_menu()
{
    add_submenu_page('edit.php?post_type=videos', 'Videos Settings', 'Videos Settings', 'manage_options', 'setting-submenu-page', 'setting_submenu_page_callback');
}
add_action('admin_menu', 'setting_sub_menu');

function setting_submenu_page_callback()
{
?>
    <form method="POST" action="options.php">
        <?php
        settings_fields('setting-submenu-page');
        do_settings_sections('setting-submenu-page');
        submit_button();
        ?>
    </form>
<?php
}


add_action('admin_init', 'my_settings_init');

function my_settings_init()
{

    add_settings_section(
        'video_setting',
        __('Videos settings', 'my-textdomain'),
        '',
        'setting-submenu-page'
    );

    add_settings_field(
        'api_key',
        __('API Key', 'my-textdomain'),
        'api_key_markup',
        'setting-submenu-page',
        'video_setting'
    );

    register_setting('setting-submenu-page', 'api_key');

    add_settings_field(
        'channel_id',
        __('Channel ID', 'my-textdomain'),
        'channel_id_markup',
        'setting-submenu-page',
        'video_setting'
    );

    register_setting('setting-submenu-page', 'channel_id');
}

function api_key_markup()
{
?>
    <input type="text" id="api_key" name="api_key" value="<?php echo get_option('api_key'); ?>">
<?php
}

function channel_id_markup()
{
?>
    <input type="text" id="channel_id" name="channel_id" value="<?php echo get_option('channel_id'); ?>">
<?php
}

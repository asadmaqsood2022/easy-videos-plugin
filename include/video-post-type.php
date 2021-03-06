<?php

// Register Custom Post Type
function videos_function()
{

    $labels = array(
        'name'                  => _x('videos', 'Post Type General Name', 'text_domain'),
        'singular_name'         => _x('videos', 'Post Type Singular Name', 'text_domain'),
        'menu_name'             => __('Videos', 'text_domain'),
        'name_admin_bar'        => __('videos', 'text_domain'),
        'archives'              => __('videos Archives', 'text_domain'),
        'attributes'            => __('videos Attributes', 'text_domain'),
        'parent_item_colon'     => __('Parent videos:', 'text_domain'),
        'all_items'             => __('All videos', 'text_domain'),
        'add_new_item'          => __('Add New video', 'text_domain'),
        'add_new'               => __('Add video', 'text_domain'),
        'new_item'              => __('New video', 'text_domain'),
        'edit_item'             => __('Edit video', 'text_domain'),
        'update_item'           => __('Update video', 'text_domain'),
        'view_item'             => __('View video', 'text_domain'),
        'view_items'            => __('View videos', 'text_domain'),
        'search_items'          => __('Search video', 'text_domain'),
        'not_found'             => __('Not found', 'text_domain'),
        'not_found_in_trash'    => __('Not found in Trash', 'text_domain'),
        'featured_image'        => __('Featured Image', 'text_domain'),
        'set_featured_image'    => __('Set featured image', 'text_domain'),
        'remove_featured_image' => __('Remove featured image', 'text_domain'),
        'use_featured_image'    => __('Use as featured image', 'text_domain'),
        'insert_into_item'      => __('Insert into item', 'text_domain'),
        'uploaded_to_this_item' => __('Uploaded to this item', 'text_domain'),
        'items_list'            => __('videos list', 'text_domain'),
        'items_list_navigation' => __('videos list navigation', 'text_domain'),
        'filter_items_list'     => __('Filter video list', 'text_domain'),
    );
    $args = array(
        'label'                 => __('videos', 'text_domain'),
        'description'           => __('videos', 'text_domain'),
        'labels'                => $labels,
        'supports' => array('title', 'editor', 'thumbnail'),
        'taxonomies'            => array('department'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
    );
    register_post_type('videos', $args);

    $labels = array(
        'name' => _x('Video Category', 'taxonomy general name'),
        'singular_name' => _x('Video Category', 'taxonomy singular name'),
        'search_items' =>  __('Search Category'),
        'all_items' => __('All Category'),
        'parent_item' => __('Parent Category'),
        'parent_item_colon' => __('Parent Category:'),
        'edit_item' => __('Edit Category'),
        'update_item' => __('Update Category'),
        'add_new_item' => __('Add New Category'),
        'new_item_name' => __('New Category Name'),
        'menu_name' => __('Video Categories'),
    );

    // Now register the taxonomy
    register_taxonomy('vcategory', array('videos'), array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'vcategory'),
    ));
}
add_action('init', 'videos_function', 0);


add_filter('views_edit-videos', 'so_13813805_add_button_to_views');
function so_13813805_add_button_to_views($views)
{
    $views['import-button'] = '<button id="import-video" class="page-title-action" type="button">Import Videos</button><img class="load-check" style="opacity:0; width: 22px;" src="' . WP_PLUGIN_URL . '/easy-videos/img/load.gif' . '">';
    return $views;
}

<?php
function pocketFix($post_id, $post_type) {

    if ($post_type == 'pockets'){
        $single_pocket_layout = get_post_meta( $post_id, 'single_pocket_layout', true);
        if ( empty( $single_pocket_layout ) ){
            update_post_meta($post_id, 'single_pocket_layout', 'pocket-layout-wide');
        }
    }
}

add_action('mp_post_meta', 'pocketFix', 10, 2);

function headwayLayoutFix($post_id, $tmp_post_id){
        $layout = get_option('headway_layout_options_' . $post_id);
        if ($layout){
            update_option('headway_layout_options_' . $tmp_post_id, $layout);
        } else {
            delete_option('headway_layout_options_' . $tmp_post_id);
        }
}

add_action('mp_theme_fix', 'headwayLayoutFix', 10, 2);
<?php
if(!current_user_can('administrator'))
define('TRIBE_DISABLE_TOOLBAR_ITEMS', true);
define("PARENT_THEME_PATH",get_template_directory());
define("PARENT_THEME_PATH_URI",get_template_directory_uri());

define("CHILD_THEME_PATH_URI",get_stylesheet_directory_uri());
define("CHILD_THEME_PATH",get_stylesheet_directory());
define("CHILD_THEME_MAIN_STYLE",get_stylesheet_uri());
define("CHILD_THEME_UPLOAD_URI",wp_upload_dir()['baseurl']);

include('src/terekhin.php');
include('inc/config.php');
include('src/framework/TD_Framework.php');
include('src/short_codes/short_codes.php');
include('src/td_news_short_codes/td_news.php');

include('inc/social_config.php');
include('inc/rewrite_rules.php');
include('inc/news_config.php');
include('inc/pages_config.php');

add_action('wp_enqueue_scripts', 'main_style_setup',25);
function main_style_setup()
{
    wp_register_style( 'td_jquery-ui-css', CHILD_THEME_PATH_URI.'/content/css/jquery-ui.min.css');
    wp_enqueue_style( 'td_jquery-ui-css' );
    wp_register_style( 'td_custom-css', CHILD_THEME_PATH_URI.'/content/css/custom.css');
    wp_enqueue_style( 'td_custom-css' );
    wp_register_script('td_seafarers-js',CHILD_THEME_PATH_URI.'/content/js/seafarers.js');
    wp_enqueue_script('td_seafarers-js');

}

    function bridge_qode_get_button_v2_html($params)
    {

        if (preg_match('/Read More/i', $params['text'], $m) == 1) {
            $params['text'] = preg_replace('/Read more/i', ' Читать далее', $params['text']);
        }

        $button_html = qode_execute_shortcode('qode_button_v2', $params);
        $button_html = str_replace("\n", '', $button_html);
        return $button_html;

    }

function add_opengraph_doctype( $output ) {
    return $output . ' xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml"';
}
add_filter('language_attributes', 'add_opengraph_doctype');

function insert_fb_in_head() {
    global $post;
    if ( !is_singular())
        return;
    echo '<meta property="og:title" content="' . get_the_title() . '"/>';
    echo '<meta property="og:type" content="article"/>';
    echo '<meta property="og:url" content="' . get_permalink() . '"/>';
    echo '<meta property="og:site_name" content="Seafarers Journal-Marine News"/>';
    if(has_post_thumbnail( $post->ID )) {
        $thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' );
        echo '<meta property="og:image" content="' . esc_attr( $thumbnail_src[0] ) . '"/>';
        echo '<meta property="og:image:width" content="960" />';
        echo '<meta property="og:image:height" content="480" />';
    }
    echo "";
}
add_action( 'wp_head', 'insert_fb_in_head', 5 );

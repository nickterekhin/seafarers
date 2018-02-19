<?php


namespace TerekhinDevelopment\short_codes\helpers;


class TD_Short_Codes_AutoComplete_Tools
{
    private static $instance;
    private $base;

    /**
     * TD_Short_Codes_Tools constructor.
     * @param $base
     */
    public function __construct($base)
    {
        $this->base = $base;
        add_filter( 'vc_autocomplete_'.$this->base.'_tag_callback', array( $this, 'tag_autocomplete_suggester', ), 10, 1 );

        //Tag render
        add_filter( 'vc_autocomplete_'.$this->base.'_tag_render', array( $this, 'tag_autocomplete_render', ), 10, 1 );

        //Category filter
        add_filter( 'vc_autocomplete_'.$this->base.'_category_name_callback', array( $this, 'category_autocomplete_suggester', ), 10, 1 ); // Get suggestion(find). Must return an array

        //Category render
        add_filter( 'vc_autocomplete_'.$this->base.'_category_name_render', array( $this, 'category_autocomplete_render', ), 10, 1 );

        //Author filter
        add_filter( 'vc_autocomplete_'.$this->base.'_author_id_callback', array( &$this, 'author_autocomplete_suggester', ), 10, 1 );

        //Author render
        add_filter( 'vc_autocomplete_'.$this->base.'_author_id_render', array( &$this, 'author_autocomplete_render', ), 10, 1 );

        add_filter( 'vc_autocomplete_'.$this->base.'_reaction_callback', array( &$this, 'reaction_autocomplete_suggester', ), 10, 1 );

        //Reaction render
        add_filter( 'vc_autocomplete_'.$this->base.'_reaction_render', array( &$this, 'reaction_autocomplete_render', ), 10, 1 );

        //Post in ID filter
        add_filter( 'vc_autocomplete_'.$this->base.'_post_in_callback', array( &$this, 'postId_autocomplete_suggester', ), 10, 1 );

        //Post in ID render
        add_filter( 'vc_autocomplete_'.$this->base.'_post_in_render', array( &$this, 'postId_autocomplete_render', ), 10, 1 );

        //Post not in ID filter
        add_filter( 'vc_autocomplete_'.$this->base.'_post_not_in_callback', array( &$this, 'postId_autocomplete_suggester', ), 10, 1 );

        //Post not in ID render
        add_filter( 'vc_autocomplete_'.$this->base.'_post_not_in_render', array( &$this, 'postId_autocomplete_render', ), 10, 1 );
    }

    public static function getInstance($base)
    {
        if(!self::$instance)
        {
            self::$instance = new self($base);
        }
        return self::$instance;
    }

    public function tag_autocomplete_suggester( $query ) {
        global $wpdb;
        $post_meta_infos = $wpdb->get_results( $wpdb->prepare( "SELECT a.slug AS slug, a.name AS tag_title
                    FROM {$wpdb->terms} AS a
                    LEFT JOIN ( SELECT term_id, taxonomy  FROM {$wpdb->term_taxonomy} ) AS b ON b.term_id = a.term_id
                    WHERE b.taxonomy = 'post_tag' AND a.name LIKE '%%%s%%'", stripslashes( $query ) ), ARRAY_A );

        $results = array();
        if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
            foreach ( $post_meta_infos as $value ) {
                $data          = array();
                $data['value'] = $value['slug'];
                $data['label'] = ( ( strlen( $value['tag_title'] ) > 0 ) ? esc_html__( 'Tag', 'qode-news' ) . ': ' . $value['tag_title'] : '' );
                $results[]     = $data;
            }
        }

        return $results;
    }
    public function tag_autocomplete_render( $query ) {
        $query = trim( $query['value'] ); // get value from requested
        if ( ! empty( $query ) ) {

            $tag = get_term_by( 'slug', $query, 'post_tag' );
            if ( is_object( $tag ) ) {

                $tag_slug = $tag->slug;
                $tag_title = $tag->name;

                $tag_title_display = '';
                if ( ! empty( $tag_title ) ) {
                    $tag_title_display = esc_html__( 'Tag', 'qode-news' ) . ': ' . $tag_title;
                }

                $data          = array();
                $data['value'] = $tag_slug;
                $data['label'] = $tag_title_display;

                return ! empty( $data ) ? $data : false;
            }

            return false;
        }

        return false;
    }

    public function category_autocomplete_suggester( $query ) {

        global $wpdb;

        $post_meta_infos       = $wpdb->get_results( $wpdb->prepare( "SELECT a.slug AS slug, a.name AS category_title
                    FROM {$wpdb->terms} AS a
                    LEFT JOIN ( SELECT term_id, taxonomy  FROM {$wpdb->term_taxonomy} ) AS b ON b.term_id = a.term_id
                    WHERE b.taxonomy = 'category' AND a.name LIKE '%%%s%%'", stripslashes( $query ) ), ARRAY_A );


        $results = array();
        if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
            foreach ( $post_meta_infos as $value ) {
                $data          = array();
                $data['value'] = $value['slug'];
                $data['label'] = ( ( strlen( $value['category_title'] ) > 0 ) ? esc_html__( 'Category', 'qode-news' ) . ': ' . $value['category_title'] : '' );
                $results[]     = $data;
            }
        }

        return $results;
    }

    /**
     * Find post category by slug
     * @since 4.4
     *
     * @param $query
     *
     * @return bool|array
     */
    public function category_autocomplete_render( $query ) {
        $query = trim( $query['value'] ); // get value from requested

        if ( ! empty( $query ) ) {

            $category = get_term_by( 'slug', $query, 'category' );
            if ( is_object( $category ) ) {

                $category_slug = $category->slug;
                $category_title = $category->name;

                $category_title_display = '';
                if ( ! empty( $category_title ) ) {
                    $category_title_display = esc_html__( 'Category', 'qode-news' ) . ': ' . $category_title;
                }

                $data          = array();
                $data['value'] = $category_slug;
                $data['label'] = $category_title_display;

                return ! empty( $data ) ? $data : false;
            }

            return false;
        }

        return false;
    }

    public function author_autocomplete_suggester( $query ) {
        global $wpdb;

        $post_meta_infos = $wpdb->get_results( $wpdb->prepare( "SELECT a.id AS ID, a.user_nicename as user_nicename
                    FROM {$wpdb->users} AS a WHERE a.user_nicename LIKE '%%%s%%'", stripslashes( $query ) ), ARRAY_A );

        $results = array();
        if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
            foreach ( $post_meta_infos as $value ) {
                $data          = array();
                $data['value'] = $value['ID'];
                $data['label'] = ( ( strlen( $value['user_nicename'] ) > 0 ) ? esc_html__( 'Author', 'qode-news' ) . ': ' . $value['user_nicename'] : '' );
                $results[]     = $data;
            }
        }

        return $results;
    }

    /**
     * Find posts author by slug
     * @since 4.4
     *
     * @param $query
     *
     * @return bool|array
     */
    public function author_autocomplete_render( $query ) {
        $query = trim( $query['value'] ); // get value from requested
        if ( ! empty( $query ) ) {

            $author = get_user_by( 'ID', $query, 'user_nicename' );
            if ( is_object( $author ) ) {

                $author_id = $author->id;
                $author_user_nicename = $author->user_nicename;

                $author_display = '';
                if ( ! empty( $author_user_nicename ) ) {
                    $author_display = esc_html__( 'Author', 'qode-news' ) . ': ' . $author_user_nicename;
                }

                $data          = array();
                $data['value'] = $author_id;
                $data['label'] = $author_display;

                return ! empty( $data ) ? $data : false;
            }

            return false;
        }

        return false;
    }

    public function reaction_autocomplete_suggester( $query ) {
        global $wpdb;
        $post_meta_infos = $wpdb->get_results( $wpdb->prepare( "SELECT a.slug AS slug, a.name AS reaction_title
                    FROM {$wpdb->terms} AS a
                    LEFT JOIN ( SELECT term_id, taxonomy  FROM {$wpdb->term_taxonomy} ) AS b ON b.term_id = a.term_id
                    WHERE b.taxonomy = 'news-reaction' AND a.name LIKE '%%%s%%'", stripslashes( $query ) ), ARRAY_A );

        $results = array();
        if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
            foreach ( $post_meta_infos as $value ) {
                $data          = array();
                $data['value'] = $value['slug'];
                $data['label'] = ( ( strlen( $value['reaction_title'] ) > 0 ) ? esc_html__( 'Reaction', 'qode-news' ) . ': ' . $value['reaction_title'] : '' );
                $results[]     = $data;
            }
        }

        return $results;
    }

    /**
     * Find post tag by slug
     * @since 4.4
     *
     * @param $query
     *
     * @return bool|array
     */
    public function reaction_autocomplete_render( $query ) {
        $query = trim( $query['value'] ); // get value from requested
        if ( ! empty( $query ) ) {

            $reaction_term = get_term_by( 'slug', $query, 'news-reaction' );
            if ( is_object( $reaction_term ) ) {

                $reaction_slug = $reaction_term->slug;
                $reaction_title = $reaction_term->name;

                $reaction_title_display = '';
                if ( ! empty( $reaction_title ) ) {
                    $reaction_title_display = esc_html__( 'Reaction', 'qode-news' ) . ': ' . $reaction_title;
                }

                $data          = array();
                $data['value'] = $reaction_slug;
                $data['label'] = $reaction_title_display;

                return ! empty( $data ) ? $data : false;
            }

            return false;
        }

        return false;
    }

}
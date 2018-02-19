<?php


namespace TerekhinDevelopment\tools;


use WP_Query;

class WP_Union_Query extends WP_Query
{
    protected $args    = array();
    protected $sub_sql = array();
    protected $sql     = '';

    public function __construct( $args = array() )
    {
        $defaults = array(
            'sublimit'       => 1000,
            'posts_per_page' => 10,
            'paged'          => 1,
            'args'           => array(),
        );

        $this->args = wp_parse_args( $args, $defaults );

        add_filter( 'posts_request',  array( $this, 'posts_request' ), PHP_INT_MAX  );

        parent::__construct( array( 'post_type' => 'post' ) );
    }

    public function posts_request( $request )
    {
        remove_filter( current_filter(), array( $this, __FUNCTION__ ), PHP_INT_MAX  );

        // Collect the generated SQL for each sub-query:
        foreach( (array) $this->args['args'] as $a )
        {
            $q = new WP_Query_Empty( $a, $this->args['sublimit'] );
            $this->sub_sql[] = $q->get_sql();
            unset( $q );
        }

        // Combine all the sub-queries into a single SQL query.
        // We must have at least two subqueries:
        if ( count( $this->sub_sql ) > 1 )
        {
            $s = '(' . join( ') UNION (', $this->sub_sql ) . ' ) ';

            $request = sprintf( "SELECT SQL_CALC_FOUND_ROWS * FROM ( $s ) as combined LIMIT %s,%s",
                $this->args['posts_per_page'] * ( $this->args['paged']-1 ),
                $this->args['posts_per_page']
            );
        }
        return $request;
    }
}
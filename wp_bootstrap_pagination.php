<?php
/**
 * Class Name: wp_boostrap_pagination
 * GitHub URI: https://github.com/sigami/wp_boostrap_pagination
 * Description: Cover all types of pagination within wordpress link pages, numeric archives and posts
 * Version: 1.0
 * Author: Miguel Sirvent
 */
class  wp_boostrap_pagination {
	private static $instance = null;

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	private function wp_boostrap_pagination() {
		/** wp page links */
		add_filter( 'wp_link_pages_args', __CLASS__ . '::' . 'wp_link_pages_args' );
		add_filter( 'wp_link_pages_link', __CLASS__ . '::' . 'wp_link_pages_link', 10, 2 );
		add_filter( 'wp_link_pages', __CLASS__ . '::' . 'wp_link_pages' );
	}

	/**
	 * @param array $args
	 *
	 * @return mixed
	 */
	static function wp_link_pages_args( $args ) {
		$args['before'] = '<nav class="text-center"><ul class="pagination"><li class="disabled"><a href="#">' . __( 'Pages:', 'text_domain' ) . '</a></li>';
		$args['after']  = '</ul></nav>';

		return $args;
	}

	static function wp_link_pages_link( $link, $i ) {
		if ( $i == false || $i == null || $i == 0 ) {
			$i = 1;
		}
		$page   = get_query_var( 'page', '1' );
		$return = ( $page == $i ) ? '<li class="active"><a href="#">' : '<li>';
		$return .= ( $page == $i ) ? "$link</a></li>" : "$link</li>";

		return $return;
	}

	static function wp_link_pages( $output ) {
		return str_replace( '<li>1</li>', '<li class="active"><a href="#">1</a></li>', $output );
	}

	static function posts( $args = array() ) {
		$args = wp_parse_args( $args, array(
			'prev_text'          => '<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>%title',
			'next_text'          => '%title<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>',
			'screen_reader_text' => __( 'Post navigation', 'text_domain' ),
		) );

		$navigation = '';
		$previous   = get_previous_post_link( '<li class="nav-previous previous">%link</li>', $args['prev_text'] );
		$next       = get_next_post_link( '<li class="nav-next next">%link</li>', $args['next_text'] );
		$links      = $previous . $next;
		$class      = 'posts-navigation';
		if ( $previous || $next ) {
			$template   = '
	<nav class="navigation %1$s" role="navigation">
		<h2 class="screen-reader-text">%2$s</h2>
		<ul class="nav-links pager">%3$s</ul>
	</nav>';
			$navigation = sprintf( $template, sanitize_html_class( $class ), esc_html( $args['screen_reader_text'] ), $links );
		}

		echo $navigation;
	}

	static function numeric( $args = array()) {

		$args = wp_parse_args( $args, array(
			'prev_text'        => '<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>' . __( 'Previous', 'text_domain' ),
			'next_text'        => __( 'Next', 'text_domain' ) . '<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>',
			'first_page_text'  => '&laquo',
			'first_page_title' => __( 'First', 'text_domain' ),
			'last_page_text'   => '&raquo;',
			'last_page_title'  => __( 'Last', 'text_domain' ),
			'pages_to_show'    => 5,
			'before'           => '',
			'after'            => '',
			'query'            => null,
		) );
		if ( $args['query'] instanceof WP_Query ) {
			$wp_query =  $args['query'];
		} else {
			global $wp_query;
		}
		$posts_per_page = intval( $wp_query->get( 'posts_per_page' ) );
		$paged          = intval( $wp_query->get( 'paged' ) );
		$numposts       = $wp_query->found_posts;
		$max_page       = $wp_query->max_num_pages;
		$pages_to_show  = $args['pages_to_show'];
		if ( $numposts <= $posts_per_page ) {
			return;
		}
		if ( empty( $paged ) || $paged == 0 ) {
			$paged = 1;
		}
		$pages_to_show_minus_1 = $pages_to_show - 1;
		$half_page_start       = floor( $pages_to_show_minus_1 / 2 );
		$half_page_end         = ceil( $pages_to_show_minus_1 / 2 );
		$start_page            = $paged - $half_page_start;
		if ( $start_page <= 0 ) {
			$start_page = 1;
		}
		$end_page = $paged + $half_page_end;
		if ( ( $end_page - $start_page ) != $pages_to_show_minus_1 ) {
			$end_page = $start_page + $pages_to_show_minus_1;
		}
		if ( $end_page > $max_page ) {
			$start_page = $max_page - $pages_to_show_minus_1;
			$end_page   = $max_page;
		}
		if ( $start_page <= 0 ) {
			$start_page = 1;
		}

		echo $args['before'] . '<div class="text-center"><ul class="pagination">' . "";
		if ( $paged > 1 ) {
			$first_page_text = $args['first_page_text'];
			echo '<li class="prev"><a href="' . get_pagenum_link() . '" title="' . $args['first_page_title'] . '">' . $first_page_text . '</a></li>';
		}

		$prevposts = get_previous_posts_link( $args['prev_text'] );
		if ( $prevposts ) {
			echo '<li>' . $prevposts . '</li>';
		} else {
			echo '<li class="disabled"><a href="#">' . $args['prev_text'] . '</a></li>';
		}

		for ( $i = $start_page; $i <= $end_page; $i ++ ) {
			if ( $i == $paged ) {
				echo '<li class="active"><a href="#">' . $i . '</a></li>';
			} else {
				echo '<li><a href="' . get_pagenum_link( $i ) . '">' . $i . '</a></li>';
			}
		}
		echo '<li class="">';
		next_posts_link( $args['next_text'] );
		echo '</li>';
		if ( $end_page < $max_page ) {
			$last_page_text = $args['last_page_text'];
			echo '<li class="next"><a href="' . get_pagenum_link( $max_page ) . '" title="' . $args['last_page_title'] . '">' . $last_page_text . '</a></li>';
		}
		echo '</ul></div>' . $args['after'];
	}

	static function comments_numeric( $args = array(), $query = null ) {
		//TODO
	}

	static function comments_simple($args = array()){
		$args = wp_parse_args( $args, array(
			'screen_reader_text'        => esc_html__( 'Comment navigation', 'text_domain' ),
			'next_text'        => esc_html__( 'Newer Comments %s', 'text_domain' ),
			'next_icon'=>'<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>',
			'previous_text'  => esc_html__( '%s Older Comments', 'text_domain' ),
			'previous_icon'=>'<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>',
		) );
		?>
		<h2 class="screen-reader-text"><?php echo $args['screen_reader_text'] ?></h2>
		<ul class="pager">
			<li class="previous">
				<?php previous_comments_link( sprintf( $args['previous_text'],$args['previous_icon'] ) ); ?>
			</li>
			<li class="next">
				<?php next_comments_link( sprintf( $args['next_text'],$args['next_icon'] ) ); ?>
			</li>
		</ul>
		<?php
	}

}

wp_boostrap_pagination::instance();

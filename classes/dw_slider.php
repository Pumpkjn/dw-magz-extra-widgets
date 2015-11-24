<?php
/**
 * @package DW Slider Widget
 */

add_action( 'widgets_init', 'dw_mz_carousel_widgets_init' );
function dw_mz_carousel_widgets_init() {
	register_widget( 'DW_mz_carousel_Widget' );
}

class DW_mz_carousel_Widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array( 'classname' => 'dw_mz_carousel_widget', 'description' => 'Show Your Posts as a Slider.' );
		parent::__construct( 'news-slider', 'DW MagZ: Carousel Widget', $widget_ops );
		add_action( 'save_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );
	}

	public function widget($args, $instance) {
		$cache = array();
		if ( ! $this->is_preview() ) {
			$cache = wp_cache_get( 'dw_mz_carousel_widget', 'widget' );
		}

		if ( ! is_array( $cache ) ) {
			$cache = array();
		}

		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo $cache[ $args['widget_id'] ];
			return;
		}

		ob_start();

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';
		$carousel_style = ( ! empty( $instance['carousel_style'] ) ) ? $instance['carousel_style'] : 'style-1';
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		$show_content = ( ! empty( $instance['show_content'] ) ) ? $instance['show_content'] : '';
		$cat_id = ( ! empty( $instance['cat_id'] ) ) ? absint( $instance['cat_id'] ) : 0;
		$tags = ( ! empty( $instance['tags'] ) ) ? $instance['tags'] : '';
		$show_category = isset( $instance['show_category'] ) ? $instance['show_category'] : false;
		$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;
		$show_author = isset( $instance['show_author'] ) ? $instance['show_author'] : false;
		$show_comment = isset( $instance['show_comment'] ) ? $instance['show_comment'] : false;
		$post_format = isset( $instance['post_format'] ) ? $instance['post_format'] : '';

		$query = array(
					'posts_per_page' => $number,
					'no_found_rows' => true,
					'post_status' => 'publish',
					'ignore_sticky_posts' => true,
				);
		if ( $post_format ) {
				$query['post_format'] = 'post-format-'.$post_format;
		}

		if ( '' != $tags && 0 != $cat_id ) {
			$query['tax_query'] = array(
					'relation' => 'AND',
					array(
						'taxonomy' => 'category',
						'field' => 'id',
						'terms' => array( $cat_id ),
						),
					array(
						'taxonomy' => 'post_tag',
						'field' => 'name',
						'terms' => explode( ',', $tags ),
						),
				);
		} else {
			if ( '' != $tags ) {
				$query['tag_slug__in'] = explode( ',', $tags );
			}

			if ( 0 != $cat_id ) {
				$query['cat'] = $cat_id;
			}
		}

		$r = new WP_Query( apply_filters( 'dw_mz_carousel_widget', $query ) );

		if ( $r->have_posts() ) :
?>
		<?php echo $args['before_widget']; ?>
		
		<?php include DWMZ_DIR . 'templates/slider_styles/'. $carousel_style .'.php' ; ?> 

		
		<?php echo $args['after_widget']; ?>
		<?php
		wp_reset_postdata();

		endif;

		if ( ! $this->is_preview() ) {
			$cache[ $args['widget_id'] ] = ob_get_flush();
			wp_cache_set( 'dw_mz_carousel_widget', $cache, 'widget' );
		} else {
			ob_end_flush();
		}
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['carousel_style'] = strip_tags( $new_instance['carousel_style'] );
		$instance['number'] = (int) $new_instance['number'];
		$instance['show_content'] = strip_tags( $new_instance['show_content'] );
		$instance['cat_id'] = (int) $new_instance['cat_id'];
		$instance['tags'] = strip_tags( $new_instance['tags'] );
		$instance['post_format'] = strip_tags( $new_instance['post_format'] );
		$instance['show_category'] = isset( $new_instance['show_category'] ) ? (bool) $new_instance['show_category'] : false;
		$instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
		$instance['show_author'] = isset( $new_instance['show_author'] ) ? (bool) $new_instance['show_author'] : false;
		$instance['show_comment'] = isset( $new_instance['show_comment'] ) ? (bool) $new_instance['show_comment'] : false;

		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset( $alloptions['dw_mz_carousel_widget'] ) ) {
			delete_option( 'dw_mz_carousel_widget' );
		}
		return $instance;
	}

	public function flush_widget_cache() {
		wp_cache_delete( 'dw_mz_carousel_widget', 'widget' );
	}

	public function form( $instance ) {


		$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$carousel_style = isset( $instance['carousel_style'] ) ? esc_attr( $instance['carousel_style'] ) : '';
		$number = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;

		$show_content = isset( $instance['show_content'] ) ? esc_attr( $instance['show_content'] ) : '';
		$cat_id = isset( $instance['cat_id'] ) ? esc_attr( $instance['cat_id'] ) : 0;
		$tags = isset( $instance['tags'] ) ? esc_attr( $instance['tags'] ) : '';
		$post_format = isset( $instance['post_format'] ) ? esc_attr( $instance['post_format'] ) : '';
		$show_category = isset( $instance['show_category'] ) ? (bool) $instance['show_category'] : false;
		$show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
		$show_author = isset( $instance['show_author'] ) ? (bool) $instance['show_author'] : false;
		$show_comment = isset( $instance['show_comment'] ) ? (bool) $instance['show_comment'] : false;
?>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'dw-carousel' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>
		

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'carousel_style' ) ); ?>"><?php _e( 'Choose carousel style', 'dw-carousel' ) ?></label>
		<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'carousel_style' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'carousel_style' ) ); ?>">
			<?php 
			$styles = $this->read_styles();
			for ( $i = 1; $i <= intval( $styles ); $i++) { 
				$style = 'style-'.$i;
				?>
				<option value="<?php echo $style; ?>" <?php selected( $carousel_style, $style ) ?>><?php _e( $style , 'dw-carousel' ); ?></option>
				<?php
			}
			?>
		</select></p>		

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php _e( 'Number of posts to show:', 'dw-carousel' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>" size="3" /></p>
		


		<p><label for="<?php echo esc_attr( $this->get_field_id( 'show_content' ) ); ?>"><?php _e( 'Display post content?', 'dw-carousel' ) ?></label>
		<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'show_content' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_content' ) ); ?>">
			<option value="" <?php selected( $show_content, '' ) ?>></option>
			<option value="excerpt" <?php selected( $show_content, 'excerpt' ) ?>><?php _e( 'Excerpt', 'dw-carousel' ); ?></option>
			<option value="content" <?php selected( $show_content, 'content' ) ?>><?php _e( 'Content', 'dw-carousel' ); ?></option>
		</select></p>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'cat_id' ) ); ?>"><?php _e( 'Category:', 'dw-carousel' ); ?></label>
		<?php wp_dropdown_categories( 'name='.$this->get_field_name( 'cat_id' ).'&class=widefat&show_option_all=All&hide_empty=0&hierarchical=1&depth=2&selected='.$cat_id ); ?></p>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'tags' ) ); ?>"><?php _e( 'Tags:', 'dw-carousel' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'tags' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'tags' ) ); ?>" placeholder="<?php _e( 'tag 1, tag 2, tag 3','dw-carousel' )?>" type="text" value="<?php echo esc_attr( $tags ); ?>" /></p>

		<p><input class="checkbox" type="checkbox" <?php checked( $show_category ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_category' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_category' ) ); ?>" />
		<label for="<?php echo esc_attr( $this->get_field_id( 'show_category' ) ); ?>"><?php _e( 'Display post categories?', 'dw-carousel' ); ?></label></p>

		<p><input class="checkbox" type="checkbox" <?php checked( $show_date ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_date' ) ); ?>" />
		<label for="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>"><?php _e( 'Display post date?', 'dw-carousel' ); ?></label></p>

		<p><input class="checkbox" type="checkbox" <?php checked( $show_author ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_author' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_author' ) ); ?>" />
		<label for="<?php echo esc_attr( $this->get_field_id( 'show_author' ) ); ?>"><?php _e( 'Display post author?', 'dw-carousel' ); ?></label></p>

		<p><input class="checkbox" type="checkbox" <?php checked( $show_comment ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_comment' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_comment' ) ); ?>" />
		<label for="<?php echo esc_attr( $this->get_field_id( 'show_comment' ) ); ?>"><?php _e( 'Display comment count?', 'dw-carousel' ); ?></label></p>
<?php
	}
	function read_styles() {
		$dir = DWMZ_DIR . "/templates/slider_styles";
		$count = 0;
		$files = scandir( $dir );
			for ( $i = 0; $i < count( $files ) ;$i++ ){
				if($files[ $i ] !='.' && $files[ $i ] !='..')
				{
					$count++;
				}
			}
		return $count;
	}
}

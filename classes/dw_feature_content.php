<?php

add_action( 'widgets_init', 'dw_mz_feature_content_widget_init' );
function dw_mz_feature_content_widget_init() {
	register_widget( 'DW_mz_feature_content' );
}

class DW_mz_feature_content extends WP_Widget
{
	
	public function __construct() {
		$widget_ops = array( 'classname' => 'dw-mz-feature-content-widget', 'description' => __( 'DW MagZ: Feature Content', 'dw-widget' ) );
		parent::__construct( 'dw-mz-feature-content-widget', __( 'DW MagZ: Feature Content', 'dw-widget' ), $widget_ops );
	}

	public function widget( $args, $instance ) {
		ob_start();

		$cat_id = ( ! empty( $instance['cat_id'] ) ) ? absint( $instance['cat_id'] ) : 0;
		$tags = ( ! empty( $instance['tags'] ) ) ? $instance['tags'] : '';
		$order = isset( $instance['order'] ) ? esc_attr( $instance['order'] ) : '';

		$query = array(
				'posts_per_page' => 6,
				'no_found_rows' => true,
				'post_status' => 'publish',
				'ignore_sticky_posts' => true,
			);

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

		if( isset( $instance['order'] ) ) {
			$query['orderby'] = $instance['order'];
			if($query['orderby'] == 'title') {
				$query['order'] = 'ASC';
			} else {
				$query['order'] = 'DESC';
			}
		}

		$metro_query = new WP_Query( apply_filters( 'DW_mz_feature_content', $query ) );
		if ( $metro_query->have_posts() ):
	?>
	<?php echo $args['before_widget']; ?>
	<div class="feature">
		<div id="metro-slide">
			<?php
				$i = 0;
				while ( $metro_query->have_posts() ) : $metro_query->the_post(); 

				$class = "gradient gradient-".$i;

				if ($i < 2) {
					$thumbnail_size = 500;
					$class .= " hentry-big";
				} else {
					$thumbnail_size = 250;
					$class .= " hentry-small";
				}

				if ($i == 2) {
					$class .= " clear-left";
				}
			?>
			<article id="post-<?php the_ID(); ?>" class="hentry-metro <?php echo $class ?>">
				<div class="entry-thumbnail gradient-tran-white" >
				<?php if(preg_match('/(?i)msie [1-8]/',$_SERVER['HTTP_USER_AGENT'])) : ?>
					<!--[if IE 8]>
					<div class="ie8-gradient-tran-white"></div>
					<![endif]-->
				<?php endif; ?>
				<?php if(has_post_thumbnail()) : ?>
					<?php the_post_thumbnail(); ?>
				<?php else : ?>
					<img alt="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'dw-argo' ), the_title_attribute( 'echo=0' ) ) ); ?>" src="http://placehold.it/<?php echo $thumbnail_size.'x'.$thumbnail_size; ?>" />
				<?php endif; ?>
				</div>
				<img class="placeholder" src="<?php echo DWMZ_URI ?>assets/img/placeholder.png" alt="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'dw-argo' ), the_title_attribute( 'echo=0' ) ) ); ?>">
				<h2 class="entry-title">
					<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'dw-argo' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
				</h2>
				<?php if(preg_match('/(?i)msie [1-8]/',$_SERVER['HTTP_USER_AGENT'])) : ?>
				<!--[if IE 8]>
				<div class="<?php echo $class ?>">
					<div class="inner"></div>
				</div>
				<! [endif]-->
				<?php endif; ?>
			</article>
			<?php $i++; ?>
			<?php endwhile;
			?>
		</div>
	</div>
	<?php echo $args['after_widget']; ?>
	<?php wp_reset_postdata(); ?>
	<?php ob_end_flush(); ?>
	<?php endif;

	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['cat_id'] = (int) $new_instance['cat_id'];
		$instance['tags'] = strip_tags( $new_instance['tags'] );
		$instance['order'] = strip_tags( $new_instance['order'] );

		return $instance;
	}

	public function form( $instance ) {
		$cat_id = isset( $instance['cat_id'] ) ? esc_attr( $instance['cat_id'] ) : 0;
		$tags = isset( $instance['tags'] ) ? esc_attr( $instance['tags'] ) : '';
		$order = isset( $instance['order'] ) ? esc_attr( $instance['order'] ) : '';

		?>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'cat_id' ) ); ?>"><?php _e( 'Category:', 'dw-feature-content' ); ?></label>
		<?php wp_dropdown_categories( 'name='.$this->get_field_name( 'cat_id' ).'&class=widefat&show_option_all=All&hide_empty=0&hierarchical=1&depth=2&selected='.$cat_id ); ?></p>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'tags' ) ); ?>"><?php _e( 'Tags:', 'dw-feature-content' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'tags' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'tags' ) ); ?>" placeholder="<?php _e( 'tag 1, tag 2, tag 3','dw-feature-content' )?>" type="text" value="<?php echo esc_attr( $tags ); ?>" /></p>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php _e( 'Display post content?', 'dw-feature-content' ) ?></label>
		<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>">
			<option value="" <?php selected( $order, '' ) ?>></option>
			<option value="title" <?php selected( $order, 'title' ) ?>><?php _e( 'Title', 'dw-feature-content' ); ?></option>
			<option value="date" <?php selected( $order, 'date' ) ?>><?php _e( 'Date', 'dw-feature-content' ); ?></option>
			<option value="comment_count" <?php selected( $order, 'comment_count' ) ?>><?php _e( 'Content', 'dw-feature-content' ); ?></option>
			<option value="rand" <?php selected( $order, 'rand' ) ?>><?php _e( 'Random', 'dw-feature-content' ); ?></option>
		</select></p>
		<?php
	}
}
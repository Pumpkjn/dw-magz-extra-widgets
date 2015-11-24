<?php
/**
 * @package DW Slider Widget
 * Style 2
*/
?>
<div class="<?php echo esc_attr( $carousel_style ); ?>">
			<?php if ( $title ) : ?>
				<?php echo $args['before_title']; ?>
				<?php if ( 0 != $cat_id ) : ?>
					<a href="<?php echo esc_url( get_category_link( $cat_id ) ); ?>"><?php echo wp_kses_post( $title ); ?></a>
				<?php else : ?>
					<?php echo wp_kses_post( $title ); ?>
				<?php endif; ?>
				<?php echo $args['after_title']; ?>
			<?php endif; ?>

			<div class="news-grid">
				<div id="carousel-<?php echo esc_attr( $args['widget_id'] ); ?>" class="carousel slide" data-ride="carousel"><div class="carousel-inner" role="listbox"><div class="item active">
				<div class="row">
				<?php $col = 3 ?>
				<?php $row_num = 0; $i = 1; ?>
				<?php $item_count = $r->post_count; ?>
				<?php while ( $r->have_posts() ) : $r->the_post(); ?>
					<div class="col-sm-<?php echo esc_attr( $col ); ?>">
						<article <?php post_class(); ?>>
							<?php if ( has_post_thumbnail() ) : ?>
								<div class="entry-thumbnail"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'dw-slider-style-2' ); ?></a></div>
							<?php endif; ?>
							<h3 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<div class="entry-meta">
								<?php if ( $show_date ) : ?>
									<span class="entry-date"><i class="fa fa-clock-o"></i> <?php echo get_the_date( __('F j, Y', 'dw-focus') ); ?></span>
								<?php endif; ?>
								<?php if ( $show_author ) : ?>
									<span class="entry-author"><i class="fa fa-user"></i> <?php the_author(); ?></span>
								<?php endif; ?>
								<?php if ( $show_comment && ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
									<span class="comments-link"><?php _e( '<i class="fa fa-comment"></i> ', 'dw-focus' ); ?><?php comments_popup_link( __( '0', 'dw-focus' ), __( '1', 'dw-focus' ), __( '%', 'dw-focus' ) ); ?></span>
								<?php endif; ?>
							</div>

							<?php
							if ( 'content' == $show_content ) :
								$more = 0;
							?>
								<div class="entry-content"><?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'dw-focus' ) ); ?></div>
							<?php elseif ( 'excerpt' == $show_content ) : ?>
								<div class="entry-summary"><?php the_excerpt(); ?></div>
							<?php endif; ?>
						</article>
					</div>
				<?php if ( ( 0 === $i % ( 4 ) ) && ( $i < $item_count ) ) : ?>
					</div>
				</div>
				<div class="item">
					<div class="row">
				<?php $row_num++; endif; ?>
				<?php $i++; ?>
				<?php endwhile; ?>
				</div>
				</div></div>
					<ol class="carousel-indicators">
						<?php for ( $j = 0; $j <= $row_num; $j++ ) { ?>
						<li data-target="#carousel-<?php echo esc_attr( $args['widget_id'] ); ?>" data-slide-to="<?php echo esc_attr( $j ); ?>"<?php if ( 0 === $j ) { echo 'class="active"'; } ?>></li>
						<?php } ?>
					</ol>
					<!-- Controls -->
					<a class="left carousel-control" href="#carousel-<?php echo esc_attr( $args['widget_id'] ); ?>" role="button" data-slide="prev">
						<span class="fa fa-chevron-left" aria-hidden="true"></span>
						<span class="sr-only">Previous</span>
					</a>
					<a class="right carousel-control" href="#carousel-<?php echo esc_attr( $args['widget_id'] ); ?>" role="button" data-slide="next">
						<span class="fa fa-chevron-right" aria-hidden="true"></span>
						<span class="sr-only">Next</span>
					</a>
				</div>
			</div>
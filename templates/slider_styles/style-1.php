<?php
/**
 * @package DW Slider Widget
 * Style 1
*/
?>
<div id="carousel-<?php echo esc_attr( $args['widget_id'] ); ?>" class="carousel slide <?php echo esc_attr( $carousel_style ); ?>" data-ride="carousel">
			<div class="carousel-inner" role="listbox">
				<?php $i = 0; while ( $r->have_posts() ) : $r->the_post(); ?>
				<div class="item<?php echo $i == 0 ? ' active' : ''; ?>">
					<article class="carousel-entry">
						<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'dw-slider-style-1' ); ?></a>
						<?php $categories_list = get_the_category_list( __( ', ', 'dw-slider-widget' ) );
						if ( $show_category && $categories_list ) {
							printf( '<span class="cat-links hidden-xs">' . __( '%1$s', 'dw-slider-widget' ) . '</span>', $categories_list );
						} ?>
						<div class="carousel-caption">
							<div class="entry-meta hidden-xs">
								<?php if ( $show_date ) : ?>
									<span class="entry-date"><i class="fa fa-clock-o"></i> <?php echo get_the_date( __('F j, Y', 'dw-slider-widget') ); ?></span>
								<?php endif; ?>
								<?php if ( $show_author ) : ?>
									<span class="entry-author"><i class="fa fa-user"></i> <?php the_author(); ?></span>
								<?php endif; ?>
								<?php if ( $show_comment && ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
									<span class="comments-link"><?php _e( '<i class="fa fa-comment"></i> ', 'dw-slider-widget' ); ?><?php comments_popup_link( __( '0', 'dw-slider-widget' ), __( '1', 'dw-slider-widget' ), __( '%', 'dw-slider-widget' ) ); ?></span>
								<?php endif; ?>
							</div>
							<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<?php if ( 'content' == $show_content ) :
								$more = 0;
							?>
								<div class="entry-content"><?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'dw-slider-widget' ) ); ?></div>
							<?php elseif ( 'excerpt' == $show_content ) : ?>
								<div class="entry-summary"><?php the_excerpt(); ?></div>
							<?php endif; ?>
						</div>
					</article>
				</div>
				<?php $i++; endwhile; ?>
			</div>
			<div class="carousel-navigation hidden-xs hidden-sm">
				<?php if ( $title ) { echo $args['before_title'] . wp_kses_post( $title ) . $args['after_title']; } ?>
				<ol class="carousel-title-indicators">
					<?php $k = 0; while ( $r->have_posts() ) : $r->the_post(); ?>
					<li data-target="#carousel-<?php echo esc_attr( $args['widget_id'] ); ?>" data-slide-to="<?php echo esc_attr( $k ); ?>"<?php echo $k == 0 ? ' class="active"' : ''; ?>><?php the_title(); ?></li>
					<?php $k++; endwhile; ?>
				</ol>
				<ol class="carousel-indicators">
					<?php for ( $j = 0; $j < $i; $j++ ) : ?>
					<li data-target="#carousel-<?php echo esc_attr( $args['widget_id'] ); ?>" data-slide-to="<?php echo esc_attr( $j ); ?>"<?php echo $j == 0 ? ' class="active"' : ''; ?>></li>
					<?php endfor; ?>
				</ol>
			</div>
		</div>
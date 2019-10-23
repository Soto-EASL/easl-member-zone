<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$panel_title = get_field( 'mz_memberzone_panel_title', 'option' );

if ( have_rows( 'mz_memberzone_panel_widgets', 'option' ) ) :
	?>
    <div class="mz-panel-wrap">
        <div class="container">
            <div class="mz-panel-inner">
				<?php if ( $panel_title ): ?>
                    <div class="mz-panel-title"><span><?php echo $panel_title; ?></span></div>
				<?php endif; ?>
                <a href="#" class="mz-panel-close"></a>
                <div class="mz-panel-buttons-wrap">
					<?php
					while ( have_rows( 'mz_memberzone_panel_widgets', 'option' ) ) :
						the_row();
						$widget_id        = absint( get_sub_field( 'widget' ) );
						$widget_post      = get_post( $widget_id );
						$icon             = '';
						$hover_state_icon = '';
						$link_title       = '';
						$link_new_tab     = '';
						$css_class        = 'mz-panel-button';
						if ( $widget_post ) {
							$icon             = get_field( 'icon', $widget_id );
							$hover_state_icon = get_field( 'hover_state_icon', $widget_id );
							$link_title       = get_field( 'link_title', $widget_id );
							$link_url         = get_field( 'link_url', $widget_id );
							$link_new_tab     = get_field( 'link_new_tab', $widget_id );
						}
						if ( $link_url ) {
							$link_url = esc_url( $link_url );
						}

						if ( $link_new_tab ) {
							$link_new_tab = ' target="_blank"';
						} else {
							$link_new_tab = '';
						}
						if ( $hover_state_icon ) {
							$css_class .= ' has-hover-icon';
						}
						if ( ! $icon ) {
							continue;
						}
						?>
                        <a class="<?php echo $css_class; ?>" href="<?php echo $link_url; ?>"<?php echo $link_new_tab; ?>>
                            <span class="mz-panel-button-icon">
                            <img class="mz-panel-button-icon-normal" src="<?php echo esc_url( $icon ); ?>" alt="">
                            <?php if ( $hover_state_icon ): ?>
                                <img class="mz-panel-button-icon-hover" src="<?php echo esc_url( $hover_state_icon ); ?>" alt="">
                            <?php endif; ?>
                            </span>
							<?php if ( $link_title ): ?>
                                <span class="mz-panel-button-title"><?php echo $link_title; ?></span>
							<?php endif; ?>
                        </a>
					<?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
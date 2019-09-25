<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}


/**
 * Shortcode attributes
 * @var $atts
 * @var $title
 * @var $el_class
 * @var $el_id
 * @var $css
 * Shortcode class
 * @var $this EASL_VC_MZ_Publications
 */
$el_class      = '';
$el_id         = '';
$css_animation = '';
$title         = '';
$atts          = vc_map_get_attributes( $this->getShortcode(), $atts );

extract( $atts );

$class_to_filter = 'wpb_easl_mz_publications wpb_content_element ' . $this->getCSSAnimation( $css_animation );
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class       = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );
$css_animation   = $this->getCSSAnimation( $css_animation );

$wrapper_attributes = array();
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
if ( ! empty( $css_class ) ) {
	$wrapper_attributes[] = 'class="' . esc_attr( $css_class ) . '"';
}
$latest_publications_data = $this->get_publications_data();
if ( count( $latest_publications_data ) > 0 ):
	?>
    <div <?php echo implode( ' ', $wrapper_attributes ); ?>>
		<?php if ( $title ): ?>
            <h2 class="mz-section-heading"><?php echo $title; ?></h2>
		<?php endif; ?>
        <div class="easl-mz-publicaitons-inner easl-row easl-row-col-3 clr">
			<?php foreach ( $latest_publications_data as $publication_data ): ?>
                <div class="easl-mz-publicaiton-item easl-col">
                    <div class="easl-col-inner">
                        <div class="easl-mz-publication-container">
                            <div class="easl-mz-publicaiton-item-title">
								<?php echo $publication_data['cat_title']; ?>
                            </div>
                            <div class="easl-mz-publicaiton-item-image" style="background-image: url(<?php echo $publication_data['image']; ?>);">
                                <a href="<?php echo $publication_data['link']; ?>"><img src="<?php echo $publication_data['image']; ?>" alt=""></a>
                            </div>
                            <div class="easl-mz-publicaiton-item-link">
                                <a href="<?php echo $publication_data['link']; ?>">View publication</a>
                            </div>
                        </div>
                    </div>
                </div>
			<?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
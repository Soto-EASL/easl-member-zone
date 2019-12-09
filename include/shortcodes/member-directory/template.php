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
 * @var $this EASL_VC_MZ_Member_Directory
 */
$el_class      = '';
$el_id         = '';
$css_animation = '';
$title         = '';
$atts          = vc_map_get_attributes( $this->getShortcode(), $atts );

extract( $atts );

$class_to_filter = 'wpb_easl_mz_directory wpb_content_element ' . $this->getCSSAnimation( $css_animation );
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

easl_mz_enqueue_select_assets();
?>
<div <?php echo implode( ' ', $wrapper_attributes ); ?>>
    <div class="easl-mz-directory-inner mz-md-loading">
		<?php if ( $title ): ?>
            <h2 class="mz-page-heading"><?php echo $title; ?></h2>
		<?php endif; ?>
        <div class="easl-mz-directory-filters">
            <div class="easl-ec-filter">
                <input type="hidden" id="mz-md-filter-letter" value="">
                <input type="hidden" id="mz-md-filter-page" value="">
                <div class="easl-row">
                    <div class="easl-col">
                        <div class="easl-col-inner">
                            <div class="ec-filter-search">
                                <input type="text" id="mz-md-filter-search" value="" placeholder="Search for a member"/>
                                <span id="mz-search-trigger" class="ecs-icon"><i class="ticon ticon-search" aria-hidden="true"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="easl-mz-filter-or">Or filter by...</div>
                <div class="easl-row">
                    <div class="easl-col easl-col-2">
                        <div class="easl-col-inner">
                            <div class="mzms-field-wrap">
                                <select class="easl-mz-select2" id="mz-md-filter-country" data-placeholder="Select a country" style="width: 100%;">
                                    <option value="">All Countries</option>
                                    <?php echo easl_mz_get_non_empty_countries_dropdown(); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="easl-col easl-col-2">
                        <div class="easl-col-inner">
                            <div class="mzms-field-wrap">
                                <select class="easl-mz-select2" id="mz-md-filter-spec" data-placeholder="Select a speciality" style="width: 100%;">
                                    <option value="">All Specialities</option>
	                                <?php echo easl_mz_get_crm_dropdown_items( 'specialities' ); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="easl-mz-letter-filter">
                    <a href="#a" data-value="a">A</a>
                    <a href="#b" data-value="b">B</a>
                    <a href="#c" data-value="c">C</a>
                    <a href="#d" data-value="d">D</a>
                    <a href="#e" data-value="e">E</a>
                    <a href="#f" data-value="f">F</a>
                    <a href="#g" data-value="g">G</a>
                    <a href="#h" data-value="h">H</a>
                    <a href="#i" data-value="i">I</a>
                    <a href="#j" data-value="j">J</a>
                    <a href="#k" data-value="k">K</a>
                    <a href="#l" data-value="l">L</a>
                    <a href="#m" data-value="m">M</a>
                    <a href="#n" data-value="n">N</a>
                    <a href="#o" data-value="o">O</a>
                    <a href="#p" data-value="p">P</a>
                    <a href="#q" data-value="q">Q</a>
                    <a href="#r" data-value="r">R</a>
                    <a href="#s" data-value="s">S</a>
                    <a href="#t" data-value="t">T</a>
                    <a href="#u" data-value="u">U</a>
                    <a href="#v" data-value="v">V</a>
                    <a href="#w" data-value="w">W</a>
                    <a href="#x" data-value="x">X</a>
                    <a href="#y" data-value="y">Y</a>
                    <a href="#z" data-value="z">Z</a>
                </div>
            </div>
            <div class="easl-mz-filter-clear-wrap">
                <a class="easl-mz-clear-filters" href="#clear-filters">X Clear Filters</a>
            </div>
        </div>
        <div class="easl-mz-members-direcoty-content">
            <div class="easl-mz-members-direcoty-content-inner">

            </div>
            <div class="easl-mz-loader">
                <img src="<?php echo get_stylesheet_directory_uri() ?>/images/easl-loader.gif" alt="loading...">
            </div>
        </div>
    </div>
</div>

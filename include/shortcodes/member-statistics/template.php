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
 * Shortcode class EASL_VC_MZ_Member_Statistics
 * @var $this EASL_VC_MZ_Member_Statistics
 */

$el_class      = '';
$el_id         = '';
$css_animation = '';
$title         = '';
$subtitle      = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$css_animation = $this->getCSSAnimation( $css_animation );

$class_to_filter = 'wpb_easl_mz_statistics wpb_content_element ' . $this->getCSSAnimation( $css_animation );
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
$flag_url_root = easl_mz_get_asset_url( '/images/flags/' );
$this->enqueue_map_scripts();
?>
<div <?php echo implode( ' ', $wrapper_attributes ); ?>>
    <div class="mz-statistics-inner">
        <div id="mz-stats-container">
			<?php if ( $title ): ?>
                <h2 class="mz-page-heading"><?php echo $title; ?></h2>
			<?php endif; ?>
			<?php if ( $subtitle ): ?>
                <h4 class="mz-subheading"><?php echo $subtitle; ?></h4>
			<?php endif; ?>
            <div class="mz-stat-filters">
                <div class="mz-country-filter">
                    <div class="easl-custom-select">
                        <span class="ec-cs-label">Select your country</span>
                        <select name="mzstat_country" id="mzstat_country">
                            <option value="">Select your country</option>
							<?php foreach ( easl_mz_get_list_countries() as $country_key => $country_name ): ?>
                                <option value="<?php echo $country_key; ?>"><?php echo $country_name; ?></option>
							<?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="mz-country-stats"><span>00,000 Members</span></div>
            </div>
            <div class="mz-wordlwide-stats">
                <h4 class="mz-subheading">Worldwide membership by type</h4>
                <div class="mz-wordlwide-stats-categories">
                    <div class="mz-stat-block">
                        <div class="mz-stat-block-inner">
                            <div>
                                <span>Regular</span>
                                <strong>20,000</strong>
                            </div>
                        </div>
                    </div>
                    <div class="mz-stat-block">
                        <div class="mz-stat-block-inner">
                            <div>
                                <span>Emeritus</span>
                                <strong>12,356</strong>
                            </div>
                        </div>
                    </div>
                    <div class="mz-stat-block">
                        <div class="mz-stat-block-inner">
                            <div>
                                <span>Trainees / YI</span>
                                <strong>3,278</strong>
                            </div>
                        </div>
                    </div>
                    <div class="mz-stat-block">
                        <div class="mz-stat-block-inner">
                            <div>
                                <span>Nurses</span>
                                <strong>456</strong>
                            </div>
                        </div>
                    </div>
                    <div class="mz-stat-block">
                        <div class="mz-stat-block-inner">
                            <div>
                                <span>Patients</span>
                                <strong>8,847</strong>
                            </div>
                        </div>
                    </div>
                    <div class="mz-stat-block">
                        <div class="mz-stat-block-inner">
                            <div>
                                <span>Corresponding Member</span>
                                <strong>1,200</strong>
                            </div>
                        </div>
                    </div>
                    <div class="mz-stat-block">
                        <div class="mz-stat-block-inner">
                            <div>
                                <span>Allied Health Professionals</span>
                                <strong>4,764</strong>
                            </div>
                        </div>
                    </div>
                </div>
                <h4 class="mz-subheading">Worldwide membership by type</h4>
                <div class="mz-worldwide-stats-speciality">
                    <div class="mz-stat-block easl-color-orrange">
                        <div class="mz-stat-block-inner">
                            <div>
                                <strong>30,000</strong>
                                <span>Cholestasis and autoimmune</span>
                            </div>
                        </div>
                    </div>
                    <div class="mz-stat-block easl-color-blue">
                        <div class="mz-stat-block-inner">
                            <div>
                                <strong>23,000</strong>
                                <span>General hepatology</span>
                            </div>
                        </div>
                    </div>
                    <div class="mz-stat-block easl-color-teal">
                        <div class="mz-stat-block-inner">
                            <div>
                                <strong>4,000</strong>
                                <span>Metabolism, alcohol and toxicity</span>
                            </div>
                        </div>
                    </div>
                    <div class="mz-stat-block easl-bg-color-gray">
                        <div class="mz-stat-block-inner">
                            <div>
                                <strong>12,467</strong>
                                <span>Cirrhosis and complication</span>
                            </div>
                        </div>
                    </div>
                    <div class="mz-stat-block easl-color-red">
                        <div class="mz-stat-block-inner">
                            <div>
                                <strong>12,030</strong>
                                <span>Liver tumors</span>
                            </div>
                        </div>
                    </div>
                    <div class="mz-stat-block easl-color-yellow">
                        <div class="mz-stat-block-inner">
                            <div>
                                <strong>343</strong>
                                <span>Viral hepatitis</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mz-stat-top-countries-wrap">
                <h4>Top member countries</h4>
                <div class="mz-stat-top-countries-map">
                    <div id="easl-mz-stat-map"></div>
                </div>
                <div class="mz-stat-top-countries easl-row easl-row-col-3">
                    <div class="easl-col">
                        <div class="easl-col-inner">
                            <div class="mz-stat-country">
                                <div class="mz-country-flag">
                                    <img src="<?php echo $flag_url_root; ?>US.png" alt="">
                                </div>
                                <div class="mz-country-name">USA <span>(14,567)</span></div>
                            </div>
                            <div class="mz-stat-country">
                                <div class="mz-country-flag">
                                    <img src="<?php echo $flag_url_root; ?>DE.png" alt="">
                                </div>
                                <div class="mz-country-name">Germany <span>(2,898)</span></div>
                            </div>
                            <div class="mz-stat-country">
                                <div class="mz-country-flag">
                                    <img src="<?php echo $flag_url_root; ?>UK.png" alt="">
                                </div>
                                <div class="mz-country-name">United Kingdom <span>(1,456)</span></div>
                            </div>
                            <div class="mz-stat-country">
                                <div class="mz-country-flag">
                                    <img src="<?php echo $flag_url_root; ?>IT.png" alt="">
                                </div>
                                <div class="mz-country-name">Italy <span>(898)</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="easl-col">
                        <div class="easl-col-inner">
                            <div class="mz-stat-country">
                                <div class="mz-country-flag">
                                    <img src="<?php echo $flag_url_root; ?>FR.png" alt="">
                                </div>
                                <div class="mz-country-name">France <span>(847)</span></div>
                            </div>
                            <div class="mz-stat-country">
                                <div class="mz-country-flag">
                                    <img src="<?php echo $flag_url_root; ?>ES.png" alt="">
                                </div>
                                <div class="mz-country-name">Spain <span>(676)</span></div>
                            </div>
                            <div class="mz-stat-country">
                                <div class="mz-country-flag">
                                    <img src="<?php echo $flag_url_root; ?>AT.png" alt="">
                                </div>
                                <div class="mz-country-name">Austria <span>(654)</span></div>
                            </div>
                            <div class="mz-stat-country">
                                <div class="mz-country-flag">
                                    <img src="<?php echo $flag_url_root; ?>CN.png" alt="">
                                </div>
                                <div class="mz-country-name">China <span>(432)</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="easl-col">
                        <div class="easl-col-inner">
                            <div class="mz-stat-country">
                                <div class="mz-country-flag">
                                    <img src="<?php echo $flag_url_root; ?>BR.png" alt="">
                                </div>
                                <div class="mz-country-name">Brazil <span>(383)</span></div>
                            </div>
                            <div class="mz-stat-country">
                                <div class="mz-country-flag">
                                    <img src="<?php echo $flag_url_root; ?>CH.png" alt="">
                                </div>
                                <div class="mz-country-name">Switzerlan <span>(377)</span></div>
                            </div>
                            <div class="mz-stat-country">
                                <div class="mz-country-flag">
                                    <img src="<?php echo $flag_url_root; ?>BR.png" alt="">
                                </div>
                                <div class="mz-country-name">Brazil <span>(383)</span></div>
                            </div>
                            <div class="mz-stat-country">
                                <div class="mz-country-flag">
                                    <img src="<?php echo $flag_url_root; ?>CH.png" alt="">
                                </div>
                                <div class="mz-country-name">Switzerlan <span>(377)</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="easl-mz-loader">
            <img src="<?php echo get_stylesheet_directory_uri() ?>/images/easl-loader.gif" alt="loading...">
        </div>
    </div>

</div>

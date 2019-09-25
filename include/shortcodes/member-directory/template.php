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

?>
<div <?php echo implode( ' ', $wrapper_attributes ); ?>>
    <div class="easl-mz-directory-inner">
	    <?php if ( $title ): ?>
            <h2 class="mz-page-heading"><?php echo $title; ?></h2>
	    <?php endif; ?>
        <div class="easl-mz-directory-filters">
            <div class="easl-ec-filter">
                <div class="easl-row">
                    <div class="easl-col">
                        <div class="easl-col-inner">
                            <div class="ec-filter-search">
                                <input type="text" name="ecf_search" value="" placeholder="Search for a member"/>
                                <span class="ecs-icon"><i class="ticon ticon-search" aria-hidden="true"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="easl-mz-filter-or">Or filter by...</div>
                <div class="easl-row">
                    <div class="easl-col easl-col-2">
                        <div class="easl-col-inner">
                            <div class="easl-custom-select">
                                <span class="ec-cs-label"></span>
                                <select name="ec-meeting-type">
                                    <option value="">All Countries</option>
                                    <option value="1">Country 1</option>
                                    <option value="1">Country 2</option>
                                    <option value="1">Country 3</option>
                                    <option value="1">Country 4</option>
                                    <option value="1">Country 5</option>
                                    <option value="1">Country 6</option>
                                    <option value="1">Country 7</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="easl-col easl-col-2">
                        <div class="easl-col-inner">
                            <div class="easl-custom-select">
                                <span class="ec-cs-label"></span>
                                <select name="ec-meeting-type">
                                    <option value="">All Specialities</option>
                                    <option value="1">Speciality 1</option>
                                    <option value="1">Speciality 2</option>
                                    <option value="1">Speciality 3</option>
                                    <option value="1">Speciality 4</option>
                                    <option value="1">Speciality 5</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="easl-mz-letter-filter">
                    <a href="#a" data-value="a">A</a>
                    <a href="#a" data-value="a">B</a>
                    <a href="#a" data-value="a">C</a>
                    <a href="#a" data-value="a">D</a>
                    <a href="#a" data-value="a">E</a>
                    <a href="#a" data-value="a">F</a>
                    <a href="#a" data-value="a">G</a>
                    <a href="#a" data-value="a">H</a>
                    <a href="#a" data-value="a">I</a>
                    <a href="#a" data-value="a">J</a>
                    <a href="#a" data-value="a">K</a>
                    <a href="#a" data-value="a">L</a>
                    <a href="#a" data-value="a">M</a>
                    <a href="#a" data-value="a">N</a>
                    <a href="#a" data-value="a">O</a>
                    <a href="#a" data-value="a">P</a>
                    <a href="#a" data-value="a">Q</a>
                    <a href="#a" data-value="a">R</a>
                    <a href="#a" data-value="a">S</a>
                    <a href="#a" data-value="a">T</a>
                    <a href="#a" data-value="a">U</a>
                    <a href="#a" data-value="a">V</a>
                    <a href="#a" data-value="a">W</a>
                    <a href="#a" data-value="a">X</a>
                    <a href="#a" data-value="a">Y</a>
                    <a href="#a" data-value="a">Z</a>
                </div>
            </div>
            <div class="easl-mz-filter-clear-wrap">
                <a class="easl-mz-clear-filters" href="#clear-filters">X Clear Filters</a>
            </div>
        </div>
        <div class="easl-mz-pagination easl-mz-pagination-top">
            <ul class="easl-mz-pagination-list">
                <li><a class="prev" href=""><span class="ticon ticon-angle-left"></span></a></li>
                <li><a class="page-numbers" href="">1</a></li>
                <li><a class="page-numbers" href="">2</a></li>
                <li><a class="page-numbers" href="">3</a></li>
                <li><span class="page-numbers-breaks" href="">...</span></li>
                <li><a class="page-numbers" href="">19</a></li>
                <li><a class="page-numbers" href="">20</a></li>
                <li><a class="page-numbers" href="">21</a></li>
                <li><a class="next" href=""><span class="ticon ticon-angle-right"></span></a></li>
            </ul>
        </div>
        <div class="easl-mz-directory-members-wrap">
            <div class="easl-mz-directory-members easl-row easl-row-col-2">
                <div class="easl-col">
                    <div class="easl-col-inner easl-mz-md-item">
                        <div class="md-item-image">
                            <img src="https://easl.websitestage.co.uk/wp-content/uploads/2018/09/easl-pierre-emmanuel-rautou-1-254x254.jpg" alt="">
                        </div>
                        <div class="md-item-details">
                            <h4 class="md-item-name"><a href=""><span>Tom Karlsen</span></a></h4>
                            <p class="md-item-text">Title goes here and is often quite long and will run over a few lines</p>
                            <span class="md-item-country">Great Britain</span>
                        </div>
                    </div>
                </div>
                <div class="easl-col">
                    <div class="easl-col-inner easl-mz-md-item">
                        <div class="md-item-image">
                            <img src="https://easl.websitestage.co.uk/wp-content/uploads/2018/09/easl-pierre-emmanuel-rautou-1-254x254.jpg" alt="">
                        </div>
                        <div class="md-item-details">
                            <h4 class="md-item-name"><a href=""><span>Massimo Pinzani</span></a></h4>
                            <p class="md-item-text">Title goes here and is often quite long and will run over a few lines</p>
                            <span class="md-item-country">Italy</span>
                        </div>
                    </div>
                </div>
                <div class="easl-col">
                    <div class="easl-col-inner easl-mz-md-item">
                        <div class="md-item-image">
                            <img src="https://easl.websitestage.co.uk/wp-content/uploads/2018/09/easl-pierre-emmanuel-rautou-1-254x254.jpg" alt="">
                        </div>
                        <div class="md-item-details">
                            <h4 class="md-item-name"><a href=""><span>Prof Marco Marzioni</span></a></h4>
                            <p class="md-item-text">Title goes here and is often quite long and will run over a few lines</p>
                            <span class="md-item-country">Italy</span>
                        </div>
                    </div>
                </div>
                <div class="easl-col">
                    <div class="easl-col-inner easl-mz-md-item">
                        <div class="md-item-image">
                            <img src="https://easl.websitestage.co.uk/wp-content/uploads/2018/09/easl-pierre-emmanuel-rautou-1-254x254.jpg" alt="">
                        </div>
                        <div class="md-item-details">
                            <h4 class="md-item-name"><a href=""><span>Marco Marzioni</span></a></h4>
                            <p class="md-item-text">Title goes here and is often quite long and will run over a few lines</p>
                            <span class="md-item-country">Italy</span>
                        </div>
                    </div>
                </div>
                <div class="easl-col">
                    <div class="easl-col-inner easl-mz-md-item">
                        <div class="md-item-image">
                            <img src="https://easl.websitestage.co.uk/wp-content/uploads/2018/09/easl-pierre-emmanuel-rautou-1-254x254.jpg" alt="">
                        </div>
                        <div class="md-item-details">
                            <h4 class="md-item-name"><a href=""><span>Philip Newsome</span></a></h4>
                            <p class="md-item-text">Title goes here and is often quite long and will run over a few lines</p>
                            <span class="md-item-country">Great Britain</span>
                        </div>
                    </div>
                </div>
                <div class="easl-col">
                    <div class="easl-col-inner easl-mz-md-item">
                        <div class="md-item-image">
                            <img src="https://easl.websitestage.co.uk/wp-content/uploads/2018/09/easl-pierre-emmanuel-rautou-1-254x254.jpg" alt="">
                        </div>
                        <div class="md-item-details">
                            <h4 class="md-item-name"><a href=""><span>Helena Cortez-Pinto</span></a></h4>
                            <p class="md-item-text">Title goes here and is often quite long and will run over a few lines</p>
                            <span class="md-item-country">Italy</span>
                        </div>
                    </div>
                </div>
                <div class="easl-col">
                    <div class="easl-col-inner easl-mz-md-item">
                        <div class="md-item-image">
                            <img src="https://easl.websitestage.co.uk/wp-content/uploads/2018/09/easl-pierre-emmanuel-rautou-1-254x254.jpg" alt="">
                        </div>
                        <div class="md-item-details">
                            <h4 class="md-item-name"><a href=""><span>Mauro-Bernardi</span></a></h4>
                            <p class="md-item-text">Title goes here and is often quite long and will run over a few lines</p>
                            <span class="md-item-country">Italy</span>
                        </div>
                    </div>
                </div>
                <div class="easl-col">
                    <div class="easl-col-inner easl-mz-md-item">
                        <div class="md-item-image">
                            <img src="https://easl.websitestage.co.uk/wp-content/uploads/2018/09/easl-pierre-emmanuel-rautou-1-254x254.jpg" alt="">
                        </div>
                        <div class="md-item-details">
                            <h4 class="md-item-name"><a href=""><span>Gregoire Pavillon</span></a></h4>
                            <p class="md-item-text">Title goes here and is often quite long and will run over a few lines</p>
                            <span class="md-item-country">France</span>
                        </div>
                    </div>
                </div>
                <div class="easl-col">
                    <div class="easl-col-inner easl-mz-md-item">
                        <div class="md-item-image">

                        </div>
                        <div class="md-item-details">
                            <h4 class="md-item-name"><a href=""><span>Frank-Tacke</span></a></h4>
                            <p class="md-item-text">Title goes here and is often quite long and will run over a few lines</p>
                            <span class="md-item-country">Switzerland</span>
                        </div>
                    </div>
                </div>
                <div class="easl-col">
                    <div class="easl-col-inner easl-mz-md-item">
                        <div class="md-item-image">

                        </div>
                        <div class="md-item-details">
                            <h4 class="md-item-name"><a href=""><span>Annalisa Berzigotti</span></a></h4>
                            <p class="md-item-text">Title goes here and is often quite long and will run over a few lines</p>
                            <span class="md-item-country">Italy</span>
                        </div>
                    </div>
                </div>
                <div class="easl-col">
                    <div class="easl-col-inner easl-mz-md-item">
                        <div class="md-item-image">

                        </div>
                        <div class="md-item-details">
                            <h4 class="md-item-name"><a href=""><span>Francesco-Negro</span></a></h4>
                            <p class="md-item-text">Title goes here and is often quite long and will run over a few lines</p>
                            <span class="md-item-country">Austria</span>
                        </div>
                    </div>
                </div>
                <div class="easl-col">
                    <div class="easl-col-inner easl-mz-md-item">
                        <div class="md-item-image">

                        </div>
                        <div class="md-item-details">
                            <h4 class="md-item-name"><a href=""><span>Alejandro Forne</span></a></h4>
                            <p class="md-item-text">Title goes here and is often quite long and will run over a few lines</p>
                            <span class="md-item-country">Italy</span>
                        </div>
                    </div>
                </div>
                <div class="easl-col">
                    <div class="easl-col-inner easl-mz-md-item">
                        <div class="md-item-image">

                        </div>
                        <div class="md-item-details">
                            <h4 class="md-item-name"><a href=""><span>Markus Cornberg</span></a></h4>
                            <p class="md-item-text">Title goes here and is often quite long and will run over a few lines</p>
                            <span class="md-item-country">Germany</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="easl-mz-pagination easl-mz-pagination-bottom">
            <ul class="easl-mz-pagination-list">
                <li><a class="prev" href=""><span class="ticon ticon-angle-left"></span></a></li>
                <li><a class="page-numbers" href="">1</a></li>
                <li><a class="page-numbers" href="">2</a></li>
                <li><a class="page-numbers" href="">3</a></li>
                <li><span class="page-numbers-breaks" href="">...</span></li>
                <li><a class="page-numbers" href="">19</a></li>
                <li><a class="page-numbers" href="">20</a></li>
                <li><a class="page-numbers" href="">21</a></li>
                <li><a class="next" href=""><span class="ticon ticon-angle-right"></span></a></li>
            </ul>
        </div>
    </div>
    <div class="easl-mz-member-profile-wrap">
        <div class="easl-mz-member-profile-con">
            <div class="easl-mz-back-link-wrap">
                <a class="easl-mz-back-link" href="#">Back</a>
            </div>
            <div class="easl-easl-mz-member-profile">
                <div class="easl-easl-mz-mp-details">
                    <div class="easl-easl-mz-mp-header">
                        <div class="easl-easl-mz-mp-image">
                            <img src="https://easl.websitestage.co.uk/wp-content/uploads/2018/09/easl-pierre-emmanuel-rautou-1-254x254.jpg" alt="">
                        </div>
                        <div class="easl-easl-mz-mp-title">
                            <h3>Prof.Tom Karlsen </h3>
                            <h4>Department of Transplantation Medicine</h4>
                            <div class="easl-easl-mz-mp-excerpt">Division of Surgery, Inflammatory medicine and Transplantation<br/>
                                Oslo University Hospital Rikshospitalet</div>
                        </div>
                    </div>
                    <div class="easl-easl-mz-mp-speciality">
                        <strong>Speciality:</strong> <span>Liver Tumors</span>
                    </div>
                    <div class="easl-easl-mz-mp-intro">
                        <p>Dr. Karlsen graduated from medical school at the University of Bergen in 1997. In 2014 he was appointed full professor of internal medicine at the University of Oslo. Within the Oslo University Hospital he is head of research at the Division of Surgery, Inflammatory medicine and Transplantation and leader of the Norwegian PSC research center at the Department of Transplantation Medicine and Research Institute of Internal Medicine. Clinically, he works as a senior consultant within transplant hepatology at the Section of Gastroenterology at the Department of Transplantation Medicine.</p>
                        <p>Dr. Karlsen's research experience ranges from genetic epidemiology and applications of genomic technologies (e.g. dissecting the immunogenetic susceptibility to immune‐mediated gut and liver diseases), via translational research (e.g. biomarker studies) to clinical studies (e.g. liver transplantation). He is secretary and co-founder of the International PSC study group and has served as the coordinator of the Nordic Liver Transplant Registry from 2006-2014.</p>
                    </div>
                </div>
                <div class="easl-easl-mz-mp-contacts">
                    <div class="easl-easl-mz-mp-email">
                        <p class="easl-easl-mz-mp-contact-item"><strong>Email:</strong><a href="mailto:t.h.karlsen@medisin.uio.no">t.h.karlsen@medisin.uio.no</a></p>
                    </div>
                    <div class="easl-easl-mz-mp-telmob">
                        <p class="easl-easl-mz-mp-contact-item"><strong>TEL:</strong><span">+47 23073616</span></p>
                        <p class="easl-easl-mz-mp-contact-item"><strong>MOB:</strong><span">+47 783737777</span></p>
                    </div>
                    <div lang="easl-easl-mz-mp-address">
                        <p class="easl-easl-mz-mp-contact-item">
                            <strong>ADDRESS:</strong>
                            Address Line 1 could be long<br/>
                            Address Line 2<br/>
                            Address Line 3<br/>
                            Postcode</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

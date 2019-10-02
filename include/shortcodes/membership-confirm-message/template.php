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
 * Shortcode class EASL_VC_MZ_New_Membership_Form
 * @var $this EASL_VC_MZ_New_Membership_Form
 */
$el_class      = '';
$el_id         = '';
$css_animation = '';
$title         = '';
$atts          = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$css_animation = $this->getCSSAnimation( $css_animation );

$class_to_filter = 'wpb_easl_mz_membership_message wpb_content_element ' . $this->getCSSAnimation( $css_animation );
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class       = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$wrapper_attributes = array();
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
if ( ! empty( $css_class ) ) {
	$wrapper_attributes[] = 'class="' . esc_attr( $css_class ) . '"';
}
$type    = ! empty( $_GET['membership_status'] ) ? $_GET['membership_status'] : '';
$message = '';
if ( $type == 'created_offline' ) {
	$message_title = get_field( 'mz_offline_payment_msg_title', 'option' );
	$message       = get_field( 'mz_offline_payment_msg_content', 'option' );
	//$membership_id = ! empty( $_GET['mbs_id'] ) ? $_GET['mbs_id'] : '';
	$membership_number = ! empty( $_GET['mbs_num'] ) ? $_GET['mbs_num'] : '';
	$name              = ! empty( $_GET['mb_name'] ) ? $_GET['mb_name'] : '';
	$fname             = ! empty( $_GET['fname'] ) ? $_GET['fname'] : '';
	$lname             = ! empty( $_GET['lname'] ) ? $_GET['lname'] : '';

	$membership_details = array();
	if ( $membership_number ) {
		$membership_details[] = "Membership Number: {$membership_number}";
	}
	if ( $fname ) {
		$membership_details[] = "First Name: {$fname}";
	}
	if ( $lname ) {
		$membership_details[] = "Last Name: {$lname}";
	}
	if ( $name ) {
		$membership_details[] = "Full Name: {$name}";
	}

	if ( count( $membership_details ) ) {
		$membership_details = implode( '<br/>', $membership_details );
	} else {
		$membership_details = '';
	}
	$message = str_replace( array( '{{membership_details}}' ), array( $membership_details ), $message );

} elseif ( $type == 'declined_online' ) {
	$message_title = get_field( 'mz_online_declined_payment_msg_title', 'option' );
	$message       = get_field( 'mz_online_declined_payment_msg_content', 'option' );
	//$membership_id = ! empty( $_GET['mbs_id'] ) ? $_GET['mbs_id'] : '';
	$membership_number = ! empty( $_GET['mbs_num'] ) ? $_GET['mbs_num'] : '';
	$name              = ! empty( $_GET['mb_name'] ) ? $_GET['mb_name'] : '';
	$fname             = ! empty( $_GET['fname'] ) ? $_GET['fname'] : '';
	$lname             = ! empty( $_GET['lname'] ) ? $_GET['lname'] : '';

	$membership_details = array();
	if ( $membership_number ) {
		$membership_details[] = "Membership Number: {$membership_number}";
	}
	if ( $fname ) {
		$membership_details[] = "First Name: {$fname}";
	}
	if ( $lname ) {
		$membership_details[] = "Last Name: {$lname}";
	}
	if ( $name ) {
		$membership_details[] = "Full Name: {$name}";
	}

	if ( count( $membership_details ) ) {
		$membership_details = implode( '<br/>', $membership_details );
	} else {
		$membership_details = '';
	}
	$message = str_replace( array( '{{membership_details}}' ), array( $membership_details ), $message );

} elseif ( $type == 'cancelled_online' ) {
	$message_title = get_field( 'mz_online_cancelled_payment_msg_title', 'option' );
	$message       = get_field( 'mz_online_cancelled_payment_msg_content', 'option' );
	//$membership_id = ! empty( $_GET['mbs_id'] ) ? $_GET['mbs_id'] : '';
	$membership_number = ! empty( $_GET['mbs_num'] ) ? $_GET['mbs_num'] : '';
	$name              = ! empty( $_GET['mb_name'] ) ? $_GET['mb_name'] : '';
	$fname             = ! empty( $_GET['fname'] ) ? $_GET['fname'] : '';
	$lname             = ! empty( $_GET['lname'] ) ? $_GET['lname'] : '';

	$membership_details = array();
	if ( $membership_number ) {
		$membership_details[] = "Membership Number: {$membership_number}";
	}
	if ( $fname ) {
		$membership_details[] = "First Name: {$fname}";
	}
	if ( $lname ) {
		$membership_details[] = "Last Name: {$lname}";
	}
	if ( $name ) {
		$membership_details[] = "Full Name: {$name}";
	}

	if ( count( $membership_details ) ) {
		$membership_details = implode( '<br/>', $membership_details );
	} else {
		$membership_details = '';
	}
	$message = str_replace( array( '{{membership_details}}' ), array( $membership_details ), $message );

} elseif ( $type == 'failed_online' ) {
	$message_title = get_field( 'mz_online_failed_payment_msg_title', 'option' );
	$message       = get_field( 'mz_online_falied_payment_msg_content', 'option' );
	//$membership_id = ! empty( $_GET['mbs_id'] ) ? $_GET['mbs_id'] : '';
	$membership_number = ! empty( $_GET['mbs_num'] ) ? $_GET['mbs_num'] : '';
	$name              = ! empty( $_GET['mb_name'] ) ? $_GET['mb_name'] : '';
	$fname             = ! empty( $_GET['fname'] ) ? $_GET['fname'] : '';
	$lname             = ! empty( $_GET['lname'] ) ? $_GET['lname'] : '';

	$membership_details = array();
	if ( $membership_number ) {
		$membership_details[] = "Membership Number: {$membership_number}";
	}
	if ( $fname ) {
		$membership_details[] = "First Name: {$fname}";
	}
	if ( $lname ) {
		$membership_details[] = "Last Name: {$lname}";
	}
	if ( $name ) {
		$membership_details[] = "Full Name: {$name}";
	}

	if ( count( $membership_details ) ) {
		$membership_details = implode( '<br/>', $membership_details );
	} else {
		$membership_details = '';
	}
	$message = str_replace( array( '{{membership_details}}' ), array( $membership_details ), $message );

} elseif ( $type == 'paid_online' ) {
	$message_title = get_field( 'mz_online_payment_msg_title', 'option' );
	$message       = get_field( 'mz_online_payment_msg_content', 'option' );
	//$membership_id = ! empty( $_GET['mbs_id'] ) ? $_GET['mbs_id'] : '';
	$membership_number = ! empty( $_GET['mbs_num'] ) ? $_GET['mbs_num'] : '';
	$name              = ! empty( $_GET['mb_name'] ) ? $_GET['mb_name'] : '';

	$membership_details = array();
	if ( $membership_number ) {
		$membership_details[] = "Membership Number: {$membership_number}";
	}
	if ( $name ) {
		$membership_details[] = "Full Name: {$name}";
	}

	if ( count( $membership_details ) ) {
		$membership_details = implode( '<br/>', $membership_details );
	} else {
		$membership_details = '';
	}

	$message = str_replace( array( '{{membership_details}}' ), array( $membership_details ), $message );
}
if ( $message ):
	?>

    <div <?php echo implode( ' ', $wrapper_attributes ); ?>>
		<?php if ( $message_title ): ?>
            <h2 class="mz-page-heading"><?php echo $message_title; ?></h2>
		<?php endif; ?>
        <div class="mz-message-content">
			<?php echo wpb_js_remove_wpautop( $message, false ); ?>
        </div>
    </div>
<?php endif; ?>



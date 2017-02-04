<?php
/**
 * Email Styles
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-styles.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates/Emails
 * @version 2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Load colours
$bg              = get_option( 'woocommerce_email_background_color' );
$body            = get_option( 'woocommerce_email_body_background_color' );
$base            = get_option( 'woocommerce_email_base_color' );
$base_text       = wc_light_or_dark( $base, '#202020', '#ffffff' );
$text            = get_option( 'woocommerce_email_text_color' );

$bg_darker_10    = wc_hex_darker( $bg, 10 );
$base_lighter_20 = wc_hex_lighter( $base, 20 );
$base_lighter_40 = wc_hex_lighter( $base, 40 );
$text_lighter_20 = wc_hex_lighter( $text, 20 );

// !important; is a gmail hack to prevent styles being stripped if it doesn't like something.
?>
#wrapper {
background-color: <?php echo esc_attr( $bg ); ?>;
margin: 0;
padding: 70px 0 70px 0;
-webkit-text-size-adjust: none !important;
width: 100%;
letter-spacing: 1px;
}

#template_container {
box-shadow: 0 1px 9px rgba(0,0,0,0.05) !important;
background-color: <?php echo esc_attr( $body ); ?>;
border: 1px solid <?php echo esc_attr( $bg_darker_10 ); ?>;
border-radius: 2px !important;
}

#template_header {
background-color: <?php echo esc_attr( $base ); ?>;
border-radius: 2px 2px 0 0 !important;
color: <?php echo esc_attr( $base_text ); ?>;
border-bottom: 0;
font-weight: bold;
line-height: 100%;
vertical-align: middle;
font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
}

#template_header h1 {
color: <?php echo esc_attr( $base_text ); ?>;
}

#template_footer td {
padding: 0;
-webkit-border-radius: 2px;
}

#template_footer #credit {
border:0;
color: <?php echo esc_attr( $bg_darker_10 ); ?>;
font-family: Arial;
font-size: 13px;
line-height:125%;
text-align:center;
padding: 0 15px 15px;
}

#body_content {
background-color: <?php echo esc_attr( $body ); ?>;
}
#body_content table {
border-spacing: 1px;
border-collapse: collapse;
}
#body_content table td {
padding: 0 15px;
}

#body_content table td td {
padding: 15px;
}

#body_content table td th {
padding: 15px;
letter-spacing: 2px;
font-size: 13px;
}

#body_content p {
margin: 0 0 16px;
}

#body_content_inner {
color: <?php echo esc_attr( $text_lighter_20 ); ?>;
font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
font-size: 13px;
line-height: 150%;
text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
}

h1 {
color: #111;
display: block;
font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
font-size: 30px;
font-weight: 300;
line-height: 100%;
margin: 0;
padding: 20px 15px 0;
text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
text-shadow: 0 1px 0 <?php echo esc_attr( $base_lighter_20 ); ?>;
-webkit-font-smoothing: antialiased;
}

h2 {
color: #111;
display: block;
font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
font-size: 16px;
font-weight: bold;
line-height: 130%;
margin: 16px 0 8px;
text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
}

h3 {
color: #111;
display: block;
font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
font-size: 16px;
font-weight: bold;
line-height: 130%;
margin: 16px 0 8px;
text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
}

a {
color: #111;
font-weight: normal;
text-decoration: underline;
}

img {
border: none;
display: inline;
font-size: 14px;
font-weight: bold;
height: auto;
line-height: 100%;
outline: none;
text-decoration: none;
text-transform: capitalize;
}
.socials ul {
padding: 0;
}
.socials img  {
width: 32px;
height: 32px;

}
<?php

<!DOCTYPE html>
<html <?php language_attributes(); ?> >
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1, width=device-width, minimum-scale=1">
    <link rel="shortcut icon" type="image/png" href="<?php echo (look_get_option('look_favicon')=='')?  MAIN_ASSETS_URI.'/images/favicon.png': look_get_option('look_favicon') ; ?>"/>
    <?php wp_head(); ?>
</head>
<?php
    look_get_template( 'header' );
?>
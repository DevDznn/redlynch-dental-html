<?php
/**
 * Document head and site header.
 *
 * @package Redlynch_Dental
 */

defined( 'ABSPATH' ) || exit;
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width,initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip" href="#main"><?php esc_html_e( 'Skip to main content', 'redlynch-dental' ); ?></a>
<?php get_template_part( 'template-parts/header/site-header' ); ?>
<main id="main">

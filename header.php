<!DOCTYPE html>
<html <?php language_attributes(); ?> <?php lightshadestudioworks_schema_type(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width">
    <?php wp_head(); ?>
</head>


<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <div id="wrapper" class="hfeed">
        
            <header id="header" role="banner">
                <?php
                $navbar_layout = get_theme_mod('navbar_layout_choice', 'lsw_menu_layout_1');
                $navbar_path = "template-parts/navbar/{$navbar_layout}.php";
                if (file_exists(get_template_directory() . '/' . $navbar_path)) {
                    get_template_part("template-parts/navbar/{$navbar_layout}");
                } else {
                    get_template_part('template-parts/navbar/lsw_menu_layout_1');
                }
                ?>
            </header>
<div id="container" class="site-container">
            <main id="content" role="main">
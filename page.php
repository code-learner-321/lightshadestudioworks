<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="entry-content" itemprop="mainContentOfPage">
                <?php if (has_post_thumbnail()) {
                    the_post_thumbnail('full', array('itemprop' => 'image'));
                } ?>
                <?php
                if ( is_front_page() || is_page( array( 'home', 'front-page' ) ) ) {
                    echo do_blocks( lightshadestudioworks_render_home() );
                } elseif ( is_page( 'about' ) ) {
                    echo do_blocks( lightshadestudioworks_render_about() );
                } elseif ( is_page( 'portfolio' ) ) {
                    echo do_blocks( lightshadestudioworks_render_portfolio() );
                } elseif ( is_page( 'portfolio-gallery' ) ) {
                    echo do_blocks( lightshadestudioworks_render_portfolio_gallery() );
                } elseif ( is_page( 'blog' ) ) {
                    echo do_blocks( lightshadestudioworks_render_blog() );
                } elseif ( is_page( 'contact' ) ) {
                    echo do_blocks( lightshadestudioworks_render_contact() );
                } else {
                    the_content();
                }
                ?>
                <div class="entry-links"><?php wp_link_pages(); ?></div>
            </div>
        </article>
        <?php if (comments_open() && !post_password_required()) {
            comments_template('', true);
        } ?>
<?php endwhile;
endif; ?>
<?php get_footer(); ?>
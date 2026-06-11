<?php get_header(); ?>

    <h1 class="text-4xl font-bold tracking-tight text-gray-700 my-4">Single Post Template</h1>
    <?php while ( have_posts() ) : the_post(); ?>
        
        <?php 
        // Gutenberg content is output here
        the_content(); 
        
        // Use your partials for metadata
        get_template_part('entry-footer'); 
        ?>

    <?php endwhile; ?>

<?php get_footer(); ?>
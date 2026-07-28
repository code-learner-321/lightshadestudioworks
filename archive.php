<?php get_header(); ?>
<div id="primary" class="px-4 py-12 min-h-screen 
    bg-gradient-to-b from-[#ffffff] to-[#f9f9f9] dark:from-[#000000] dark:to-[#111111]">
    <div class="max-w-5xl mx-auto">
        <!-- Archive Header -->
        <header class="mb-10 text-center">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 mb-5 
                text-xs font-bold tracking-widest text-[#C5A059] uppercase 
                bg-[#C5A059]/10 rounded-full shadow-sm">
                <span class="w-2.5 h-2.5 rounded-full bg-[#C5A059] animate-pulse"></span>
                <?php esc_html_e( 'Archive', 'lightshadestudioworks' ); ?>
            </div>

            <h1 class="text-3xl sm:text-4xl font-extrabold 
                text-[#000000] dark:text-[#ffffff] tracking-tight">
                <?php the_archive_title(); ?>
            </h1>

            <?php if ( '' != get_the_archive_description() ) : ?>
                <p class="mt-4 text-base font-medium leading-relaxed 
                    text-[#000000]/70 dark:text-[#ffffff]/70 max-w-2xl mx-auto">
                    <?php echo wp_kses_post( get_the_archive_description() ); ?>
                </p>
            <?php endif; ?>
        </header>

        <!-- Archive Loop -->
        <?php if ( have_posts() ) : ?>
            <div class="py-8 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <?php while ( have_posts() ) : the_post(); ?>
                    <div class="bg-[#ffffff] dark:bg-[#111111] rounded-2xl shadow-md 
                        hover:shadow-[0_10px_30px_-5px_rgba(197,160,89,0.3)] transition-all p-6">
                        
                        <h2 class="text-xl font-bold mb-3 
                            text-[#000000] dark:text-[#ffffff]">
                            <a href="<?php the_permalink(); ?>" 
                               class="hover:text-[#C5A059] transition-colors">
                                <?php the_title(); ?>
                            </a>
                        </h2>

                        <div class="text-sm text-[#000000]/70 dark:text-[#ffffff]/70 mb-4">
                            <?php the_excerpt(); ?>
                        </div>

                        <a href="<?php the_permalink(); ?>" 
                           class="inline-flex items-center px-4 py-2 text-xs font-bold uppercase 
                           text-[#ffffff] bg-[#000000] dark:bg-[#ffffff] dark:text-[#000000] 
                           rounded-lg hover:bg-[#C5A059] hover:scale-105 active:scale-95 
                           transition-transform shadow-md">
                            <?php esc_html_e( 'Read More', 'lightshadestudioworks' ); ?>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>

            <!-- Pagination -->
            <div class="mt-10">
                <?php get_template_part( 'nav', 'below' ); ?>
            </div>

        <?php else : ?>
            <p class="text-center text-[#000000]/70 dark:text-[#ffffff]/70">
                <?php esc_html_e( 'No posts found in this archive.', 'lightshadestudioworks' ); ?>
            </p>
        <?php endif; ?>

    </div>
</div>

<?php get_footer(); ?>

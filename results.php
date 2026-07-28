<?php
/**
 * Template Name: No Results Page
 * Description: Custom template for displaying when searches return no results.
 */

get_header(); ?>
<div id="primary" class="px-4 pt-20 pb-20 min-h-screen 
    bg-gradient-to-b from-[#ffffff] to-[#f9f9f9] dark:from-[#000000] dark:to-[#111111]">

    <div class="max-w-3xl mx-auto text-center">

        <!-- No Results Header -->
        <header class="mb-10">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 mb-5 
                text-xs font-bold tracking-widest text-[#C5A059] uppercase 
                bg-[#C5A059]/10 rounded-full shadow-sm">
                <span class="w-2.5 h-2.5 rounded-full bg-[#C5A059] animate-pulse"></span>
                <?php esc_html_e( 'Search', 'lightshadestudioworks' ); ?>
            </div>

            <h1 class="text-3xl sm:text-4xl font-extrabold 
                text-[#000000] dark:text-[#ffffff] tracking-tight">
                <?php esc_html_e( 'No Results Found', 'lightshadestudioworks' ); ?>
            </h1>
        </header>

        <!-- Message -->
        <p class="text-base text-[#000000]/70 dark:text-[#ffffff]/70 mb-8">
            <?php esc_html_e( 'Sorry, nothing matched your search. Please try again with different keywords.', 'lightshadestudioworks' ); ?>
        </p>

        <!-- Search Form -->
<div class="max-w-md mx-auto">
    <?php get_search_form(); ?>
</div>


        <!-- Helpful Links -->
        <div class="mt-10">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" 
               class="inline-flex items-center px-6 py-3 text-sm font-bold uppercase 
               text-[#ffffff] bg-[#000000] dark:bg-[#ffffff] dark:text-[#000000] 
               rounded-lg hover:bg-[#C5A059] hover:scale-105 active:scale-95 
               transition-transform shadow-md">
                <?php esc_html_e( 'Back to Home', 'lightshadestudioworks' ); ?>
            </a>
        </div>

    </div>
</div>

<?php get_footer(); ?>

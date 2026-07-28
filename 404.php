<?php get_header(); ?>

<div id="primary" class="flex-grow w-full flex items-center justify-center px-4 py-12 min-h-screen 
    bg-gradient-to-b from-[#ffffff] to-[#f9f9f9] dark:from-[#000000] dark:to-[#111111]">

    <div class="max-w-xl w-full mx-auto text-center">

        <!-- Main 404 Card -->
        <article id="post-0" class="post not-found 
            bg-[#ffffff] dark:bg-[#111111] 
            rounded-3xl shadow-[0_10px_30px_-5px_rgba(197,160,89,0.3)] 
            p-8 sm:p-12 transition-all duration-300 relative overflow-hidden" role="alert">

            <!-- Header -->
            <header class="mb-6 relative z-10">
                <!-- Status Badge -->
                <div class="inline-flex items-center gap-2 px-4 py-1.5 mb-5 
                    text-xs font-bold tracking-widest text-[#C5A059] uppercase 
                    bg-[#C5A059]/10 rounded-full shadow-sm">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#C5A059] animate-pulse"></span>
                    <?php esc_html_e( 'Error 404', 'lightshadestudioworks' ); ?>
                </div>

                <!-- Main Title -->
                <h1 class="text-3xl sm:text-4xl font-extrabold 
                    text-[#000000] dark:text-[#ffffff] tracking-tight">
                    <?php esc_html_e( 'Page not found', 'lightshadestudioworks' ); ?>
                </h1>
            </header>

            <!-- Content -->
            <div class="text-[#000000]/70 dark:text-[#ffffff]/70 space-y-6 relative z-10">
                <p class="text-base font-medium leading-relaxed">
                    <?php esc_html_e( 'Nothing found for the requested page. Try a search instead?', 'lightshadestudioworks' ); ?>
                </p>

                <!-- Search Form -->
                <div class="mt-4 flex justify-center">
                    <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" 
                        class="w-full max-w-md space-y-2">
                        
                        <!-- Visible Label -->
                        <label for="s" class="block mb-2 text-sm font-semibold text-[#000000]/60 dark:text-[#ffffff]/60">
                            <?php esc_html_e( 'Search for:', 'lightshadestudioworks' ); ?>
                        </label>

                        <!-- Input + Button -->
                        <div class="flex items-center rounded-xl border border-[#000000]/5 dark:border-[#ffffff]/10 
                            bg-[#000000]/2 dark:bg-[#ffffff]/2 focus-within:ring-2 focus-within:ring-[#C5A059] transition-all">
                            
                            <input type="search" id="s" value="<?php echo get_search_query(); ?>" name="s" 
                                placeholder="<?php esc_attr_e( 'Search website...', 'lightshadestudioworks' ); ?>" 
                                class="w-full h-12 px-4 text-sm bg-transparent border-none 
                                text-[#000000] dark:text-[#ffffff] 
                                placeholder-[#000000]/40 dark:placeholder-[#ffffff]/40 
                                focus:outline-none focus:ring-0" />

                            <button type="submit" 
                                class="flex-shrink-0 px-6 h-12 text-xs font-bold tracking-wider uppercase 
                                text-[#ffffff] bg-[#000000] dark:bg-[#ffffff] dark:text-[#000000] 
                                rounded-xl hover:bg-[#C5A059] hover:scale-105 active:scale-95 
                                transition-transform shadow-md cursor-pointer">
                                <?php esc_html_e( 'Search', 'lightshadestudioworks' ); ?>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Action Links -->
                <div class="pt-6 border-t border-[#000000]/10 dark:border-[#ffffff]/10 flex flex-wrap items-center justify-center">
                    <a href="<?php echo esc_url(home_url('/')); ?>" 
                        class="inline-flex items-center justify-center px-6 py-3 
                        text-xs font-bold uppercase text-[#ffffff] bg-[#000000] 
                        dark:bg-[#ffffff] dark:text-[#000000] rounded-xl 
                        hover:bg-[#C5A059] hover:scale-105 active:scale-95 
                        transition-transform shadow-md focus:outline-none 
                        focus:ring-2 focus:ring-[#C5A059] focus:ring-offset-2">
                        <?php esc_html_e( 'Back to Home', 'lightshadestudioworks' ); ?>
                    </a>
                </div>
            </div>

        </article>
    </div>
</div>

<?php get_footer(); ?>

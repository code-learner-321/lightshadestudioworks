<?php get_header(); ?>

<main id="primary" class="site-main bg-slate-50 min-h-screen pb-20 font-sans antialiased">
    
    <header class="w-full pt-12 pb-8 md:pt-16 md:pb-12 bg-white border-b border-slate-200/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-slate-50 border border-slate-200/60 rounded-2xl p-6 md:p-8 shadow-sm flex flex-col md:flex-row items-center md:items-start gap-6 md:gap-8">
                
                <div class="flex-shrink-0 h-24 w-24 md:h-28 md:w-28 overflow-hidden rounded-full p-1 ring-4 ring-indigo-50 bg-white shadow-sm">
                    <?php echo get_avatar( get_the_author_meta( 'ID' ), 112, '', '', array('class' => 'h-full w-full object-cover rounded-full') ); ?>
                </div>

                <div class="flex-1 text-center md:text-left">
                    <span class="text-xs font-bold tracking-widest uppercase text-indigo-600 block mb-1">Author Profile</span>
                    
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mb-2">
                        <?php the_author(); ?>
                    </h1>

                    <?php if ( get_the_author_meta( 'description' ) ) : ?>
                        <p class="text-sm md:text-base text-slate-600 leading-relaxed max-w-2xl mb-4">
                            <?php the_author_meta( 'description' ); ?>
                        </p>
                    <?php else : ?>
                        <p class="text-sm italic text-slate-400 mb-4">
                            This author hasn't added a biographical description yet.
                        </p>
                    <?php endif; ?>

                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 text-xs font-medium text-slate-500">
                        <span class="inline-flex items-center gap-1.5 bg-slate-200/60 text-slate-700 px-3 py-1 rounded-full">
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path></svg>
                            <?php echo count_user_posts( get_the_author_meta( 'ID' ) ); ?> Articles Written
                        </span>

                        <?php if ( get_the_author_meta( 'user_url' ) ) : ?>
                            <a href="<?php echo esc_url( get_the_author_meta( 'user_url' ) ); ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 text-indigo-600 hover:text-indigo-700 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244"></path></svg>
                                Visit Website
                            </a>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12">
        
        <h2 class="text-xl font-bold text-slate-900 tracking-tight mb-6">
            Latest Articles by <?php the_author(); ?>
        </h2>

        <?php if ( have_posts() ) : ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                <?php while ( have_posts() ) : the_post(); ?>
                    
                    <article id="post-<?php the_ID(); ?>" <?php post_class('bg-white border border-slate-200/60 rounded-2xl overflow-hidden shadow-sm hover:shadow-md hover:border-slate-300/80 transition duration-200 flex flex-col h-full'); ?>>
                        
                        <a href="<?php the_permalink(); ?>" class="block aspect-video bg-slate-100 overflow-hidden relative group">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <?php the_post_thumbnail('medium_large', array('class' => 'w-full h-full object-cover transform group-hover:scale-[1.03] transition duration-500')); ?>
                            <?php else : ?>
                                <div class="w-full h-full bg-slate-200 flex items-center justify-center text-slate-400">
                                    <svg class="w-10 h-10 stroke-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"></path></svg>
                                </div>
                            <?php endif; ?>
                        </a>

                        <div class="p-5 md:p-6 flex flex-col flex-1">
                            
                            <?php if ( has_category() ) : ?>
                                <div class="flex flex-wrap gap-1.5 mb-3">
                                    <?php
                                    $categories = get_the_category();
                                    if ( ! empty( $categories ) ) {
                                        echo '<span class="text-[10px] font-bold tracking-wider uppercase text-indigo-600 bg-indigo-50 px-2.5 py-0.5 rounded-md">' . esc_html( $categories[0]->name ) . '</span>';
                                    }
                                    ?>
                                </div>
                            <?php endif; ?>

                            <h3 class="text-lg font-bold text-slate-900 leading-snug mb-2 line-clamp-2 hover:text-indigo-600 transition">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h3>

                            <div class="text-sm text-slate-500 line-clamp-3 mb-4 leading-relaxed">
                                <?php the_excerpt(); ?>
                            </div>

                            <div class="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-medium text-slate-400">
                                <div class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.625 21h12.75a2.25 2.25 0 002.25-2.25m-18 0v-7.5A2.25 2.25 0 015.625 9h12.75A2.25 2.25 0 0121 11.25v7.5"></path></svg>
                                    <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
                                </div>
                                <a href="<?php the_permalink(); ?>" class="text-indigo-600 font-bold hover:text-indigo-700 inline-flex items-center gap-0.5 group">
                                    Read Post 
                                    <span class="transform group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                                </a>
                            </div>

                        </div>
                    </article>

                <?php endwhile; ?>
            </div>

            <div class="mt-12 py-4 border-t border-slate-200/60 flex justify-center">
                <?php 
                the_posts_pagination(array(
                    'mid_size'  => 2,
                    'prev_text' => __('&larr; Older Articles', 'textdomain'),
                    'next_text' => __('Newer Articles &rarr;', 'textdomain'),
                    'class'     => 'tailwind-custom-pagination'
                )); 
                ?>
            </div>

        <?php else : ?>
            
            <div class="bg-white border border-slate-200/60 rounded-2xl p-12 text-center max-w-xl mx-auto my-12">
                <svg class="w-12 h-12 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008h-.008v-.008z"></path></svg>
                <h3 class="text-base font-bold text-slate-900 tracking-tight mb-1">No Posts Found</h3>
                <p class="text-sm text-slate-500">This author hasn't published any articles yet.</p>
            </div>

        <?php endif; ?>

    </div>
</main>

<?php get_footer(); ?>
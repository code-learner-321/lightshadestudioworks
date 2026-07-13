<?php get_header(); ?>

<main id="primary" class="site-main bg-slate-50 min-h-screen pb-20">
    <?php while ( have_posts() ) : the_post(); ?>
        
        <article id="post-<?php the_ID(); ?>" <?php post_class('w-full'); ?>>
            
            <header class="w-full pt-12 pb-6 md:pt-16 md:pb-8 bg-transparent">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    
                    <?php if ( has_category() ) : ?>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <?php 
                            $categories = get_the_category();
                            foreach ( $categories as $category ) {
                                echo '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" class="inline-block text-xs font-bold tracking-widest uppercase text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full hover:bg-indigo-100 transition duration-200">' . esc_html( $category->name ) . '</a>';
                            }
                            ?>
                        </div>
                    <?php endif; ?>

                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight text-slate-900 leading-tight mb-6">
                        <?php the_title(); ?>
                    </h1>

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 py-4 border-t border-b border-slate-200">
                        
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 h-10 w-10 overflow-hidden rounded-full ring-2 ring-indigo-100 bg-slate-200">
                                <?php echo get_avatar( get_the_author_meta( 'ID' ), 40, '', '', array('class' => 'h-full w-full object-cover rounded-full') ); ?>
                            </div>
                            <div>
                                <span class="text-[11px] font-medium text-slate-400 block uppercase tracking-wider">Written by</span>
                                <span class="text-sm font-semibold text-slate-800 hover:text-indigo-600 transition">
                                    <?php the_author_posts_link(); ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-4 text-xs font-medium text-slate-500">
                            <div class="flex items-center space-x-1">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
                            </div>
                            <span class="w-1 h-1 rounded-full bg-slate-300" aria-hidden="true"></span>
                            <div class="flex items-center space-x-1">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span><?php echo max( 1, round( str_word_count( strip_tags( get_the_content() ) ) / 200 ) ); ?> min read</span>
                            </div>
                        </div>

                    </div>

                </div>
            </header>

            <?php if ( has_post_thumbnail() ) : ?>
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 my-6">
                    <div class="w-2/3 h-80 sm:h-[450px] md:h-[550px] lg:h-[480px] overflow-hidden rounded-2xl shadow-sm border border-slate-200 bg-slate-200">
                        <?php 
                        the_post_thumbnail('full', array(
                            'class' => 'w-full h-full object-cover'
                        )); 
                        ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="prose prose-slate prose-indigo prose-lg max-w-none text-slate-800 leading-relaxed">
                    <?php the_content(); ?>
                </div>

                <footer class="mt-12 pt-6 border-t border-slate-200">
                    <?php get_template_part('entry-footer'); ?>
                </footer>
                
            </div>

        </article>

        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 pt-8 border-t border-slate-200" aria-label="Posts">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                
                <div class="bg-white p-4 rounded-xl border border-slate-200 hover:border-indigo-200 transition-all">
                    <?php if ( $prev_post = get_previous_post() ) : ?>
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block mb-1">← Previous Post</span>
                        <a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" class="text-sm font-bold text-slate-800 hover:text-indigo-600 transition">
                            <?php echo esc_html( get_the_title( $prev_post->ID ) ); ?>
                        </a>
                    <?php endif; ?>
                </div>

                <div class="bg-white p-4 rounded-xl border border-slate-200 hover:border-indigo-200 text-right transition-all">
                    <?php if ( $next_post = get_next_post() ) : ?>
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Next Post →</span>
                        <a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" class="text-sm font-bold text-slate-800 hover:text-indigo-600 transition">
                            <?php echo esc_html( get_the_title( $next_post->ID ) ); ?>
                        </a>
                    <?php endif; ?>
                </div>

            </div>
        </nav>

        <?php if ( comments_open() || get_comments_number() ) : ?>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-16">
                <div class="bg-white border border-slate-200 rounded-2xl p-6 md:p-8 shadow-sm post-comments-wrapper">
                    <h3 class="text-lg font-bold text-slate-900 tracking-tight mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        Discussion (<?php echo get_comments_number(); ?>)
                    </h3>
                    <?php comments_template(); ?>
                </div>
            </div>
        <?php endif; ?>

    <?php endwhile; ?>
</main>

<?php get_footer(); ?>

<?php get_header(); ?>
     <h1 class="site__heading">CSS 177</h1>

<div class="site__blog">
    <main class="site__content">
        <?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>
            <article class="post">
                <h2><?php the_title(); ?></h2>
        
               
                
                <p class="post__meta"> 
                    <?php the_post_thumbnail(); ?>
                    Publié le <?php the_time( get_option( 'date_format' ) ); ?> 
                    par <?php the_author(); ?> • <?php comments_number(); ?>
                </p>
                
                <?php the_excerpt(); ?>
                
                <p>
                    <a href="<?php the_permalink(); ?>" class="post__link">Lire la suite</a>
                </p>
            </article>
            
        <?php endwhile; endif; ?>
        <?php the_posts_pagination(); ?>
    </main>
    <aside class="site__sidebar">
        <ul>
            <?php dynamic_sidebar( 'blog-sidebar' ); ?>
            <li id="search-4" class="widget widget_search">
                <h2 class="widgettitle">Rechercher</h2>
                ...
            </li>

            <li id="text-2" class="widget widget_text">
                <h2 class="widgettitle">A propos</h2>
                ...
        </ul>
    </aside>
</div>
<?php get_footer(); ?>

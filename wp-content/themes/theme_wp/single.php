<?php get_header(); ?>
  <?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>
    
    <article class="post">

      <h1><?php the_title(); ?></h1>

      <div class="post__meta">
        <?php echo get_avatar( get_the_author_meta( 'ID' ), 50 ); ?>
        <p>
          Publié le <?php the_date(); ?>
          par <?php the_author(); ?>
          Dans la catégorie <?php the_category(); ?>
        </p>
      </div>

      <div class="post__content">
        <?php the_content(); ?>
      </div>
      <?php comments_template(); // Par ici les commentaires ?>
    </article>
    


  
  <?php endwhile; endif; ?>
<?php get_footer(); ?>


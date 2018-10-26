<?php 

/*
Plugin Name: WTF Bootstrap Carousel
Description: Ukázkový WordPress Bootstrap Carousel plugin – pro použití se šablonou "laura7".
 
*/

defined( 'ABSPATH' ) OR exit;

add_action( 'init', function() {

	register_post_type('history', 
		array(	
			'label' => 'Historie',
			'public' => false,
			'show_ui' => true,
			'show_in_menu' => true,
			'supports' => array( 'title', 'thumbnail' ),
    ));
    
});

add_shortcode('history', function() {

    $query_atts = array(
        'post_type' => 'history',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC'
    );

    $history_query = new WP_Query( $query_atts );

    if ( ! $history_query->have_posts() ) return 'Žádná historie';

    ob_start();

    ?>
    
    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">

        <!-- Indicators -->
        <ol class="carousel-indicators">
            <?php 
                $i = 0; 
                while( $history_query->have_posts() ) { 
                    $history_query->the_post();  
                    ?>
                        <li data-target="#carousel-example-generic" 
                            data-slide-to="<?= $i ?>" 
                            class="<?= $i == 0 ? 'active': ''; ?>"></li>
                    <?php 
                    $i++; 
                } 
                ?>
        </ol>

        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">
            <?php 
                $i = 0; 
                while( $history_query->have_posts() ) { 
                    $history_query->the_post(); ?>
                        <div class="item <?= $i == 0 ? 'active': ''; ?>">
                            <?php the_post_thumbnail('history') ?>
                            <div class="carousel-caption">
                                <h2><?php the_title(); ?></h2>
                                <h3><?php the_field('description') ?></h3>
                            </div>
                        </div>
                        <?php 
                    $i++;  
                } 
            ?>
        </div>

        <!-- Controls -->
        <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
            <span class="icon-prev" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
            <span class="icon-next" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>

    </div>
   
    <?php  
    wp_reset_postdata(); 
    return ob_get_clean();

});

add_action( 'after_setup_theme', function() {
    add_image_size ( 'history', 750, 500, true );
});

add_action('pre_get_posts', function ( $query ) {
    if (  $query->get('post_type') != 'history' ) return;
    $query->set('orderby', 'title');
    $query->set('order', 'ASC');        
});

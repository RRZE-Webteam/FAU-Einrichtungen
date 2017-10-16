<?php
/**
* @package WordPress
* @subpackage FAU
* @since FAU 1.7
* 
* Comment Functions 
*/





if ( ! function_exists( 'fau_comment' ) ) :
/**
 * Template for comments and pingbacks.
 */
function fau_comment( $comment, $args, $depth ) {
        $GLOBALS['comment'] = $comment;
        global $options;         
        
        switch ( $comment->comment_type ) :
                case '' :
        ?>
        <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
          <div id="comment-<?php comment_ID(); ?>">
            <article itemprop="comment" itemscope itemtype="http://schema.org/UserComments">
              <header>  
                <div class="comment-details">
                    
                <span class="comment-author vcard" itemprop="creator" itemscope itemtype="http://schema.org/Person">
                    <?php if ($options['advanced_comments_avatar']) {
                        echo '<div class="avatar" itemprop="image">';
                        echo get_avatar( $comment, 48); 
                        echo '</div>';   
                    } 
                    printf( __( '%s <span class="says">schrieb am</span>', 'fau' ), sprintf( '<cite class="fn" itemprop="name">%s</cite>', get_comment_author_link() ) ); 
                    ?>
                </span><!-- .comment-author .vcard -->
              

                <span class="comment-meta commentmetadata"><a itemprop="url" href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><time itemprop="commentTime" datetime="<?php comment_time('c'); ?>">
                    <?php
                          /* translators: 1: date, 2: time */
                       printf( __( '%1$s um %2$s Uhr', 'fau' ), get_comment_date(),  get_comment_time() ); ?></time></a> <?php echo __('folgendes','fau');?>:
                  
                </span><!-- .comment-meta .commentmetadata -->
                </div>
              </header>
		     <?php if ( $comment->comment_approved == '0' ) : ?>
                        <em><?php _e( 'Kommentar wartet auf Freischaltung.', 'fau' ); ?></em>
                        <br />
                <?php endif; ?>
                <div class="comment-body" itemprop="commentText"><?php comment_text(); ?></div>
		 <?php edit_comment_link( __( '(Bearbeiten)', 'fau' ), ' ' ); ?>
            </article>
          </div><!-- #comment-##  -->

        <?php
                        break;
                case 'pingback'  :
                case 'trackback' :
        ?>
        <li class="post pingback">
                <p><?php _e( 'Pingback:', 'fau' ); ?> <?php comment_author_link(); edit_comment_link( __('Bearbeiten', 'fau'), ' ' ); ?></p>
        <?php
                        break;
        endswitch;
}
endif;


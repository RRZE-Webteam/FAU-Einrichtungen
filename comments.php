<?php 
global $options;

if ( is_singular() ) wp_enqueue_script( "comment-reply" ); 

?>
<h2 id="comments-title"><?php _e("Kommentare", 'fau'); ?></h2>

<?php
if ( post_password_required() ) : ?>
    <p><?php _e("Dieser Eintrag ist mit einem Passwort geschützt. Bitte geben Sie das Passwort ein, um ihn freizuschalten.", 'fau'); ?></p>
    <?php return;
endif; 
if ( have_comments() ) : ?>
   
    
    <?php if (isset($options['advanced_comments_disclaimer'])) {
	echo '<p class="attention">'.$options['advanced_comments_disclaimer'] .'</p>'."\n";
     } ?>

     <p>   
    <?php printf( _n( 'Ein Kommentar zu <em>"%2$s"</em>', '%1$s Kommentare zu <em>"%2$s"</em>', get_comments_number(), 'fau' ), number_format_i18n( get_comments_number() ), '' . get_the_title() . '' ); ?>:
    </p>
    <?php 
    if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :  
           previous_comments_link( '&larr; '. __( 'Ältere Kommentare', 'fau' ) ); 
           next_comments_link( __( 'Neuere Kommentare', 'fau' ).' &rarr' ); 
    endif; ?>
    <ul>
            <?php wp_list_comments( array(  'style' => 'ul', 'callback' => 'fau_comment' ) ); ?>
    </ul>

    <?php 
     if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : 
             previous_comments_link( '&larr; '.__( 'Ältere Kommentare', 'fau' ) ); 
             next_comments_link( __( 'Neuere Kommentare', 'fau' ). ' &rarr;' ); 
     endif; 
     if ( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' )  ) : ?>
	<p><?php _e("Eine Kommentierung ist nicht mehr möglich.", 'fau'); ?></p>
<?php
    endif; 
 endif; 
     
if (!empty($options['advanced_comments_notes_before'])) {
    $notes = '<p class="comment-notes">'.$options['advanced_comments_notes_before'].'</p>';
	    
     comment_form( array( 'comment_notes_before' => $notes) ); 
} else {
    comment_form();
}

    

?> 
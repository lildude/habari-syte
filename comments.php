<?php if ( !defined( 'HABARI_PATH' ) ) { die('No direct access'); } ?>
		<section id="comments">
			<a href="<?php echo $post->permalink; ?>#comments"><h4><?php echo $post->comments->moderated->count . _n( ' Comment', ' Comments', $post->comments->moderated->count ); ?></h4></a>
<?php if ( $post->comments->moderated->count ) : ?>
    <?php foreach ( $post->comments->comments->moderated as $comment ) :
        $comment_url = ( $comment->url_out == '' ) ? $comment->name_out : '<a href="' . $comment->url_out . '">' . $comment->name_out . '</a>'; ?>
			
				<article id="comment-<?php echo $comment->id; ?>" class="comment<?php if ( $comment->status == Comment::STATUS_UNAPPROVED ) { echo " pending"; } if ( $comment->email == $post->author->email ) { echo " postauthor"; } ?>">
					<div class="comment-content">
						<?php echo $comment->content_out; ?>
					</div>
					<footer>
						<?php if ( $loggedin ) : ?><span class="comment-edit"><a href="<?php URL::out( 'admin', 'page=comment&id=' . $comment->id); ?>">&#x2386;</a></span><?php endif; ?>
						<span class="comment-author"><?php /* &#x21AA; */ ?><?php echo $comment_url; ?></span>
						<time datetime="<?php echo $post->pubdate->text_format('{c}'); ?>" class="comment-time"><?php echo $comment->date->out('d M y @ h:m'); ?></time>			
						<?php if ( $comment->status == Comment::STATUS_UNAPPROVED ) : ?><span class="notice">Your comment is awaiting moderation.</span><?php endif; ?>
					</footer>
				</article>
	<?php endforeach; ?>
<?php endif; ?>
<?php if ( ! $post->info->comments_disabled ) : ?>

<?php if ( Session::has_messages() ) Session::messages_out(); ?>
			<?php $post->comment_form()->out(); ?>
	<?php endif; ?>
		</section>




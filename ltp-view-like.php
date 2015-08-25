<?php
function ltpCurrrentLike() {
	global $wpdb;
	$post_id = get_the_ID();
	$show_ajax_notify = get_option('ltp_show_ajax_notify');
	$show_only_count = get_option('ltp_show_only_count');
	
	$nonce = wp_create_nonce("ltp_vote_nonce");
	$ltp_alllikes= ltp_likecount($post_id);
	$ltp_like_users = GetAllUserslikethePost($post_id);
	$ltp_user_like_data = showLikedUsers($ltp_like_users);
	$ajax_like_link = admin_url('admin-ajax.php?action=ltp_ajax_process&task=like&post_id=' . $post_id . '&nonce=' . $nonce);

	$showLikeBox = '<div class="ltp-box">';
	$showLikeBox .= '<div class="like-box"><a class="likebutton" href="' . $ajax_like_link . '" data-task="like" data-post_id="' . $post_id . '" data-nonce="' . $nonce . '"><span class="icon-like"></span><span class="no-like no-like-' . $post_id . ' like-content">' . $ltp_alllikes . '</span></a>';
	if($show_only_count == 0) {$showLikeBox .= "<div class='likeusers likeusers-" . $post_id . "'>" . $ltp_user_like_data . "</div>"; }
	if($show_ajax_notify == 1) {$showLikeBox .= '<div class="no-msg no-msg-' . $post_id . '"></div>'; }
	$showLikeBox .= '</div></div>';
	return $showLikeBox;
}

function ltpShowLikeBox($content) {
	global $wpdb;

	$likedislikebox = '';
	if (is_single()) {
		$likedislikebox = ltpCurrrentLike();
	}
	$content = $content . $likedislikebox;
	return $content;
}

add_filter(the_content,'ltpShowLikeBox');
?>
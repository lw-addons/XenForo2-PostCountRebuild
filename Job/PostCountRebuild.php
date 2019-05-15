<?php

namespace LiamW\PostCountRebuild\Job;

use XF\Entity\Post;
use XF\Job\AbstractRebuildJob;

class PostCountRebuild extends AbstractRebuildJob
{
	protected function getNextIds($start, $batch)
	{
		$db = $this->app->db();

		return $db->fetchAllColumn($db->limit(
			"
				SELECT user_id
				FROM xf_user
				WHERE user_id > ?
				ORDER BY user_id
			", $batch
		), $start);
	}

	protected function rebuildById($id)
	{
		$this->app->db()->query("
			UPDATE xf_user user SET message_count = (
			SELECT COUNT(*) FROM xf_post post
			INNER JOIN xf_thread thread ON (post.thread_id = thread.thread_id)
			INNER JOIN xf_forum forum ON (thread.node_id = forum.node_id)
			WHERE forum.count_messages = 1
			  AND post.message_state = 'visible' 
			  AND thread.discussion_state = 'visible' 
			  AND post.user_id = user.user_id) WHERE user.user_id = ?
		", $id);
	}

	protected function getStatusType()
	{
		return \XF::phrase('liamw_postcountrebuild_user_post_counts');
	}
}
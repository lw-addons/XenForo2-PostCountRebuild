<?php

namespace LiamW\PostCountRebuild\Job;

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
		/** @var \XF\Entity\User $user */
		$user = $this->app->em()->find('XF:User', $id);
		if (!$user)
		{
			return;
		}

		$user->message_count = $this->app->finder('XF:Post')->where('user_id', $id)
			->where('Thread.Forum.count_messages', 1)->total();
		$user->saveIfChanged();
	}

	protected function getStatusType()
	{
		return \XF::phrase('liamw_postcountrebuild_user_post_counts');
	}
}
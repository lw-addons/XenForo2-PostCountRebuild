<?php

namespace LiamW\PostCountRebuild\Cli\Command\Rebuild;

use XF\Cli\Command\Rebuild\AbstractRebuildCommand;

class RebuildPostCounts extends AbstractRebuildCommand
{
	protected function getRebuildName()
	{
		return 'liamw-postcountrebuild-user-post-counts';
	}

	protected function getRebuildDescription()
	{
		return 'Rebuild user post counts';
	}

	protected function getRebuildClass()
	{
		return 'LiamW\PostCountRebuild:PostCountRebuild';
	}
}
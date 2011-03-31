<?php use_helper('XssSafe') ?>
<?php $postsReversedPurified = $sf_data->get('postsReversed', ESC_XSSSAFE) ?>
<?php echo "<" . "?" ?>xml version="1.0"?>
<rss version="2.0">
	<channel>
		<title><?php printf('Forum: %s', $root['name']) ?></title>
		<link><?php echo url_for('@forum_show_forum?slug=' . $root['slug'], true) ?></link>
		<description><?php echo $root['description'] ?></description>
<?php if (!empty($postsReversed) && count($postsReversed)): ?>
		<pubDate><?php echo date('r', strtotime($postsReversed[0]['updated_at'])) ?></pubDate>
		<lastBuildDate><?php echo date('r', strtotime($postsReversed[0]['updated_at'])) ?></lastBuildDate>
<?php endif; ?>
		<docs>http://www.rssboard.org/rss-specification</docs>
		<generator>sfMaraePlugin</generator>

<?php foreach ($postsReversed as $k => $item): ?>

		<item>
			<title><?php echo $item['title'] ?></title>
			<link><?php echo url_for('@post_show_post?id=' . $item['id'] . '&replies=' . (($item['rgt'] - $item['lft'] - 1)/2), true) ?></link>
			<description><![CDATA[<?php echo $postsReversedPurified[$k]['message'] ?>]]></description>
			<pubDate><?php echo date('r', strtotime($item['created_at'])) ?></pubDate>
			<guid isPermaLink="true"><?php echo url_for('@post_show?id=' . $item['id'], true) ?></guid>
		</item>
<?php endforeach; ?>
	</channel> 
</rss>

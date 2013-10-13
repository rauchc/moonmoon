<?php
include_once(dirname(__FILE__).'/app/app.php');
include_once(dirname(__FILE__).'/app/lib/Cache.php');

if ($Planet->loadOpml(dirname(__FILE__).'/custom/people.opml') == 0) exit;

$Planet->loadFeeds();
$items = $Planet->getItems();
$limit = $PlanetConfig->getMaxDisplay();
$count = 0;
$category = null;
$print = false;
if(isset($_GET['filter']))
{
	if(in_array(urldecode($_GET['filter']),$Planet->getCategories(),true))
	{
		$category = urldecode($_GET['filter']);
	}
}
//header('Content-Type: application/atom+xml; charset=UTF-8');
echo '<?xml version="1.0" encoding="UTF-8" ?>';
?>
<feed xmlns="http://www.w3.org/2005/Atom">
    <title><?=htmlspecialchars($PlanetConfig->getName())?></title>
	<?php
	if($category != null) :?>
		<subtitle><?=htmlspecialchars($category)?></subtitle>
	<?php endif; ?>
    <id><?=$PlanetConfig->getUrl()?></id>
    <link rel="self" type="application/atom+xml" href="<?=$PlanetConfig->getUrl()?>atom.php" />
    <link rel="alternate" type="text/html" href="<?=$PlanetConfig->getUrl()?>" />
    <updated><?=date("Y-m-d\TH:i:s\Z")?></updated>
    <author><name><?=htmlspecialchars($PlanetConfig->getName())?></name></author>

<?php $count = 0; ?>
<?php foreach ($items as $item): ?>
<?php if($category != null) {
	if($item->get_feed()->getCategory() == $category){
		$print = true;
	} else {
		$print = false;
	}
} else {
	$print = true;
}
?>
<?php if($print == true) : ?>
    <entry>
        <title type="html"><?=htmlspecialchars($item->get_feed()->getName())?> : <?=htmlspecialchars($item->get_title())?></title>
        <id><?=htmlspecialchars($item->get_permalink())?></id>
        <link rel="alternate" href="<?=htmlspecialchars($item->get_permalink())?>"/>
        <published><?=$item->get_date('Y-m-d\\TH:i:s+00:00')?></published>
        <updated><?=$item->get_date('Y-m-d\\TH:i:s+00:00')?></updated>
        <author><name><?=($item->get_author() ? $item->get_author()->get_name() : 'anonymous')?></name></author>

        <content type="html"><![CDATA[<?=$item->get_content()?>]]></content>
    </entry>

<?php if (++$count == $limit) break; ?>
<?php endif; ?>
<?php endforeach; ?>

</feed>

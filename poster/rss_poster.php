<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; CHARSET=UTF-8">
</head>
</html>
<?php
require_once '../app_config.php';
require_once '../vk_auth/VKclass.php';

ini_set( 'display_errors', 'Off' );
//инациализация вк
$token="c76ae506cd10e151a03b47f0ca8160552cb64ac8799bfddeecebd44674b4175ff25323df2933c530470fb&expires_in=0&user_id=152223765";
//$token="6727f0401cfebd9eb31265a67e4b695121e7b0eb8490ed30fee1f8bb0167af73268fbf72df21a56c45496";
$delta    = '100';
$app_id   = '48323781';
$group_id = '85303665';
$vk       = new vk( $token, $delta, $app_id, $group_id );

$rss_url = "http://www.bsmu.by/rss/rss.xml";
$rss     = simplexml_load_file( $rss_url );
$items   = $rss->channel->item;
$filter="owner"; $count=sizeof($items)*2;
//получаем последние 32 поста со стенки
$posts = $vk->getPosts($filter, $count);

if ( sizeof($items) ) {
	foreach ( $items as $item ) {
		$flag=1;//флаг разрешения дла отправки поста
		for($i=1; $i<sizeof($posts); ++$i){
			if($posts[$i]->media->share_url == $item->link){
				//проверка, если изменили название статьи, но ссылка осталась той же
				//удаляем текущий пост и делаем новый с той же ссылкой
				if($posts[$i]->text!=$item->title) $vk->deletePost($posts[$i]->id);
				else $flag=0;
				break;
			}
		}
		if($flag) $vk->post(null, null, $item->link);
	}
}
?>
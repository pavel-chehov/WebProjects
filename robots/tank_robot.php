<html>
	<meta charset="UTF-8">
</html>
<?php
require_once 'parserLib.php';
function screen($url) 
{
    //$url = "http://api.s-shot.ru/".$extn."/".$size."/".$format."/?".urlencode($url);
    $url='http://mini.s-shot.ru/2000*10000/1980/jpeg/?https://vk.com/plantro?w=wall-43932139_29045';
    $str = file_get_contents($url);
    file_put_contents("../".'screen.jpeg', $str); // тут лучше указать путь куда сохранять
}

$token
	= '92b73575a455b69bd32a54215038a3a74e7997d73923a364bd93790912b7f576c18b813f440348dfb5321&expires_in=0&user_id=152223765';
$delta    = '100';
$app_id   = '4832378';
$post_id='29045';
$group_id = '43932139';
$post_string='wall-43932139_29045';
$log_file='../tank_log.txt';
$last_num=false;
$vk = new vk( $token, $delta, $app_id, $group_id );

writeLog('start', $log_file);
$comments=$vk->getComments($post_id, 2, 'desc');
for($i=1; $i<sizeof($comments)-1;++$i){
	(int) $comment1=(int) $comments[$i]->text;
	(int) $comment2=(int) $comments[$i+1]->text;
	if($comment1 && $comment2) {
		if(($comment2-$comment1)!=1) $comment2>$comment1?$last_num=$comment2:$last_num=$comment1;
		}
	}
if(!$last_num){
	(int) $last_num=(int) $comments[1]->text;
	if($last_num!=0) --$last_num;
	else $last_num=false;
}
if(rand(0, 100) == 1 && $comments[1]->from_id != '152223765' && $last_num > 0 && $last_num < 25000) {
	if($vk->setOnline()) writeLog('set online', $log_file);
	else writeLog('cant set online', $log_file);
	$count=rand(1,2);//определяет вероятность запостить 2 или 1 пост
	for($i=0; $i < $count; ++$i){
		if($last_num > 0) {
			-- $last_num;
			if ( $vk_comment = $vk->addComment( $last_num, $post_id, null ) ) {
				writeLog( 'add comment: ' . $last_num, $log_file );
			} else {
				writeLog( 'cant add comment ' . $last_num, $log_file );
			}
		}
	}
}
if($last_num > 0 && $last_num < 45) mail( "good-1991@mail.ru", 'Танки!!!', $message );
if($last_num > 20 && $last_num < 35){
	if($vk->getRepost($post_string, NULL)) writeLog("get repost from $post_string", $log_file);
}
//screen(NULL);
writeLog('stop', $log_file);
?>
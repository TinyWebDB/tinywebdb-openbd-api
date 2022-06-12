<?php
//checks ifthe tag post is there and if its been a proper form post
// tagで検索
if(isset($_REQUEST['tag']) ){
	ItemSearch("Books", $_REQUEST['tag']);
}

//Set up the operation in the request
function ItemSearch($SearchIndex, $Keywords){

	// openBD APIの仕様に沿ったリクエスト出力用のPHPスクリプト
	$base_request = "https://api.openbd.jp/v1/get?isbn=$Keywords";
	$items = json_decode(file_get_contents($base_request), false);

	foreach($items as $item){
	    $isbn = $item->summary->isbn;
	    $pubdate = $item->summary->pubdate;
	    $title = $item->summary->title;
	    $tagValue[] = array($title, $pubdate, $isbn); 
	}	

        echo json_encode(array("VALUE", $Keywords, $tagValue));

	$log_message = sprintf("%s:%s\n", date('Y-m-d H:i:s'), "openDB API: ($apiKey) $Keywords -- $base_request");
	$file_name   = 'openBD_' . date('Y-m-d') . '.log';
	error_log($log_message, 3, $file_name);
} 

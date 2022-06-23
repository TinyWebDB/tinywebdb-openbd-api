<?php
//checks ifthe tag post is there and if its been a proper form post
// tagで検索
if(isset($_REQUEST['tag']) ){
	ItemSearch("Books", $_REQUEST['tag']);
}

//Set up the operation in the request
function ItemSearch($SearchIndex, $Keywords){

	$tagName = intval($Keywords);
	// openBD APIの仕様に沿ったリクエスト出力用のPHPスクリプト
	$base_request = "https://api.openbd.jp/v1/get?isbn=$tagName";
	$items = json_decode(file_get_contents($base_request), false);

	foreach($items as $item){
	    $tagValue['isbn'] = $item->summary->isbn;
	    $tagValue['pubdate'] = $item->summary->pubdate;
	    $tagValue['title'] = $item->summary->title;
	    $tagValue['author'] = $item->summary->author;
	    $tagValue['cover'] = $item->summary->cover;
	}	

        echo json_encode(array("VALUE", $tagName, $tagValue));

	$fh = fopen("_data/" . $item->summary->isbn . ".txt", "w") or die("check file write permission.");
        fwrite($fh, json_encode($tagValue));
        fclose($fh);

	$log_message = sprintf("%s:%s\n", date('Y-m-d H:i:s'), "openDB API: ($apiKey) $tagName:$tagValue -- $base_request");
	$file_name   = 'openBD_' . date('Y-m-d') . '.log';
	error_log($log_message, 3, $file_name);
} 

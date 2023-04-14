<?php
//checks ifthe tag post is there and if its been a proper form post
// tagで検索
if(isset($_REQUEST['tag'])){
        $isbn = $_REQUEST['tag'];
        if (validateISBN($isbn)) ItemSearch("Books", $isbn);
}

function validateISBN($isbn) {
    $isbn = str_replace('-', '', $isbn); // ハイフンを削除

    if (!preg_match('/^\d{13}$/', $isbn)) { // 13桁の数字でない場合
        return false;
    }

    $sum = 0;
    for ($i = 0; $i < 12; $i++) {
        $sum += intval($isbn[$i]) * (($i % 2 === 0) ? 1 : 3);
    }

    $check_digit = (10 - ($sum % 10)) % 10;

    return intval($isbn[12]) === $check_digit;
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
        $file_name   = '_log/openBD_' . date('Y-m-d') . '.log';
        error_log($log_message, 3, $file_name);

}

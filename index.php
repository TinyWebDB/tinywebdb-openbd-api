<html>
<head>
     <style type="text/css">
        body {margin-left: 5% ; margin-right: 5%; margin-top: 0.5in;
             font-family: verdana, arial,"trebuchet ms", helvetica, sans-serif;}
        ul {list-style: disc;}
     </style>
     <title>App Inventor openBD API</title>
</head>

<body>
<h2>App-Inventor-Compliant API: openBD API</h2>
<table border=0>
<tr valign="top">
<td><image src="/images/customLogo.gif" width="200" hspace="10"></td>
<td>
<p>
This web service is a proxy to openBD API and is to be used in conjunction with  
	<a　href="http://appinventor.mit.edu">App Inventor　for Android</a>. 
	App Inventor apps can access this service using the TinyWebDB component and setting the ServiceURL to the URL of this site. 
	The service returns a book data.  
	You can explore how this API works by entering a tag of an isbn in the form "xxxxxxxx" in the box below and clicking Get value.
</p>
<p><a href="https://github.com/TinyWebDB/tinywebdb-openbd-api">read more...</a></p>
</td> </tr> 
</table>


    <form action="/getvalue" method="post"
          enctype=application/x-www-form-urlencoded>
       <p>Tag:<input type="text" name="tag" /></p>
       <input type="hidden" name="fmt" value="html">
       <input type="submit" value="Get value">
    </form>

   
    Returned as value to TinyWebDB component: <br/>
   
<?php

$listLog = array();
if ($handler = opendir("./")) {
    while (($sub = readdir($handler)) !== FALSE) {
        if (substr($sub, 0, 10) == "tinywebdb_") {
            $listLog[] = $sub;
        }
    }
    closedir($handler);
}
$listTxt = array();
if ($handler = opendir("_data/")) {
    while (($sub = readdir($handler)) !== FALSE) {
        if (substr($sub, -4, 4) == ".txt") {
            $listTxt[] = $sub;
        }
    }
    closedir($handler);
}

echo "<h3>Recent Tags</h3>";
echo "<table border=1>";
echo "<thead><tr>";
echo "<th> Tag </th>";
echo "<th> ISBN  </th>";
echo "<th> Title </th>";
echo "<th> Author </th>";
echo "<th> Pub Date </th>";
echo "<th> Cover </th>";
echo "<th> Time </th>";
echo "<th> Size </th>";
echo "</tr></thead>\n";
if ($listTxt) {
    sort($listTxt);
    foreach ($listTxt as $sub) {
        echo "<tr>";
        echo "<td><a href=getvalue?tag=" . substr($sub, 0, -4) . ">" .substr($sub, 0, -4) . "</a></td>\n";
	$lines = file("_data/" . $sub);
	$tagValue = json_decode($lines[0], true);
	//  print_r($tagValue);
        echo "<td>" . $tagValue['isbn'] . "</td>\n";
        echo "<td>" . $tagValue['title'] . "</td>\n";
        echo "<td>" . $tagValue['author'] . "</td>\n";
        echo "<td>" . $tagValue['pubdate'] . "</td>\n";
        echo "<td><img width=100 src=" . $tagValue['cover'] . "></td>\n";
        echo "<td>" . date('Y-m-d H:i:s',filemtime("_data/" . $sub)) . "</td>\n";
        echo "<td>" . filesize("_data/" . $sub) . "</td>\n";
        echo "</tr>";
    }
}
echo "</table>";
?>
</body></html>

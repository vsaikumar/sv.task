<html>
<head>
<title>Your Name</title>
 <link rel="stylesheet" href="bootstrap.css">
<link rel="stylesheet" href="styles.css" type="text/css">
 <style>
body{color:black;}
 </style>
</head>
<body>

<?php
error_reporting(0);
$ch=curl_init();
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Fk-Affiliate-Id: adithyaad',
    'Fk-Affiliate-Token: 24518b8a0fca445a8825b2ac27051fbd'
    ));
	$se=$_POST['search_keywords'];
	
?>
  <div id='header'>
<h1 style='font-size:2em;color:#00bfff'> Buy Me </h1>
</div>

<?php
	//echo "<div id='topbanner'><h2 class='text-success'><a href='index.php'>Million Store - </a>
	//<span style='float:right;font-size:1em;'>
	
	//Searching for '".$se."'</span></h2></div><hr width='50%;text-align:center;'>";


	$see=str_replace(' ', '+', $se);
curl_setopt($ch, CURLOPT_URL,"https://affiliate-api.flipkart.net/affiliate/search/json?query=$see&resultCount=5");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$server_output = curl_exec ($ch);
//echo curl_error();
curl_close ($ch);

//var_dump($server_output);
$hh=json_decode($server_output);
?>

<br><br>


	<?php 
echo "<div class='container' style='background-color:#FFD1BA;padding-bottom:20px;'>";

echo "<br><div id='topbanner2'><h2  style='text-align:center;'class='text-success'>
<a href='index.php'>Flipkart Store</a></h2></div><br>";
  

for($i=0;$i<4;$i++){
$title=$hh->productInfoList[$i]->productBaseInfo->productAttributes->title;
$dd=get_object_vars($hh->productInfoList[$i]->productBaseInfo->productAttributes->imageUrls);
$kk=get_object_vars($hh->productInfoList[$i]->productBaseInfo->productAttributes);
$pri=get_object_vars($hh->productInfoList[$i]->productBaseInfo->productAttributes->sellingPrice);
$proid=$hh->productInfoList[$i]->productBaseInfo->productIdentifier->productId;
$desc=substr($hh->productInfoList[$i]->productBaseInfo->productAttributes->productDescription,0,400);


echo "


 <div class='col-md-3' style='border:1px solid gray;height:340px;padding-top:20px;'>

   <center><img src=".$dd['200x200']."></center>
 

    <br>
    <b>Title : $title</b><br>

    <b class='text-danger' style='font-size:2em;'> Price : ".$pri['amount']." /-</b>
   
  
    
     <center><button class='button'>
     <a id='b' href=compare.php?id=$proid target='_blank'>Compare</a>
     </button></center>


 </div>

  
";
?>



<?php 

}


?>
</div>

<?php
  
$endpoint = 'http://svcs.ebay.com/services/search/FindingService/v1'; 
$version = '1.0.0';  
$appid = 'chanduiv-1959-4032-b1f0-133f017b83f9';  
$globalid = 'EBAY-US';  
$query = $se; 
$safequery = urlencode($query);  
$i = '0';  

// Create a PHP array of the item filters you want to use in your request
$filterarray =
  array(
    array(
    'name' => 'MaxPrice',
    'value' => '1000',
    'paramName' => 'Currency',
    'paramValue' => 'USD'),
    array(
    'name' => 'FreeShippingOnly',
    'value' => 'true',
    'paramName' => '',
    'paramValue' => ''),
    array(
    'name' => 'ListingType',
    'value' => array('FixedPrice'),
    'paramName' => '',
    'paramValue' => ''),
  );
function buildURLArray ($filterarray) {
  global $urlfilter;
  global $i;
  // Iterate through each filter in the array
  foreach($filterarray as $itemfilter) {
    // Iterate through each key in the filter
    foreach ($itemfilter as $key =>$value) {
      if(is_array($value)) {
        foreach($value as $j => $content) { // Index the key for each value
          $urlfilter .= "&itemFilter($i).$key($j)=$content";
        }
      }
      else {
        if($value != "") {
          $urlfilter .= "&itemFilter($i).$key=$value";
        }
      }
    }
    $i++;
  }
  return "$urlfilter";
} // End of buildURLArray function

// Build the indexed item filter URL snippet
buildURLArray($filterarray);
$apicall = "$endpoint?";
$apicall .= "OPERATION-NAME=findItemsByKeywords";
$apicall .= "&SERVICE-VERSION=$version";

$apicall .= "&SECURITY-APPNAME=$appid";
$apicall .= "&GLOBAL-ID=$globalid";
$apicall .= "&keywords=$safequery";
$apicall .= "&paginationInput.entriesPerPage=6&paginationOutput.PricePlusShippingHighest";

//$apicall .= "$urlfilter";


$resp = simplexml_load_file($apicall);
echo "<br><br><div class='container' style='background-color:#FFD18C;padding-bottom:20px;'>";
echo "<br><div id='topbanner2'>
<h2 style='text-align:center;'class='text-success'>
<a href='index.php'>eBay Store</a></h2></div><br>";
	
if ($resp->ack == "Success") {
  $results = '';
 
  $count=0;
  foreach($resp->searchResult->item as $item) {

    $count++;

    $pic   = $item->galleryURL;
    $link  = $item->viewItemURL;
    $title = $item->title;
	$title2=str_replace(' ', '+', $title);
    $price = $item->sellingStatus->currentPrice;
    $rating = $item->sellerInfo->feedbackScore;
  
  $price =round($price*66.89);
  
  echo "<div class='col-md-3' style='border:1px solid gray;height:340px;padding-top:20px;'>
         <center> <img src=".$pic."> </center>
       
      <br>
      <b>Title : $title $rating</b><br>
      <b class='text-danger 'style='font-size:2em;'> Price : ".$price." /-</b>

       <center><button class='button'>
    <a id='b' href=compare.php?product_name=".$title2." target='_blank'>Compare</a>
      </button></center>



</div>
";
  if($count==4){
    break;
  }


  }

// close div
  echo "</div><br><br>";

}
else {
  $results  = "<h3>Invalid request";
  $results .= "</h3>";
}
?>





<html>
<head>
<title>eBay Search Results for <?php echo $query; ?></title>
<style type="text/css">body { font-family: arial,sans-serif;} </style>
</head>
<body>

<table>
<tr>
<td>
    <?php echo $results;?>
</td>
</tr>
</table>

</body>
</html>
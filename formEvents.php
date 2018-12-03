<?php
	$apiIn = "https://fareharbor.com/api/external/v1/companies/greenvalleyrange/items/";
	$apiCreds = "?api-app=f0e53a5f-48c1-446c-a0c1-01a21702f301&api-user=7171c7df-a7ab-409a-98cd-46c4268ae315";
	$itemsApi = $apiIn.$apiCreds;
	$bookingApi = "https://fareharbor.com/embeds/book/greenvalleyrange/items/";
	$starting = $_POST['eventsStart'];
	$ending = $_POST['eventsEnd'];
	$dates = '/availabilities/date-range/'.$starting.'/'.$ending.'/';

	function runApi($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$response = curl_exec($ch);
		curl_close($ch);
		global $json;
		$json = json_decode($response, true);
	}

	function makeTime($time) {
		$time = explode("T", $time);
		$day = trim($time[0]);
		$day = strtotime($day);
		global $niceDay;
		$niceDay = date('l, F jS', $day);
		$hour = trim($time[1]);
		$hour = explode("-", $hour);
		$hour = $hour[0];
		$hour = strtotime($hour);
		global $niceTime;
		$niceTime = date('g:ia', $hour);
		return $niceDay;
		return $niceTime;
	}

	function sortArray(&$whichArray) {
		usort($whichArray, function($a, $b){
			return $a['start_at'] <=> $b['start_at'];
		});
	}

	runApi($itemsApi);
	$items = $json['items'];
	array_splice($items, 0, 4);
  	$allEvents = [];
	foreach ($items as $key => $value) {
		$namePrice = $items[$key]['name'];
		if( strpos($namePrice,':') !== false ) { 
			$namePrice = explode(":", $namePrice);
			$name = trim($namePrice[0]);
			$price = trim($namePrice[1]);
		} else {
			$name = trim($namePrice);
			$price = 'Free!';
		};
		$image = $items[$key]['image_cdn_url'];
		$itemID = $items[$key]['pk'];
		$descriptionText = $items[$key]['description_safe_html'];
		$howMany = preg_match_all('#<hr>\R<p>(.*?)</p>#', $descriptionText, $matches);
		$description = '';
		for($i = 0; $i < $howMany; $i++) {
			$description .= '<p style="margin:0 0 10px 0!important;line-height:1.375em!important;font-family:\'Fira Sans\',Helvetica,sans-serif!important;font-weight:300!important;">'.$matches[1][$i].'</p>';
		}

  		$availApi = $apiIn.$itemID.$dates.$apiCreds;
  		runApi($availApi);
  		$availabilities = $json['availabilities'];
	
  		foreach($availabilities as $key => $value) {
  			$start = $availabilities[$key]['start_at'];
  			$end = $availabilities[$key]['end_at'];
  			makeTime($start);
  			$day = $niceDay;
  			$startTime = $niceTime;
  			makeTime($end);
  			$endTime = $niceTime;
  			$hours = $startTime.' - '.$endTime;
  			$bookingID = $availabilities[$key]['pk'];
  			$bookingLink = $bookingApi.$itemID.'/availability/'.$bookingID.'/book/';
  			// ORIGINAL $bookingLink = $bookingApi.$itemID.'/availability/'.$bookingID.'/book/?flow=6025';
  			$oneEvent = array( "id" => $itemID, "name" => $name, "price" => $price, "image" => $image, "description" => $description, "day" => $day, "hours" => $hours, "bookIt" => $bookingLink, "start_at" => $start );
  			if (isset($day)) {
          array_push($allEvents, $oneEvent);
        }
  		}
	}
	sortArray($allEvents); 

	foreach($allEvents as $key => $value) {
		$ename = $allEvents[$key]['name'];
		$eprice = $allEvents[$key]['price'];
		$eimage = $allEvents[$key]['image'];
		$edescription = $allEvents[$key]['description'];
		$eday = $allEvents[$key]['day'];
		$ehours = $allEvents[$key]['hours'];
		$ebookit = $allEvents[$key]['bookIt'];
	
$listItem = <<<LISTITEM
	<li>
		<div class="image" data-count="$key" style="background-image: url($eimage) no-repeat;">
		</div>
		<div class="text">
			<p><span class="day">$eday</span>&nbsp;&bull;&nbsp;<span class="hours">$ehours</span></p>
			<h4>$ename</h4>
			<p><span class="price">$eprice</span><span class="bookit"><a href="$ebookit">Book It!</a></span></p>
		</div>
	</li>
LISTITEM;

$gridItemBooking = <<<GRIDITEMBOOKING
	<div>
		<div class="image" data-count="$key" style="background-image: url($eimage);">
		</div>
		<div class="text">
			<p><span class="day">$eday</span>&nbsp;&bull;&nbsp;<span class="hours">$ehours</span></p>
			<h4>$ename</h4>
			<div class="description">$edescription</div>
			<p><span class="price">$eprice</span><span class="bookit"><a href="$ebookit">Book It!</a></span></p>
		</div>
	</div>
GRIDITEMBOOKING;

$gridItem = <<<GRIDITEM
	<div>
		<div class="image" data-count="$key" style="background-image: url($eimage);">
		</div>
		<div class="text">
			<p><span class="day">$eday</span>&nbsp;&bull;&nbsp;<span class="hours">$ehours</span></p>
			<h4>$ename</h4>
			<div class="description">$edescription</div>
			<p><span class="price">$eprice</span><span class="bookit"><a href="$ebookit">Book It!</a></span></p>
		</div>
	</div>
GRIDITEM;
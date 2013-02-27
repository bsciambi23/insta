<?php

//returns a big old hunk of JSON from a non-private IG account page.
function scrape_insta($username) {
	$insta_source = file_get_contents('http://instagram.com/'.$username);
	$shards = explode('window._jscalls = [', $insta_source);
	$insta_json = explode(',

  ["infra\/react_helper"', $shards[1]);  //YES that whitespace needs to be there. 
	$insta_array = json_decode($insta_json[0], TRUE);
	return $insta_array;
}


//Supply a username
$my_account = 'cosmocatalano'; 

//Do the deed
$results_array = scrape_insta($my_account);

//An example of where to go from there
$latest_array = $results_array[2][2]['userMedia'][0];

echo 'Latest Photo:<br/>';
echo '<a href="'.$latest_array['link'].'"><img src="'.$latest_array['images']['low_resolution']['url'].'"></a></br>';
echo 'Likes: '.$latest_array['likes']['count'].' - Comments: '.$latest_array['comments']['count'].'<br/>';
echo 'Taken at '.$latest_array['location']['name'].'<br/>';

//Heck, lets compare it to a useful API, just for kicks.
echo '<img src="http://maps.googleapis.com/maps/api/staticmap?markers=color:red%7Clabel:X%7C'.$latest_array['location']['latitude'].','.$latest_array['location']['longitude'].'&zoom=13&size=300x150&sensor=false">';
?>
<?php
require_once '../vendor/autoload.php';

//Load Twig templating environment
$loader = new Twig_Loader_Filesystem('../templates/');
$twig 	= new Twig_Environment($loader, ['debug' => true]);
$datas 	= [];

//Get the episodes from the API
try {

	$client = new GuzzleHttp\Client();
	$res 	= $client->request('GET', 'http://3ev.org/dev-test-api/');
	$data 	= json_decode($res->getBody(), true);

} catch (GuzzleHttp\Exception\ServerException $e) {
	$datas["status"] = "error";
}



function sortBySeasonAndEpisode($a,$b){

	if($a['season'] == $b["season"]) {
		return $a['episode'] > $b['episode'];
	}

	return $a["season"] > $b["season"];
}


function printMovies($movies) {
	echo "<pre>";
	foreach ($movies as $movie) {
		print_r($movie["season"] . "\n");
	}
	exit;
}

if(isset($data)) {
	$datas["episodes"] = $data;
	usort($data, "sortBySeasonAndEpisode");
}




//Render the template
echo $twig->render('page.html', $datas);

<?php
set_time_limit(4500);

function __autoload($class)
{
	require dirname(__FILE__).'/class/'.strtolower($class).'.php';
}
// create 100 new, random robots
$gen = array();
$gen = buildGeneration();
$fittest[1] = selectFittest($gen);
// make more generations and select fittest
for($i = 2; $i <= 200; $i++) {
	$gen = buildGeneration($fittest[$i-1][0]['robot'], $fittest[$i-1][1]['robot']);
	$fittest[$i] = selectFittest($gen);
}
echo "<pre>";
foreach($fittest as $genNbr => $bot) {
	echo "$genNbr: ".$bot[0]['avg'].PHP_EOL;
}
echo "</pre>";

function buildGeneration(Robot $mom=null, Robot $dad=null)
{
	$generation = array();
	for($i = 0; $i < 100; $i++) {
		if(is_null($mom) or is_null($dad)) {
			$generation[$i]['robot'] = new Robot();
		}
		else {
			$generation[$i]['robot'] = new Robot($mom, $dad);
		}
	}
	return $generation;
}

function selectFittest(array $generation)
{
	// run each through 100 tests
	for($i = 0; $i < 80; $i++) {
		$test = new Test(new Map());
		foreach($generation as $ix => $bot) {
			$generation[$ix]['scores'][$i] = $test->runTest($bot['robot'], 150);
		}
		unset($test);
	}
	foreach($generation as $ix => $bot) {
		$generation[$ix]['avg'] = array_sum($generation[$ix]['scores']) / count($generation[$ix]['scores']);
	}
	usort($generation, 'sortByAvg');
	return array_slice($generation, 0, 2);
}

/**
 * Sort the two best genomes to the top
 * @return int
 * @param array $a
 * @param array $b
 */
function sortByAvg(Array $a, Array $b) {
	return ($a['avg'] < $b ['avg']);
}
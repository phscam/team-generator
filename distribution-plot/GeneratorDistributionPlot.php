<?php
require __DIR__ .  '/../vendor/autoload.php';

use Amenadiel\JpGraph\Plot;
use Amenadiel\JpGraph\Graph;
use TeamGenerator\Generator\Generator;


const MEMBER_COUNT = 7;
const TEAM_COUNT = 3;
const MAX_ITERATIONS = 10000000;


function hashTeams($teams)
{
  $teamsHashes = collect([]);

  foreach($teams as $team) {
    $teamsHashes->push($team->hash());
  }

  return $teamsHashes->join("");
}


$members = [];
for($i = 1; $i <= MEMBER_COUNT ; $i++) {
  $members[$i] = $i;
}

$generator = new Generator();
$generator->init(TEAM_COUNT, $members);

$counts = [];
for($i = 0; $i < MAX_ITERATIONS; $i++) {
  $teams = $generator->randomTeams();
  $teamsHash = hashTeams($teams);

  if(!array_key_exists($teamsHash, $counts)) {
    $counts[$teamsHash] = 0;
  }

  $counts[$teamsHash] += 1;
}

$mean = collect($counts)->avg();


$plot = new Plot\LinePlot(collect($counts)->values()->toArray());
$plot->SetColor('blue');
$plot->SetCenter();

$graph = new Graph\Graph(1000, 600);
$graph->Add($plot);
$graph->setScale('intlin', 0, $mean+100);

$graph->Stroke();
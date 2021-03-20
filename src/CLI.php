<?php

namespace TeamGenerator;
use TeamGenerator\Generator\Generator;
use TeamGenerator\Generator\Exceptions\TooFewMembersException;

class CLI
{
  private $generator;


  public function __construct($generator) {
    $this->generator = $generator;
  }


  public function run()
  {
    echo "---- Zufällige Teams ----\n\n";

    $amountTeams = $this->readAmountTeams();

    $teamMembers = $this->readTeamMembers();

    $generatedTeams = $this->generateTeams($amountTeams, $teamMembers);

    echo "\n\n";

    foreach( $generatedTeams as $team ) {
      echo $team->toString() . "\n";
    }
  }


  private function readAmountTeams()
  {
    $amountTeams = -1;
    $inputValid = false;

    do {
      $amountTeams = readline("Anzahl an Teams: ");
      $inputValid = ctype_digit($amountTeams) && $amountTeams > 0;

      if( !$inputValid ) {
        echo "Fehlerhafte Eingabe: " . $amountTeams . " ist keine ganze Zahl größer 0.\n\n";
      }
    } while ( !$inputValid );

    return $amountTeams;
  }


  private function readTeamMembers()
  {
    echo "Namen eingeben (Abbruch = leere Eingabe):\n";

    $teamMembers = collect([]);
    $abort = false;

    do {
      $name = readline();
      $abort = ctype_space($name) || (!$name && !ctype_digit($name));

      if( !$abort ) {
        $teamMembers->push($name);
        echo "Weiterer Name:\n";
      }
    } while( !$abort );

    return $teamMembers->toArray();
  }


  private function generateTeams($amountTeams, $teamMembers)
  {  
    try {
      $this->generator->init($amountTeams, $teamMembers);
    }
    catch( TooFewMembersException $e ) {
      $missingMembers = $e->getSatisfyingMembers() - $e->getActualMembers();

      echo "Zu wenige Mitglieder eingegeben um " . $e->getActualTeams() . " Team(s) zu generieren.\n";
      echo "Entweder die Anzahl der Teams auf max. " . $e->getSatisfyingTeams() . " reduzieren oder \n";
      echo "noch " . $missingMembers . " Mitglied(er) ergänzen.\n\n"; 

      $amountTeams = $this->readAmountTeams(); 

      if( ! ($amountTeams <= $e->getSatisfyingTeams()) ) {
        $newMembers = $this->readTeamMembers();
        $teamMembers = array_merge($teamMembers, $newMembers);
      }

      return $this->generateTeams($amountTeams, $teamMembers);
    }

    return $this->generator->randomTeams();
  }
}
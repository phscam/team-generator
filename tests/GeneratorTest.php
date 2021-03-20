<?php

use PHPUnit\Framework\TestCase;
use TeamGenerator\Generator\Generator;
use TeamGenerator\Generator\Exceptions\TooFewMembersException;


class GeneratorTest extends TestCase
{
  const MIN_AMOUNT_TEAMS = 1;
  const MAX_AMOUNT_TEAMS = 20;
  const MIN_AMOUNT_MEMBERS = 1;
  const MAX_AMOUNT_MEMBERS = 40;

  public function testGenerateRandom()
  { 
    $generator = new Generator();

    for($i = self::MIN_AMOUNT_MEMBERS; $i <= self::MAX_AMOUNT_MEMBERS; $i++) {
      for($j = self::MIN_AMOUNT_TEAMS; $j <= self::MAX_AMOUNT_TEAMS; $j++) {
        $members = $this->getMembers($i);
        
        try {
          $generator->init($j, $members);
        }
        catch( TooFewMembersException $e ) {
          $this->assertTooFewMembers($i, $j, $e, $generator::MIN_TEAM_SIZE);

          continue;
        }

        $teams = $generator->randomTeams();

        $this->assertGeneratedTeams($j, $members, $teams, $generator->getMaxTeamSize());
      }
    }
  }

  private function assertTooFewMembers($memberCount, $teamCount, $exception, $minTeamSize)
  {
    $minMemberCount = $minTeamSize * $teamCount;
    $this->assertLessThan(
      $minMemberCount,
      $memberCount,
      "Failed with valid memberCount: " . $memberCount . " actual members, " .  $minMemberCount . " min. members\n"
    );

    $this->assertEquals(
      $memberCount,
      $exception->getActualMembers(),
      "Invalid TooFewMembersException: " . $exception->getActualMembers() . " members according to exception, " . $memberCount . " actual members\n"
    );

    $satisfyingMembers = $minTeamSize * $teamCount;
    $this->assertEquals(
      $satisfyingMembers,
      $exception->getSatisfyingMembers(),
      "Invalid TooFewMembersException: " . $exception->getSatisfyingMembers() . " satisfying members according to exception, " . $satisfyingMembers . " actual satisfying members\n"
    );

    $this->assertEquals(
      $teamCount,
      $exception->getActualTeams(),
      "Invalid TooFewMembersException: " . $exception->getActualTeams() . " teams according to exception, " . $teamCount . " actual teams\n"
    );

    $satisfyingTeams = floor($memberCount/$minTeamSize);
    $this->assertEquals(
      $satisfyingTeams,
      $exception->getSatisfyingTeams(),
      "Invalid TooFewMembersException: " . $exception->getSatisfyingTeams() . " satisfying teams according to exception, " . $satisfyingTeams . " actual satisfying teams\n"
    );
  }

  private function assertGeneratedTeams($amountTeams, $members, $teams, $maxTeamSize)
  {
    $this->assertEquals(
      $amountTeams,
      count($teams),
      "Amount of generated teams does not match expected amount (" . count($members) . ")"
    );

    foreach($teams as $team) {
      /** teams are allowed to have maxTeamSize or maxTeamSize-1 members */
      if( $team->getSize() != $maxTeamSize ) {
        $this->assertEquals(
          $maxTeamSize - 1,
          $team->getSize(),
          "Team with invalid member count generated"
        );
      }
    }

    /** If there are duplicated members then the unique $generatedMembers will have less entries than $members */
    $generatedMembers = collect($teams)->map(function($team) {
      return $team->getMembers();
    })->collapse()->unique()->toArray();

    $this->assertEquals(
      count($members),
      count($generatedMembers),
      "There are duplicated members in generated teams - unique member counts differ"
    );
  }

  private function getMembers($count)
  {
    $members = [];
    for($i = 0; $i < $count; $i++) {
      $members[$i] = "Member " . ($i+1);
    }

    return $members;
  }
}
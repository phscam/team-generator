<?php 

namespace TeamGenerator\Generator;
use TeamGenerator\Generator\Exceptions\TooFewMembersException;
use TeamGenerator\Model\Team;

class Generator
{
  const MIN_TEAM_SIZE = 1;
  private $members;
  private $amountTeams;
  

  public function init($amountTeams, $members)
  {
    if( count($members) / $amountTeams < self::MIN_TEAM_SIZE ) {
      $requriedMembers = $amountTeams * self::MIN_TEAM_SIZE;
      $requiredTeams = floor(count($members) / self::MIN_TEAM_SIZE);
      
      throw new TooFewMembersException($requriedMembers, count($members), $requiredTeams, $amountTeams);
    }

    $this->amountTeams = $amountTeams;
    $this->members = $members;
  }


  public function randomTeams()
  {
    $shuffledMembers = collect($this->members)->shuffle();

    $baseTeamSize = $this->getMinTeamSize();
    $amountBaseMembers = $this->amountTeams * $baseTeamSize;

    $additionalMembers = $shuffledMembers
      ->take($amountBaseMembers - count($shuffledMembers))
      ->values(); // reindex

    return $shuffledMembers
      ->take($amountBaseMembers)
      ->chunk($baseTeamSize)
      ->map(function($members, $key) use ($additionalMembers) {
        $additionalMember = $additionalMembers->get($key);

        if($additionalMember) {
          $members->push($additionalMember);
        }

        return new Team("Team " . ($key+1), $members->toArray());
      })->toArray();
  }


  public function getMaxTeamSize()
  {
    return ceil(count($this->members) / $this->amountTeams);
  }

  public function getMinTeamSize()
  {
    return floor(count($this->members) / $this->amountTeams);
  }
}
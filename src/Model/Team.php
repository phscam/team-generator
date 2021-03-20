<?php
namespace TeamGenerator\Model;

class Team
{
  private $name;
  private $members;


  public function __construct($name, $members)
  {
    $this->name = $name;
    $this->members = $members;
  }


  public function toString()
  {
    return $this->name . ': ' . implode(', ', $this->members);
  }


  public function getName()
  {
    return $this->name;
  }


  public function getMembers()
  {
    return $this->members;
  }

  
  public function getSize()
  {
    return count($this->members);
  }

  public function hash()
  {
    $stringID = collect($this->members)->push($this->name)->join("");

    return hash("md5", $stringID);
  }
}
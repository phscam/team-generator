<?php

namespace TeamGenerator\Generator\Exceptions;
use Exception;

class TooFewMembersException extends Exception
{
    private $satisfyingMembers;
    private $actualMembers;
    private $satisfyingTeams;
    private $actualTeams;


    public function __construct($satisfyingMembers, $actualMembers, $satisfyingTeams, $actualTeams, $code = 0, Exception $previous = null)
    {
        $message = "Too few members given to generate Teams properly.\n";
        $message .= "There are at least " . $satisfyingMembers . "member(s) needed for " . $actualTeams . " Teams.\n";
        $message .= "Actual members: " . $actualMembers;

        $this->satisfyingMembers = $satisfyingMembers;
        $this->actualMembers = $actualMembers;
        $this->satisfyingTeams = $satisfyingTeams;
        $this->actualTeams = $actualTeams;

        parent::__construct($message, $code, $previous);
    }


    public function __toString() 
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }


    public function getSatisfyingMembers() 
    {
        return $this->satisfyingMembers;
    }


    public function getActualMembers()
    {
        return $this->actualMembers;
    }


    public function getSatisfyingTeams()
    {
        return $this->satisfyingTeams;
    }


    public function getActualTeams()
    {
        return $this->actualTeams;
    }    
}
<?php

/**
 * Subclass for representing a row from the 'adjudicator_allocations' table.
 *
 * 
 *
 * @package lib.model
 */ 
class AdjudicatorAllocation extends BaseAdjudicatorAllocation
{
	const ADJUDICATOR_TYPE_CHAIR = 1;
	const ADJUDICATOR_TYPE_PANELIST = 2;
	const ADJUDICATOR_TYPE_TRAINEE = 3;
	
    public function getAdjudicatorName($conn = null){
            return $this->getAdjudicator()->getName();
    }
	
    public function getTeamScoreSheet($position, $conn = null)
    {
        $c = new Criteria();
        $c->add(DebateTeamXrefPeer::POSITION, $position);
        $teamScoreSheets = $this->getTeamScoreSheetsJoinDebateTeamXref($c, $conn);
        
        return $teamScoreSheets[0];
    }
    
    public function getTeamByScore($score, $conn = null)
    {
        $c = new Criteria();
        $c->add(TeamScoreSheetPeer::SCORE, $score);
        $teamScoreSheets = $this->getTeamScoreSheetsJoinDebateTeamXref($c, $conn);
        
        return $teamScoreSheets[0]->getDebateTeamXref($conn)->getTeam($conn);
    }
    
    //gets the trainee allocations to the same debate as this adjudicator allocation
    //but remove all trainee allocations that already have feedback
    public function getTraineeAllocationsWithoutFeedback($con = null)
    {
            $traineeAllocations = self::getTraineeAllocations($con);            
            $traineeAllocationsWithoutFeedback = array();
            $c = new Criteria();
            $c->add(AdjudicatorAllocationPeer::ID,self::getId());
            
            foreach($traineeAllocations as $traineeAllocation)
            {               
                if(count($traineeAllocation->getAdjudicator($con)->getAdjudicatorFeedbackSheetsJoinAdjudicatorAllocation($c, $con)) == 0)
                {
                    $traineeAllocationsWithoutFeedback[] = $traineeAllocation;
                }
            }   
            return $traineeAllocationsWithoutFeedback;
    }   
    
    //return the trainees assigned to the same debate as this adjudicator allocation
    public function getTraineeAllocations($con = null)
    {
        
        if($con==null)
            {
                    $con = Propel::getConnection();
            }		
            $stmt = $con->createStatement();
            $query = "select adjudicator_allocations.* from adjudicator_allocations where debate_id = %d and type = %d";
            $query = sprintf($query,  $this->getDebateId(), AdjudicatorAllocation::ADJUDICATOR_TYPE_TRAINEE);
            $rs = $stmt->executeQuery($query, ResultSet::FETCHMODE_NUM);
            $traineeAllocations = AdjudicatorAllocationPeer::populateObjects($rs);
            
            return $traineeAllocations;        
    }
        
    public function isComplete($conn = null)
    {
        return (
          $this->countTeamScoreSheets(null, false, $conn) == 
          $this->getDebate($conn)->countDebateTeamXrefs(null, false, $conn)
        );
    }
    
    public function hasSpeakerScoreSheetForDebateAndSpeakerPosition($speakingPosition, $debateTeamXref, $conn = null)
    {
        $c = new Criteria();
        $c->add(SpeakerScoreSheetPeer::SPEAKING_POSITION, $speakingPosition);
        $c->add(SpeakerScoreSheetPeer::DEBATE_TEAM_XREF_ID, $debateTeamXref->getId());
        
        if($this->countSpeakerScoreSheets($c, $conn) > 1)
        {
            throw new Exception("More than 1 speaker in the position '".$speakingPosition."' for debate with id '".$debateTeamXref->getDebateId()."'");
        }
        else if($this->countSpeakerScoreSheets($c, $conn) < 1)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    
    public function getSpeakerScoreSheetByDebateAndSpeakerPosition($speakingPosition, $debateTeamXref, $conn = null)
    {
        $c = new Criteria();
        $c->add(SpeakerScoreSheetPeer::SPEAKING_POSITION, $speakingPosition);
        $c->add(SpeakerScoreSheetPeer::DEBATE_TEAM_XREF_ID, $debateTeamXref->getId());
        $speakerScoreSheets = $this->getSpeakerScoreSheets($c, $conn);
        
        if(count($speakerScoreSheets) > 1)
        {
            throw new Exception("More than 1 speaker in the position '".$speakingPosition."' for debate with id '".$debateTeamXref->getDebateId()."'");
        }
        else if(count($speakerScoreSheets) < 1)
        {
            return null;
        }
        else
        {
            return $speakerScoreSheets[0];
        }
    }	
		
    public function getTeamTotalSpeakerScore($debateTeamXref, $conn = null)
    {
        $c = new Criteria();
        $c->add(SpeakerScoreSheetPeer::DEBATE_TEAM_XREF_ID, $debateTeamXref->getId());
        
        $totalSpeakerScore = 0;
        foreach($this->getSpeakerScoreSheets($c, $conn) as $speakerScoreSheet)
        {
            $totalSpeakerScore += $speakerScoreSheet->getScore();
        }
        
        return $totalSpeakerScore;
    }
}

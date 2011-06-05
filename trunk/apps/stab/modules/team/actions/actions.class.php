<?php
// auto-generated by sfPropelCrud
// date: 2008/04/20 18:59:53
?>
<?php

/**
 * team actions.
 *
 * @package    stab
 * @subpackage team
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 3335 2007-01-23 16:19:56Z fabien $
 */
class teamActions extends sfActions
{
  public function executeIndex()
  {
    return $this->forward('team', 'list');
  }

  public function executeViewRankings()
  {
	$this->teamScores = TeamScorePeer::getTeamsInRankedOrder();
  }
  
  public function executeViewTeamsMet()
  {
	$this->teams = TeamPeer::doSelect(new Criteria());
  }
  
  public function executeViewSpeakerRankings()
  {
	$this->speakerScores = SpeakerScorePeer::getDebatersInOrder();
  }

  public function executeList()
  {
    $this->teams = TeamPeer::doSelect(new Criteria());
  }

  public function executeShow()
  {
    $this->team = TeamPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($this->team);
  }

  public function executeCreate()
  {
    $this->team = new Team();
    
    for($i = 0; $i < Team::getTeamSize(); $i++)
    {
        $debater = new Debater();
        $this->team->addDebater($debater);
    }
    
    $this->setTemplate('edit');
  }

  public function executeEdit()
  {
    $this->team = TeamPeer::retrieveByPk($this->getRequestParameter('id'));
    $this->forward404Unless($this->team);
  }

  public function executeUpdate()
  {
	$propelConn = Propel::getConnection();
	try
	{
		$propelConn->begin();   
        if (!$this->getRequestParameter('id'))
        {
          $team = new Team();
        }
        else
        {
          $team = TeamPeer::retrieveByPk($this->getRequestParameter('id'), $propelConn);
          $this->forward404Unless($team);
        }
        
        $team->setId($this->getRequestParameter('id'));
        $team->setName($this->getRequestParameter('name'));
        $team->setInstitutionId($this->getRequestParameter('institution_id') ? $this->getRequestParameter('institution_id') : null);
        $team->setSwing($this->getRequestParameter('swing', 0));
        $team->setActive($this->getRequestParameter('active', 0));
        $team->save($propelConn);		
        
        foreach($this->getRequestParameter('debaters') as $aDebater)
        {
            if(!($aDebater['debater_id']))
            {
                $debater = new Debater();
            }
            else
            {
                $debater = DebaterPeer::retrieveByPk($aDebater['debater_id'], $propelConn);
            }
            
            $debater->setName($aDebater['name']);
            $debater->setTeam($team);
            
            $debater->save($propelConn);		
        }		
        
        $propelConn->commit();
    }
    catch(Exception $e)
    {
        $propelConn->rollback();
        throw $e;
    }
    
    return $this->redirect('team/show?id='.$team->getId());
  }

  public function executeDelete()
  {
    $team = TeamPeer::retrieveByPk($this->getRequestParameter('id'));

    $this->forward404Unless($team);
		
    $team->delete();

    return $this->redirect('team/list');
  }
}

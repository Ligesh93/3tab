<?php
// auto-generated by sfPropelCrud
// date: 2008/04/20 19:00:01
?>


<h1>The Matchups for <?php echo $round->getName() ?></h1>
<table>
<thead>
<tr>
  <th>Venue</th>
  <th>Gov</th>
  <th>Opp</th>
  <th>Chair</th>
  <th>Panelist</th>
  <th>Panelist</th>
</tr>
</thead>
<tbody>
<?php foreach ($debates as $debate): ?>
<tr>
    <td><?php echo VenuePeer::retrieveByPk($debate->getVenueId())->getName() ?></td>
	<td>
		<?php 
			$c = new Criteria();
			$c->add(DebateTeamXrefPeer::DEBATE_ID, $debate->getId());
			$c->add(DebateTeamXrefPeer::POSITION, 1);
			$xref = DebateTeamXrefPeer::doSelect($c);
			
			echo TeamPeer::retrieveByPk($xref[0]->getTeamId())->getName();
		?>
	</td>
	<td>
		<?php 
			$c = new Criteria();
			$c->add(DebateTeamXrefPeer::DEBATE_ID, $debate->getId());
			$c->add(DebateTeamXrefPeer::POSITION, 2);
			$xref = DebateTeamXrefPeer::doSelect($c);
			
			echo TeamPeer::retrieveByPk($xref[0]->getTeamId())->getName();
		?>
	</td>
  </tr>
<?php endforeach; ?>


</tbody>
</table>

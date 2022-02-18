
<?php
if(!defined('INITIALIZED'))
	exit;
$order = $_REQUEST['order'];
if($order == 'name') {
	$orderby = 'name';
}
if($order == 'level') {
	$orderby = 'level';
}
if($order == 'vocation') {
	$orderby = 'vocation';
}
if(empty($orderby)) {
	$orderby = 'name';
}
if(count($config['site']['worlds']) > 1)
{
	$worlds .= '<i>Select world:</i> ';
	foreach($config['site']['worlds'] as $idd => $world_n)
	{
		if($idd == (int) $_GET['world'])
		{
			$world_id = $idd;
			$world_name = $world_n;
		}
	}
}
if($idd == (int) $_GET['world'])
{
	$world_id = $idd;
	$world_name = $world_n;
}
if(!isset($world_id))
{
	$world_id = 0;
	$world_name = $config['server']['serverName'];
}
if(count($config['site']['worlds']) > 1)
{
	$main_content .= '<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=100%><TR><TD>
		<FORM ACTION="?subtopic=whoisonline" METHOD=get><INPUT TYPE=hidden NAME=subtopic VALUE=whoisonline><INPUT TYPE=hidden NAME=list VALUE=experience>
		<TABLE WIDTH=100% BORDER=0 CELLSPACING=1 CELLPADDING=4><TR><TD BGCOLOR="'.$config['site']['vdarkborder'].'" CLASS=white><B>World Selection</B></TD></TR><TR><TD BGCOLOR="'.$config['site']['lightborder'].'">
		<TABLE BORDER=0 CELLPADDING=1><TR><TD>World: </TD><TD><SELECT SIZE="1" NAME="world"><OPTION VALUE="" SELECTED>(choose world)</OPTION>';
		foreach($config['site']['worlds'] as $id => $world_n)
		{
			$main_content .= '<OPTION VALUE="'.$id.'">'.$world_n.'</OPTION>';
		}
		$main_content .= '</SELECT> </TD><TD><INPUT TYPE=image NAME="Submit" ALT="Submit" SRC="'.$layout_name.'/images/buttons/sbutton_submit.gif" BORDER=0 WIDTH=120 HEIGHT=18>
		</TD></TR></TABLE></TABLE></FORM></TABLE><br>';
}
$playercast = $SQL->query('SELECT cast_name FROM live_casts WHERE player_id > 0 ORDER BY '.$orderby);
$player_online_data = $SQL->query('SELECT * FROM players WHERE id= '.$playercast['player_id'].' ORDER BY '.$orderby);
$number_of_players_online = 0;
foreach($player_online_data as $player)
{
	$number_of_players_online++;
	if($config['site']['show_flag'])
	{
		$account = $SQL->query('SELECT account_id FROM players WHERE id = '.$playercast['player_id'].'')->fetch();
		$flag = '<image src="images/flags/'.$account['flag'].'.png"/> ';
	}
	if(is_int($number_of_players_online / 2)) 
	{
		$bgcolor = $config['site']['darkborder'];
	}
	else
	{
		$bgcolor = $config['site']['lightborder'];
	}
	$players_rows .= '
	<TR BGCOLOR='.$bgcolor.'>
		<TD WIDTH=40%>'.$flag.'<A HREF="index.php?subtopic=characters&name='.urlencode($playercast['name']).'">'.$playercast['name'].'</A><br/>'.$player['level'].' '.$vocation_name[$player['world_id']][$player['promotion']][$player['vocation']].'</TD>
		<TD WIDTH=20%><font color="#008000">ONLINE</font></TD>
	</TR>';
}
if($number_of_players_online == 0) 
{
	//server status - server empty
	$main_content .= '<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR="'.$config['site']['vdarkborder'].'"><TD CLASS=white><B>Server Status</B></TD></TR><TR BGCOLOR='.$config['site']['darkborder'].'><TD><TABLE BORDER=0 CELLSPACING=1 CELLPADDING=1><TR><TD>Currently there are no active casts on '.$config['server']['serverName'].'.</TD></TR></TABLE></TD></TR></TABLE><BR>';
}
else
{
	//server status - someone is online
	$main_content .= '
	<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%>
		<TR BGCOLOR="'.$config['site']['vdarkborder'].'">
			<TD CLASS=white><B>Server Status</B></TD>
		</TR>
		<TR BGCOLOR='.$config['site']['darkborder'].'>
			<TD>';
			$main_content .= 'Currently there are '.$number_of_players_online.' active live casts';
			$main_content .= ' on '.$world_name.'.<br>
			</TD>
		</TR>
	</TABLE><BR>';
	//list of players
	$main_content .= '
	<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%>
		<TR BGCOLOR="'.$config['site']['vdarkborder'].'">
			<TD><A HREF="index.php?subtopic=whoisonline&order=name" CLASS=white>Name</A></TD>
			<TD><A HREF="index.php?subtopic=whoisonline&order=vocation" CLASS=white>Status</TD>
		</TR>
	'.$players_rows.'</TABLE>';
	//search bar
	//$main_content .= '<BR><FORM ACTION="index.php?subtopic=characters" METHOD=post>  <TABLE WIDTH=100% BORDER=0 CELLSPACING=1 CELLPADDING=4><TR><TD BGCOLOR="'.$config['site']['vdarkborder'].'" CLASS=white><B>Search Character</B></TD></TR><TR><TD BGCOLOR="'.$config['site']['darkborder'].'"><TABLE BORDER=0 CELLPADDING=1><TR><TD>Name:</TD><TD><INPUT NAME="name" VALUE=""SIZE=29 MAXLENGTH=29></TD><TD><INPUT TYPE=image NAME="Submit" SRC="'.$layout_name.'/images/buttons/sbutton_submit.gif" BORDER=0 WIDTH=120 HEIGHT=18></TD></TR></TABLE></TD></TR></TABLE></FORM>';
}
	$main_content .= '<BR><TABLE WIDTH=100% BORDER=0 CELLSPACING=1 CELLPADDING=4><TR><TD BGCOLOR="'.$config['site']['vdarkborder'].'" CLASS=white><B>Description</B></TD></TR><TR><TD BGCOLOR="'.$config['site']['darkborder'].'"><h4 style="margin: 0px;">Commands (owner):</h4><i>!cast {on/off}</i> - Create or close your own cast<br/><i>!cast password, |password|</i> - Sets a password for the cast<br/><i>!cast,desc, |description|</i> - Set a description for the cast<br/><i>!cast status</i> - Information about your cast (viewer amount, description, password)<br/><i>!cast viewers</i> - Displays the name of all viewers<br/><i>!cast {ban/unban},"name"</i> - Bans a viewer from joining your cast/Removes the ban<br/><i>!cast {mute/unmute} "name"</i> - Mutes a viewer on your cast/Removes the mute<br/><i>!cast bans</i> - Displays a list of banned viewers<br/><i>!cast mutes</i> - Displays a list of muted viewers<br/><i>!cast update</i> - Updates the description and status on the website<br/><br/><h4 style="margin: 0px;">Commands (viewer):</h4><i>!nick newNick</i> - Changes the viewer\'s name<br><i>!info</i> - Displays a list of all viewers</TD></TR></TABLE>';

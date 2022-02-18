<?php
if(!defined('INITIALIZED'))
	exit;
	
// CONFIG
$level_limit = 8; // minimum 1 character with 30 lvl on account to post
$post_interval = 20; // 20 seconds between posts
$group_not_blocked = $config['site']['access_admin_panel']; // group id of player that can always post, remove post, remove threads
$posts_per_page = 20;
$threads_per_page = 20;

//Tiny Editor
				$main_content .= '
					<script type="text/javascript" src="'.$layout_name.'/tiny_mce/tiny_mce.js"></script>
					<script type="text/javascript">
						tinyMCE.init({
							// General options
							mode : "textareas",
							theme : "advanced",
							plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave,visualblocks",
					
							// Theme options
							theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
							theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,link,unlink,anchor,image,cleanup,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
							theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,ltr,rtl",
							theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft,visualblocks",
							theme_advanced_toolbar_location : "top",
							theme_advanced_toolbar_align : "left",
							theme_advanced_statusbar_location : "bottom",
							theme_advanced_resizing : true,
					
							// Example content CSS (should be your site CSS)
							content_css : "css/content.css",
					
							// Drop lists for link/image/media/template dialogs
							template_external_list_url : "lists/template_list.js",
							external_link_list_url : "lists/link_list.js",
							external_image_list_url : "lists/image_list.js",
							media_external_list_url : "lists/media_list.js",
					
							// Style formats
							style_formats : [
								{title : \'Bold text\', inline : \'b\'},
								{title : \'Red text\', inline : \'span\', styles : {color : \'#ff0000\'}},
								{title : \'Red header\', block : \'h1\', styles : {color : \'#ff0000\'}},
								{title : \'Example 1\', inline : \'span\', classes : \'example1\'},
								{title : \'Example 2\', inline : \'span\', classes : \'example2\'},
								{title : \'Table styles\'},
								{title : \'Table row 1\', selector : \'tr\', classes : \'tablerow1\'}
							],
					
							// Replace values for the template plugin
							template_replace_values : {
								username : "Some User",
								staffid : "991234"
							}
						});
					</script>';
// SECTION WITH ID 1 IS FOR "NEWS", ONLY ADMINS CAN CREATE NEW THREAD IN IT
$sections = array(
					1 => 'News', 
					2 => 'Wars', 
					3 => 'Quests', 
					4 => 'Pictures', 
					5 => 'Bug Report', 
					6 => 'Events (English Only)', 
					7 => 'Real Life');
$sections_desc = array(
					1 => 'Here are the latest news of the server, and you can comment.', 
					2 => 'Feel free to tell what you think about your enemy.', 
					3 => 'Talk with others about quests you made and how to make them.', 
					4 => 'Show others your best photos from server!', 
					5 => 'Report bugs on website and in-game here.', 
					6 => 'This board is all about events. Here you can advertise your events server-wide to find more participants or exchange ideas on how to organise events best.', 
					7 => 'Everything about your private interests which has nothing to do with Tibia.');
$sections_img = array(
					1 => '<img src="'.$layout_name.'/images/forum/boards/1.png">', 
					2 => '<img src="'.$layout_name.'/images/forum/boards/2.png">', 
					3 => '<img src="'.$layout_name.'/images/forum/boards/3.png">', 
					4 => '<img src="'.$layout_name.'/images/forum/boards/4.png">', 
					5 => '<img src="'.$layout_name.'/images/forum/boards/5.png">', 
					6 => '<img src="'.$layout_name.'/images/forum/boards/6.png">', 
					7 => '<img src="'.$layout_name.'/images/forum/boards/7.png">');
// END
# Check if player can post
function canPost($account)
{
    if($account->isLoaded())
    {
        $SQL = $GLOBALS['SQL'];
        $level_limit = $GLOBALS['level_limit'];
	$player = filter_var($player, FILTER_SANITIZE_STRING);	
        $player = $SQL->query("SELECT " . $SQL->fieldName('level') . " FROM " . $SQL->tableName('players') . " WHERE " . $SQL->fieldName('account_id') . " = ".$SQL->quote($account->getId())." ORDER BY " . $SQL->fieldName('level') . " DESC")->fetch();
        if($player['level'] >= $level_limit)
            return true;
    }
    return false;
}

# Replace codes for smiles
function replaceSmile($text, $smile)
{
    $smileys = array(
						':p' => 1, 
						':eek:' => 2, 
						':rolleyes:' => 3, 
						';)' => 4, 
						':o' => 5, 
						':D' => 6,  
						':(' => 7, 
						':mad:' => 8,
						':)' => 9,
						':cool:' => 10
					);
    if($smile == 1)
        return $text;
    else
    {
        foreach($smileys as $search => $replace)
            $text = str_replace($search, '<img src="./images/forum/smile/'.$replace.'.gif" />', $text);
        return $text;
    }
}

function replaceAll($text, $smile)
{
    $rows = 0;
    while(stripos($text, '[code]') !== false && stripos($text, '[/code]') !== false )
    {
        $code = substr($text, stripos($text, '[code]')+6, stripos($text, '[/code]') - stripos($text, '[code]') - 6);
        if(!is_int($rows / 2)) { $bgcolor = 'ABED25'; } else { $bgcolor = '23ED25'; } $rows++;
        $text = str_ireplace('[code]'.$code.'[/code]', '<i>Code:</i><br /><table cellpadding="0" style="background-color: #'.$bgcolor.'; width: 480px; border-style: dotted; border-color: #CCCCCC; border-width: 2px"><tr><td>'.$code.'</td></tr></table>', $text);
    }
    $rows = 0;
    while(stripos($text, '[quote]') !== false && stripos($text, '[/quote]') !== false )
    {
        $quote = substr($text, stripos($text, '[quote]')+7, stripos($text, '[/quote]') - stripos($text, '[quote]') - 7);
        if(!is_int($rows / 2)) { $bgcolor = 'AAAAAA'; } else { $bgcolor = 'CCCCCC'; } $rows++;
        $text = str_ireplace('[quote]'.$quote.'[/quote]', '<table cellpadding="0" style="background-color: #'.$bgcolor.'; width: 480px; border-style: dotted; border-color: #007900; border-width: 2px"><tr><td>'.$quote.'</td></tr></table>', $text);
    }
    $rows = 0;
    while(stripos($text, '[url]') !== false && stripos($text, '[/url]') !== false )
    {
        $url = substr($text, stripos($text, '[url]')+5, stripos($text, '[/url]') - stripos($text, '[url]') - 5);
        $text = str_ireplace('[url]'.$url.'[/url]', '<a href="'.$url.'" target="_blank">'.$url.'</a>', $text);
    }
    while(stripos($text, '[player]') !== false && stripos($text, '[/player]') !== false )
    {
        $player = substr($text, stripos($text, '[player]')+8, stripos($text, '[/player]') - stripos($text, '[player]') - 8);
        $text = str_ireplace('[player]'.$player.'[/player]', '<a href="?subtopic=characters&name='.urlencode($player).'">'.$player.'</a>', $text);
    }
    while(stripos($text, '[img]') !== false && stripos($text, '[/img]') !== false )
    {
        $img = substr($text, stripos($text, '[img]')+5, stripos($text, '[/img]') - stripos($text, '[img]') - 5);
        $text = str_ireplace('[img]'.$img.'[/img]', '<img src="'.$img.'">', $text);
    }
	while(stripos($text, '[letter]') !== false && stripos($text, '[/letter]') !== false )
    {
        $letter = substr($text, stripos($text, '[letter]')+8, stripos($text, '[/letter]') - stripos($text, '[letter]') - 8);
        $text = str_ireplace('[letter]'.$letter.'[/letter]', '<img src="./images/forum/letters/letter_martel_'.$letter.'.gif">', $text);
    }
    while(stripos($text, '[b]') !== false && stripos($text, '[/b]') !== false )
    {
        $b = substr($text, stripos($text, '[b]')+3, stripos($text, '[/b]') - stripos($text, '[b]') - 3);
        $text = str_ireplace('[b]'.$b.'[/b]', '<b>'.$b.'</b>', $text);
    }
    while(stripos($text, '[i]') !== false && stripos($text, '[/i]') !== false )
    {
        $i = substr($text, stripos($text, '[i]')+3, stripos($text, '[/i]') - stripos($text, '[i]') - 3);
        $text = str_ireplace('[i]'.$i.'[/i]', '<i>'.$i.'</i>', $text);
    }
    while(stripos($text, '[u]') !== false && stripos($text, '[/u]') !== false )
    {
        $u = substr($text, stripos($text, '[u]')+3, stripos($text, '[/u]') - stripos($text, '[u]') - 3);
        $text = str_ireplace('[u]'.$u.'[/u]', '<u>'.$u.'</u>', $text);
    }
    return replaceSmile($text, $smile);
}

function removeBBCode($text)
{
    while(stripos($text, '[code]') !== false && stripos($text, '[/code]') !== false )
    {
        $code = substr($text, stripos($text, '[code]')+6, stripos($text, '[/code]') - stripos($text, '[code]') - 6);
        $text = str_ireplace('[code]'.$code.'[/code]', $code, $text);
    }
    while(stripos($text, '[quote]') !== false && stripos($text, '[/quote]') !== false )
    {
        $quote = substr($text, stripos($text, '[quote]')+7, stripos($text, '[/quote]') - stripos($text, '[quote]') - 7);
        $text = str_ireplace('[quote]'.$quote.'[/quote]', $quote, $text);
    }
    while(stripos($text, '[url]') !== false && stripos($text, '[/url]') !== false )
    {
        $url = substr($text, stripos($text, '[url]')+5, stripos($text, '[/url]') - stripos($text, '[url]') - 5);
        $text = str_ireplace('[url]'.$url.'[/url]', $url, $text);
    }
    while(stripos($text, '[player]') !== false && stripos($text, '[/player]') !== false )
    {
        $player = substr($text, stripos($text, '[player]')+8, stripos($text, '[/player]') - stripos($text, '[player]') - 8);
        $text = str_ireplace('[player]'.$player.'[/player]', $player, $text);
    }
    while(stripos($text, '[img]') !== false && stripos($text, '[/img]') !== false )
    {
        $img = substr($text, stripos($text, '[img]')+5, stripos($text, '[/img]') - stripos($text, '[img]') - 5);
        $text = str_ireplace('[img]'.$img.'[/img]', $img, $text);
    }
    while(stripos($text, '[b]') !== false && stripos($text, '[/b]') !== false )
    {
        $b = substr($text, stripos($text, '[b]')+3, stripos($text, '[/b]') - stripos($text, '[b]') - 3);
        $text = str_ireplace('[b]'.$b.'[/b]', $b, $text);
    }
    while(stripos($text, '[i]') !== false && stripos($text, '[/i]') !== false )
    {
        $i = substr($text, stripos($text, '[i]')+3, stripos($text, '[/i]') - stripos($text, '[i]') - 3);
        $text = str_ireplace('[i]'.$i.'[/i]', $i, $text);
    }
    while(stripos($text, '[u]') !== false && stripos($text, '[/u]') !== false )
    {
        $u = substr($text, stripos($text, '[u]')+3, stripos($text, '[/u]') - stripos($text, '[u]') - 3);
        $text = str_ireplace('[u]'.$u.'[/u]', $u, $text);
    }
    return $text;
}

function codeLower($text)
{
    return str_ireplace(array('[b]', '[i]', '[u]', '[/u][/i][/b][i][u]', '[/u][/i][u]', '[/u]', '[url]', '[player]', '[img]', '[code]', '[quote]', '[/quote][/code][/url][code][quote]', '[/player]', '[/img]', '[/quote][/code][quote]', '[/quote]'), array('[b]', '[i]', '[u]', '[/u][/i][/b][i][u]', '[/u][/i][u]', '[/u]', '[url]', '[player]', '[img]', '[code]', '[quote]', '[/quote][/code][/url][code][quote]', '[/player]', '[/img]', '[/quote][/code][quote]', '[/quote]'), $text);
}

function showPost($topic, $text, $smile)
{
    $text = "<br><br>".$text;
    $post = '';
    if(!empty($topic))
        $post .= '<b>'.replaceSmile($topic, $smile).'</b>';
    $post .= replaceAll($text, $smile);
    return $post;
}

function showPreview($topic, $text, $smile)
{
	$text = $_POST['text'];
    $post = '';
    if(!empty($topic))
        $post .= '<b>'.replaceSmile($topic, $smile).'</b>';
    $post .= replaceAll($text, $smile);
    return $post;
}

$main_content .= '
	<table border=0 cellpadding=2 cellspacing=0 width=100%>
		<tr>';
	
if($action == '')
{
	if(!$logged)
    $main_content .=  '
	<table border=0 cellpadding=2 cellspacing=0 width=100% style="width: 99%; border-radius:5px; border:1px dashed #b71313; background-color:#fcbebe; padding:2px">
		<tr>
			<td  class="ff_std" align="left" >You are <b>not</b> logged in. <a href="?subtopic=accountmanagement" >Log in</a> to post on the forum.</td>
		</tr>
	</table>';
		$main_content .= '
	</br>
	<table border="0" cellpadding="2" cellspacing="0" width="100%">
		<tr>
			<td  class="ff_info" align="right" valign="bottom"&nbsp;<br>Current Time: <i>'.date('G:i').' CEST</i></td>
		</tr>
	</table>
	</br>';
		
    $main_content .= '
<div class="TableContainer">
    <div class="CaptionContainer">
        <div class="CaptionInnerContainer">
            <span class="CaptionEdgeLeftTop" style="background-image:url('.$layout_name.'/images/global/content/box-frame-edge.gif);"></span>
            <span class="CaptionEdgeRightTop" style="background-image:url('.$layout_name.'/images/global/content/box-frame-edge.gif);"></span>
            <span class="CaptionBorderTop" style="background-image:url('.$layout_name.'/images/global/content/table-headline-border.gif);"></span>
            <span class="CaptionBorderBottom" style="background-image:url('.$layout_name.'/images/global/content/table-headline-border.gif);"></span>
            <span class="CaptionEdgeLeftBottom" style="background-image:url('.$layout_name.'/images/global/content/box-frame-edge.gif);"></span>
            <span class="CaptionVerticalLeft" style="background-image:url('.$layout_name.'/images/global/content/box-frame-vertical.gif);"></span>
            <div class="Text" style="min-height: 17px">
                <div style="float: left">Fórum</div>
            </div>
            <span class="CaptionVerticalRight" style="background-image:url('.$layout_name.'/images/global/content/box-frame-vertical.gif);"></span>
            <span class="CaptionBorderBottom" style="background-image:url('.$layout_name.'/images/global/content/table-headline-border.gif);"></span>
            <span class="CaptionEdgeLeftBottom"></span>
            <span class="CaptionEdgeRightBottom" style="background-image:url('.$layout_name.'/images/global/content/box-frame-edge.gif);"></span>
        </div>
    </div>

    <table class="Table3" cellpadding="0" cellspacing="0" align="">
        <tbody>
            <tr>
                <td>
                    <div class="InnerTableContainer">
                        <table style="width:100%;">
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="TableShadowContainerRightTop">
                                            <div class="TableShadowRightTop" style="background-image:url('.$layout_name.'/images/global/content/table-shadow-rt.gif);"></div>
                                        </div>
                                        <div class="TableContentAndRightShadow" style="background-image:url('.$layout_name.'/images/global/content/table-shadow-rm.gif);">
                                            <div class="TableContentContainer">
                                                <table class="TableContent " width="100%">
                                                    <tbody>
                                                        <tr>
                                                            <td colspan=1 align="center" width="32" height="16"></td>
                                                            <td colspan=1><b>Board</b></td>
                                                            <td colspan=1 align="center"><b>Posts</b></td>
                                                            <td colspan=1 align="center"><b>Threads</b></td>
                                                            <td colspan=1 align="center"><b>Last Post</b></td>
                                                        </tr>';
			
    $info = filter_var($info, FILTER_SANITIZE_STRING);	
    $info = $SQL->query("SELECT " . $SQL->fieldName('section') . ", COUNT(" . $SQL->fieldName('id') . ") AS 'threads', SUM(" . $SQL->fieldName('replies') . ") AS 'replies' FROM " . $SQL->tableName('z_forum') . " WHERE " . $SQL->fieldName('first_post') . " = " . $SQL->fieldName('id') . " GROUP BY " . $SQL->fieldName('section') . "")->fetchAll();
    foreach($info as $data)
        $counters[$data['section']] = array('threads' => $data['threads'], 'posts' => $data['replies'] + $data['threads']);
    foreach($sections as $id => $section)
    {
    	$last_post = filter_var($last_post, FILTER_SANITIZE_STRING);
        $last_post = $SQL->query("SELECT " . $SQL->tableName('players') . "." . $SQL->fieldName('name') . ", " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('post_date') . " FROM " . $SQL->tableName('players') . ", " . $SQL->tableName('z_forum') . " WHERE " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('section') . " = ".(int) $id." AND " . $SQL->tableName('players') . "." . $SQL->fieldName('id') . " = " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('author_guid') . " ORDER BY " . $SQL->fieldName('post_date') . " DESC LIMIT 1")->fetch();
        if(!is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
		
        $main_content .= '
			<tr bgcolor="'.$bgcolor.'">
				<td class="ff_std" >'.$sections_img[$id].'</td>
				<td class="ff_std"><a href="?subtopic=forum&action=show_board&id='.$id.'">'.$section.'</a><br /><small>'.$sections_desc[$id].'</small></td>
				<td class="ff_std" colspan=1 align="center">'.(int) $counters[$id]['posts'].'</td>
				<td class="ff_std" colspan=1 align="center">'.(int) $counters[$id]['threads'].'</td>
				<td class="ff_std" align="left" NOWRAP>';
        if(isset($last_post['name']))
            $main_content .= '<a href="?subtopic=forum&action=show_board&id='.$id.'"><img src="images/forum/logo_lastpost.gif" border=0 width=10 height=9></a>'.date('d/m/y H:i:s', $last_post['post_date']).'<br>
			<font class="ff_info">by&nbsp;<a href="?subtopic=characters&name='.urlencode($last_post['name']).'">'.$last_post['name'].'</a></font>';
        else
            $main_content .= 'No posts';
        $main_content .= '
				</td>
			</tr>
			';

    }
    $main_content .= '
                                                    <tr>
                                                        <td colspan=5 align="left"><b>All times are CEST.</b></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="TableShadowContainer">
                                        <div class="TableBottomShadow" style="background-image:url(./layouts/tibiacom/images/global/content/table-shadow-bm.gif);">
                                            <div class="TableBottomLeftShadow" style="background-image:url(./layouts/tibiacom/images/global/content/table-shadow-bl.gif);"></div>
                                            <div class="TableBottomRightShadow" style="background-image:url(./layouts/tibiacom/images/global/content/table-shadow-br.gif);"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
    </tbody>
</table>';
}

if($action == 'show_board')
{
    $section_id = (int) $_REQUEST['id'];
    $page = (int) $_REQUEST['page'];
    $threads_count = filter_var($threads_count, FILTER_SANITIZE_STRING);
    $threads_count = $SQL->query("SELECT COUNT(" . $SQL->tableName('z_forum') . "." . $SQL->fieldName('id') . ") AS threads_count FROM " . $SQL->tableName('players') . ", " . $SQL->tableName('z_forum') . " WHERE " . $SQL->tableName('players') . "." . $SQL->fieldName('id') . " = " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('author_guid') . " AND " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('section') . " = ".(int) $section_id." AND " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('first_post') . " = " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('id') . "")->fetch();
    for($i = 0; $i < $threads_count['threads_count'] / $threads_per_page; $i++)
    {
        if($i != $page)
            $links_to_pages .= '<a href="?subtopic=forum&action=show_board&id='.$section_id.'&page='.$i.'">'.($i + 1).'</a> ';
        else
            $links_to_pages .= '<b>'.($i + 1).' </b>';
    }
	
    $main_content .= '
	<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=100%>
		<TR>
			<TD><IMG SRC="'.$layout_name.'/images/global/general/blank.gif" WIDTH=10 HEIGHT=1 BORDER=0></TD>
			<TD WIDTH=100% ALIGN=right><a href="?subtopic=forum" >Community Boards</a> | <b>'.$sections[$section_id].'</b></TD>
			<TD><IMG SRC="'.$layout_name.'/images/global/general/blank.gif" WIDTH=10 HEIGHT=1 BORDER=0></TD>
		</TR>
	</TABLE>';
	
	if(!$logged)
    $main_content .=  '
		<br>You are <b>not</b> logged in. <a href="?subtopic=accountmanagement" >Log in</a> to post on the forum.<br><br>';
		
	$main_content .= '
	<table border=0 cellpadding=2 cellspacing=0 width=100%>
		<tr>
			<td  class="ff_std" colspan=4 align="left" ></td>
		</tr>
		<tr>
			<td  colspan=1 align="left" ><a href="?subtopic=forum&action=new_topic&section_id='.$section_id.'" ><img src="images/forum/topic.gif" name="" width="85" height="20" border="0" ></a>		
			</td>
		</tr>
	</table>';
	
    $last_threads = filter_var($last_threads, FILTER_SANITIZE_STRING);
    $last_threads = $SQL->query("SELECT " . $SQL->tableName('players') . "." . $SQL->fieldName('name') . ", " . $SQL->tableName('players') . "." . $SQL->fieldName('signature') . ", " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('post_text') . ", " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('post_topic') . ", " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('id') . ", " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('last_post') . ", " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('replies') . ", " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('views') . ", " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('post_date') . ", " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('icon_id') . " FROM " . $SQL->tableName('players') . ", " . $SQL->tableName('z_forum') . " WHERE " . $SQL->tableName('players') . "." . $SQL->fieldName('id') . " = " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('author_guid') . " AND " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('section') . " = ".(int) $section_id." AND " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('first_post') . " = " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('id') . " ORDER BY " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('last_post') . " DESC LIMIT ".$threads_per_page." OFFSET ".($page * $threads_per_page))->fetchAll();
	
    if(isset($last_threads[0]))
    {
		$main_content .= '
			<table border=0 cellpadding=3 cellspacing=1 width=100%>
				<tr>
					<td  bgcolor="'.$config['site']['vdarkborder'].'" colspan=7 >&nbsp;</td>
				</tr>';		
        $main_content .= '
				<tr>
					<td  bgcolor="'.$config['site']['vdarkborder'].'" class="ff_white" colspan=1 align="center" width=22 >
						<b><img src="'.$layout_name.'/images/global/general/blank.gif" width=16 height=16 border=0></b>
					</td>
					<td  bgcolor="'.$config['site']['vdarkborder'].'" class="ff_white" colspan=1 align="center" width=22 >
						<b><img src="'.$layout_name.'/images/global/general/blank.gif" width=16 height=16 border=0></b>
					</td>
					<td bgcolor="'.$config['site']['vdarkborder'].'" class="ff_white" colspan=1 align="center">
						<b>Thread</b>
					</td>
					<td bgcolor="'.$config['site']['vdarkborder'].'" class="ff_white" colspan=1 align="center">
						<b>	</b>
					</td>
					<td bgcolor="'.$config['site']['vdarkborder'].'" class="ff_white" colspan=1 align="center">
						<b>Replies</b>
					</td>
					<td bgcolor="'.$config['site']['vdarkborder'].'" class="ff_white" colspan=1 align="center">
						<b>Views</b>
					</td>
					<td bgcolor="'.$config['site']['vdarkborder'].'" class="ff_white" colspan=1 align="center">
						<b>Last Post</b>
					</td>
				</tr>';
        foreach($last_threads as $thread)
        {
            if(!is_int($number_of_rows / 2)) { $bgcolor = $config['site']['lightborder']; } else { $bgcolor = $config['site']['darkborder']; } $number_of_rows++;
			
            $main_content .= '
				<tr>
					<td  bgcolor="#D4C0A1" class="ff_std" colspan=1 align="center" >
						<div class="HNCContainer" >
							<img src="';
							if((int)$thread['replies'] >= 2)
							$main_content .= $layout_name.'/images/global/forum/logo_hot.gif"';
							else
							$main_content .= $layout_name.'/images/global/general/blank.gif"';							
						$main_content .= '	
							 width=22 height=22 border=0>
						</div>
					</td>
					<td  bgcolor="#F1E0C6" class="ff_std" colspan=1 align="center" >';
					if($thread['icon_id'] >= 1)
					$main_content .= '
						<img src="'.$layout_name.'/images/global/forum/icons/'.$thread['icon_id'].'.gif" border=0 width=15 height=15 alt="Eek">';
					$main_content .= '
					</td>
					<td bgcolor="#D4C0A1" class="ff_std" colspan=1 align="left">';
						
            if($logged && $group_id_of_acc_logged >= $group_not_blocked)
			
                $main_content .= '
					<a href="?subtopic=forum&action=remove_post&id='.$thread['id'].'" onclick="return confirm(\'Are you sure you want remove thread > '.htmlspecialchars($thread['post_topic']).' <?\')"><font color="red">[REMOVE]</font></a> ';
					
            $main_content .= '
				<a href="?subtopic=forum&action=show_thread&id='.$thread['id'].'">'.htmlspecialchars($thread['post_topic']).'</a></td>
				<td bgcolor="#F1E0C6" class="font_std" colspan=1 align="center"><a href="?subtopic=characters&name='.urlencode($thread['name']).'">'.$thread['name'].'</a></td>
				<td bgcolor="#D4C0A1" class="ff_std" colspan=1 align="center">'.(int) $thread['replies'].'</td>
				<td bgcolor="#F1E0C6" class="ff_std" colspan=1 align="center">'.(int) $thread['views'].'</td>
				<td bgcolor="#D4C0A1"  class="ff_std" colspan=1 align="left">';
				
            if($thread['last_post'] > 0)
            {
	    	$last_post = filter_var($last_post, FILTER_SANITIZE_STRING);
                $last_post = $SQL->query("SELECT " . $SQL->tableName('players') . "." . $SQL->fieldName('name') . ", " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('post_date') . " FROM " . $SQL->tableName('players') . ", " . $SQL->tableName('z_forum') . " WHERE " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('first_post') . " = ".(int) $thread['id']." AND " . $SQL->tableName('players') . "." . $SQL->fieldName('id') . " = " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('author_guid') . " ORDER BY " . $SQL->fieldName('post_date') . " DESC LIMIT 1")->fetch();
                if(isset($last_post['name']))
                    $main_content .= date('d/m/y H:i:s', $last_post['post_date']).'<br />by <a href="?subtopic=characters&name='.urlencode($last_post['name']).'">'.$last_post['name'].'</a>';
                else
                    $main_content .= 'No posts.';
            }
            else
                $main_content .= date('d/m/y H:i:s', $thread['post_date']).'<br />by <a href="?subtopic=characters&name='.urlencode($thread['name']).'">'.$thread['name'].'</a>';
            $main_content .= '
				</td>
			</tr>';
        }
        $main_content .= '
			<table border=0 cellpadding=6 cellspacing=0 width=100%>
				<tr>
					<td  bgcolor="#505050" class="ff_white" colspan=1 align="left" NOWRAP ><b>All times are CEST.</b></td>
					<td  bgcolor="#505050" class="ff_white" colspan=1 align="right" NOWRAP >&nbsp;</td>
				</tr>
			</table>
			<table border=0 cellpadding=2 cellspacing=0 width=100%>
				<tr>
					<td  colspan=1 align="left" valign="top" >
						<a href="?subtopic=forum&action=new_topic&section_id='.$section_id.'" >
							<img src="'.$layout_name.'/images/global/forum/button_newtopic_off.gif" name="" width="85" height="20" border="0" >
						</a>
					</td>
				</tr>					
			</table>
			<td  class="ff_info" colspan=1 align="right" valign="top" >Page: '.$links_to_pages.'</td>
		</tr>
		<tr>
			<td  class="ff_std" colspan=4 align="left" ><br></td>
		</tr>
	</table>
	<center>
		<table border=0 cellpadding=3 cellspacing=0>
			<tr>
				<td  class="ff_info" colspan=1 align="center" valign="middle" NOWRAP ><img src="'.$layout_name.'/images/global/forum/logo_new.gif" border=0 width=22 height=22></td>
				<td  class="ff_info" colspan=1 align="center" valign="middle" NOWRAP ><b>New Posts</b></td>
				<td  class="ff_info" colspan=1 align="center" valign="middle" NOWRAP ><img src="'.$layout_name.'/images/global/general/blank.gif" width=12 height=1 border=0></td>
				<td  class="ff_info" colspan=1 align="center" valign="middle" NOWRAP ><img src="'.$layout_name.'/images/global/forum/logo_hot.gif" border=0 width=22 height=22><img src="'.$layout_name.'/images/global/forum/logo_hotnew.gif" border=0 width=22 height=22></td>
				<td  class="ff_info" colspan=1 align="center" valign="middle" NOWRAP ><b>More Than 16 Replies</b></td>
				<td  class="ff_info" colspan=1 align="center" valign="middle" NOWRAP ><img src="'.$layout_name.'/images/global/general/blank.gif" width=12 height=1 border=0></td>
				<td  class="ff_info" colspan=1 align="center" valign="middle" NOWRAP ><img src="'.$layout_name.'/images/global/forum/logo_closed.gif" border=0 width=22 height=22></td>
				<td  class="ff_info" colspan=1 align="center" valign="middle" NOWRAP ><b>Closed Thread</b></td>
				<td  class="ff_info" colspan=1 align="center" valign="middle" NOWRAP ><img src="'.$layout_name.'/images/global/forum/logo_sticky.gif" border=0 width=22 height=22></td>
				<td  class="ff_info" colspan=1 align="center" valign="middle" NOWRAP ><b>Sticky Thread</b></td>
			</tr>
		</table>
	</center>
	<br>
	<br>
	<b>Board Rights:<br>
	</b>View threads.<br>
	<br>
	Replace code is ON. Smileys are ON. Images are OFF. Links are OFF. "Thank You!" option is OFF. <br>
	Account muting option is ON. <br>';
    }
    else
        $main_content .= '<h3>No threads in this board.</h3>';
}
if($action == 'show_thread')
{
    $thread_id = (int) $_REQUEST['id'];
    $page = (int) $_REQUEST['page'];
    $thread_name = filter_var($thread_name, FILTER_SANITIZE_STRING);
    $thread_name = $SQL->query("SELECT " . $SQL->tableName('players') . "." . $SQL->fieldName('name') . ", " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('post_topic') . " FROM " . $SQL->tableName('players') . ", " . $SQL->tableName('z_forum') . " WHERE " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('first_post') . " = ".(int) $thread_id." AND " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('id') . " = " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('first_post') . " AND " . $SQL->tableName('players') . "." . $SQL->fieldName('id') . " = " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('author_guid') . " LIMIT 1")->fetch();
    if(!empty($thread_name['name']))
    {
	$posts_count = filter_var($posts_count, FILTER_SANITIZE_STRING);
        $posts_count = $SQL->query("SELECT COUNT(" . $SQL->tableName('z_forum') . "." . $SQL->fieldName('id') . ") AS posts_count FROM " . $SQL->tableName('players') . ", " . $SQL->tableName('z_forum') . " WHERE " . $SQL->tableName('players') . "." . $SQL->fieldName('id') . " = " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('author_guid') . " AND " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('first_post') . " = ".(int) $thread_id)->fetch();
        for($i = 0; $i < $posts_count['posts_count'] / $threads_per_page; $i++)
        {
            if($i != $page)
                $links_to_pages .= '<a href="?subtopic=forum&action=show_thread&id='.$thread_id.'&page='.$i.'">'.($i + 1).'</a> ';
            else
                $links_to_pages .= '<b>'.($i + 1).' </b>';
        }
		
        $threads = $SQL->query("SELECT 
									" . $SQL->tableName('players') . "." . $SQL->fieldName('name') . ", 
									" . $SQL->tableName('players') . "." . $SQL->fieldName('group_id') . ", 
									" . $SQL->tableName('players') . "." . $SQL->fieldName('town_id') . ", 
									" . $SQL->tableName('players') . "." . $SQL->fieldName('account_id') . ",  
									" . $SQL->tableName('players') . "." . $SQL->fieldName('vocation') . ", 
									" . $SQL->tableName('players') . "." . $SQL->fieldName('level') . ", 
									" . $SQL->tableName('players') . "." . $SQL->fieldName('signature') . ", 
									" . $SQL->tableName('z_forum') . "." . $SQL->fieldName('id') . ",
									" . $SQL->tableName('z_forum') . "." . $SQL->fieldName('first_post') . ", 
									" . $SQL->tableName('z_forum') . "." . $SQL->fieldName('section') . ", 
									" . $SQL->tableName('z_forum') . "." . $SQL->fieldName('icon_id') . ", 
									" . $SQL->tableName('z_forum') . "." . $SQL->fieldName('post_text') . ", 
									" . $SQL->tableName('z_forum') . "." . $SQL->fieldName('post_topic') . ", 
									" . $SQL->tableName('z_forum') . "." . $SQL->fieldName('post_date') . ", 
									" . $SQL->tableName('z_forum') . "." . $SQL->fieldName('post_smile') . ", 
									" . $SQL->tableName('z_forum') . "." . $SQL->fieldName('author_aid') . ", 
									" . $SQL->tableName('z_forum') . "." . $SQL->fieldName('author_guid') . ", 
									" . $SQL->tableName('z_forum') . "." . $SQL->fieldName('last_edit_aid') . ", 
									" . $SQL->tableName('z_forum') . "." . $SQL->fieldName('edit_date') . " 
						FROM 
									" . $SQL->tableName('players') . ", 
									" . $SQL->tableName('z_forum') . " 
						WHERE 
									" . $SQL->tableName('players') . "." . $SQL->fieldName('id') . " 
						= 
									" . $SQL->tableName('z_forum') . "." . $SQL->fieldName('author_guid') . " 
						AND 
									" . $SQL->tableName('z_forum') . "." . $SQL->fieldName('first_post') . " 
						= 
									".(int) $thread_id." 
						ORDER BY 
									" . $SQL->tableName('z_forum') . "." . $SQL->fieldName('post_date') . " 
						LIMIT 
									".$posts_per_page." 
						OFFSET 
									".($page * $posts_per_page))->fetchAll();
        if(isset($threads[0]['name']))
            $SQL->query("UPDATE " . $SQL->tableName('z_forum') . " SET " . $SQL->fieldName('views') . "=" . $SQL->fieldName('views') . "+1 WHERE " . $SQL->fieldName('id') . " = ".(int) $thread_id);
		
		$main_content .= '
			<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=100%>
				<TR>
					<TD><IMG SRC="'.$layout_name.'/images/global/general/blank.gif" WIDTH=10 HEIGHT=1 BORDER=0></TD>
					<TD WIDTH=100% ALIGN=right>
						<a href="?subtopic=forum" >Community Boards</a> | 
						<a href="?subtopic=forum&action=show_board&id='.$threads[0]['section'].'">'.$sections[$threads[0]['section']].'</a> | <b>'.htmlspecialchars($thread_name['post_topic']).'</b>
					</TD>
					<TD><IMG SRC="'.$layout_name.'/images/global/general/blank.gif" WIDTH=10 HEIGHT=1 BORDER=0></TD>
				</TR>
			</TABLE>
			<br />';
		if(!$logged)
		$main_content .= '
			<br>
			You are <b>not</b> logged in. <a href="?subtopic=accountmanagement" >Log in</a> to post on the forum.<br>
			<br>';
		$main_content .= '
			<table border=0 cellpadding=0 cellspacing=0 width=100%>
				<tr>
					<td  class="ff_std" colspan=4 align="left" ></td>
				</tr>
				<tr>
					<td  bgcolor="#F1E0C6" class="ff_large" colspan=2 align="left" width=800 >
						<div class="PostReplyLink">
							<a href="?subtopic=forum&action=new_post&thread_id='.$thread_id.'">
								<img src="images/forum/post.gif" name="" width="92" height="20" border="0" >
							</a>
						</div>
						<div class="Text" ><b>'.$sections[$threads[0]['section']].': '.htmlspecialchars($thread_name['post_topic']).'</b></div></td>
				</tr>
			</table>';
		$main_content .= '
			<table border=0 cellpadding=5 cellspacing=1 width=100%>
				<tr>
					<td  bgcolor="'.$config['site']['vdarkborder'].'" class="ff_white" colspan=2 align="left" NOWRAP >
						<div class="ForumPost" style="position:relative;height:26px;left:0px;">
							<div style="float:left;width:175;padding: 5 5 0 5;" ><b>Author</b></div>
							<div style="float:right;width:600px;padding: 5 5 0 5;" >
								<table border=0 cellpadding=2 cellspacing=0 width=100%>
									<tr>
										<td  bgcolor="'.$config['site']['vdarkborder'].'" class="ff_white" colspan=1 align="left" NOWRAP ><b>Thread #'.$threads[0]['id'].'</b></td>
										<td  bgcolor="'.$config['site']['vdarkborder'].'" class="ff_white" colspan=1 align="right" NOWRAP ><b>Pages: '.$links_to_pages.'</b>
										</td>
									</tr>
								</table>
							</div>
							<div class="PostSeparatorV" ></div>
						</div>
					</td>
				</tr>';	
		
        foreach($threads as $thread)
        {
            if(!is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
            $main_content .= '
				<tr>
					<td colspan="2" class="CipPost" >
						<div class="ForumPost" style="background-color:'.$bgcolor.';" >';
					if($thread['group_id'] >= 3)
					$main_content .= '
						<div class="CipBorderTop" >
							<div class="CipBorder" >
								<div class="CipBorderCornerL" style="background-image: url('.$layout_name.'/images/global/forum/cip_post_border_lu.jpg)" ></div>
								<div class="CipBorderH" style="background-image: url('.$layout_name.'/images/global/forum/cip_post_border_h.jpg)" ></div>
								<div class="CipBorderCornerR" style="background-image: url('.$layout_name.'/images/global/forum/cip_post_border_ru.jpg)" ></div>
							</div>
						</div>
						<div class="CipBorderLeft" >
							<div class="CipBorderV" style="background-image: url('.$layout_name.'/images/global/forum/cip_post_border_v.jpg)" ></div>
						</div>
						<div class="CipBorderRight" >
							<div class="CipBorderV" style="background-image: url('.$layout_name.'/images/global/forum/cip_post_border_v.jpg)" ></div>
						</div>';
					$main_content .= '
						<div class="PostSeparatorV" ></div>
						<div class="PostUpper">
							<div class="PostCharacterText" >
								<b><a href="?subtopic=characters&name='.urlencode($thread['name']).'">'.htmlspecialchars($thread['name']).'</a></b><br>';
							if($thread['group_id'] >= 3)
							$main_content .= '
								<font class="ff_smallinfo">Community Manager<br/>';
								
								if($thread['group_id'] >= 3)
								$main_content .= '
									<img class="CipPostIcon" src="'.$layout_name.'/images/global/forum/cip_post_icon.gif" /><br>';
								
							$main_content .= '
								</font><br>
								<font class="ff_infotext">Inhabitant of ' . $config['server']['serverName'] . '<br>
								Vocation: '.htmlspecialchars(Website::getVocationName($thread['vocation'], $thread['promotion'])).'<br>
								Level: '.$thread['level'].'<br>
								<br>';
            $rank = new GuildRank($thread['rank_id']);
            if($rank->isLoaded())
            {
                $guild = $rank->getGuild();
                if($guild->isLoaded())
                    $main_content .= '
						<font class="ff_smallinfo">'.htmlspecialchars($rank->getName()).' of the <a href="?subtopic=guilds&action=show&guild='.$guild->getId().'" >'.htmlspecialchars($guild->getName()).'</a> (Larissa)</font><br>';
            }
	    $posts = filter_var($posts, FILTER_SANITIZE_STRING);
            $posts = $SQL->query("SELECT COUNT(" . $SQL->fieldName('id') . ") AS 'posts' FROM " . $SQL->tableName('z_forum') . " WHERE " . $SQL->fieldName('author_aid') . "=".(int) $thread['account_id'])->fetch();
			
            $main_content .= '
				<br />Posts: '.(int) $posts['posts'].'<br /></font></div>
				<div class="PostText" >';
				if($thread['icon_id'] != 0)
				$main_content .= '
					<img src="'.$layout_name.'/images/global/forum/icons/'.$thread['icon_id'].'.gif">
				';
			$main_content .= '
				'.showPost(htmlspecialchars($thread['post_topic']), $thread['post_text'], $thread['post_smile']);
				
			if(!empty($thread['signature'])){
			$main_content .= '
				<br />________________<br />'.$thread['signature'];
			$main_content .= '
				<br /><br /><br /><br />
			';
			}
			$main_content .= '			
				</div>				
				<div class="PostLower" >
					<div class="PostDetailsHelper" >
						<div class="PostDetails" ><img src="'.$layout_name.'/images/global/forum/logo_oldpost.gif" border=0 width=14 height=11>'.date('d/m/y H:i:s', $thread['post_date']);
			
            if($thread['edit_date'] > 0)
            {
                if($thread['last_edit_aid'] != $thread['author_aid'])
                    $main_content .= '<br />Edited by moderator';
                else
                    $main_content .= '<br />Edited by '.htmlspecialchars($thread['name']);
                $main_content .= '<br />on '.date('d/m/y H:i:s', $thread['edit_date']);
            }
            $main_content .= '</div></div>';
			
			$main_content .= '
				<div class="PostActions" >
					<div class="AdditionalBox" >Post #'.$thread['id'].'</div>';
			
            if($logged && $group_id_of_acc_logged >= $group_not_blocked)
			
            if($thread['first_post'] != $thread['id'])
                 $main_content .= '
					<a href="?subtopic=forum&action=remove_post&id='.$thread['id'].'" onclick="return confirm(\'Are you sure you want remove post of '.htmlspecialchars($thread['name']).'?\')">
						<font color="red">REMOVE POST</font>
					</a>';
            else
                 $main_content .= '
					<a href="?subtopic=forum&action=remove_post&id='.$thread['id'].'" onclick="return confirm(\'Are you sure you want remove thread > '.htmlspecialchars($thread['post_topic']).' <?\')">
						<font color="red">REMOVE THREAD</font>
					</a>';
					
            if($logged && ($thread['account_id'] == $account_logged->getId() || $group_id_of_acc_logged >= $group_not_blocked))
			
                $main_content .= '
					<br/><a href="?subtopic=forum&action=edit_post&id='.$thread['id'].'">Edit Post</a>';
            if($logged)
                $main_content .= '
					<br/><a href="?subtopic=forum&action=new_post&thread_id='.$thread_id.'&quote='.$thread['id'].'">Quote</a>';
					
			$main_content .= '
				</div>';
			if($thread['group_id'] >= 3)
			$main_content .= '
				<div class="CipBorderBottom">
				<div class="CipBorder">
					<div class="CipBorderCornerL" style="background-image: url('.$layout_name.'/images/global/forum/cip_post_border_ll.jpg)" ></div>
					<div class="CipBorderH" style="background-image: url('.$layout_name.'/images/global/forum/cip_post_border_h.jpg)" ></div>
					<div class="CipBorderCornerR" style="background-image: url('.$layout_name.'/images/global/forum/cip_post_border_rl.jpg)" ></div>
				</div>
			</div>';
			else
		$main_content .= '
			</div>';	
     $main_content .= '
		</td>
	</tr>';
        }
		$main_content .= '
			<table border=0 cellpadding=6 cellspacing=0 width=100%>
				<tr>
					<td  bgcolor="#505050" class="ff_white" colspan=1 align="left" NOWRAP ><b>All times are CEST.&nbsp;&nbsp;&nbsp;Pages: '.$links_to_pages.'</b></td>
				</tr>
			</table>
			<table border=0 cellpadding=6 cellspacing=0 width=100%>
				<tr>
					<td  class="ff_pagetext" colspan=1 align="left" ></td>
					<td  class="ff_large" colspan=1 align="right" >
						<a href="?subtopic=forum&action=new_post&thread_id='.$thread_id.'" >
							<img src="images/forum/post.gif" name="" width="92" height="20" border="0" >
						</a>
					</td>
				</tr>
				<tr>
					<td  class="ff_std" colspan=1 align="left" ></td>
				</tr>
				<tr>
					<td  class="ff_std" colspan=4 align="left" ></td>
				</tr>
			</table>
			<br>
			<b>Board Rights:<br>
			</b>View threads.<br>
			<br>
			Replace code is ON. Smileys are ON. Images are OFF. Links are OFF. "Thank You!" option is OFF. <br>
			Account muting option is ON. <br>';
    }
    else
        $main_content .= 'Thread with this ID does not exits.';

}
if($action == 'remove_post')
{
    if($logged && $group_id_of_acc_logged >= $group_not_blocked)
    {
        $id = (int) $_REQUEST['id'];
	$post = filter_var($post, FILTER_SANITIZE_STRING);
        $post = $SQL->query("SELECT " . $SQL->fieldName('id') . ", " . $SQL->fieldName('first_post') . ", " . $SQL->fieldName('section') . " FROM " . $SQL->tableName('z_forum') . " WHERE " . $SQL->fieldName('id') . " = ".$id." LIMIT 1")->fetch();
        if($post['id'] == $id)
        {
            if($post['id'] == $post['first_post'])
            {
                $SQL->query("DELETE FROM " . $SQL->tableName('z_forum') . " WHERE " . $SQL->fieldName('first_post') . " = ".$post['id']);
                header('Location: ?subtopic=forum&action=show_board&id='.$post['section']);
            }
            else
            {
		$post_page = filter_var($post_page, FILTER_SANITIZE_STRING);
                $post_page = $SQL->query("SELECT COUNT(" . $SQL->tableName('z_forum') . "." . $SQL->fieldName('id') . ") AS posts_count FROM " . $SQL->tableName('players') . ", " . $SQL->tableName('z_forum') . " WHERE " . $SQL->tableName('players') . "." . $SQL->fieldName('id') . " = " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('author_guid') . " AND " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('id') . " < ".$id." AND " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('first_post') . " = ".(int) $post['first_post'])->fetch();
                $page = (int) ceil($post_page['posts_count'] / $threads_per_page) - 1;
				$SQL->query("UPDATE " . $SQL->tableName('z_forum') . " SET " . $SQL->fieldName('replies') . " = " . $SQL->fieldName('replies') . " - 1 WHERE " . $SQL->fieldName('id') . " = ".$post['first_post']);
                $SQL->query("DELETE FROM " . $SQL->tableName('z_forum') . " WHERE " . $SQL->fieldName('id') . " = ".$post['id']);
                header('Location: ?subtopic=forum&action=show_thread&id='.$post['first_post'].'&page='.(int) $page);
            }
        }
        else
            $main_content .= 'Post with ID '.$id.' does not exist.';
    }
    else
        $main_content .= 'You are not logged in or you are not moderator.';
}
if($action == 'new_post')
{
    if($logged)
    {
        if(canPost($account_logged) || $group_id_of_acc_logged >= $group_not_blocked)
        {
	    $players_from_account = filter_var($players_from_account, FILTER_SANITIZE_STRING);
            $players_from_account = $SQL->query("SELECT " . $SQL->tableName('players') . "." . $SQL->fieldName('name') . ", " . $SQL->tableName('players') . "." . $SQL->fieldName('id') . " FROM " . $SQL->tableName('players') . " WHERE " . $SQL->tableName('players') . "." . $SQL->fieldName('account_id') . " = ".(int) $account_logged->getId())->fetchAll();
            $thread_id = (int) $_REQUEST['thread_id'];
	    $thread = filter_var($thread, FILTER_SANITIZE_STRING);
            $thread = $SQL->query("SELECT " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('post_topic') . ", " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('id') . ", " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('section') . ", " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('icon_id') . " FROM " . $SQL->tableName('z_forum') . " WHERE " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('id') . " = ".(int) $thread_id." AND " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('first_post') . " = ".(int) $thread_id." LIMIT 1")->fetch();
			
			$main_content .= '
				<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=100%>
					<TR>
						<TD><IMG SRC="'.$layout_name.'/images/global/general/blank.gif" WIDTH=10 HEIGHT=1 BORDER=0></TD>
						<TD WIDTH=100% ALIGN=right>
							<a href="?subtopic=forum" >Community Boards</a> | 
							<a href="?subtopic=forum&action=show_board&id='.$thread['section'].'">'.$sections[$thread['section']].'</a> | 
							<a href="?subtopic=forum&action=show_thread&id='.$thread_id.'">'.htmlspecialchars($thread['post_topic']).'</a> | 
							<b>Post New Reply</b></TD>
						<TD><IMG SRC="'.$layout_name.'/images/global/general/blank.gif" WIDTH=10 HEIGHT=1 BORDER=0></TD>
					</TR>
				</TABLE>
				<br>
			';
			
			if(isset($_POST['preview_new_post'])){
				$main_content .= '
					<table border=0 cellpadding=4 cellspacing=1 width=100%>
						<tr>
							<td  bgcolor="#505050" class="ff_whitelarge" colspan=1 align="left" NOWRAP ><b>Message Preview</b></td>
						</tr>
						<tr>
							<td style="position:relative; height:100%;background-color:#D4C0A1;" align="top" >
								<div style="position:relative; min-height:18px; width:100%; overflow-x:auto; overflow-y:visible; width:734px; word-wrap:break-word;">
								'.showPreview(htmlspecialchars($thread['post_topic']), $_POST['text'], $thread['post_smile']).'
								</div>
							</td>
						</tr>
					</table><br><br>';
			}
				
            if(isset($thread['id']))
            {
                $quote = (int) $_REQUEST['quote'];
                $text = trim(codeLower($_REQUEST['text']));
				$forum_iconid = (int) $_REQUEST['forum_iconid'];
                $char_id = (int) $_REQUEST['char_id'];
                $post_topic = trim($_REQUEST['topic']);
                $smile = (int) $_REQUEST['smile'];
                $saved = false;
                if(isset($_REQUEST['quote']))
                {
		    $quoted_post = filter_var($quoted_post, FILTER_SANITIZE_STRING);
                    $quoted_post = $SQL->query("SELECT " . $SQL->tableName('players') . "." . $SQL->fieldName('name') . ", " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('post_text') . ", " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('post_date') . " FROM " . $SQL->tableName('players') . ", " . $SQL->tableName('z_forum') . " WHERE " . $SQL->tableName('players') . "." . $SQL->fieldName('id') . " = " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('author_guid') . " AND " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('id') . " = ".(int) $quote)->fetchAll();
                    if(isset($quoted_post[0]['name']))
                        $text = '[i]Originally posted by '.$quoted_post[0]['name'].' on '.date('d/m/y H:i:s', $quoted_post[0]['post_date']).':[/i][quote]'.$quoted_post[0]['post_text'].'[/quote]';
                }
                if(isset($_POST['save_post']) && $_POST['save'] == "save")
                {
                    $lenght = 0;
                    for($i = 0; $i <= strlen($text); $i++)
                    {
                        if(ord($text[$i]) >= 33 && ord($text[$i]) <= 126)
                            $lenght++;
                    }
                    if($lenght < 1 || strlen($text) > 15000)
                        $errors[] = 'Too short or too long post (short: '.$lenght.' long: '.strlen($text).' letters). Minimum 1 letter, maximum 15000 letters.';
                    if($char_id == 0)
                        $errors[] = 'Please select a character.';
                    $player_on_account == false;
                    if(count($errors) == 0)
                    {
                        foreach($players_from_account as $player)
                            if($char_id == $player['id'])
                                $player_on_account = true;
                        if(!$player_on_account)
                            $errors[] = 'Player with selected ID '.$char_id.' doesn\'t exist or isn\'t on your account';
                    }
                    if(count($errors) == 0)
                    {
                        $last_post = $account_logged->getCustomField('last_post');
                        if($last_post+$post_interval-time() > 0 && $group_id_of_acc_logged < $group_not_blocked)
                            $errors[] = 'You can post one time per '.$post_interval.' seconds. Next post after '.($last_post+$post_interval-time()).' second(s).';
                    }
                    if(count($errors) == 0)
                    {
                        $saved = true;
                        $account_logged->set('last_post', time());
						$account_logged->save();
                        $insert_post = $SQL->query("INSERT INTO " . $SQL->tableName('z_forum') . " (
											" . $SQL->fieldName('first_post') . ",
											" . $SQL->fieldName('last_post') . ",
											" . $SQL->fieldName('section') . ",
											" . $SQL->fieldName('replies') . ",
											" . $SQL->fieldName('views') . ",
											" . $SQL->fieldName('author_aid') . ",
											" . $SQL->fieldName('author_guid') . ",
											" . $SQL->fieldName('post_text') . ",
											" . $SQL->fieldName('post_topic') . ",
											" . $SQL->fieldName('post_smile') . ",
											" . $SQL->fieldName('post_date') . ",
											" . $SQL->fieldName('last_edit_aid') . ",
											" . $SQL->fieldName('edit_date') . ", 
											" . $SQL->fieldName('post_ip') . ", 
											" . $SQL->fieldName('icon_id') . "
									) VALUES (
											'".$thread['id']."', 
											'0', 
											'".$thread['section']."', 
											'0', 
											'0', 
											'".$account_logged->getId()."', 
											'".(int) $char_id."', 
											".$SQL->quote($text).", 
											".$SQL->quote($post_topic).", 
											'".(int) $smile."', 
											'".time()."', 
											'0', 
											'0', 
											'".$_SERVER['REMOTE_ADDR']."', 
											'".(int) $forum_iconid."'
									)
							");
						
                        $SQL->query("UPDATE " . $SQL->tableName('z_forum') . " SET " . $SQL->fieldName('replies') . "=" . $SQL->fieldName('replies') . "+1, " . $SQL->fieldName('last_post') . "=".time()." WHERE " . $SQL->fieldName('id') . " = ".(int) $thread_id);
						
			$post_page = filter_var($post_page, FILTER_SANITIZE_STRING);
                        $post_page = $SQL->query("SELECT COUNT(" . $SQL->tableName('z_forum') . "." . $SQL->fieldName('id') . ") AS posts_count FROM " . $SQL->tableName('players') . ", " . $SQL->tableName('z_forum') . " WHERE " . $SQL->tableName('players') . "." . $SQL->fieldName('id') . " = " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('author_guid') . " AND " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('post_date') . " <= ".time()." AND " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('first_post') . " = ".(int) $thread['id'])->fetch();
						
                        $page = (int) ceil($post_page['posts_count'] / $threads_per_page) - 1;
                        header('Location: ?subtopic=forum&action=show_thread&id='.$thread_id.'&page='.$page);
                        $main_content .= '<br />Thank you for posting.<br /><a href="?subtopic=forum&action=show_thread&id='.$thread_id.'">GO BACK TO LAST THREAD</a>';
                    }
                }
                if(!$saved)
                {
                    if(count($errors) > 0)
                    {
                        $main_content .= '<font color="red" size="2"><b>Errors occured:</b>';
                        foreach($errors as $error)
                            $main_content .= '<br />* '.$error;
                        $main_content .= '</font><br />';
                    }
                    $main_content .= '
					<table border=0 cellpadding=4 cellspacing=1 width="97%">
						<form action="?" method="POST">
							<input type="hidden" name="action" value="new_post" />
							<input type="hidden" name="thread_id" value="'.$thread_id.'" />
							<input type="hidden" name="subtopic" value="forum" />
							<input type="hidden" name="save" value="save" />
								<tr>
									<td  bgcolor="#505050" class="ff_whitelarge" colspan=2 align="left" >Post New Reply</td>
								</tr>
								<tr>
									<td  bgcolor="#D4C0A1" class="ff_std" colspan=1 align="left" valign="top" ><b>'.$config['server']['serverName'].' Character:</b></td>
									<td  bgcolor="#D4C0A1" class="ff_std" colspan=1 align="left" >
										<select name="char_id">
											<option value="0">(Choose character)</option>';
						foreach($players_from_account as $player)
						{
							$main_content .= '<option value="'.$player['id'].'"';
							if($player['id'] == $char_id)
								$main_content .= ' selected="selected"';
							$main_content .= '>'.$player['name'].'</option>';
						}
									$main_content .= '
										</select>
									</td>
								</tr>
								<tr>
									<td  bgcolor="#F1E0C6" class="ff_std" colspan=1 align="left" valign="top" ><b>Post Subject:</b></td>
									<td  bgcolor="#F1E0C6" class="ff_std" colspan=1 align="left" >
										<input type="text" name="topic" value="'.htmlspecialchars($post_topic).'" size="40" maxlength="60" /> 
										<font class="ff_info"> (Optional)</font>
									</td>
								</tr>
								<tr>
									<td  bgcolor="#D4C0A1" class="ff_std" colspan=1 align="left" valign="top" ><b>Post Icon:</b></td>
									<td  bgcolor="#D4C0A1" class="ff_std" colspan=1 align="left" >
										<input type=radio name="forum_iconid" value="11">
										&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/11.gif" border=0 width=15 height=15 alt="Stuck Tongue Out">&nbsp;&nbsp;&nbsp;
										<input type=radio name="forum_iconid" value="12">
										&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/12.gif" border=0 width=15 height=15 alt="Eek">&nbsp;&nbsp;&nbsp;
										<input type=radio name="forum_iconid" value="13">
										&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/13.gif" border=0 width=15 height=15 alt="Roll Eyes">&nbsp;&nbsp;&nbsp;
										<input type=radio name="forum_iconid" value="14">
										&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/14.gif" border=0 width=15 height=15 alt="Thumbs up">&nbsp;&nbsp;&nbsp;
										<input type=radio name="forum_iconid" value="15">
										&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/15.gif" border=0 width=15 height=15 alt="Thumbs down">&nbsp;&nbsp;&nbsp;
										<input type=radio name="forum_iconid" value="16">
										&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/16.gif" border=0 width=15 height=15 alt="Wink">&nbsp;&nbsp;&nbsp;
										<input type=radio name="forum_iconid" value="17">
										&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/17.gif" border=0 width=15 height=15 alt="Red face">&nbsp;&nbsp;&nbsp;<br>
										<input type=radio name="forum_iconid" value="18">
										&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/18.gif" border=0 width=15 height=15 alt="Talking">&nbsp;&nbsp;&nbsp;
										<input type=radio name="forum_iconid" value="19">
										&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/19.gif" border=0 width=15 height=15 alt="Unhappy">&nbsp;&nbsp;&nbsp;
										<input type=radio name="forum_iconid" value="20">
										&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/20.gif" border=0 width=15 height=15 alt="Angry">&nbsp;&nbsp;&nbsp;
										<input type=radio name="forum_iconid" value="21">
										&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/21.gif" border=0 width=15 height=15 alt="Smile">&nbsp;&nbsp;&nbsp;
										<input type=radio name="forum_iconid" value="22">
										&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/22.gif" border=0 width=15 height=15 alt="Cool">&nbsp;&nbsp;&nbsp;
										<input type=radio name="forum_iconid" value="23">
										&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/23.gif" border=0 width=15 height=15 alt="Question">&nbsp;&nbsp;&nbsp;
										<input type=radio name="forum_iconid" value="24">
										&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/24.gif" border=0 width=15 height=15 alt="Exclamation">&nbsp;&nbsp;&nbsp;<br>
										<input type=radio name="forum_iconid" value="25">
										&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/25.gif" border=0 width=15 height=15 alt="Lightbulb">&nbsp;&nbsp;&nbsp;
										<input type=radio name="forum_iconid" value="26">
										&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/26.gif" border=0 width=15 height=15 alt="Arrow">&nbsp;&nbsp;&nbsp;
										<input type=radio name="forum_iconid" value="27">
										&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/27.gif" border=0 width=15 height=15 alt="Post">&nbsp;&nbsp;&nbsp;<br>
										<input type=radio name="forum_iconid" value="0" checked>
										&nbsp;No Icon</td>
								</tr>
                    			<tr>
									<td  bgcolor="#F1E0C6" class="ff_std" colspan=1 align="left" valign="top" ><b>Message:</b><br>
										<br>
										<font class="ff_info">Replace codes are allowed.<br><br>
										How to use smileys:<br>
										<table border=0 cellpadding=2 cellspacing=0 width=100%>
											<tr>
												<td  colspan=1 align="left" ><img src="'.$layout_name.'/images/global/forum/smile/1.gif" border=0 width=15 height=15 alt="Stuck Tongue Out"></td>
												<td  colspan=1 align="left" >:p</td>
											</tr>
											<tr>
												<td  colspan=1 align="left" ><img src="'.$layout_name.'/images/global/forum/smile/2.gif" border=0 width=15 height=15 alt="Eek"></td>
												<td  colspan=1 align="left" >:eek:</td>
											</tr>
											<tr>
												<td  colspan=1 align="left" ><img src="'.$layout_name.'/images/global/forum/smile/3.gif" border=0 width=15 height=15 alt="Roll Eyes"></td>
												<td  colspan=1 align="left" >:rolleyes:</td>
											</tr>
											<tr>
												<td  colspan=1 align="left" ><img src="'.$layout_name.'/images/global/forum/smile/4.gif" border=0 width=15 height=15 alt="Wink"></td>
												<td  colspan=1 align="left" >;)</td>
											</tr>
											<tr>
												<td  colspan=1 align="left" ><img src="'.$layout_name.'/images/global/forum/smile/5.gif" border=0 width=15 height=15 alt="Red face"></td>
												<td  colspan=1 align="left" >:o</td>
											</tr>
											<tr>
												<td  colspan=1 align="left" ><img src="'.$layout_name.'/images/global/forum/smile/6.gif" border=0 width=15 height=15 alt="Talking"></td>
												<td  colspan=1 align="left" >:D</td>
											</tr>
											<tr>
												<td  colspan=1 align="left" ><img src="'.$layout_name.'/images/global/forum/smile/7.gif" border=0 width=15 height=15 alt="Unhappy"></td>
												<td  colspan=1 align="left" >:(</td>
											</tr>
											<tr>
												<td  colspan=1 align="left" ><img src="'.$layout_name.'/images/global/forum/smile/8.gif" border=0 width=15 height=15 alt="Angry"></td>
												<td  colspan=1 align="left" >:mad:</td>
											</tr>
											<tr>

												<td  colspan=1 align="left" ><img src="'.$layout_name.'/images/global/forum/smile/9.gif" border=0 width=15 height=15 alt="Smile"></td>
												<td  colspan=1 align="left" >:)</td>
											</tr>
											<tr>
												<td  colspan=1 align="left" ><img src="'.$layout_name.'/images/global/forum/smile/10.gif" border=0 width=15 height=15 alt="Cool"></td>
												<td  colspan=1 align="left" >:cool:</td>
											</tr>
										</table>
										</font>
									</td>
									<td  bgcolor="#F1E0C6" class="ff_std" colspan=1 align="left" ><textarea rows=20 cols=55 name="text">'.htmlspecialchars($text).'</textarea></td>
								</tr>
                    			<tr>
									<td  bgcolor="#D4C0A1" class="ff_std" colspan=1 align="left" valign="top" ><b>Options:</td>
									<td  bgcolor="#D4C0A1" class="ff_info" colspan=1 align="left" ><input type="checkbox" name="smile" value="1"';
                    if($smile == 1)
                        $main_content .= ' checked="checked"';
                    $main_content .= '/>&nbsp;<b>Disable Smileys in This Post </b><br>
						</td>
					</tr>
					<tr>
						<td class="ff_std" colspan=2 align="center"><br>
							<input type=submit name="preview_new_post" value="Preview Reply">
							&nbsp;
							<input type="submit" name="save_post" value="Submit Message">
							&nbsp;
							<input type=reset name="reset" value="Reset Fields">
						</td>
				</FORM>
			</table>';
	 	    $threads = filter_var($threads, FILTER_SANITIZE_STRING);
                    $threads = $SQL->query("SELECT " . $SQL->tableName('players') . "." . $SQL->fieldName('name') . ", " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('post_text') . ", " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('post_topic') . ", " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('post_smile') . " FROM " . $SQL->tableName('players') . ", " . $SQL->tableName('z_forum') . " WHERE " . $SQL->tableName('players') . "." . $SQL->fieldName('id') . " = " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('author_guid') . " AND " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('first_post') . " = ".(int) $thread_id." ORDER BY " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('post_date') . " DESC LIMIT 10")->fetchAll();
						
                    $main_content .= '
						<br><br>
						<table border=0 cellpadding=5 cellspacing=1 width=100%>
							<tr>
								<td  bgcolor="#505050" class="ff_whitelarge" colspan=2 align="center" NOWRAP ><b>Thread Review (newest first)</b></td>
							</tr>';
                    foreach($threads as $thread)
                    {
                        if(is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
                        $main_content .= '
							<tr>
								<td  bgcolor="'.$bgcolor.'" class="ff_pagetext" colspan=1 align="left" valign="top" width=175 ><a href="#">'.$thread['name'].'</a></td>
								<td style="position:relative; height:100%;background-color:'.$bgcolor.';" align="top" >
									<div style="position:relative; min-height:18px; width:100%; overflow-x:auto; overflow-y:visible; width:538px; word-wrap:break-word;" >'.showPost(htmlspecialchars($thread['post_topic']), $thread['post_text'], $thread['post_smile']).'</div></td>
							</tr>';
                    }
                    
                }
            }
            else
                $main_content .= 'Thread with ID '.$thread_id.' doesn\'t exist.';
        }
        else
            $main_content .= 'Your account is banned, deleted or you don\'t have any player with level '.$level_limit.' on your account. You can\'t post.';
    }
    else
        $main_content .= 'Login first.';
}
$main_content .= '</table>';
if($action == 'edit_post')
{
    if($logged)
    {
        if(canPost($account_logged) || $group_id_of_acc_logged >= $group_not_blocked)
        {
            $post_id = (int) $_REQUEST['id'];
	    $thread = filter_var($thread, FILTER_SANITIZE_STRING);
            $thread = $SQL->query("SELECT " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('author_guid') . ", " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('author_aid') . ", " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('first_post') . ", " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('post_topic') . ", " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('post_date') . ", " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('post_text') . ", " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('post_smile') . ", " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('id') . ", " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('section') . " FROM " . $SQL->tableName('z_forum') . " WHERE " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('id') . " = ".(int) $post_id." LIMIT 1")->fetch();
            if(isset($thread['id']))
            {
		$first_post = filter_var($first_post, FILTER_SANITIZE_STRING);
                $first_post = $SQL->query("SELECT " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('author_guid') . ", " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('author_aid') . ", " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('first_post') . ", " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('post_topic') . ", " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('post_text') . ", " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('post_smile') . ", " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('id') . ", " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('section') . " FROM " . $SQL->tableName('z_forum') . " WHERE " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('id') . " = ".(int) $thread['first_post']." LIMIT 1")->fetch();
				
				$main_content .= '
					<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=100%>
					<TR>
						<TD><IMG SRC="'.$layout_name.'/images/global/general/blank.gif" WIDTH=10 HEIGHT=1 BORDER=0></TD>
						<TD WIDTH=100% ALIGN=right>
							<a href="?subtopic=forum" >Community Boards</a> | 
							<a href="?subtopic=forum&action=show_board&id='.$thread['section'].'">'.$sections[$thread['section']].'</a> | 
							<a href="?subtopic=forum&action=show_thread&id='.$thread['first_post'].'">'.htmlspecialchars($first_post['post_topic']).'</a> | 
							<b><a href="?subtopic=forum" >Post</a></b> | 
							<b>Edit Post</b></TD>
						<TD><IMG SRC="'.$layout_name.'/images/global/general/blank.gif" WIDTH=10 HEIGHT=1 BORDER=0></TD>
					</TR>
				</TABLE>
				<br>
				';
				
				if(isset($_POST['preview_edit_post'])){
				$main_content .= '
					<table border=0 cellpadding=4 cellspacing=1 width=100%>
						<tr>
							<td  bgcolor="#505050" class="ff_whitelarge" colspan=1 align="left" NOWRAP ><b>Message Preview</b></td>
						</tr>
						<tr>
							<td style="position:relative; height:100%;background-color:#D4C0A1;" align="top" >
								<div style="position:relative; min-height:18px; width:100%; overflow-x:auto; overflow-y:visible; width:734px; word-wrap:break-word;">
								'.showPreview(htmlspecialchars($thread['post_topic']), $_POST['text'], $thread['post_smile']).'
								</div>
							</td>
						</tr>
					</table><br><br>';
			}
					
                if($account_logged->getId() == $thread['author_aid'] || $group_id_of_acc_logged >= $group_not_blocked)
                {
		    $players_from_account = filter_var($players_from_account, FILTER_SANITIZE_STRING);
                    $players_from_account = $SQL->query("SELECT " . $SQL->tableName('players') . "." . $SQL->fieldName('name') . ", " . $SQL->tableName('players') . "." . $SQL->fieldName('id') . " FROM " . $SQL->tableName('players') . " WHERE " . $SQL->tableName('players') . "." . $SQL->fieldName('account_id') . " = ".(int) $account_logged->getId())->fetchAll();
                    $saved = false;
                    if(isset($_POST['edit_post']) && $_POST['save'] == "save")
                    {
                        $text = trim(codeLower($_REQUEST['text']));
                        $char_id = (int) $_REQUEST['char_id'];
						$forum_iconid = (int) $_REQUEST['forum_iconid'];
                        $post_topic = trim($_REQUEST['topic']);
                        $smile = (int) $_REQUEST['smile'];
                        $lenght = 0;
                        for($i = 0; $i <= strlen($post_topic); $i++)
                        {
                            if(ord($post_topic[$i]) >= 33 && ord($post_topic[$i]) <= 126)
                                $lenght++;
                        }
                        if(($lenght < 1 || strlen($post_topic) > 60) && $thread['id'] == $thread['first_post'])
                            $errors[] = 'Too short or too long topic (short: '.$lenght.' long: '.strlen($post_topic).' letters). Minimum 1 letter, maximum 60 letters.';
                        $lenght = 0;
                        for($i = 0; $i <= strlen($text); $i++)
                        {
                            if(ord($text[$i]) >= 33 && ord($text[$i]) <= 126)
                                $lenght++;
                        }
                        if($lenght < 1 || strlen($text) > 15000)
                            $errors[] = 'Too short or too long post (short: '.$lenght.' long: '.strlen($text).' letters). Minimum 1 letter, maximum 15000 letters.';
                        if($char_id == 0)
                            $errors[] = 'Please select a character.';
                        if(empty($post_topic) && $thread['id'] == $thread['first_post'])
                            $errors[] = 'Thread topic can\'t be empty.';
                        $player_on_account == false;
                        if(count($errors) == 0)
                        {
                            foreach($players_from_account as $player)
                                if($char_id == $player['id'])
                                    $player_on_account = true;
                            if(!$player_on_account)
                                $errors[] = 'Player with selected ID '.$char_id.' doesn\'t exist or isn\'t on your account';
                        }
                        if(count($errors) == 0)
                        {
                            $saved = true;
                            if($account_logged->getId() != $thread['author_aid'])
                                $char_id = $thread['author_guid'];
                            $SQL->query("UPDATE " . $SQL->tableName('z_forum') . " SET " . $SQL->fieldName('author_guid') . " = ".(int) $char_id.", " . $SQL->fieldName('post_text') . " = ".$SQL->quote($text).", " . $SQL->fieldName('post_topic') . " = ".$SQL->quote($post_topic).", " . $SQL->fieldName('post_smile') . " = ".(int) $smile.", " . $SQL->fieldName('last_edit_aid') . " = ".(int) $account_logged->getId()."," . $SQL->fieldName('edit_date') . " = ".time().", " . $SQL->fieldName('icon_id')." = " .(int) $forum_iconid. " WHERE " . $SQL->fieldName('id') . " = ".(int) $thread['id']);
                            $post_page = $SQL->query("SELECT COUNT(" . $SQL->tableName('z_forum') . "." . $SQL->fieldName('id') . ") AS posts_count FROM " . $SQL->tableName('players') . ", " . $SQL->tableName('z_forum') . " WHERE " . $SQL->tableName('players') . "." . $SQL->fieldName('id') . " = " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('author_guid') . " AND " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('post_date') . " <= ".$thread['post_date']." AND " . $SQL->tableName('z_forum') . "." . $SQL->fieldName('first_post') . " = ".(int) $thread['first_post'])->fetch();
                            $page = (int) ceil($post_page['posts_count'] / $threads_per_page) - 1;
                            header('Location: ?subtopic=forum&action=show_thread&id='.$thread['first_post'].'&page='.$page);
                            $main_content .= '<br />Thank you for editing post.<br /><a href="?subtopic=forum&action=show_thread&id='.$thread['first_post'].'">GO BACK TO LAST THREAD</a>';
                        }
                    }
                    else
                    {
                        $text = $thread['post_text'];
                        $char_id = (int) $thread['author_guid'];
                        $post_topic = $thread['post_topic'];
                        $smile = (int) $thread['post_smile'];
                    }
                    if(!$saved)
                    {
                        if(count($errors) > 0)
                        {
                            $main_content .= '<br /><font color="red" size="2"><b>Errors occured:</b>';
                            foreach($errors as $error)
                                $main_content .= '<br />* '.$error;
                            $main_content .= '</font>';
                        }
                        $main_content .= '
							<table border=0 cellpadding=4 cellspacing=1>
							<form action="?" method="POST">
								<input type="hidden" name="action" value="edit_post" />
								<input type="hidden" name="id" value="'.$post_id.'" />
								<input type="hidden" name="subtopic" value="forum" />
								<input type="hidden" name="save" value="save" />
								<tr>
									<td  bgcolor="#505050" class="ff_whitelarge" colspan=2 align="left" >Edit Post</td>
								</tr>
								<tr>
									<td  bgcolor="#D4C0A1" class="ff_std" colspan=1 align="left" valign="top" ><b>'.$config['server']['serverName'].' Character:</b><br>
										<font class="ff_smallinfo">(This will appear as "Edited by ..." in the post)</font>
									</td>
									<td  bgcolor="#D4C0A1" class="ff_std" colspan=1 align="left" >
										<select name="char_id">
											<option value="0">(Choose character)</option>';
                        foreach($players_from_account as $player)
                        {
                            $main_content .= '<option value="'.$player['id'].'"';
                            if($player['id'] == $char_id)
                                $main_content .= ' selected="selected"';
                            $main_content .= '>'.$player['name'].'</option>';
                        }
							$main_content .= '
								</select>
							</td>
						</tr>
						<tr>
							<td  bgcolor="#F1E0C6" class="ff_std" colspan=1 align="left" valign="top" ><b>Post Subject:</b></td>
							<td  bgcolor="#F1E0C6" class="ff_std" colspan=1 align="left" ><input type="text" value="'.htmlspecialchars($post_topic).'" name="topic" size="40" maxlength="60" /><font class="ff_info"> (Optional)</font></td>
						</tr>
						<tr>
							<td  bgcolor="#D4C0A1" class="ff_std" colspan=1 align="left" valign="top" ><b>Post Icon:</b></td>
							<td  bgcolor="#D4C0A1" class="ff_std" colspan=1 align="left" ><input type=radio name="forum_iconid" value="11">
								&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/11.gif" border=0 width=15 height=15 alt="Stuck Tongue Out">&nbsp;&nbsp;&nbsp;
								<input type=radio name="forum_iconid" value="12">
								&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/12.gif" border=0 width=15 height=15 alt="Eek">&nbsp;&nbsp;&nbsp;
								<input type=radio name="forum_iconid" value="13">
								&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/13.gif" border=0 width=15 height=15 alt="Roll Eyes">&nbsp;&nbsp;&nbsp;
								<input type=radio name="forum_iconid" value="14">
								&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/14.gif" border=0 width=15 height=15 alt="Thumbs up">&nbsp;&nbsp;&nbsp;
								<input type=radio name="forum_iconid" value="15">
								&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/15.gif" border=0 width=15 height=15 alt="Thumbs down">&nbsp;&nbsp;&nbsp;
								<input type=radio name="forum_iconid" value="16">
								&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/16.gif" border=0 width=15 height=15 alt="Wink">&nbsp;&nbsp;&nbsp;
								<input type=radio name="forum_iconid" value="17">
								&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/17.gif" border=0 width=15 height=15 alt="Red face">&nbsp;&nbsp;&nbsp;<br>
								<input type=radio name="forum_iconid" value="18">
								&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/18.gif" border=0 width=15 height=15 alt="Talking">&nbsp;&nbsp;&nbsp;
								<input type=radio name="forum_iconid" value="19">
								&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/19.gif" border=0 width=15 height=15 alt="Unhappy">&nbsp;&nbsp;&nbsp;
								<input type=radio name="forum_iconid" value="20">
								&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/20.gif" border=0 width=15 height=15 alt="Angry">&nbsp;&nbsp;&nbsp;
								<input type=radio name="forum_iconid" value="21">
								&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/21.gif" border=0 width=15 height=15 alt="Smile">&nbsp;&nbsp;&nbsp;
								<input type=radio name="forum_iconid" value="22">
								&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/22.gif" border=0 width=15 height=15 alt="Cool">&nbsp;&nbsp;&nbsp;
								<input type=radio name="forum_iconid" value="23">
								&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/23.gif" border=0 width=15 height=15 alt="Question">&nbsp;&nbsp;&nbsp;
								<input type=radio name="forum_iconid" value="24">
								&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/24.gif" border=0 width=15 height=15 alt="Exclamation">&nbsp;&nbsp;&nbsp;<br>
								<input type=radio name="forum_iconid" value="25">
								&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/25.gif" border=0 width=15 height=15 alt="Lightbulb">&nbsp;&nbsp;&nbsp;
								<input type=radio name="forum_iconid" value="26">
								&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/26.gif" border=0 width=15 height=15 alt="Arrow">&nbsp;&nbsp;&nbsp;
								<input type=radio name="forum_iconid" value="27">
								&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/27.gif" border=0 width=15 height=15 alt="Post">&nbsp;&nbsp;&nbsp;<br>
								<input type=radio name="forum_iconid" value="0" checked>
								&nbsp;No Icon</td>
						</tr>
                        <tr>
							<td  bgcolor="#F1E0C6" class="ff_std" colspan=1 align="left" valign="top" ><b>Message:</b><br>
							<br>
							<font class="ff_info">Replace codes are allowed.<br><br>
							How to use smileys:<br>
							<table border=0 cellpadding=2 cellspacing=0 width=100%>
							<tr>
								<td  colspan=1 align="left" ><img src="'.$layout_name.'/images/global/forum/smile/1.gif" border=0 width=15 height=15 alt="Stuck Tongue Out"></td>
								<td  colspan=1 align="left" >:p</td>
							</tr>
							<tr>
							<td  colspan=1 align="left" ><img src="'.$layout_name.'/images/global/forum/smile/2.gif" border=0 width=15 height=15 alt="Eek"></td>
							<td  colspan=1 align="left" >:eek:</td>
							</tr>
							<tr>
								<td  colspan=1 align="left" ><img src="'.$layout_name.'/images/global/forum/smile/3.gif" border=0 width=15 height=15 alt="Roll Eyes"></td>
								<td  colspan=1 align="left" >:rolleyes:</td>
							</tr>
							<tr>
								<td  colspan=1 align="left" ><img src="'.$layout_name.'/images/global/forum/smile/4.gif" border=0 width=15 height=15 alt="Wink"></td>
								<td  colspan=1 align="left" >;)</td>
							</tr>
							<tr>
								<td  colspan=1 align="left" ><img src="'.$layout_name.'/images/global/forum/smile/5.gif" border=0 width=15 height=15 alt="Red face"></td>
								<td  colspan=1 align="left" >:o</td>
							</tr>
							<tr>
								<td  colspan=1 align="left" ><img src="'.$layout_name.'/images/global/forum/smile/6.gif" border=0 width=15 height=15 alt="Talking"></td>
								<td  colspan=1 align="left" >:D</td>
							</tr>
							<tr>
								<td  colspan=1 align="left" ><img src="'.$layout_name.'/images/global/forum/smile/7.gif" border=0 width=15 height=15 alt="Unhappy"></td>
								<td  colspan=1 align="left" >:(</td>
							</tr>
							<tr>
								<td  colspan=1 align="left" ><img src="'.$layout_name.'/images/global/forum/smile/8.gif" border=0 width=15 height=15 alt="Angry"></td>
								<td  colspan=1 align="left" >:mad:</td>
							</tr>
							<tr>
								<td  colspan=1 align="left" ><img src="'.$layout_name.'/images/global/forum/smile/9.gif" border=0 width=15 height=15 alt="Smile"></td>
								<td  colspan=1 align="left" >:)</td>
							</tr>
							<tr>
								<td  colspan=1 align="left" ><img src="'.$layout_name.'/images/global/forum/smile/10.gif" border=0 width=15 height=15 alt="Cool"></td>
								<td  colspan=1 align="left" >:cool:</td>
							</tr>
						</table>
					</font>
					</td>
					<td  bgcolor="#F1E0C6" class="ff_std" colspan=1 align="left" ><textarea rows=20 cols=55 name="text">'.htmlspecialchars($text).'</textarea><div id="forum_pt_lenght">4094 characters left.</div></td>
						</tr>
                        <tr>
							<td  bgcolor="#D4C0A1" class="ff_std" colspan=1 align="left" valign="top" ><b>Options:</td>
							<td  bgcolor="#D4C0A1" class="ff_info" colspan=1 align="left" ><input type="checkbox" name="smile" value="1"';
                        if($smile == 1)
                            $main_content .= ' checked="checked"';
                        $main_content .= '/><strong>Disable Smileys in This Post</strong> </td>
					</tr>
				
					<tr>
						<td  class="ff_std" colspan=2 align="center" ><br>
							<input type=submit name="preview_edit_post" value="Preview Changes"  >
							&nbsp;
							<input type="submit" name="edit_post" value="Submit Message">
							&nbsp;
							<input type=reset name="reset" value="Reset Fields">
						</td>
					</tr>
				</table>
			</form>';
                    }
                }
                else
                    $main_content .= '<br />You are not an author of this post.';
            }
            else
                $main_content .= '<br />Post with ID '.$post_id.' doesn\'t exist.';
        }
        else
            $main_content .= '<br />Your account is banned, deleted or you don\'t have any player with level '.$level_limit.' on your account. You can\'t post.';
    }
    else
        $main_content .= '<br />Login first.';
}

if($action == 'new_topic')
{
    if($logged)
    {
        if(canPost($account_logged) || $group_id_of_acc_logged >= $group_not_blocked)
        {
	    $players_from_account = filter_var($players_from_account, FILTER_SANITIZE_STRING);
            $players_from_account = $SQL->query("SELECT " . $SQL->tableName('players') . "." . $SQL->fieldName('name') . ", " . $SQL->tableName('players') . "." . $SQL->fieldName('id') . " FROM " . $SQL->tableName('players') . " WHERE " . $SQL->tableName('players') . "." . $SQL->fieldName('account_id') . " = ".(int) $account_logged->getId())->fetchAll();
			
            $section_id = (int) $_REQUEST['section_id'];
			
			$main_content .= '
				<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=100%>
					<TR>
						<TD><IMG SRC="'.$layout_name.'/images/global/general/blank.gif" WIDTH=10 HEIGHT=1 BORDER=0></TD>
						<TD WIDTH=100% ALIGN=right>
							<a href="?subtopic=forum" >Community Boards</a> | 
							<a href="?subtopic=forum&action=show_board&id='.$section_id.'">'.$sections[$section_id].'</a> | 
							<b>Post New Thread</b></TD>
						<TD><IMG SRC="'.$layout_name.'/images/global/general/blank.gif" WIDTH=10 HEIGHT=1 BORDER=0></TD>
					</TR>
				</TABLE>
				<br>
			';
			
			if(isset($_POST['preview_new_topic'])){
				$main_content .= '
					<table border=0 cellpadding=4 cellspacing=1 width=100%>
						<tr>
							<td  bgcolor="#505050" class="ff_whitelarge" colspan=1 align="left" NOWRAP ><b>Message Preview</b></td>
						</tr>
						<tr>
							<td style="position:relative; height:100%;background-color:#D4C0A1;" align="top" >
								<div style="position:relative; min-height:18px; width:100%; overflow-x:auto; overflow-y:visible; width:734px; word-wrap:break-word;">
								'.showPreview(htmlspecialchars($thread['post_topic']), $_POST['text'], $thread['post_smile']).'
								</div>
							</td>
						</tr>
					</table><br><br>';
			}
				
            if(isset($sections[$section_id]))
            {
                if($section_id == 1 && $group_id_of_acc_logged < $group_not_blocked)
                    $errors[] = 'Only moderators and admins can post on news board.';
                $quote = (int) $_REQUEST['quote'];
                $text = trim(codeLower($_REQUEST['text']));
				$forum_iconid = (int) $_REQUEST['forum_iconid'];
                $char_id = (int) $_REQUEST['char_id'];
                $post_topic = trim($_REQUEST['topic']);
                $smile = (int) $_REQUEST['smile'];
                $saved = false;
				
                if(isset($_POST['save_topic']) && $_POST['save'] == "save")
                {
                    $lenght = 0;
                    for($i = 0; $i <= strlen($post_topic); $i++)
                    {
                        if(ord($post_topic[$i]) >= 33 && ord($post_topic[$i]) <= 126)
                            $lenght++;
                    }
                    if($lenght < 1 || strlen($post_topic) > 60)
                        $errors[] = 'Too short or too long topic (short: '.$lenght.' long: '.strlen($post_topic).' letters). Minimum 1 letter, maximum 60 letters.';
                    $lenght = 0;
                    for($i = 0; $i <= strlen($text); $i++)
                    {
                        if(ord($text[$i]) >= 33 && ord($text[$i]) <= 126)
                            $lenght++;
                    }
                    if($lenght < 1 || strlen($text) > 15000)
                        $errors[] = 'Too short or too long post (short: '.$lenght.' long: '.strlen($text).' letters). Minimum 1 letter, maximum 15000 letters.';
                    if($char_id == 0)
                        $errors[] = 'Please select a character.';
                    $player_on_account == false;
                    if(count($errors) == 0)
                    {
                        foreach($players_from_account as $player)
                            if($char_id == $player['id'])
                                $player_on_account = true;
                        if(!$player_on_account)
                            $errors[] = 'Player with selected ID '.$char_id.' doesn\'t exist or isn\'t on your account';
                    }
                    if(count($errors) == 0)
                    {
                        $last_post = $account_logged->getCustomField('last_post');
                        if($last_post+$post_interval-time() > 0 && $group_id_of_acc_logged < $group_not_blocked)
                            $errors[] = 'You can post one time per '.$post_interval.' seconds. Next post after '.($last_post+$post_interval-time()).' second(s).';
                    }

					if(isset($_POST['save_topic'])){
						if(count($errors) == 0)
						{
							$saved = true;
							$account_logged->set('last_post', time());
							$account_logged->save();
							
							$insert_topic = filter_var($insert_topic, FILTER_SANITIZE_STRING);
							$insert_topic = $SQL->query("INSERT INTO " . $SQL->tableName('z_forum') . " (
													" . $SQL->fieldName('first_post') . " ,
													" . $SQL->fieldName('last_post') . " ,
													" . $SQL->fieldName('section') . " ,
													" . $SQL->fieldName('replies') . " ,
													" . $SQL->fieldName('views') . " ,
													" . $SQL->fieldName('author_aid') . " ,
													" . $SQL->fieldName('author_guid') . " ,
													" . $SQL->fieldName('post_text') . " ,
													" . $SQL->fieldName('post_topic') . " ,
													" . $SQL->fieldName('post_smile') . " ,
													" . $SQL->fieldName('post_date') . " ,
													" . $SQL->fieldName('last_edit_aid') . " ,
													" . $SQL->fieldName('edit_date') . ", 
													" . $SQL->fieldName('post_ip') . ", 
													" . $SQL->fieldName('icon_id') . ",
													" . $SQL->fieldName('news_icon') . "
										) VALUES (
													'0', 
													'".time()."', 
													'".(int) $section_id."', 
													'0', 
													'0', 
													'".$account_logged->getId()."', 
													'".(int) $char_id."', 
													".$SQL->quote($text).", 
													".$SQL->quote($post_topic).", 
													'".(int) $smile."', 
													'".time()."', 
													'0', 
													'0', 
													'".$_SERVER['REMOTE_ADDR']."', 
													'".(int )$forum_iconid."',
													".$SQL->quote($_POST['news_icon'])."
										)"
								);
								
							if(!$insert_topic)
								$main_content .= mysql_error();
								
							$thread_id = $SQL->lastInsertId();
							$SQL->query("UPDATE " . $SQL->tableName('z_forum') . " SET " . $SQL->fieldName('first_post') . "=".(int) $thread_id." WHERE " . $SQL->fieldName('id') . " = ".(int) $thread_id);
							header('Location: ?subtopic=forum&action=show_thread&id='.$thread_id);
							$main_content .= '<br />Thank you for posting.<br /><a href="?subtopic=forum&action=show_thread&id='.$thread_id.'">GO BACK TO LAST THREAD</a>';
						}
					}
                }
                if(!$saved)
                {
                    if(count($errors) > 0)
                    {
                        $main_content .= '<font color="red" size="2"><b>Errors occured:</b>';
                        foreach($errors as $error)
                            $main_content .= '<br />* '.$error;
                        $main_content .= '</font><br />';
                    }
					
                    $main_content .= '
					<table border=0 cellpadding=4 cellspacing=1 width="100%">
						<form action="" method="POST">
							<input type="hidden" name="action" value="new_topic" />
							<input type="hidden" name="section_id" value="'.$section_id.'" />
							<input type="hidden" name="subtopic" value="forum" />
							<input type="hidden" name="save" value="save" />
							<tr>
								<td  bgcolor="#505050" class="ff_whitelarge" colspan=2 align="left" >Post New Thread</td>
							</tr>
							<tr>
								<td  bgcolor="#D4C0A1" class="ff_std" colspan=1 align="left" valign="top" ><b>'.$config['server']['serverName'].' Character:</b></td>
								<td  bgcolor="#D4C0A1" class="ff_std" colspan=1 align="left" >
									<select name="char_id">
										<option value="0">(Choose character)</option>';
                    foreach($players_from_account as $player)
                    {
                        $main_content .= '<option value="'.$player['id'].'"';
                        if($player['id'] == $char_id)
                            $main_content .= ' selected="selected"';
                        $main_content .= '>'.$player['name'].'</option>';
                    }
                    $main_content .= '
						</select>
					</td>
				</tr>
				<tr>
					<td  bgcolor="#F1E0C6" class="ff_std" colspan=1 align="left" valign="top" ><b>Thread Subject:</b></td>
					<td  bgcolor="#F1E0C6" class="ff_std" colspan=1 align="left" >
						<input type="text" name="topic" value="'.htmlspecialchars($post_topic).'" size="40" maxlength="60" />
					</td>
				</tr>
                <tr>
					<td  bgcolor="#D4C0A1" class="ff_std" colspan=1 align="left" valign="top" ><b>Thread Icon:</b></td>
					<td  bgcolor="#D4C0A1" class="ff_std" colspan=1 align="left" >
						<input type=radio name="forum_iconid" value="11">
						&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/11.gif" border=0 width=15 height=15 alt="Stuck Tongue Out">&nbsp;&nbsp;&nbsp;
						<input type=radio name="forum_iconid" value="12">
						&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/12.gif" border=0 width=15 height=15 alt="Eek">&nbsp;&nbsp;&nbsp;
						<input type=radio name="forum_iconid" value="13">
						&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/13.gif" border=0 width=15 height=15 alt="Roll Eyes">&nbsp;&nbsp;&nbsp;
						<input type=radio name="forum_iconid" value="14">
						&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/14.gif" border=0 width=15 height=15 alt="Thumbs up">&nbsp;&nbsp;&nbsp;
						<input type=radio name="forum_iconid" value="15">
						&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/15.gif" border=0 width=15 height=15 alt="Thumbs down">&nbsp;&nbsp;&nbsp;
						<input type=radio name="forum_iconid" value="16">
						&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/16.gif" border=0 width=15 height=15 alt="Wink">&nbsp;&nbsp;&nbsp;
						<input type=radio name="forum_iconid" value="17">
						&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/17.gif" border=0 width=15 height=15 alt="Red face">&nbsp;&nbsp;&nbsp;<br>
						<input type=radio name="forum_iconid" value="18">
						&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/18.gif" border=0 width=15 height=15 alt="Talking">&nbsp;&nbsp;&nbsp;
						<input type=radio name="forum_iconid" value="19">
						&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/19.gif" border=0 width=15 height=15 alt="Unhappy">&nbsp;&nbsp;&nbsp;
						<input type=radio name="forum_iconid" value="20">
						&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/20.gif" border=0 width=15 height=15 alt="Angry">&nbsp;&nbsp;&nbsp;
						<input type=radio name="forum_iconid" value="21">
						&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/21.gif" border=0 width=15 height=15 alt="Smile">&nbsp;&nbsp;&nbsp;
						<input type=radio name="forum_iconid" value="22">
						&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/22.gif" border=0 width=15 height=15 alt="Cool">&nbsp;&nbsp;&nbsp;
						<input type=radio name="forum_iconid" value="23">
						&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/23.gif" border=0 width=15 height=15 alt="Question">&nbsp;&nbsp;&nbsp;
						<input type=radio name="forum_iconid" value="24">
						&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/24.gif" border=0 width=15 height=15 alt="Exclamation">&nbsp;&nbsp;&nbsp;<br>
						<input type=radio name="forum_iconid" value="25">
						&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/25.gif" border=0 width=15 height=15 alt="Lightbulb">&nbsp;&nbsp;&nbsp;
						<input type=radio name="forum_iconid" value="26">
						&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/26.gif" border=0 width=15 height=15 alt="Arrow">&nbsp;&nbsp;&nbsp;
						<input type=radio name="forum_iconid" value="27">
						&nbsp;<img src="'.$layout_name.'/images/global/forum/icons/27.gif" border=0 width=15 height=15 alt="Post">&nbsp;&nbsp;&nbsp;<br>
						<input type=radio name="forum_iconid" value="0" checked>
						&nbsp;No Icon</td>
				</tr>';
			if($section_id == 1 && $group_id_of_acc_logged >= $group_not_blocked)
				$main_content .= '
					<tr>
						<td  bgcolor="#D4C0A1" class="ff_std" colspan=1 align="left" valign="top" ><b>News Icon:</b></td>
						<td  bgcolor="#D4C0A1" class="ff_std" colspan=1 align="left" >
							<select name="news_icon">
								<option value="newsicon_community_big">Community</option>
								<option value="newsicon_development_big">Development</option>
								<option value="newsicon_technical_big">Technical</option>
							</select>
						</td>';
			$main_content .= '
				</tr>
					<tr>
						<td  bgcolor="#F1E0C6" class="ff_std" colspan=1 align="left" valign="top" ><b>Message:</b><br>
						<br>
						<font class="ff_info">Replace codes are allowed.<br><br>
						How to use smileys:<br>
						<table border=0 cellpadding=2 cellspacing=0 width=100%>
						<tr>
							<td  colspan=1 align="left" ><img src="'.$layout_name.'/images/global/forum/smile/1.gif" border=0 width=15 height=15 alt="Stuck Tongue Out"></td>
							<td  colspan=1 align="left" >:p</td>
						</tr>
						<tr>
						<td  colspan=1 align="left" ><img src="'.$layout_name.'/images/global/forum/smile/2.gif" border=0 width=15 height=15 alt="Eek"></td>
						<td  colspan=1 align="left" >:eek:</td>
						</tr>
						<tr>
							<td  colspan=1 align="left" ><img src="'.$layout_name.'/images/global/forum/smile/3.gif" border=0 width=15 height=15 alt="Roll Eyes"></td>
							<td  colspan=1 align="left" >:rolleyes:</td>
						</tr>
						<tr>
							<td  colspan=1 align="left" ><img src="'.$layout_name.'/images/global/forum/smile/4.gif" border=0 width=15 height=15 alt="Wink"></td>
							<td  colspan=1 align="left" >;)</td>
						</tr>
						<tr>
							<td  colspan=1 align="left" ><img src="'.$layout_name.'/images/global/forum/smile/5.gif" border=0 width=15 height=15 alt="Red face"></td>
							<td  colspan=1 align="left" >:o</td>
						</tr>
						<tr>
							<td  colspan=1 align="left" ><img src="'.$layout_name.'/images/global/forum/smile/6.gif" border=0 width=15 height=15 alt="Talking"></td>
							<td  colspan=1 align="left" >:D</td>
						</tr>
						<tr>
							<td  colspan=1 align="left" ><img src="'.$layout_name.'/images/global/forum/smile/7.gif" border=0 width=15 height=15 alt="Unhappy"></td>
							<td  colspan=1 align="left" >:(</td>
						</tr>
						<tr>
							<td  colspan=1 align="left" ><img src="'.$layout_name.'/images/global/forum/smile/8.gif" border=0 width=15 height=15 alt="Angry"></td>
							<td  colspan=1 align="left" >:mad:</td>
						</tr>
						<tr>
							<td  colspan=1 align="left" ><img src="'.$layout_name.'/images/global/forum/smile/9.gif" border=0 width=15 height=15 alt="Smile"></td>
							<td  colspan=1 align="left" >:)</td>
						</tr>
						<tr>
							<td  colspan=1 align="left" ><img src="'.$layout_name.'/images/global/forum/smile/10.gif" border=0 width=15 height=15 alt="Cool"></td>
							<td  colspan=1 align="left" >:cool:</td>
						</tr>
					</table>
				</font>
				</td>
				<td  bgcolor="#F1E0C6" class="ff_std" colspan=1 align="left" ><textarea rows=20 cols=55 name="text">'.htmlspecialchars($text).'</textarea><br /></td>
				</tr>
                <tr>
					<td  bgcolor="#D4C0A1" class="ff_std" colspan=1 align="left" valign="top" ><b>Options:</td>
					<td  bgcolor="#D4C0A1" class="ff_info" colspan=1 align="left" ><input type="checkbox" name="smile" value="1"';
                    if($smile == 1)
                        $main_content .= ' checked="checked"';
                    $main_content .= '/>&nbsp;<b>Disable Smileys in This Post </b><br>
						
					</td>
				</tr>
				<tr>
					<td class="ff_std" colspan=2 align="center"><br>
						<input type=submit name="preview_new_topic" value="Preview Message">
						&nbsp;
						<input type="submit" name="save_topic" value="Submit Message">
						&nbsp;
						<input type=reset name="reset" value="Reset Fields">
					</td>
				</tr>
			</FORM>
		</table>';
                }
            }
            else
                $main_content .= 'Board with ID '.$board_id.' doesn\'t exist.';
        }
        else
            $main_content .= 'Your account is banned, deleted or you don\'t have any player with level '.$level_limit.' on your account. You can\'t post.';
    }
    else
        $main_content .= 'Login first.';
}
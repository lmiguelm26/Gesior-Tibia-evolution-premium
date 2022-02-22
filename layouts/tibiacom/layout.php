<?php
if(!defined('INITIALIZED'))
    exit;
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>Tibia-Evolution</title>
    <meta name="author" content="lmiguelm26">
    <meta http-equiv="content-language" content="pt-br">
    <meta name="keywords" content="free online game, free multiplayer game, free online rpg, free mmorpg, mmorpg, mmog, online role playing game, online multiplayer game, internet game, online rpg, rpg">
	<meta name="description" content="Tibia is a free massively multiplayer online role-playing game (MMORPG). Join this fascinating game that has thousands of fans from all over the world! - https://127.0.0.1" />
	
    <!--  regular browsers -->
    <link rel="shortcut icon" href="<?php echo $layout_name; ?>/images/global/general/favicon.ico" type="image/x-icon">
    <!-- For iPad with high-resolution Retina display running iOS = 7: -->
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo $layout_name; ?>/images/global/general/apple-touch-icon-152x152.png">
    <!-- For iPad with high-resolution Retina display running iOS = 6: -->
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo $layout_name; ?>/images/global/general/apple-touch-icon-144x144.png">
    <!-- For iPhone with high-resolution Retina display running iOS = 7: -->
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo $layout_name; ?>/images/global/general/apple-touch-icon-120x120.png">
    <!-- For iPhone with high-resolution Retina display running iOS = 6: -->
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo $layout_name; ?>/images/global/general/apple-touch-icon-114x114.png">
    <!-- For the iPad mini and the first- and second-generation iPad on iOS = 7: -->
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo $layout_name; ?>/images/global/general/apple-touch-icon-76x76.png">
    <!-- For the iPad mini and the first- and second-generation iPad on iOS = 6: -->
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo $layout_name; ?>/images/global/general/apple-touch-icon-72x72.png">
    <!-- For non-Retina iPhone, iPod Touch, and Android 2.1+ devices: -->
    <link rel="apple-touch-icon" href="<?php echo $layout_name; ?>/images/global/general/apple-touch-icon.png">
    <!-- Fallback for older devices: -->
    <link rel="apple-touch-icon-precomposed" href="<?php echo $layout_name; ?>/images/global/general/apple-touch-icon-precomposed.png">

    <link href="<?php echo $layout_name; ?>/css/style.css" rel="stylesheet" type="text/css">
    <?php
    if($_REQUEST['subtopic'] == "latestnews" || $_REQUEST['subtopic'] == "newsarchive")
        echo '<link href="'.$layout_name.'/css/news.css" rel="stylesheet" type="text/css">';
    ?>
    <script src="<?php echo $layout_name; ?>/js/jquery/jquery.js" ></script>
    <script src="<?php echo $layout_name; ?>/js/jquery/jquery-ui.core.js" ></script>
    <script src="<?php echo $layout_name; ?>/js/jquery/jquery-ui.widgets.js" ></script>
    <script src="<?php echo $layout_name; ?>/js/jquery/jquery.mask.js"></script>
    <script src="<?php echo $layout_name; ?>/js/ajaxcip.js"></script>
    <script src="<?php echo $layout_name; ?>/js/ajaxmonteiro.js"></script>
    <?php
    if($_REQUEST['subtopic'] == "createaccount")
        echo '<script src="'.$layout_name.'/js/create_character.js"></script>';
    ?>
    <script src="<?php echo $layout_name; ?>/js/generic.js"></script>
    <script>
        var loginStatus=0;
        loginStatus='<?php if($logged){ ?>true<?php } else { ?>false<?php } ?>';
        <?php if ($_REQUEST['subtopic'] == 'accountmanagement' && $_REQUEST['action'] == 'donate'){?>
        var activeSubmenuItem='donate';
        <?php }else{?>
        var activeSubmenuItem='<?php echo $subtopic; ?>';
        <?php }?>
        var JS_DIR_IMAGES=0;
        JS_DIR_IMAGES='<?php echo $layout_name; ?>/images/';
        var JS_DIR_ACCOUNT=0;
        JS_DIR_ACCOUNT='';
        var g_FormName='';
        var g_FormField='';
        var g_Deactivated=false;
        var g_FlashClientInPopUp= true;
    </script>
    <script>
        if(top.location != window.location) {
            g_FlashClientInPopUp = false;
        }
    </script>
    <script src="<?php echo $layout_name; ?>/js/initialize.js"></script>
    <script src="<?php echo $layout_name; ?>/js/swfobject.js" ></script>
    <?php if($_REQUEST['subtopic'] == "accountmanagement") { ?>
        <script type="text/javascript">
            function openGameWindow(a_URL)
            {
                var Height = 768;
                var Width = 1024;
                var Top = (screen.height - Height) / 2;
                var Left = (screen.width - Width) / 2;
                var NewWindow = window.open(a_URL + '&window=2',
                    "Tibia",
                    "width=" + Width + ",height=" + Height + ",top=" + Top + ",left=" + Left + ",dependent=no,hotkeys=no,location=no,menubar=no,resizable=yes,scrollbars=no,status=no,toolbar=no"
                );
                if (NewWindow != null) {
                    NewWindow.focus();
                }
            }
        </script>
    <?php } ?>
</head>

<body onbeforeunload="SaveMenu();"
      style="background-image:url(<?php echo $layout_name; ?>/images/global/header/background-artwork.jpg);
              background-size: 100%;
              background-position: top center;
              background-repeat: no-repeat;
              "
      onunload="SaveMenu();"
      onload="SetFormFocus()"
      data-twttr-rendered="true">
	  
<?php include_once('./pages/promo.php');?>  

<div id="DeactivationContainer" onclick="DisableDeactivationContainer();"></div>

<div id="MainHelper1">
    <div id="MainHelper2">
        <div id="Bodycontainer">
            <div id="ContentRow">
                <div id="MenuColumn">
                    <div id="LeftArtwork">
                        <a href="./?subtopic=latestnews">
                            <img id="TibiaLogoArtworkTop"
                                 src="<?php echo $layout_name; ?>/images/global/header/tibia-logo-artwork-top.gif"
                                 alt="logoartwork"
                                 name="Tibia"
                            >
                        </a>
                    </div>

                    <div id="Loginbox">
                        <div id="LoginTop" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/box-top.gif)"></div>
                        <div id="BorderLeft" class="LoginBorder" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif)"></div>
                        <div id="LoginButtonContainer" style="background-image:url(<?php echo $layout_name; ?>/images/global/loginbox/loginbox-textfield-background.gif)">
                            <div id="PlayNowContainer"><form class="MediumButtonForm" action="?subtopic=accountmanagement" method="post"><input type="hidden" name="page" value="overview"><div class="MediumButtonBackground" style="background-image:url(<?php echo $layout_name; ?>/images/global/buttons/mediumbutton.gif)" onmouseover="MouseOverMediumButton(this);" onmouseout="MouseOutMediumButton(this);"><div class="MediumButtonOver" style="background-image:url(<?php echo $layout_name; ?>/images/global/buttons/mediumbutton-over.gif)" onmouseover="MouseOverMediumButton(this);" onmouseout="MouseOutMediumButton(this);"></div><input class="MediumButtonText" type="image" name="Play Now" alt="Play Now" src="<?php echo $layout_name; ?>/images/global/buttons/mediumbutton_playnow.png"></div></form>
                            </div>
                        </div>
                        <div class="Loginstatus" style="background-image:url(<?php echo $layout_name; ?>/images/global/loginbox/loginbox-textfield-background.gif)">
                            <div id="LoginstatusText"
                                 onclick="LoginstatusTextAction(this);"
                                 onmouseover="MouseOverLoginBoxText(this);"
                                 onmouseout="MouseOutLoginBoxText(this);">
                                <div id="LoginstatusText_1"
                                     class="LoginstatusText"
                                     style="background-image: url(<?php echo $layout_name; ?>/images/global/loginbox/loginbox-font-create-account.gif);">
                                </div>
                                <div id="LoginstatusText_2"
                                     class="LoginstatusText"
                                     style="background-image: url(<?php echo $layout_name; ?>/images/global/loginbox/loginbox-font-create-account-over.gif);">
                                </div>
                            </div>
                        </div>
                        <div id="BorderRight" class="LoginBorder" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif)"></div>
                        <div id="LoginBottom" class="Loginstatus" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/box-bottom.gif)"></div>
                    </div>

                    <div class="SmallMenuBox" style="top: 4px;" >
                        <div id="LoginTop" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/box-top.gif)" ></div>
                        <div id="BorderLeft" class="LoginBorder" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif); height: 39px;" ></div>
                        <div id="LoginButtonContainer" style="background-image:url(<?php echo $layout_name; ?>/images/global/loginbox/loginbox-textfield-background.gif)" >
                            <div id="PlayNowContainer" ><form class="MediumButtonForm" action="?subtopic=downloadclient&step=downloadagreement" method="post" ><div class="MediumButtonBackground" style="background-image:url(<?php echo $layout_name; ?>/images/global/buttons/mediumbutton.gif)" onMouseOver="MouseOverMediumButton(this);" onMouseOut="MouseOutMediumButton(this);" ><div class="MediumButtonOver" style="background-image:url(<?php echo $layout_name; ?>/images/global/buttons/mediumbutton-over.gif)" onMouseOver="MouseOverMediumButton(this);" onMouseOut="MouseOutMediumButton(this);" ></div><input class="MediumButtonText" type="image" name="Download" alt="Download" src="<?php echo $layout_name; ?>/images/global/buttons/mediumbutton_download.png" /></div></form></div>
                        </div>
                        <div id="BorderRight" class="LoginBorder" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif); height: 39px;" ></div>
                        <div id="LoginBottom" class="Loginstatus" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/box-bottom.gif); top: 39px;" ></div>
                    </div>

                    <div id="Menu">
                        <div id="MenuTop" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/box-top.gif);"></div>
                        <div id="news" class="menuitem">
								<span onclick="MenuItemAction('news')">
  									<div class="MenuButton" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/button-background.gif);">
    									<div onmouseover="MouseOverMenuItem(this);" onmouseout="MouseOutMenuItem(this);"><div class="Button" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/button-background-over.gif);"></div>
											<span id="news_Lights" class="Lights" style="visibility: hidden;">
												<div class="light_lu" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/green-light.gif);"></div>
												<div class="light_ld" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/green-light.gif);"></div>
												<div class="light_ru" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/green-light.gif);"></div>
											</span>
											<div id="news_Icon" class="Icon" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/icon-news.gif);"></div>
											<div id="news_Label" class="Label" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/label-news.gif);"></div>
											<div id="news_Extend" class="Extend" style="background-image: url(<?php echo $layout_name; ?>/images/global/general/minus.gif);"></div>
										</div>
									</div>
								</span>
                            <div id="news_Submenu" class="Submenu">
                                <a href="?subtopic=latestnews">
                                    <div id="submenu_latestnews" class="Submenuitem" onmouseover="MouseOverSubmenuItem(this)" onmouseout="MouseOutSubmenuItem(this)">
                                        <div class="LeftChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                        <div id="ActiveSubmenuItemIcon_latestnews" class="ActiveSubmenuItemIcon" style="background-image: url(<?php echo $layout_name; ?>/images/global/menu/icon-activesubmenu.gif);"></div>
                                        <div id="ActiveSubmenuItemLabel_latestnews" class="SubmenuitemLabel">Latest News</div>
                                        <div class="RightChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                    </div>
                                </a>
                                <a href="?subtopic=newsarchive">
                                    <div id="submenu_newsarchive" class="Submenuitem" onmouseover="MouseOverSubmenuItem(this)" onmouseout="MouseOutSubmenuItem(this)">
                                        <div class="LeftChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                        <div id="ActiveSubmenuItemIcon_newsarchive" class="ActiveSubmenuItemIcon" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/icon-activesubmenu.gif);"></div>
                                        <div id="ActiveSubmenuItemLabel_newsarchive" class="SubmenuitemLabel">News Archive</div>
                                        <div class="RightChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div id="community" class="menuitem">
								<span onclick="MenuItemAction('community')">
  									<div class="MenuButton" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/button-background.gif);">
    									<div onmouseover="MouseOverMenuItem(this);" onmouseout="MouseOutMenuItem(this);"><div class="Button" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/button-background-over.gif);"></div>
      										<span id="community_Lights" class="Lights" style="visibility: visible;">
												<div class="light_lu" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/green-light.gif);"></div>
												<div class="light_ld" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/green-light.gif);"></div>
												<div class="light_ru" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/green-light.gif);"></div>
      										</span>
											<div id="community_Icon" class="Icon" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/icon-community.gif);"></div>
											<div id="community_Label" class="Label" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/label-community.gif);"></div>
											<div id="community_Extend" class="Extend" style="background-image: url(<?php echo $layout_name; ?>/images/global/general/plus.gif);"></div>
    									</div>
  									</div>
								</span>
                            <div id="community_Submenu" class="Submenu">
                                <a href="?subtopic=characters">
                                    <div id="submenu_characters" class="Submenuitem" onmouseover="MouseOverSubmenuItem(this)" onmouseout="MouseOutSubmenuItem(this)">
                                        <div class="LeftChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                        <div id="ActiveSubmenuItemIcon_characters" class="ActiveSubmenuItemIcon" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/icon-activesubmenu.gif);"></div>
                                        <div id="ActiveSubmenuItemLabel_characters" class="SubmenuitemLabel">Characters</div>
                                        <div class="RightChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                    </div>
                                </a>
                                    <a href="?subtopic=worlds">
                                        <div id="submenu_worlds" class="Submenuitem" onmouseover="MouseOverSubmenuItem(this)" onmouseout="MouseOutSubmenuItem(this)">
                                            <div class="LeftChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                            <div id="ActiveSubmenuItemIcon_worlds" class="ActiveSubmenuItemIcon" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/icon-activesubmenu.gif);"></div>
                                            <div id="ActiveSubmenuItemLabel_worlds" class="SubmenuitemLabel">Worlds</div>
                                            <div class="RightChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                        </div>
                                    </a>
                                <a href="?subtopic=highscores">
                                    <div id="submenu_highscores" class="Submenuitem" onmouseover="MouseOverSubmenuItem(this)" onmouseout="MouseOutSubmenuItem(this)">
                                        <div class="LeftChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                        <div id="ActiveSubmenuItemIcon_highscores" class="ActiveSubmenuItemIcon" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/icon-activesubmenu.gif);"></div>
                                        <div id="ActiveSubmenuItemLabel_highscores" class="SubmenuitemLabel">Highscores</div>
                                        <div class="RightChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                    </div>
                                </a>
                                <a href="?subtopic=killstatistics">
                                    <div id="submenu_killstatistics" class="Submenuitem" onmouseover="MouseOverSubmenuItem(this)" onmouseout="MouseOutSubmenuItem(this)">
                                        <div class="LeftChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                        <div id="ActiveSubmenuItemIcon_killstatistics" class="ActiveSubmenuItemIcon" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/icon-activesubmenu.gif);"></div>
                                        <div id="ActiveSubmenuItemLabel_killstatistics" class="SubmenuitemLabel">Kill Statistics</div>
                                        <div class="RightChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                    </div>
                                </a>
                                <a href="?subtopic=houses">
                                    <div id="submenu_houses" class="Submenuitem" onmouseover="MouseOverSubmenuItem(this)" onmouseout="MouseOutSubmenuItem(this)">
                                        <div class="LeftChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                        <div id="ActiveSubmenuItemIcon_houses" class="ActiveSubmenuItemIcon" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/icon-activesubmenu.gif);"></div>
                                        <div id="ActiveSubmenuItemLabel_houses" class="SubmenuitemLabel">Houses</div>
                                        <div class="RightChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                    </div>
                                </a>
                                <a href="?subtopic=guilds">
                                    <div id="submenu_guilds" class="Submenuitem" onmouseover="MouseOverSubmenuItem(this)" onmouseout="MouseOutSubmenuItem(this)">
                                        <div class="LeftChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                        <div id="ActiveSubmenuItemIcon_guilds" class="ActiveSubmenuItemIcon" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/icon-activesubmenu.gif);"></div>
                                        <div id="ActiveSubmenuItemLabel_guilds" class="SubmenuitemLabel">Guilds</div>
                                        <div class="RightChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                    </div>
                                </a>
                                <a href="?subtopic=polls">
                                    <div id="submenu_polls" class="Submenuitem" onmouseover="MouseOverSubmenuItem(this)" onmouseout="MouseOutSubmenuItem(this)">
                                        <div class="LeftChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                        <div id="ActiveSubmenuItemIcon_polls" class="ActiveSubmenuItemIcon" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/icon-activesubmenu.gif);"></div>
                                        <div id="ActiveSubmenuItemLabel_polls" class="SubmenuitemLabel">Polls</div>
                                        <div class="RightChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div id="forum" class="menuitem">
								<span onclick="MenuItemAction('forum')">
  									<div class="MenuButton" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/button-background.gif);">
    									<div onmouseover="MouseOverMenuItem(this);" onmouseout="MouseOutMenuItem(this);"><div class="Button" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/button-background-over.gif);"></div>
      										<span id="forum_Lights" class="Lights" style="visibility: visible;">
												<div class="light_lu" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/green-light.gif);"></div>
												<div class="light_ld" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/green-light.gif);"></div>
												<div class="light_ru" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/green-light.gif);"></div>
      										</span>
      										<div id="forum_Icon" class="Icon" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/icon-forum.gif);"></div>
      										<div id="forum_Label" class="Label" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/label-forum.gif);"></div>
      										<div id="forum_Extend" class="Extend" style="background-image: url(<?php echo $layout_name; ?>/images/global/general/plus.gif);"></div>
    									</div>
  									</div>
								</span>
                            <div id="forum_Submenu" class="Submenu">
                                <a href="?subtopic=forum">
                                    <div id="submenu_forum" class="Submenuitem" onmouseover="MouseOverSubmenuItem(this)" onmouseout="MouseOutSubmenuItem(this)">
                                        <div class="LeftChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                        <div id="ActiveSubmenuItemIcon_forum" class="ActiveSubmenuItemIcon" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/icon-activesubmenu.gif);"></div>
                                        <div id="ActiveSubmenuItemLabel_forum" class="SubmenuitemLabel">Server Forum</div>
                                        <div class="RightChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div id="account" class="menuitem">
								<span onclick="MenuItemAction('account')">
  									<div class="MenuButton" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/button-background.gif);">
    									<div onmouseover="MouseOverMenuItem(this);" onmouseout="MouseOutMenuItem(this);"><div class="Button" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/button-background-over.gif);"></div>
      										<span id="account_Lights" class="Lights" style="visibility: visible;">
												<div class="light_lu" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/green-light.gif);"></div>
												<div class="light_ld" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/green-light.gif);"></div>
												<div class="light_ru" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/green-light.gif);"></div>
      										</span>
      										<div id="account_Icon" class="Icon" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/icon-account.gif);"></div>
      										<div id="account_Label" class="Label" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/label-account.gif);"></div>
      										<div id="account_Extend" class="Extend" style="background-image: url(<?php echo $layout_name; ?>/images/global/general/plus.gif);"></div>
    									</div>
  									</div>
								</span>
                            <div id="account_Submenu" class="Submenu">
                                <a href="?subtopic=accountmanagement&page=overview">
                                    <div id="submenu_accountmanagement" class="Submenuitem" onmouseover="MouseOverSubmenuItem(this)" onmouseout="MouseOutSubmenuItem(this)">
                                        <div class="LeftChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                        <div id="ActiveSubmenuItemIcon_accountmanagement" class="ActiveSubmenuItemIcon" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/icon-activesubmenu.gif);"></div>
                                        <div id="ActiveSubmenuItemLabel_accountmanagement" class="SubmenuitemLabel">Account Management</div>
                                        <div class="RightChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                    </div>
                                </a>
                                <?php if($group_id_of_acc_logged >= $config['site']['access_admin_panel']) { ?>
                                    <a href="?subtopic=adminpanel">
                                        <div id="submenu_adminpanel" class="Submenuitem" onmouseover="MouseOverSubmenuItem(this)" onmouseout="MouseOutSubmenuItem(this)">
                                            <div class="LeftChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                            <div id="ActiveSubmenuItemIcon_adminpanel" class="ActiveSubmenuItemIcon" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/icon-activesubmenu.gif);"></div>
                                            <div id="ActiveSubmenuItemLabel_adminpanel" class="SubmenuitemLabel">Admin Panel</div>
                                            <div class="RightChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                        </div>
                                    </a>
                                <?php } ?>
                                <?php if(!$logged){ ?>
                                    <a href="?subtopic=createaccount">
  										<div id="submenu_createaccount" class="Submenuitem" onmouseover="MouseOverSubmenuItem(this)" onmouseout="MouseOutSubmenuItem(this)">
											<div class="LeftChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
											<div id="ActiveSubmenuItemIcon_createaccount" class="ActiveSubmenuItemIcon" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/icon-activesubmenu.gif);"></div>
											<div id="ActiveSubmenuItemLabel_createaccount" class="SubmenuitemLabel">Create Account</div>
											<div class="RightChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
  										</div>
									</a>
                                <?php } ?>
                                <a href="?subtopic=downloadclient&step=downloadagreement">
                                    <div id="submenu_downloadclient" class="Submenuitem" onmouseover="MouseOverSubmenuItem(this)" onmouseout="MouseOutSubmenuItem(this)">
                                        <div class="LeftChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                        <div id="ActiveSubmenuItemIcon_downloadclient" class="ActiveSubmenuItemIcon" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/icon-activesubmenu.gif);"></div>
                                        <div id="ActiveSubmenuItemLabel_downloadclient" class="SubmenuitemLabel">Download Client</div>
                                        <div class="RightChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                    </div>
                                </a>
                                <a href="?subtopic=lostaccount">
                                    <div id="submenu_lostaccount" class="Submenuitem" onmouseover="MouseOverSubmenuItem(this)" onmouseout="MouseOutSubmenuItem(this)">
                                        <div class="LeftChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                        <div id="ActiveSubmenuItemIcon_lostaccount" class="ActiveSubmenuItemIcon" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/icon-activesubmenu.gif);"></div>
                                        <div id="ActiveSubmenuItemLabel_lostaccount" class="SubmenuitemLabel">Lost Account</div>
                                        <div class="RightChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div id="library" class="menuitem">
								<span onclick="MenuItemAction('library')">
  									<div class="MenuButton" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/button-background.gif);">
    									<div onmouseover="MouseOverMenuItem(this);" onmouseout="MouseOutMenuItem(this);"><div class="Button" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/button-background-over.gif);"></div>
      										<span id="library_Lights" class="Lights" style="visibility: visible;">
        										<div class="light_lu" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/green-light.gif);"></div>
												<div class="light_ld" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/green-light.gif);"></div>
												<div class="light_ru" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/green-light.gif);"></div>
      										</span>
										  	<div id="library_Icon" class="Icon" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/icon-library.gif);"></div>
										  	<div id="library_Label" class="Label" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/label-library.gif);"></div>
										  	<div id="library_Extend" class="Extend" style="background-image: url(<?php echo $layout_name; ?>/images/global/general/plus.gif);"></div>
    									</div>
  									</div>
								</span>
                            <div id="library_Submenu" class="Submenu">
                                <a href="?subtopic=experiencetable">
                                    <div id="submenu_experiencetable" class="Submenuitem" onmouseover="MouseOverSubmenuItem(this)" onmouseout="MouseOutSubmenuItem(this)">
                                        <div class="LeftChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                        <div id="ActiveSubmenuItemIcon_experiencetable" class="ActiveSubmenuItemIcon" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/icon-activesubmenu.gif);"></div>
                                        <div id="ActiveSubmenuItemLabel_experiencetable" class="SubmenuitemLabel">Experience Table</div>
                                        <div class="RightChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                    </div>
                                </a>
                                <a href="?subtopic=serverinfo">
                                    <div id="submenu_serverinfo" class="Submenuitem" onmouseover="MouseOverSubmenuItem(this)" onmouseout="MouseOutSubmenuItem(this)">
                                        <div class="LeftChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                        <div id="ActiveSubmenuItemIcon_serverinfo" class="ActiveSubmenuItemIcon" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/icon-activesubmenu.gif);"></div>
                                        <div id="ActiveSubmenuItemLabel_serverinfo" class="SubmenuitemLabel">Server Info</div>
                                        <div class="RightChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div id="support" class="menuitem">
								<span onclick="MenuItemAction('support')">
  									<div class="MenuButton" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/button-background.gif);">
    									<div onmouseover="MouseOverMenuItem(this);" onmouseout="MouseOutMenuItem(this);"><div class="Button" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/button-background-over.gif);"></div>
      										<span id="support_Lights" class="Lights" style="visibility: visible;">
												<div class="light_lu" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/green-light.gif);"></div>
												<div class="light_ld" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/green-light.gif);"></div>
												<div class="light_ru" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/green-light.gif);"></div>
      										</span>
      										<div id="support_Icon" class="Icon" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/icon-support.gif);"></div>
      										<div id="support_Label" class="Label" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/label-support.gif);"></div>
      										<div id="support_Extend" class="Extend" style="background-image: url(<?php echo $layout_name; ?>/images/global/general/plus.gif);"></div>
    									</div>
  									</div>
								</span>
                            <div id="support_Submenu" class="Submenu">
                                <a href="?subtopic=tibiarules">
                                    <div id="submenu_tibiarules" class="Submenuitem" onmouseover="MouseOverSubmenuItem(this)" onmouseout="MouseOutSubmenuItem(this)">
                                        <div class="LeftChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                        <div id="ActiveSubmenuItemIcon_tibiarules" class="ActiveSubmenuItemIcon" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/icon-activesubmenu.gif);"></div>
                                        <div id="ActiveSubmenuItemLabel_tibiarules" class="SubmenuitemLabel">Tibia Rules</div>
                                        <div class="RightChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                    </div>
                                </a>
                                <a href="?subtopic=team">
                                    <div id="submenu_team" class="Submenuitem" onmouseover="MouseOverSubmenuItem(this)" onmouseout="MouseOutSubmenuItem(this)">
                                        <div class="LeftChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                        <div id="ActiveSubmenuItemIcon_team" class="ActiveSubmenuItemIcon" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/icon-activesubmenu.gif);"></div>
                                        <div id="ActiveSubmenuItemLabel_team" class="SubmenuitemLabel">Tibia Support</div>
                                        <div class="RightChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                    </div>
                                </a>
                            <?php if($_REQUEST["subtopic"] == "error"){?>
                                <a href="?subtopic=error">
                                    <div id="submenu_error" class="Submenuitem" onmouseover="MouseOverSubmenuItem(this)" onmouseout="MouseOutSubmenuItem(this)">
                                        <div class="LeftChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                        <div id="ActiveSubmenuItemIcon_error" class="ActiveSubmenuItemIcon" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/icon-activesubmenu.gif);"></div>
                                        <div id="ActiveSubmenuItemLabel_error" class="SubmenuitemLabel">Error</div>
                                        <div class="RightChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                    </div>
                                </a>
                            <?php } ?>
							<?php if (isset($_SESSION['account'])){?>
							    <a href="?subtopic=ticket">
                                    <div id="submenu_ticket" class="Submenuitem" onmouseover="MouseOverSubmenuItem(this)" onmouseout="MouseOutSubmenuItem(this)">
                                        <div class="LeftChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                        <div id="ActiveSubmenuItemIcon_ticket" class="ActiveSubmenuItemIcon" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/icon-activesubmenu.gif);"></div>
                                        <div id="ActiveSubmenuItemLabel_ticket" class="SubmenuitemLabel">Ticket Support</div>
                                        <div class="RightChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                    </div>
                                </a>
							<?php } ?>
                            </div>
                        </div>
                        <div id="shop" class="menuitem">
								<span onclick="MenuItemAction('shop')">
  									<div class="MenuButton" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/button-background.gif);">
    									<div onmouseover="MouseOverMenuItem(this);" onmouseout="MouseOutMenuItem(this);"><div class="Button" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/button-background-over.gif);"></div>
      										<span id="shop_Lights" class="Lights" style="visibility: visible;">
												<div class="light_lu" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/green-light.gif);"></div>
												<div class="light_ld" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/green-light.gif);"></div>
												<div class="light_ru" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/green-light.gif);"></div>
      										</span>
      										<div id="shop_Icon" class="Icon" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/icon-shops.gif);"></div>
      										<div id="shop_Label" class="Label" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/label-shops.gif);"></div>
      										<div id="shop_Extend" class="Extend" style="background-image: url(<?php echo $layout_name; ?>/images/global/general/plus.gif);"></div>
    									</div>
  									</div>
								</span>
                            <div id="shop_Submenu" class="Submenu">
                                <?php if (isset($_SESSION['account'])){?>
                                    <a href="?subtopic=accountmanagement&action=services&ServiceCategoryID=2">
                                        <div id="submenu_shop" class="Submenuitem" onmouseover="MouseOverSubmenuItem(this)" onmouseout="MouseOutSubmenuItem(this)">
                                            <div class="LeftChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                            <div id="ActiveSubmenuItemIcon_shop" class="ActiveSubmenuItemIcon" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/icon-activesubmenu.gif);"></div>
                                            <div id="ActiveSubmenuItemLabel_shop" class="SubmenuitemLabel">Webshop</div>
                                            <div class="RightChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                        </div>
                                    </a>
                                <?php }else{?>
                                    <a href="?subtopic=shop">
                                        <div id="submenu_shop" class="Submenuitem" onmouseover="MouseOverSubmenuItem(this)" onmouseout="MouseOutSubmenuItem(this)">
                                            <div class="LeftChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                            <div id="ActiveSubmenuItemIcon_shop" class="ActiveSubmenuItemIcon" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/icon-activesubmenu.gif);"></div>
                                            <div id="ActiveSubmenuItemLabel_shop" class="SubmenuitemLabel">Webshop</div>
                                            <div class="RightChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                        </div>
                                    </a>
                                <?php }?>
                                <a href="?subtopic=accountmanagement&action=donate">
                                    <div id="submenu_donate" class="Submenuitem" onmouseover="MouseOverSubmenuItem(this)" onmouseout="MouseOutSubmenuItem(this)">
                                        <div class="LeftChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                        <div id="ActiveSubmenuItemIcon_donate" class="ActiveSubmenuItemIcon" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/icon-activesubmenu.gif);"></div>
                                        <div id="ActiveSubmenuItemLabel_donate" class="SubmenuitemLabel">Donate</div>
                                        <div class="RightChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                    </div>
                                </a>
                                <?php if($_REQUEST['subtopic'] == 'tankyou'){?>
                                    <a>
                                        <div id="submenu_tankyou" class="Submenuitem" onmouseover="MouseOverSubmenuItem(this)" onmouseout="MouseOutSubmenuItem(this)">
                                            <div class="LeftChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                            <div id="ActiveSubmenuItemIcon_tankyou" class="ActiveSubmenuItemIcon" style="background-image:url(<?php echo $layout_name; ?>/images/global/menu/icon-activesubmenu.gif);"></div>
                                            <div id="ActiveSubmenuItemLabel_tankyou" class="SubmenuitemLabel">Thank You!</div>
                                            <div class="RightChain" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/chain.gif);"></div>
                                        </div>
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                        <div id="MenuBottom" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/box-bottom.gif);"></div>
                    </div>
                    <script>InitializePage();</script>
                </div>
                <div id="ContentColumn">
                        <div id="Content" class="Content">
                            <div id="ContentHelper">
                                <div id="preload">
                                    <?php
        
                                    if ( ! session_id() ) @ session_start();
        
                                    $last = null;
                                    if (!isset($_SESSION)) {
                                        $_SESSION = [];
                                    }
        
                                    if (isset($_SESSION['server_status_last_check'])) {
                                        $last = $_SESSION['server_status_last_check'];
                                    }
                                    if ($last == null || time() > $last + 30) {
                                        $_SESSION['server_status_last_check'] = time();
                                        $_SESSION['server_status'] = $config['status']['serverStatus_online'];
                                    }
                                    
                                    $infobar = Website::getWebsiteConfig()->getValue('info_bar_active');
        
                                    if($_SESSION['server_status'] == 1){
                                        $qtd_players_online = $SQL->prepare("SELECT count(*) as total from `players_online`");
                                        $qtd_players_online->execute([]);
                                        $qtd_players_online = $qtd_players_online->fetch();
                                        if($qtd_players_online["total"] == "1"){
                                            $players_online = ($infobar ? $qtd_players_online["total"].' Player Online' : $qtd_players_online["total"].'<br/>Player Online');
                                        }else{
                                            $players_online = ($infobar ? $qtd_players_online["total"].' Players Online' : $qtd_players_online["total"].'<br/>Players Online');
                                        }
                                    }
                                    else{
                                        $players_online = ($infobar ? 'Server Offline' : 'Server<br/>Offline');
                                    }
                                    ?>
                                    <div id="PlayersOnline" class="Box">
                                        <div class="Corner-tl" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/corner-tl.gif);"></div>
                                        <div class="Corner-tr" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/corner-tr.gif);"></div>
                                        <div class="Border_1" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/border-1.gif);"></div>
                                        <div class="BorderTitleText" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/newsheadline_background.gif); height: 28px;">
                                            <div class="InfoBar">
                                                    <?php
                                                    $playersCast = $SQL->prepare("SELECT count(*) as `players_cast`, sum(`spectators`) as `spectators` FROM `live_casts`");
                                                    $playersCast->execute([]);
                                                    $playersCast = $playersCast->fetchAll();
                                                    ?>
                                                    <a class="InfoBarBlock" href="?subtopic=castsystem">
                                                        <img class="InfoBarBigLogo" src="<?php echo $layout_name; ?>/images/global/header/info/icon-cast.png">
                                                        <span class="InfoBarNumbers" <?php if($_REQUEST['subtopic'] == 'characters' && $_REQUEST['name']){ echo "style='top:0'"; }?>><img class="InfoBarSmallElement" src="<?php echo $layout_name; ?>/images/global/header/info/icon-streamers.png">
                                                            <span class="InfoBarSmallElement"><?= (isset($playersCast['players_cast']) == null ? 0 : $playersCast['players_cast'])  ?></span><img class="InfoBarSmallElement" src="<?php echo $layout_name; ?>/images/global/header/info/icon-viewers.png">
                                                            <span class="InfoBarSmallElement"><?= (isset($playersCast['spectators']) == 0 ? 0 : $playersCast['spectators']) ?></span>
                                                        </span>
                                                    </a>
                                                    <a href="?subtopic=downloadclient&step=downloadagreement">
                                                        <img class="InfoBarBigLogo" src="<?php echo $layout_name; ?>/images/global/header/info/icon-download.png">
                                                        <span class="InfoBarNumbers" <?php if($_REQUEST['subtopic'] == 'characters' && $_REQUEST['name']){ echo "style='top:0'"; }?>>
                                                            <span class="InfoBarSmallElement">Download Client</span>
                                                    </span>
                                                </a>
                                                <a style="float: right" href="?subtopic=worlds">
                                                    <img class="InfoBarBigLogo" src="<?php echo $layout_name; ?>/images/global/header/info/icon-players-online.png">
                                                    <span class="InfoBarNumbers" <?php if($_REQUEST['subtopic'] == 'characters' && $_REQUEST['name']){ echo "style='top:0'"; }?>>
                                                        <span class="InfoBarSmallElement show_online_data"><?php echo $players_online; ?></span>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="Border_1" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/border-1.gif);"></div>
                                        <div class="CornerWrapper-b">
                                            <div class="Corner-bl" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/corner-bl.gif);"></div>
                                        </div>
                                        <div class="CornerWrapper-b">
                                            <div class="Corner-br" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/corner-br.gif);"></div>
                                        </div>
                                    </div>
                            <script type="text/javascript" src="<?php echo $layout_name; ?>/js/newsticker.js"></script>
                            <?php echo $news_content; ?>
                            <div id="NewsArchive" class="Box">
                                <div class="Corner-tl" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/corner-tl.gif);"></div>
                                <div class="Corner-tr" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/corner-tr.gif);"></div>
                                <div class="Border_1" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/border-1.gif);"></div>
                                <div class="BorderTitleText" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/title-background-green.gif);"></div>
                                <?php
                                $headline = ucfirst($_REQUEST['subtopic']);
                                if($_REQUEST['subtopic'] == "latestnews")
                                    $headline = "News";
                                elseif($_REQUEST['subtopic'] == "accountmanagement")
                                    $headline = "Account Management";
                                elseif($_REQUEST['subtopic'] == "createaccount")
                                    $headline = "Create Account";
                                elseif($_REQUEST['subtopic'] == "whoisonline")
                                    $headline = "Who is Online";
                                elseif($_REQUEST['subtopic'] == "adminpanel")
                                    $headline = "Admin Panel";
                                elseif($_REQUEST['subtopic'] == "tankyou")
                                    $headline = "Thank you";
								elseif($_REQUEST['subtopic'] == "tibiarules")
                                    $headline = "Tibia Rules";
								elseif($_REQUEST['subtopic'] == "team")
                                    $headline = "Tibia Support";
								elseif($_REQUEST['subtopic'] == "shop")
                                    $headline = "Webshop";
								elseif($_REQUEST['subtopic'] == "experiencetable")
                                    $headline = "Experience Table";
								elseif($_REQUEST['subtopic'] == "serverinfo")
                                    $headline = "Server Info";
								elseif($_REQUEST['subtopic'] == "lostaccount")
                                    $headline = "Lost Account";
								elseif($_REQUEST['subtopic'] == "downloadclient")
                                    $headline = "Download Client";
								elseif($_REQUEST['subtopic'] == "killstatistics")
                                    $headline = "Kill Statistics";
								elseif($_REQUEST['subtopic'] == "newsarchive")
                                    $headline = "News Archive";
                                ?>
                                <img id="ContentBoxHeadline" class="Title" src="pages/headline.php?txt=<?PHP echo ucwords(str_replace('_', ' ', strtolower($headline))); ?>" alt="Contentbox headline">
                                <div class="Border_2">
                                    <div class="Border_3">
                                        <div class="BoxContent" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/scroll.gif);">
                                            <?php echo $main_content; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="Border_1" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/border-1.gif);"></div>
                                <div class="CornerWrapper-b"><div class="Corner-bl" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/corner-bl.gif);"></div></div>
                                <div class="CornerWrapper-b"><div class="Corner-br" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/corner-br.gif);"></div></div>
                            </div>
							
              <div id="ThemeboxesColumn" >
                <div id="DeactivationContainerThemebox" onClick="DisableDeactivationContainer();" ></div>
                <div id="RightArtwork">
                   <img id="Monster" src="<?php echo $layout_name; ?>/images/global/header/monsters/dragonlord.gif" alt="Monster of the Week">
                  <img id="Pedestal" src="<?php echo $layout_name; ?>/images/global/header/pedestal.png" alt="Monster Pedestal Box"/>
                </div>							
                                <div id="Themeboxes">
								  <div id="TopLvl">
                                        <h3 class="TopLvl_title">Top 5 Experience</h3>
                                        <?php $topLevel5 = $SQL->query('SELECT * FROM players WHERE deleted = 0 AND group_id = 1 AND account_id != 1 ORDER BY level DESC LIMIT 5'); ?>
                                        <?php $topLevel5->execute([]) ?>
                                        <?php $topLevel5 = $topLevel5->fetchAll(); ?>
                                        <?php $cont = 0 ?>
                                        <?php foreach ($topLevel5 as $key => $value) { ?>
                                        <?php 
                                            $cont++ ;
                                            $rankLevel = ''
                                        ?>
                                        <div class="tplevellayout">
                                            <a class="topleveltext top_offline" style="text-decoration:none" href="?subtopic=characters&name=<?=  $value['name'] ?>">
                                                <span><?= $cont ?></span> - <?= $value['name'] ?><br><small>Level: <?= $value['level'] ?><br><?= Website::getVocationName($value['vocation']) ?></small>
                                            </a>
                                            <img class="outfitImgtoplevel" src="https://outfit-images.ots.me/1285_walk_animation/animoutfit.php?id=<?= $value['looktype'] ?>&addons=0&head=<?= $value['lookhead'] ?>&body=<?= $value['lookbody'] ?>&legs=<?= $value['looklegs'] ?>&feet=<?= $value['lookfeet'] ?>&g=0&h=3&i=1">
                                            <?php if($cont == 1) {
                                                $rankLevel = 'firstlevel';
                                            } else if($cont == 2) {
                                                $rankLevel = 'secondlevel';
                                            } else if($cont == 3) {
                                                $rankLevel = 'thirdlevel';
                                            }
                                            ?>
                                            <?php if($rankLevel != "") { ?>
                                            <div><span class="<?= $rankLevel ?>"><span id="<?= $rankLevel ?>"></span></span></div>

                                            <div class="rankinglevel">
                                                <span class="<?= $rankLevel ?>"><span id="<?= $rankLevel ?>"></span></span>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <?php }?>
                                    </div>
                                  <?php $skills = $SQL->query('SELECT * FROM players WHERE deleted = 0 AND group_id = 1 AND account_id != 1 ORDER BY level DESC LIMIT 5'); ?>
								

								
								
                                    <?php
                                    $skills = $SQL->query('SELECT * FROM players WHERE deleted = 0 AND group_id = 1 AND account_id != 1 ORDER BY level DESC LIMIT 5');
                                    ?>
                                    <!-- premium theme box -->
                                    <style>
                                        .ribbon-double {
                                            background:url(<?php echo $layout_name; ?>/images/shop/ribbon-double.png) no-repeat;
                                            width: 80px;
                                            height: 80px;
                                            position:absolute;
                                            right: 0;
                                            top: -1px;
                                        }
                                    </style>
									
						              
                                    <!-- Premium theme box -->
									<div id="PremiumBox" class="Themebox" style="background-image:url(<?php echo $layout_name; ?>/images/global/themeboxes/premium/themebox.png);"><div id="PremiumBoxDecor" style="background-image:url(<?php echo $layout_name; ?>/images/global/themeboxes/premium/coin_animation.gif);"></div><div id="PremiumBoxBg" style="background-image:url(<?php echo $layout_name; ?>/images/global/themeboxes/premium/coins_decoration.png);"></div><div id="PremiumBoxOverlay" style="background-image:url(<?php echo $layout_name; ?>/images//global/themeboxes/premium/type_overlay.png);"><p id="PremiumBoxOverlayText">Buy Epic Decoration!</p></div><div id="PremiumBoxButton"><form action="?subtopic=accountmanagement&action=donate" method="post" style="padding:0px;margin:0px;"><div class="WebshopButton" style="background-image:url(<?php echo $layout_name; ?>/images/global/themeboxes/premium/button.png)"><div onMouseOver="MouseOverWebshopButton(this);" onMouseOut="MouseOutWebshopButton(this);"><div class="WebshopButtonOver" style="background-image:url(<?php echo $layout_name; ?>/images/global/themeboxes/premium/button_hover.png);"></div><input class="WebshopButtonText" type="image" name="Get Coins" alt="Get Coins" src="<?php echo $layout_name; ?>/images/global/themeboxes/premium/get_tibia_coins.png"></div></div></form></div><div id="PremiumBoxButtonDecor" style="background-image:url(<?php echo $layout_name; ?>/images/global/themeboxes/premium/button_tibia_coins.png);"></div></div>
									
									<!-- Ticket Themebox -->
									<a href="?subtopic=ticket">
										<div id="supportButton" class="Themebox"
										style="height: 70px; margin-bottom: 0px; background-image:url(<?php echo $layout_name; ?>/images/global/themeboxes/ticket/support.png);"></div>
									</a>
                                    
									<!-- current poll theme box -->
                                        <?php
                                        $date = time();
                                        $getPolls = $SQL->query("SELECT * FROM `z_polls` LIMIT 1")->fetchAll();
                                        foreach($getPolls as $poll) {
                                            if($poll['end'] >= time()) {
                                                ?>
                                                <div id="CurrentPollBox" class="Themebox" style="background-image:url(<?php echo $layout_name; ?>/images/global/themeboxes/current-poll/currentpollbox.gif);">
                                                    <div id="CurrentPollText"><?php echo $poll['question']; ?></div>
                                                    <div class="ThemeboxButton">
                                                        <form action="?subtopic=polls&id=<?php echo $poll['id']; ?>" method="post" style="padding:0px;margin:0px;"><div class="BigButton" style="background-image:url(<?php echo $layout_name; ?>/images/global/buttons/sbutton.gif)"><div onmouseover="MouseOverBigButton(this);" onmouseout="MouseOutBigButton(this);"><div class="BigButtonOver" style="background-image:url(<?php echo $layout_name; ?>/images/global/buttons/sbutton_over.gif);"></div><input class="ButtonText" type="image" name="Vote Now" alt="Vote Now" src="<?php echo $layout_name; ?>/images/global/buttons/_sbutton_votenow.gif"></div></div></form>      </div>
                                                    <div class="Bottom" style="background-image:url(<?php echo $layout_name; ?>/images/global/general/box-bottom.gif);"></div>
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div id="Footer">
                                Copyright by <a href="https://www.cipsoft.com" target="_new"><b>CipSoft GmbH</b></a>. All rights reserveds<br>
                                <a href=?subtopic=forum><b>Game Forum</b></a> | <a href=<?php echo $config['social']['facebook']; ?>><b>Facebook</b></a> | <a href=?subtopic=team><b>Support Game</b></a><br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="HelperDivContainer" style="background-image: url(<?php echo $layout_name; ?>/images/global/content/scroll.gif);">
            <div class="HelperDivArrow" style="background-image: url(<?php echo $layout_name; ?>/images/global/content/helper-div-arrow.png);"></div>
            <div id="HelperDivHeadline"></div>
            <div id="HelperDivText"></div>
            <center><img class="Ornament" src="<?php echo $layout_name; ?>/images/global/content/ornament.gif"></center><br>
        </div>
    </div>
    </div>
    <div id="fb-root"></div>
    <script type="text/javascript">
        // disable all control elements which are not part of the content container element
        if (g_Deactivated == true) {
            $(document).ready(function() {
                document.getElementById('Monster').setAttribute('onclick', '');
                document.getElementById('PlayersOnline').setAttribute('onclick', '');
                document.getElementById('DeactivationContainer').setAttribute('onclick', '');
                document.getElementById('LoginButtonContainer').style.zIndex = '1';
                document.getElementById('DeactivationContainer').style.display = 'block';
                document.getElementById('DeactivationContainer').style.zIndex = '50';
                document.getElementById('DeactivationContainerThemebox').style.display = 'block';
                document.getElementById('Monster').style.cursor = 'auto';
                document.getElementById('PlayersOnline').style.cursor = 'auto';
                document.getElementById('ThemeboxesColumn').style.opacity = '0.30';
                document.getElementById('ThemeboxesColumn').style.MozOpacity = '0.30';
                document.getElementById('ThemeboxesColumn').filters.alpha.opacity = '0.75';
                document.getElementById('ThemeboxesColumn').style.filter = 'alpha(opacity=50); opacity: 0.30';
            });
        }
    </script>
    <script>(function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/pt_BR/sdk.js#xfbml=1&appId=329021967165778&version=v2.3";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
    <?php if ($config['base_url'] !== 'https://127.0.0.1/'){?>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-110963342-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-110963342-1');
    </script>
    <?php }?>
	<script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = 'https://connect.facebook.net/pt_BR/sdk.js#xfbml=1&autoLogAppEvents=1&version=v2.12&appId=1722335358003085';
        fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
	
	<!-- Discord -->
	<!--<script id="float_discord" src="<?php echo $layout_name; ?>/js/discord/discord_float_plugin.js" data-id="<?php echo $config['social']['discord']; ?>" async></script>-->
	
    <!-- Facebook -->
    <script id="float_fb" src="<?php echo $layout_name; ?>/js/facebook/fb_float_plugin.js" data-href="<?php echo $config['social']['facebook']; ?>" async></script> ﻿
	
	<!-- PageUp -->
    <script>
        $(document).ready(function(){
    
            //Check to see if the window is top if not then display button
            $(window).scroll(function(){
                if ($(this).scrollTop() > 100) {
                    $('.scrollToTop').fadeIn();
                } else {
                    $('.scrollToTop').fadeOut();
                }
            });
    
            //Click event to scroll to top
            $('.scrollToTop').click(function(){
                $('html, body').animate({scrollTop : 0},800);
                return false;
            });
    
        });
    </script>
    <div class="scrollToTop"></div>
	</body>
</html>
<?php
if (!defined('INITIALIZED'))
    exit;
require 'config/namesblocked.php';
if (!$logged) {
    $voc = array(); // Rookgard Active !

    if (isset($_POST['step']) && $_POST['step'] == 'docreate') {
        $erro = array();

        # Nome da conta
        $accountname = isset($_POST['accountname']) ? $_POST['accountname'] : '';

        if ($accountname == '')
            $erro['acc'] = 'Please enter an account name!';
        elseif (strlen($accountname) < 3)
            $erro['acc'] = 'This account name is too short!';
        elseif (strlen($accountname) > 30)
            $erro['acc'] = 'This account name is too long!';
        else {
            $accountname = strtoupper($accountname);

            if (!ctype_alnum($accountname))
                $erro['acc'] = 'This account name has an invalid format. Your account name may only consist of numbers 0-9 and letters A-Z!';
            elseif (!preg_match('/[A-Z0-9]/', $accountname))
                $erro['acc'] = 'Your account name must include at least one letter A-Z!';
            else {
                $acc = new Account($accountname, Account::LOADTYPE_NAME);
                if ($acc->isLoaded())
                    $erro['acc'] = 'This account name is already used. Please select another one!';
            }
        }

        $email = isset($_POST['email']) ? $_POST['email'] : '';

        if ($email == '')
            $erro['email'] = 'Please enter your email address!';
        elseif (strlen($email) > 49)
            $erro['email'] = 'Your email address is too long!';
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
            $erro['email'] = 'This email address has an invalid format. Please enter a correct email address!';
        else {
            $accMailCheck = new Account($email, Account::LOADTYPE_MAIL);
            if ($accMailCheck->isLoaded())
                $erro['email'] = 'This email address is already used. Please enter another email address!';
        }

        $password1 = isset($_POST['password1']) ? $_POST['password1'] : '';
        $password2 = isset($_POST['password2']) ? $_POST['password2'] : '';

        if (empty($password2))
            $erro['pass'] = 'Please enter the password again!';
        elseif ($password1 != $password2)
            $erro['pass'] = 'The two passwords do not match!';
        else {
            $err = array();
            if (strlen($password1) < 8 || strlen($password1) > 29)
                $err[] = 'The password must have at least 8 and less than 30 letters!';
            /**
             * Serve para saber se foi encontrado algum numero na string
             *
             * @param $string
             * @return bool
             */
            function encontrouNumeros($string)
            {
                return strpbrk($string, '0123456789') !== false;
            }

            if (!ctype_alnum($password1) || !encontrouNumeros($password1)) {
                $err[] = 'The password must contain at least one number!';
            }
            if(is_numeric($password1)){
                $err[] = 'The password must contain at least one letter A-Z or a-z!!';
            }

//            if (ctype_alnum($password1))
//                $err[] = 'The password contains invalid letters!';


            if (count($err) != 0) {
                $erro['pass'] = '';
                for ($i = 0; $i < count($err); $i++)
                    $erro['pass'] .= ($i == 0 ? '' : '<br/>') . $err[$i];
            }
        }

        if (!isset($_POST['agreeagreements']) || empty($_POST['agreeagreements']))
            $erro['rules'] = 'You have to agree to the ' . $config['server']['serverName'] . ' Rules in order to create an account!';

        if (count($erro) != 0) {

            $main_content = '
                <div class="SmallBox">
                <div class="MessageContainer">
                <div class="BoxFrameHorizontal" style="background-image:url(' . $layout_name . '/images/global/content/box-frame-horizontal.gif)"/></div>
                <div class="BoxFrameEdgeLeftTop" style="background-image:url(' . $layout_name . '/images/global/content/box-frame-edge.gif)"/></div>
                <div class="BoxFrameEdgeRightTop" style="background-image:url(' . $layout_name . '/images/global/content/box-frame-edge.gif)"/></div>
                <div class="ErrorMessage"><div class="BoxFrameVerticalLeft" style="background-image:url(' . $layout_name . '/images/global/content/box-frame-vertical.gif)"/></div>
                <div class="BoxFrameVerticalRight" style="background-image:url(' . $layout_name . '/images/global/content/box-frame-vertical.gif)"/></div>
                <div class="AttentionSign" style="background-image:url(' . $layout_name . '/images/global/content/attentionsign.gif)"/></div>
                <b>The Following Errors Have Occurred:</b>
                <br/>';
            foreach ($erro as $error) $main_content .= $error . '<br/>';
            $main_content .= '</div>
                <div class="BoxFrameHorizontal" style="background-image:url(' . $layout_name . '/images/global/content/box-frame-horizontal.gif)"/></div>
                <div class="BoxFrameEdgeRightBottom" style="background-image:url(' . $layout_name . '/images/global/content/box-frame-edge.gif)"/></div>
                <div class="BoxFrameEdgeLeftBottom" style="background-image:url(' . $layout_name . '/images/content/global/box-frame-edge.gif)"/>
                </div>
                </div>
                </div>
                <br/>';

            $main_content .= '
			<script src="' . $layout_name . '/js/create_character.js"></script>
			<div style="position:relative;top:0px;left:0px;" >
				<form action="?subtopic=createaccount" method=post name="CreateAccountAndCharacter" >
					<div class="TableContainer" >
						<table class="Table5" cellpadding="0" cellspacing="0" >
							<div class="CaptionContainer" >
								<div class="CaptionInnerContainer" > 
									<span class="CaptionEdgeLeftTop" style="background-image:url(' . $layout_name . '/images/global/content/box-frame-edge.gif);" /></span>
									<span class="CaptionEdgeRightTop" style="background-image:url(' . $layout_name . '/images/global/content/box-frame-edge.gif);" /></span>
									<span class="CaptionBorderTop" style="background-image:url(' . $layout_name . '/images/global/content/table-headline-border.gif);" ></span> 
									<span class="CaptionVerticalLeft" style="background-image:url(' . $layout_name . '/images/global/content/box-frame-vertical.gif);" /></span>								
									<div class="Text" >Create New Account</div>
									<span class="CaptionVerticalRight" style="background-image:url(' . $layout_name . '/images/global/content/box-frame-vertical.gif);" /></span>
									<span class="CaptionBorderBottom" style="background-image:url(' . $layout_name . '/images/global/content/table-headline-border.gif);" ></span> 
									<span class="CaptionEdgeLeftBottom" style="background-image:url(' . $layout_name . '/images/global/content/box-frame-edge.gif);" /></span>
									<span class="CaptionEdgeRightBottom" style="background-image:url(' . $layout_name . '/images/global/content/box-frame-edge.gif);" /></span>
								</div>
							</div>';
            //Account
            $main_content .= '
							<tr>
								<td>
									<div class="InnerTableContainer" >
										<table style="width:100%;" >
											<tr>
												<td>
													<div class="TableShadowContainerRightTop" >
														<div class="TableShadowRightTop" style="background-image:url(' . $layout_name . '/images/global/content/table-shadow-rt.gif);" ></div>
													</div>
													<div class="TableContentAndRightShadow" style="background-image:url(' . $layout_name . '/images/global/content/table-shadow-rm.gif);" >
														<div class="TableContentContainer" >
															<table class="TableContent" width="100%"  style="border:1px solid #faf0d7;" >
																<tr>
																	<td class="LabelV150" >
																		<span id="accountname_label"' . (isset($e['acc']) ? ' class="red"' : '') . ' >Account Name:</span>
																	</td>																	
																	<td>
																		<input id="accountname" 
																		name="accountname" 
																		class="CipAjaxInput" 
																		style="width:206px;float:left;" 
																		value="' . (isset($_POST['accountname']) ? htmlspecialchars(substr($_POST['accountname'], 0, 30)) : '') . '" 
																		size="30" 
																		maxlength="30" 
																		onBlur="SendAjaxCip({DataType: \'Container\'}, {Href: \'./ajax_account.php\',PostData: \'a_AccountName=\'+getElementById(\'accountname\').value,Method: \'POST\'});" />
																		<div id="accountname_indicator" class="InputIndicator" style="background-image:url(' . $layout_name . '/images/global/general/' . (isset($e['acc']) ? 'n' : '') . 'ok.gif);" ></div>
																	</td>
																</tr>
																<tr>
																	<td></td>
																	<td><span id="accountname_errormessage" class="FormFieldError">' . (isset($e['acc']) ? $e['acc'] : '') . '</span></td>
																</tr>
																<tr>
																	<td class="LabelV150" >
																		<span id="email_label"' . (isset($e['email']) ? ' class="red"' : '') . '>Email Address:</span>
																	</td>
																	<td>
																		<input id="email" name="email" class="CipAjaxInput" style="width:206px;float:left;" value="' . (isset($_POST['email']) ? htmlspecialchars(substr($_POST['email'], 0, 50)) : '') . '" size="30" maxlength="50" onBlur="SendAjaxCip({DataType: \'Container\'}, {Href: \'./ajax_email.php\',PostData: \'a_EMail=\'+encodeURIComponent(getElementById(\'email\').value),Method: \'POST\'});" />
																		<div id="email_indicator" class="InputIndicator" style="background-image:url(' . $layout_name . '/images/global/general/' . ($_POST['step'] != 'docreate' || isset($e['email']) ? 'n' : '') . 'ok.gif);" ></div>
																	</td>																	
																</tr>
																<tr>
																	<td></td>
																	<td><span id="email_errormessage" class="FormFieldError">' . (isset($e['email']) ? $e['email'] : '') . '</span></td>
																</tr>
																<tr>
																	<td class="LabelV150" >
																		<span id="password1_label"' . (isset($e['pass']) ? ' class="red"' : '') . '>Password:</span>
																	</td>
																	<td>
																		<input id="password1" type="password" name="password1" style="width:206px;float:left;" value="' . (isset($_POST['password1']) ? htmlspecialchars(substr($_POST['password1'], 0, 30)) : '') . '" size="30" maxlength="30" onBlur="SendAjaxCip({DataType: \'Container\'}, {Href: \'./account/ajax_password.php\',PostData: \'a_Password1=\'+getElementById(\'password1\').value+\'&a_Password2=\'+getElementById(\'password2\').value,Method: \'POST\'});" />
																		<div id="password1_indicator" class="InputIndicator" style="background-image:url(' . $layout_name . '/images/global/general/' . ($_POST['step'] != 'docreate' || isset($e['pass']) ? 'n' : '') . 'ok.gif);" ></div>
																	</td>
																</tr>
																<tr>
																	<td class="LabelV150" >
																		<span id="password2_label"' . (isset($e['pass']) ? ' class="red"' : '') . '>Password Again:</span>
																	</td>
																	<td>
																		<input id="password2" type="password" name="password2" style="width:206px;float:left;" value="' . (isset($_POST['password2']) ? htmlspecialchars(substr($_POST['password2'], 0, 30)) : '') . '" size="30" maxlength="30" onBlur="SendAjaxCip({DataType: \'Container\'}, {Href: \'./account/ajax_password.php\',PostData: \'a_Password1=\'+getElementById(\'password1\').value+\'&a_Password2=\'+getElementById(\'password2\').value,Method: \'POST\'});" />
																		<div id="password2_indicator" class="InputIndicator" style="background-image:url(' . $layout_name . '/images/global/general/' . ($_POST['step'] != 'docreate' || isset($e['pass']) ? 'n' : '') . 'ok.gif);" ></div>
																	</td>
																</tr>
																<tr>
																	<td></td>
																	<td><span id="password_errormessage" class="FormFieldError">' . (isset($e['pass']) ? $e['pass'] : '') . '</span></td>
																</tr>
															</table>
														</div>
													</div>
													<script>
                                                        window.onload = function() {
                                                          SendAjaxCip({DataType: \'Container\'}, {Href: \'./ajax_account.php\',PostData: \'a_AccountName=\'+document.getElementById(\'accountname\').value,Method: \'POST\'});
                                                          SendAjaxCip({DataType: \'Container\'}, {Href: \'./ajax_email.php\',PostData: \'a_EMail=\'+encodeURIComponent(document.getElementById(\'email\').value),Method: \'POST\'});
                                                          SendAjaxCip({DataType: \'Container\'}, {Href: \'./ajax_email.php\',PostData: \'a_EMail=\'+encodeURIComponent(document.getElementById(\'email\').value),Method: \'POST\'});
                                                          SendAjaxCip({DataType: \'Container\'}, {Href: \'./account/ajax_password.php\',PostData: \'a_Password1=\'+document.getElementById(\'password1\').value+\'&a_Password2=\'+document.getElementById(\'password2\').value,Method: \'POST\'});
                                                          //SendAjaxCip({DataType: \'Container\'}, {Href: \'./account/ajax_password.php\',PostData: \'a_Password1=\'+document.getElementById(\'password1\').value+\'&a_Password2=\'+document.getElementById(\'password2\').value,Method: \'POST\'});
                                                            
                                                        
                                                        };
                                                    </script>
													<div class="TableShadowContainer" >
														<div class="TableBottomShadow" style="background-image:url(' . $layout_name . '/images/global/content/table-shadow-bm.gif);" >
															<div class="TableBottomLeftShadow" style="background-image:url(' . $layout_name . '/images/global/content/table-shadow-bl.gif);" ></div>
															<div class="TableBottomRightShadow" style="background-image:url(' . $layout_name . '/images/global/content/table-shadow-br.gif);" ></div>
														</div>
													</div>
												</td>
											</tr>';
            $main_content .= '									
<tr>
									 <tr>
                <td>
                    <div class="TableShadowContainerRightTop" >
                        <div class="TableShadowRightTop" style="background-image:url(' . $layout_name . '/images/global/content/table-shadow-rt.gif);" ></div>
                    </div>
                    <div class="TableContentAndRightShadow" style="background-image:url(' . $layout_name . '/images/global/content/table-shadow-rm.gif);" >
                        <div class="TableContentContainer" >
                            <table class="TableContent" width="100%"  style="border:1px solid #faf0d7;" >
													<tbody>
													<tr>
															<td class="LabelV150" valign="top"><span><b>World Type:</b></span></td>
															<td>
																<table width="40%">
																	<tbody><tr>
																		<td align="center"><img src="' . $layout_name . '/images/account/option_server_pvp_type_open.gif"></td>
																	</tr>
																	<tr>
																		<td align="center"><input type="radio" checked="checked"> <b>Open PvP</b></td>
																	</tr>
																	<tr>
																		<td align="center">Killing other characters is possible, but restricted</td>
																	</tr>
																	</tbody></table><br>
															</td>
														</tr>
														
														<tr>
																	<td valign="top"><span><b>World Name:</b></span></td>
																	<td>Suggested world: <b>' . $config['server']['serverName'] . '</b> 
																	<span>
																	<span class="HelperDivIndicator" onMouseOver="ActivateHelperDiv($(this), \'Free premium game world:\', \'This game world free premium for players\', \'\');" onMouseOut="$(\'#HelperDivContainer\').hide();" >
																		<image style="border:0px;" src="' . $layout_name . '/images/global/content/info.gif" />
																		</span></span>
																		<span>
																	<span class="HelperDivIndicator" onMouseOver="ActivateHelperDiv($(this), \'Staff present in game world:\', \'On this game world, Staff blocks cheats from the game. The game world has been protected by Staff since its release.\', \'\');" onMouseOut="$(\'#HelperDivContainer\').hide();" >
																		<image style="border:0px;" src="' . $layout_name . '/images/global/content/icon_battleyeinitial.gif" />
																		</span></span>
																	
																	
																	<br><small> [<a href="#">change game world</a>]</small><br><br></td>
																	</tr>
														
														
													</tbody>
												</table>
											</div>
										</div>
										<div class="TableShadowContainer" >
                        <div class="TableBottomShadow" style="background-image:url(' . $layout_name . '/images/global/content/table-shadow-bm.gif);" >
                            <div class="TableBottomLeftShadow" style="background-image:url(' . $layout_name . '/images/global/content/table-shadow-bl.gif);" ></div>
                            <div class="TableBottomRightShadow" style="background-image:url(' . $layout_name . '/images/global/content/table-shadow-br.gif);" ></div>
                        </div>
                    </div>
                </td>
            </tr>
		
		';
            $main_content .= '
											<tr>
												<td>
													<div class="TableShadowContainerRightTop" >
														<div class="TableShadowRightTop" style="background-image:url(' . $layout_name . '/images/global/content/table-shadow-rt.gif);" ></div>
													</div>
													<div class="TableContentAndRightShadow" style="background-image:url(' . $layout_name . '/images/global/content/table-shadow-rm.gif);" >
														<div class="TableContentContainer" >
															<table class="TableContent" width="100%"  style="border:1px solid #faf0d7;" >
																<tr>
																	<td><b>Please select the following check box:</b></td>
																</tr>
																<tr>
																	<td><input type="checkbox" name="agreeagreements" value="true"  onClick="if(this.checked == true) {  document.getElementById(\'agreeagreements_errormessage\').innerHTML = \'\';} else {  document.getElementById(\'agreeagreements_errormessage\').innerHTML = \'You have to agree to the ' . $config['server']['serverName'] . ' Rules in order to create an account!\';}"' . ($_POST['step'] == 'docreate' && !isset($e['rules']) ? ' checked="checked"' : '') . '/>
																		I agree to the <a href="?subtopic=tibiarules" target="_blank" >' . $config['server']['serverName'] . ' Rules</a>.</td>
																</tr>
																<tr>
																	<td><span id="agreeagreements_errormessage" class="FormFieldError">' . (isset($e['rules']) ? $e['rules'] : '') . '</span></td>
																</tr>
															</table>
														</div>
													</div>
													<div class="TableShadowContainer" >
														<div class="TableBottomShadow" style="background-image:url(' . $layout_name . '/images/global/content/table-shadow-bm.gif);" >
															<div class="TableBottomLeftShadow" style="background-image:url(' . $layout_name . '/images/global/content/table-shadow-bl.gif);" ></div>
															<div class="TableBottomRightShadow" style="background-image:url(' . $layout_name . '/images/global/content/table-shadow-br.gif);" ></div>
														</div>
													</div>
												</td>
											</tr>';
            $main_content .= '
										</table>
									</div>
								</td>
							</tr>';

            $main_content .= '
						</table>
					</div>
					<br />
					<center>
						<table border="0" cellspacing="0" cellpadding="0" >
						<tr>
							<td style="border:0px;" >
								<input type="hidden" name=step value=docreate >
								<input type="hidden" name=noframe value= >
								<div class="BigButton" style="background-image:url(' . $layout_name . '/images/global/buttons/sbutton.gif)" >
									<div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" ><div class="BigButtonOver" style="background-image:url(' . $layout_name . '/images/global/buttons/sbutton_over.gif);" ></div>
										<input class="ButtonText" type="image" name="Submit" alt="Submit" src="' . $layout_name . '/images/global/buttons/_sbutton_submit.gif" >
									</div>
								</div>
							</td>
						<tr>
					</form>
				</table>
			</center>
		</form>		
	</div>';

        } else {
            $reg_account = new Account();
            $reg_account->setName(strtoupper($_POST['accountname']));
            $reg_account->setPassword($_POST['password1']);
            $reg_account->setEMail($_POST['email']);
            $reg_account->setCreateDate(time());
            $reg_account->setCreateIP(Visitor::getIP());
            $reg_account->setFlag(Website::getCountryCode(long2ip(Visitor::getIP())));
            $reg_account->save();

            if ($config['site']['send_emails']) {
                $reg_name = $reg_account->getName();
                $reg_email = $reg_account->getEMail();
                $mailBody = '<html>
			<body>
			<h3>Your account name and password!</h3>
			<p>You or someone else registred on server <a href="' . $config['server']['url'] . '"><b>' . htmlspecialchars($config['server']['serverName']) . '</b></a> with this e-mail.</p>
			<p>Account name: <b>' . htmlspecialchars($reg_name) . '</b></p>			
			<br />
			<p>After login you can:</p>
			<li>Create new characters
			<li>Change your current password
			<li>Change your current e-mail
			</body>
			</html>';
                $mail = new PHPMailer();
                if ($config['site']['smtp_enabled']) {
                    $mail->IsSMTP();
                    $mail->Host = $config['site']['smtp_host'];
                    $mail->Port = (int)$config['site']['smtp_port'];
                    $mail->SMTPAuth = $config['site']['smtp_auth'];
                    $mail->Username = $config['site']['smtp_user'];
                    $mail->Password = $config['site']['smtp_pass'];
                    if ($config['site']['smtp_secure']) {
                        if ((int)$config['site']['smtp_port'] == 465)
                            $mail->SMTPSecure = "ssl";
                        else
                            $mail->SMTPSecure = "tls";
                    }

                    $mail->FromName = $config['site']['mail_senderName'];
                    $mail->IsHTML(true);
                    $mail->From = $config['site']['mail_address'];
                    $mail->AddAddress($reg_email);
                    $mail->Subject = $config['server']['serverName'] . " - Registration";
                    $mail->Body = $mailBody;
                }

                if ($mail->Send()) {
                    //$main_content .= 'Your account has been created. Check your e-mail. See you in ' . htmlspecialchars($config['server']['serverName']) . '!<BR><BR>';
                    $main_content .= '<TABLE WIDTH=100% BORDER=0 CELLSPACING=1 CELLPADDING=4>
				<TR><TD BGCOLOR="' . $config['site']['vdarkborder'] . '" CLASS=white><B>Account Created</B></TD></TR>
				<TR><TD BGCOLOR="' . $config['site']['darkborder'] . '">
				  <TABLE BORDER=0 CELLPADDING=1><TR><TD>
				    <BR>Your account name is <b>' . $reg_name . '</b>.
					<BR><b><i>You will receive e-mail at (<b>' . htmlspecialchars($reg_email) . '</b>) with your account details.</b></i><br>';
                    $main_content .= 'You will need the account name and your password to play on ' . htmlspecialchars($config['server']['serverName']) . '.
				    Please keep your account name and password in a safe place and
				    never give your account name or password to anybody.<BR><BR>
				    <br /><small>If these information do not arrive on <b>' . htmlspecialchars($reg_email) . ' in the next few minutes</b>. Please check your spam folder and add our address to your e-mail contact list.
                    </small>
                    </TD></TR></TABLE>
                    </TD></TR></TABLE>';

                } else {
                    $main_content .= '<h2>Your account has been created.</h2>';
                    error_log('Error sending e-mail: ' . $mail->ErrorInfo, 1);
                }
            } else header("Location: ?subtopic=latestnews");

        }

    } else {

        $main_content .= '
			<script src="' . $layout_name . '/js/create_character.js"></script>
			<div style="position:relative;top:0px;left:0px;" >
				<form action="?subtopic=createaccount" method=post name="CreateAccountAndCharacter" >
					<div class="TableContainer" >
						<table class="Table5" cellpadding="0" cellspacing="0" >
							<div class="CaptionContainer" >
								<div class="CaptionInnerContainer" > 
									<span class="CaptionEdgeLeftTop" style="background-image:url(' . $layout_name . '/images/global/content/box-frame-edge.gif);" /></span>
									<span class="CaptionEdgeRightTop" style="background-image:url(' . $layout_name . '/images/global/content/box-frame-edge.gif);" /></span>
									<span class="CaptionBorderTop" style="background-image:url(' . $layout_name . '/images/global/content/table-headline-border.gif);" ></span> 
									<span class="CaptionVerticalLeft" style="background-image:url(' . $layout_name . '/images/global/content/box-frame-vertical.gif);" /></span>								
									<div class="Text" >Create New Account</div>
									<span class="CaptionVerticalRight" style="background-image:url(' . $layout_name . '/images/global/content/box-frame-vertical.gif);" /></span>
									<span class="CaptionBorderBottom" style="background-image:url(' . $layout_name . '/images/global/content/table-headline-border.gif);" ></span> 
									<span class="CaptionEdgeLeftBottom" style="background-image:url(' . $layout_name . '/images/global/content/box-frame-edge.gif);" /></span>
									<span class="CaptionEdgeRightBottom" style="background-image:url(' . $layout_name . '/images/global/content/box-frame-edge.gif);" /></span>
								</div>
							</div>';
        //Account
        $main_content .= '
							<tr>
								<td>
									<div class="InnerTableContainer" >
										<table style="width:100%;" >
											<tr>
												<td>
													<div class="TableShadowContainerRightTop" >
														<div class="TableShadowRightTop" style="background-image:url(' . $layout_name . '/images/global/content/table-shadow-rt.gif);" ></div>
													</div>
													<div class="TableContentAndRightShadow" style="background-image:url(' . $layout_name . '/images/global/content/table-shadow-rm.gif);" >
														<div class="TableContentContainer" >
															<table class="TableContent" width="100%"  style="border:1px solid #faf0d7;" >
																<tr>
																	<td class="LabelV150" >
																		<span id="accountname_label"' . (isset($e['acc']) ? ' class="red"' : '') . ' >Account Name:</span>
																	</td>
																	<td>
																		<input id="accountname" name="accountname" class="CipAjaxInput" style="width:206px;float:left;" value="' . (isset($_POST['accountname']) ? htmlspecialchars(substr($_POST['accountname'], 0, 30)) : '') . '" size="30" maxlength="30" onBlur="SendAjaxCip({DataType: \'Container\'}, {Href: \'./ajax_account.php\',PostData: \'a_AccountName=\'+getElementById(\'accountname\').value,Method: \'POST\'});" />
																		<div id="accountname_indicator" class="InputIndicator" style="background-image:url(' . $layout_name . '/images/global/general/' . ($_POST['step'] != 'docreate' || isset($e['acc']) ? 'n' : '') . 'ok.gif);" ></div>
																	</td>
																</tr>
																<tr>
																	<td></td>
																	<td><span id="accountname_errormessage" class="FormFieldError">' . (isset($e['acc']) ? $e['acc'] : '') . '</span></td>
																</tr>
																<tr>
																	<td class="LabelV150" >
																		<span id="email_label"' . (isset($e['email']) ? ' class="red"' : '') . '>Email Address:</span>
																	</td>
																	<td>
																		<input id="email" name="email" class="CipAjaxInput" style="width:206px;float:left;" value="' . (isset($_POST['email']) ? htmlspecialchars(substr($_POST['email'], 0, 50)) : '') . '" size="30" maxlength="50" onBlur="SendAjaxCip({DataType: \'Container\'}, {Href: \'./ajax_email.php\',PostData: \'a_EMail=\'+encodeURIComponent(getElementById(\'email\').value),Method: \'POST\'});" />
																		<div id="email_indicator" class="InputIndicator" style="background-image:url(' . $layout_name . '/images/global/general/' . ($_POST['step'] != 'docreate' || isset($e['email']) ? 'n' : '') . 'ok.gif);" ></div>
																	</td>
																</tr>
																<tr>
																	<td></td>
																	<td><span id="email_errormessage" class="FormFieldError">' . (isset($e['email']) ? $e['email'] : '') . '</span></td>
																</tr>
																<tr>
																	<td class="LabelV150" >
																		<span id="password1_label"' . (isset($e['pass']) ? ' class="red"' : '') . '>Password:</span>
																	</td>
																	<td>
																		<input id="password1" type="password" name="password1" style="width:206px;float:left;" value="' . (isset($_POST['password1']) ? htmlspecialchars(substr($_POST['password1'], 0, 30)) : '') . '" size="30" maxlength="30" onBlur="SendAjaxCip({DataType: \'Container\'}, {Href: \'./account/ajax_password.php\',PostData: \'a_Password1=\'+getElementById(\'password1\').value+\'&a_Password2=\'+getElementById(\'password2\').value,Method: \'POST\'});" />
																		<div id="password1_indicator" class="InputIndicator" style="background-image:url(' . $layout_name . '/images/global/general/' . ($_POST['step'] != 'docreate' || isset($e['pass']) ? 'n' : '') . 'ok.gif);" ></div>
																	</td>
																</tr>
																<tr>
																	<td class="LabelV150" >
																		<span id="password2_label"' . (isset($e['pass']) ? ' class="red"' : '') . '>Password Again:</span>
																	</td>
																	<td>
																		<input id="password2" type="password" name="password2" style="width:206px;float:left;" value="' . (isset($_POST['password2']) ? htmlspecialchars(substr($_POST['password2'], 0, 30)) : '') . '" size="30" maxlength="30" onBlur="SendAjaxCip({DataType: \'Container\'}, {Href: \'./account/ajax_password.php\',PostData: \'a_Password1=\'+getElementById(\'password1\').value+\'&a_Password2=\'+getElementById(\'password2\').value,Method: \'POST\'});" />
																		<div id="password2_indicator" class="InputIndicator" style="background-image:url(' . $layout_name . '/images/global/general/' . ($_POST['step'] != 'docreate' || isset($e['pass']) ? 'n' : '') . 'ok.gif);" ></div>
																	</td>
																</tr>
																<tr>
																	<td></td>
																	<td><span id="password_errormessage" class="FormFieldError">' . (isset($e['pass']) ? $e['pass'] : '') . '</span></td>
																</tr>
															</table>
														</div>
													</div>
													<div class="TableShadowContainer" >
														<div class="TableBottomShadow" style="background-image:url(' . $layout_name . '/images/global/content/table-shadow-bm.gif);" >
															<div class="TableBottomLeftShadow" style="background-image:url(' . $layout_name . '/images/global/content/table-shadow-bl.gif);" ></div>
															<div class="TableBottomRightShadow" style="background-image:url(' . $layout_name . '/images/global/content/table-shadow-br.gif);" ></div>
														</div>
													</div>
												</td>
											</tr>';
        $main_content .= '									
<tr>
									 <tr>
                <td>
                    <div class="TableShadowContainerRightTop" >
                        <div class="TableShadowRightTop" style="background-image:url(' . $layout_name . '/images/global/content/table-shadow-rt.gif);" ></div>
                    </div>
                    <div class="TableContentAndRightShadow" style="background-image:url(' . $layout_name . '/images/global/content/table-shadow-rm.gif);" >
                        <div class="TableContentContainer" >
                            <table class="TableContent" width="100%"  style="border:1px solid #faf0d7;" >
													<tbody>
													<tr>
															<td class="LabelV150" valign="top"><span><b>World Type:</b></span></td>
															<td>
																<table width="40%">
																	<tbody><tr>
																		<td align="center"><img src="' . $layout_name . '/images/account/option_server_pvp_type_open.gif"></td>
																	</tr>
																	<tr>
																		<td align="center"><input type="radio" checked="checked"> <b>Open PvP</b></td>
																	</tr>
																	<tr>
																		<td align="center">Killing other characters is possible, but restricted</td>
																	</tr>
																	</tbody></table><br>
															</td>
														</tr>
														
														<tr>
																	<td valign="top"><span><b>World Name:</b></span></td>
																	<td>Suggested world: <b>' . $config['server']['serverName'] . '</b> 
																	<span>
																	<span class="HelperDivIndicator" onMouseOver="ActivateHelperDiv($(this), \'Free premium game world:\', \'This game world free premium for players\', \'\');" onMouseOut="$(\'#HelperDivContainer\').hide();" >
																		<image style="border:0px;" src="' . $layout_name . '/images/global/content/info.gif" />
																		</span></span>
																		<span>
																	<span class="HelperDivIndicator" onMouseOver="ActivateHelperDiv($(this), \'Staff present in game world:\', \'On this game world, Staff blocks cheats from the game. The game world has been protected by Staff since its release.\', \'\');" onMouseOut="$(\'#HelperDivContainer\').hide();" >
																		<image style="border:0px;" src="' . $layout_name . '/images/global/content/icon_battleyeinitial.gif" />
																		</span></span>
																	
																	
																	<br><small> [<a href="#">change game world</a>]</small><br><br></td>
																	</tr>
														
														
													</tbody>
												</table>
											</div>
										</div>
										<div class="TableShadowContainer" >
                        <div class="TableBottomShadow" style="background-image:url(' . $layout_name . '/images/global/content/table-shadow-bm.gif);" >
                            <div class="TableBottomLeftShadow" style="background-image:url(' . $layout_name . '/images/global/content/table-shadow-bl.gif);" ></div>
                            <div class="TableBottomRightShadow" style="background-image:url(' . $layout_name . '/images/global/content/table-shadow-br.gif);" ></div>
                        </div>
                    </div>
                </td>
            </tr>
		
		';
        $main_content .= '
											<tr>
												<td>
													<div class="TableShadowContainerRightTop" >
														<div class="TableShadowRightTop" style="background-image:url(' . $layout_name . '/images/global/content/table-shadow-rt.gif);" ></div>
													</div>
													<div class="TableContentAndRightShadow" style="background-image:url(' . $layout_name . '/images/global/content/table-shadow-rm.gif);" >
														<div class="TableContentContainer" >
															<table class="TableContent" width="100%"  style="border:1px solid #faf0d7;" >
																<tr>
																	<td><b>Please select the following check box:</b></td>
																</tr>
																<tr>
																	<td><input type="checkbox" name="agreeagreements" value="true"  onClick="if(this.checked == true) {  document.getElementById(\'agreeagreements_errormessage\').innerHTML = \'\';} else {  document.getElementById(\'agreeagreements_errormessage\').innerHTML = \'You have to agree to the ' . $config['server']['serverName'] . ' Rules in order to create an account!\';}"' . ($_POST['step'] == 'docreate' && !isset($e['rules']) ? ' checked="checked"' : '') . '/>
																		I agree to the <a href="?subtopic=tibiarules" target="_blank" >' . $config['server']['serverName'] . ' Rules</a>.</td>
																</tr>
																<tr>
																	<td><span id="agreeagreements_errormessage" class="FormFieldError">' . (isset($e['rules']) ? $e['rules'] : '') . '</span></td>
																</tr>
															</table>
														</div>
													</div>
													<div class="TableShadowContainer" >
														<div class="TableBottomShadow" style="background-image:url(' . $layout_name . '/images/global/content/table-shadow-bm.gif);" >
															<div class="TableBottomLeftShadow" style="background-image:url(' . $layout_name . '/images/global/content/table-shadow-bl.gif);" ></div>
															<div class="TableBottomRightShadow" style="background-image:url(' . $layout_name . '/images/global/content/table-shadow-br.gif);" ></div>
														</div>
													</div>
												</td>
											</tr>';
        $main_content .= '
										</table>
									</div>
								</td>
							</tr>';

        $main_content .= '
						</table>
					</div>
					<br />
					<center>
						<table border="0" cellspacing="0" cellpadding="0" >
						<tr>
							<td style="border:0px;" >
								<input type="hidden" name=step value=docreate >
								<input type="hidden" name=noframe value= >
								<div class="BigButton" style="background-image:url(' . $layout_name . '/images/global/buttons/sbutton.gif)" >
									<div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" ><div class="BigButtonOver" style="background-image:url(' . $layout_name . '/images/global/buttons/sbutton_over.gif);" ></div>
										<input class="ButtonText" type="image" name="Submit" alt="Submit" src="' . $layout_name . '/images/global/buttons/_sbutton_submit.gif" >
									</div>
								</div>
							</td>
						<tr>
					</form>
				</table>
			</center>
		</form>		
            </div>';

    }


} else header("Location: ./?subtopic=accountmanagement");
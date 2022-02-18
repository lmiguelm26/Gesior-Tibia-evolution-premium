<?php
if (!$_POST) {
    header("Location: ./");
} else {
    if ($group_id_of_acc_logged >= $config['site']['access_admin_panel']) {
        if ($_POST['acao'] == "manageCategories") {
            $categoryID = (int)$_POST['catID'];
	    $selectCat = filter_var($selectCat, FILTER_SANITIZE_STRING);
            $selectCat = $SQL->query("SELECT `hide` FROM `z_shop_category` WHERE `id` = '$categoryID'")->fetch();
            if ($selectCat['hide'] == 0) {
		$update = filter_var($update, FILTER_SANITIZE_STRING);
                $update = $SQL->query("UPDATE `z_shop_category` SET `hide` = 1 WHERE `id` = '$categoryID'");
                $action = "hide";
            } else {
		$update = filter_var($update, FILTER_SANITIZE_STRING);
                $update = $SQL->query("UPDATE `z_shop_category` SET `hide` = 0 WHERE `id` = '$categoryID'");
                $action = "unhide";
            }
            echo json_encode(array('status' => 'success', 'action' => $action));
        }

        if ($_POST['acao'] == "playerGift") {

            $playerName = trim($_POST['playerName']);
            $serviceID = (int)$_POST['giftID'];

            $player = new Player();
            $player->loadByName($playerName);
            if (!$player->isLoaded())
                $gift_error[] = "The player " . $playerName . " is wrong or does not exist.";
            $playerAccount = $player->getAccount()->getName();
            if (empty($gift_error)) {
		$update_order = filter_var($update_order, FILTER_SANITIZE_STRING);
                $update_order = $SQL->query("UPDATE `z_shop_payment` SET `account_name` = '" . $playerAccount . "' WHERE `id` = '$serviceID'");
                if ($update_order) {
                    $status = "success";
                    $msg = "You presented this service to your friend successfully.";
                }
            } else {
                $status = "error";
                $msg = "The player " . $playerName . " is wrong or does not exist.";
            }

            echo json_encode(array('status' => $status, 'msg' => $msg));
        }

        if ($_POST['acao'] == "managePayments") {
            $paymentID = (int)$_POST['payID'];
	    $selectPay = filter_var($selectPay, FILTER_SANITIZE_STRING);
            $selectPay = $SQL->query("SELECT `hide` FROM `z_shop_payment_method` WHERE `id` = '$paymentID'")->fetch();
            if ($selectPay['hide'] == 0) {
				$update = filter_var($update, FILTER_SANITIZE_STRING);
                $update = $SQL->query("UPDATE `z_shop_payment_method` SET `hide` = 1 WHERE `id` = '$paymentID'");
                $action = "hide";
            } else {
				$update = filter_var($update, FILTER_SANITIZE_STRING);
                $update = $SQL->query("UPDATE `z_shop_payment_method` SET `hide` = 0 WHERE `id` = '$paymentID'");
                $action = "unhide";
            }
            echo json_encode(array('status' => 'success', 'action' => $action));
        }

        if ($_POST['acao'] == "addMounts") {

            $mountInfo = $_POST['mountInfo'];
            $mArray = explode(",", $mountInfo);
            $mountId = $mArray[0];
            $mountName = $mArray[1];
            $mountCost = (int)$_POST['mountCost'];
            $offerDate = time();
			
	    $addMount = filter_var($addMount, FILTER_SANITIZE_STRING);
            $addMount = $SQL->query("INSERT INTO `z_shop_offer` (`category`,`coins`,`mount_id`,`count`,`offer_type`,`offer_name`,`offer_date`) VALUES (3,'$mountCost','$mountId',1,'mounts','$mountName','$offerDate')");
            if ($addMount)
                echo json_encode(array('status' => 'success', 'msg' => 'Mount successfully added .'));
            else echo json_encode(array('error' => 'success', 'msg' => 'Was not possible to add the mount , unknown problem .'));
        }

        if ($_POST['acao'] == "delMount") {
            $offerId = (int)$_POST['offerId'];
	    $delMount = filter_var($delMount, FILTER_SANITIZE_STRING);
            $delMount = $SQL->query("DELETE FROM `z_shop_offer` WHERE `id` = '$offerId'");
            if ($delMount) {
                echo json_encode(array('status' => 'success'));
            } else {
                echo json_encode(array('status' => 'success'));
            }
        }

        if ($_POST['acao'] == "delOutfit") {
            $offerId = (int)$_POST['offerId'];
	    $delOutfit = filter_var($emailcode, FILTER_SANITIZE_STRING);
            $delOutfit = $SQL->query("DELETE FROM `z_shop_offer` WHERE `id` = '$offerId'");
            if ($delMount) {
                echo json_encode(array('status' => 'success'));
            } else {
                echo json_encode(array('status' => 'success'));
            }
        }

        if ($_POST['acao'] == "extraServiceStatus") {
            $offerID = (int)$_POST['offerID'];
	    $selectOffer = filter_var($selectOffer, FILTER_SANITIZE_STRING);
            $selectOffer = $SQL->query("SELECT `hide` FROM `z_shop_offer` WHERE `id` = '$offerID'")->fetch();
            if ($selectOffer['hide'] == 0) {
	    	$update = filter_var($update, FILTER_SANITIZE_STRING);
                $update = $SQL->query("UPDATE `z_shop_offer` SET `hide` = 1 WHERE `id` = '$offerID'");
                $action = "hide";
            } else {
	    	$update = filter_var($update, FILTER_SANITIZE_STRING);
                $update = $SQL->query("UPDATE `z_shop_offer` SET `hide` = 0 WHERE `id` = '$offerID'");
                $action = "unhide";
            }
            echo json_encode(array('status' => true, 'action' => $action));
        }

        if ($_POST['acao'] == "extraUpdatePrice") {
            $offerID = (int)$_POST['offerId'];
            $points = (int)$_POST['pointsChange'];
	    $updateOffer = filter_var($updateOffer, FILTER_SANITIZE_STRING);
            $updateOffer = $SQL->query("UPDATE `z_shop_offer` SET `coins` = '$points' WHERE `id` = '$offerID'");
            echo json_encode(array('status' => true, 'msg' => 'Successfully changed value !'));
        }

        if ($_POST['acao'] == "addOutfits") {
            $outfitName = $_POST['outfitName'];
            $outfitPrice = (int)$_POST['outfitPrice'];
            $offerName = $outfitName . " Outfit";
            $data = time();
            $offerType = "outfits";
			
	    $addOutfit = filter_var($addOutfit, FILTER_SANITIZE_STRING);
            $addOutfit = $SQL->query("INSERT INTO `z_shop_offer` (`category`,`coins`,`addon_name`,`count`,`offer_type`,`offer_name`,`offer_date`) VALUES (4,'$outfitPrice','$outfitName',1,'$offerType','$offerName','$data')");
            if ($addOutfit)
                echo json_encode(array('status' => 'success', 'msg' => 'The outfit was registered successfully.'));
        }

        if ($_POST['acao'] == "addItems") {
            $itemId = (int)$_POST['itemId'];
            $itemName = addslashes($_POST['itemName']);
            $itemDesc = trim($_POST['itemDesc']);
            $itemAmount = (int)$_POST['itemAmount'];
            $itemPrice = (int)$_POST['itemPrice'];
            $data = time();
            $offerType = "items";
			
	    $addItem = filter_var($addItem, FILTER_SANITIZE_STRING);
            $addItem = $SQL->query("INSERT INTO `z_shop_offer` (`category`,`coins`,`itemid`,`count`,`offer_type`,`offer_description`,`offer_name`,`offer_date`) VALUES (5,'$itemPrice','$itemId','$itemAmount','$offerType','$itemDesc','$itemName','$data')");
            if ($addItem)
                echo json_encode(array('status' => 'success', 'msg' => 'The item was registered successfully.'));
        }

        if ($_POST['acao'] == "delItem") {
            $offerId = (int)$_POST['offerId'];
	    $delItem = filter_var($delItem, FILTER_SANITIZE_STRING);
            $delItem = $SQL->query("DELETE FROM `z_shop_offer` WHERE `id` = '$offerId'");
            if ($delItem) {
                echo json_encode(array('status' => 'success'));
            } else {
                echo json_encode(array('status' => 'success'));
            }
        }

        if ($_POST['acao'] == "addTicker") {
            $ticker = trim(stripslashes($_POST['ticker']));
            $icon = $_POST['icon'];
            $data = time();
	    $addTicker = filter_var($addTicker, FILTER_SANITIZE_STRING);
            $addTicker = $SQL->query("INSERT INTO `newsticker` (`date`,`text`,`icon`) VALUES ('$data','$ticker','$icon')");
            if ($addTicker)
                echo json_encode(array('status' => 'success', 'msg' => 'Ticker has successfully added!'));
        }

        if ($_POST['acao'] == "delTicker") {
            $tickerID = (int)$_POST['tickerID'];
            $delTicker = $SQL->query("DELETE FROM `newsticker` WHERE `id` = '$tickerID'");
            if ($delTicker)
                echo json_encode(array('status' => 'success'));
        }

        if ($_POST['acao'] == "addPoints") {
            $pointsAmount = (int)$_POST['pointsAmount'];
            $pointsPrice = trim($_POST['pointsPrice']);
            $pointsDesc = trim($_POST['pointsDesc']);
            $offerType = "premiumpoints";
            $offerName = $pointsAmount . ' Premium Points';
            $data = time();
	    $addPoints = filter_var($addPoints, FILTER_SANITIZE_STRING);
            $addPoints = $SQL->query("INSERT INTO `z_shop_offer` (`category`,`price`,`count`,`offer_type`,`offer_description`,`offer_name`,`offer_date`) VALUES (6,'$pointsPrice','$pointsAmount','$offerType','$pointsDesc','$offerName','$data')");
            if ($addPoints)
                echo json_encode(array('status' => true, 'msg' => 'Premium points package was successfully added.'));
        }

        if ($_POST['acao'] == "delPoints") {
            $pointsID = (int)$_POST['pointsID'];
	    $delPoints = filter_var($delPoints, FILTER_SANITIZE_STRING);
            $delPoints = $SQL->query("DELETE FROM `z_shop_offer` WHERE `id` = '$pointsID'");
            if ($delPoints)
                echo json_encode(array('status' => 'success'));
        }

        if ($_POST['acao'] == "playerPoints") {
            $pointsAdd = (int)$_REQUEST['points'];
            $accounName = (string)$_REQUEST['accName'];

            $account = new Account();
            $account->find($accounName);

            $account->setPremiumPoints($account->getPremiumPoints() + $pointsAdd);
            $account->save();

            if ($account)
                echo json_encode(array('status' => 'success'));
        }

        if ($_POST['acao'] == "doublePoints") {
	    $doubleStatus = filter_var($doubleStatus, FILTER_SANITIZE_STRING);
            $doubleStatus = $SQL->query("SELECT `value` FROM `server_config` WHERE `config` = 'double'")->fetch();
            if ($doubleStatus['value'] == "active") {
		$doublePoints = filter_var($doublePoints, FILTER_SANITIZE_STRING);
                $doublePoints = $SQL->query("UPDATE `server_config` SET `value` = 'desactived' WHERE `config` = 'double'");
                $pointsD = "off";
            } else {
		$doublePoints = filter_var($doublePoints, FILTER_SANITIZE_STRING);
                $doublePoints = $SQL->query("UPDATE `server_config` SET `value` = 'active' WHERE `config` = 'double'");
                $pointsD = "on";
            }
            if ($doublePoints)
                echo json_encode(array('status' => 'success', 'points' => $pointsD));
        }
    }else{
        exit;
    }
    exit;
}
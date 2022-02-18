<?php
if(!defined('INITIALIZED'))
	exit;

class Account extends ObjectData
{
	const LOADTYPE_ID = 'id';
	const LOADTYPE_NAME = 'name';
	const LOADTYPE_MAIL = 'email';
	public static $table = 'accounts';
	public $data = array('name' => null, 'password' => null, 'secret'=>null, 'premdays' => 0, 'coins' => 0, 'lastday' => 0, 'email' => null, 'key' => null, 'create_ip' => null, 'creation' => null, 'page_access' => 1, 'location' => null, 'rlname' => null, 'birth_date' => null, 'gender' => null, 'email_new' => null, 'email_new_time' => 0, 'email_code' => null, 'next_email' => 0, 'last_post' => 0, 'flag' => null, 'vote' => 0, 'loyalty_points' => 0, 'guild_points' => 0, 'authToken' => '0');
	public static $fields = array('id', 'name', 'password', 'premdays', 'coins', 'lastday', 'email', 'key', 'create_ip', 'creation', 'page_access', 'location', 'rlname','birth_date', 'gender', 'email_new', 'email_new_time', 'email_code', 'next_email', 'last_post', 'flag', 'vote', 'loyalty_points', 'guild_points');
	public $players;
	public $playerRanks;
	public $guildAccess;
	public $bans;

    public function __construct($search_text = null, $search_by = self::LOADTYPE_ID)
    {
		if($search_text != null)
			$this->load($search_text, $search_by);
    }

	public function load($search_text, $search_by = self::LOADTYPE_ID)
	{
		if(in_array($search_by, self::$fields))
			$search_string = $this->getDatabaseHandler()->fieldName($search_by) . ' = ' . $this->getDatabaseHandler()->quote($search_text);
		else
			new Error_Critic('', 'Wrong Account search_by type.');
		$fieldsArray = array();
		foreach(self::$fields as $fieldName)
			$fieldsArray[$fieldName] = $this->getDatabaseHandler()->fieldName($fieldName);
		$this->data = $this->getDatabaseHandler()->query('SELECT ' . implode(', ', $fieldsArray) . ' FROM ' . $this->getDatabaseHandler()->tableName(self::$table) . ' WHERE ' . $search_string)->fetch();
	}

	public function loadById($id)
	{
		$this->load($id, 'id');
	}

	public function loadByName($name)
	{
		$this->load($name, 'name');
	}

	public function loadByEmail($mail)
	{
		$this->load($mail, 'email');
	}

	public function save($forceInsert = false)
	{
		if(!isset($this->data['id']) || $forceInsert)
		{
			$keys = array();
			$values = array();
			foreach(self::$fields as $key)
				if($key != 'id')
				{
					$keys[] = $this->getDatabaseHandler()->fieldName($key);
					$values[] = $this->getDatabaseHandler()->quote($this->data[$key]);
				}
			$this->getDatabaseHandler()->query('INSERT INTO ' . $this->getDatabaseHandler()->tableName(self::$table) . ' (' . implode(', ', $keys) . ') VALUES (' . implode(', ', $values) . ')');
			$this->setID($this->getDatabaseHandler()->lastInsertId());
		}
		else
		{
			$updates = array();
			foreach(self::$fields as $key)
				if($key != 'id')
					$updates[] = $this->getDatabaseHandler()->fieldName($key) . ' = ' . $this->getDatabaseHandler()->quote($this->data[$key]);
			$this->getDatabaseHandler()->query('UPDATE ' . $this->getDatabaseHandler()->tableName(self::$table) . ' SET ' . implode(', ', $updates) . ' WHERE ' . $this->getDatabaseHandler()->fieldName('id') . ' = ' . $this->getDatabaseHandler()->quote($this->data['id']));
		}
	}

	public function getPlayers($forceReload = false)
	{
		if(!isset($this->players) || $forceReload)
		{
			$this->players = new DatabaseList('Player');
			$this->players->setFilter(new SQL_Filter(new SQL_Field('account_id'), SQL_Filter::EQUAL, $this->getID()));
			$this->players->addOrder(new SQL_Order(new SQL_Field('name')));
		}
		return $this->players;
	}
	
	public function unban()
	{
        $this->getDatabaseHandler()->query('DELETE FROM ' . $this->getDatabaseHandler()->tableName('account_bans') . ' WHERE ' . $this->getDatabaseHandler()->fieldName('account_id') . ' = ' . $this->getDatabaseHandler()->quote($this->data['id']));

        unset($this->bans);
	}

	public function loadBans($forceReload = false)
	{
		if(!isset($this->bans) || $forceReload)
		{
			$this->bans = new DatabaseList('AccountBan');
			$filter = new SQL_Filter(new SQL_Field('account_id'), SQL_Filter::EQUAL, $this->data['id']);
			$this->bans->setFilter($filter);
		}
	}

	public function isBanned($forceReload = false)
	{
		$this->loadBans($forceReload);
		return count($this->bans) > 0;
	}

	public function getBanTime($forceReload = false)
	{
		$this->loadBans($forceReload);
		$lastExpires = 0;
		$now = date('Y-m-d h:i:s');
		foreach($this->bans->data as $ban)
		{
			if($ban['expires_at'] <= $now)
			{
				$lastExpires = 0;
				break;
			}
			if($ban['expires_at'] > $now && $ban['expires_at'] > $lastExpires)
				$lastExpires = $ban['expires_at'];
		}
		return $lastExpires;
	}

    public function delete()
    {
        $this->getDatabaseHandler()->query('DELETE FROM ' . $this->getDatabaseHandler()->tableName(self::$table) . ' WHERE ' . $this->getDatabaseHandler()->fieldName('id') . ' = ' . $this->getDatabaseHandler()->quote($this->data['id']));

        unset($this->data['id']);
    }

	public function setID($value){$this->data['id'] = $value;}
	public function getID(){return $this->data['id'];}
	public function setName($value){$this->data['name'] = $value;}
	public function getName(){return $this->data['name'];}
	public function setPassword($value)
	{
		$this->data['password'] = Website::encryptPassword($value, $this);
	}
	public function getPassword(){return $this->data['password'];}
	public function setPremDays($value){$this->data['premdays'] = $value;}
	public function getPremDays(){return $this->data['premdays'] - (date("z", time()) + (365 * (date("Y", time()) - date("Y", $this->data['lastday']))) - date("z", $this->data['lastday']));}
	public function setLastDay($value){$this->data['lastday'] = $value;}
	public function getLastDay(){return $this->data['lastday'];}
	public function setMail($value){$this->data['email'] = $value;}
	public function getMail(){return $this->data['email'];}
	public function setKey($value){$this->data['key'] = $value;}
	public function getKey(){return $this->data['key'];}
	public function setCreateIP($value){$this->data['create_ip'] = $value;}
	public function getCreateIP(){return $this->data['create_ip'];}
	public function setCreateDate($value){$this->data['creation'] = $value;}
	public function getCreateDate(){return $this->data['creation'];}
	public function setPremiumPoints($value){$this->data['coins'] = $value;}
	public function getPremiumPoints(){return $this->data['coins'];}
	public function setPageAccess($value){$this->data['page_access'] = $value;}
	public function getPageAccess(){return $this->data['page_access'];}
	public function setLocation($value){$this->data['location'] = $value;}
	public function getLocation(){return $this->data['location'];}
	public function setLoyalty($value){$this->data['loyalty_points'] = $value;}
	public function getLoyalty(){return $this->data['loyalty_points'];}
	public function setRLName($value){$this->data['rlname'] = $value;}
	public function getRLName(){return $this->data['rlname'];}
	public function setBirthDate($value){$this->data['birth_date'] = $value;}
	public function getBirthDate(){return $this->data['birth_date'];}
	public function setGender($value){$this->data['gender'] = $value;}
	public function getGender(){return $this->data['gender'];}
	public function setFlag($value){$this->data['flag'] = $value;}
	public function getFlag(){return $this->data['flag'];}
	public function getEMail(){return $this->getMail();}
	public function setEMail($value){$this->setMail($value);}
	public function getPlayersList(){return $this->getPlayers();}
	public function getGuildAccess($guildID){return $this->getGuildLevel($guildID);}
	public function isValidPassword($password)
	{
		return ($this->data['password'] == Website::encryptPassword($password, $this));
	}

	public function find($name){$this->loadByName($name);}
	public function findByEmail($email){$this->loadByEmail($email);}
	public function isPremium(){return ($this->getPremDays() > 0);}
	public function getLastLogin(){return $this->getLastDay();}
	public function getSecret(){return $this->data['secret'];}
	public function setSecret($value){$this->data['secret'] = $value;}

}


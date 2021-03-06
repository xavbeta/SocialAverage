<?php

namespace SocialAverage\Databases;

use SocialAverage\Config\Configuration;


class SocialAvgDB {

	private $conn = FALSE;
	private $dbHost;
	private $dbName;
	private $dbUser;
	private $dbPass;

	function __construct(Configuration $configuration) {
		$this -> dbHost = $configuration->getDbHost();
		$this -> dbName = $configuration->getDbName();
		$this -> dbUser = $configuration->getDbUsername();
		$this -> dbPass = $configuration->getDbPassword();
	}

	public function Open() {

		$this->conn = pg_connect("host=" . $this->dbHost . " dbname=" . $this->dbName . " user=" . $this->dbUser . " password=" . $this->dbPass)
			or die("connection failed");

	}

	public function IsActive() {
		return $this -> conn != FALSE;
	}

	public function Close() {
		if($this->IsActive())
			pg_close($this -> conn);
			//pg_close();
	}

    public function GenerateToken($user_id) {

        $result = pg_query_params($this -> conn, "INSERT INTO token (init_node_id) VALUES ($1) RETURNING token_id;", array($user_id));

        if (!$result)
            throw new \Exception("Error creating new token: " . pg_last_error($this -> conn));

        $row = pg_fetch_array($result);

        return $row['token_id'];
    }

	public function GetToken($token_id) {

		$result = pg_query_params($this -> conn, "SELECT * FROM token WHERE token_id = $1;", array($token_id));

		if (!$result)
			throw new \Exception("Error fetching token information: " . pg_last_error($this -> conn));

		$row = pg_fetch_array($result);

		$t = new \stdClass;
		$t -> token_id = $row['token_id'];
		$t -> init_node_id = $row['init_node_id'];
		$t -> end_node_id = $row['end_node_id'];
		$t -> created = $row['created'];
		$t -> ended = $row['ended'];
		$t -> init_node_value = $row['init_node_value'];
		$t -> end_node_value = $row['end_node_value'];
		$t -> final_init_node_value = $row['final_init_node_value'];
		$t -> final_end_node_value = $row['final_end_node_value'];

		return $t;
	}

	public function LastOpenToken($user_id) {

		$result = pg_query_params($this -> conn, "SELECT * FROM token WHERE init_node_id = $1 AND end_node_id IS NULL;", array($user_id));

		if (!$result)
			throw new \Exception("Error checking last open token: " . pg_last_error($this -> conn));

		if(pg_num_rows($result) == 0){
			return false;
		}

		return pg_fetch_object ($result);

	}

	public function IsTokenValid($token_id){
		$token = $this->GetToken($token_id);

		return $token->ended == null;

	}

	public function CommitToken($token_id, $end_user_id){
		$result = pg_query_params($this -> conn, "SELECT ci_commit_transaction($1, $2) AS final_value;", array($token_id, $end_user_id));

		if (!$result)
			throw new \Exception("Error committing token '$token_id': " . pg_last_error($this -> conn));

		$row = pg_fetch_array($result);

		return $row['final_value'];
	}



	public function GetNodeHistory($node_id)
	{

		$result = pg_query_params($this -> conn, "select token_id, end_node_id as other_node, ended, 'i' as action, init_node_value as init_value, final_init_node_value as final_value, end_node_value as other_value, final_end_node_value as other_final_value FROM token WHERE end_node_id IS NOT NULL AND init_node_id = $1
UNION select token_id, init_node_id as other_node, ended, 'e' as action, end_node_value as init_value, final_end_node_value as final_value, init_node_value as other_value, final_init_node_value as other_final_value FROM token WHERE end_node_id = $1 ORDER BY ended DESC;", array($node_id));

		if (!$result)
			throw new \Exception("Error checking node history: " . pg_last_error($this -> conn));

		$transactions = array();

		$row = pg_fetch_array($result);
		while ($row) {
			$t = new \stdClass;
			$t -> token_id = $row['token_id'];
			$t -> node_id = $node_id;
			$t -> other_node_id = $row['other_node'];
			$t -> ended = date_parse($row['ended']);
			$t -> is_initiator = strcmp($row['action'], "i") == 0 ? true : false;
			$t -> init_value = $row['init_value'];
			$t -> final_value = $row['final_value'];
			$t -> other_node_value = $row['other_value'];
			$t -> other_node_final_value = $row['other_final_value'];
			array_push($transactions, $t);

			// fetch next row
			$row = pg_fetch_array($result);
		}

		return $transactions;

	}

	public function GenerateNode($newValue)
	{
		$result = pg_query_params($this -> conn, "INSERT INTO node (value) VALUES ($1) RETURNING node_id;", array($newValue));

		if (!$result)
			throw new \Exception("Error generating new node with value '$newValue': " . pg_last_error($this -> conn));

		$row = pg_fetch_array($result);

		return $row['node_id'];
	}

	public function AddAccount($node_id, $social_id, $identifier, $displayName, $photoUrl, $meta)
	{
		$result = pg_query_params($this -> conn, "INSERT into account (node_id,  social, identifier, display_name, photo_url, meta) VALUES ($1, $2, $3, $4, $5, $6) RETURNING account_id;", array($node_id, $social_id, $identifier, $displayName, $photoUrl, $meta));

		if (!$result)
			throw new \Exception("Error creating new account associated belonging to node '$node_id': " . pg_last_error($this -> conn));

		$row = pg_fetch_array($result);

		return $row['account_id'];
	}

	public function MergeNodes($first_node_id, $second_node_id){

	}

	public function  GetNode($node_id) {
		$result = pg_query_params($this -> conn, "SELECT * FROM node LEFT JOIN account ON node.node_id = account.node_id WHERE node.node_id = $1;", array($node_id));

		if(pg_num_rows($result) > 0) {

			$row = pg_fetch_array($result);

			$node = new \stdClass();
			$node -> node_id = $node_id;
			$node -> value = $row['value'];
			$node -> last_change = $row['last_change'];
			$node -> accounts = array();

			do {
				if($row['account_id'] != null){
					$account = new \stdClass();
					$account -> account_id = $row['account_id'];
					$account -> social_id = $row['social'];
					$account -> identifier = $row['identifier'];
					$account -> photo_url = $row['photo_url'];
					$account -> display_name = $row['display_name'];
					$account -> meta = $row['meta'];
					array_push($node->accounts, $account);
				}

				// fetch next row
				$row = pg_fetch_array($result);
			} while($row && $row['account_id']);

			return $node;
		} else {
			return false;
		}
	}

	public function FindNodeByAccount($identifier)
	{
		$result = pg_query_params($this -> conn, "select node_id from account where identifier = $1 LIMIT 1;", array($identifier));

		if (!$result)
			throw new \Exception("Error finding a node associated with account identifier: '$identifier'': " . pg_last_error($this -> conn));

		if(pg_num_rows($result) > 0) {
			$row = pg_fetch_array($result);

			return $row['node_id'];
		} else {
			return false;
		}
	}

}

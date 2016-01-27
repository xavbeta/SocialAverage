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

	public function open() {

		$this->conn = pg_connect("host=" . $this->dbHost . " dbname=" . $this->dbName . " user=" . $this->dbUser . " password=" . $this->dbPass)
			or die("connection failed");

	}

	public function ia_active() {
		return $this -> conn == FALSE;
	}

	public function close() {
		pg_close($this -> conn);
	}

    public function GenerateToken($user_id) {

        $result = pg_query($this -> conn, "INSERT INTO token (user_id) VALUES ($user_id) RETURNING token_id;");

        if (!$result)
            throw new \Exception("Error creating new token: " . pg_last_error($this -> conn));

        $row = pg_fetch_array($result);

        return $row['token_id'];
    }

	public function LastOpenToken($user_id) {

		$result = pg_query($this -> conn, "SELECT COUNT(*) FROM token WHERE init_node_id = $user_id AND end_node_id IS NULL;;");

		if (!$result)
			throw new \Exception("Error checking last open token: " . pg_last_error($this -> conn));

		if(pg_num_rows($result) == 0){
			return false;
		}

		return pg_fetch_array($result);

	}

	public function CommitToken($token_id, $end_user_id){
		$result = pg_query($this -> conn, "SELECT ci_commit_transaction('$token_id', $end_user_id) AS final_value;");

		if (!$result)
			throw new \Exception("Error committing token '$token_id': " . pg_last_error($this -> conn));

		$row = pg_fetch_array($result);

		return $row['final_value'];
	}



	public function GetNodeHistory($node_id)
	{
		$result = pg_query($this -> conn, "select token_id, end_node_id as other_node, ended, 'i' as action, init_node_value as init_value, final_init_node_value as final_value, end_node_value as other_value, final_end_node_value as other_final_value FROM token WHERE end_node_id IS NOT NULL AND init_node_id = $node_id
UNION select token_id, init_node_id as other_node, ended, 'e' as action, end_node_value as init_value, final_end_node_value as final_value, init_node_value as other_value, final_init_node_value as other_final_value FROM token WHERE end_node_id = $node_id ORDER BY ended DESC;");

		if (!$result)
			throw new \Exception("Error checking node history: " . pg_last_error($this -> conn));

		$transactions = array();

		$row = pg_fetch_array($result);
		while ($row) {
			$t = new \stdClass;
			$t -> token_id = $row['token_id'];
			$t -> node_id = $node_id;
			$t -> other_node_id = $row['other_node'];
			$t -> ended = $row['ended'];
			$t -> is_initiatiator = strcmp($row['action'], "i") == 0 ? true : false;
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
		$result = pg_query($this -> conn, "INSERT INTO node (value) VALUES ($newValue) RETURNING node_id;");

		if (!$result)
			throw new \Exception("Error generating new node with value '$newValue': " . pg_last_error($this -> conn));

		$row = pg_fetch_array($result);

		return $row['node_id'];
	}

	public function AddAccount($node_id, $social_id, $username, $meta)
	{
		$result = pg_query($this -> conn, "INSERT into account (node_id,  social, username, meta) VALUES ($node_id, $social_id, '$username', '$meta') RETURNING account_id;");

		if (!$result)
			throw new \Exception("Error creating new account associated belonging to node '$node_id': " . pg_last_error($this -> conn));

		$row = pg_fetch_array($result);

		return $row['account_id'];
	}

	public function  GetNode($node_id) {
		$result = pg_query($this -> conn, "select * from node LEFT JOIN account on node.node_id = account.node_id where node.node_id = $node_id;");

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
					$account -> username = $row['username'];
					$account -> meta = $row['meta'];
					array_push($node->accounts, $account);
				}

				// fetch next row
				$row = pg_fetch_array($result);
			} while($row && $row['account_id']);

			return $node;
		} else {
			return FALSE;
		}
	}

}

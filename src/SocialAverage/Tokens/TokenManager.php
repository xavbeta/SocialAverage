<?php
/**
 * Created by PhpStorm.
 * User: SAVERI0
 * Date: 13/01/2016
 * Time: 11:27
 */

namespace SocialAverage\Tokens;

use SocialAverage\Config\Configuration;
use SocialAverage\DataBases\SocialAvgDB;
use SocialAverage\SlimExtensions\Errors\InvalidTokenException;
use SocialAverage\SlimExtensions\Errors\SpoiledTokenException;

class TokenManager
{
    private $db;

    function __construct(SocialAvgDB $db = null) {

        if($db == null){
            $this->db = new SocialAvgDB(Configuration::getDefaultConfiguration());
            $this->db->Open();
        } else {
            $this->db = $db;
        }
    }

    function __destruct() {
        $this->db->Close();
    }

    public function GetNewToken($user_id) {
        return $this->db->GenerateToken($user_id);
    }

    public function GetToken($token_id) {
        return $this->db->GetToken($token_id);
    }

    public function HasOpenToken($user_id) {
        return $this->db->LastOpenToken($user_id) === false ? false : true;

    }

    public function IsTokenValid($token_id) {
        return $this->db->IsTokenValid($token_id);

    }

    private function IsTokenRedeemableByNode($token_id, $end_user_id)
    {
        $token = $this->GetToken($token_id);
        return $token->init_node_id != $end_user_id;
    }

    public function CommitToken($token_id, $end_user_id) {
        if(!$this->IsTokenValid($token_id)){
            throw new SpoiledTokenException();
            exit(0);
        }

        if(!$this->IsTokenRedeemableByNode($token_id, $end_user_id)){
            throw new InvalidTokenException();
        }


        return $this->db->CommitToken($token_id, $end_user_id);
    }

    public function CommitUserToken($init_token_id, $end_user_id) {
        if($init_token_id == $end_user_id){
            throw new \InvalidArgumentException("Trying to commit a token between the same token.");
        }

        $token = $this->db->LastOpenToken($init_token_id);

        if($token === false){
            throw new \BadFunctionCallException("$init_token_id has no open token.");
        }

        return $this->CommitToken($token['token_id'], $end_user_id);
    }

    public function GetNodeHistory($node_id){
        return $this->db->GetNodeHistory($node_id);
    }


}
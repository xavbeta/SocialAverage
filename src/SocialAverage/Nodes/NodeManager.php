<?php
/**
 * Created by PhpStorm.
 * User: SAVERI0
 * Date: 27/01/2016
 * Time: 16:55
 */

namespace SocialAverage\Nodes;


use SocialAverage\Config\Configuration;
use SocialAverage\Databases\SocialAvgDB;
use SocialAverage\SlimExtensions\Errors\BadRequestException;
use SocialAverage\Socials\SocialNetwork;
use SocialAverage\Values\IValueGenerator;

class NodeManager
{
    private $db;

    private static $instance;

    private function __construct(SocialAvgDB $db = null) {

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

    public static function GetInstance(){
        if(self::$instance == null){
            self::$instance = new NodeManager();
        }
        return self::$instance;
    }

    public function AddNode(IValueGenerator $valueGenerator){

        $newValue = $valueGenerator->Generate();

        echo "New value generated: ".$newValue."\n";

        return $this->db->GenerateNode($newValue);
    }

    public function AddAccount($node_id, $social, $identifier , $displayName, $photoUrl, $meta = ''){
        //return $this->db->AddAccount($node_id, SocialNetwork::NameToValue($social), $identifier , $displayName, $photoUrl, $meta);
        return $this->db->AddAccount($node_id, $social, $identifier , $displayName, $photoUrl, $meta);
    }

    public function AddNodeAccount($newValue, $social,  $identifier , $displayName, $photoUrl, $meta = '')
    {
        $node_id = $this->db->GenerateNode($newValue);

        return $this->db->AddAccount($node_id, $social,  $identifier , $displayName, $photoUrl, $meta);
    }

    public function FindNodeByAccount($identifier, $throwException = true) {
        $node_id = $this->db->FindNodeByAccount($identifier);

        if($node_id){
            return $this->GetNode($node_id);
        }

        if($throwException){
            throw new BadRequestException();
        } else {
            return false;
        }

    }

    public function GetNode($node_id){
        return $this->db->GetNode($node_id);
    }

    public function GetNodeHistory($nodeId)
    {
        return $this->db->GetNodeHistory($nodeId);
    }

    public function GetTransactionsCount($nodeId)
    {
        $history = $this->GetNodeHistory($nodeId);

        if($history){
            return count($history);
        }

        return 0;
    }

    public function HasOpenTransaction($nodeId)
    {
        return $this->db->LastOpenToken($nodeId);
    }

    public function GetSocialAccountList($nodeId) {
        $node = $this->GetNode($nodeId);

        $socialAccountList = array();
        foreach ($node->accounts as $account){
            array_push($socialAccountList, $account->social_id);
        }

        return $socialAccountList;
    }

}


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

    public function AddNode(IValueGenerator $valueGenerator){

        $newValue = $valueGenerator->Generate();

        echo "New value generated: ".$newValue."\n";

        return $this->db->GenerateNode($newValue);
    }

    public function AddAccount($node_id, $social, $identifier , $photoUrl, $displayName, $meta = ''){
        return $this->db->AddAccount($node_id, SocialNetwork::NameToValue($social), $identifier , $photoUrl, $displayName, $meta);
    }

    public function AddNodeAccount($newValue, SocialNetwork $social,  $identifier , $photoUrl, $displayName, $meta = '')
    {
        $node_id = $this->db->GenerateNode($newValue);

        return $this->db->AddAccount($node_id, $social,  $identifier , $photoUrl, $displayName, $meta);
    }

    public function FindNodeByAccount($identifier) {
        $node_id = $this->db->FindNodeByAccount($identifier);

        if($node_id){
            return $this->GetNode($node_id);
        }

        throw new BadRequestException();
    }

    public function GetNode($node_id){
        return $this->db->GetNode($node_id);
    }

    public function GetNodeHistory($nodeId)
    {
        return $this->db->GetNodeHistory($nodeId);
    }

    public function HasOpenTransaction($nodeId)
    {
        return $this->db->LastOpenToken($nodeId) !== false;
    }

}


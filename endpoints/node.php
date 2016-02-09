<?php
/**
 * Created by PhpStorm.
 * User: SAVERI0
 * Date: 09/02/2016
 * Time: 16:36
 */


use SocialAverage\Inputs\DataExtractor;
use SocialAverage\Inputs\InputChecker;
use SocialAverage\Nodes\NodeManager;
use SocialAverage\SlimExtensions\Errors\BadRequestException;
use SocialAverage\Socials\SocialNetwork;

$app->get('/node/:nodeId', function ($nodeId) use ($app) {

    if(InputChecker::CheckNodeId($nodeId)) {
        $nm = new NodeManager();
        echo json_encode($nm->GetNode($nodeId));
    } else {
        throw new BadRequestException();
    }
});

$app->post('/node/history', function () use ($app) {
    $nodeId = DataExtractor::ExtractNodeId($app->request->getBody());

    if(InputChecker::CheckNodeId($nodeId)) {
        $nm = new NodeManager();
        echo json_encode($nm->GetNodeHistory($nodeId));
    } else {
        throw new BadRequestException();
    }
});

$app->post('/node/addaccount', function () use ($app) {
    $body = $app->request->getBody();
    $nodeId = DataExtractor::ExtractNodeId($body);
    $social = DataExtractor::ExtractSocial($body);
    $username = DataExtractor::ExtractAccountUsername($body);
    $meta = DataExtractor::ExtractAccountMeta($body);

    if(InputChecker::CheckNodeId($nodeId, true)
        && InputChecker::CheckSocial($social)
        && InputChecker::CheckAccountUsername($username)
        && InputChecker::CheckAccountMeta($meta)) {

        $socialNetwork = SocialNetwork::ValueToName($social);

        $nm = new NodeManager();
        echo json_encode($nm->AddAccount($nodeId, $socialNetwork,$username, $meta));
    } else {
        throw new BadRequestException();
    }

});


$app->post('/node/iswaiting', function () use ($app) {
    $nodeId = DataExtractor::ExtractNodeId($app->request->getBody());

    if(InputChecker::CheckNodeId($nodeId)) {
        $nm = new NodeManager();
        echo json_encode($nm->HasOpenTransaction($nodeId));
    } else {
        throw new BadRequestException();
    }
});
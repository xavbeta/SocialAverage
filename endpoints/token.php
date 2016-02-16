<?php
/**
 * Created by PhpStorm.
 * User: SAVERI0
 * Date: 09/02/2016
 * Time: 16:33
 */


use SocialAverage\Inputs\DataExtractor;
use SocialAverage\Inputs\DataJSONExtractor;
use SocialAverage\Inputs\InputChecker;
use SocialAverage\SlimExtensions\Errors\BadRequestException;
use SocialAverage\Tokens\TokenManager;

$app->get('/token/:token_id', function ($token_id) use ($app) {

    $tm = new TokenManager();

    if(InputChecker::CheckTokenId($token_id)){
        echo json_encode($tm->GetToken($token_id));
    } else {
        throw new BadRequestException();
    }

})->name("token")->conditions(array('token_id' => '[\w-]+'));

$app->post('/token/generate', function () use ($app) {
    $userId = DataJSONExtractor::ExtractNodeId($app->request->getBody());

    if(InputChecker::CheckNodeId($userId)) {
        $tm = new TokenManager();
        echo $tm->GetNewToken($userId);
    } else {
        throw new BadRequestException();
    }
});

$app->post('/token/commit', function () use ($app) {

    $body = $app->request->getBody();
    $nodeId = DataJSONExtractor::ExtractNodeId($body);
    $tokenId = DataJSONExtractor::ExtractTokenId($body);

    if(InputChecker::CheckNodeId($nodeId)
        && InputChecker::CheckTokenId($tokenId)) {
        $tm = new TokenManager();
        echo  $tm->CommitToken($tokenId, $nodeId);
    } else {
        throw new BadRequestException();
    }

});

<?php
/**
 * Created by PhpStorm.
 * User: SAVERI0
 * Date: 29/01/2016
 * Time: 15:19
 */

namespace SocialAverage\SlimExtensions;

use Slim\Slim;
use SocialAverage\SlimExtensions\Errors\GenericException;
use SocialAverage\SlimExtensions\Errors\HttpException;
use SocialAverage\SlimExtensions\Errors\IllegalRequestException;
use SocialAverage\SlimExtensions\Errors\InvalidTokenException;
use SocialAverage\SlimExtensions\Errors\SpoiledTokenException;
use SocialAverage\SlimExtensions\Errors\UnauthenticatedRequestException;

class ErrorHandler
{
    public function Handle(\Exception $ex, $app)
    {
        if ($ex instanceof HttpException) {
            if($this->exceptionNeedsSpecialTreatment($ex)) {
                $this->manageSpecialException($ex, $app);
            } else {
                $this->printError($ex, $app);
            }
        } else {
            $this->printError(new GenericException(), $app);
        }
    }

    private function printError(HttpException $ex, $app)
    {
        // set the response content-type
        $app->response->headers->set('Content-Type', 'application/json');
        $app->response->setStatus($ex->getCode());

        $err = new \stdClass();
        $err->message = $ex->getMessage();
        $err->code = $ex->getCode();

        echo json_encode($err);
    }

    private function exceptionNeedsSpecialTreatment(HttpException $ex)
    {
        return $ex instanceof UnauthenticatedRequestException
        || $ex instanceof SpoiledTokenException
        || $ex instanceof InvalidTokenException
        || $ex instanceof IllegalRequestException;
    }

    private function manageSpecialException($ex, Slim $app)
    {
        if($ex instanceof UnauthenticatedRequestException){
            $e = (array)$ex;
            $app->redirect('/login'.($e['resourceUri']?"?url=".$e['resourceUri']:""));
        } else if ($ex instanceof SpoiledTokenException) {
            $app->redirect('/spoiled');
        } else if ($ex instanceof InvalidTokenException){
            $app->redirect('/invalid');
        } else if ($ex instanceof IllegalTokenException){
            $app->redirect('/illegalrequest');
        }

    }
}
<?php

use SocialAverage\Templates\SocialSharerTemplate;

$app->get('/share', function () {
    echo SocialSharerTemplate::getAllSharerTemplate("no text");
});
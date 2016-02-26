<?php
/**
 * Created by PhpStorm.
 * User: SAVERI0
 * Date: 10/02/2016
 * Time: 11:32
 */

use SocialAverage\Templates\SocialLoginTemplate;


echo "<h1>Add another account?</h1>";

SocialLoginTemplate::getInitTemplate($homeUrl);
?>
<p><a href="<?php echo $homeUrl; ?>">No thanks. I'm all set</a>.</p>
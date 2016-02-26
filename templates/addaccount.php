<?php
/**
 * Created by PhpStorm.
 * User: SAVERI0
 * Date: 10/02/2016
 * Time: 11:32
 */

use SocialAverage\Nodes\NodeManager;
use SocialAverage\Templates\SocialLoginTemplate;


$nm = NodeManager::GetInstance();
$accountList = $nm->GetSocialAccountList($nodeId);

echo "<h1>Add another account?</h1>";
SocialLoginTemplate::getInitTemplate($homeUrl, $accountList);

?>
<p><a href="<?php echo $homeUrl; ?>">No thanks. I'm all set</a>.</p>
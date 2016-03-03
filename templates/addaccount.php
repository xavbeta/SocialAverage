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
?>

<?php require 'base/header.php'; ?>
    <div class="header clearfix">
        <nav>
            <ul class="nav nav-pills pull-right">
                <li role="presentation"><a href="/">Home</a></li>
                <li role="presentation"><a href="/info">Info</a></li>
            </ul>
        </nav>
        <h3 class="text-muted">Collective Intelligence</h3>
    </div>

    <div class="jumbotron">
        <p>Add another account?</p>
        <div class="token-action">
            <?php SocialLoginTemplate::getInitTemplate($homeUrl, $accountList); ?>
        </div>
        <div class="token-action">
            <a class="btn btn-lg btn-primary" href="<?php echo $homeUrl; ?>">No thanks. I'm all set</a>
        </div>
    </div>
<?php require 'base/footer.php'; ?>
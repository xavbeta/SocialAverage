<?php
/**
 * Created by PhpStorm.
 * User: SAVERI0
 * Date: 10/02/2016
 * Time: 11:32
 */

use SocialAverage\Templates\SocialLoginTemplate;

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
        <p>Login:</p>
        <?php SocialLoginTemplate::getInitTemplate($redirect_url); ?>
    </div>
<?php require 'base/footer.php'; ?>
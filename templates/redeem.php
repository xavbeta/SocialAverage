<?php
/**
 * Created by PhpStorm.
 * User: SAVERI0
 * Date: 10/02/2016
 * Time: 11:32
 */
use SocialAverage\Nodes\NodeManager;

?>

<?php require 'base/header.php'; ?>

<?php $nm = NodeManager::GetInstance(); ?>
<?php
$node = $nm->GetNode($nodeId);
?>



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
    <p>Redeem a token:</p>
    <form name="module" action="<?php echo $commitUrl; ?>" method="post">
        <div id="redeem-box-token" class="token-action">
                <div class="form-group">
                    <label class="control-label col-sm-2" for="token">Token:</label>
                    <div class="col-sm-10">
                        <input type="text" value="<?php echo $token; ?>" class="form-control" id="token" placeholder="Enter token" name="token" />
                    </div>
                </div>
        </div>
        <div class="token-action">
                <div class="form-group">
                    <input class="btn btn-lg btn-primary" type="submit" value="redeem" />
                </div>
        </div>
    </form>
</div>


<?php require 'base/footer.php'; ?>

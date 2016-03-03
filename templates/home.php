<?php
/**
 * Created by PhpStorm.
 * User: SAVERI0
 * Date: 10/02/2016
 * Time: 11:32
 */
use SocialAverage\Nodes\NodeManager;
use SocialAverage\Socials\ProfileUrlBuilder\SocialProfileUrlFactory;
use SocialAverage\Socials\SocialNetwork;
use SocialAverage\Templates\SocialSharerTemplate;

?>

<?php require 'base/header.php'; ?>

<?php $nm = NodeManager::GetInstance(); ?>
<?php
$node = $nm->GetNode($nodeId);
?>



    <div class="header clearfix">
        <nav>
            <ul class="nav nav-pills pull-right">
                <li role="presentation" class="active"><a href="/">Home</a></li>
                <li role="presentation"><a href="/info">Info</a></li>
            </ul>
        </nav>
        <h3 class="text-muted">Collective Intelligence</h3>
    </div>

    <div class="jumbotron">
        <p>Linked accounts:</p>
        <!--<pre><?php print_r ($node->accounts); ?></pre>-->
        <ul class="social-logged" >
            <?php foreach($node->accounts as $account): ?>
                <a href="<?php echo SocialProfileUrlFactory::GetProfileUrl($account); ?>" target="_blank">
                    <li class="social-logged-item">
                        <div class="social-thumb"><img height="100px" src="<?php echo $account->photo_url; ?>" /></div>
                        <div class="social-icon">
                            <a class="btn btn-social-icon btn-lg btn-<?php echo strtolower(SocialNetwork::ValueToName($account->social_id)); ?>">
                                <span class="fa fa-<?php echo strtolower(SocialNetwork::ValueToName($account->social_id)); ?>"> </span>
                            </a>
                        </div>
                        <div class="social-details">
                            <?php echo $account->display_name; ?>
                        </div>
                    </li>
                </a>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="jumbotron">
        <p>Your current value is</p>
        <h1><?php echo $node->value ?></h1>
        <p class="lead">Last update was <?php echo $node->last_change ?></p>
        <p><a class="btn btn-lg btn-primary" onclick="$('#value-history').removeClass('hidden');return false;" href="#" role="button">Show history</a></p>
        <div id="value-history" class="hidden" >
            <h3>Value history</h3>

                <?php
                    $history = $nm->GetNodeHistory($nodeId);

                    if(!$history || count($history) < 1): ?>
                        <ul class="list-group" >
                            <li class="list-group-item">
                                You have no transaction completed yet!
                            </li>
                        </ul>
                <?php else: ?>
                        <!--<ul class="list-group" >
                        <?php foreach($history as $historyEntry): ?>
                            <li class="list-group-item">
                                with node: <?php $historyEntry->other_node_id; ?>
                                as <?php echo ($historyEntry->is_initiator ? "sender":"recevier"); ?>
                                value: <?php echo $historyEntry->init_value; ?> -> <?php echo $historyEntry->final_value; ?>
                                token: <?php echo $historyEntry -> token_id; ?>
                            </li>
                        <?php endforeach; ?>
                        </ul>-->
                        <script type="text/javascript" src="/assets/js/jquery.canvasjs.min.js"></script>
                        <script type="text/javascript">
                            $(function () {
                                //Better to construct options first and then pass it as a parameter
                                var options = {
                                    title: {
                                        text: "Value history"
                                    },
                                    animationEnabled: true,
                                    data: [
                                        {
                                            type: "spline", //change it to line, area, column, pie, etc
                                            dataPoints: [
                                                <?php foreach($history as $historyEntry): ?>
                                                { x: new Date(<?php echo $historyEntry->ended['year']; ?>, <?php echo $historyEntry->ended['month']; ?>, <?php echo $historyEntry->ended['day']; ?>, <?php echo $historyEntry->ended['hour']; ?>, <?php echo $historyEntry->ended['minute']; ?>), y: <?php echo $historyEntry->final_value; ?>, toolTipContent: "<?php echo ($historyEntry->is_initiator ? "sender":"recevier"); ?> <br/> other node <?php echo $historyEntry->other_node_id; ?> <br/> token: <?php echo $historyEntry->token_id; ?>" },
                                                <?php endforeach; ?>
                                            ]
                                        }
                                    ]
                                };

                                $("#value-chart").CanvasJSChart(options);

                            });
                        </script>
                        <div id="value-chart" style="height:450px; width: 100%;"></div>

                <?php endif; ?>

        </div>

    </div>

    <div class="jumbotron">
        <?php
        $ot = $nm->HasOpenTransaction($nodeId);

        if($ot != false): ?>
            <div id="current-token" class="token-action">
                <p>
                    Your current token is
                </p>
                <h1>
                    <?php echo $ot->token_id; ?>
                </h1>
            </div>
            <div id="share-token" class="token-action">
                <p class="lead">
                    Share it!
                </p>
                <p>
                    <?php echo SocialSharerTemplate::getAllSharerTemplate("Condividi", $redeemTokenUrl."/".$ot->token_id); ?>
                </p>
            </div>
        <?php else: ?>
            <div id="generate-token" class="token-action">
                <p>
                    <a class="btn btn-lg btn-primary" href="<?php echo $generateTokenUrl; ?>" role="button" >Generate new token</a>
                </p>
            </div>
        <?php endif;?>
            <div id="redeem-token" class="token-action">
                <p>
                    or redeem a token from another user
                </p>
                <p>
                    <a class="btn btn-lg btn-primary" href="<?php echo $redeemTokenUrl; ?>" role="button">Redeem token</a>
                </p>
            </div>
    </div>


<?php require 'base/footer.php'; ?>
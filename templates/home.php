<?php
/**
 * Created by PhpStorm.
 * User: SAVERI0
 * Date: 10/02/2016
 * Time: 11:32
 */
use SocialAverage\Templates\SocialSharerTemplate;

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <title>Collective Intelligence</title>
    <meta name="description" content="A collective intelligence serious game">
    <meta name="author" content="Saverio Delpriori">

</head>
<?php $nm = new \SocialAverage\Nodes\NodeManager(); ?>
<body>
<?php
$node = $nm->GetNode($nodeId);
?>

<div>
    <h2>Current value</h2>
    <strong><?php echo $node->value ?></strong> (last update <?php echo $node->last_change ?>)
</div>

<div>
    <h2>Value history</h2>
    <ul>
    <?php foreach($nm->GetNodeHistory($nodeId) as $historyEntry): ?>
        <li>
            with node: <?php $historyEntry->other_node_id; ?>
            as <?php echo ($historyEntry->is_initiator ? "sender":"recevier"); ?>
            value: <?php echo $historyEntry->init_value; ?> -> <?php echo $historyEntry->final_value; ?>
            token: <?php echo $historyEntry -> token_id; ?>
        </li>
    <?php endforeach; ?>
    </ul>
</div>

<div>
    <h2>Token</h2>
    <?php
        $ot = $nm->HasOpenTransaction($nodeId);

        if($ot != false): ?>
            <p>
                Current token is <strong><?php echo $ot->token_id; ?></strong>
            </p>
            <p>
                <?php SocialSharerTemplate::getAllSharerTemplate("Condividi", "/redeem/".$ot->token_id); ?>
            </p>
        <?php else: ?>
            <p>
                <a href="<?php echo $generateTokenUrl; ?>">Generate token</a>
            </p>
    <?php endif;?>
            <p>
                <a href="<?php echo $redeemTokenUrl; ?>">Redeem token</a>
            </p>
</div>

</body>
</html>

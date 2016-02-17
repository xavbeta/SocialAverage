<?php
/**
 * Created by PhpStorm.
 * User: SAVERI0
 * Date: 10/02/2016
 * Time: 11:32
 */
use SocialAverage\Nodes\NodeManager;

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <title>Collective Intelligence - Redeem Token</title>
    <meta name="description" content="A collective intelligence serious game">
    <meta name="author" content="Saverio Delpriori">

</head>
<?php $nm = new NodeManager(); ?>
<body>
<?php
$node = $nm->GetNode($nodeId);
?>

    <div>
        <h2>Redeem token</h2>
        <form name="modulo" action="<?php echo $commitUrl; ?>" method="post">
            <label>
                <input type="text" value="<?php echo $token; ?>" name="token"/>
            </label>
            <input type="submit" value="redeem" />
        </form>
    </div>


</body>
</html>


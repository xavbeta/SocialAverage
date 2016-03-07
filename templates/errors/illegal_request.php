<?php
/**
 * Created by PhpStorm.
 * User: SAVERI0
 * Date: 15/02/2016
 * Time: 17:10
 */
?>


<?php require __DIR__ .'/../base/header.php'; ?>

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
        <h1>Illegal request!</h1>
        <p>You are not allowed to do that. Sorry!</p>
        <p><a class="btn btn-lg btn-primary"  href="<?php echo $homeUrl; ?>">Go back home</a></p>
    </div>



<?php require __DIR__ .'/../base/footer.php'; ?>
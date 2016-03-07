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
        <h1>Spoiled token!</h1>
        <p>Provided token <?php echo $token? "($token) ":"" ?>is not valid anymore!
            <br/>It has been utilized by someone else.</p>

        <p>Well, not big deal! You can generate and share a brand new token on your own.
            <br/>You can do that easily from your home page.</p>
        <p><a class="btn btn-lg btn-primary"  href="<?php echo $homeUrl; ?>">Go back home</a></p>
    </div>


<?php require __DIR__ .'/../base/footer.php'; ?>
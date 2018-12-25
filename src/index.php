<?php
    /**
     * Created by PhpStorm.
     * User: godson
     * Date: 4/5/15
     * Time: 21:51
     */
?>
<!DOCTYPE html>

<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
</head>
<body style="padding-top: 40px;">
<div class="container">
    <form method="get" class="form-signin">
        <h2 class="form-signin-heading">Please enter url</h2>
        <input class="form-control" type="text" name="url"
               value="<?php echo isset( $_REQUEST['url'] ) ? $_REQUEST['url'] : ''; ?>"/>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Submit</button>
    </form>
</div>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="col-xs-6">
            <h1>Parsed</h1>
            <?php

                if (isset( $_REQUEST['url'] ) && ! empty( $_REQUEST['url'] )) {
                    require_once('Readabillity.php');

                    $r = new \readabillity\Readabillity( $_REQUEST['url'] );
                    echo $r->getContent();
                }
            ?>
        </div>
        <div class="col-xs-6">
            <h1>Source</h1>
            <?php if (isset( $_REQUEST['url'] )): ?>
                <iframe width="100%" height="500px" src="<?= $_REQUEST['url']; ?>"></iframe>
            <?php endif; ?>
        </div>
    </div>

</div>
<script>
    (
        function ( i, s, o, g, r, a, m )
        {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function ()
            {
                (
                    i[r].q = i[r].q || []
                ).push( arguments )
            }, i[r].l = 1 * new Date();
            a = s.createElement( o ),
                m = s.getElementsByTagName( o )[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore( a, m )
        }
    )( window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga' );

    ga( 'create', 'UA-62068241-1', 'auto' );
    ga( 'send', 'pageview' );

</script>
</body>
</html>

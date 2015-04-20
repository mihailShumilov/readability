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
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
</head>
<body style="padding-top: 40px;">
<div class="container">
    <form method="post" class="form-signin">
        <h2 class="form-signin-heading">Please enter url</h2>
        <input class="form-control" type="text" name="url"
               value="<?php echo isset( $_POST['url'] ) ? $_POST['url'] : ''; ?>"/>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Submit</button>
    </form>
</div>
<div class="container-fluid">
    <?php

        if (isset( $_POST['url'] ) && ! empty( $_POST['url'] )) {
            require_once( 'Readabillity.php' );
            require_once( 'PageLoader.php' );

            $r = new \readability\Readabillity( $_POST['url'] );
            echo $r->getContent();
        }
    ?>
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

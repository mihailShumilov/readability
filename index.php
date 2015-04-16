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
</body>
</html>

<?php
    /**
     * Created by PhpStorm.
     * User: godson
     * Date: 4/5/15
     * Time: 21:51
     */
    require_once( 'Readabillity.php' );
    require_once( 'PageLoader.php' );

    $url = "http://www.foxnews.com/politics/2015/04/05/corker-works-overtime-to-get-last-few-votes-to-ensure-congress-has-mandatory/#";
//    $url = "http://www.segodnya.ua/politics/pnews/plany-rady-na-nedelyu-otmena-zaloga-dlya-korrupcionerov-sozdanie-komissiy-i-mitingi-605536.html";
//    $url = "http://www.washingtontimes.com/news/2015/apr/2/f-35-comes-400k-helmet-pilot-can-see-through-plane/";

    $r = new \readability\Readabillity( $url );
    echo $r->getContent();
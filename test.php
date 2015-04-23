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
//    $url = "http://jurliga.ligazakon.ua/news/2015/4/7/126778.htm";
//    $url = "http://ain.ua/2015/04/15/575389";
//    $url = "http://www.phpbuilder.com/columns/DOM-XML-extension/Octavia_Anghel102710.php3";
//    $url = "http://habrahabr.ru/company/minirobot/blog/255321/";
//    $url = "http://www.femalefoundersconference.org/speakers/#grace";
//    $url = "http://edition.cnn.com/2015/04/15/opinions/welch-medical-test-mania/index.html";
//    $url = "http://www.newsru.ua/ukraine/22apr2015/shyrokino.html";

    $r = new \readability\Readabillity( $url );
    echo $r->getContent();
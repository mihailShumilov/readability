<?php
    /**
     * Created by PhpStorm.
     * User: godson
     * Date: 4/5/15
     * Time: 21:51
     */
    require_once( 'Readabillity.php' );

    $url = "http://www.foxnews.com/politics/2015/04/05/corker-works-overtime-to-get-last-few-votes-to-ensure-congress-has-mandatory/#";
    $url = "http://www.segodnya.ua/politics/pnews/plany-rady-na-nedelyu-otmena-zaloga-dlya-korrupcionerov-sozdanie-komissiy-i-mitingi-605536.html";
    $url = "http://www.washingtontimes.com/news/2015/apr/2/f-35-comes-400k-helmet-pilot-can-see-through-plane/";
    $url = "http://jurliga.ligazakon.ua/news/2015/4/7/126778.htm";
    $url = "http://ain.ua/2015/04/15/575389";
    $url = "http://www.phpbuilder.com/columns/DOM-XML-extension/Octavia_Anghel102710.php3";
    $url = "http://habrahabr.ru/company/minirobot/blog/255321/";
    $url = "http://www.femalefoundersconference.org/speakers/#grace";
    $url = "http://edition.cnn.com/2015/04/15/opinions/welch-medical-test-mania/index.html";
    $url = "http://www.newsru.ua/ukraine/22apr2015/shyrokino.html";
    $url = "http://news.liga.net/news/politics/5603060-mayora_natsgvardii_zapodozrili_v_posobnichestve_boevikam_gpu.htm";
    $url = "http://2700chess.com/";
    $url = "http://korrespondent.net/ukraine/politics/3521365-saakashvyly-poluchyl-ukraynskoe-hrazhdanstvo-naiem";
    $url = "http://www.unn.com.ua/uk/news/1469593-glava-mzs-nimechchini-30-travnya-vidvidaye-dnipropetrovsk-1";
//    $url = "http://www.057.ua/news/842147";
//    $url = "http://podrobnosti.ua/2037550-poroshenko-uvolil-glavu-missii-ukrainy-pri-nato.html";
//    $url = "http://www.rbc.ua/rus/news/saakashvili-stal-grazhdaninom-ukrainy-nardep-1432932548.html";
//    $url = "http://from-ua.com/news/348927-ssha-oficialno-isklyuchili-kubu-iz-spiska-sponsorov-terrorizma.html";
//    $url = "http://companion.ua/articles/content?id=297762";

    $r = new \readability\Readabillity( $url );
    echo $r->getContent();
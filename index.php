<?php
session_start();

require_once __DIR__.'/includes/Page.php';

require_once __DIR__.'/controller/PageController.php';

    $oPage=new Page();

      $oPage->setBody($body);

    echo $oPage->getHtml();


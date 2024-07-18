<?php

require_once('vendor/autoload.php');

use Krombox\Service\TextConverter;

function prepareSettings(){
    $settings = array();
    
    $settings['textToConvert'] = $_POST['textToConvert'];
    $settings['separatorInput'] = $_POST['separatorInput'];
    $settings['extract'] = $_POST['extract'];
    $settings['caseSensitive'] = $_POST['caseSensitive'];
    
    if(isset($_POST['options'])){
        $settings['options'] = $_POST['options'];        
    }
    
    if(isset($_POST['addBeforeRecord']) && !empty($_POST['addBeforeRecord'])) $settings['add']['before'] = $_POST['addBeforeRecord'];
    if(isset($_POST['addAfterRecord']) && !empty($_POST['addAfterRecord'])) $settings['add']['after'] = $_POST['addAfterRecord'];
    
    return $settings;
}

$settings = prepareSettings();

$c = new TextConverter($settings);
$c->convert();
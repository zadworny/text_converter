<?php

/* author ikrombox@gmail.com */

namespace Krombox\Service;

require "vendor/autoload.php";

use Goutte\Client;

class TextConverter {
            
    const files_base_path = 'x/files/';       
    
    private $settings;
    
    private $result;

    public function __construct($settings) { 
        $this->setSettings($settings);
        $this->createFilesDirectory();
    }                
    
    public function setSettings($settings){
        $this->settings = $settings;
    }
    
    public function getSettings(){
        return $this->settings;
    }
    
    public function getSettingsParam($key){
        if(!isset($this->settings[$key]))
            return false;
        
        return $this->settings[$key];                    
    }
    
    public function setResult($result){
        return $this->result = $result;
    }
    
    public function setResultItem($resultItem){
        return $this->result[] = $resultItem;
    }
    
    public function eraseResult(){
        return $this->result = array();
    }
    
    public function getResult(){
        return array_values($this->result);
    }

    protected function modifyAccountsData($accounts){        
        if($this->getSearchType() === 'emailToId'){
            $accounts = array_map(
                function($ac) {
                    return preg_replace('#\@.*#', '', $ac);
                },
                $accounts
            );
            $this->setSearchType('usernameToId');
        }
            
        return implode(',', $accounts);
    }
    
    protected function createFilesDirectory(){
        if (!file_exists(self::files_base_path))
            mkdir(self::files_base_path, 0777, true);
    }

    public function convert(){
        $fileName = "TC_".date('YmdHis');
        //step1
        $this->parseText();
                
        //step2
        $this->processOptions();
        //step3
        $this->extract();
        //step4
        $this->casesensitive();
        //step5
        $this->addToRecords();        
        
        $df = fopen(self::files_base_path . $fileName . '.csv', 'a+');        
        
        foreach ($this->getResult() as $item)
            fputcsv($df, (array)$item);                    
                            
        fclose($df);
               
        echo json_encode(array(
            'status' => 200,
            'textConverted' => $this->getResult(),
            'fileName' => $fileName
        ));
    }
    
    protected function parseText(){
        $delimiter = "\r\n";
        if($this->getSettingsParam('separatorInput') !== 'lb')
            $delimiter = $this->getSettingsParam('separatorInput');
        $text = $this->getSettingsParam('textToConvert');
        $options = $this->getSettingsParam('options');   
            
        return $this->setResult(explode($delimiter, $text));                
    }

    protected function extract()
    {
        if($this->getSettingsParam('extract') === 'no')
            return;
        
        $extractParam = $this->getSettingsParam('extract');        
        call_user_func(array($this, 'extract' . ucfirst($extractParam)));        
    }
    
    protected function extractUrls(){
        $result = $this->getResult();
        $pattern = "#\b((?:[a-z][\w-]+:(?:\/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:.,<>?«»“”‘’]))#";           
        $this->eraseResult();
        
        foreach ($result as $item) {
            if(preg_match($pattern, $item, $match)) $this->setResultItem($match[0]);                
        }                
    }
    
    protected function extractEmails(){
        $result = $this->getResult();
        $pattern = "/([a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z0-9._-]+)/";                
        $this->eraseResult();
        
        foreach ($result as $item) {
            if(preg_match($pattern, $item, $match)) $this->setResultItem($match[0]);                
        }                
    }
    
    protected function casesensitive(){
        if($this->getSettingsParam('caseSensitive') === 'no')
            return;
        
        $casesensitiveParam = $this->getSettingsParam('caseSensitive');
        $casesensitiveParam == 'lowercase' ?  $fn = 'strtolower' : $fn = 'strtoupper';
        $this->setResult(array_map($fn, $this->getResult()));
        
        return true;        
    }        
    
    protected function processOptions(){
        if(!$this->getSettingsParam('options'))
            return;
        
        $options = $this->getSettingsParam('options');       
        
        foreach ($this->getSettingsParam('options') as $key => $value){            
            call_user_func(array($this, $key), $value);
        }
        
    }
    
    protected function addToRecords(){
        $addOption = $this->getSettingsParam('add');
        
        if(!$addOption)
            return;
        
        if(isset($addOption['before']))
            $this->setResult(array_map(array($this, 'addBefore'), $this->getResult()));
        
        if(isset($addOption['after']))
            $this->setResult(array_map(array($this, 'addAfter'), $this->getResult()));
        
        return true;                
    }
    
    protected function addBefore($item){
        $addString = $this->getSettingsParam('add')['before'];
        return $addString . $item;
    }
    
    protected function addAfter($item){
        $addString = $this->getSettingsParam('add')['after'];
        return $item . $addString;
    }

    protected function removeDuplicates(){
        $options = $this->getSettingsParam('options');
        
        if($options['removeDuplicates'] == 'caseinsensitive')
            return $this->setResult(array_unique(array_map("StrToLower",  $this->getResult())));        
        else                        
            return $this->setResult(array_unique($this->getResult()));                
    }
    
    protected function sort(){
        $options = $this->getSettingsParam('options');
        $arrayToSort = $this->getResult();
        
        if($options['sort'] === 'reverse')
            rsort($arrayToSort);
        else
            sort($arrayToSort);
        
        $this->setResult($arrayToSort);
    }
    
    protected function removeEmptyLines(){       
        return $this->setResult(array_filter($this->getResult()));
    }
    
    protected function removeBlankSpaces(){
        return $this->setResult(array_map('trim', $this->getResult()));
    }            
}
<?php
$xpath = "../../../";
include($xpath.'_wp_logincheck.php');
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Text Converter</title>
        
        <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="assets/css/style.css">
        
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        
    </head>
    <body>                                
        <div class="wrapper container">                                
            <h1 class="text-center title">Text Converter</h1>                        
                        
            <div class="steps-holder">
                <div class="active  step1Label pull-left">STEP 1</div>
                <div class="step2Label pull-right">STEP 2</div>
            </div>
                        
            <div id="error-alert" class="alert alert-danger alert-dismissible  hide" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Something wrong. Check input data with option selected to convert.</strong>
            </div>
            
            
            <div class="form-holder step1">                
                <form id="text-converter-form-1" class="converter-form" name="textConverterForm">
                    <textarea id="textToConvert" rows="6" cols="40" name="textToConvert" placeholder="Text to convert"></textarea>                    
                    <button type="submit" id="convert" class="btn btn-info">CONVERT</button>

                    <div class="options-wrapper">
                        <div class="option-holder panel">
                            <div class="option">
                                <label> separator/delimiter of passed text </label>
                                <input type="text" id="separatorInput" name="separatorInput" placeholder="lb">
                                <label> * line break = lb</label>
                            </div>
                        </div>

                        <div class="option-holder panel">
   
                                <label class=" col-lg-4">
                                    <input type="radio" name="extract" value="emails">
                                    extract emails
                                </label>
    
                                <label class=" col-lg-4">
                                    <input type="radio" name="extract" value="urls">
                                     extract urls
                                </label>
    
                                <label class=" col-lg-4">
                                    <input type="radio" name="extract" value="no"  checked>
                                    no extract 
                                </label>
    
                        </div>
                            
                        <div class="option-holder panel">                        
                                <label class="col-lg-4">
                                    <input type="radio" name="caseSensitive" value="lowercase">
                                    make all lowercase
                                </label>                        

                                <label class=" col-lg-4">
                                    <input type="radio" name="caseSensitive" value="uppercase">
                                    make all uppercase
                                </label>                        

                                <label class=" col-lg-4">
                                    <input type="radio" name="caseSensitive" value="no" checked>
                                    no changes
                                </label>                        
                        </div>                                            
                            
                        <div class="option-holder panel">
                            <div class="checkbox option">
                                <label>
                                  <input type="checkbox" name="options[removeBlankSpaces]" value="true"> remove blank space
                                </label>
                            </div>
                            
                            <div class="checkbox option">
                                <label>
                                  <input type="checkbox" name="options[removeEmptyLines]" value="true"> remove empty lines
                                </label>
                            </div>
                                                                                    
                            <div class="checkbox option">
                                <label>
                                  <input type="checkbox" name="options[removeDuplicates]" value="caseinsensitive"> remove duplicates
                                </label>                                  
                            </div>

                            <div class="checkbox sub-level option">
                                <label>
                                    <input type="checkbox" name="options[removeDuplicates]" value="casesensitive"> case sensitive
                                </label>
                            </div>                                                      

                            <div class="checkbox option">
                                <label>
                                  <input type="checkbox" name="options[sort]" value="alphabetically"> sort alphabetically
                                </label>
                            </div>

                            <div class="checkbox sub-level option">
                                <label>
                                  <input type="checkbox" name="options[sort]" value="reverse"> reverse sorting
                                </label>
                            </div>
                        </div>    

                        <div class="option-holder panel">    
                            <div class="option">
                                <label>add </label>
                                <input type="text" id="addBeforeRecord" name="addBeforeRecord" >
                                <label for="addBeforeRecord">before each record</label>
                            </div>

                            <div class="option">
                                <label>add </label>                            
                                <input type="text" id="addAfterRecord" name="addAfterRecord" >
                                <label>after each record</label>
                            </div>
                        </div>    
                            
                        <div class="option-holder panel">                            
                                <label class="col-lg-4">
                                    <input type="radio" name="separatorOutput" value="lb">
                                    line break
                                </label>                                                        

                                <label class="col-lg-4">
                                    <input type="radio" name="separatorOutput" value=",">
                                     comma
                                </label>

                                <label class="col-lg-4">
                                    <input type="radio" name="separatorOutput" value=";"  >
                                    semicolon
                                </label>                            

                                <label class="col-lg-4">
                                    <input type="radio" name="separatorOutput" value=" ">
                                    space
                                </label>                            

                                <label class="col-lg-4">
                                    <input type="radio" name="separatorOutput" value="no" checked>
                                    no changes
                                </label>                   
                        </div>
                    </div>
                    
                </form>
            </div>
            
            <div class="form-holder step2 hide">                  
                
                <form id="twitter-converter-form-2" class="converter-form">
                    <textarea id="accounts-output" rows="6" cols="40" placeholder="Text input"></textarea>
                    <a id="download" class="btn btn-success disabled" href="../result.csv">DOWNLOAD</a>                    
                    <button id="newsearch" class="btn btn-info">NEW SEARCH</button>
                </form>
            </div>
        </div>                    
        
        <script src="../bower_components/jquery/dist/jquery.min.js"></script>
        <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>        
        <script src="../bower_components/jshashes/hashes.min.js"></script>        
        <script src="assets/js/textConverter.js"></script>        
        <script>
            $(document).ready(function(){
                textConverter.init();
            })                            
        </script>        
    </body>
</html>

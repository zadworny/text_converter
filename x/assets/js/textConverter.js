var textConverter = {
    progress: 0,
    maxAccounts: 500,
    requestsCount:1,
    responseCount:0,
    inputAccountCount:0,
    outputAccountCount:0,
    progressPart: 100,
    filehash: '',
    separatorInput: "",
    separatorOutput: "",
    accessToken : '',
    response: '',
    
    appSettings: {},
    errorAlertObject: $('#error-alert'),
    
    init: function(fileName){
        //this.getAppSettings();
        this.bindConvertForm();
    },        
    
    bindConvertForm : function(){
        $('#convert').bind('click', function(e){
            e.preventDefault();
            
            var textValue = $('#textToConvert').val();
            //textValue.replace('\r', '');
            var separator = $('#separatorInput').val();
            
            if(textValue == '' || separator == '') return false;                                                           
            textConverter.separatorInput = separator;
            
            console.log('textvalue', textValue);
            textConverter.separatorOutput = textConverter.separatorInput;
            if(textConverter.separatorInput == 'lb')
                textConverter.separatorOutput = "\n";
            if($('input[name=separatorOutput]:checked').val() !== 'no'){
                textConverter.separatorOutput = $('input[name=separatorOutput]:checked').val();
                if($('input[name=separatorOutput]:checked').val() === 'lb')
                    textConverter.separatorOutput = "\n"
                //textConverter.separatorOutput = $('input[name=separatorOutput]:checked').val();
            }
            
            textConverter.ajaxRequest('post', '../Linker.php', $('#text-converter-form-1').serialize(), textConverter.processProgress);
            //textConverter.filehash = new Hashes.MD5().hex($.now() + 'facebookconverter');
            //var accountsElements = accountsValue.split('\n');
            //var accountsElementsLength = accountsElements.length;
            
            textConverter.changeStep();
//            if(textConverter.searchType === 'usernameToEmail'){ 
//                textConverter.usernameToEmail(accountsElements);
//                return false;
//            }
            
//            var accounts = textConverter.chunkArray(accountsElements, textConverter.maxAccounts);            
//            textConverter.inputAccountCount = accountsElementsLength;
//            
//            if(accountsElementsLength > textConverter.maxAccounts){
//                textConverter.requestsCount = Math.ceil(accountsElementsLength / textConverter.maxAccounts);                
//                textConverter.progressPart = (100 / textConverter.requestsCount);                
//            }                        
//                        
//            var params = {};
//            params.fileHash = textConverter.filehash;
//            params.searchType = textConverter.searchType;
//            //params.access_token = textConverter.accessToken;                                    
//            
//            for(var i = 0; i < textConverter.requestsCount; i++){
//                console.log(accounts[i].length);
//                params.accounts = accounts[i];                
//                textConverter.ajaxRequest('post', '../Linker.php', params, textConverter.processProgress);                
//                //textConverter.apiCall('/fql.query', params, textConverter.processProgress);
//            }            
        });
    },       
    
    changeStep: function(){
        $('.steps-holder div').removeClass('active');
        $('.step2Label').addClass('active');
        $('.step1').addClass('hide');
        $('.step2').removeClass('hide');
    },
    
    chunkArray : function(arr,size){
        var arrays = [];        
        while (arr.length > 0)
            arrays.push(arr.splice(0, size));

        console.log(arrays);
        return arrays;
    },
                            
    processProgress: function(data){
        console.log(data, 'new separator', textConverter.separatorOutput);        
        
        var textConverted = data.textConverted.join(textConverter.separatorOutput);
        console.log(textConverted);
        
        $('#accounts-output').append(textConverted);
        console.log(data.fileName);
        $('#download').prop('href', 'files/' + data.fileName + '.csv');
        $('#download').removeClass('disabled');
        //if(data.status !== 200){
            //if(data.code == 34) textConverter.setProgress(true);
            
            //textConverter.errorAlertObject.find('strong').html(data.message);            
            //$('#error-alert').removeClass('hide');
            //return false;
        //}
        
//        textConverter.responseCount++;
//        console.log(textConverter.progressPart);
//        textConverter.progress = textConverter.progress + textConverter.progressPart;
//        $.each( data.accountsConverted, function( key, value ) {
//            $('#accounts-output').append(value);
//            $('#accounts-output').append('\n');
//            textConverter.outputAccountCount++;
//        });
//        
//        if(textConverter.requestsCount == textConverter.responseCount){
//            console.log('Request and response count the same');
//            console.log(textConverter.inputAccountCount, textConverter.outputAccountCount);
//            if(textConverter.inputAccountCount != textConverter.outputAccountCount){
//                
//                var diffAccountCount = textConverter.inputAccountCount - textConverter.outputAccountCount;                
//                textConverter.errorAlertObject.find('strong').html(diffAccountCount + ' accounts could not be converted or doesnt exist');
//                textConverter.errorAlertObject.removeClass('hide');                
//            }               
//            
//            textConverter.setProgress(true);
//        }
//        
//        textConverter.setProgress(false);
        
                        
    },
    
//    usernameToId: function(){
//        $.each(textConverter.response, function(key, value) {
//            $('#accounts-output').append(value.id);
//            $('#accounts-output').append('\n');
//            textConverter.outputAccountCount++;
//        });
//    },
//    
//    usernameToEmail: function(accounts){
//        //console.log(accounts, 'acc');false;
//        $.each(accounts, function(key, value) {
//            $('#accounts-output').append(value + '@facebook.com');
//            $('#accounts-output').append('\n');
//            textConverter.outputAccountCount++;
//        });
//    },
//    
//    idToEmail: function(){
//        console.log(textConverter.response);    
//        $.each(textConverter.response, function(key, value) {
//            $('#accounts-output').append(value.username + '@facebook.com');
//            $('#accounts-output').append('\n');
//            textConverter.outputAccountCount++;
//        });
//    },
    
    setProgress: function(isError){
        if(isError){
            $('#download').removeClass('disabled');
            textConverter.progress = 100;
        }
            
        var progressBar = $('.progress-bar');
        progressBar.css('width', textConverter.progress + '%');
        progressBar.html(Math.floor(textConverter.progress) + '%');
        $('#download').prop('href', 'files/' + textConverter.filehash + '.csv');
    },
                
    ajaxRequest: function(method, url, data, callback){
        $.ajax({
            method: method,
            data: data,
            url: url,
            dataType : "json",
            success: callback            
        });
    }                
};
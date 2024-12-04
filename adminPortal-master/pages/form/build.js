$(document).ready(function () {
        
    edit_form_file();

});

function edit_form_file() {
    $("#builder-editor").html("");
    $("#form_content_id").val(formToken);
    var form_content = get_form_content(formToken);

    if(form_content != undefined && form_content != null && form_content != "new" && form_content != ""){
        var form_content_obj = JSON.parse(form_content);
        if(form_content_obj.length > 0){
            form_content_obj.forEach(function(item, index){
                if(item.label !== undefined){ //item.type == "paragraph" || item.type == "header" //
                    item.label = item.label.replace(/&quot;/g,"\"");
                    item.label = item.label.replace(/&apos;/g, "'"); 
                }
                if(item.description !== undefined){ 
                    item.description = item.description.replace(/&quot;/g,"\""); 
                    item.description = item.description.replace(/&apos;/g, "'"); 
                }
                if(item.type != "table" && item.placeholder !== undefined){ 
                    item.placeholder = item.placeholder.replace(/&quot;/g,"\""); 
                    item.placeholder = item.placeholder.replace(/&apos;/g, "'"); 
                }
                if(item.type != "hidden" && item.value !== undefined){
                    item.value = item.value.replace(/&quot;/g,"\"");
                    item.value = item.value.replace(/&apos;/g, "'"); 
                }

                if(item.type == "select" || item.type == "checkbox-group" || item.type == "radio-group"){
                    if(item.values !== undefined && item.values.length > 0){
                        item.values.forEach(function(item2, index){
                            item2.label = item2.label.replace(/&quot;/g,"\""); 
                            item2.label = item2.label.replace(/&apos;/g, "'");
                        });
                    }
                }

            });
            form_content = JSON.stringify(form_content_obj);
        }
    }
    var $fbEditor = $(document.getElementById('builder-editor'));
    var formBuilder;
    var positionOptions = {
        class: {
            label: 'Position',
            class: "frm-position",
            multiple: false, // optional, omitting generates normal <select>
            options: {
                '':'',
                'form-control-element-right': 'Right',
                'form-control-element-left': 'Left',
                'form-control-element-center': 'Center'
            }/*,
            onchange: 'console.log(this)'*/
        }
    };
    var maxFileSize = {
        fileSize: {
            label: 'File max size',
            type: 'number',
            min: '0',
            value: '1024'
        },
        sizeUnits: {
            label: 'File size units',
            type: 'select',
            options: {
                'bytes':'Bytes',
                'kB':'Kilobyte (kB)',
                'MB':'Megabyte (MB)'
            }
        }
    };
    var tableBtn = {
        title: {
            label: 'Table Columns',
            type: "button",
            value: "Edit",
            style: "width:80px",
            onclick: "setTableSettings(this)"
        }
    };
    var options = {
        controlPosition: 'left',
        disabledActionButtons: ['data', 'clear'],
        formData: (form_content=="new")?"":form_content,
        dataType: 'json',
        typeUserAttrs: {
            header: positionOptions,
            file: maxFileSize,
            table: tableBtn,
            Buttons: {
                label:{
                    label: 'Container label',
                    type: 'text',
                    value: ''
                },
                submitBtnColor: {
                    label: 'Submit button color',
                    multiple: false, // optional, omitting generates normal <select>
                    options: {
                        '':'',
                        'btn btn-primary': 'blue',
                        'btn btn-secondary': 'gray',
                        'btn btn-success': 'green',
                        'btn btn-danger': 'red',
                        'btn btn-warning': 'yellow',
                        'btn btn-info': 'light blue',
                        'btn btn-light': 'white',
                        'btn btn-dark': 'dark',
                        'btn btn-link': 'link'
                    }/*,
                    onchange: 'console.log(this)'*/
                },
                clearBtnColor: {
                    label: 'Clear button color',
                    multiple: false, // optional, omitting generates normal <select>
                    options: {
                        '':'',
                        'btn btn-primary': 'blue',
                        'btn btn-secondary': 'gray',
                        'btn btn-success': 'green',
                        'btn btn-danger': 'red',
                        'btn btn-warning': 'yellow',
                        'btn btn-info': 'lightblue',
                        'btn btn-light': 'white',
                        'btn btn-dark': 'dark',
                        'btn btn-link': 'link'
                    }/*,
                    onchange: 'console.log(this)'*/
                },
                btnsPos: {
                    label: 'Buttons position',
                    multiple: false, // optional, omitting generates normal <select>
                    options: {
                        '':'',
                        'form-control-buttons-right': 'Right',
                        'form-control-buttons-left': 'Left',
                        'form-control-buttons-center': 'Center'
                    }/*,
                    onchange: 'console.log(this)'*/
                },
                submitLabel:{
                    label: 'Submit button label',
                    type: 'text',
                    value: ''
                },
                cancelLabel:{
                    label: 'Cancel button label',
                    type: 'text',
                    value: ''
                }
            }
        },
        replaceFields: [
            {
                type: "table",
                label:'Table',
                placeholder: "[{&quot;name&quot;:&quot;Column1&quot;,&quot;type&quot;:&quot;txt&quot;,&quot;attr&quot;:&quot;&quot;},{&quot;name&quot;:&quot;Column2&quot;,&quot;type&quot;:&quot;txt&quot;,&quot;attr&quot;:&quot;&quot;},{&quot;name&quot;:&quot;Column3&quot;,&quot;type&quot;:&quot;txt&quot;,&quot;attr&quot;:&quot;&quot;}]"
            }
        ],
        disableFields: ['autocomplete','hidden','button','Buttons'],
        controlOrder: [
            'header',
            'text',
            'textarea'
        ],
        disabledAttrs: [
            'access'
        ],
        disabledSubtypes: {
            file: ['fineuploader'],
            textarea: ['quill']
        },
        disabledFieldButtons: {
            table: ['copy'], // disables the copy button for table fields
            Buttons: ['copy','remove'],
            hidden: ['copy','remove']
        },
        stickyControls: {
            enable: true
        },
        scrollToFieldOnAdd: true,
        typeUserEvents: {
            header: {
                onadd: function(fld) {
                    var orginVal;
                    $('.frm-position', fld).on('focus', function () {
                        orginVal = this.value;
                    }).change(function(e) {
                        var calssVal = $(".fld-className",fld).val();
                        if(calssVal.indexOf(" ") > -1){
                            var calssAry = calssVal.split(" ");
                            if(calssAry.indexOf(orginVal) > -1){
                                calssAry[calssAry.indexOf(orginVal)] = e.target.value;
                                var newclass = calssAry.join(" ");
                                $(".fld-className",fld).val(newclass)
                            }else{
                                calssAry.push(e.target.value);
                                var newclass = calssAry.join(" ");
                                $(".fld-className",fld).val(newclass)
                            }
                        }else{
                            $(".fld-className",fld).val(e.target.value)
                        }
                    });
                }
            },
            table: {
                onadd: function (fld) {
                    var $patternField = $(".fld-placeholder", fld);
                    var $patternWrap = $patternField.parents(".placeholder-wrap:eq(0)");
                    $patternWrap.hide();
                }
            },
            Buttons: {
                onadd: function (fld) {
                    var $patternField = $(".fld-value", fld);
                    var $patternWrap = $patternField.parents(".value-wrap:eq(0)");
                    $patternWrap.hide();
                    var $patternField = $(".fld-required", fld);
                    var $patternWrap = $patternField.parents(".required-wrap:eq(0)");
                    $patternWrap.hide();
                    var $patternField = $(".fld-placeholder", fld);
                    var $patternWrap = $patternField.parents(".placeholder-wrap:eq(0)");
                    $patternWrap.hide();
                    /*
                    var $patternField = $(".fld-name", fld);
                    var $patternWrap = $patternField.parents(".name-wrap:eq(0)");
                    $patternWrap.hide();
                    */
                }
            },
            hidden: {
                onadd: function (fld) {
                    var $valueField = $(".fld-value", fld);
                    $valueField.attr("readonly",true);
                    var $nameField = $(".fld-name", fld);
                    $nameField.attr("readonly",true);
                }
            }
        },
        actionButtons: [{
            id: 'preview_form',
            className: 'btn btn-success',
            label: 'Preview',
            type: 'button',
            events: {
                click: function() {
                    var data = formBuilder.actions.getData('xml', true);
                    formBuilder.actions.removeField("button-submit-form");

                    showPreview(data);
                }
            }
        }],
        onSave: function (e,formData) {
            try{
                var formDataObj =  JSON.parse(formData);
                if(formDataObj.length > 0){
                    formDataObj.forEach(function(item, index){
                        if(item.label !== undefined){ //item.type == "paragraph" || item.type == "header" //
                            item.label = item.label.replace(/"/g, "&quot;"); 
                            item.label = item.label.replace(/'/g, "&apos;"); 
                        }
                        if(item.description !== undefined){ 
                            item.description = item.description.replace(/"/g, "&quot;"); 
                            item.description = item.description.replace(/'/g, "&apos;"); 
                        }
                        if(item.type != "table" && item.placeholder !== undefined){ 
                            item.placeholder = item.placeholder.replace(/"/g, "&quot;"); 
                            item.placeholder = item.placeholder.replace(/'/g, "&apos;"); 
                        }
                        if(item.type != "hidden" && item.value !== undefined){
                            item.value = "";
                        }

                        if(item.type == "select" || item.type == "checkbox-group" || item.type == "radio-group"){
                            if(item.values !== undefined && item.values.length > 0){
                                item.values.forEach(function(item2, index){
                                    item2.label = item2.label.replace(/"/g, "&quot;"); 
                                    item2.label = item2.label.replace(/'/g, "&apos;");
                                });
                            }
                        }

                    });
                    formData = JSON.stringify(formDataObj);
                }
                //console.log(formData)
                formContentJsonObj = formData;
                setFormJsonObj();
            }catch(err){
                console.log(err.message);
            }
        },
        onOpenFieldEdit: function(editPanel) {
            //console.log()
            //console.log($(editPanel).parent()[0].type)
            if($(editPanel).parent()[0].type == "table"){
                $($($($(editPanel)[0].children[0]).find(".title-wrap")[0]).find(".input-wrap")[0].children[0]).click();
            }
        },
    };

    if(form_content == "new"){
        $("#form_content_status").val("new");
       formBuilder = $fbEditor.formBuilder(options);
    }else{
        $("#form_content_status").val("");
        formBuilder = $fbEditor.formBuilder(options);
    }

    selectFields(formBuilder);
    //formContentJsonObj = "";
    
    // formbuilder_content_dialog.dialog("open")
}

// SELECT FIELDS
function selectFields(formBuilder) {
    $('input[name="field_option"]').on("change", function () {
        let div = $("html, body, div, div#builder-editor");
        if ($(this).val() == "telephone") {
            if (this.checked) {
                var field = {
                    type:'text',
                    className:'form-control',
                    name:'telephone',
                    id:'telephone',
                    placeholder:'Enter phone number',
                    className:'form-control',
                    label:'Telephone'
                };
                formBuilder.actions.addField(field);
                div.animate({ scrollTop: div.prop("scrollHeight")}, 1000);
            } else {
                formBuilder.actions.removeField();
            }
        }
        else if ($(this).val() == "organisation_name") {
            if (this.checked) {
                var field = {
                    type:'text',
                    className:'form-control',
                    name:'organisation_name',
                    placeholder:'Enter organisation name',
                    className:'form-control',
                    label:'Organisation name'
                };
                formBuilder.actions.addField(field);
                div.animate({ scrollTop: div.prop("scrollHeight")}, 1000);
            } else {
                formBuilder.actions.removeField();
            }
        }
        else if ($(this).val() == "organisation_type") {
            if (this.checked) {
                var field = {
                    type:'text',
                    className:'form-control',
                    name:'organisation_type',
                    placeholder:'Enter organisation type',
                    className:'form-control',
                    label:'Organisation type'
                };
                formBuilder.actions.addField(field);
                div.animate({ scrollTop: div.prop("scrollHeight")}, 1000);
            } else {
                formBuilder.actions.removeField();
            }
        }
        else if ($(this).val() == "job_title") {
            if (this.checked) {
                var field = {
                    type:'text',
                    className:'form-control',
                    name: "job_title",
                    placeholder:'Enter job title',
                    className:'form-control',
                    label:'Job title'
                };
                formBuilder.actions.addField(field);
                div.animate({ scrollTop: div.prop("scrollHeight")}, 1000);
            } else {
                formBuilder.actions.removeField();
            }
        }
        else if ($(this).val() == "job_category") {
            if (this.checked) {
                var field = {
                    type:'text',
                    className:'form-control',
                    name:'job_category',
                    placeholder:'Enter job category',
                    className:'form-control',
                    label:'Job category'
                };
                formBuilder.actions.addField(field);
                div.animate({ scrollTop: div.prop("scrollHeight")}, 1000);
            } else {
                formBuilder.actions.removeField();
            }
        }
        else if ($(this).val() == "residence_country") {
            if (this.checked) {
                var field = {
                    type:'select',
                    className:'form-control',
                    name:'residence_country',
                    id:'residence_country',
                    placeholder:'Please select',
                    className:'form-control',
                    label:'Country',
                    values:[{"label":"Afghanistan...","value":"Afghanistan"},{"label":"Zimbabwe","value":"Zimbabwe"}]
                };
                formBuilder.actions.addField(field);
                div.animate({ scrollTop: div.prop("scrollHeight")}, 1000);
            } else {
                formBuilder.actions.removeField();
            }
        }
        else if ($(this).val() == "website") {
            if (this.checked) {
                var field = {
                    type:'text',
                    className:'form-control',
                    name:'website',
                    placeholder:'Enter website',
                    className:'form-control',
                    label:'Website'
                };
                formBuilder.actions.addField(field);
            } else {
                formBuilder.actions.removeField();
            }
        }
        else if ($(this).val() == "image") {
            if (this.checked) {
                var field = {
                    type:'file',
                    className:'form-control',
                    name:'image',
                    placeholder:'Upload picture',
                    className:'form-control',
                    label:'Picture'
                };
                formBuilder.actions.addField(field);
            } else {
                formBuilder.actions.removeField();
            }
        }
        else if ($(this).val() == "id_type") {
            if (this.checked) {
                var field = {
                    type:'select',
                    className:'form-control',
                    name:'id_type',
                    placeholder:'Please select',
                    className:'form-control',
                    label:'ID type',
                    values:[{"label":"ID Card","value":"ID Card"},{"label":"Passport","value":"Passport"}]
                };
                formBuilder.actions.addField(field);
                div.animate({ scrollTop: div.prop("scrollHeight")}, 1000);
            } else {
                formBuilder.actions.removeField();
            }
        }
        else if ($(this).val() == "id_number") {
            if (this.checked) {
                var field = {
                    type:'text',
                    className:'form-control',
                    name:'id_number',
                    placeholder:'Enter ID number',
                    className:'form-control',
                    label:'ID number'
                };
                formBuilder.actions.addField(field);
                div.animate({ scrollTop: div.prop("scrollHeight")}, 1000);
            } else {
                formBuilder.actions.removeField();
            }
        }
        else if ($(this).val() == "media_card_number") {
            if (this.checked) {
                var field = {
                    type:'text',
                    className:'form-control',
                    name:'media_card_number',
                    placeholder:'Enter press card number',
                    className:'form-control',
                    label:'Press card number'
                };
                formBuilder.actions.addField(field);
                div.animate({ scrollTop: div.prop("scrollHeight")}, 1000);
            } else {
                formBuilder.actions.removeField();
            }
        }
        else if ($(this).val() == "media_card_authority") {
            if (this.checked) {
                var field = {
                    type:'text',
                    className:'form-control',
                    name:'media_card_authority',
                    placeholder:'Enter issuing authority',
                    className:'form-control',
                    label:'Issuing authority'
                };
                formBuilder.actions.addField(field);
                div.animate({ scrollTop: div.prop("scrollHeight")}, 1000);
            } else {
                formBuilder.actions.removeField();
            }
        }

    });
}

function get_form_content(formToken){
    var rt_data = "";
   
    $.ajax({
        type: "POST",
        url: linkto,
        async:false,
        data: {formToken : formToken, request: 'formContent'},
        success: function (response) {
            rt_data = response;
            //console.log(response);
        }
    });
    return rt_data;
}

// PREVIEW FORM
function showPreview(formData) {
    $('#registerModal').modal('show');
    $('#form-render-content').formRender({
        dataType: 'xml',
        formData,
        notify: {
            error: function(message) {
                return console.error(message);
            },
            success: function(message) {
                if(/MSIE \d|Trident.*rv:/.test(navigator.userAgent)){
                    $('input[type="date"]').datepicker({
                        dateFormat: "yy-mm-dd"
                    });
                }

                appendElements();

                return console.log("success: " , message);
            },
            warning: function(message) {
                return console.warn(message);
            }
        }
    });
}

// APPEND ELEMENTS TO THE FORM
function appendElements() {
    $('.form-group').addClass('control-group');
    $('.form-group').append('<p class="help-block"></p>');

    $('#residence_country').find('option').remove().end();

    $('select').append('<option value="" selected>Please select</option>');
    if ($("#telephone").length) {
        var input = document.querySelector("#telephone");
        window.intlTelInput(input, {
            autoPlaceholder: "off",
            separateDialCode: true,
            utilsScript: DN+"/build/js/utils.js",
        });
    }

    $.getScript(DN+'/js/countries.js', function() {});

    $.getScript(DN+'/js/jqBootstrapValidation.min.js', function() {
        $('#registerForm').find('input,select,textarea').not('[type=submit]').jqBootstrapValidation({
            preventSubmit: true,
            submitError: function ($form, event, errors) {
                $("html, body, div, div#registerForm").animate({ scrollTop: "0" }, "slow");
                /* ######### Loader ########## */
                window.setTimeout(function () {
                  $("#load").attr("hidden", "");
                }, 1000);

            },
            submitSuccess: function ($form, event) {
                event.preventDefault();
                var this_form = $('#registerForm');
                $("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
                $('#register-messages').html('<div class="sent-message">SUCCESS</div>');
                this_form.find('.sent-message').slideDown().html("SUCCESS");
                $(".sent-message").delay(500).show(10, function() {
                    $(this).delay(3000).hide(10, function() {
                        $(this).remove();
                        $('#registerModal').modal('hide');
                    });
                });
            },
            filter: function () {
                return $(this).is(":visible");
            },
        });
    });
}

// SAVE FORM
function setFormJsonObj() {
    var action_type;
    var frm_id = $("#form_content_id").val();
    var content_status =  $("#form_content_status").val();
    var this_form = $('#formbuilder_content');

    if (formContentJsonObj != "") {
        var frm_data = {
            formToken: formToken,
            contentToken: contentToken,
            template: formContentJsonObj
        }
        var data_obj = {
            action: 'update',
            data: frm_data
        };
        $.ajax({
            type: "POST",
            url: linkto,
            data: {data:JSON.stringify(data_obj), request:'saveFormTemplate'},
            success: function (dataResponse) {
                var response = JSON.parse(dataResponse);
                if (response.success == true) {
                    $("html, body, div#formbuilder_content").animate({ scrollTop: '0' }, 100);
                    $('#add-messages').html('<div class="sent-message">'+ response.messages + '</div>');
                    this_form.find('.sent-message').slideDown().html(response.messages);
                    $(".sent-message").delay(500).show(10, function() {
                        $(this).delay(3000).hide(10, function() {
                            $(this).remove();
                        });
                    });
                } else {
                    $('#registerButton').prop('disabled', false);
                    $("html, body, div#formbuilder_content").animate({ scrollTop: '0' }, 100);
                    $('#add-messages').html('<div class="error-message">'+ response.messages + '</div>');
                    this_form.find('.error-message').slideDown().html(response.messages);
                    $(".error-message").delay(500).show(10, function() {
                        
                    });
                }
            },
            error:function (response) {
                console.log("Error:",JSON.stringify(response));
                alert(response.responseText)
            }
        });
    }
}





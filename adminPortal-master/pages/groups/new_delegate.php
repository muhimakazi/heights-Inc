<?php
require_once "../../core/init.php";
if (!$user->isLoggedIn()) {
    Redirect::to('login');
}

/** Get the Participation Type ID  and Event Type */
if(!Input::checkInput('slot', 'get', 1))
  Redirect::to('');

/** Get Slot */
$_SLOT_TOKEN_     = Input::get('slot', 'get');
$_SLOT_ID_        = Hash::decryptAuthToken($_SLOT_TOKEN_);

/** Get Slot Data By Id */
$_SLOT_DATA_ = FutureEventGroupController::getGrouSlotByID($_EVENT_ID_, $_GROUP_ID_, $_SLOT_ID_ );
if(!$_SLOT_DATA_ )
    Redirect::to('');

$_EVENT_TYPE_     = strtoupper( $_SLOT_DATA_->participation_sub_type_category );

/** Get the Participation Type ID  and Event Type */
if($_EVENT_TYPE_  != 'INPERSON' AND $_EVENT_TYPE_  != 'VIRTUAL'  )
  Redirect::to('');

$_EVENT_TYPE_NAME_ = ucfirst($_EVENT_TYPE_);
if($_EVENT_TYPE_ == 'INPERSON')
    $_EVENT_TYPE_NAME_ = 'In-person';
if($_EVENT_TYPE_ == 'VIRTUAL')
    $_EVENT_TYPE_NAME_ = 'Virtual';


$_EVENT_PARTICIPATION_SUB_TYPE_ID_ENCRYPTED_ = Hash::encryptToken($_SLOT_DATA_->participation_sub_type_id);
$_EVENT_PARTICIPATION_SUB_TYPE_ID_ = $_SLOT_DATA_ ->participation_sub_type_id;

$_EVENT_PARTICIPATION_TYPE_NAME_ = $_SLOT_DATA_->participation_type_name;
$_EVENT_PARTICIPATION_SUB_TYPE_NAME_ = $_SLOT_DATA_->participation_sub_type_name;


$_HIDDEN_STATE['SECTION'] = array(
    'IDENTIFICATION' => '',
    'MEDIA_TOOLS'    => '',
);

$_DCOLOR_  = '#37af47';
$_EvCode_  = 'INP001';
$_EvPCode_ = 'AFBR001';
/** Condition When True - Hidden */
if($_EVENT_TYPE_ == 'VIRTUAL'):
    $_HIDDEN_STATE['SECTION']['IDENTIFICATION'] = 'hidden';
    $_HIDDEN_STATE['SECTION']['MEDIA_TOOLS']    = 'hidden';

    $_DCOLOR_ = '#a42f1d';
    $_EvCode_ = 'VRT002';
endif;

$_DCOLOR_ = '#dedede!important';

?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="<?php linktoApac('css/select2.min.css'); ?>">
    <link rel="stylesheet" href="<?php linktoApac('css/flag-icon.min.css'); ?>">
    <link rel="stylesheet" class="host" link2="<?=Config::get('server/web')?>" link="<?=Config::get('server/name')?>"></link>
    <link rel="stylesheet" href="<?php linktoApac('build/css/intlTelInput.css'); ?>">
    <?php include $INC_DIR . "head.php"; ?>
    <script src="<?php linkto('js/jquery-2.1.1.js'); ?>"></script>
    <link href="<?php linktoApac('fileinput/css/fileinput.min.css'); ?>" rel="stylesheet">
    <style>
    .radio {
        margin: 0.5rem;
    }
    .radio label {
        padding-left: 0;
    }
    .radio input[type="radio"] {
         position: absolute;
         opacity: 0;
    }
     .radio input[type="radio"] + .radio-label:before {
         content: '';
         background: #f4f4f4;
         border-radius: 100%;
         border: 1px solid #b4b4b4;
         display: inline-block;
         width: 1.4em;
         height: 1.4em;
         position: relative;
         top: -0.2em;
         margin-right: 1em;
         vertical-align: top;
         cursor: pointer;
         text-align: center;
         transition: all 250ms ease;
    }
     .radio input[type="radio"]:checked + .radio-label:before {
         background-color: #e85d27;
         box-shadow: inset 0 0 0 4px #f4f4f4;
    }
     .radio input[type="radio"]:focus + .radio-label:before {
         outline: none;
         border-color: #e85d27;
    }
     .radio input[type="radio"]:disabled + .radio-label:before {
         box-shadow: inset 0 0 0 4px #f4f4f4;
         border-color: #b4b4b4;
         background: #b4b4b4;
    }
     .radio input[type="radio"] + .radio-label:empty:before {
         margin-right: 0;
    }
    </style>
    <script>
        $('#registerButton').prop('disabled', true);
    </script>
</head>

<body>
    <div id="wrapper">

        <?php include $INC_DIR . "nav.php"; ?>
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2> Register Delegate</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php linkto(''); ?>">Home</a>
                    </li>
                    <li>
                        <a href="<?=linkto('pages/group/register/delegates')?>">Group</a>
                    </li>
                    <li>
                        <a href="<?=linkto('pages/group/register/delegates')?>"><?=$_GROUP_NAME_?></a>
                    </li>
                    <li>
                        <a href="<?=linkto('pages/group/register/delegates')?>">Delegates</a>
                    </li>
                    <li>
                        <a href="#"><?=$_EVENT_PARTICIPATION_TYPE_NAME_?></a>
                    </li>
                    <li>
                        <a href="#"><?=$_EVENT_PARTICIPATION_SUB_TYPE_NAME_?></a>
                    </li>
                    <li>
                        <a href="#"><?=Functions::getEventCategory( $_EVENT_TYPE_ )?></a>
                    </li>
                    
                </ol>
            </div>
            <div class="col-lg-2"></div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">


                <div class="col-lg-2"></div>
                <div class="col-lg-8">
                    <div class="ibox float-e-margins">
                        <div class="col-lg-12" style="background: #fff; padding-bottom: 15px;">
                            <form  class="form-contact" id="registerForm" method="post">
                                <div id="register-messages"></div>
                                <label><?=$_Dictionary->words('all-fields-are-mendatory')?> </label>
                                <h4><?=$_Dictionary->translate('CONTACT INFORMATION')?></h4>
                                <hr class="separator-line"> 
                                <div class="row">
                                    <div class="form-group col-sm-12">
                                        <div class="row">
                                            <label for="firstname" class="col-sm-3"><?=$_Dictionary->translate('First name')?> <span>*</span></label>
                                            <div class="col-sm-9 field-validate">
                                                <input class="form-control"  name="firstname" oninput="validate(this)" id="firstname" type="text" placeholder="<?=$_Dictionary->translate('First name')?>" data-rule="required" data-msg="<?=$_Dictionary->words('Please enter your first name')?>"/>
                                                <div class="validate" id="firstname_error"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <div class="row">
                                            <label for="lastname" class="col-sm-3"><?=$_Dictionary->translate('last name')?> <span>*</span></label>
                                            <div class="col-sm-9 field-validate">
                                                <input class="form-control"  name="lastname" oninput="validate(this)"  id="lastname" type="text" placeholder="<?=$_Dictionary->translate('Last name')?>" data-rule="required"  data-rule="required" data-msg="<?=$_Dictionary->words('Please enter your last name')?>"/>
                                                <div class="validate" id="lastname_error"></div> 
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-12">
                                        <div class="row">
                                            <label for="email" class="col-sm-3"><?=$_Dictionary->translate('Email')?> <span>*</span></label>
                                            <div class="col-sm-9 field-validate">
                                              <input class="form-control"  name="email" oninput="validate(this)"  id="email" type="text" placeholder="<?=$_Dictionary->translate('Email')?>" data-rule="email"  data-rule="required" data-msg="<?=$_Dictionary->words('Please enter your email')?>" onselectstart="return false" />
                                                <div class="validate" id="email_error"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <div class="row">
                                            <label for="email" class="col-sm-3"><?=$_Dictionary->translate('Confirm email')?> <span>*</span></label>
                                            <div class="col-sm-9 field-validate">
                                              <input class="form-control" name="confirm_email" oninput="validate(this)"  id="confirm_email" type="text" placeholder="<?=$_Dictionary->translate('Confirm email')?>" data-rule="email"  data-rule="required" data-msg="<?=$_Dictionary->words('Please enter your confirm email')?>" onselectstart="return false" />
                                                <div class="validate" id="confirm_email_error"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-12">
                                        <div class="row">
                                            <label for="telephone" class="col-sm-3"><?=$_Dictionary->translate('Telephone number 1')?> <span>*</span></label>
                                            <div class="col-sm-9 field-validate">
                                                <input type="hidden" name ="full_telephone" value="" >
                                                <input type="text" name="telephone" id="telephone" oninput="validate(this)"  class="form-control" data-rule="required" data-msg="<?=$_Dictionary->words('Please enter your telephone')?>"/>
                                                <div class="validate" id="telephone_error"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <div class="row">
                                            <label for="telephone" class="col-sm-3"><?=$_Dictionary->translate('Telephone number 2')?></label>
                                            <div class="col-sm-9 field-validate">
                                                <input type="hidden" name ="full_telephone_2" value="" >
                                                <input type="text" name="telephone_2" oninput="validate(this)"  data-msg="<?=$_Dictionary->words('Please enter your telephone 2')?>"  id="telephone_2" class="form-control"/>
                                                <div class="validate" id="telephone_2_error"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <div class="row">
                                            <label for="organisation-name" class="col-sm-3"><?=$_Dictionary->translate('Job title')?> <span>*</span></label>
                                            <div class="col-sm-9 field-validate">
                                                <input class="form-control" name="job_title" oninput="validate(this)"  id="job_title" type="text" placeholder="<?=$_Dictionary->translate('Job title')?>" data-rule="required" data-msg="<?=$_Dictionary->words('Please enter your job title')?>"/>
                                                <div class="validate" id="job_title_error"></div> 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <div class="row">
                                            <label for="job_category" class="col-sm-3"><?=$_Dictionary->translate('Job category')?> <span>*</span></label>
                                            <div class="col-sm-9">
                                                <div class="row">
                                                    <div class="col-sm-6 field-validate">
                                                        <select class="form-control" name="job_category" id="job_category" onchange="Other(this,'#job_category1');" data-rule="required" data-msg="Please select your job category" >
                                                            <?php $user->jobTitle($form->ERRORS,Input::get('job_category'),$categ);?>
                                                        </select>
                                                        <div class="validate" id="job_category_error"></div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input class="form-control" oninput="validate(this)"  name="job_category1" id="job_category1" type="text" placeholder="<?=$_Dictionary->translate('For other - please specify')?>"  data-msg="<?=$_Dictionary->words('Please enter your job category')?>" 
                                                        <?php if(escape(Input::get('job_category')) != 'Other'){?> disabled="disabled" <?php }?>>
                                                        <div class="validate" id="job_category1_error"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <div class="row">
                                            <label for="gender" class="col-sm-3"><?=$_Dictionary->translate('language')?> <span>*</span></label>
                                            <div class="col-sm-9 field-validate">
                                                <select id="language" name="language" onchange="validate(this)" class="form-control" data-rule="required" data-msg="<?=$_Dictionary->words('Please select  Language')?>">
                                                    <option value="">[--<?=$_Dictionary->translate('Select')?>--]</option>
                                                    <option value="English">English</option>
                                                    <option value="French">French</option>
                                                    <option value="Portuguese">Portuguese</option>
                                                    <option value="Arabic">Arabic</option>
                                                </select>
                                                <div class="validate" id="language_error"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <div class="row">
                                            <label for="gender" class="col-sm-3"><?=$_Dictionary->translate('Gender')?> <span>*</span></label>
                                            <div class="col-sm-9 field-validate">
                                                <select id="gender" name="gender" onchange="validate(this)"  class="form-control" data-rule="required" data-msg="<?=$_Dictionary->words('Please select your gender')?>">
                                                    <option value="">[--<?=$_Dictionary->translate('Select')?>--]</option>
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                    <option value="Other">Prefer not to disclose</option>
                                                </select>
                                                <div class="validate" id="gender_error"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-12"  id="data_1"> 
                                        <div class="row">
                                            <label for="birthday" class="col-sm-3"><?=$_Dictionary->translate('Date of birth')?></label>
                                            <div class="col-sm-9 field-validate">
                                                <div class="input-group date">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" oninput="validate(this)" value="dd/mm/yyyy" name="birthday" id="birthday" class="form-control" data-rule="" data-msg="<?=$_Dictionary->words('Please enter date of birth')?>" data-msgc="<?=$_Dictionary->translate('Only people with age between 10 and 120 can register')?>"/>
                                                    <div class="validate" id="birthday_error"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <h4><?=$_Dictionary->translate('ORGANIZATION')?> </h4>
                                <hr class="separator-line"> 
                                <div class="row">
                                    <div class="form-group col-sm-12">
                                        <div class="row">
                                            <label for="organisation-name" class="col-sm-3"><?=$_Dictionary->translate('Organization name')?> <span>*</span></label>
                                            <div class="col-sm-9 field-validate">
                                                <input class="form-control" oninput="validate(this)"  name="organisation_name" id="organisation_name" type="text" placeholder="<?=$_Dictionary->translate('Organization name')?>" data-rule="required" data-msg="<?=$_Dictionary->words('Please enter your organisation name')?>"/>
                                                <div class="validate" id="organisation_name_error"></div> 
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-12">
                                        <div class="row">
                                            <label for="organisation-name" class="col-sm-3"><?=$_Dictionary->translate('Organization type')?> <span>*</span></label>
                                            <div class="col-sm-9">
                                                <div class="row">
                                                    <div class="col-sm-6 field-validate">
                                                        <select class="form-control" onchange="Other(this,'#organisation_type1');" id="organisation_type" name="organisation_type" data-rule="required" data-msg="<?=$_Dictionary->words('Please enter your organisation type')?>" >
                                                            <option value="">[--<?=$_Dictionary->translate('Select')?>--]</option>
                                                            <option value="Academia">Academia</option>
                                                            <option value="Civil Society">Civil Society </option>
                                                            <option value="International Organization">International Organization</option>
                                                            <option value="Non-Governmental Organization">Non-Governmental Organization</option>
                                                            <option value="Non-Profit Organization">Non-Profit Organization</option>
                                                            <option value="Private/Corporation">Private/Corporation</option>
                                                            <option value="Regional Organization">Regional Organization </option>
                                                            <option value="Other">Other </option>
                                                        </select>
                                                        <div class="validate" id="organisation_type_error"></div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input class="form-control" oninput="validate(this)"  name="organisation_type1" id="organisation_type1" type="text" placeholder="<?=$_Dictionary->translate('For other - please specify')?>"   data-msg="<?=$_Dictionary->words('Please enter your organisation type')?>" 
                                                        <?php if(escape(Input::get('organisation_type')) != 'Other'){?> disabled="disabled" <?php }?>>
                                                        <div class="validate" id="organisation_type1_error"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-12">
                                        <div class="row">
                                            <label for="industry" class="col-sm-3"><?=$_Dictionary->translate('Industry')?> <span>*</span></label>
                                            <div class="col-sm-9">
                                                <div class="row">
                                                    <div class="col-sm-6 field-validate">
                                                        <select class="form-control" name="industry" id="industry" onchange="Other(this,'#industry1');" data-rule="required" data-msg="<?=$_Dictionary->words('Please enter your industry')?>" >
                                                            <option value="">[--<?=$_Dictionary->translate('Select')?>--]</option>   
                                                            <option value="Academics/ Education">Academics/ Education</option>
                                                            <option value="Advertising/Public Relations">Advertising/Public Relations</option>
                                                            <option value="Agricultural Services &amp; Products">Agricultural Services &amp; Products</option>
                                                            <option value="Attorneys and law">Attorneys and law</option>
                                                            <option value="Clergy &amp; Religious Organizations" >Clergy &amp; Religious Organizations </option>
                                                            <option value="Clothing and Textiles">Clothing and Textiles</option>
                                                            <option value="Defence and security">Defence and security</option>
                                                            <option value="Energy">Energy</option>
                                                            <option value="Energy and Natural Resources and Environment"> Natural Resources and Environment</option>
                                                            <option value="Entertainment Industry">Entertainment Industry</option>
                                                            <option value="Financial and Commercial Services">Financial and Commercial Services</option>
                                                            <option value="Hospitality and Tourism">Hospitality and Tourism</option>
                                                            <option value="Healthcare services">Healthcare services</option>
                                                            <option value="ICT">ICT</option>
                                                            <option value="Infrastructure">Infrastructure</option>
                                                            <option value="Logistics and Transportation">Logistics and Transportation</option>
                                                            <option value="Manufacturing">Manufacturing</option>
                                                            <option value="Mining">Mining</option>
                                                            <option value="Media">Media </option>
                                                            <option value="Non-profits, Foundations &amp; Philanthropists">Non-profits, Foundations &amp; Philanthropists</option>
                                                            <option value="Printing &amp; Publishing">Printing &amp; Publishing</option>
                                                            <option value="Private Equity &amp; Investment Firms">Private Equity &amp; Investment Firms</option>
                                                            <option value="Real Estate">Real Estate</option>
                                                            <option value="Religious Organizations/Clergy">Religious Organizations/Clergy</option>
                                                            <option value="Sports, Professional">Sports, Professional</option>
                                                            <option value="Telecommunications">Telecommunications </option>
                                                            <option value="Other">Other </option>
                                                        </select>
                                                        <div class="validate" id="industry_error"></div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input class="form-control" oninput="validate(this)"  name="industry1" id="industry1" type="text" placeholder="<?=$_Dictionary->translate('For other - please specify')?>"  data-msg="<?=$_Dictionary->words('Please enter your industry')?>" 
                                                        <?php if(escape(Input::get('industry')) != 'Other'){?> disabled="disabled" <?php }?>>
                                                        <div class="validate" id="industry1_error"></div> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <div class="row">
                                            <label for="website" class="col-sm-3"><?=$_Dictionary->translate('Organization Website')?></label>
                                            <div class="col-sm-9 field-validate">
                                                <input class="form-control" oninput="validate(this)"  name="website" id="website" type="text" placeholder="<?=$_Dictionary->translate('Organization Website')?>"  data-msg="<?=$_Dictionary->words('Please enter your website')?>" >
                                                <div class="validate" id="website_error"></div> 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <div class="row">
                                           <label for="" class="col-sm-3"><?=$_Dictionary->translate('Company Location')?> <span>*</span></label>
                                            <div class="col-sm-9 field-validate">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <select id="organisation_country" onchange="validate(this)"  name="organisation_country" class="form-control" data-rule="required" data-title="<?=$_Dictionary->words('Select country')?>" data-msg="<?=$_Dictionary->words('Please select your country')?>" >
                                                            <option></option>
                                                        </select>
                                                        <div class="validate" id="organisation_country_error"></div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input class="form-control" oninput="validate(this)"  name="organisation_city" id="organisation_city" type="text"  placeholder="<?=$_Dictionary->words('City')?>" data-rule="required" data-msg="<?=$_Dictionary->words('Please enter your city')?>"/>
                                                        <div class="validate" id="organisation_city_error"></div> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <h4><?=$_Dictionary->translate('WHAT ARE YOUR OBJECTIVES FOR ATTENDING THIS CONGRESS?')?></h4>
                                <hr class="separator-line"> 
                                <div class="row"> 
                                    
                                    <div class="form-group col-sm-12">
                                        <div class="row">
                                            <label for="organisation-name" class="col-sm-3"><?=$_Dictionary->words('Select objectives')?> <span>*</span></label>
                                            <div class="col-sm-9">
                                                <div class="row">
                                                    <div class="col-sm-6 field-validate">
                                                        <select class="js-example-basic-multiple form-control" onchange="Other(this,'#objectives1');" id="objectives" name="objectives[]" data-rule="required" data-msg="<?=$_Dictionary->words('Please enter your objective')?>"  class=""  multiple="multiple"  >
                                                            <option value="">[--<?=$_Dictionary->translate('Select')?>--]</option>
                                                            <option value="Make a key-note address">Make a key-note address</option> 
                                                            <option value="Engage in high-level debates and refine your ideas">Engage in high-level debates and refine your ideas </option>
                                                            <option value="Showcase your work (e.g. side event, exhibitions, presentations etc.)">Showcase your work (e.g. side event, exhibitions, presentations etc.)</option>
                                                            <option value="Network and build a community of like-minded individuals">Network and build a community of like-minded individuals</option>
                                                            <option value="Learn, share new ideas and best practices">Learn, share new ideas and best practices</option>
                                                            <option value="Other">Other (specify)</option>
                                                        </select>
                                                        <div class="validate" id="objectives_error"></div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input class="form-control" oninput="validate(this)"  name="objectives1" id="objectives1" type="text" placeholder="<?=$_Dictionary->words('please specify')?>"   data-msg="<?=$_Dictionary->words('Please enter your objective')?>" 
                                                        <?php if(escape(Input::get('organisation_type')) != 'Other'){?> disabled="disabled" <?php }?>>
                                                        <div class="validate" id="objectives1_error"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <h4><?=$_Dictionary->translate('WHERE DID YOU HEAR ABOUT APAC?')?> </h4>
                                <hr class="separator-line"> 
                                <div class="row">
                                    <div class="form-group col-sm-12">
                                        <div class="row">
                                            <label for="organisation-name" class="col-sm-3"><?=$_Dictionary->translate('Select source')?><span>*</span></label>
                                            <div class="col-sm-9 field-validate">
                                                <select class="form-control" name="info_source"  onchange="validate(this)" id="info_source" data-rule="required" data-msg="<?=$_Dictionary->words('Please select')?>" > 
                                                    <option value="">[--<?=$_Dictionary->translate('Select')?>--]</option> 
                                                    <option value="Radio"> Radio</option>
                                                    <option value="TV"> TV</option>
                                                    <option value="Online / Social media">Online / Social media </option>
                                                    <option value="Word of mouth"> Word of mouth </option>
                                                    <option value="Email"> Email </option>
                                                    <option value="Embassy / Consulate"> Embassy / Consulate </option>
                                                </select>
                                                <div class="validate" id="info_source_error"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
            <?php
            if($_HIDDEN_STATE['SECTION']['IDENTIFICATION'] != 'hidden'):
            ?>
                            <span class="<?=$_HIDDEN_STATE['SECTION']['IDENTIFICATION']?>">
                                <h4><?=$_Dictionary->translate('BADGE COLLECTION IDENTIFICATION')?></h4>
                                <hr class="separator-line"> 
                                <div class="row">
                                    <div class="form-group col-sm-12">
                                        <div class="row">
                                            <label for="organisation-name" class="col-sm-3"><?=$_Dictionary->translate('Type of ID document')?> <span>*</span></label>
                                            <div class="col-sm-9">
                                                <div class="row">
                                                    <div class="col-sm-6 field-validate">
                                                        <select class="form-control" onchange="validate(this)"  name="id_type" id="id_type" data-rule="required" data-msg="<?=$_Dictionary->words('Please select document type')?>" > 
                                                            <option value="">[--<?=$_Dictionary->translate('Select')?>--]</option> 
                                                            <option value="Passport">Passport</option>
                                                            <option value="ID">ID card</option>
                                                        </select>
                                                        <div class="validate" id="id_type_error"></div>
                                                    </div>
                                                    <div class="col-sm-6 field-validate">
                                                        <input class="form-control" id="id_number" oninput="validate(this)"  name="id_number"  placeholder="<?=$_Dictionary->translate('Document number')?>" data-rule="required" data-msg="<?=$_Dictionary->words('Please enter document number')?>"/>
                                                        <div class="validate" id="id_number_error"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12"> 
                                                <div class="row">
                                                    <label for="organisation-name" class="col-sm-12">
                                                        <?=$_Dictionary->translate('every-participant')?> <span>*</span><br><br>
                                                        
                                                    </label>
                                                    <div class="col-sm-12">
                                                        <div class="row">
                                                            <label for="organisation-name" class="col-sm-3 text-justif">   
                                                                <b><?=$_Dictionary->translate('image file format')?>:</b><br> <span><?=$_Dictionary->translate('image-file-format-desc')?></span> <br>
                                                                <b><?=$_Dictionary->translate('Image size')?>:</b><br> <span><?=$_Dictionary->translate('image-size-desc')?></span> <br>
                                                                <b><?=$_Dictionary->translate('head-position')?></b><br> <span><?=$_Dictionary->translate('head-position-desc')?></span> <br>
                                                                <b><?=$_Dictionary->translate('color-p')?>:</b> <span> <?=$_Dictionary->translate('full-color')?> </span> <br>
                                                                <b><?=$_Dictionary->translate('background')?>:</b><br> <span> <?=$_Dictionary->translate('background-desc')?> </span>
                                                            </label>
                                                            <div class="col-sm-9">
                                                                <div id="kv-avatar-errors-1" class="center-block" style="display:none;"></div>
                                                                <div class="kv-avatar center-block">                            
                                                                    <input type="file" name="image" class="form-control" id="image" placeholder="Profile picture"  class="file-loading" style="width:auto;" data-rule="required" data-msg="<?=$_Dictionary->translate('Please upload your profile picture')?>"/>
                                                                    <div class="validate" id="image_error"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </span>
                            <?php
                            endif;
                            ?>
                                
                                <h4><?=$_Dictionary->translate('ACCOMMODATION')?></h4>
                                <hr class="separator-line">
                                <div class="row">
                                    <div class="form-group col-sm-12">
                                        <div class="row">
                                            <label for="lastname" class="col-sm-12" ><?=$_Dictionary->translate('Would you like to receive information on accommodation booking in Kigali?')?> <span>*</span></label>
                                            <div class="col-sm-9">
                                                <div class="radio">
                                                    <input id="radio-1" class="form-check-label" type="radio" name="needAccommodation" checked value="1">
                                                    <label for="radio-1" class="radio-label"><?=$_Dictionary->translate('Yes')?></label>
                                                </div>
                                                <div class="radio">
                                                    <input id="radio-2" class="form-check-label" type="radio" name="needAccommodation"  value="0">
                                                    <label  for="radio-2" class="radio-label"><?=$_Dictionary->translate('No')?></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h4><?=$_Dictionary->translate('CREATE PASSWORD')?></h4>
                                <hr class="separator-line">
                                <div class="row">
                                    <div class="form-group col-sm-12">
                                        <div class="row">
                                            <label for="firstname" class="col-sm-3"><?=$_Dictionary->translate('Password')?> <span>*</span></label>
                                            <div class="col-sm-9 field-validate">
                                                <input class="form-control" name="password" id="password" oninput="validate(this)"  type="password" placeholder="<?=$_Dictionary->words('Enter password')?>" data-rule="required" data-msg="<?=$_Dictionary->words('Minimum 6 characters')?>"/>
                                                <div class="validate" id="password_error"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-12">
                                        <div class="row">
                                            <label for="lastname" class="col-sm-3"><?=$_Dictionary->translate('Confirm Password')?> <span>*</span></label>
                                            <div class="col-sm-9 field-validate">
                                              <input class="form-control" name="confirm_password" oninput="validate(this)"  id="confirm_password" type="password" placeholder="<?=$_Dictionary->translate('Confirm password')?>" data-rule="required" data-msg="<?=$_Dictionary->words('Password does not match')?>"/>
                                                <div class="validate" id="confirm_password_error"></div>
                                                <p class="pw hidden"><?=$_Dictionary->translate('Password message')?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- - -->
                                <h4><?=$_Dictionary->translate('EMERGENCY CONTACT')?></h4> 
                                <hr class="separator-line"> 
                                <div class="row">
                                    <div class="form-group col-sm-12">
                                        <div class="row">
                                            <label for="emergency_firstname" class="col-sm-3"><?=$_Dictionary->translate('First name')?> <span>*</span></label>
                                            <div class="col-sm-9 field-validate">
                                                <input class="form-control" name="emergency_firstname" oninput="validate(this)" id="emergency_firstname" type="text" placeholder="<?=$_Dictionary->translate('First name')?>" data-rule="required" data-msg="<?=$_Dictionary->words('Please enter first name')?>"/>
                                                <div class="validate" id="emergency_firstname_error"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-12">
                                        <div class="row">
                                            <label for="emergency_lastname" class="col-sm-3"><?=$_Dictionary->translate('last name')?> <span>*</span></label>
                                            <div class="col-sm-9 field-validate">
                                                <input class="form-control" name="emergency_lastname" oninput="validate(this)"  id="emergency_lastname" type="text" placeholder="<?=$_Dictionary->translate('Last name')?>" data-rule="required"  data-rule="required" data-msg="<?=$_Dictionary->words('Please enter last name')?>"/>
                                                <div class="validate" id="emergency_lastname_error"></div> 
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-12">
                                        <div class="row">
                                            <label for="emergency_email" class="col-sm-3"><?=$_Dictionary->translate('Email')?> <span>*</span></label>
                                            <div class="col-sm-9 field-validate">
                                              <input class="form-control" name="emergency_email" oninput="validate(this)"  id="emergency_email" type="text" placeholder="<?=$_Dictionary->translate('Email')?>" data-rule="email"  data-rule="required" data-msg="<?=$_Dictionary->words('Please enter email')?>" onselectstart="return false" />
                                                <div class="validate" id="emergency_email_error"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <div class="row">
                                            <label for="emergency_telephone" class="col-sm-3"><?=$_Dictionary->translate('Telephone number')?> <span>*</span></label>
                                            <div class="col-sm-9 field-validate">
                                                <input type="hidden" name ="emergency_full_telephone" value="" >
                                                <input type="text" name="emergency_telephone" id="emergency_telephone" oninput="validate(this)"  class="form-control" data-rule="required" data-msg="<?=$_Dictionary->words('Please enter telephone')?>"/>
                                                <div class="validate" id="emergency_telephone_error"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- - -->
                                
                                <div class="row" style="margin-bottom: 2%;">
                                    <div class="form-group col-sm-12">
                                        <div>
                                            <label class="checkbox-mc"> <?=$_Dictionary->string('form-by-clicking-you-agree-terms-conditions')?>  
                                                <input type="checkbox" name="privacy"  id="privacy"> 
                                                <span class="geekmark" ></span> 
                                            </label> 
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-bottom: 2%;">
                                    <div class="form-group col-sm-12">
                                        <div>
                                            <label class="checkbox-mc"> <?=$_Dictionary->string('form-by-clicking-you-agree-terms-conditions2')?>  
                                                <input type="checkbox" name="privacy2"  id="privacy2"> 
                                                <span class="geekmark" ></span> 
                                            </label> 
                                        </div>
                                    </div>
                                </div>
                                <hr class="separator-line">
                                <div class="row" style="margin-bottom: 2%;">
                                    <div class="col-md-4 col-sm-12 col-xm-12" style="margin-top: 3%; ">
                                        <img src="<?=linkto('get_captcha.php?rand='.rand())?>" id='captcha' class="img img-responsive">
                                        <a href="javascript:void(0)" id="reloadCaptcha" title="Refresh" style="color: #f47821; font-size: 140%; margin-left: 10%;"><i class="fa fa-refresh"></i></a>
                                        <div class="validate" id="securityCode_error"></div>
                                     </div>
                                    
                                     <div class="col-md-8 col-sm-12 col-xm-12">
                                        <div class="span8 main-row">
                                            <div class="input">
                                                <label style="color: #ffffff; font-size: 16px; margin-top: 1%; margin-bottom: -1%;"><?=$_Dictionary->translate('Type the characters you see')?></label><br>
                                                <input type="text" id="securityCode" placeholder="<?=$_Dictionary->translate('Type the captcha characters here')?>" name="securityCode" class="form-control">
                                            </div>
                                        </div>  
                                    </div>
                                </div>

                                <div class="form-group mt-2" style="overflow: auto;">
                                    <input type="hidden" name="request"  value="groupDelegateRegistration">
                                    <input type="hidden" name="eventId"  value="<?=Hash::encryptToken($activeEventId)?>">
                                    <input type="hidden" name="_EvCode_" id="_EvCode_"  value="<?=$_EvCode_?>">
                                    <input type="hidden" name="_EvPCode_"  id="_EvPCode_" value="<?=$_EvPCode_?>">
                                    <input type="hidden" name="del_type" value="">
                                    <input type="hidden" name="eventParticipation" value="<?=$_EVENT_PARTICIPATION_SUB_TYPE_ID_ENCRYPTED_?>">
                                    <input type="hidden" name="groupToken" value="<?=Hash::encryptAuthToken($_SLOT_ID_)?>">
                                    <button type="button" id="registerButton" class="btn btn-primary px-5 py-2 text-white pull-right registerFormSubmit loader-btn" data-loading-text="Loading..."><?=$_Dictionary->translate('Submit')?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2"></div>
            </div>
        </div>               

        <script src="<?=linkto('pages/groups/new_delegate.js?v=3')?>"></script>
        <br> 
        <?php include $INC_DIR . "footer.php"; ?>

        <script src="<?php linktoApac('fileinput/js/fileinput.min.js'); ?>"></script>
        <script>
            $("#image").fileinput({
            overwriteInitial: true,
            maxFileSize: 500,
            showClose: false,
            showCaption: false,
            browseLabel: '',
            removeLabel: '',
            browseIcon: '<i class="fa fa-folder-open"></i> Upload from computer',
            removeIcon: '<i class="fa fa-remove"></i> Delete image',
            removeTitle: 'Cancel or reset changes',
            elErrorContainer: '#kv-avatar-errors-1',
            msgErrorClass: 'alert alert-block alert-danger',
            defaultPreviewContent: '<img src="<?=DN?>/data_system/img/photo_default.png" alt="Event banner" style="width:50%;">',
            layoutTemplates: {main2: '{preview} {remove} {browse}'},                                    
            allowedFileExtensions: ["jpg", "png", "gif", "JPG", "PNG", "GIF"]
        });  
        </script>


        <script src="<?php linktoApac('js/select2.min.js'); ?>"></script>
        <script src="<?php linktoApac('js/countries.js'); ?>"></script>
        <script src="<?php linktoApac('build/js/intlTelInput.js'); ?>"></script>
        <script>
            var phone_number = window.intlTelInput(document.querySelector("#telephone"), {
                autoPlaceholder: "off",
                separateDialCode: true,
                initialCountry: "rw",
                hiddenInput: "full",
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
            });

            var phone_number_2 = window.intlTelInput(document.querySelector("#telephone_2"), {
                autoPlaceholder: "off",
                separateDialCode: true,
                initialCountry: "rw",
                hiddenInput: "full", 
                utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
            });

            var phone_number_emergency = window.intlTelInput(document.querySelector("#emergency_telephone"), {
                autoPlaceholder: "off",
                separateDialCode: true,
                initialCountry: "rw",
                hiddenInput: "full", 
                utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
            });
        </script>


<script>
  $(document).ready(function() {
      $('.js-example-basic-multiple').select2();
  })
  </script>
  
<script>
  $(document).ready(function() {
      $('.js-example-basic').select2();
  })
  </script>
  
  <!-- loader  -->
   <script >
        $(".loader-btn").on("click" , function(){
            $("#load").removeAttr("hidden");
        });
        

        /* ######### Loader ########## */
        window.setTimeout(function(){
            $("#load").attr("hidden", "");
        }, 1000);
     </script>

        </div>
        </div>
</body>

</html>

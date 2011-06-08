<?php
# eForm 1.4.4.7 - Electronic Form Snippet
# Original created by Raymond Irving 15-Dec-2004.
# Version 1.3+ extended by Jelle Jager (TobyL) September 2006
# -----------------------------------------------------
# Captcha image support - thanks to Djamoer
# Multi checkbox, radio, select support - thanks to Djamoer
# Form Parser and extened validation - by Jelle Jager
#
# see eform/docs/eform.htm for history, usage and examples
#
# Extended for SMTP by David Bunker (bunk58) 4th Feb 2011
# Example snippet call...
# [!eForm? &formid=`contact-form` &subject=`[+subject+]` &smtp=`true` &smtp_pass=`password` &smtp_user=`you@your-domian.co.uk` &smtp_host=`smtp.your-domian.co.uk` &smtp_port=`587` &to=`you@your-domian.co.uk` &tpl=`contact-form` &report=`contact-report` &thnaks=`contact-thanks`!]
#
# PMS mods...
# bugfix: Format of the email injection attempt warning email was not correctly set.
# The submitted data is now escaped before being inserted into the email injection
# attempt warning email.
# Added the safestring datatype and made it the default should none be specified.
# Stripping of valid tags is now recursive for added protection against code injection.
# Set Snippet Paths
$snipFolder = isset($snipFolder)?$snipFolder:'eform';
$snipPath = $modx->config["base_path"].'assets/snippets/'.$snipFolder.'/';


# check if inside manager
if ($modx->isBackend()) {
return ''; # don't go any further when inside manager
}

//tidying up some casing errors in parameters
if(isset($eformOnValidate)) $eFormOnValidate = $eformOnValidate;
if(isset($eformOnBeforeMailSent)) $eFormOnBeforeMailSent = $eformOnBeforeMailSent;
if(isset($eformOnMailSent)) $eFormOnMailSent = $eformOnMailSent;
if(isset($eformOnValidate)) $eFormOnValidate = $eformOnValidate;
if(isset($eformOnBeforeFormMerge)) $eFormOnBeforeFormMerge = $eformOnBeforeFormMerge;
if(isset($eformOnBeforeFormParse)) $eFormOnBeforeFormParse = $eformOnBeforeFormParse;
//for sottwell :)
if(isset($eFormCSS)) $cssStyle = $eFormCSS;
//another for/from Susan :)
$config = isset($config) ? "assets/snippets/eform/configs/$config.config.php" : "assets/snippets/eform/configs/default.config.php";
        if (file_exists($config)) {
        include_once("$config");
        }
# Snippet customize settings
$params = array (
   // Snippet Path
   'snipPath' => $snipPath, //includes $snipFolder
   'snipFolder' => $snipFolder,

// eForm Params
    //Start added by David Bunker
    /* some servers have the PHP 'sendmail_from' value default to 'me@example.com' the new sender parameter enables you to override it so your email header will have an accurate 'Return-Path' value */
   'sender' => isset($sender)? $sender:$modx->config['emailsender'],
   /* new SMTP parameters */   
   'smtp' => isset($smtp)? $smtp:false,
   'smtp_host' => isset($smtp_host)? $smtp_host:'',
   'smtp_port' => isset($smtp_port)? $smtp_port:25,
   'smtp_auth' => isset($smtp_auth)? $smtp_auth:'true',
   'smtp_user' => isset($smtp_user)? $smtp_user:$modx->config['emailsender'],
   'smtp_pass' => isset($smtp_pass)? $smtp_pass:'',
    //End added by  David Bunker
   'vericode' => isset($vericode)? $vericode:"",
   'formid' => isset($formid)? $formid:"",
   'from' => isset($from)? $from:$modx->config['emailsender'],
   'fromname' => isset($fromname)? $fromname:$modx->config['site_name'],
   'to' => isset($to)? $to:$modx->config['emailsender'],
   'cc' => isset($cc)? $cc:"",
   'bcc' => isset($bcc)? $bcc:"",
   'subject' => isset($subject)? $subject:"",
   'ccsender' => isset($ccsender)?$ccsender:0,
   'sendirect' => isset($sendirect)? $sendirect:0,
   'mselector' => isset($mailselector)? $mailselector:0,
   'mobile' => isset($mobile)? $mobile:'',
   'mobiletext' => isset($mobiletext)? $mobiletext:'',
   'autosender' => isset($autosender)? $autosender:$from,
   'autotext' => isset($automessage)? $automessage:"",
   'category' => isset($category)? $category:0,
   'keywords' => isset($keywords)? $keywords:"",
   'gid' => isset($gotoid)? $gotoid:$modx->documentIdentifier,
   'noemail' => isset($noemail)? ($noemail):false,
   'saveform' => isset($saveform)? ($saveform? true:false):true,
   'tpl' => isset($tpl)? $tpl:"",
   'report' => isset($report)? $report:"",
   'allowhtml' => isset($allowhtml)? $allowhtml:0,
   //Added by JJ
   'replyto' => isset($replyto)? $replyto:"",
   'language' => isset($language)? $language:$modx->config['manager_language'],
   'thankyou' => isset($thankyou)? $thankyou:"form-thanks",
   'isDebug' => isset($debug)? $debug:0,
   'reportAbuse' => isset($reportAbuse)? $reportAbuse:false,
   'disclaimer' => isset($disclaimer)?$disclaimer:'',
   'sendAsHtml' => isset($sendAsHtml)?$sendAsHtml:false,
   'sendAsText' => isset($sendAsText)?$sendAsText:false,
   'sessionVars' => isset($sessionVars)?$sessionVars:false,
   'postOverides' => isset($postOverides)?$postOverides:0,
   'eFormOnBeforeMailSent' => isset($eFormOnBeforeMailSent)?$eFormOnBeforeMailSent:'',
   'eFormOnMailSent' => isset($eFormOnMailSent)?$eFormOnMailSent:'',
   'eFormOnValidate' => isset($eFormOnValidate)?$eFormOnValidate:'',
   'eFormOnBeforeFormMerge' => isset($eFormOnBeforeFormMerge)?$eFormOnBeforeFormMerge:'',
   'eFormOnBeforeFormParse' => isset($eFormOnBeforeFormParse)?$eFormOnBeforeFormParse:'',
   'cssStyle' => isset($cssStyle)?$cssStyle:'',
   'jScript' => isset($jScript)?$jScript:'',
   'submitLimit' => (isset($submitLimit) &&  is_numeric($submitLimit))?$submitLimit*60:0,
   'protectSubmit' => isset($protectSubmit)?$protectSubmit:1,
   'requiredClass' => isset($requiredClass)?$requiredClass:"required",
   'invalidClass' => isset($invalidClass)?$invalidClass:"invalid",
   'runSnippet' => ( isset($runSnippet) && !is_numeric($runSnippet) )?$runSnippet:'',
   'autoSenderName' => isset($autoSenderName)?$autoSenderName:'',
   'version' => '1.4.4.7'
);

// pixelchutes PHx workaround
foreach( $params as $key=>$val ) $params[ $key ] = str_replace( array('((','))'), array('[+','+]'), $val );

# Start processing

include_once ($snipPath."eform.inc.php");

$output = eForm($modx,$params);

# Return
return $output;
?>
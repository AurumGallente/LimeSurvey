<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * LimeSurvey (tm)
 * Copyright (C) 2011 The LimeSurvey Project Team / Carsten Schmitz
 * All rights reserved.
 * License: GNU/GPL License v2 or later, see LICENSE.php
 * LimeSurvey is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 *
 * 
 */
 
 class templates extends AdminController {
    
    function __construct()
	{
		parent::__construct();
	}
    
    
    function view($editfile='startpage.pstpl', $screenname='welcome', $templatename='default')
    {
        
        //self::_js_admin_includes(base_url().'scripts/admin/edit_area/edit_area_loader.js');
        self::_js_admin_includes(base_url().'scripts/admin/templates.js');
        
        self::_getAdminHeader();
        self::_initTemplateInfo($templatename, $screenname, $editfile);
        
        self::_loadEndScripts();
                
                
	   self::_getAdminFooter("http://docs.limesurvey.org", $this->limesurvey_lang->gT("LimeSurvey online manual"));
        
        if ($screenname != 'welcome') {$this->session->set_userdata('step',1);} else {$this->session->unset_userdata('step');} //This helps handle the load/save buttons)
        
        
    }
    //temporary solution to th bug that crashes LS!
    function screenredirect($editfile='startpage.pstpl', $templatename='default', $screenname='welcome')
    {
        redirect("admin/templates/view/".$editfile."/".$screenname."/".$templatename,'refresh');
    }
    //temporary solution to th bug that crashes LS!
    function fileredirect($templatename='default', $screenname='welcome', $editfile='startpage.pstpl')
    {
        redirect("admin/templates/view/".$editfile."/".$screenname."/".$templatename,'refresh');
    }
    
    function _templateditorbar($codelanguage,$highlighter,$flashmessage,$templatename,$templates,$editfile,$screenname)
    {
        $data['clang'] = $this->limesurvey_lang;
        $data['codelanguage'] = $codelanguage;
        $data['highlighter'] = $highlighter;
        //$data['allowedtemplateuploads'] = $this->config->item('allowedtemplateuploads');
        $data['flashmessage'] = $flashmessage;
        $data['templatename'] = $templatename;
        $data['templates'] = $templates;
        $data['editfile'] = $editfile;
        $data['screenname'] = $screenname;
        
        $this->load->view("admin/Templates/templateeditorbar_view",$data);
        
    }
    
    function _templatebar($screenname,$editfile,$screens,$tempdir,$templatename)
    {
        $data['clang'] = $this->limesurvey_lang;
        $data['screenname'] = $screenname;
        $data['editfile'] = $editfile;
        $data['screens'] = $screens;
        $data['tempdir'] = $tempdir;
        $data['templatename'] = $templatename;
        $data['usertemplaterootdir'] = $this->config->item('usertemplaterootdir');
        
        $this->load->view("admin/Templates/templatebar_view",$data);
        
    }
    
    function _templatesummary($templatename,$screenname,$editfile,$templates,$files,$cssfiles,$otherfiles,$myoutput)
    {
        
        $tempdir = $this->config->item("tempdir");
        $tempurl = $this->config->item("tempurl");
        
        $this->load->helper("admin/template");
        $data = array();
        if (is_template_editable($templatename)==true)
        {              
            
            // prepare textarea class for optional javascript
            $templateclasseditormode='full'; // default
	        if ($this->session->userdata('templateeditormode')=='none'){$templateclasseditormode='none';}
            $data['templateclasseditormode'] = $templateclasseditormode;

                                   // The following lines are forcing the browser to refresh the templates on each save
            $time=date("ymdHis");
            @$fnew=fopen("$tempdir/template_temp_$time.html", "w+");
            $data['time'] = $time;
            
            if(!$fnew)
            {
                $data['filenotwritten'] =  true;
            }
            else
            {
                @fwrite ($fnew, getHeader());
                foreach ($cssfiles as $cssfile)
                {
                    $myoutput=str_replace($cssfile['name'],$cssfile['name']."?t=$time",$myoutput);
                }
                    
                foreach($myoutput as $line) {
               	    @fwrite($fnew, $line);
                }
                @fclose($fnew);
                //$langdir_template="$publicurl/locale/".$_SESSION['adminlang']."/help";
                    //$templatesoutput.= "<p>\n"."<iframe id='previewiframe' src='$tempurl/template_temp_$time.html' width='95%' height='768' name='previewiframe' style='background-color: white;'>Embedded Frame</iframe>\n"
                    //."</div>\n";
            }
        
        
        }
        
        
        $data['clang'] = $this->limesurvey_lang;
        $data['screenname'] = $screenname;
        $data['editfile'] = $editfile;
       
        $data['tempdir'] = $tempdir;
        $data['templatename'] = $templatename;
        $data['templates'] = $templates;
        $data['files'] = $files;
        $data['cssfiles'] = $cssfiles;
        $data['otherfiles'] = $otherfiles;
        $data['tempurl'] = $tempurl;
        
        $this->load->view("admin/Templates/templatesummary_view",$data);
        
    }
    
    function _initTemplateInfo($templatename, $screenname, $editfile)
    {
        global $siteadminname, $siteadminemail;
        $clang = $this->limesurvey_lang;
        $this->load->helper('admin/template');
        //Standard Template Subfiles
        //Only these files may be edited or saved
        $files[]=array('name'=>'assessment.pstpl');
        $files[]=array('name'=>'clearall.pstpl');
        $files[]=array('name'=>'completed.pstpl');
        $files[]=array('name'=>'endgroup.pstpl');
        $files[]=array('name'=>'endpage.pstpl');
        $files[]=array('name'=>'groupdescription.pstpl');
        $files[]=array('name'=>'load.pstpl');
        $files[]=array('name'=>'navigator.pstpl');
        $files[]=array('name'=>'printanswers.pstpl');
        $files[]=array('name'=>'privacy.pstpl');
        $files[]=array('name'=>'question.pstpl');
        $files[]=array('name'=>'register.pstpl');
        $files[]=array('name'=>'save.pstpl');
        $files[]=array('name'=>'surveylist.pstpl');
        $files[]=array('name'=>'startgroup.pstpl');
        $files[]=array('name'=>'startpage.pstpl');
        $files[]=array('name'=>'survey.pstpl');
        $files[]=array('name'=>'welcome.pstpl');
        $files[]=array('name'=>'print_survey.pstpl');
        $files[]=array('name'=>'print_group.pstpl');
        $files[]=array('name'=>'print_question.pstpl');
        
        //Standard CSS Files
        //These files may be edited or saved
        $cssfiles[]=array('name'=>'template.css');
        $cssfiles[]=array('name'=>'template-rtl.css');
        $cssfiles[]=array('name'=>'ie_fix_6.css');
        $cssfiles[]=array('name'=>'ie_fix_7.css');
        $cssfiles[]=array('name'=>'ie_fix_8.css');
        $cssfiles[]=array('name'=>'print_template.css');
        $cssfiles[]=array('name'=>'template.js');
        
        //Standard Support Files
        //These files may be edited or saved
        $supportfiles[]=array('name'=>'print_img_radio.png');
        $supportfiles[]=array('name'=>'print_img_checkbox.png');
        
        //Standard screens
        //Only these may be viewed
        
        $screens[]=array('name'=>$clang->gT('Survey List Page'),'id'=>'surveylist');
        $screens[]=array('name'=>$clang->gT('Welcome Page'),'id'=>'welcome');
        $screens[]=array('name'=>$clang->gT('Question Page'),'id'=>'question');
        $screens[]=array('name'=>$clang->gT('Completed Page'),'id'=>'completed');
        $screens[]=array('name'=>$clang->gT('Clear All Page'),'id'=>'clearall');
        $screens[]=array('name'=>$clang->gT('Register Page'),'id'=>'register');
        $screens[]=array('name'=>$clang->gT('Load Page'),'id'=>'load');
        $screens[]=array('name'=>$clang->gT('Save Page'),'id'=>'save');
        $screens[]=array('name'=>$clang->gT('Print answers page'),'id'=>'printanswers');
        $screens[]=array('name'=>$clang->gT('Printable survey page'),'id'=>'printablesurvey');
        
        //Page display blocks
        $SurveyList=array('startpage.pstpl',
                          'surveylist.pstpl', 
                          'endpage.pstpl'
                          );
        $Welcome=array('startpage.pstpl',
                       'welcome.pstpl', 
                       'privacy.pstpl', 
                       'navigator.pstpl', 
                       'endpage.pstpl'
                       );
        $Question=array('startpage.pstpl',
                        'survey.pstpl', 
                        'startgroup.pstpl', 
                        'groupdescription.pstpl',  
                        'question.pstpl', 
                        'endgroup.pstpl', 
                        'navigator.pstpl', 
                        'endpage.pstpl'
                        );
        $CompletedTemplate=array(
                        'startpage.pstpl', 
                        'assessment.pstpl', 
                        'completed.pstpl', 
                        'endpage.pstpl'
                        );
        $Clearall=array('startpage.pstpl',
                        'clearall.pstpl', 
                        'endpage.pstpl'
                        );
        $Register=array('startpage.pstpl',
                        'survey.pstpl', 
                        'register.pstpl', 
                        'endpage.pstpl'
                        );
        $Save=array('startpage.pstpl',
                    'save.pstpl', 
                    'endpage.pstpl'
                    );
        $Load=array('startpage.pstpl',
                    'load.pstpl', 
                    'endpage.pstpl'
                    );
        $printtemplate=array('startpage.pstpl',
                             'printanswers.pstpl', 
                             'endpage.pstpl'
                             );
        $printablesurveytemplate=array('print_survey.pstpl',
                                       'print_group.pstpl', 
                                       'print_question.pstpl'
                                       );
        
        
        
        
        
        $file_version="LimeSurvey template editor ".$this->config->item('versionnumber');
        $this->session->set_userdata('s_lang', $this->session->userdata('adminlang'));
        
        $templatename = sanitize_paranoid_string($templatename);
        //if (!isset($templatedir)) {$templatedir = sanitize_paranoid_string(returnglobal('templatedir'));}
        $editfile = sanitize_filename($editfile);
        $screenname=auto_unescape($screenname);
        
        // Checks if screen name is in the list of allowed screen names
        if ( multiarray_search($screens,'id',$screenname)===false) {die('Invalid screen name');}  // Die you sneaky bastard! haha :P
        
        
        if (!isset($action)) {$action=sanitize_paranoid_string(returnglobal('action'));}
        if (!isset($subaction)) {$subaction=sanitize_paranoid_string(returnglobal('subaction'));}
        //if (!isset($otherfile)) {$otherfile = sanitize_filename(returnglobal('otherfile'));}
        if (!isset($newname)) {$newname = sanitize_paranoid_string(returnglobal('newname'));}
        if (!isset($copydir)) {$copydir = sanitize_paranoid_string(returnglobal('copydir'));}
        
        if(is_file($this->config->item('usertemplaterootdir').'/'.$templatename.'/question_start.pstpl')) 
        {
           $files[]=array('name'=>'question_start.pstpl');
           $Question[]='question_start.pstpl';
        }
        
        $availableeditorlanguages=array('bg','cs','de','dk','en','eo','es','fi','fr','hr','it','ja','mk','nl','pl','pt','ru','sk','zh');
        $extension = substr(strrchr($editfile, "."), 1);
        if ($extension=='css' || $extension=='js') {$highlighter=$extension;} else {$highlighter='html';};
        if(in_array($this->session->userdata('adminlang'),$availableeditorlanguages)) {$codelanguage=$this->session->userdata('adminlang');}
        else  {$codelanguage='en';}
        
        if ($this->input->post('changes')) {
           $changedtext=$this->input->post('changes');
           $changedtext=str_replace ('<?','',$changedtext);
           if(get_magic_quotes_gpc())
           {
               $changedtext = stripslashes($changedtext);
           }
        }
        
        if ($this->input->post('changes_cp')) {
           $changedtext=$this->input->post('changes_cp');
           $changedtext=str_replace ('<?','',$changedtext);
           if(get_magic_quotes_gpc())
           {
               $changedtext = stripslashes($changedtext);
           }
        }
        
        $templates=gettemplatelist();
        if (!isset($templates[$templatename]))
        {
           $templatename = $this->config->item('defaulttemplate');
        }
        
        $normalfiles=array("DUMMYENTRY", ".", "..", "preview.png");
        foreach ($files as $fl) {
           $normalfiles[]=$fl["name"];
        }
        foreach ($cssfiles as $fl) {
           $normalfiles[]=$fl["name"];
        }
        
        
        // Set this so common.php doesn't throw notices about undefined variables
        $thissurvey['active']='N';
        
        // ===========================   FAKE DATA FOR TEMPLATES
        $thissurvey['name']=$clang->gT("Template Sample");
        $thissurvey['description']=$clang->gT('This is a sample survey description. It could be quite long.').'<br /><br />'.$clang->gT("But this one isn't.");
        $thissurvey['welcome']=$clang->gT('Welcome to this sample survey').'<br />'.$clang->gT('You should have a great time doing this').'<br />';
        $thissurvey['allowsave']="Y";
        $thissurvey['active']="Y";
        $thissurvey['tokenanswerspersistence']="Y";
        $thissurvey['templatedir']=$templatename;
        $thissurvey['format']="G";
        $thissurvey['surveyls_url']="http://www.limesurvey.org/";
        $thissurvey['surveyls_urldescription']=$clang->gT("Some URL description");
        $thissurvey['usecaptcha']="A";
        $percentcomplete=makegraph(6, 10);
        $groupname=$clang->gT("Group 1: The first lot of questions");
        $groupdescription=$clang->gT("This group description is fairly vacuous, but quite important.");
        $navigator="<input class=\"submit\" type=\"submit\" value=\"".$clang->gT('Next')."&gt;&gt;\" name=\"move\" />\n";
        if ($screenname != 'welcome') {$navigator = "<input class=\"submit\" type=\"submit\" value=\"&lt;&lt;".$clang->gT('Previous')."\" name=\"move\" />\n".$navigator;}
        $help=$clang->gT("This is some help text.");
        $totalquestions="10";
        $surveyformat="Format";
        $completed = "<br /><span class='success'>".$clang->gT("Thank you!")."</span><br /><br />"
        .$clang->gT("Your survey responses have been recorded.")."<br /><br />\n";
        $notanswered="5";
        $privacy="";
        $surveyid="1295";
        $token=1234567;
        $assessments="<table align='center'><tr><th>".$clang->gT("Assessment heading")."</th></tr><tr><td align='center'>".$clang->gT("Assessment details")."<br />".$clang->gT("Note that this assessment section will only show if assessment rules have been set and assessment mode is activated.")."</td></tr></table>";
        $printoutput="<span class='printouttitle'><strong>".$clang->gT("Survey name (ID)")."</strong> Test survey (46962)</span><br />
        <table class='printouttable' >
        <tr><th>".$clang->gT("Question")."</th><th>".$clang->gT("Your answer")."</th></tr>
            <tr>
                <td>id</td>
                <td>12</td>
            </tr>
            <tr>
                <td>Date Submitted</td>
        
                <td>1980-01-01 00:00:00</td>
            </tr>
            <tr>
                <td>This is a sample question text. The user was asked to enter a date.</td>
                <td>2007-11-06</td>
            </tr>
            <tr>
                <td>This is another sample question text - asking for number. </td>
                <td>666</td>
            </tr>
            <tr>
                <td>This is one last sample question text - asking for some free text. </td>
                <td>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum</td>
            </tr>
        </table>";
        
        $addbr=false;
        
        $templatedir=sGetTemplatePath($templatename);
        $templateurl=sGetTemplateURL($templatename);
        
        switch($screenname) {
            case 'surveylist':
                unset($files);
        
                $list[]="<li class='surveytitle'><a href='#'>Survey Number 1</a></li>\n";
                $list[]="<li class='surveytitle'><a href='#'>Survey Number 2</a></li>\n";
        
                $surveylist=array(
                "nosid"=>$clang->gT("You have not provided a survey identification number"),
                "contact"=>sprintf($clang->gT("Please contact %s ( %s ) for further assistance."),$siteadminname,$siteadminemail),
                "listheading"=>$clang->gT("The following surveys are available:"),
                "list"=>implode("\n",$list),
                );
        
                $myoutput[]="";
                foreach ($SurveyList as $qs) {
                    $files[]=array("name"=>$qs);
                    $myoutput = array_merge($myoutput, doreplacement(sGetTemplatePath($templatename)."/$qs"));
                    
                }
                break;
        
            case 'question':
                unset($files);
                foreach ($Question as $qs) {
                   $files[]=array("name"=>$qs);
                }
                $myoutput[]="<meta http-equiv=\"expires\" content=\"Wed, 26 Feb 1997 08:21:57 GMT\" />\n";
                $myoutput[]="<meta http-equiv=\"Last-Modified\" content=\"".gmdate('D, d M Y H:i:s'). " GMT\" />\n";
                $myoutput[]="<meta http-equiv=\"Cache-Control\" content=\"no-store, no-cache, must-revalidate\" />\n";
                $myoutput[]="<meta http-equiv=\"Cache-Control\" content=\"post-check=0, pre-check=0, false\" />\n";
                $myoutput[]="<meta http-equiv=\"Pragma\" content=\"no-cache\" />\n";
                $myoutput = array_merge($myoutput, doreplacement(sGetTemplatePath($templatename)."/startpage.pstpl"));
                $myoutput = array_merge($myoutput, doreplacement(sGetTemplatePath($templatename)."/survey.pstpl"));
                $myoutput = array_merge($myoutput, doreplacement(sGetTemplatePath($templatename)."/startgroup.pstpl"));
                $myoutput = array_merge($myoutput, doreplacement(sGetTemplatePath($templatename)."/groupdescription.pstpl"));
        
                $question = array(
        		         'all' => 'How many roads must a man walk down?'
        		         ,'text' => 'How many roads must a man walk down?'
        		         ,'code' => '1a'
        		         ,'help' => 'helpful text'
        		         ,'mandatory' => ''
        		         ,'man_message' => ''
        		         ,'valid_message' => ''
        		         ,'file_valid_message' => ''
        		         ,'essentials' => 'id="question1"'
        		         ,'class' => 'list-radio'
        		         ,'man_class' => ''
        		         ,'input_error_class' => ''
        		         ,'number' => '1'
        		         );
        
        		$answer="<ul><li><input type='radio' class='radiobtn' name='1' value='1' id='radio1' /><label class='answertext' for='radio1'>One</label></li><li><input type='radio' class='radiobtn' name='1' value='2' id='radio2' /><label class='answertext' for='radio2'>Two</label></li><li><input type='radio' class='radiobtn' name='1' value='3' id='radio3' /><label class='answertext' for='radio3'>Three</label></li></ul>\n";
                $myoutput = array_merge($myoutput, doreplacement(sGetTemplatePath($templatename)."/question.pstpl"));
        
        	    //	$question='<span class="asterisk">*</span>'.$clang->gT("Please explain something in detail:");
                $answer="<textarea class='textarea' rows='5' cols='40'>Some text in this answer</textarea>";
        	    $question = array(
        		  'all' => '<span class="asterisk">*</span>'.$clang->gT("Please explain something in detail:")
        		 ,'text' => $clang->gT('Please explain something in detail:')
        		 ,'code' => '2a'
        		 ,'help' => ''
        		 ,'mandatory' => $clang->gT('*')
        		 ,'man_message' => ''
        		 ,'valid_message' => ''
        		 ,'file_valid_message' => ''
        		 ,'essentials' => 'id="question2"'
        		 ,'class' => 'text-long'
        		 ,'man_class' => 'mandatory'
        		 ,'input_error_class' => ''
        		 ,'number' => '2'
        		 );
                $myoutput = array_merge($myoutput, doreplacement(sGetTemplatePath($templatename)."/question.pstpl"));
                $myoutput = array_merge($myoutput, doreplacement(sGetTemplatePath($templatename)."/endgroup.pstpl"));
                $myoutput = array_merge($myoutput, doreplacement(sGetTemplatePath($templatename)."/navigator.pstpl"));
                $myoutput = array_merge($myoutput, doreplacement(sGetTemplatePath($templatename)."/endpage.pstpl"));
        		break;
        
            case 'welcome':
            
                unset($files);
                
                $myoutput[]="";
                foreach ($Welcome as $qs) {
                    $files[]=array("name"=>$qs);
                    $myoutput = array_merge($myoutput, doreplacement(sGetTemplatePath($templatename)."/$qs"));
                    
                }
                break;
        
            case 'register':
                unset($files);
                foreach($Register as $qs) {
                   $files[]=array("name"=>$qs);
                }
                foreach(file("$templatedir/startpage.pstpl") as $op)
                {
                   $myoutput[]=templatereplace($op);
                }
                foreach(file("$templatedir/survey.pstpl") as $op)
                {
                   $myoutput[]=templatereplace($op);
                }
                foreach(file("$templatedir/register.pstpl") as $op)
                {
                   $myoutput[]=templatereplace($op);
                }
                foreach(file("$templatedir/endpage.pstpl") as $op)
                {
                   $myoutput[]=templatereplace($op);
                }
                $myoutput[]= "\n";
                break;
        
            case 'save':
                unset($files);
                foreach($Save as $qs) {
                   $files[]=array("name"=>$qs);
                }
        
                foreach(file("$templatedir/startpage.pstpl") as $op)
                {
                   $myoutput[]=templatereplace($op);
                }
                foreach(file("$templatedir/save.pstpl") as $op)
                {
                   $myoutput[]=templatereplace($op);
                }
                foreach(file("$templatedir/endpage.pstpl") as $op)
                {
                   $myoutput[]=templatereplace($op);
                }
                $myoutput[]= "\n";
                break;
        
            case 'load':
                unset($files);
                foreach($Load as $qs) {
                   $files[]=array("name"=>$qs);
                }
        
                foreach(file("$templatedir/startpage.pstpl") as $op)
                {
                   $myoutput[]=templatereplace($op);
                }
                foreach(file("$templatedir/load.pstpl") as $op)
                {
                   $myoutput[]=templatereplace($op);
                }
                foreach(file("$templatedir/endpage.pstpl") as $op)
                {
                   $myoutput[]=templatereplace($op);
                }
                $myoutput[]= "\n";
                break;
        
            case 'clearall':
                unset($files);
                foreach ($Clearall as $qs) {
                   $files[]=array("name"=>$qs);
                }
        
                foreach(file("$templatedir/startpage.pstpl") as $op)
                {
                   $myoutput[]=templatereplace($op);
                }
                foreach(file("$templatedir/clearall.pstpl") as $op)
                {
                   $myoutput[]=templatereplace($op);
                }
                foreach(file("$templatedir/endpage.pstpl") as $op)
                {
                   $myoutput[]=templatereplace($op);
                }
                $myoutput[]= "\n";
                break;
        
            case 'completed':
                unset($files);
                $myoutput[]="";
                foreach ($CompletedTemplate as $qs) {
                    $files[]=array("name"=>$qs);
                    $myoutput = array_merge($myoutput, doreplacement(sGetTemplatePath($templatename)."/$qs"));
                }
                break;
        
            case 'printablesurvey':
                unset($files);
                foreach ($printablesurveytemplate as $qs) {
                   $files[]=array("name"=>$qs);
                }
        
                $questionoutput=array();
                foreach(file("$templatedir/print_question.pstpl") as $op)
                { // echo '<pre>line '.__LINE__.'$op = '.htmlspecialchars(print_r($op)).'</pre>';
                    $questionoutput[]=templatereplace($op, array(
                         'QUESTION_NUMBER'=>'1',
                         'QUESTION_CODE'=>'Q1',
                         'QUESTION_MANDATORY' => $clang->gT('*'),
                         'QUESTION_SCENARIO' => 'Only answer this if certain conditions are met.',    // if there are conditions on a question, list the conditions.
                         'QUESTION_CLASS' => ' mandatory list-radio',
                         'QUESTION_TYPE_HELP' => $clang->gT('Please choose *only one* of the following:'),
                         'QUESTION_MAN_MESSAGE' => '',        // (not sure if this is used) mandatory error
                         'QUESTION_VALID_MESSAGE' => '',        // (not sure if this is used) validation error
                         'QUESTION_FILE_VALID_MESSAGE' => '',        // (not sure if this is used) file validation error
                         'QUESTION_TEXT'=>'This is a sample question text. The user was asked to pick an entry.',
                         'QUESTIONHELP'=>'This is some help text for this question.',
                         'ANSWER'=>'<ul>
                                        <li>
                                            <img src="'.$templateurl.'/print_img_radio.png" alt="First choice" class="input-radio" height="14" width="14">First choice
                                        </li>
                                        <li>
                                            <img src="'.$templateurl.'/print_img_radio.png" alt="Second choice" class="input-radio" height="14" width="14">Second choice
                                        </li>
                                        <li>
                                            <img src="'.$templateurl.'/print_img_radio.png" alt="Third choice" class="input-radio" height="14" width="14">Third choice
                                        </li>
                                    </ul>'
                         ));
                }
                $groupoutput=array();
                foreach(file("$templatedir/print_group.pstpl") as $op)
                {
                    $groupoutput[]=templatereplace($op, array('QUESTIONS'=>implode(' ',$questionoutput)));
                }
                foreach(file("$templatedir/print_survey.pstpl") as $op)
                {
                   $myoutput[]=templatereplace($op, array('GROUPS'=>implode(' ',$groupoutput),
                           'FAX_TO' => $clang->gT("Please fax your completed survey to:")." 000-000-000",
                           'SUBMIT_TEXT'=> $clang->gT("Submit your survey."),
                           'HEADELEMENTS'=>getPrintableHeader(),
                           'SUBMIT_BY' => sprintf($clang->gT("Please submit by %s"), date('d.m.y')),
                           'THANKS'=>$clang->gT('Thank you for completing this survey.'),
                           'END'=>$clang->gT('This is the survey end message.')
                   ));
                }
                break;
        
            case 'printanswers':
                unset($files);
                foreach ($printtemplate as $qs) 
                {
                   $files[]=array("name"=>$qs);
                }
                foreach(file("$templatedir/startpage.pstpl") as $op)
                {
                   $myoutput[]=templatereplace($op);
                }
                foreach(file("$templatedir/printanswers.pstpl") as $op)
                {
                   $myoutput[]=templatereplace($op,array('ANSWERTABLE'=>$printoutput));
                }
                foreach(file("$templatedir/endpage.pstpl") as $op)
                {
                   $myoutput[]=templatereplace($op);
                }
                $myoutput[]= "\n";
                break;
        }
        $myoutput[]="</html>";
        
        if (is_array($files)) {
           $match=0;
           foreach ($files as $f) {
               if ($editfile == $f["name"]) {
                   $match=1;
               }
           }
           foreach ($cssfiles as $f) {
               if ($editfile == $f["name"]) {
                   $match=1;
               }
           }
           if ($match == 0) {
               if (count($files) > 0) {
                   $editfile=$files[0]["name"];
               } else {
                   $editfile="";
               }
           }
        }
    
        //Get list of 'otherfiles'
        $otherfiles=array();
        if ($handle = opendir($templatedir)) {
           while(false !== ($file = readdir($handle))) {
               if (!array_search($file, $normalfiles)) {
                   if (!is_dir($templatedir.DIRECTORY_SEPARATOR.$file)) {
                       $otherfiles[]=array("name"=>$file);
                   }
               }
           } // while
           closedir($handle);
        }
        
        self::_templateditorbar($codelanguage,$highlighter,$this->session->userdata('flashmessage'),$templatename,$templates,$editfile,$screenname);
        
        self::_templatebar($screenname,$editfile,$screens,$this->config->item('tempdir'),$templatename);
        self::_templatesummary($templatename,$screenname,$editfile,$templates,$files,$cssfiles,$otherfiles,$myoutput);
        
        
    }
    
    
    
    
    
    
 }
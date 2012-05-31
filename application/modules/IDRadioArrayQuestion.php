<?php
class IDRadioArrayQuestion extends RadioArrayQuestion
{
    public function getAnswerHTML()
    {
        global $thissurvey;
        global $notanswered;
        $extraclass ="";
        $clang = Yii::app()->lang;

        $checkconditionFunction = "checkconditions";

        $qquery = "SELECT other FROM {{questions}} WHERE qid=".$this->id." AND language='".$_SESSION['survey_'.Yii::app()->getConfig('surveyID')]['s_lang']."'";
        $qresult = dbExecuteAssoc($qquery);   //Checked
        $aQuestionAttributes = $this->getAttributeValues();
        if (trim($aQuestionAttributes['answer_width'])!='')
        {
            $answerwidth=$aQuestionAttributes['answer_width'];
        }
        else
        {
            $answerwidth = 20;
        }
        $cellwidth  = 3; // number of columns
        if ($this->mandatory != 'Y' && SHOW_NO_ANSWER == 1) //Question is not mandatory
        {
            ++$cellwidth; // add another column
        }
        $cellwidth = round((( 100 - $answerwidth ) / $cellwidth) , 1); // convert number of columns to percentage of table width

        foreach($qresult->readAll() as $qrow)
        {
            $other = $qrow['other'];
        }
        $ansresult = $this->getChildren();
        $anscount = count($ansresult);

        $fn = 1;

        $answer = "\n<table class=\"question subquestions-list questions-list {$extraclass}\" summary=\"".str_replace('"','' ,strip_tags($this->text))." - Increase/Same/Decrease Likert scale array\">\n"
        . "\t<colgroup class=\"col-responses\">\n"
        . "\t<col class=\"col-answers\" width=\"$answerwidth%\" />\n";

        $odd_even = '';
        for ($xc=1; $xc<=3; $xc++)
        {
            $odd_even = alternation($odd_even);
            $answer .= "<col class=\"$odd_even\" width=\"$cellwidth%\" />\n";
        }
        if ($this->mandatory != 'Y' && SHOW_NO_ANSWER == 1) //Question is not mandatory
        {
            $odd_even = alternation($odd_even);
            $answer .= "<col class=\"col-no-answer $odd_even\" width=\"$cellwidth%\" />\n";
        }
        $answer .= "\t</colgroup>\n"
        . "\t<thead>\n"
        . "<tr>\n"
        . "\t<td>&nbsp;</td>\n"
        . "\t<th>".$clang->gT('Increase')."</th>\n"
        . "\t<th>".$clang->gT('Same')."</th>\n"
        . "\t<th>".$clang->gT('Decrease')."</th>\n";
        if ($this->mandatory != 'Y' && SHOW_NO_ANSWER == 1) //Question is not mandatory
        {
            $answer .= "\t<th>".$clang->gT('No answer')."</th>\n";
        }
        $answer .= "</tr>\n"
        ."\t</thead>\n";
        $answer_body = '<tbody>';
        $trbc = '';
        foreach($ansresult as $ansrow)
        {
            $myfname = $this->fieldname.$ansrow['title'];
            $answertext = dTexts__run($ansrow['question']);
            /* Check if this item has not been answered: the 'notanswered' variable must be an array,
            containing a list of unanswered questions, the current question must be in the array,
            and there must be no answer available for the item in this session. */
            if ($this->mandatory=='Y' && (is_array($notanswered)) && (array_search($myfname, $notanswered) !== FALSE) && ($_SESSION['survey_'.Yii::app()->getConfig('surveyID')][$myfname] == "") )
            {
                $answertext = "<span class=\"errormandatory\">{$answertext}</span>";
            }

            $trbc = alternation($trbc , 'row');

            // Get array_filter stuff
            list($htmltbody2, $hiddenfield)=return_array_filter_strings($this, $aQuestionAttributes, $thissurvey, $ansrow, $myfname, $trbc, $myfname,'tr',"$trbc answers-list radio-list");

            $answer_body .= $htmltbody2;

            $answer_body .= "\t<th class=\"answertext\">\n"
            . "$answertext\n"
            . $hiddenfield
            . "<input type=\"hidden\" name=\"java$myfname\" id=\"java$myfname\" value=\"";
            if (isset($_SESSION['survey_'.Yii::app()->getConfig('surveyID')][$myfname]))
            {
                $answer_body .= $_SESSION['survey_'.Yii::app()->getConfig('surveyID')][$myfname];
            }
            $answer_body .= "\" />\n\t</th>\n";

            $answer_body .= "\t<td class=\"answer_cell_I answer-item radio-item\">\n"
            . "<label for=\"answer$myfname-I\">\n"
            ."\t<input class=\"radio\" type=\"radio\" name=\"$myfname\" id=\"answer$myfname-I\" value=\"I\" title=\"".$clang->gT('Increase').'"';
            if (isset($_SESSION['survey_'.Yii::app()->getConfig('surveyID')][$myfname]) && $_SESSION['survey_'.Yii::app()->getConfig('surveyID')][$myfname] == 'I')
            {
                $answer_body .= CHECKED;
            }

            $answer_body .= " onclick=\"$checkconditionFunction(this.value, this.name, this.type)\" />\n"
            . "</label>\n"
            . "\t</td>\n"
            . "\t<td class=\"answer_cell_S answer-item radio-item\">\n"
            . "<label for=\"answer$myfname-S\">\n"
            . "\t<input class=\"radio\" type=\"radio\" name=\"$myfname\" id=\"answer$myfname-S\" value=\"S\" title=\"".$clang->gT('Same').'"';

            if (isset($_SESSION['survey_'.Yii::app()->getConfig('surveyID')][$myfname]) && $_SESSION['survey_'.Yii::app()->getConfig('surveyID')][$myfname] == 'S')
            {
                $answer_body .= CHECKED;
            }

            $answer_body .= " onclick=\"$checkconditionFunction(this.value, this.name, this.type)\" />\n"
            . "</label>\n"
            . "\t</td>\n"
            . "\t<td class=\"answer_cell_D answer-item radio-item\">\n"
            . "<label for=\"answer$myfname-D\">\n"
            . "\t<input class=\"radio\" type=\"radio\" name=\"$myfname\" id=\"answer$myfname-D\" value=\"D\" title=\"".$clang->gT('Decrease').'"';
            // --> END NEW FEATURE - SAVE
            if (isset($_SESSION['survey_'.Yii::app()->getConfig('surveyID')][$myfname]) && $_SESSION['survey_'.Yii::app()->getConfig('surveyID')][$myfname] == 'D')
            {
                $answer_body .= CHECKED;
            }

            $answer_body .= " onclick=\"$checkconditionFunction(this.value, this.name, this.type)\" />\n"
            . "</label>\n"
            . "<input type=\"hidden\" name=\"java$myfname\" id=\"java$myfname\" value=\"";

            if (isset($_SESSION['survey_'.Yii::app()->getConfig('surveyID')][$myfname])) {$answer_body .= $_SESSION['survey_'.Yii::app()->getConfig('surveyID')][$myfname];}
            $answer_body .= "\" />\n\t</td>\n";

            if ($this->mandatory != 'Y' && SHOW_NO_ANSWER == 1)
            {
                $answer_body .= "\t<td class=\"answer-item radio-item noanswer-item\">\n"
                . "<label for=\"answer$myfname-\">\n"
                . "\t<input class=\"radio\" type=\"radio\" name=\"$myfname\" id=\"answer$myfname-\" value=\"\" title=\"".$clang->gT('No answer').'"';
                if (!isset($_SESSION['survey_'.Yii::app()->getConfig('surveyID')][$myfname]) || $_SESSION['survey_'.Yii::app()->getConfig('surveyID')][$myfname] == '')
                {
                    $answer_body .= CHECKED;
                }
                $answer_body .= " onclick=\"$checkconditionFunction(this.value, this.name, this.type)\" />\n"
                . "</label>\n"
                . "\t</td>\n";
            }
            $answer_body .= "</tr>\n";
            $fn++;
        }
        $answer .=  $answer_body . "\t</tbody>\n</table>\n";
        return $answer;
    }
    
    //public function getInputNames() - inherited
    
    public function availableAttributes()
    {
        return array("answer_width","array_filter","array_filter_exclude","statistics_showgraph","statistics_graphtype","hide_tip","hidden","max_answers","min_answers","page_break","public_statistics","random_order","parent_order","scale_export","random_group");
    }
}
?>
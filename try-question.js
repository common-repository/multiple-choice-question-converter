function gmc_try_question(identifier)
{
	var not_attempted = 'not_attempted';
	var form_id = "form_" + identifier;
	var question_id = "question_" + identifier;
	var question_options_id = "question_options_" + identifier;
	var right_option_id = "right_option_" + identifier;
	var message_id = "message_" + identifier;
	mychoice = document.getElementById(question_options_id);
	if( mychoice.value == not_attempted )
	{
		alert("Not Attempted");
	}
	else
	{
		mychoice.disabled = true;
		alert( document.getElementById(message_id).value = "Your attemp was : " + mychoice.value + ", The answer is " +  document.getElementById(right_option_id).text );
	}
}
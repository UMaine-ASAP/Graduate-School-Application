$("history_form").validate ({
	onfocusout: true
	rules:
	{
		discplinary_violations
		{
			required: true
		}
		
		prev_um_app
		{
			required: true
		}
		
		gre_verbal
		{
			digits: true
		}
		
		gre_analytical
		{
			digits: true
		}
		
		gmat_score
		{
			digits: true
		}
		
		mat_score
		{
			digits: true
		}
	}
	
	messages:
	{	
		disciplinary_violations: "The disciplnary violation button must be selected.",
		
		prev_um_app: "You must indicate if you have previously applied to the Unversity of Maine.",
		
		gre_verbal: "You must enter in digits only.",
		
		gre_analytical: "You must enter in digits only.",
		
		gmat_score: "You must enter in digits only.",
		
		mat_score: "You must enter in digits only."
	}
})
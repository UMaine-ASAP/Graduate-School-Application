/*
	toggleVisibility
	
	Radio buttons toggle field visibility
	Used to reveal futher information fields based on radio button selection.
	
	need to edit document.gradform??
	
*/

<script type="text/javascript">    function toggleVisibility(elementID) {         var theDiv = document.getElementById(elementID);         if (document.gradform.RadioGroup1_0.checked == true) {            theDiv.style.display = 'block';        } else {            theDiv.style.display = 'none';        }    }</script>
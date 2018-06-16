function kendo_min_max_numeric(nombretext,min,max){
	
//	alert(nombretext+'->'+min+'->'+max);
	
	if(!IsNumeric(min)){
		var numerictextboxmin = $("#"+min).data("kendoNumericTextBox");
		min = numerictextboxmin.value();
	}
	if(!IsNumeric(max)){
		var numerictextboxmax = $("#"+max).data("kendoNumericTextBox");
		max = numerictextboxmax.value();
	}
	
	var numerictextbox = $("#"+nombretext).data("kendoNumericTextBox");
	var nro = numerictextbox.value();
		
		if(parseFloat(nro)>parseFloat(max)){
			numerictextbox.value(max);
		}else if(parseFloat(nro)<parseFloat(min)){
			numerictextbox.value(min);
		}else{
			numerictextbox.value(nro);
		}
}
		var epressionReguliere = new Array;
		epressionReguliere[0]=new RegExp(["^[0-9]{2}[ ][/][ ][0-9]{3}[ ][0-9]{2}[ ][0-9]{2}$"]);
		epressionReguliere[1]=new RegExp(["^[0-9]{3}[ ][/][ ][0-9]{3}[ ][0-9]{2}[ ][0-9]{2}$"]);
		epressionReguliere[2]=new RegExp(["^[+][4][1][ ][0-9]{1}[ ][/][ ][0-9]{3}[ ][0-9]{2}[ ][0-9]{2}$"]);
		epressionReguliere[3]=new RegExp(["^[+][4][1][ ][0-9]{2}[ ][/][ ][0-9]{3}[ ][0-9]{2}[ ][0-9]{2}$"]);		
		epressionReguliere[4]=new RegExp(["^[0]{2}[4][1][ ][0-9]{1}[ ][/][ ][0-9]{3}[ ][0-9]{2}[ ][0-9]{2}$"]);
		epressionReguliere[5]=new RegExp(["^[0]{2}[4][1][ ][0-9]{2}[ ][/][ ][0-9]{3}[ ][0-9]{2}[ ][0-9]{2}$"]);										
		epressionReguliere[6]=new RegExp(["^[\+][0-9][0-9][ ][0-9]{2}[ ][/][ ][0-9]{3}[ ][0-9]{2}[ ][0-9]{2}$"]);										
		epressionReguliere[7]=new RegExp(["^[\+][0-9][0-9][ ][0-9]{2}[ ][0-9]{3}[ ][0-9]{2}[ ][0-9]{2}$"]);										
		
		var numeroTelephoneValide=false;
		for(var i=0;i<epressionReguliere.length && !numeroTelephoneValide;i++){
			if(epressionReguliere[i].test(mesInfos.telephone.value)){
				numeroTelephoneValide=true;
			}
		}				
		if(mesInfos.telephone.value.substr(0,1)=="" || numeroTelephoneValide){
			var regNatel = new RegExp(["^[0][7][6-9]"]);
			if(regNatel.test(mesInfos.telephone.value)){
				nbErreur++;			
				mesInfos.telephone.style.background=couleurErreur;
				alert("Numéro de portable impossible ici");
			}
			else{
				mesInfos.telephone.style.background=couleurValide;
			}		
		}
		else{
			nbErreur++;			
			mesInfos.telephone.style.background=couleurErreur;
		}

		epressionReguliereNatel=new RegExp(["^[0][7][6-9][ ][/][ ][0-9]{3}[ ][0-9]{2}[ ][0-9]{2}$"]);
		epressionReguliereNatel2=new RegExp(["^[\+][0-9][0-9][ ][0-9]{2}[ ][0-9]{3}[ ][0-9]{2}[ ][0-9]{2}$"]);	
		if(mesInfos.portable.value.substr(0,1)=="" || epressionReguliereNatel.test(mesInfos.portable.value) 
							|| epressionReguliereNatel2.test(mesInfos.portable.value)){
			mesInfos.portable.style.background=couleurValide;
		}
		else{
			nbErreur++;			
			mesInfos.portable.style.background=couleurErreur;	
		}
		
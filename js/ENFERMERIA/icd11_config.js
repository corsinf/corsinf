



    const mySettings = {
	// comentar estados lineas para modo pruebas de icd 
	  	//apiServerUrl: "https://icd11restapi-developer-test.azurewebsites.net",
	   	apiServerUrl: "https://id.who.int",   
		apiSecured: true,
	//----------------------------
      language: "es", // set the language to Spanish
      enableKeyboard:false,
      searchByCodeOrURI:true,
      popupMode:true,
      simplifiedMode:true,
    };

    const myCallbacks = {

      selectedEntityFunction: (selectedEntity) => { 
        switch(selectedEntity.iNo) {
          case '1':
             ECT.Handler.clear("1")    
             $('#sa_conp_diagnostico_1').val(selectedEntity.code +'-'+selectedEntity.title);
             $('#sa_conp_CIE_10_1').val(selectedEntity.code);
            break;
          case '2':
             ECT.Handler.clear("2")    
             $('#sa_conp_diagnostico_2').val(selectedEntity.code +'-'+selectedEntity.title);
             $('#sa_conp_CIE_10_2').val(selectedEntity.code);
            break;
        }
         console.log(selectedEntity)        
      },
        getNewTokenFunction: async () => {

             const url = '../controlador/idc11_config.php?new_token=true'
            try {
                const response = await fetch(url);
                const result = await response.json();
                const token = result.token;
                return token; // the function return is required 
            } catch (e) {
            	console.log(e)
                // console.log("Error during the request");
            }
        }
    };
   
    ECT.Handler.configure(mySettings, myCallbacks);
if (!com)
	var com = {};
if (!com.logicpartners)
	com.logicpartners = {};
	
com.logicpartners.propertyInspector = function(designer, canvas) {
	this.canvas = canvas;
	this.canvasElement = $(canvas);
	this.labelDesigner = designer;
	this.activeElement = null;
	this.propertyNodes = {};
	this.boundingBox = null;
	var self = this;

	// Create the property window.
	this.propertyInspector = $('<div id="pnl_detalles"></div>')
			.addClass("designerUtilityWindow")
			.css({
				"left": this.canvas.getBoundingClientRect().right - 260,
				"top": this.canvas.getBoundingClientRect().top - 136
			})
			//.draggable({handle: "div.designerPropertyTitle"})
			.insertAfter(this.canvasElement);
			
	this.updatePosition = function(xchange) {
		this.propertyInspector.css("left", parseInt(this.propertyInspector.css("left")) + xchange);
		this.boundingBox = this.propertyInspector[0].getBoundingClientRect();
	}


	this.propertyViewContainer = $('<div></div>')
			.addClass("designerPropertyContainer")
			.resizable({
				resize: function(event, ui) {
					ui.size.width = ui.originalSize.width;
				}
			})
			.appendTo(this.propertyInspector);

	this.titleBar = $('<div>Editor de Propiedades</div>')
			.addClass("designerPropertyTitle")
			.prependTo(this.propertyInspector)
			.on("dblclick", function() {
				self.propertyViewContainer.toggle();
			});

	this.propertyView = $('<div></div>')
			.addClass("designerPropertyContent")
			.appendTo(this.propertyViewContainer);

	this.update = function(activeElement) {
		var self = this;
		var getType = {};
		var keys = [];
		if (this.activeElement == activeElement) {
			for (var key in activeElement) {
				if (!activeElement.readonly || key != "readonly" && $.inArray(key, activeElement.readonly) == -1) {
					if (getType.toString.call(activeElement[key]) != '[object Function]') {
						if($('#rbl_igual').prop('checked'))
						{
							if(key=='rfid')
							{
								activeElement[key] = activeElement['text'];
							}
						}
						// console.log(activeElement)
						// console.log(activeElement[key])
						this.propertyNodes[key].val(activeElement[key]);
					}
				}
			}
		}
		else {
			this.activeElement = activeElement;
			this.propertyView.html('');
			for (var key in activeElement) {
				if (!keys[key]) {
					keys[key] = true;
					
					if (key != "readonly" && getType.toString.call(activeElement[key]) != '[object Function]') {
						var elementKey = $('<div>' + key + '</div>')
								.css({
									"width": "65px",
									"height": "20px",
									"border": "1px solid #AAAAAA",
									"float": "left",
									"font-size": "12px",
									"line-height": "20px",
									"border-right": "none",
									"text-align": "right",
									"padding-right": "5px",
									"margin-left": "5px"
								});

						var id = '';
						var elementButton ='';
						var elementCheckRfid ='';
						var elementCheckRfid2 = '';
						if(key=='rfid'){ id = 'txt_rfid';}else{id=key}
						var elementValue = $('<input type="text" name="' + key + '" id="'+id+'" value="' + activeElement[key] + '">')
								.css({
									"width": "120px",
									"float": "left",
									"height": "22px",
									"line-height": "20px",
									"padding-left": "5px"
								});	

						if(key=='text')
						{
							elementButton = $('<button type="button" id="data_'+activeElement[key] +'"><i class="bx bx-data"></i></button>')
							.css({
								"height": "22px",
								"line-height": "20px",
								"border-radius":"5px",
							}).on('click', function () {
						        alert('¡Botón "' + key + '" presionado!');
						    });
						}
						if(key=='rfid')
						{
							elementCheckRfid2 = $('<label class="ps-1 pe-1 mb-1"><input type="checkbox" id="rbl_rfid" />Codificar</label>')
							.css({
								"height": "22px",
								"line-height": "20px",
							})
							elementCheckRfid = $('<label class="ps-1 pe-1 mb-1"><input type="checkbox" id="rbl_igual" onclick="textos_iguales()"/>Igual que text</label>')
							.css({
								"height": "22px",
								"line-height": "20px",
							})
						}

						if (!activeElement.readonly || $.inArray(key, activeElement.readonly) == -1) {
							elementValue.on("keyup", {"objectProperty": key}, function(event) {
								var data = self.activeElement[event.data.objectProperty];
								self.activeElement[event.data.objectProperty] = (data === parseInt(data, 10)) ? parseInt($(this).val()) : $(this).val();
								self.labelDesigner.updateCanvas();
							});
						}
						else {
							// Draw readonly textbox.
							elementValue.prop("readonly", true).css({ "background-color" : "#DDDDDD", border : "1px solid #AAAAAA" });
						}
					
						this.propertyNodes[key] = elementValue;

						var elementContainer = $('<div></div>')
								.css({
									"clear": "both",
									"padding-top": "2px"
								})
								.append(elementKey).append(elementValue).append(elementButton).append(elementCheckRfid).append(elementCheckRfid2);
						this.propertyView.append(elementContainer);
					}
				}
			}
		}
	}
	
	this.updatePosition(0);
}
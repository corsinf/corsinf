if (!com)
	var com = {};
if (!com.logicpartners)
	com.logicpartners = {};
if (!com.logicpartners.labelControl)
	com.logicpartners.labelControl = {};

com.logicpartners.labelControl.size = function (designer) {
	var self = this;
	this.designer = designer;
	this.workspace = $("<div></div>").addClass("designerLabelControl").attr("title", "Label Size");

	this.widthContainer = $("<div>Ancho: </div>").addClass("designerLabelControlContainer").appendTo(this.workspace);
	this.widthController = $("<input type=\"text\" />")
		.addClass("designerLabelControlElement")
		.css({
			width: "80px",
			height: "30px",
		})
		.val(this.designer.labelWidth / this.designer.dpi)
		.appendTo(this.widthContainer)
		.on("blur", function () {
			self.updateDesigner();
		})
		.on("keypress", function (e) {
			if (e.which == 13) {
				e.preventDefault();
				self.updateDesigner();
			}
		});

	this.heightContainer = $("<div>Altura: </div>").addClass("designerLabelControlContainer").appendTo(this.workspace);
	this.heightController = $("<input type=\"text\" />")
		.addClass("designerLabelControlElement")
		.css({
			width: "80px",
			height: "30px",

		})
		.val(this.designer.labelHeight / this.designer.dpi)
		.appendTo(this.heightContainer)
		.on("blur", function () {

			self.updateDesigner();
		})
		.on("keypress", function (e) {
			if (e.which == 13) {
				e.preventDefault();
				self.updateDesigner();
			}
		});

	this.dpiContainer = $("<div>DPI: </div>").addClass("designerLabelControlContainer").appendTo(this.workspace);
	this.dpiController = $("<input type=\"text\" />")
		.addClass("designerLabelControlElement")
		.css({
			width: "80px",
			height: "30px",
		})
		.val(this.designer.dpi)
		.appendTo(this.dpiContainer)
		.on("blur", function () {

			self.updateDesigner();
		})
		.on("keypress", function (e) {
			if (e.which == 13) {
				e.preventDefault();
				self.updateDesigner();
			}
		});

	this.medidasContainer = $("<div>Unidad: </div>").addClass("designerLabelControlContainer").appendTo(this.workspace);
	this.medidasController = $("<select></select>")
		.addClass("designerLabelControlElement")
		.css({
			width: "100px",
			height: "30px",
		})
		.append("<option value='in'>in</option>")
		.append("<option value='cm'>cm</option>")
		.append("<option value='mm'>mm</option>")
		.appendTo(this.medidasContainer)
		.on("change", function () { // Evento para select
			self.updateDesigner();
		});


	//Funcion anterior
	// this.updateDesigner = function () {
	// 	var dpi = this.designer.dpi;

	// 	if (!isNaN(this.dpiController.val())) dpi = this.dpiController.val();
	// 	this.designer.dpi = dpi;

	// 	var width = this.designer.labelWidth / this.designer.dpi;
	// 	var height = this.designer.labelHeight / this.designer.dpi;

	// 	if (!isNaN(this.widthController.val())) width = this.widthController.val();
	// 	if (!isNaN(this.heightController.val())) height = this.heightController.val();

	// 	this.designer.updateLabelSize(width, height);
	// 	this.widthController.val(width);
	// 	this.heightController.val(height);
	// }

	this.updateDesigner = function () {
		var dpi = this.designer.dpi;

		// Obtener el valor de DPI si no es NaN
		if (!isNaN(this.dpiController.val())) {
			dpi = this.dpiController.val();
		}
		this.designer.dpi = dpi;

		// Calcular las dimensiones iniciales
		var width = this.designer.labelWidth / this.designer.dpi;
		var height = this.designer.labelHeight / this.designer.dpi;

		// console.log('laber' + this.designer.labelWidth);
		// console.log('width + ' + width_memory);

		// Obtener y actualizar la unidad de medida seleccionada
		var unit = this.designer.unit;
		unit = this.medidasController.val();
		this.designer.unit = unit;

		// console.log(this.designer.unit);

		// Calcular las dimensiones según la unidad de medida
		width_size = this.widthController.val();
		height_size = this.heightController.val();

		if (unit === 'in') {
			// Si la unidad es pulgadas, no es necesario realizar conversión adicional
			if (!isNaN(width_size) && width_size > 0) {
				width = parseFloat(width_size);
				// console.log('width: ' + width);
			}
			if (!isNaN(height_size) && height_size > 0) {
				height = parseFloat(height_size);
				// console.log('height: ' + height);
			}
		} else if (unit === 'cm') {
			// Si la unidad es centímetros, conviértela a pulgadas
			if (!isNaN(width_size) && width_size > 0) {
				width = parseFloat(width_size) / 2.54; // Conversión de cm a pulgadas
				// console.log('width (converted from cm): ' + width);
			}
			if (!isNaN(height_size) && height_size > 0) {
				height = parseFloat(height_size) / 2.54; // Conversión de cm a pulgadas
				// console.log('height (converted from cm): ' + height);
			}
		} else if (unit === 'mm') {
			// Si la unidad es milímetros, conviértela a pulgadas
			if (!isNaN(width_size) && width_size > 0) {
				width = parseFloat(width_size) / 25.4; // Conversión de mm a pulgadas
				// console.log('width (converted from mm): ' + width);
			}
			if (!isNaN(height_size) && height_size > 0) {
				height = parseFloat(height_size) / 25.4; // Conversión de mm a pulgadas
				// console.log('height (converted from mm): ' + height);
			}
		}

		// Actualizar el tamaño de la etiqueta
		this.designer.updateLabelSize(width, height);

		// Restaurar los valores originales de ancho y alto en el controlador
		this.widthController.val(width_size);
		this.heightController.val(height_size);
	};


	this.update = function () {
		this.widthController.val(this.designer.labelWidth / this.designer.dpi);
		this.heightController.val(this.designer.labelHeight / this.designer.dpi);
	}
}
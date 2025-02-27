if (!com)
	var com = {};
if (!com.logicpartners)
	com.logicpartners = {};
if (!com.logicpartners.designerTools)
	com.logicpartners.designerTools = {};

com.logicpartners.designerTools.text = function () {
	var self = this;
	this.counter = 1;
	this.button = $("<div></div>").addClass("designerToolbarText designerToolbarButton").attr("title", "Texto").append($("<div></div>"));
	this.object = function (x, y, width, height) {
		this.name = "Textbox " + self.counter++;
		this.text = this.name;
		// this.rfid = this.name;
		// this.igual = 0;
		// this.codi = 0;
		this.x = x;
		this.y = y;
		this.fontSize = 48;
		this.fontType = "Arial";
		this.width = 100;
		this.height = 0;

		this.readonly = ["width", "height","name"];

		this.getFontHeight = function () {
			var textMeasure = $("<div></div>").css({
				"font-size": this.fontSize + "px",
				"font-family": this.fontType,
				"opacity": 0,
			}).text("M").appendTo($("body"));

			var height = textMeasure.outerHeight();
			textMeasure.remove();
			return height;
		}

		// this.getOptionRfid = function () {
		// 	parametros = 
		// 	{
		// 		'igual':$('#rbl_igual').prop(),
		// 		'codificar':$('#rbl_rfid').prop(),
		// 	}
		// 	return parametros;
		// }

		this.getZPLData = function () {
			return "";
		}

		//Version original
		// this.toZPL = function(labelx, labely, labelwidth, labelheight) {
		// 	return "^FO" + (this.x - labelx) + "," + (this.y - labely) + "^FD" + this.text + "^FS";
		// }

		this.toZPL = function (labelx, labely, labelwidth, labelheight, fontType = '0') {
			// Ajustes de escala para altura y ancho de la fuente
			fontHeight = ((this.fontSize * 54.4) / 48).toFixed(1); // Ajuste para el "laberary"
			fontWidth = ((this.fontSize * 54.4) / 48).toFixed(1);  // Mantener altura y ancho iguales

			// Ajustes de coordenadas
			x = Math.abs((this.x - labelx)).toFixed(3);
			y = Math.abs((this.y - labely + 10)).toFixed(3);

			// Generar el código ZPL
			return (
				`^FO${x},${y}` + // Coordenadas ajustadas
				`^A${fontType},${fontHeight},${fontWidth}` + // Tamaño de la fuente ajustado
				`^FD${this.text}^FS` // Texto contenido
			);
		};

		this.draw = function (context) {
			context.font = this.fontSize + "px " + this.fontType;
			var oColor = context.fillStyle;
			context.fillStyle = "white";
			this.height = this.getFontHeight();			
			var measuredText = context.measureText(this.text);
			this.width = measuredText.width;
			context.globalCompositeOperation = "difference";
			context.fillText(this.text, this.x, this.y + (this.height * 0.75));
			context.globalCompositeOperation = "source-over";
			context.fillStyle = oColor;
			//context.fillRect(this.x, this.y, this.width, this.height);
		}

		this.setWidth = function (width) {
			//this.width = width;
		}

		this.getWidth = function () {
			return this.width;
		}

		this.setHeight = function (height) {
			//height = height;
		}

		this.getHeight = function () {
			return this.height * 0.75;
		}

		this.setHandle = function (coords) {
			this.handle = this.resizeZone(coords);
		}

		this.getHandle = function () {
			return this.handle;
		}

		this.drawActive = function (context) {
			context.dashedStroke(parseInt(this.x + 1), parseInt(this.y + 1), parseInt(this.x) + parseInt(this.width) - 1, parseInt(this.y) + parseInt(this.height * 0.9) - 1, [2, 2]);
		}

		this.hitTest = function (coords) {
			return (coords.x >= parseInt(this.x) && coords.x <= parseInt(this.x) + parseInt(this.width) && coords.y >= parseInt(this.y) && coords.y <= parseInt(this.y) + parseInt(this.height) * 0.75);
		}
	}
}
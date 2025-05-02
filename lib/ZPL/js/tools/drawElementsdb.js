canvasDesigner.QRcode = com.logicpartners.designerTools.QRcode; 
canvasDesigner.text =  com.logicpartners.designerTools.text;
canvasDesigner.rectangle = com.logicpartners.designerTools.rectangle;
canvasDesigner.barcode  = com.logicpartners.designerTools.barcode;
canvasDesigner.image = com.logicpartners.designerTools.image;



function drawElments(elemetos)
{
	 elementos = elemetos.filter(elemento => elemento !== null);

	console.log(elementos)

	elementos.forEach(function(items,i){

		elem = items.name.toUpperCase();
		const qr = elem.includes('QRCODE'); 
		const text = elem.includes('TEXTBOX'); 
		const rect = elem.includes('RECTANGLE'); 
		const barcode = elem.includes('BARCODE'); 
		const imagen = elem.includes('IMAGE'); 

		console.log(qr)
		if(qr)
		{
			QRcodeDraw(items);
		}

		if(text)
		{
			TextboxDraw(items);
		}
		if(barcode)
		{
			barcodeDraw(items);
		}

		if(rect)
		{
			rectangleDraw(items);
		}
		if(imagen)
		{
			imagenDraw(items);
		}

		// switch()
		// {
		// case :
		// 	break;
		// }
	})

	// canvasDesigner.QRcode = com.logicpartners.designerTools.QRcode; 

    //            var items = [
	// 			  { name: "Qrcode 1", text: "QRCODE", x: 99, y: 53,  width: 75, height: 75, readonly: ["name"] },
	// 			  { name: "Qrcode 2", text: "QRCODE", x: 87, y: 77,  width: 48, height: 48, readonly: ["name"] }
	// 			];

	// 	// 4) Recorres y agregas
	// 	items.forEach(function(cfg) {
	// 	  // a) Instancia la herramienta y crea el objeto
	// 	  var tool = new canvasDesigner.QRcode();
	// 	  var qrObj = new tool.object(cfg.x, cfg.y, cfg.width, cfg.height);
		  
	// 	  // b) Ajusta propiedades según tu JSON
	// 	  qrObj.name     = cfg.name;
	// 	  qrObj.text     = cfg.text;
	// 	  qrObj.readonly = cfg.readonly;
	// 	  qrObj.setWidth(cfg.width);   // para que el tamaño interno de tu código coincida
	// 	  qrObj.setHeight(cfg.height);

	// 	  // c) Lo agregas al canvas
	// 	  canvasDesigner.addObject(qrObj);
	// 	});



}

function QRcodeDraw(data)
{

	canvasDesigner.QRcode = com.logicpartners.designerTools.QRcode; 
	var tool = new canvasDesigner.QRcode();
	var qrObj = new tool.object(data.x, data.y, data.width, data.height);
	  // b) Ajusta propiedades según tu JSON
	  qrObj.name     = data.name;
	  qrObj.text     = data.text;
	  qrObj.readonly = data.readonly;
	  qrObj.setWidth(data.width);   // para que el tamaño interno de tu código coincida
	  qrObj.setHeight(data.height);

	  // c) Lo agregas al canvas
	  canvasDesigner.addObject(qrObj);
}

function TextboxDraw(data)
{

	canvasDesigner.text =  com.logicpartners.designerTools.text;
	var tool = new canvasDesigner.text();
	var qrObj = new tool.object(data.x, data.y, data.width, data.height);
	  // b) Ajusta propiedades según tu JSON
	  qrObj.name     = data.name;
	  qrObj.text     = data.text;
	  qrObj.readonly = data.readonly;
	  qrObj.setWidth(data.width);   // para que el tamaño interno de tu código coincida
	  qrObj.setHeight(data.height);
	  qrObj.fontSize = data.fontSize;

	  // c) Lo agregas al canvas
	  canvasDesigner.addObject(qrObj);
}

function barcodeDraw(data)
{
	canvasDesigner.barcode  = com.logicpartners.designerTools.barcode;
	var tool = new canvasDesigner.barcode();
	var qrObj = new tool.object(data.x, data.y, data.width, data.height);
	  // b) Ajusta propiedades según tu JSON
	  qrObj.name     = data.name;
	  qrObj.text     = data.text;
	  qrObj.readonly = data.readonly;
	  qrObj.setWidth(data.width);   // para que el tamaño interno de tu código coincida
	  qrObj.setHeight(data.height);

	  // c) Lo agregas al canvas
	  canvasDesigner.addObject(qrObj);
}

function rectangleDraw(data)
{
	canvasDesigner.rectangle = com.logicpartners.designerTools.rectangle;
	var tool = new canvasDesigner.rectangle();
	var qrObj = new tool.object(data.x, data.y, data.width, data.height);
	  // b) Ajusta propiedades según tu JSON
	  qrObj.name     = data.name;
	  qrObj.text     = data.text;
	  qrObj.readonly = data.readonly;
	  qrObj.setWidth(data.width);   // para que el tamaño interno de tu código coincida
	  qrObj.setHeight(data.height);

	  // c) Lo agregas al canvas
	  canvasDesigner.addObject(qrObj);
}

function imagenDraw(data)
{
	canvasDesigner.image = com.logicpartners.designerTools.image;
	var tool = new canvasDesigner.image();
	var qrObj = new tool.object(data.x, data.y, data.width, data.height);
	  // b) Ajusta propiedades según tu JSON
	  qrObj.name     = data.name;
	  qrObj.text     = data.text;
	  qrObj.readonly = data.readonly;
	  qrObj.setWidth(data.width);   // para que el tamaño interno de tu código coincida
	  qrObj.setHeight(data.height);
	  qrObj.uniqueID = data.uniqueID
	  qrObj.data = data.data

	  // c) Lo agregas al canvas
	  canvasDesigner.addObject(qrObj);
}
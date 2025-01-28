if (!com) var com = {};
if (!com.logicpartners) com.logicpartners = {};
if (!com.logicpartners.designerTools) com.logicpartners.designerTools = {};

com.logicpartners.designerTools.QRcode = function() {
    var self = this;
    this.counter = 1;
    this.button = $("<div></div>").addClass("designerToolbarQRcode designerToolbarButton").attr("title", "Codigo qr").append($("<div></div>"));
    
    // Constructor para crear el objeto QR
    this.object = function(x, y, width, height) {
        var canvasHolder = $("<canvas></canvas>").prop("width", "10").prop("height", "10");
        this.name = "Qrcode " + self.counter++;
        this.text = "QRCODE";
        this.x = x;
        this.y = y;
        this.width = width;
        this.height = height;

        // Método para obtener los datos ZPL
        this.getZPLData = function() {
            return "";
        }

        // Método para convertir a ZPL
        this.toZPL = function(labelx, labely, labelwidth, labelheight) {
            return "^FO" + (this.x - labelx) + "," + (this.y - labely) + "^BY1^B3N,N," + this.height + "N,N^FD" + this.text + "^FS";
        }

        // Método para dibujar el QR en el canvas
        this.draw = function(context) {
            console.log(context);

            // Obtener el canvas principal y su contexto
            // var canvas = document.getElementById("labelDesigner");
            // var ctx = canvas.getContext("2d");

            // Crear un canvas temporal para generar el QR
            var tempCanvas = canvasHolder[0]
            var cwidth = canvasHolder[0].width;
            var cheight = canvasHolder[0].height;
            console.log(tempCanvas);
            
            var tempCtx = tempCanvas.getContext('2d');

            // Generar el código QR usando qrcode.js
            QRCode.toCanvas(tempCanvas, this.text, { width: cwidth, height: cheight }, function (error) {
                if (error) {
                    console.error("Error generando el QR:", error);
                    return;
                }

                // Dibujar el QR en el canvas principal (labelDesigner)
                // Ajusta la posición (x, y) del QR en el canvas principal
                console.log(this.getWidth)
                context.drawImage(tempCanvas, this.getWidth, this.getHeight);
            });
        }

        // Métodos para establecer y obtener el ancho y alto del QR
        this.setWidth = function(width) {
            this.width = width;
        }

        this.getWidth = function() {
            return this.width;
        }

        this.setHeight = function(height) {
            this.height = height;
        }

        this.getHeight = function() {
            return this.height;
        }

        // Método para manejar la zona de redimensionamiento
        this.setHandle = function(coords) {
            this.handle = this.resizeZone(coords);
        }

        this.getHandle = function() {
            return this.handle;
        }

        // Método para dibujar el borde del QR cuando está activo
        this.drawActive = function(context) {
            context.dashedStroke(parseInt(this.x + 1), parseInt(this.y + 1), parseInt(this.x) + parseInt(this.width) - 1, parseInt(this.y) + parseInt(this.height) - 1, [2, 2]);
        }

        // Método de prueba de colisión con el QR
        this.hitTest = function(coords) {
            return (coords.x >= parseInt(this.x) && coords.x <= parseInt(this.x) + parseInt(this.width) && coords.y >= parseInt(this.y) && coords.y <= parseInt(this.y) + parseInt(this.height));
        }
    }
};

if (!com) var com = {};
if (!com.logicpartners) com.logicpartners = {};
if (!com.logicpartners.designerTools) com.logicpartners.designerTools = {};

com.logicpartners.designerTools.QRcode = function() {
    var self = this;
    this.counter = 1;
    this.button = $("<div></div>").addClass("designerToolbarQRcode designerToolbarButton").attr("title", "Codigo qr").append($("<div></div>"));
    
    // Constructor para crear el objeto QR
    this.object = function(x, y, width, height) {
        var width = 75;
        var height = 75;
        var temp = 0;
        var sube = 1;
        var canvasHolder = $("<canvas></canvas>").prop("width", "100").prop("height", "100");
        this.name = "Qrcode " + self.counter++;
        this.text = "QRCODE";
        this.x = x;
        this.y = y;
        this.width = width;
        this.height = height;
        this.readonly = ['name'];

        // Método para obtener los datos ZPL
        this.getZPLData = function() {
            return "";
        }

        // Método para convertir a ZPL
        this.toZPL = function(labelx, labely, labelwidth, labelheight) {
            // var k = ((3*203)/75)
            // dpi = $('#txt_dpi').val();
            // var t = Math.ceil((k*this.width/dpi))
             this.qrSize(this.width);

            return "^FO" + (this.x) + "," + (this.y-labely-2) + "\n^BQN,2,"+t+"\n^FDQA," + this.text + "^FS";
        }

        // Método para dibujar el QR en el canvas
        this.draw = function(context) {
           
            // Obtener las dimensiones del canvas principal
            this.qrSize(this.width);

            var canvasWidth = tw;
            var canvasHeight = th;

            // Crear un canvas temporal para generar el QR
            var tempCanvas = document.createElement('canvas');
            tempCanvas.width = canvasWidth; // Mismo ancho que el canvas principal
            tempCanvas.height = canvasHeight; // Mismo alto que el canvas principal

            var tempCtx = tempCanvas.getContext('2d');

            // Generar el código QR usando qrcode.js
            QRCode.toCanvas(tempCanvas, this.text, { 
                width: canvasWidth, // Ancho del QR = ancho del canvas
                height: canvasHeight, // Alto del QR = alto del canvas
                margin: 0 // Sin márgenes
            }, function (error) {
                if (error) {
                    console.error("Error generando el QR:", error);
                    return;
                }

                // Dibujar el QR en el canvas principal
                context.drawImage(tempCanvas, this.x, this.y,tw,th);
            }.bind(this)); // Usamos bind(this) para mantener el contexto de "this"

        }

        // Métodos para establecer y obtener el ancho y alto del QR
        this.setWidth = function(width) {
            this.width = width;
            this.height = width;
        }

        this.getWidth = function() {
            return this.width;
        }

        this.setHeight = function(height) {
            this.height = height;
            this.width = height;
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
            console.log(this.width)
            console.log(this.height)
            console.log(tw)
            console.log(th)
            context.dashedStroke(parseInt(this.x), parseInt(this.y), parseInt(this.x) + parseInt(this.width), parseInt(this.y) + parseInt(this.height));
        }

        // Método de prueba de colisión con el QR
        this.hitTest = function(coords) {
            return (coords.x >= parseInt(this.x) && coords.x <= parseInt(this.x) + parseInt(tw) && coords.y >= parseInt(this.y) && coords.y <= parseInt(this.y) + parseInt(th));
        }


        this.qrSize = function(width,sube)
        {
             if(temp==0){temp = width;}
            if(this.width>temp){ sube = 1; temp =width; }else{ sube = 0;  temp =width;}

            if(sube==1)
            {
                switch (true) {
                    case (width <=26):
                        console.log("El valor está dentro del rango.");
                        tw =  26;
                        th = 26;
                        t =1;
                        break;
                    case (width > 26 && width <= 48):
                        tw = 48;
                        th = 48;
                        t =2;
                        break;
                    case (width > 48 && width <= 75):
                        tw =  75;
                        th = 75;
                        t =3;
                        break;
                    case (width > 75 && width <= 100):
                        tw = 100;
                        th = 100;
                        t =4;
                        break;
                     case (width > 100 && width <= 123):
                        tw = 123;
                        th = 123;
                        t =5;
                        break;
                    case (width > 123 && width <= 144):
                        tw = 144;
                        th = 144;
                        t =6;
                        break;
                    case (width > 144 && width <= 171):
                        tw = 171;
                        th = 171;
                        t =7;
                        break;
                    case (width > 171 && width <= 201):
                        tw = 201;
                        th = 201;
                        t =8;
                        break;
                    case (width > 201 && width <= 227):
                        tw = 227;
                        th = 227;
                        t =9;
                        break;
                    case (width > 227 && width <= 250):
                        tw = 250;
                        th = 250;
                        t =10;
                        break;
                    default:
                        tw =  75;
                        th = 75;
                        t=3;
                        break;
                }
            }else
            {
                 switch (true) {
                    case (width <=26):
                        console.log("El valor está dentro del rango.");
                        tw =  26;
                        th = 26;
                        t =1;
                        break;
                    case ( width < 48):
                        tw = 26;
                        th = 26;
                        t =1;
                        break;
                    case (width < 75):
                        tw =  48;
                        th = 48;
                        t =2;
                        break;
                    case (width < 100):
                        tw = 75;
                        th = 75;
                        t =3;
                        break;
                     case (width < 123):
                        tw = 100;
                        th = 100;
                        t =4;
                        break;
                    case (width < 144):
                        tw = 123;
                        th = 123;
                        t =5;
                        break;
                    case (width < 171):
                        tw = 144;
                        th = 144;
                        t =6;
                        break;
                    case (width < 201):
                        tw = 171;
                        th = 171;
                        t =7;
                        break;
                    case (width < 227):
                        tw = 201;
                        th = 201;
                        t =8;
                        break;
                    case (width < 250):
                        tw = 227;
                        th = 227;
                        t =9;
                        break;
                    default:
                        tw =  75;
                        th = 75;
                        t=3;
                        break;
                }
            }

            this.width = tw;
            this.height = th;

         
            return size = {'tw':tw,'th':th}
        }
    }
};

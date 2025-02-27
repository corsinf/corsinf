if (!com) var com = {};
if (!com.logicpartners) com.logicpartners = {};
if (!com.logicpartners.designerTools) com.logicpartners.designerTools = {};

com.logicpartners.designerTools.DBdata = function() {
    var self = this;
    this.counter = 1;
    this.button = $("<div onclick='modal_datos()'></div>").addClass("designerToolbarDBdata designerToolbarButton").attr("title", "Origen de datos").append($("<div></div>"));
};

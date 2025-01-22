if (!com)
	var com = {};
if (!com.logicpartners)
	com.logicpartners = {};
	
com.logicpartners.toolsWindow = function(designer, canvas) {
	this.canvas = canvas;
	this.canvasElement = $(canvas);
	this.labelDesigner = designer;
	this.boundingBox = null;
	var self = this;

	// Create the property window.
	this.toolsWindow = $('<div id="pnl_tools"></div>')
			.addClass("designerUtilityToolbar")
			.css({
				"left": this.canvas.getBoundingClientRect().left - 365,
				"top": this.canvas.getBoundingClientRect().top - 136
			})
			//.draggable({handle: "div.designerPropertyTitle"})
			.insertAfter(this.canvasElement);

	console.log(this.canvas.getBoundingClientRect().left)

	this.toolsViewContainer = $('<div></div>')
			.addClass("designerToolbarContent")
			.resizable({
				resize: function(event, ui) {
					ui.size.width = ui.originalSize.width;
				}
			})
			.appendTo(this.toolsWindow);

	this.titleBar = $('<div>Panel</div>')
			.addClass("designerPropertyTitle")
			.prependTo(this.toolsWindow)
			.on("dblclick", function() {
				self.toolsViewContainer.toggle();
			});

	this.buttonView = $('<div></div>')
			.appendTo(this.toolsViewContainer);
	
	this.setTool = function(controller) {
		if (self.labelDesigner.newObjectController == controller) {
			self.labelDesigner.setNewObject(null);
			controller.button.removeClass("designerToolbarButtonActive");
		}
		else {
			if (self.labelDesigner.newObjectController) self.labelDesigner.newObjectController.button.removeClass("designerToolbarButtonActive");
			self.labelDesigner.setNewObject(controller);
			if (controller) {
				controller.button.addClass("designerToolbarButtonActive");
			
				if (controller.activate) controller.activate(this);
			}
		}
	};
	
	this.addTool = function(controller) {
		var self = this;
		controller.button.on("click", { tool : controller }, function(event) {
			self.setTool(event.data.tool);
		});
		
		this.buttonView.append(controller.button);
	}
	
	this.updatePosition = function(xchange) {
		this.boundingBox = this.toolsWindow[0].getBoundingClientRect();
	}
	
	this.update = function(activeElement) {
	}
	
	this.updatePosition(0);
}
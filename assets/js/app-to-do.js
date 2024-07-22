// to do list 
		 var todos = [
		 	{
				area: "Matematica",
				text: "Matematica",
				nota: "7.81",
				done: false,
				id: 0
			},
			{
				area: "Ciencias Naturales",
				text: "Fisica",
				nota: "8.12",
				done: false,
				id: 1
			},	
			{
				area:  "Ciencias Naturales",
				text: "Quimica",
				nota: "9.38",
				done: false,
				id: 2
			},		
			{
				area:  "Ciencias Naturales",
				text: "Biologia",
				nota: "9.15",
				done: false,
				id: 3
			},	
			{
				area: "Ciencia Sociales",
				text: "Historia",
				nota: "9.43",
				done: false,
				id: 4
			},	
			{
				area: "Ciencia Sociales",
				text: "Educacion para la Ciudadania",
				nota: "9.32",
				done: false,
				id: 5
			},	
			{
				area: "Ciencia Sociales",
				text: "Filosofia",
				nota: "9.37",
				done: false,
				id: 6
			},	
			{
				area: "Lenguaje y Literatura",
				text: "Lenguaje y Literatura",
				nota: "9.32",
				done: false,
				id: 7
			},	
			{
				area: "Lenguaje Extrangero",
				text: "Gramatica",
				nota: "9.03",
				done: false,
				id: 8
			},	
			{
				area: "Lenguaje Extrangero",
				text: "Escrita",
				nota: "8.93",
				done: false,
				id: 9
			},	
			{
				area: "Lenguaje Extrangero",
				text: "Lectora",
				nota: "9.25",
				done: false,
				id: 10
			},	
			{
				area: "Lenguaje Extrangero",
				text: "Hablado y escuchado",
				nota: "9.22",
				done: false,
				id: 11
			},	
			{
				area: "Educacion Cultural y artistica",
				text:"Educacion Cultural y artistica",
				nota: "9.79",
				done: false,
				id: 12
			},	
			{
				area: "Educacion Fisica",
				text:"Educacion Fisica (gym)",
				nota: "9.01",
				done: false,
				id: 13
			},	
			{
				area: "Modulo interdisiplinario",
				text:"Emprendimiento",
				nota: "8.66",
				done: false,
				id: 14
			},	
			{
				area: "Educacion Religiosa",
				text:"",
				nota: "9.89",
				done: false,
				id: 15
			},	
			{
				area: "Investigacion",
				text:"",
				nota: "9.23",
				done: false,
				id: 16
			},	
			{
				area: "Estudios Multidiciplinarios",
				text:"",
				nota: "EX",
				done: false,
				id: 17
			},	
			{
				area: "Desarrollo integral y humano",
				text: "Desarrollo integral y humano",
				nota: "EX",
				done: false,
				id: 18
			}
		];

		  var todos2 = [];
		  var todos3 = [];
		var currentTodo = {
			area: "",
			text: "",
			nota: "1",
			done: false,
			id: 0
		}
		document.getElementById("todo-input").oninput = function (e) {
			currentTodo.text = e.target.value;
		};
		/*
			//jQuery Version
			$('#todo-input').on('input',function(e){
				currentTodo.text = e.target.value;
			   });
			*/
		function DrawTodo(todo) {
			var newTodoHTML = `
			<div class="pb-3 todo-item" todo-id="${todo.id}">
				<div class="row">
					<div class="col-sm-4">
						<label>${todo.area}</label>
					</div>
					<div class="col-sm-5">
						<label>${todo.text}</label>
					</div>
					<div class="col-sm-2">
						<label>${todo.nota}</label>
					</div>
					<div class="col-sm-1">
						<button todo-id="${todo.id}" class="btn btn-outline-secondary bg-danger text-white" type="button" onclick="DeleteTodo(this);" id="button-addon2 ">X</button>
					</div>
				</div>				
			</div>
			  `;
			var dummy = document.createElement("DIV");
			dummy.innerHTML = newTodoHTML;
			document.getElementById("todo-container").appendChild(dummy.children[0]);
			/*
				//jQuery version
				 var newTodo = $.parseHTML(newTodoHTML);
				 $("#todo-container").append(newTodo);
				*/
		}

		function RenderAllTodos() {
			var container = document.getElementById("todo-container");
			while (container.firstChild) {
				container.removeChild(container.firstChild);
			}
			
				//jQuery version
				  $("todo-container").empty();
				
			for (var i = 0; i < todos.length; i++) {
				DrawTodo(todos[i]);
			}
		}
		RenderAllTodos();

		function DeleteTodo(button) {
			var deleteID = parseInt(button.getAttribute("todo-id"));
			/*
				//jQuery version
				  var deleteID = parseInt($(button).attr("todo-id"));
				*/
			for (let i = 0; i < todos.length; i++) {
				if (todos[i].id === deleteID) {
					todos.splice(i, 1);
					RenderAllTodos();
					break;
				}
			}
		}

		function TodoChecked(id) {
			todos[id].done = !todos[id].done;
			RenderAllTodos();
		}

		function CreateTodo() {			
			newtodo = {
				area: $('#todo-area').val(),
				text: $('#todo-input').val(),
				nota: $('#todo-nota').val(),
				done: false,
				id: todos.length
			}
			todos.push(newtodo);
			RenderAllTodos();
		}
// -------------------------------------------------------------- 2 -----------------------------------------------
	function CreateTodo2() {			
			newtodo = {
				area: $('#todo-area2').val(),
				text: $('#todo-input2').val(),
				nota: $('#todo-nota2').val(),
				done: false,
				id: todos2.length
			}
			todos2.push(newtodo);
			RenderAllTodos2();
	}

	function RenderAllTodos2() {
			var container = document.getElementById("todo-container2");
			while (container.firstChild) {
				container.removeChild(container.firstChild);
			}
			
				//jQuery version
				  // $("todo-container2").empty();
				
			for (var i = 0; i < todos2.length; i++) {
				DrawTodo2(todos2[i]);
			}
		}
		RenderAllTodos2();

		function DrawTodo2(todo) {
			var newTodoHTML = `
			<div class="pb-3 todo-item" todo-id="${todo.id}">
				<div class="row">
					<div class="col-sm-4">
						<label>${todo.area}</label>
					</div>
					<div class="col-sm-5">
						<label>${todo.text}</label>
					</div>
					<div class="col-sm-2">
						<label>${todo.nota}</label>
					</div>
					<div class="col-sm-1">
						<button todo-id="${todo.id}" class="btn btn-outline-secondary bg-danger text-white" type="button" onclick="DeleteTodo2(this);" id="button-addon2 ">X</button>
					</div>
				</div>				
			</div>
			  `;
			var dummy = document.createElement("DIV");
			dummy.innerHTML = newTodoHTML;
			document.getElementById("todo-container2").appendChild(dummy.children[0]);
			/*
				//jQuery version
				 var newTodo = $.parseHTML(newTodoHTML);
				 $("#todo-container").append(newTodo);
				*/
		}

		function DeleteTodo2(button) {
			var deleteID = parseInt(button.getAttribute("todo-id"));
			/*
				//jQuery version
				  var deleteID = parseInt($(button).attr("todo-id"));
				*/
			for (let i = 0; i < todos2.length; i++) {
				if (todos2[i].id === deleteID) {
					todos2.splice(i, 1);
					RenderAllTodos2();
					break;
				}
			}
		}

// -------------------------------------------------------------- 3 -----------------------------------------------
	function CreateTodo3() {			
			newtodo = {
				area: $('#todo-area3').val(),
				text: $('#todo-input3').val(),
				nota: $('#todo-nota3').val(),
				done: false,
				id: todos3.length
			}
			todos3.push(newtodo);
			RenderAllTodos3();
	}

	function RenderAllTodos3() {
			var container = document.getElementById("todo-container3");
			while (container.firstChild) {
				container.removeChild(container.firstChild);
			}
			
				//jQuery version
				  // $("todo-container2").empty();
				
			for (var i = 0; i < todos3.length; i++) {
				DrawTodo3(todos3[i]);
			}
		}
		RenderAllTodos3();

		function DrawTodo3(todo) {
			var newTodoHTML = `
			<div class="pb-3 todo-item" todo-id="${todo.id}">
				<div class="row">
					<div class="col-sm-4">
						<label>${todo.area}</label>
					</div>
					<div class="col-sm-5">
						<label>${todo.text}</label>
					</div>
					<div class="col-sm-2">
						<label>${todo.nota}</label>
					</div>
					<div class="col-sm-1">
						<button todo-id="${todo.id}" class="btn btn-outline-secondary bg-danger text-white" type="button" onclick="DeleteTodo2(this);" id="button-addon2 ">X</button>
					</div>
				</div>				
			</div>
			  `;
			var dummy = document.createElement("DIV");
			dummy.innerHTML = newTodoHTML;
			document.getElementById("todo-container3").appendChild(dummy.children[0]);
			/*
				//jQuery version
				 var newTodo = $.parseHTML(newTodoHTML);
				 $("#todo-container").append(newTodo);
				*/
		}

		function DeleteTodo3(button) {
			var deleteID = parseInt(button.getAttribute("todo-id"));
			/*
				//jQuery version
				  var deleteID = parseInt($(button).attr("todo-id"));
				*/
			for (let i = 0; i < todos3.length; i++) {
				if (todos3[i].id === deleteID) {
					todos3.splice(i, 1);
					RenderAllTodos3();
					break;
				}
			}
		}

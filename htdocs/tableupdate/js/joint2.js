/*
 Now the final thing we need is the code that will start the bootstrap table  
Copy the JavaScript from here: http://fooplugins.github.io/FooTable/docs/examples/component/showcase.html  
 
 
Right before we actually add the row values into the table I want to use http post to call a php file that will add the new row into the rows.json file.  

POST does the same thing a GET which grabs information from the requested site; however, POST allows you to add some “data” to the request. Our data will be the new row we just created.  
 */
function btns(value1, value2){
	 // var $row = $(this).closest('tr');
	var row = FooTable.getRow(value1).value;
	var rank = 0;
if (value2 == "up"){
rank = row.user +1;
console.log("up");
}else{rank = row.user -1; console.log("down");}
$.ajax({
						type: "POST",
						url: "editrow.php",
						cache: false, //this cache line is used so that the website wont keep the rows so it stays updated
						contentType: "application/json",
						dataType: "json",
						data: $data = JSON.stringify({					
						id: String(row.id),
						user: String(rank),
						Name: row.Name,
						showTitle: row.showTitle,
						recommendedOn: row.recommendedOn,
						rating: row.rating,
						types: row.types,
						options: row.options,
						comments: row.comments,
						updown: "<button type=\"btnup\" onclick=\"$(document).ready(btns($(this).closest('tr'),'up'));\" class=\"btn btn-default btn-sm\"> <span class=\"glyphicon glyphicon-plus\"></span></button><button type=\"btndown\" onclick=\"$(document).ready(btns($(this).closest('tr'),'down'));\" class=\"btn btn-default btn-sm\"> <span class=\"glyphicon glyphicon-minus\"></span></button>"
							}) });
setTimeout(function(){
window.location.reload(true);
},200);
		
	} ;
jQuery(function ($){
	var $modal = $('#editor-modal'),
		$editor = $('#editor'),
		$editorTitle = $('#editor-title'),
		// the below initializes FooTable and returns the created instance for later use
		ft = FooTable.init('#showcase-example-1', {
			columns: $.ajax("content/columns.json", {dataType:'json', timeout: 3000}), //no cache for collumn because it shouldn't change
			rows: $.ajax("content/rows.json", {cache: false, dataType:'json', timeout: 3000}),//this cache line is used so that the website wont keep the rows so it stays updated
			editing: {
				enabled: true,
				addRow: function(){
					$modal.removeData('row');// remove any previous row data
					$editor[0].reset();// reset the form to clear any previous row data
					$editorTitle.text('Add a new row');// set the modal title
					$modal.modal('show'); // display the modal
				},
				// the editRow callback is supplied the FooTable.Row object for editing as the first argument.
				editRow: function(row){
					var values = row.val();
					// we need to find and set the initial value for the editor inputs
					$editor.find('#id').val(values.id);
					$editor.find('#user').val(values.user);
					$editor.find('#Name').val(values.Name);
					$editor.find('#showTitle').val(values.showTitle);
					$editor.find('#rating').val(values.rating);
					$editor.find('#types').val(values.types);
					$editor.find('#options').val(values.options);
					$editor.find('#comments').val(values.comments);
					$editor.find('#recommendedOn').val(values.recommendedOn);
					$modal.data('row', row);// set the row data value for use later
					$editorTitle.text('Edit row #' + values.id);// set the modal title
					$modal.modal('show');// display the modal
				},
				// the deleteRow callback is supplied the FooTable.Row object for deleting as the first argument.
				deleteRow: function(row){
					 // This example displays a confirm popup and then simply removes the row but you could just
					// as easily make an ajax call and then only remove the row once you retrieve a response.
					if (confirm('Are you sure you want to delete the row?')){
					row.delete();
					//Right after this line: row.delete(); (we could have actually done a GET here, just did what I thought would work. UPDATE: We might need POST later for this to delete certain rows.)   
					$.ajax({
						type: "POST",
						url: "deleterow.php",
						cache: false, //this cache line is used so that the website wont keep the rows so it stays updated
						contentType: "application/json",
						dataType: "json",
						data: JSON.stringify({
							id: row.val().id,
							user:row.val().user,
							Name: row.val().Name,
							showTitle: row.val().showTitle,
							recommendedOn:  row.val().recommendedOn,
							rating: row.val().rating						
							})
						/*,success: function(response) {
						//	console.log(response);
						//	
						//	},
						//error: function(response) {
						//	console.log(response);
						//	}
							*/});
						//UPDATE: I also wanted to make sure that the browser updates right after we add any new information I added a reload page line to right after we delete a row, and add a new row. (so that you don’t have to click F5) 	
						row.delete();	
						setTimeout(function(){
						window.location.reload(true);
						},1000);	
						
						
					}
				}
			}
		}),
		// this example does not send data to the server so this variable holds the integer to use as an id for newly
		// generated rows. In production this value would be returned from the server upon a successful ajax call.
		uid=0;
		/* UPDATE: I also wanted the id value to be updated when we create a new row, this row ID should be based on the length of the rows.json file (e.g. if there are 4 lines in the rows.json file make the id 5 for the 5th line). I added another php request (this time using GET) */
		$.ajax({
			type: "GET",
			url: "rowid.php",
			cache: false,
			success: function(data) {
            uid = data;
			console.log(data);
			},
			error: function(data) {
			console.log(data);
			}
		
		});
		//saving a row//
	$editor.on('submit', function(e){
		if (this.checkValidity && !this.checkValidity()) return;// if validation fails exit early and do nothing.
		e.preventDefault();// stop the default post back from a form submit
		var row = $modal.data('row'),// get any previously stored row object
			values = { // create a hash of the editor row values
				id: $editor.find('#id').val(),
				user: $editor.find('#user').val(),
				Name: $editor.find('#Name').val(),
				showTitle: $editor.find('#showTitle').val(),
				recommendedOn: $editor.find('#recommendedOn').val(),
				rating: $editor.find('#rating option:selected').val(),
				types: $editor.find('#types option:selected').val(),
				options: $editor.find('#options option:selected').val(),
				comments: $editor.find('#comments').val()
			};

		if (row instanceof FooTable.Row){
		// if we have a row object then this is an edit operation
		// here you can execute an ajax call to the server and then only update the row once the result is
		// retrieved. This example simply updates the row straight away.
		row.val(values);
		
			$.ajax({
						type: "POST",
						url: "editrow.php",
						cache: false, //this cache line is used so that the website wont keep the rows so it stays updated
						contentType: "application/json",
						dataType: "json",
						data: JSON.stringify({
							id: row.val().id,
							user: row.val().user,
							Name: row.val().Name,
							showTitle: row.val().showTitle,
							recommendedOn:  row.val().recommendedOn,
							rating: row.val().rating,
							types: row.val().types,
							options: row.val().options,
							comments: row.val().comments,
							updown: "<button type=\"btnup\" onclick=\"$(document).ready(btns($(this).closest('tr'),'up'));\" class=\"btn btn-default btn-sm\"> <span class=\"glyphicon glyphicon-plus\"></span></button><button type=\"btndown\" onclick=\"$(document).ready(btns($(this).closest('tr'),'down'));\" class=\"btn btn-default btn-sm\"> <span class=\"glyphicon glyphicon-minus\"></span></button>"							
							
						})
						
		//				,success: function(response) {
		//					console.log(response);
		//					
		//					},
		//				error: function(response) {
		//					console.log(response);
		//					}
							});

							//UPDATE: I also wanted to make sure that the browser updates right after we add any new information I added a reload page line to right after we delete a row, and add a new row. (so that you don’t have to click F5) 
		setTimeout(function(){
		window.location.reload(true);
		},1000);	
		
		} else {
		// otherwise this is an add operation
		// here you can execute an ajax call to the server to save the values and get the new row id and then
		// only add the row once the result is retrieved. This example simply adds the row straight away using
		// a basic integer id.
			
				
			$.ajax({
			type: "POST",
			url: "savedata.php",
			cache: false,//this cache line is used so that the website wont keep the rows so it stays updated
			contentType: "application/json",
			dataType: "json",
			data: JSON.stringify({
				//id: $('#id').val(),
				id: uid,
				user: 0,
				Name: $('#Name').val(),
				showTitle: $('#showTitle').val(),
				recommendedOn:  $('#recommendedOn').val(),
				rating: $('#rating option:selected').val(),
				types: $('#types option:selected').val(),
				options: $('#options option:selected').val(),
				comments: $('#comments').val(),
				updown: "<button type=\"btnup\" onclick=\"$(document).ready(btns($(this).closest('tr'),'up'));\" class=\"btn btn-default btn-sm\"> <span class=\"glyphicon glyphicon-plus\"></span></button><button type=\"btndown\" onclick=\"$(document).ready(btns($(this).closest('tr'),'down'));\" class=\"btn btn-default btn-sm\"> <span class=\"glyphicon glyphicon-minus\"></span></button>"
        })
        //,success: function(response) {
        //    console.log(response);
        //},
        //error: function(response) {
        //    console.log(response);
        //}
		
		}); 
		//alert("Please wait while information is saving (you will be notified again)");
			$.ajax({
			type: "POST",
			url: "sendupdate.php",
			cache: false, //this cache line is used so that the website wont keep the rows so it stays updated
			contentType: "application/json",
			dataType: "json",
			data: JSON.stringify({
				id: uid,
				user: 0,
				Name: $('#Name').val(),
				showTitle: $('#showTitle').val(),
				recommendedOn:  $('#recommendedOn').val(),
				rating: $('#rating option:selected').val(),
				types: $('#types option:selected').val(),
				options: $('#options option:selected').val(),
				comments: $('#comments').val()
			})
			
			,error: function(response) {
				console.log(response);
			//alert(response['responseText']);
				}
				});
		//values.id = uid++;
					
		ft.rows.add(values);
		window.location.reload(true);
		//UPDATE: I also wanted to make sure that the browser updates right after we add any new information I added a reload page line to right after we delete a row, and add a new row. (so that you don’t have to click F5) 
		}
		
		$modal.modal('hide');
	});
});

<script>
		//call our AJAX function on load (if you want to dynamically update menu without reload we can add some continuous calls to this as well)
		window.onload = FetchMenu();
	
		var xmlHttp
		var website
		var menu


		//this function inits our ajax var and sends the request to our php code.
		function FetchMenu()
		{ 
			xmlHttp=GetXmlHttpObject();
			if (xmlHttp==null)
			{
			   alert ("Your browser does not support AJAX!");
			   return;
			} 
			var url="FetchMenu.php";
			url=url+"?sid="+Math.random(); //this line is needed to ensure we don't get cached results, it adds ?sid=####### to the url where #### is a random number
			xmlHttp.onreadystatechange=stateChanged; //setting the callback function for when the AJAX call completes
			xmlHttp.open("GET",url,true);
			xmlHttp.send(null);
		}

		//this function is called by xmlHttp.onreadystatechange when our AJAX call to the php code completes
		function stateChanged() 
		{ 
			if (xmlHttp.readyState==4)
			{ 
				
				var json = xmlHttp.responseText;
				menu = JSON && JSON.parse(json) || $.parseJSON(json);
				
			   
			   //going to build up the rows/columns, with the top level group names being on their own, 
			   // and then the category titles being 2 line returns tall and items being 1 tall
			   //
			   // will take 1 group per row
			   //
			   
			   var itemCount;
			   var currentCount;               
			   var itemsPerColumn;
			   var groupTitle;
			   var text="";
			   var text2="";
			   var column = new Array();

			   //loop through all 3 levels Groups/Categories/items and build up the menu text
				for( i=0; i < menu.menus.length; i++ )
				{
					
					itemCount = 0;
					itemsPerColumn = 0;
					currentCount=0; 
					
					groupTitle = menu.menus[i].name;
					column[0]="";
					column[1]="";
					column[2]="";
					var noValues = true;
					var noValCount = 0;
					if(groupTitle != 'Drafts'){continue;}
					groupTitle = 'Tap List';
					// loop through the lower 2 levels and couont up number of "line returns" we will need. 
					// Counting the category lines twice as the <h4> is 2 lines high
					for( j=0; j < menu.menus[i].menu_categories.length; j++ )
					{
						for( k=0; k < menu.menus[i].menu_categories[j].menu_items.length; k++ )
						{
							//don't output blank line, check if empty
							if( menu.menus[i].menu_categories[j].menu_items[k].description != "" && menu.menus[i].menu_categories[j].menu_items[k].description != null ) //change ".name" to .description here and below if wanting to change output source for items
							{
								noValues = false;
								
							}
						}
						if(!noValues)
						{
							itemCount+=2;
							for( k=0; k < menu.menus[i].menu_categories[j].menu_items.length; k++ )
							{
								if( menu.menus[i].menu_categories[j].menu_items[k].description != "" && menu.menus[i].menu_categories[j].menu_items[k].description != null ) //change ".name" to .description here and below if wanting to change output source for items
								{
									itemCount++;
								}
							}
						}
						else
						{
							noValCount++;
						}
					}
					
					//alert(groupTitle + " noValCount=" + noValCount + ", numCategories="+ menu.menus[i].menu_categories.length);
					if(noValCount != menu.menus[i].menu_categories.length)
					{
						// to split across 3 columns we will div by 3
						itemsPerColumn = (itemCount / 3);
						
						// now loop through lower two levels again and count along as you go, store the text added in one of three indexes in our column array
						// get the floor of current position divided by itemsPerColumn to put in correct place
						
						for( j=0; j < menu.menus[i].menu_categories.length; j++ )
						{
							var noValues = true;
							for( k=0; k < menu.menus[i].menu_categories[j].menu_items.length; k++ )
							{
								//don't output blank line, check if empty
								if( menu.menus[i].menu_categories[j].menu_items[k].description != "" && menu.menus[i].menu_categories[j].menu_items[k].description != null ) //change ".name" to .description here and below if wanting to change output source for items
								{
									noValues = false;
								}
							}
						
							if(menu.menus[i].menu_categories[j].menu_items.length > 0 && !noValues)
							{
								// To avoid the category title being the last element in a colum we do this check
								if(Math.floor( currentCount / itemsPerColumn) < Math.floor( (currentCount+3) / itemsPerColumn) && itemsPerColumn > 3)
								{
									currentCount+=3;
								}
								
								//change ".name" to .description here change output source for categories
								column[ Math.floor( currentCount / itemsPerColumn) ] += "<h4>" + menu.menus[i].menu_categories[j].name + "</h4>";
								currentCount++;
								
								for( k=0; k < menu.menus[i].menu_categories[j].menu_items.length; k++ )
								{
									//don't output blank line, check if empty
									if( menu.menus[i].menu_categories[j].menu_items[k].description != "" && menu.menus[i].menu_categories[j].menu_items[k].description != null ) //change ".name" to .description here and below if wanting to change output source for items
									{
										//change ".name" to .description here and above if wanting to change output source for items
										column[ Math.floor( currentCount / itemsPerColumn) ] += "<li>" + menu.menus[i].menu_categories[j].menu_items[k].description + "</li>";
									}
									currentCount++;
								}
							}
						}
						
						//take all the stuff we've gathered up and just flat output it
						// we could store this groupTitle and columns in another var and out put later if we want to reorder things
						
						text += '<div class="row">\n';
						text += '     <div class="col-lg-12">\n';
						text += '         <h3>' + groupTitle + '</h3>\n';
						text += '         <div class="col col-sm-4 col-lg-4">\n<ul>\n';
						text += '            ' + column[0] + '\n</ul>\n';
						text += '         </div>\n';
						text += '         <div class="col-xs-6 col-sm-4">\n<ul>\n';
						text += '            ' + column[1] + '\n</ul>\n';
						text += '         </div>';
						text += '         <div class="col-xs-6 col-sm-4">\n<ul>\n';
						text += '            ' + column[2] + '\n</ul>\n';
						text += '         </div>';
						text += '     </div>';
						text += ' </div><!--end row -->\n';
					}
				}
				
				//take the text string we built up and assign it to the div with id menu
				document.getElementById("menu").innerHTML = text;
			}
		}
		
		//function to initialize AJAX
		function GetXmlHttpObject()
		{
			var xmlHttp=null;
			try
			{
				// Firefox, Opera 8.0+, Safari
				xmlHttp=new XMLHttpRequest();
			}
			catch (e)
			{
				// Internet Explorer
				try
				{
					xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch (e)
				{
					xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
				}
			}
			
			return xmlHttp;
		}
	</script>
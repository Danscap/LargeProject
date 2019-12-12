// code.js -- POOP Group 13

//hello
var urlBase = 'http://pooptopoos.com';
var extension = "php";

var userId = 0;
var contactId = 0;
var s = 0;


//userId of current user and teacherId of prof being rated 
var uId; // line 49
var tId; // line 157
var currentComment;





/* LOGIN TAB */


function doRegistration()
{

   userId = 0;
   document.getElementById("loginResult").innerHTML = "";

   // Get the username and password typed in by the user
	var uName = document.getElementById("uName").value;
	var passWord = document.getElementById("pWord").value;

   // Setup the json that will be sent to the server and the url
   var jsonPayload = JSON.stringify({login:uName, password:passWord});
	var url = urlBase + '/api/signup.' + extension;

   // Prep for sending the json payload to the server
	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

	try
	{
  
	   xhr.send(jsonPayload);

      xhr.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
            
            var jsonObject = JSON.parse(xhr.responseText);

            // Set the userId and check to make sure it was changed, if so, print error and return
            userId = jsonObject.id;
            if (userId < 1)
            {
               document.getElementById("loginResult").innerHTML = jsonObject.error;
               return;
            }

            // Reset the username and password
            document.getElementById("uName").value = "";
            document.getElementById("pWord").value = "";

            // Change the page from the login page
            hideOrShow("contacts", true);
            hideOrShow("login", false);
            //location.href = 'contacts.html';
			}
		};

	}
	catch(err)
	{
		document.getElementById("loginResult").innerHTML = err.message;
	}
}


function doLogin()
{
   
   userId = 0;
   document.getElementById("loginResult").innerHTML = "";

   // Get the username and password typed in by the user
	var uName = document.getElementById("uName").value;
	var passWord = document.getElementById("pWord").value;

   // Setup the json that will be sent to the server and the url
	var jsonPayload = JSON.stringify({login:uName, password:passWord});
	var url = urlBase + '/api/login.' + extension;

   // Prep for sending the json payload to the server
	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

   try
   {
      // Send the json payload to the server
      xhr.send(jsonPayload);

      xhr.onreadystatechange = function()
      {
         if (this.readyState == 4 && this.status == 200)
         {
            // Parse the response from the server
            var jsonObject = JSON.parse(xhr.responseText);

            // Set the userId and check to make sure it was changed, if so, print the error and return
      		userId = jsonObject.id;
      		var num = userId.toString()
      		
      		//store userID in local storage for access in other html pages
      		localStorage.setItem("USERID", num)
      		
      		
      		uId = jsonObject.id;
      		if (userId < 1)
      		{
      			document.getElementById("loginResult").innerHTML = jsonObject.error;
      			return;
      		}

            // Reset the username and password
      		document.getElementById("uName").value = "";
      		document.getElementById("pWord").value = "";

            // Change the page from the login page
      		//hideOrShow("contacts", true);
      		hideOrShow("login", false);
      		
      		
      		//retrieveContacts();
            //location.href = 'contacts.html';
            location.href = 'index.html'
         }
      };

   }
   catch(err)
   {
      document.getElementById("loginResult").innerHTML = err.message;
   }
}

function doLogout()
{

   
   window.localstorage.clear()

   location.href = 'index.html';
}

function hideOrShow(elementId, showState)
{
   // Handles what the HTML should show on the webpage
	var vis = "visible";
	var dis = "block";

	if (!showState)
	{
		vis = "hidden";
		dis = "none";
	}

	document.getElementById(elementId).style.visibility = vis;
	document.getElementById(elementId).style.display = dis;
}


/*END LOGIN TAB */





/* RATINGS TAB */

//used in ratings.html to search for prof

function getProfessor()
{
//   document.getElementById("contactRetrieveResult").innerHTML = "";

    var search = document.getElementById("teacherSearchBar").value
   var jsonPayload = '{"search" : "' + search + '"}';
   var url = urlBase + '/api/searchTeachers.' + extension;


    var suggestedResult = document.getElementById("teacherSearchResult")
   var xhr = new XMLHttpRequest();
   xhr.open("POST", url, true);
   xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

   try
   {
      xhr.send(jsonPayload);

      xhr.onreadystatechange = function()
      {
         if (this.readyState == 4 && this.status == 200)
         {
            var jsonObject = JSON.parse(xhr.responseText);
            //contactId = jsonObject.tableId;
            if(jsonObject.error.length > 0)
			   {
				   suggestedResult.innerHTML = "No teachers were found.";
				   return;
			   }

            var i;
            var result = ""
            for (i = 0; i < jsonObject.firstName.length; i++)
				{
                result += jsonObject.firstName[i] + " " + jsonObject.lastName[i] + "\n"
			   }
			   
			   suggestedResult.innerHTML = result
			   hideOrShow("teacherSearchResult", false)
			 //  suggestedResult.style.backgroundColor = "red"
			    var ratingField = document.getElementById("createARating")
			    ratingField.innerHTML = ratingField.innerHTML + " for " + result
			    
			    tId = jsonObject.teacherId[0];
			 
         }
      };
   }
   catch(err)
   {
      document.getElementById("contactSearchResult").innerHTML = err.message;
   }
}



function doComment()
{
    var comment = document.getElementsByName("commentBox")[0]

    var data = comment.value

    currentComment = data
    
    var realuserid = localStorage.getItem("USERID")
    
  var jsonPayload = JSON.stringify({userId:realuserid, teacherId:tId, comment:data});

   var url = urlBase + '/api/addComment.' + extension;


    // var suggestedResult = document.getElementById("teacherSearchResult")
   var xhr = new XMLHttpRequest();
   xhr.open("POST", url, true);
   xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

   try
   {
      xhr.send(jsonPayload);

      xhr.onreadystatechange = function()
      {
         if (this.readyState == 4 && this.status == 200)
         {
  
         }
      };
   }
   catch(err)
   {
      document.getElementById("contactSearchResult").innerHTML = err.message;
   }
    
}

function doRating()
{
    var slider1 = document.getElementById("outputSlider1").textContent
    var slider2 = document.getElementById("outputSlider2").textContent
    var slider3 = document.getElementById("outputSlider3").textContent
    var slider4 = document.getElementById("outputSlider4").textContent
    var slider5 = document.getElementById("outputSlider5").textContent
    
    //take care of comment
    doComment()
    

    
    var realuserId = localStorage.getItem("USERID")
    
    var search = document.getElementById("teacherSearchBar").value
      var jsonPayload = JSON.stringify({approachabilityRating:slider1,appropriateWorkRating:slider2,gradeFairnessRating:slider3,lectureEffectivenessRating:slider4,knowledgeOfMatRating:slider5,teacherId:tId,userId:realuserId})
      var url = urlBase + '/api/createReview.' + extension;
    
    
        // var suggestedResult = document.getElementById("teacherSearchResult")
      var xhr = new XMLHttpRequest();
      xhr.open("POST", url, true);
      xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
    
      try
      {
          xhr.send(jsonPayload);
    
          xhr.onreadystatechange = function()
          {
             if (this.readyState == 4 && this.status == 200)
             {
                var jsonObject = JSON.parse(xhr.responseText);
                //contactId = jsonObject.tableId;
                if(jsonObject.error.length > 0)
    			   {
    				   errorRating.innerHTML = "Review could not be created.";
    				   return;
    			   }
    
                hideOrShow("successRating", true)
             }
          };
      }
      catch(err)
      {
          //document.getElementById("contactSearchResult").innerHTML = err.message;
      }
    
    
}


/* END RATINGS TAB */









/*TEACHERS TAB */

function myFunction() {
              var popup = document.getElementById("myPopup");
              popup.classList.toggle("show");
            }
            
            
            //figure this out later
function showCard()
{
    var card = document.getElementById("whatsthis")
    
    hideOrShow("whatsthis", true)
    document.body.onclick = function()
    {
        function doThis(){
            card.style.visibility = "hidden"
            card.style.display = "none"
            
        }
        setTimeOut(doThis, 2000)
        
    }
    // setTimeout(hideOrShow("whatsthis",false), 3000)
}

function getCommentsForProf()
{

    
    let realtId = localStorage.getItem("TEACHERID")
    
    
    var jsonPayload = '{"teacherId" : "' + realtId + '"}';
   var url = urlBase + '/api/getCommentsForOneProfessor.' + extension;

   var xhr = new XMLHttpRequest();
   xhr.open("POST", url, true);
   xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

   try
   {
      xhr.send(jsonPayload);

      xhr.onreadystatechange = function()
      {
         if (this.readyState == 4 && this.status == 200)
         {
            var jsonObject = JSON.parse(xhr.responseText);
            //contactId = jsonObject.tableId;
            if(jsonObject.error.length > 0)
			   {
				   document.getElementById("commentList").innerHTML = "No comments were found.";
				   return;
			   }
            var list = document.getElementById('commentList');
            
            var i;
            var commentTableCount;
            commentTableCount = 0;
            
            
            if(list.childNodes.length > 0)
            {
                var last;
                while (last = list.lastChild) list.removeChild(last);
               
            }
            
            
            for (i = 0; i < jsonObject.userId.length; i++)
				{
				    //get  teacherTable
    			    commentTableCount++
                   
                  console.log(list)
                //   var tbody = list.childNodes[2];
                   let tr = document.createElement("li");
                   
                    //   s = jsonObject.userId[i];
                    //give each tr an ID
                    
                       tr.setAttribute("id", "teacherTable"+commentTableCount);
                       tr.setAttribute("class", "list-group-item")
                       
                    //   tr.className += " popuptext"
                       
                    //   tr.setAttribute("class", )
                    tr.onclick = function()
                    {
                        tr.classList.toggle("list-group-item-success")
                        // tr.classList.toggle("show")
                        
                    }
                   
                   tr.textContent = commentTableCount.toString() + ". " + jsonObject.comment[i]
                   list.appendChild(tr);
			}
         }
      };
   }
   catch(err)
   {
      document.getElementById("contactSearchResult").innerHTML = err.message;
   }
}



//used in showTeacherComments.html

function getProfessorInComments()
{
//   document.getElementById("contactRetrieveResult").innerHTML = "";

    var search = document.getElementById("teacherSearchBar").value
   var jsonPayload = '{"search" : "' + search + '"}';
   var url = urlBase + '/api/searchTeachers.' + extension;


    var suggestedResult = document.getElementById("teacherSearchResult")
   var xhr = new XMLHttpRequest();
   xhr.open("POST", url, true);
   xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

   try
   {
      xhr.send(jsonPayload);

      xhr.onreadystatechange = function()
      {
         if (this.readyState == 4 && this.status == 200)
         {
            var jsonObject = JSON.parse(xhr.responseText);
            //contactId = jsonObject.tableId;
            if(jsonObject.error.length > 0)
			   {
				   suggestedResult.innerHTML = "No teachers were found.";
				   return;
			   }

            var i;
            var result = ""
            for (i = 0; i < jsonObject.firstName.length; i++)
				{
                result += jsonObject.firstName[i] + " " + jsonObject.lastName[i] + "\n"
			   }
			   
			   suggestedResult.innerHTML = result
			   hideOrShow("teacherSearchResult", true)
			 //  suggestedResult.style.backgroundColor = "red"
			   
			    tId = jsonObject.teacherId[0];
			    
			 
			    localStorage.removeItem("TEACHERID")
			    localStorage.setItem("TEACHERID", tId)
			 
			    
			    getCommentsForProf()
         }
      };
   }
   catch(err)
   {
      document.getElementById("contactSearchResult").innerHTML = err.message;
   }
   
   
}



function populateTeachersTable()
{
//   document.getElementById("contactRetrieveResult").innerHTML = "";

   var jsonPayload = '{"userId" : "' + userId + '"}';
   var url = urlBase + '/api/getAllTeachers.' + extension;

   var xhr = new XMLHttpRequest();
   xhr.open("POST", url, true);
   xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

   try
   {
      xhr.send(jsonPayload);

      xhr.onreadystatechange = function()
      {
         if (this.readyState == 4 && this.status == 200)
         {
            var jsonObject = JSON.parse(xhr.responseText);
            //contactId = jsonObject.tableId;
            if(jsonObject.error.length > 0)
			   {
				   document.getElementById("contactSearchResult").innerHTML = "No contacts were found.";
				   return;
			   }

            var i;
            var teacherTableCount;
            teacherTableCount = 0;
            for (i = 0; i < jsonObject.firstName.length; i++)
				{
				    //get  teacherTable
    			    teacherTableCount++
                   var table = document.getElementById('teacherTable');
                   console.log(table.childNodes)
                   var tbody = table.childNodes[2];
                   let tr = document.createElement("tr");
                   
                    //   s = jsonObject.userId[i];
                    //give each tr an ID
                    
                       tr.setAttribute("id", "teacherTable"+teacherTableCount);
                       
                    //   tr.className += " popuptext"
                       
                    //   tr.setAttribute("class", )
                    tr.onclick = function()
                    {
                        tr.classList.toggle("table-success")
                        // tr.classList.toggle("show")
                        
                    }
                   
                   tr.innerHTML = '<td>' + teacherTableCount + '</td>' +
                   '<td>' + jsonObject.firstName[i] + '</td>' +
                   '<td>' + jsonObject.lastName[i] + '</td>' +
                   '<td>' + jsonObject.courses[i] + '</td>' ;
                   table.appendChild(tr);
			}
         }
      };
   }
   catch(err)
   {
      document.getElementById("contactSearchResult").innerHTML = err.message;
   }
}

/* END TEACHERS TAB*/










/* COURSES TAB */

function populateCoursesTable()
{
//   document.getElementById("contactRetrieveResult").innerHTML = "";

   var jsonPayload = '{"userId" : "' + userId + '"}';
   var url = urlBase + '/api/getAllCourses.' + extension;

   var xhr = new XMLHttpRequest();
   xhr.open("POST", url, true);
   xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

   try
   {
      xhr.send(jsonPayload);

      xhr.onreadystatechange = function()
      {
         if (this.readyState == 4 && this.status == 200)
         {
            var jsonObject = JSON.parse(xhr.responseText);
            //contactId = jsonObject.tableId;
            if(jsonObject.error.length > 0)
			   {
				   document.getElementById("contactSearchResult").innerHTML = "No contacts were found.";
				   return;
			   }

            var i;
            var coursesTableCount;
            coursesTableCount = 0;
            for (i = 0; i < jsonObject.courseName.length; i++)
				{
				    //get  teacherTable
			    coursesTableCount++
               var table = document.getElementById('coursesTable');
               console.log(table.childNodes)
               var tbody = table.childNodes[2];
               var tr = document.createElement("tr");
               
            //   s = jsonObject.userId[i];
            //give each tr an ID
            
               tr.setAttribute("id", "coursesTable"+coursesTableCount);
               
               
               tr.innerHTML = '<td>' + coursesTableCount + '</td>' +
               '<td>' + jsonObject.courseName[i] + '</td>' +
               '<td>' + jsonObject.courseTitle[i] + '</td>' 
            //   +'<td>' + jsonObject.courses[i] + '</td>';
               table.appendChild(tr);
			   }
         }
      };
   }
   catch(err)
   {
      document.getElementById("contactSearchResult").innerHTML = err.message;
   }
}

/*END COURSES TAB */

















/* CONTACT MANAGER FUNCTIONS ONLY BELOW */

/*



*/



function addContact()
{
   // Get the contact info from the HTML
   var cFName = document.getElementById("cFirstName").value;
   var cLName = document.getElementById("cLastName").value;
   var cPhoneNum = document.getElementById("cPhoneNumber").value;
   var cEmail = document.getElementById("cEmail").value;
   document.getElementById("contactAddResult").innerHTML = "";

   // Prepare to send the contact info to the server
   var jsonPayload = '{"cFirstName" : "' + cFName + '", "cLastName" : "' + cLName + '", "cPhoneNum" : "' + cPhoneNum + '", "cEmail" : "' + cEmail + '", "userId" : "' + userId + '"}';
   var url = urlBase + '/api/createContact.' + extension;

   // Create and open a connection to the server
   var xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

   try
	{
      // Send the json payload
      xhr.send(jsonPayload);

		xhr.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
			    
			    
			    var table = document.getElementById('gibberish');
               var tr = document.createElement("tr");
               
              
               
               tr.innerHTML = '<td>' + document.getElementById("cFirstName").value + '</td>' +
               '<td>' + document.getElementById("cLastName").value + '</td>' +
               '<td>' + document.getElementById("cEmail").value + '</td>' +
               '<td>' + document.getElementById("cPhoneNumber").value + '</td>';
               table.appendChild(tr);
            // Clear the add contact fields
         	document.getElementById("cEmail").value = "";
         	document.getElementById("cFirstName").value = "";
         	document.getElementById("cLastName").value = "";
         	document.getElementById("cPhoneNumber").value = "";
		    document.getElementById("contactAddResult").innerHTML = "Contact has been added";
			 // deleteTable();
			 // retrieveContacts();
			 
			 
			}
		};
	}
	catch(err)
	{
		document.getElementById("contactAddResult").innerHTML = err.message;
	}
}

function retrieveContacts()
{
//   document.getElementById("contactRetrieveResult").innerHTML = "";

   var jsonPayload = '{"userId" : "' + userId + '"}';
   var url = urlBase + 'api/retrieveContacts.' + extension;

   var xhr = new XMLHttpRequest();
   xhr.open("POST", url, true);
   xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

   try
   {
      xhr.send(jsonPayload);

      xhr.onreadystatechange = function()
      {
         if (this.readyState == 4 && this.status == 200)
         {
            var jsonObject = JSON.parse(xhr.responseText);
            //contactId = jsonObject.tableId;
            if(jsonObject.error.length > 0)
			   {
				   document.getElementById("contactSearchResult").innerHTML = "No contacts were found.";
				   return;
			   }

            var i;
            for (i = 0; i < jsonObject.firstName.length; i++)
				{
               var table = document.getElementById('gibberish');
               var tr = document.createElement("tr");
               
              s = jsonObject.userId[i];
               tr.setAttribute("id", "insertedTable1"+s);
               tr.innerHTML = '<td>' + jsonObject.firstName[i] + '</td>' +
               '<td>' + jsonObject.lastName[i] + '</td>' +
               '<td>' + jsonObject.email[i] + '</td>' +
               '<td>' + jsonObject.phoneNumber[i] + '</td>';
               table.appendChild(tr);
			   }
         }
      };
   }
   catch(err)
   {
      document.getElementById("contactSearchResult").innerHTML = err.message;
   }
}

function searchContact()
{
   var srch = document.getElementById("searchContact").value;
	document.getElementById("contactSearchResult").innerHTML = "";

	var jsonPayload = '{"search" : "' + srch + '", "userId" : "' + userId + '"}';
	var url = urlBase + 'api/SearchContacts.' + extension;

	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

	try
	{
      xhr.send(jsonPayload);

		xhr.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
			   document.getElementById("contactSearchResult").innerHTML = "Contact(s) found";

				var jsonObject = JSON.parse(xhr.responseText);
				if (jsonObject.error.length > 0)
				{
				   document.getElementById("contactSearchResult").innerHTML = "No contacts were found.";
				   return;
				}

            contactId = jsonObject.tableId;

				var i;
				for (i = 0; i < jsonObject.firstName.length; i++)
				{
               var table = document.getElementById('gibberish2');
               var tr = document.createElement("tr");
               s = jsonObject.userId[i];

               tr.setAttribute("id", "insertedTable2"+s);
               tr.innerHTML = '<td>' + jsonObject.firstName[i] + '</td>' +
               '<td>' + jsonObject.lastName[i] + '</td>' +
               '<td>' + jsonObject.email[i] + '</td>' +
               '<td>' + jsonObject.phoneNumber[i] + '</td>' +
               '<td><button type="button" class="button" onclick="deleteContact(' + (s) + ');">Delete</button></td>';

               table.appendChild(tr);
				}
			}
		};
	}
	catch(err)
	{
		document.getElementById("contactSearchResult").innerHTML = err.message;
	}
}

function deleteTable()
{
   //document.getElementById("gibberish").remove();
   var mainContactTable = document.getElementById("gibberish");
   mainContactTable.parentNode.removeChild(mainContactTable);
}


function deleteContact(id)
{
//   document.getElementById("contactDeleteResult").innerHTML = "";
   contactId = id;

   var jsonPayload = '{"tableId" : "' + contactId + '", "UserId" : "' + userId + '"}';
	var url = urlBase + 'api/deleteContact.' + extension;

	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

	try
	{
      xhr.send(jsonPayload);

		xhr.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
            var searchContactTable = document.getElementById("insertedTable2"+id);
            searchContactTable.parentNode.removeChild(searchContactTable);

            // deleting current row in search contact result table
            //var mainContactTable = document.getElementById("insertedTable1"+id);
            //mainContactTable.parentNode.removeChild(mainContactTable);

            //document.getElementById("contactDeleteResult").innerHTML = "Contact has been deleted";
            //var jsonObject = JSON.parse(xhr.responseText);
            
            // Deleteing table
            mainContactTable = document.getElementById("gibberish");
            mainContactTable.parentNode.removeChild(mainContactTable);
            
                var mainContact = document.getElementById("contacts");
               // var table = document.getElementById('gibberish2');
                var table = document.createElement("table");
               // tr.setAttribute("id", "insertedTable2"+s);
               table.setAttribute("class", "contacts");
               table.setAttribute("id", "gibberish");
               table.setAttribute("border", "1");
               table.innerHTML = '<tr>' + '<th>FirstName</th>' + '<th>Last Name</th>' + '<th>E-Mail</th>' + '<th>Phone Number</th>'
               '</tr>';
               mainContact.appendChild(table);
                
               
            retrieveContacts();
			}
		};
	}
	catch(err)
	{
		document.getElementById("contactSearchResult").innerHTML = err.message;
	}
}

function teach()
{
    
}

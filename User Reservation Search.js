/*
 Code references demo code provided in Tutorial 6 of CMM004 - usingAjax.js
 */
$(function()
    {
    $("#searchButton").click(function()
                                {
                                searchUser();
                                }
                        );

    
    searchUser();
    } 
);


function searchUser()
{
var keyword=$("#keyword").val(); 
populateSection(keyword);             
} 


function populateSection(keyword)
{
var url="User Reservation Search.php";
var data={"keyword":keyword};   


$.getJSON(  url,
            data,
            function(result)
            {
                $("#result_display").empty();   
                for (var index in result)      
                {
                    var reservations=result[index];  
                    var htmlCode="<ul>";   
                    htmlCode+="<li>Username: "+reservations["username"]+"</li>"; 
                    htmlCode+="<li>Item User Reserved: "+reservations["title"]+"</li>";
                    htmlCode+="<li>Date Reservation Made: "+reservations["reservation_date"]+"</li></ul><br>";
                    $("#result_display").append(htmlCode);     
                }
            } 
        ); 
} 
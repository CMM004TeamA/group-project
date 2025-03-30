/*
 Code references demo code provided in Tutorial 6 of CMM004 - usingAjax.js
 */

 function searchReservations() {
    var keyword = $("#keyword").val();
    populateSection(keyword);
}

$(function() {
    $("#searchButton").click(function() {
        searchReservations();
    });

    $("#keyword").on("keyup", function() {
        searchReservations();
    });

    searchReservations();
});

function populateSection(keyword) {
    var url = "User Reservation Search.php";
    var data = { "keyword": keyword };

    $.getJSON(url,
        data,
        function(result) {
        $("#result_display tbody").empty(); 
        for (var index in result) 
            {
            var reservations = result[index]; 
            
            // Code taken from Copilot to convert reservation_date to DD-MM-YYYY
            var originalDate = new Date(reservations["reservation_date"]);
            var formattedDate = originalDate.getDate().toString().padStart(2, '0') + "-" + 
                                (originalDate.getMonth() + 1).toString().padStart(2, '0') + "-" + 
                                originalDate.getFullYear();
            // End of code taken from Copilot

            var htmlCode="<tr>";                        
                    htmlCode+="<td>"+reservations["reservation_id"]+"</td>"; 
                    htmlCode+="<td>"+reservations["username"]+"</td>";
                    htmlCode+="<td>"+reservations["title"]+"</td>";
                    htmlCode+="<td>"+formattedDate +"</td>";
                    htmlCode+="<td>"+reservations["status_name"]+"</td>";
                    htmlCode+="</tr>";
            $("#result_display tbody").append(htmlCode);
        }
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error("AJAX request failed:", textStatus, errorThrown);
    });
}


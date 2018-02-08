$(document).ready(function(){
  RefreshPeople();
  var states;
  GetStates();
  console.log(states);

  $('#peopleList').click(function(e){
    //this should grab the selected person, and load their data into the other objects
    //load up the Details
    GetPersonDetails(e.target.id);
    //load up the Visits
    GetPersonVisits(e.target.id);
    //select the person in the list
    SelectPersonList(e.target.id);

  });

  function RefreshPeople() {
    //make ajax call to get list of people
    $.ajax({
      type: "GET",
      url: "/api/people",
      success: function(result)
      {
        //build the list from the result and insert it into the correct element
        var list = JSON.parse(result);
        var htmlString = '';
        for (var i = 0; i < list.length; i++) {
          htmlString += "<a href=#  class='list-group-item list-group-item-action' id=" + list[i]['id'] + ">" + list[i]['first_name'] + " " + list[i]['last_name'] + "</a>";
        }

        document.getElementById('peopleList').innerHTML = htmlString;
      }
    });

  }

  function GetPersonDetails(id) {
    $.ajax({
      type: "GET",
      url: "/api/people/" + id,
      success: function(result)
      {
        //build the list from the result and insert it into the correct element
        var list = JSON.parse(result);
        var htmlString = "Name: " + list[0]['first_name'] + " " + list[0]['last_name'] + "</br>"
        + "Favorite Food: " + list[0]['favorite_food'];

        document.getElementById('detailCard').innerHTML = htmlString;
      }
    });
  }

  function GetPersonVisits(id) {

    $.ajax({
      type: "GET",
      url: "/api/states",
      success: function(result)
      {
        //build the list from the result and insert it into the correct element
        stateTable = JSON.parse(result);

        //do another ajax call to get state list? or do it once on load?
        $.ajax({
          type: "GET",
          url: "/api/people/" + id + "/visits",
          success: function(result)
          {
            //build the list from the result and insert it into the correct element
            var list = JSON.parse(result);
            var htmlString = "<thead class='thead-dark'>"
                            + "<th>State</th>"
                            + "<th>Date</th>"
                            + "</thead>"
                            + "<tbody>";

            for (var i = 0; i < list.length; i++) {
              var state;
              for(var j = 0; j < stateTable.length; j++) {
                if (list[i]['state_id'] == stateTable[j]['id']) {
                  state = stateTable[j];
                }
              }
              htmlString += "<tr>"
                        + "<td>" + state['state_name'] + " (" + state['state_abbreviation'] + ")</td>"
                        + "<td>" + list[i]['date_visited'] + "</td>"
                        + "</tr>";
            }

            htmlString += "</tbody>";
            document.getElementById('visitTable').innerHTML = htmlString;
          }
        });
      }
    });
  }

  function GetStates() {
    var stateTable2 = {a:1};
    $.ajax({
      type: "GET",
      url: "/api/states",
      success: function(result1,stateTable)
      {
        //build the list from the result and insert it into the correct element
        stateTable1 = JSON.parse(result1);
            console.log(stateTable1);
      }
    });
    //stateTable = JSON.parse(objResponse.responseText);

  }

  function SelectPersonList(id) {
    var list = document.getElementById('peopleList').childNodes;
    for (var i = 0; i < list.length; i++) {
      if(list[i].id == id) {
        list[i].setAttribute("class","list-group-item list-group-item-action active");
      }
      else {
        list[i].setAttribute("class","list-group-item list-group-item-action");
      }
    }
  }

});

$(document).ready(function(){
  RefreshPeople();
  LoadStates();

  $('#peopleList').click(function(e){
    //this should grab the selected person, and load their data into the other objects
    var list = document.getElementById('peopleList').childNodes;
    var testTarget = e.target.getAttribute("class");

    //if the name just clicked on is not the active name then show all the data
    if(testTarget.search("active") == -1) {
      var tempId = e.target.id.split("_");
      //load up the Details
      GetPersonDetails(tempId[1]);
      //load up the Visits
      GetPersonVisits(tempId[1]);
      //select the person in the list
      SelectPersonList(e.target.id);

      $('#detailsCollapse').collapse('show');
    }
    else {
      //hide the card that shows all the data
      $('#detailsCollapse').collapse('hide');
      e.target.setAttribute("class","list-group-item list-group-item-action list-group-item-info");
    }

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
        var htmlString1 = '';
        var htmlString2 = "<option selected>Select A Person</option>";
        for (var i = 0; i < list.length; i++) {
          htmlString1 += "<a href=#  class='list-group-item list-group-item-action list-group-item-info' id=listItem_" + list[i]['id'] + ">" + list[i]['first_name'] + " " + list[i]['last_name'] + "</a>";
          htmlString2 += "<option id=selPerson_" + list[i]['id'] + ">" + list[i]['first_name'] + " " + list[i]['last_name'] + "</option>";
        }

        document.getElementById('peopleList').innerHTML = htmlString1;
        document.getElementById('visitModalPerson').innerHTML = htmlString2;
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
            var htmlString = "<thead class='thead-info'>"
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

  function SelectPersonList(id) {
    var list = document.getElementById('peopleList').childNodes;
    for (var i = 0; i < list.length; i++) {
      if(list[i].id == id) {
        list[i].setAttribute("class","list-group-item list-group-item-action list-group-item-info active");
      }
      else {
        list[i].setAttribute("class","list-group-item list-group-item-action list-group-item-info");
      }
    }
  }

  function LoadStates() {
    $.ajax({
      type: "GET",
      url: "/api/states",
      success: function(result)
      {
        var htmlString = '<option selected>Select A State</option>';
        //build the list from the result and insert it into the correct element
        stateTable = JSON.parse(result);
        for(var i = 0; i < stateTable.length; i++) {
          htmlString += "<option id=selState_" + stateTable[i]['id'] + ">" + stateTable[i]['state_name'] + " (" + stateTable[i]['state_abbreviation'] + ")</option>";
        }
        document.getElementById('visitModalState').innerHTML = htmlString;
      }
    });
  }

});

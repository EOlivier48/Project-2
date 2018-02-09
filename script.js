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

  $('#createNewVisit').click(function(e){
    var es = document.getElementById('visitModalState');
    var ep = document.getElementById('visitModalPerson');
    var ed = document.getElementById('visitModalDate');

    var xhrData = {'state_id': es.options[es.selectedIndex].value,
                'person_id': ep.options[ep.selectedIndex].value,
                'date_visited': ed.value};
    $.ajax({
      type: "POST",
      url: "/api/visits",
      data: JSON.stringify(xhrData),
      success: function(result)
      {
        var list = document.getElementsByClassName("list-group-item list-group-item-action list-group-item-info active");
        //console.log(list[0].id);
        if(list !== 'undefined' && list.length > 0) {
          listIdArr = list[0].id.split("_");
          GetPersonVisits(listIdArr[1]);
        }
        $('#newVisitModal').modal('hide');
        //console.log(xhrData);
      }
    });

  });

  $('#createNewPerson').click(function(e){
    var efn = document.getElementById('personModalFN');
    var eln = document.getElementById('personModalLN');
    var eff = document.getElementById('personModalFF');

    var xhrData = {'first_name': efn.value,
                'last_name': eln.value,
                'favorite_food': eff.value};

    $.ajax({
      type: "POST",
      url: "/api/people",
      data: JSON.stringify(xhrData),
      success: function(result)
      {
        RefreshPeople();
        $('#newPersonModal').modal('hide');
      }
    });

  });

  $('#newVisitModal').on('hidden.bs.modal', function () {
    $(this).find("input,textarea,select").val('').end();
  });

  $('#newPersonModal').on('hidden.bs.modal', function () {
    $(this).find("input,textarea,select").val('').end();
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
          htmlString2 += "<option value=" + list[i]['id'] + ">" + list[i]['first_name'] + " " + list[i]['last_name'] + "</option>";
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
          htmlString += "<option value=" + stateTable[i]['id'] + ">" + stateTable[i]['state_name'] + " (" + stateTable[i]['state_abbreviation'] + ")</option>";
        }
        document.getElementById('visitModalState').innerHTML = htmlString;
      }
    });
  }

});

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Trip Tracker</title>
  </head>
  <body>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
<!-- remplaced with full jquery as the slim version leaves out ajax
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
-->
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <nav class="navbar navbar-expand-lg navbar-dark bg-info">
      <a class="navbar-brand" href="#">Trip Tracker</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link disabled" href="#">Add A New</a>
          </li>
          <form class="form-inline">
            <div class="btn-group-vertical">
              <button type="button" class="btn btn-outline-light" data-toggle="modal" data-target="#newVisitModal">
                Visit
              </button>
              <button type="button" class="btn btn-outline-light" data-toggle="modal" data-target="#newPersonModal">
                Person
              </button>
            </div>
          </form>
        </ul>
      </div>
    </nav>

    <div class="container justify-content-center">
      <div id="accordion">
        <div class="card">
          <div class="card-header" id="headingOne">
            <h5 class="mb-0">
              <button class="btn btn-info" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                People
              </button>
            </h5>
          </div>
          <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
            <div class="card-body text-center">
              <div class="container justify-content-center">
                <div class="list-group list-group-flush" id="peopleList">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="container justify-content-center">
    <div class="collapse" id="detailsCollapse">
      <div class="card border-info text-white">
        <div class="card-header text-center bg-info">
          <h3>Details</h3>
        </div>
        <div class="card-body text-info" id="detailCard">
          Name: None</br>
          Favorite Food: N/A</br>
        </div>
      </div>
      <div class="card border-info">
        <div class="card-header text-center text-white bg-info">
          <h3>Visits</h3>
        </div>
        <div class="card-body">
          <table class="table table-striped table-bordered table-info col-6" id="visitTable">
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="newPersonModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="newPersonModalLabel">Add A New Person</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form>
            <div class="form-group">
              <label for="firstName" class="col-form-label">First Name:</label>
              <input type="text" class="form-control" id="firstName">
            </div>
            <div class="form-group">
              <label for="lastName" class="col-form-label">Last Name:</label>
              <input type="text" class="form-control" id="lastName">
            </div>
            <div class="form-group">
              <label for="favoriteFood" class="col-form-label">Favorite Food:</label>
              <input type="text" class="form-control" id="favoriteFood">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-info">Add Person</button>
        </div>
      </div>
    </div>
  </div>


  <div class="modal fade" id="newVisitModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="newVisitModalLabel">Add A New Visit</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form>
            <div class="form-group">
              <label for="visitModalPerson" class="col-form-label">Person:</label>
              <select class="custom-select" id="visitModalPerson">
                <option selected>Select A Person</option>
              </select>
            </div>
            <div class="form-group">
              <label for="visitModalState" class="col-form-label">State:</label>
              <select class="custom-select" id="visitModalState">
                <option selected>Select A State</option>
              </select>
            </div>
            <div class="form-group">
              <label for="visitModalDate" class="col-form-label">Date Visited:</label>
              <input type="date" class="form-control" id="visitModalDate">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-info">Add Person</button>
        </div>
      </div>
    </div>
  </div>

    <script src="script.js"></script>
  </body>
</html>

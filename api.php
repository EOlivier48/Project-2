<?php
$requestURI = parse_url($_SERVER['REQUEST_URI']);
$segments = explode('/', rtrim($requestURI['path'],'/'));

$method = '';
$endpoint = '';
$args = Array();

$requestURI = parse_url($_SERVER['REQUEST_URI']);
$args = explode('/', rtrim($requestURI['path'],'/'));

$method = $_SERVER['REQUEST_METHOD'];

include 'projectlib.php';

//this first element is the endpoint of the request and, together with the
//request method, dictates how we will handle it
//first we grab the endpoint off the array of arguments

$trash = array_shift($args);
$start = array_shift($args);
if($start == 'api') {
  $endpoint = array_shift($args);
  switch($endpoint) {
    case 'people':
      echo people($args,$method);
      break;
    case 'visits':
      echo visits($args,$method);
      break;
    case 'states':
      echo states($args,$method);
      break;
    default:
      //return 400 bad request
      echo response("No Endpoint: " . $endpoint, 404);
      break;
  }
}
else {
  echo response("Not Found", 404);
}


function people($args,$method) {
  $m = new ModelClass;
  if($args[0] == NULL) {
    //people
    if($method == 'GET') {
      //GET return all people
      $m->initModel();
      $result = $m->listUsers();
      if(sizeof($result) > 0) {
        return response($result);
      }
      else {
        return response("People Not Found",404);
      }
    }
    else {
      //return 405 method not allowed
      return response("Method Not Allowed", 405);
    }
  }
  elseif(is_numeric($args[0])) {
    if(array_key_exists(1,$args) && !is_numeric($args[1])) {
      //people/#/...
      if($args[1] == 'visits') {
        //people/#/visits
        if($method == 'GET') {
          //GET return all visits for a particular person
          $m->initModel();
          $result = $m->listVisitsUser($args[0]);
          if(sizeof($result) > 0) {
            return response($result);
          }
          else {
            return response("Visits Not Found",404);
          }
        }
        else {
          //return 405 method not allowed
          return response("Method Not Allowed", 405);
        }
      }
      else {
        //people/#/invalid
        //bomb out, return 400 (bad request)
        return response("Bad Request", 400);
      }
    }
    else {
      //people/#
      switch ($method) {
      //If method is:
        case 'GET':
          //GET return 200 (ok) and json for the person they asked for
          $m->initModel();
          $result = $m->listUser($args[0]);
          if(sizeof($result) > 0) {
            return response($result);
          }
          else {
            return response("People Not Found",404);
          }
          break;
        case 'POST':
          $bodyData = getBodyData();
          if($bodyData['first_name'] && $bodyData['last_name'] && $bodyData['favorite_food']) {
            $m->initModel();
            $result = $m->addUser($bodyData['first_name'],$bodyData['last_name'],$bodyData['favorite_food']);
            if($result) {
              return response("Ok");
            }
            else {
              return response("Bad Request",400);
            }
          }
          break;
//        case 'PUT':
            //PUT return 200 (ok) if updated, 404 (not found) if ID is not valid
//          break;
//        case 'DELETE':
            //DELETE return 200 (ok), 404 (not found) if ID is not valid
//          break;
        default:
          //return 405 method not allowed
          return response("Method Not Allowed", 405);
          break;
      }
    }
  }
  else {
    //people/invalid
    //return 400 bad request
    return response("Bad Request", 400);
  }
}

function visits($args,$method) {
  $m = new ModelClass;
  if($args[0] == NULL) {
    //visits
    switch ($method) {
    //If method is:
      case 'GET':
        //GET return 200 (ok) and json for the visit they asked for
        $m->initModel();
        $result = $m->listVisits();
        if(sizeof($result) > 0) {
          return response($result);
        }
        else {
          return response("Visits Not Found",404);
        }
        break;
      case 'POST':
        $bodyData = getBodyData();
        if($bodyData['person_id'] && $bodyData['state_id'] && $bodyData['date_visited']) {
          $m->initModel();
          $result = $m->addVisit($bodyData['person_id'],$bodyData['state_id'],$bodyData['date_visited']);
          if($result) {
            return response("Ok");
          }
          else {
            return response("Bad Request",400);
          }
        }
        break;
//      case 'DELETE':
          //DELETE return 200 (ok), 404 (not found) if ID is not valid
//        break;
      default:
        //return 405 method not allowed
        return response("Method Not Allowed", 405);
        break;
      }
  }
  elseif(is_numeric($args[0])) {
    //visits/#
    switch ($method) {
    //If method is:
      case 'GET':
        //GET return 200 (ok) and json for the visit they asked for
        $m->initModel();
        $result = $m->listVisit($args[0]);
        if(sizeof($result) > 0) {
          return response($result);
        }
        else {
          return response("People Not Found",404);
        }
        break;
//      case 'PUT':
          //PUT return 200 (ok) if updated, 404 (not found) if ID is not valid
//        break;
//      case 'DELETE':
          //DELETE return 200 (ok), 404 (not found) if ID is not valid
//        break;
      default:
        //return 405 method not allowed
        return response("Method Not Allowed", 405);
        break;
    }
  }
  else {
    if($args[0] == 'states' && array_key_exists(1,$args) && is_numeric($args[1])) {
      //visits/states/#
      if($method == 'GET') {
        //return all visits for that state
        $m->initModel();
        $result = $m->listVisitsState($args[1]);
        if(sizeof($result) > 0) {
          return response($result);
        }
        else {
          return response("Visits Not Found",404);
        }
      }
      else {
        //return 405 method not allowed
        return response("Method Not Allowed", 405);
      }
    }
    elseif($args[0] == 'people' && array_key_exists(1,$args) && is_numeric($args[1])) {
      //visits/people/#
      if($method == 'GET') {
        //return all visits for that person
        $m->initModel();
        $result = $m->listVisitsUser($args[1]);
        if(sizeof($result) > 0) {
          return response($result);
        }
        else {
          return response("People Not Found",404);
        }
      }
      else {
        //return 405 method not allowed
        return response("Method Not Allowed", 405);
      }
    }
    else {
      //visits/invalid
      //400 bad request
      return response("Bad Request", 400);
    }
  }
}

function states($args,$method) {
  $m = new ModelClass;
  if($args[0] == NULL) {
    //states
    if($method == 'GET') {
      //return all states
      $m->initModel();
      $result = $m->listStates();
      if(sizeof($result) > 0) {
        return response($result);
      }
      else {
        return response("States Not Found",404);
      }
    }
    else {
      //return 405 method not allowed
      return response("Method Not Allowed", 405);
    }
  }
  elseif(is_numeric($args[0])) {
    //states/#
    if($method == 'GET') {
      //return specific state
      $m->initModel();
      $result = $m->listState($args[0]);
      if(sizeof($result) > 0) {
        return response($result);
      }
      else {
        return response("State Not Found",404);
      }
    }
    else {
      //return 405 method not allowed
      return response("Method Not Allowed", 405);
    }
  }
  else {
    //states/invalid
    //return 400 bad request
    return response("Bad Request", 400);
  }
}

function response($data,$status = 200) {
    header("HTTP/1.1 " . $status . " " . statusText($status));
    return json_encode($data);
}

function statusText($code) {
    $status = array(
        200 => 'OK',
        400 => 'Bad Request',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        500 => 'Internal Server Error',
    );
    return ($status[$code])?$status[$code]:$status[500];
}

function getBodyData() {
  $reqBody = file_get_contents('php://input');
  return json_decode($reqBody);
}
//header('application/json');
//echo(json_encode($apiVars));
die();

?>

<?php
$retrievedUser = $_SERVER['PHP_AUTH_USER'];
$retrievedPassword = $_SERVER['PHP_AUTH_PW'];
$time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];


$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true);
header('Content-Type: application/json;charset=utf-8');

#Auth
function auth($user, $password)
{
    global $retrievedUser;
    global $retrievedPassword;
    if (($user != $retrievedUser) or ($password != $retrievedPassword))
    {
        echo "Put your Credentials Correctly";
        die();
    }
}

// INTENTS and FILTERS

function intent($intentName)
{
    global $input;
    if ($input["queryResult"]["intent"]["displayName"] == $intentName)
    {
        return true;
    }
    else
    {
        return false;
    }
}

function action($actionName)
{
    global $input;
    if ($input["queryResult"]["action"] == $actionName)
    {
        return true;
    }
    else
    {
        return false;
    }
}

// PARAMETERS

function getIntentParameter()
{
    global $input;
    if (isset($input["queryResult"]["parameters"]))
    {
        return $input["queryResult"]["parameters"];
    }
    else
    {
        return false;
    }
}


function laregr(){
  $numargs = func_num_args();
  $large_array = array();
  if($numargs){
      $arg_list = func_get_args();
      $max = 0;
      foreach($arg_list as $arg){
          if(is_array($arg)){
              $big = count($arg);
              if($big >= $max ){
                  $max = $big;
                  $large_array = $arg;
              }
          }
      }
  }
  return $large_array;
}


function getContextParameter($contextIndex)
{
  global $input;
    if ($contextIndex == "auto"){

      $arrays = $input["queryResult"]["outputContexts"];

      rsort($arrays);

      return $arrays[0]["parameters"];


    } else {


      if (isset($input["queryResult"]["outputContexts"][$contextIndex]["parameters"])){
        return $input["queryResult"]["outputContexts"][$contextIndex]["parameters"];
      } else {
        return false;
      }
    }
}


// TRIGGERS

function triggerEvent(array $eventName,array $params){

  $raw ='{
    "followupEventInput": {
      "name": "'.$eventName[0].'",
      "languageCode": "en-US",
      "parameters": {';

        foreach($params as $param => $value):
          $raw .= ' "'.$param.'":"'.$value.'",';
        endforeach;
          $raw = rtrim($raw, ",");
  $raw .= '}}}'.PHP_EOL;
  echo $raw;
}

function triggerError($context,$contextBody, array $intentName,array $errorMessage, array $params){

  if($context == true){
    echo('{"fulfillmentMessages": [{"text": {"text": ["'.$intentName[0]."💥: ".$errorMessage[0].'"],"outputContexts":['.$contextBody.']}}]}'.PHP_EOL);
  } else {
    echo('{"fulfillmentMessages": [{"text": {"text": ["'.$intentName[0]."💥: ".$errorMessage[0].'"]}}]}'.PHP_EOL);
  }
}


function triggerPropmt($context,$contextBody,array $prompt){
  if($context == true){
    $raw = '{"fulfillmentMessages": [{"text": {"text": ["'.$prompt[0].'"]}}] ,"outputContexts":[';
    $raw .= $contextBody;
    $raw .= ']}'.PHP_EOL;
    echo($raw);
  } else {
    echo('{"fulfillmentMessages": [{"text": {"text": ["'.$prompt[0].'"]}}]}'.PHP_EOL);
  }
}


// CONTEXT

function setContextParameter($contextIndex, $variableName, $variableValue){
  global $input;

  if(is_array($variableName)){
    $variableName = $variableName[0];
  }

  if(is_array($variableValue)){
    $variableValue = $variableValue[0];
  }

  if ($contextIndex == "auto"){
    $arrays = $input["queryResult"]["outputContexts"];
    rsort($arrays);
    $context = $arrays[0]["parameters"]["$variableName"] = $variableValue;
    $context =  json_encode($context);
    return $context;
  } else {
    $context = $input["queryResult"]["outputContexts"][$contextIndex];
    $context["parameters"]["$variableName"] = $variableValue;
    $context =  json_encode($context);
    return $context;
  }

}

function updateContextParameter($contextIndex,$variableName,$variableValue){
  global $input;

  if(is_array($variableName)){
    $variableName = $variableName[0];
  }

  if(is_array($variableValue)){
    $variableValue = $variableValue[0];
  }

  if ($contextIndex == "auto"){
    $arrays = $input["queryResult"]["outputContexts"];
    rsort($arrays);

    if(isset($arrays[0]["parameters"]["$variableName"])){

    $context = $arrays[0]["parameters"]["$variableName"] = $variableValue;
    $context =  json_encode($context);
    return $context;
    } else {
      return false;
    }
  } else {

    if(isset($input["queryResult"]["outputContexts"][$contextIndex]["parameters"]["$variableName"])){
      $context = $input["queryResult"]["outputContexts"][$contextIndex];
      $context["parameters"]["$variableName"] = $variableValue;
      $context =  json_encode($context);
      return $context;
    } else {
      return false;
    }
  }
}

function setContextParameters($contextIndex,array $parameters){

  global $input;

  if(is_array($contextIndex)){
    $contextIndex = $contextIndex[0];
  }


  if ($contextIndex == "auto"){
    $arrays = $input["queryResult"]["outputContexts"];
    rsort($arrays);

    $context = $arrays[0];

    foreach($parameters as $variable => $value):
      $context["parameters"]["$variable"] = $value;
    // array_push($context["parameters"]["$variable"] = $value);
    endforeach;

    $context =  json_encode($context);
    return $context;

  } else {

    $context = $input["queryResult"]["outputContexts"][$contextIndex];

    foreach($parameters as $variable => $value):
      $context["parameters"]["$variable"] = $value;
    // array_push($context["parameters"]["$variable"] = $value);
    endforeach;

    $context =  json_encode($context);
    return $context;

  }
}


function updateContextParameters($contextIndex, array $parameters){

  global $input;

  if(is_array($contextIndex)){
    $contextIndex = $contextIndex[0];
  }

  if ($contextIndex == "auto"){
    $arrays = $input["queryResult"]["outputContexts"];
    rsort($arrays);

    $context = $arrays[0];

    foreach($parameters as $variable => $value):

      if(isset($input["queryResult"]["outputContexts"][$contextIndex]["parameters"]["$variable"])){
        $context["parameters"]["$variable"] = $value;
      }
    // array_push($context["parameters"]["$variable"] = $value);

    $context["parameters"]["$variable"] = $value;
    endforeach;
    $context =  json_encode($context);
    return $context;

  } else {


    $context = $input["queryResult"]["outputContexts"][$contextIndex];

    foreach($parameters as $variable => $value):

      if(isset($input["queryResult"]["outputContexts"][$contextIndex]["parameters"]["$variable"])){
        $context["parameters"]["$variable"] = $value;
      }
    // array_push($context["parameters"]["$variable"] = $value);

    $context["parameters"]["$variable"] = $value;
    endforeach;
    $context =  json_encode($context);
    return $context;


  }



}


// TROUBLESHOOT

function getInput(){
  global $input;
  return $input;
}

function getIntent()
{
  global $input;
  $intent = $input["queryResult"]["intent"]["displayName"];
  return $intent;
}

// set a the name that your file with the request will have
function createInput($fileName){

  if(is_array($fileName)){
    $fileName = $fileName[0];
  }

  global $input;
  $raw = json_encode($input);
  $file = fopen("$fileName.txt", "w");
  fwrite($file, $raw . PHP_EOL);
  fclose($file);

}

function createOutput($fileName,$raw){
  global $input;

  if(is_array($fileName)){
    $fileName = $fileName[0];
  }

  if(is_array($raw)){
    $raw = $raw[0];
  }


  $file = fopen("$fileName.txt", "w");
  fwrite($file, $raw . PHP_EOL);
  fclose($file);

}


// retrieves data from a different source (mostly integrations)
function getSourceData($fileName){
  global $input;

  if(is_array($fileName)){
    $fileName = $fileName[0];
  }

 $raw = $input['originalDetectIntentRequest'];
 $file = fopen("$fileName.txt", "w");
 fwrite($file, $raw . PHP_EOL);
 fclose($file);

}

function getTimeOut(){
  global $time;
  $time *= 1000;
  $time = round($time,2);
  return $time;
}

// retrieves user input
function getUserInput(){
  global $input;
  $userInput = $input["queryResult"]['queryText'];
  return $userInput;
}

// MISCELANEOUS


// retrieves dialogflow projectname
function getProjectName(){
  global $input;
  if(isset($input["session"])){
    $inputs = explode("/",$input["session"]);
    $projectName = 1;
    return $inputs[$projectName];
  } else {
    return false;
  }
}

// retrieves dialogflow session id
function getSessionId(){
  global $input;
  if(isset($input["session"])){
    $inputs = explode("/",$input["session"]);
    $session = 4;
    return $inputs[$session];
  } else {
    return false;
  }
}

// retrieves whatsapp phone number
function getWhatsAppPhoneNumber()
{
    global $input;
    $userPhone = substr(intval(preg_replace('/[^0-9]+/', '', $input["session"]), 10),-10);
    return $userPhone;
}


//  retrieves facebook contact id
function getContactId(){
  global $input;
  $input['originalDetectIntentRequest']['payload']['contact']['cId'] ? $contactId = $input['originalDetectIntentRequest']['payload']['contact']['cId'] : $contactId = "contact Id";
  return $contactId;
}

//  retrieves telegram chat id
function getTelegramChatId()
{
    global $input;
    $chatId = $input["originalDetectIntentRequest"]["payload"]["data"]["chat"]["id"];
    return $chatId;
}


function getTelegramCallbackId(){

  global $input;

  if($input['originalDetectIntentRequest']['payload']['data']['callback_query']['id']){
    return $input['originalDetectIntentRequest']['payload']['data']['callback_query']['id'];
  } else {
        return false;
  }

}






?>

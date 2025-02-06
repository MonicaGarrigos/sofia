<?php


// create transcript
$intentName = getIntent();


if($intentName != "saludo"){

  $source = 'Functionality';
  $userResponse = getUserInput();
  $sessionId = getContextParameter("auto")['config']['sessionId'];

  $raw = getInput();
  $userInput = getUserInput();

  $createTranscript = Transcripts::createTranscript($sessionId,$source,$intentName,$raw);


}


?>

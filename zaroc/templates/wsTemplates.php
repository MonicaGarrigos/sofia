<?php

function wsParagraphTemplate($context,$contextBody, array $wsTitle, array $wsTextArray){

    if($context == true){
      dfOpen();
        structureOpen($wsTitle);
          wsParagraph($wsTextArray);
        structureClose();
        comma();
        contextOpen();
          echo($contextBody);
        contextClose();
      dfClose();
    } else {
      dfOpen();
        structureOpen($wsTitle);
          wsParagraph($wsTextArray);
        structureClose();
      dfClose();
    }
}

function wsButtonTemplate($context,$contextBody, array $wsTitle, $element){

  if($context == true){
    dfOpen();
      structureOpen($wsTitle);
        openPayload();
        echo($element);
        closePayload();
      structureClose();
      comma();
      contextOpen();
        echo($contextBody);
      contextClose();
    dfClose();
  } else {
    dfOpen();
      structureOpen($wsTitle);
      openPayload();
      echo($element);
      closePayload();
      structureClose();
    dfClose();
  }
}



function wsStructureTemplate($context,$contextBody, array $wsTitle,array $structure,array $components){
dfOpen();
  structureOpen($wsTitle);
  for($i = 0; $i < count($structure); $i++):
    if($structure[$i] == 'comma'){
      comma();
    }
    if($structure[$i] == 'paragraph'){
      wsParagraph($components[$i][0]);
    }

    if($structure[$i] =='button'){
      // wsButtons($title,$imageUrl$footer,$buttonTitle,$buttonPostback);
      wsButton($components[$i][0],$components[$i][1],$components[$i][2],$components[$i][3],$components[$i][4]);
    }

    if($structure[$i] == 'openPayload' ){
        openPayload();
    }
    if($structure[$i] == 'closePayload' ){
        closePayload();
    }



  endfor;
  structureClose();
  if($context == true){
    comma();
    contextOpen();
      echo($contextBody);
    contextClose();
  }
dfClose();
}




?>

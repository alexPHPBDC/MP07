<?php
function treatFunctions(array $functions): array
{
    //Given some letters, no need to work with functions with more than 7 different chars
    $treatedFunct = array();
    foreach ($functions as $string) {

        if (strlen(count_chars($string, 3)) <= 7) {

    
                $treatedFunct[] = $string;

        }
    }

    return $treatedFunct;
}

mostUsedChars();
function mostUsedChars()
{

    $functions = treatFunctions(get_defined_functions()["internal"]);
    

    foreach ($functions as $index => $function){
        foreach (count_chars(strtolower($function), 1) as $char => $nTimes) {
            if(isset($ocurrences[chr($char)])){
                $ocurrences[chr($char)] +=$nTimes;
            }else{
                $ocurrences[chr($char)] =$nTimes;
            }
        }
    }
    $letters = $ocurrences;
    /*$bon = array_reverse($letters);

    $aux = array_pop($bon);

    $next = $aux;

    foreach($letters as $index => $value){
        $well[$index] = $next;
        $aux = array_pop($bon);
        $next += $aux+1;

    }


  $well = array_reverse($well, true);
  return $well;

*/

print_r($letters);
}





?>
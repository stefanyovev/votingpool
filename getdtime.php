<? session_start(); 

function microtime_float()
{
   list($usec, $sec) = explode(" ", microtime());
   return ((float)$usec + (float)$sec);
}

?><body onload="parent.document.getElementById('dtime').innerHTML='<? echo round(microtime_float()-$_SESSION["start_time"],3); ?>';"></body>
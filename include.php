<?php
  date_default_timezone_set("America/New_York");
  #$_SERVER["mysqli"] = new mysqli("narominetworks.db.7261298.hostedresource.com", "narominetworks", "hf3cPjWbGfZrd0", "narominetworks");
  session_start();
  function set_message($status, $content) {
    $_SESSION["message"]["status"] = $status;
    $_SESSION["message"]["content"] = $content;
  }
  function return_message($status, $content, $array = null) {
    $array["message"]["status"] = $status;
    $array["message"]["content"] = $content;
    exit(json_encode($array));
  }
?>

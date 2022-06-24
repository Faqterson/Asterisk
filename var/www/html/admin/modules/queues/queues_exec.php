<?php

function db_query($sqlquery,$data)
{
        $con_str = 'DRIVER={MySQL};SERVER=localhost;DATABASE=asterisk';
        $user = 'root';
        $pass = '';
        $con = odbc_connect( $con_str, $user, $pass );

        $sqlrow = array();

        $prep = odbc_prepare($con, $sqlquery);
        if(!$prep) die("could not prepare statement ".$query_string);
        $result = odbc_execute($prep, $data);

        While ($tmp = odbc_fetch_array($prep))
        {
                array_push($sqlrow, $tmp);
        }
        odbc_close($con);
        return $sqlrow;
}

      $name = $_POST['name'];

      if ($_POST['recordrequest'] == "1") {

         db_query("DELETE FROM extensions WHERE appdata = ?", array('record-prompt,s,1('.$name.'-queue)'));

         $row = db_query("SELECT MAX(CAST(SUBSTR(exten, 2) AS UNSIGNED)) as max FROM extensions WHERE appdata REGEXP '^record-prompt'", array());
         $recordcode = $row[0]['max']+1;
         if ($row[0]['max'] != null) {
            db_query("REPLACE INTO extensions (context,exten,priority,app,appdata) VALUES (?,?,?,?,?)", array('extensions','*'.$recordcode,'1','Gosub','record-prompt,s,1('.$name.'-queue)'));
//            echo "<script>alert('Your recording code is *$recordcode');</script>";
//            echo "Your recording code is *$recordcode";
         } else {
            db_query("REPLACE INTO extensions (context,exten,priority,app,appdata) VALUES (?,?,?,?,?)", array('extensions','*1000','1','Gosub','record-prompt,s,1('.$name.'-queue)'));
//            echo "<script>alert('Your recording code is *1000');</script>";
//            echo "Your recording code is *1000";
         }
      } 

      if ($_POST['dynamicagents'] == "1") {
         $dynamicagents = $_POST['dynamicagents'];

         db_query("DELETE FROM extensions WHERE appdata = ?", array('agent-login,s,1('.$name.')'));
         db_query("DELETE FROM extensions WHERE appdata = ?", array('agent-logoff,s,1('.$name.')'));

         $loginrow = db_query("SELECT MAX(CAST(SUBSTR(exten, 5) AS UNSIGNED)) as max FROM extensions WHERE appdata REGEXP '^agent-login'", array());
         $logoffrow = db_query("SELECT MAX(CAST(SUBSTR(exten, 5) AS UNSIGNED)) as max FROM extensions WHERE appdata REGEXP '^agent-logoff'", array());
         $login = $loginrow[0]['max']+1;
         $logoff = $logoffrow[0]['max']+1;
         if ($loginrow[0]['max'] != null) {
            db_query("REPLACE INTO extensions (context,exten,priority,app,appdata) VALUES (?,?,?,?,?)", array('extensions','*605'.$login,'1','Gosub','agent-login,s,1('.$name.')'));
            db_query("REPLACE INTO extensions (context,exten,priority,app,appdata) VALUES (?,?,?,?,?)", array('extensions','*606'.$logoff,'1','Gosub','agent-logoff,s,1('.$name.')'));
//            echo "<script>alert('Your Login code is *$login and Logoff code is *$logoff');</script>";
//            echo "Your Login code is *$login and Logoff code is *$logoff";
         } else {
            db_query("REPLACE INTO extensions (context,exten,priority,app,appdata) VALUES (?,?,?,?,?)", array('extensions','*6051','1','Gosub','agent-login,s,1('.$name.')'));
            db_query("REPLACE INTO extensions (context,exten,priority,app,appdata) VALUES (?,?,?,?,?)", array('extensions','*6061','1','Gosub','agent-logoff,s,1('.$name.')'));
//            echo "<script>alert('Your Login code is *$login and Logoff code is *$logoff');</script>";
//            echo "Your Login code is *$login and Logoff code is *$logoff";
         }
      }

      $callcode = db_query("SELECT exten FROM extensions WHERE appdata = ?", array('queue,s,1('.$name.')'));

      if (!isset($callcode[0]['exten'])) {
         db_query("DELETE FROM extensions WHERE appdata = ?", array('queue,s,1('.$name.')'));

         $row = db_query("SELECT MAX(CAST(SUBSTR(exten, 2) AS UNSIGNED)) as max FROM extensions WHERE appdata REGEXP '^queue,s,1'", array());
         $callingcode = $row[0]['max']+1;
         if ($row[0]['max'] != null) {
            db_query("REPLACE INTO extensions (context,exten,priority,app,appdata) VALUES (?,?,?,?,?)", array('extensions','*'.$callingcode,'1','Gosub','queue,s,1('.$name.')'));
         } else {
            db_query("REPLACE INTO extensions (context,exten,priority,app,appdata) VALUES (?,?,?,?,?)", array('extensions','*500','1','Gosub','queue,s,1('.$name.')'));
         }
      } 

   if (!empty($_FILES)) {
      $target_dir = "/var/lib/asterisk/sounds/custom/";
      $target_file = $target_dir . basename($_FILES["periodic_announce"]["name"]);
      $uploadOk = 1;
      $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

      if(isset($_POST["submit"])) {
         $check = getimagesize($_FILES["periodic_announce"]["tmp_name"]);
         if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
         } else {
            echo "File is not an image.";
            $uploadOk = 0;
         }
      }

      // Check if file already exists
      if (file_exists($target_file)) {
         unlink($target_file);
         //echo "Sorry, file already exists.\n";
         $uploadOk = 1;
      }
      // Check file size
      if ($_FILES["periodic_announce"]["size"] > 10240000) {
         echo "Sorry, your file is too large.\n";
         $uploadOk = 0;
      }
      // Allow certain file formats
      if($imageFileType != "wav" && $imageFileType != "WAV" && $imageFileType != "mp3" && $imageFileType != "g729" ) {
         echo "Sorry, only WAV, MP3 & G729 files are allowed.\n";
         $uploadOk = 0;
      }

      // Check if $uploadOk is set to 0 by an error
      if ($uploadOk == 0) {
         echo "Sorry, your file was not uploaded.\n";
      // if everything is ok, try to upload file
      } else {
         if (move_uploaded_file($_FILES["periodic_announce"]["tmp_name"], $target_file)) {
            echo "The file ". basename( $_FILES["periodic_announce"]["name"]). " has been uploaded.\n";
            exec('sudo /usr/local/scripts/audioconvert '.$target_file);
         } else {
            echo "Sorry, there was an error uploading your file.\n";
         }
      }
   } else {
      Echo "No Files Uploaded";
   }
?>

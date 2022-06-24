<?php
function pagination($currentpage,$rowsperpage,$result,$page)
{
        $numrowsPage = sizeof($result);

        $maxPage = ceil($numrowsPage/$rowsperpage); // how many pages we have when using paging?
        $nav  = '';
        $previous = '';
        $next = '';

        if($maxPage<2)
        {
                $nav .= "<form class='pageform' action='".$currentpage."&page=1' method='post'>";
                $nav .= "<input class='nav_onpage' type='submit' value='1' disabled/>";
                $nav .= "</form>";
                $previous = '';
                $next = '';
        }
	else
	{
                $testminpage = $page-5;
                if($testminpage<1){
                        $minpage = 1;
                }else{
                      	$minpage = $testminpage;
                }
                if(($page+5)>$maxPage){
                        $nomorepages = $maxPage;
                }else{
                      	$nomorepages = $page+5;
                }
                for($pageNum = $minpage; $pageNum <= $nomorepages; $pageNum++) {
                        if ($pageNum == $page) {
                           if (isset($_POST["startdate"])) {
                              $nav .= "<form class='pageform' action='".$currentpage."&page=$pageNum' method='post'>";
                              $nav .= "<input class='button' type='hidden' name='startdate' value='".$_POST['startdate']."' />";
                              $nav .= "<input class='button' type='hidden' name='enddate' value='".$_POST['enddate']."' />";
                              $nav .= "<input class='button' type='hidden' name='clid' value='".$_POST['clid']."' />";
                              $nav .= "<input class='button' type='hidden' name='src' value='".$_POST['src']."' />";
                              $nav .= "<input class='button' type='hidden' name='dst' value='".$_POST['dst']."' />";
                              $nav .= "<input class='button' type='hidden' name='channel' value='".$_POST['channel']."' />";
                              $nav .= "<input class='button' type='hidden' name='dstchannel' value='".$_POST['dstchannel']."' />";
                              $nav .= "<input class='button' type='hidden' name='accountcode' value='".$_POST['accountcode']."' />";
                              $nav .= "<input class='button' type='hidden' name='userfield' value='".$_POST['userfield']."' />";
                              $nav .= "<input class='button' type='hidden' name='disposition' value='".$_POST['disposition']."' />";
                              $nav .= "<input class='button' type='hidden' name='answers' value='".$_POST['answers']."' />";
                              $nav .= "<input class='nav_onpage' type='submit' value='".$pageNum."' disabled/></form>";
                           } else {
                              $nav .= "<form class='pageform' action='".$currentpage."&page=$pageNum' method='post'>";
                              $nav .= "<input class='nav_onpage' type='submit' value='$pageNum' disabled/>";
                              $nav .= "</form>";
                           }
                        }else{
                           if (isset($_POST["startdate"])) {
                              $nav .= "<form class='pageform' action='".$currentpage."&page=$pageNum' method='post'>";
                              $nav .= "<input class='button' type='hidden' name='startdate' value='".$_POST['startdate']."' />";
                              $nav .= "<input class='button' type='hidden' name='enddate' value='".$_POST['enddate']."' />";
                              $nav .= "<input class='button' type='hidden' name='clid' value='".$_POST['clid']."' />";
                              $nav .= "<input class='button' type='hidden' name='src' value='".$_POST['src']."' />";
                              $nav .= "<input class='button' type='hidden' name='dst' value='".$_POST['dst']."' />";
                              $nav .= "<input class='button' type='hidden' name='channel' value='".$_POST['channel']."' />";
                              $nav .= "<input class='button' type='hidden' name='dstchannel' value='".$_POST['dstchannel']."' />";
                              $nav .= "<input class='button' type='hidden' name='accountcode' value='".$_POST['accountcode']."' />";
                              $nav .= "<input class='button' type='hidden' name='userfield' value='".$_POST['userfield']."' />";
                              $nav .= "<input class='button' type='hidden' name='disposition' value='".$_POST['disposition']."' />";
                              $nav .= "<input class='button' type='hidden' name='answers' value='".$_POST['answers']."' />";
                              $nav .= "<input class='button' type='submit' value='".$pageNum."' /></form>";
                           } else {
                              $nav .= "<form class='pageform' action='".$currentpage."&page=$pageNum' method='post'>";
                              $nav .= "<input class='button' type='submit' value='$pageNum' />";
                              $nav .= "</form>";
                           }
                        }
                }
                if (isset($_POST["startdate"])) {
                   $previous .= "<form class='pageform' action='".$currentpage."&page=1' method='post'>";
                   $previous .= "<input class='button' type='hidden' name='startdate' value='".$_POST['startdate']."' />";
                   $previous .= "<input class='button' type='hidden' name='enddate' value='".$_POST['enddate']."' />";
                   $previous .= "<input class='button' type='hidden' name='clid' value='".$_POST['clid']."' />";
                   $previous .= "<input class='button' type='hidden' name='src' value='".$_POST['src']."' />";
                   $previous .= "<input class='button' type='hidden' name='dst' value='".$_POST['dst']."' />";
                   $previous .= "<input class='button' type='hidden' name='channel' value='".$_POST['channel']."' />";
                   $previous .= "<input class='button' type='hidden' name='dstchannel' value='".$_POST['dstchannel']."' />";
                   $previous .= "<input class='button' type='hidden' name='accountcode' value='".$_POST['accountcode']."' />";
                   $previous .= "<input class='button' type='hidden' name='userfield' value='".$_POST['userfield']."' />";
                   $previous .= "<input class='button' type='hidden' name='disposition' value='".$_POST['disposition']."' />";
                   $previous .= "<input class='button' type='hidden' name='answers' value='".$_POST['answers']."' />";
                   $previous .= "<input class='button' type='submit' value='&laquo' /></form>";
                } else {
                   $previous .= "<form class='pageform' action='".$currentpage."&page=1' method='post'>";
                   $previous .= "<input class='button' type='submit' value='&laquo' />";
                   $previous .= "</form>";
                }
                if (isset($_POST["startdate"])) {
                   $next .= "<form class='pageform' action='".$currentpage."&page=$maxPage' method='post'>";
                   $next .= "<input class='button' type='hidden' name='startdate' value='".$_POST['startdate']."' />";
                   $next .= "<input class='button' type='hidden' name='enddate' value='".$_POST['enddate']."' />";
                   $next .= "<input class='button' type='hidden' name='enddate' value='".$_POST['enddate']."' />";
                   $next .= "<input class='button' type='hidden' name='clid' value='".$_POST['clid']."' />";
                   $next .= "<input class='button' type='hidden' name='src' value='".$_POST['src']."' />";
                   $next .= "<input class='button' type='hidden' name='dst' value='".$_POST['dst']."' />";
                   $next .= "<input class='button' type='hidden' name='channel' value='".$_POST['channel']."' />";
                   $next .= "<input class='button' type='hidden' name='dstchannel' value='".$_POST['dstchannel']."' />";
                   $next .= "<input class='button' type='hidden' name='accountcode' value='".$_POST['accountcode']."' />";
                   $next .= "<input class='button' type='hidden' name='userfield' value='".$_POST['userfield']."' />";
                   $next .= "<input class='button' type='hidden' name='disposition' value='".$_POST['disposition']."' />";
                   $next .= "<input class='button' type='hidden' name='answers' value='".$_POST['answers']."' />";
                   $next .= "<input class='button' type='submit' value='&raquo' /></form>";
                } else {
                   $next .= "<form class='pageform' action='".$currentpage."&page=$maxPage' method='post'>";
                   $next .= "<input class='button' type='submit' value='&raquo' />";
                   $next .= "</form>";
                }
        }
	// print the navigation link
        echo ''.$previous.'&nbsp;&nbsp;'.$nav.'&nbsp;&nbsp;'.$next.'';
}

function check_department_access()
{
        $user = $_SESSION['user_session']['uid'];
        $row = db_query("SELECT department FROM webusers WHERE user_id=?", array($user));
        $depallow = $row[0]['department'];
        return $depallow;
}

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
?>


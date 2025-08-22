<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    $id = $_SESSION['id'];
    $query_usershow = "SELECT * FROM user WHERE id='$id'";
    $result_usershow = mysqli_query($conn, $query_usershow);
    $row_usershow = mysqli_fetch_array($result_usershow);
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td><img class="img" alt="" src="Attachment/LawyerLogo.png" /></td>
        <td>
            <table dir="ltr" width="80%" border="0" cellspacing="4" cellpadding="4">
                <tr valign="top">
                    <th  dir="rtl" align="right" class="font_hacen">
                        <?php echo $row_usershow['username'];?>
                        <br/>
                        <font color="#000000">ت.آخر دخول</font>
                        <br />
                        (<font style="font-size:12px; color:#F00"><?php if(isset($row_usershow['lastlogin'])){echo $row_usershow['lastlogin'];} else{echo '-';}?></font>)
                        <br />
                        <a href="logout.php" title="تسجيل خروج"><span class="log_out"></span></a> 
                        <a href="change_userPassword.php" title="تغيير كلمة المرور" target="_blank"><span class="CPass"></span></a>
                    </th>
                    <td width="40%"><img src="images/noPhoto.png" width="120" class="contentimg"  align="absmiddle"/></td>
                </tr>
                <tr></tr>
            </table>
        </td>
    </tr>
</table>
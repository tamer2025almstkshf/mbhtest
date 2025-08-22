<?php
    include_once 'connection.php';
    include_once 'login_check.php';
?>

<form method="post" action="result_process.php" enctype="multipart/form-data">
    <tr valign="middle" align="center"  >
        <td colspan="3" class="font_hacen">
            <div id="search">
                <input type="text" name="SearchKey" value="" class="input_search"/>
                <input  type="button" class="s_btn" onclick="submit()" style="border:none; cursor:pointer" align="absmiddle" title="CE?E"/>
            </div>
            <input type="radio" name="Ckind" value="1" /> موكل
            <input type="radio" name="Ckind" value="2" /> خصم 
            <input type="radio" name="Ckind" value="3" /> مرفقات 
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="AdvancedSearch.php" class="Main" target="_blank">بحث متقدم</a>
        </td>
    </tr>
    <tr valign="middle" align="center">
        <td colspan="3"></td>
    </tr>
</form>
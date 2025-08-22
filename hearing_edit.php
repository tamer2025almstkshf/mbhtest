<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_check.php';
    include_once 'safe_output.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="rtl">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1256" />
        <title>القرارات والملاحظات</title>
        <link rel="stylesheet" type="text/css" href="css/sites.css">
        <link href="css/styles.css" rel="stylesheet" type="text/css" />
    </head>

    <body>
        <?php if($row_permcheck['session_aperm'] == 1 || $row_permcheck['session_eperm'] == 1){?>
        <form action="javascript:void(0);" method="post" name="addform" enctype="multipart/form-data" onsubmit="submitForm()">
            <?php
                $sid = safe_output($_GET['sid']);
                $stmt = $conn->prepare("SELECT * FROM session WHERE session_id=?");
                $stmt->bind_param("i", $sid);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_array();
                $stmt->close();
            ?>
            <input name="session_id" value="<?php echo safe_output($sid);?>" type="hidden">
            <input type='hidden' name='fid' value='<?php echo safe_output($_GET['fid']);?>'>
            
            <div class="input-container">
                <p class="input-parag">تفاصيل الجلسة</p>
                <textarea class="form-input" name="session_details" rows="2"><?php if(isset($row['session_details'])){ echo safe_output($row['session_details']); }?></textarea>
            </div>
            
            <div class="input-container">
                <p class="input-parag">قرار الجلسة</p>
                <textarea class="form-input" name="session_decission" rows="2"><?php if(isset($row['session_decission'])){ echo safe_output($row['session_decission']); }?></textarea>
            </div>
            
            <div class="input-container">
                <p class="input-parag">ملاحظات</p>
                <textarea class="form-input" name="session_notes" rows="2"><?php if(isset($row['session_notes'])){ echo safe_output($row['session_notes']); }?></textarea>
            </div>
            
            <div class="input-container">
                <p class="input-parag">تاريخ الجلسة</p>
                <input type="date" class="form-input" name="Hearing_dt" value="<?php if(isset($row['session_date'])){ echo safe_output($row['session_date']); }?>">
            </div><br>
            
            <div class="input-container" style="text-align: center">
                <input type="submit" class="green-button" value="حفظ البيانات" onclick="Action.value='Doupdate';submit()" class="button"/>
            </div>
        </form>
        <?php }?>
    </body>
</html>
    
<script>
    function submitForm() {
        const form = document.forms['addform'];
        const formData = new FormData(form);
    
        fetch('hedit.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.ok) {
                window.close();
                window.opener.location.reload();
            } else {
                alert('Error: Unable to save data.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error: Unable to save data.');
        });
    }
</script>
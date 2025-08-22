
    <?php 
        include_once 'connection.php';
        include_once 'userInfo.php';
        include_once 'sidebar.php';
        include_once 'login_check.php';
    ?>
    
    <th align="right">
        <?php if(isset($_GET['error']) && $_GET['error'] === '1'){?><span class="blink" style="color:#FF0000; font-size:14px;">كلمة المرور الحالية التي ادخلتها غير صحيحة</span><?php }?>
    </th>

<?php
    if(!isset($_GET['id'])){
        header("Location: addClientStatus.php");
        exit();
    }
    
    $id = $_GET['id'];
    if($_GET['id'] === ''){
        header("Location: addClientStatus.php");
        exit();
    }

    if($result->num_rows == 0){
        header("Location: addClientStatus.php");
        exit();
    }
?>
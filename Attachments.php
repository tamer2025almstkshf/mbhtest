<?php
    include_once 'connection.php';
    include_once 'login_check.php';
?>
<html xmlns="http://www.w3.org/1999/xhtml" dir="rtl">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1256" />
        <title>صفات الموكل/الخصم</title>
        <link rel="stylesheet" type="text/css" href="sites.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link href="login.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />
    </head>
    <body>
        <table  width="99%" border="1" cellspacing="0" cellpadding="0" align="center" bordercolor="#DFC6BD"  style="font-size:16px" bgcolor="#FFFFFF">
            <tr  bgcolor="#96693e"  style=" color:#FFF">
                <th colspan="4" align="center">
                    <input type="text" placeholder="Search...." dir="ltr" id="SearchBox">
                </th>
            </tr>
            
            <tr  bgcolor="#96693e"  style=" color:#FFF">
                <th colspan="4" align="center">رقم الملف</th>
            </tr>
    
            <tbody id="table1">
                <?php
                    $selectedfolder = $_GET['folder'];
                    $directory = "files_images/file_upload/$selectedfolder";
                    
                    if (is_dir($directory)) {
                        $contents = scandir($directory);
                    
                        $folders = array_filter($contents, function($item) use ($directory) {
                            return is_dir($directory . DIRECTORY_SEPARATOR . $item) && !in_array($item, ['.', '..']);
                        });
                    } else {
                        $folders = [];
                    }
                            if (!empty($folders)){
                                foreach ($folders as $folder){
                                    ?>
    
                <tr>
                    <th width="49%" align="right" style="padding-right:10px;" colspan='4'>
                        <?php echo "<a href='fileidAttachment.php?folder=$selectedfolder/$folder&fid=$folder'>".htmlspecialchars($folder)."</a>"; ?>
                    </th>
                </tr>
                <?php
                        }
                    } else{ 
                        echo '<p>المجلد فارغ</p>';
                    }
                ?>
            </tbody>
        </table>
    </body>
</html>

<script>
    $(document).ready(function(){
        $('#SearchBox').on("keyup", function(){
            var value = $(this).val().toLowerCase();
            $("#table1 tr").filter(function(){
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    });
</script>

<script>
    function toggleModal(id){ 
        var toggleModal = document.getElementById('taskaddplus-btn-' + id); 
        if(toggleModal.style.display === 'block'){ 
            toggleModal.style.display = 'none'; 
        } else { 
            toggleModal.style.display = 'block'; 
        } 
    }
</script>
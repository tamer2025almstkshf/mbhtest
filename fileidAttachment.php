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
                <th width="99%" colspan="3" align="center">رقم الملف</th>
                <th width="1%" align="center">حذف</th>
            </tr>
    
            <tbody id="table1">
                <?php
                    $selectedfolder = $_GET['folder'];
                    $directory = "files_images/file_upload/$selectedfolder";
                    
                    if (is_dir($directory)) {
                        $files = [];
                    
                        $iterator = new RecursiveIteratorIterator(
                            new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS)
                        );
                    
                        foreach ($iterator as $file) {
                            if ($file->isFile()) {
                                $files[] = $file->getPathname();
                            }
                        }
                    } else {
                        $files = [];
                    }
                    
                    if(!empty($files)){
                        foreach ($files as $file){ 
                ?>
                <tr>
                    <th width="99%" align="right" style="padding-right:10px;" colspan='3'>
                        <a href="#" onClick="open('<?php echo htmlspecialchars($file); ?>','Pic','width=800 height=600 scrollbars=yes')" target="_blank">
                            <?php echo htmlspecialchars(basename($file)); ?>
                        </a>
                    </th>
                    <th width="1%" >
                        <?php
                            $myid = $_SESSION['id'];
                            $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
                            $result_permcheck = mysqli_query($conn, $query_permcheck);
                            $row_permcheck = mysqli_fetch_array($result_permcheck);
                            
                            if($row_permcheck['attachments_dperm'] === '1'){
                        ?>
                        <a href="delatt.php?file=<?php echo $file;?>&fid=<?php echo $_GET['fid'];?>"><img src="images/DeleteB.png" border="0" title="حذف"></a>
                        <?php }?>
                    </th>
                </tr>
                <?php 
                        }
                    } else{
                        echo '<tr><th><p>المجلد فارغ</p></th></tr>';
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
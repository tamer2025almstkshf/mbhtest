<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    
    $queryString = '';
    if(isset($_GET['type'])){
        if($queryString === ''){
            $queryString = "type=".$_GET['type'];
        } else{
            $queryString = $queryString."&type=".$_GET['type'];
        }
    }
    
    if(isset($_GET['place'])){
        if($queryString === ''){
            $queryString = "place=".$_GET['place'];
        } else{
            $queryString = $queryString."&place=".$_GET['place'];
        }
    }
    
    if(isset($_GET['class'])){
        if($queryString === ''){
            $queryString = "class=".$_GET['class'];
        } else{
            $queryString = $queryString."&class=".$_GET['class'];
        }
    }
    
    if(isset($_GET['fid'])){
        if($queryString === ''){
            $queryString = "fid=".$_GET['fid'];
        } else{
            $queryString = $queryString."&fid=".$_GET['fid'];
        }
    }
    
    if(isset($_GET['subject'])){
        if($queryString === ''){
            $queryString = "subject=".$_GET['subject'];
        } else{
            $queryString = $queryString."&subject=".$_GET['subject'];
        }
    }
    
    if(isset($_GET['client'])){
        if($queryString === ''){
            $queryString = "client=".$_GET['client'];
        } else{
            $queryString = $queryString."&client=".$_GET['client'];
        }
    }
    
    if(isset($_GET['ccharacteristic'])){
        if($queryString === ''){
            $queryString = "ccharacteristic=".$_GET['ccharacteristic'];
        } else{
            $queryString = $queryString."&ccharacteristic=".$_GET['ccharacteristic'];
        }
    }
    
    if(isset($_GET['opponent'])){
        if($queryString === ''){
            $queryString = "opponent=".$_GET['opponent'];
        } else{
            $queryString = $queryString."&opponent=".$_GET['opponent'];
        }
    }
    
    if(isset($_GET['ocharacteristic'])){
        if($queryString === ''){
            $queryString = "ocharacteristic=".$_GET['ocharacteristic'];
        } else{
            $queryString = $queryString."&ocharacteristic=".$_GET['ocharacteristic'];
        }
    }
    
    if(isset($_GET['advisor'])){
        if($queryString === ''){
            $queryString = "advisor=".$_GET['advisor'];
        } else{
            $queryString = $queryString."&advisor=".$_GET['advisor'];
        }
    }
    
    if(isset($_GET['researcher'])){
        if($queryString === ''){
            $queryString = "researcher=".$_GET['researcher'];
        } else{
            $queryString = $queryString."&researcher=".$_GET['researcher'];
        }
    }
    
    if(isset($_GET['ctype'])){
        if($queryString === ''){
            $queryString = "ctype=".$_GET['ctype'];
        } else{
            $queryString = $queryString."&ctype=".$_GET['ctype'];
        }
    }
    
    if(isset($_GET['prosec'])){
        if($queryString === ''){
            $queryString = "prosec=".$_GET['prosec'];
        } else{
            $queryString = $queryString."&prosec=".$_GET['prosec'];
        }
    }
    
    if(isset($_GET['station'])){
        if($queryString === ''){
            $queryString = "station=".$_GET['station'];
        } else{
            $queryString = $queryString."&station=".$_GET['station'];
        }
    }
    
    if(isset($_GET['court'])){
        if($queryString === ''){
            $queryString = "court=".$_GET['court'];
        } else{
            $queryString = $queryString."&court=".$_GET['court'];
        }
    }
    
    if(isset($_GET['opp'])){
        if($queryString === ''){
            $queryString = "opp=".$_GET['opp'];
        } else{
            $queryString = $queryString."&opp=".$_GET['opp'];
        }
    }
    
    if(isset($_GET['deg'])){
        if($queryString === ''){
            $queryString = "deg=".$_GET['deg'];
        } else{
            $queryString = $queryString."&deg=".$_GET['deg'];
        }
    }
    
    if(isset($_GET['cno'])){
        if($queryString === ''){
            $queryString = "cno=".$_GET['cno'];
        } else{
            $queryString = $queryString."&cno=".$_GET['cno'];
        }
    }
    
    if(isset($_GET['year'])){
        if($queryString === ''){
            $queryString = "year=".$_GET['year'];
        } else{
            $queryString = $queryString."&year=".$_GET['year'];
        }
    }
    
    if(isset($_GET['jud'])){
        if($queryString === ''){
            $queryString = "jud=".$_GET['jud'];
        } else{
            $queryString = $queryString."&jud=".$_GET['jud'];
        }
    }
    
    if(isset($_GET['from'])){
        if($queryString === ''){
            $queryString = "from=".$_GET['from'];
        } else{
            $queryString = $queryString."&from=".$_GET['from'];
        }
    }
    
    if(isset($_GET['to'])){
        if($queryString === ''){
            $queryString = "to=".$_GET['to'];
        } else{
            $queryString = $queryString."&to=".$_GET['to'];
        }
    }
    
    if(isset($_GET['extended'])){
        if($queryString === ''){
            $queryString = "extended=".$_GET['extended'];
        } else{
            $queryString = $queryString."&extended=".$_GET['extended'];
        }
    }
    
    if(isset($_GET['judge'])){
        if($queryString === ''){
            $queryString = "judge=".$_GET['judge'];
        } else{
            $queryString = $queryString."&judge=".$_GET['judge'];
        }
    }
    
    if(isset($_GET['pending'])){
        if($queryString === ''){
            $queryString = "pending=".$_GET['pending'];
        } else{
            $queryString = $queryString."&pending=".$_GET['pending'];
        }
    }
    
    if(isset($_GET['fwork'])){
        if($queryString === ''){
            $queryString = "fwork=".$_GET['fwork'];
        } else{
            $queryString = $queryString."&fwork=".$_GET['fwork'];
        }
    }
    
    if(isset($_GET['archived'])){
        if($queryString === ''){
            $queryString = "archived=".$_GET['archived'];
        } else{
            $queryString = $queryString."&archived=".$_GET['archived'];
        }
    }
    
    if ($queryString !== '') {
        if (strpos($queryString, 'error') !== false) {
            parse_str($queryString, $queryParams);
            unset($queryParams['error']);
            $queryString = http_build_query($queryParams);
        }
    }
    
    $page = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    if($row_permcheck['cfiles_dperm'] == 1){
        if(isset($_GET['id']) && $_GET['id'] !== ''){
            $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
            $action = "تم حذف ملف :<br>";
            
            $stmtr = $conn->prepare("SELECT * FROM file WHERE file_id=?");
            $stmtr->bind_param("i", $id);
            $stmtr->execute();
            $resultr = $stmtr->get_result();
            $row = $resultr->fetch_assoc();
            $stmtr->close();
            
            $fid = $row['file_id'];
            if(isset($fid) && $fid !== ''){
                $flag = '1';
                
                $action = $action."رقم الملف : $fid<br>";
            }
            
            $ftype = $row['file_type'];
            if(isset($ftype) && $ftype !== ''){
                $flag = '1';
                
                $action = $action."<br>نوع الملف : $ftype";
            }
            
            $fpla = $row['frelated_place'];
            if(isset($fpla) && $fpla !== ''){
                $flag = '1';
                
                $action = $action."<br>الفرع المختص : $fpla";
            }
            
            $fcla = $row['file_class'];
            if(isset($fcla) && $fcla !== ''){
                $flag = '1';
                
                $action = $action."<br>تصنيف الملف : $fcla";
            }
            
            $fsta = $row['file_status'];
            if(isset($fsta) && $fsta !== ''){
                $flag = '1';
                
                $action = $action."<br>حالة الملف : $fsta";
            }
            
            $fsub = $row['file_subject'];
            if(isset($fsub) && $fsub !== ''){
                $flag = '1';
                
                $action = $action."<br>الموضوع : $fsub";
            }
            
            $fno = $row['file_notes'];
            if(isset($fno) && $fno !== ''){
                $flag = '1';
                
                $action = $action."<br>الملاحظات : $fno";
            }
            
            $fcl1 = $row['file_client'];
            if(isset($fcl1) && $fcl1 !== ''){
                $flag = '1';
                
                $stmcl = $conn->prepare("SELECT * FROM client WHERE id=?");
                $stmcl->bind_param("i", $fcl1);
                $stmcl->execute();
                $resulcl = $stmcl->get_result();
                $rowcl = $resulcl->fetch_assoc();
                $stmcl->close();
                $cln = $rowcl['arname'];
                
                $action = $action."<br>الموكل : $cln";
            }
            
            $fcc1 = $row['fclient_characteristic'];
            if(isset($fcc1) && $fcc1 !== ''){
                $flag = '1';
                
                $action = $action."<br>صفة الموكل : $fcc1";
            }
            
            $fcl2 = $row['file_client2'];
            if(isset($fcl2) && $fcl2 !== ''){
                $flag = '1';
                
                $stmcl = $conn->prepare("SELECT * FROM client WHERE id=?");
                $stmcl->bind_param("i", $fcl2);
                $stmcl->execute();
                $resulcl = $stmcl->get_result();
                $rowcl = $resulcl->fetch_assoc();
                $stmcl->close();
                $cln = $rowcl['arname'];
                
                $action = $action."<br>الموكل 2 : $cln";
            }
            
            $fcc2 = $row['fclient_characteristic2'];
            if(isset($fcc2) && $fcc2 !== ''){
                $flag = '1';
                
                $action = $action."<br>صفة الموكل 2 : $fcc2";
            }
            
            $fcl3 = $row['file_client3'];
            if(isset($fcl3) && $fcl3 !== ''){
                $flag = '1';
                
                $stmcl = $conn->prepare("SELECT * FROM client WHERE id=?");
                $stmcl->bind_param("i", $fcl3);
                $stmcl->execute();
                $resulcl = $stmcl->get_result();
                $rowcl = $resulcl->fetch_assoc();
                $stmcl->close();
                $cln = $rowcl['arname'];
                
                $action = $action."<br>الموكل 3 : $cln";
            }
            
            $fcc3 = $row['fclient_characteristic3'];
            if(isset($fcc3) && $fcc3 !== ''){
                $flag = '1';
                
                $action = $action."<br>صفة الموكل 3 : $fcc3";
            }
            
            $fcl4 = $row['file_client4'];
            if(isset($fcl4) && $fcl4 !== ''){
                $flag = '1';
                
                $stmcl = $conn->prepare("SELECT * FROM client WHERE id=?");
                $stmcl->bind_param("i", $fcl4);
                $stmcl->execute();
                $resulcl = $stmcl->get_result();
                $rowcl = $resulcl->fetch_assoc();
                $stmcl->close();
                $cln = $rowcl['arname'];
                
                $action = $action."<br>الموكل 4 : $cln";
            }
            
            $fcc4 = $row['fclient_characteristic4'];
            if(isset($fcc4) && $fcc4 !== ''){
                $flag = '1';
                
                $action = $action."<br>صفة الموكل 4 : $fcc4";
            }
            
            $fcl5 = $row['file_client'];
            if(isset($fcl5) && $fcl5 !== ''){
                $flag = '1';
                
                $stmcl = $conn->prepare("SELECT * FROM client WHERE id=?");
                $stmcl->bind_param("i", $fcl5);
                $stmcl->execute();
                $resulcl = $stmcl->get_result();
                $rowcl = $resulcl->fetch_assoc();
                $stmcl->close();
                $cln = $rowcl['arname'];
                
                $action = $action."<br>الموكل 5 : $cln";
            }
            
            $fcc5 = $row['fclient_characteristic5'];
            if(isset($fcc5) && $fcc5 !== ''){
                $flag = '1';
                
                $action = $action."<br>صفة الموكل 5 : $fcc5";
            }
            
            $fo1 = $row['file_opponent'];
            if(isset($fo1) && $fo1 !== ''){
                $flag = '1';
                
                $stmcl = $conn->prepare("SELECT * FROM client WHERE id=?");
                $stmcl->bind_param("i", $fo1);
                $stmcl->execute();
                $resulcl = $stmcl->get_result();
                $rowcl = $resulcl->fetch_assoc();
                $stmcl->close();
                $opn = $rowcl['arname'];
                
                $action = $action."<br>الخصم : $opn";
            }
            
            $foc1 = $row['fopponent_characteristic'];
            if(isset($foc1) && $foc1 !== ''){
                $flag = '1';
                
                $action = $action."<br>صفة الخصم : $foc1";
            }
            
            $fo2 = $row['file_opponent2'];
            if(isset($fo2) && $fo2 !== ''){
                $flag = '1';
                
                $stmcl = $conn->prepare("SELECT * FROM client WHERE id=?");
                $stmcl->bind_param("i", $fo2);
                $stmcl->execute();
                $resulcl = $stmcl->get_result();
                $rowcl = $resulcl->fetch_assoc();
                $stmcl->close();
                $opn = $rowcl['arname'];
                
                $action = $action."<br>الخصم 2 : $opn";
            }
            
            $foc2 = $row['fopponent_characteristic2'];
            if(isset($foc2) && $foc2 !== ''){
                $flag = '1';
                
                $action = $action."<br>صفة الخصم 2 : $foc2";
            }
            
            $fo3 = $row['file_opponent3'];
            if(isset($fo3) && $fo3 !== ''){
                $flag = '1';
                
                $stmcl = $conn->prepare("SELECT * FROM client WHERE id=?");
                $stmcl->bind_param("i", $fo3);
                $stmcl->execute();
                $resulcl = $stmcl->get_result();
                $rowcl = $resulcl->fetch_assoc();
                $stmcl->close();
                $opn = $rowcl['arname'];
                
                $action = $action."<br>الخصم 3 : $opn";
            }
            
            $foc3 = $row['fopponent_characteristic3'];
            if(isset($foc3) && $foc3 !== ''){
                $flag = '1';
                
                $action = $action."<br>صفة الخصم 3 : $foc3";
            }
            
            $fo4 = $row['file_opponent4'];
            if(isset($fo4) && $fo4 !== ''){
                $flag = '1';
                
                $stmcl = $conn->prepare("SELECT * FROM client WHERE id=?");
                $stmcl->bind_param("i", $fo4);
                $stmcl->execute();
                $resulcl = $stmcl->get_result();
                $rowcl = $resulcl->fetch_assoc();
                $stmcl->close();
                $opn = $rowcl['arname'];
                
                $action = $action."<br>الخصم 4 : $opn";
            }
            
            $foc4 = $row['fopponent_characteristic4'];
            if(isset($foc4) && $foc4 !== ''){
                $flag = '1';
                
                $action = $action."<br>صفة الخصم 4 : $foc4";
            }
            
            $fo5 = $row['file_opponent5'];
            if(isset($fo5) && $fo5 !== ''){
                $flag = '1';
                
                $stmcl = $conn->prepare("SELECT * FROM client WHERE id=?");
                $stmcl->bind_param("i", $fo5);
                $stmcl->execute();
                $resulcl = $stmcl->get_result();
                $rowcl = $resulcl->fetch_assoc();
                $stmcl->close();
                $opn = $rowcl['arname'];
                
                $action = $action."<br>الخصم 5 : $opn";
            }
            
            $foc5 = $row['fopponent_characteristic5'];
            if(isset($foc5) && $foc5 !== ''){
                $flag = '1';
                
                $action = $action."<br>صفة الخصم 5 : $foc5";
            }
            
            $fps = $row['fpolice_station'];
            if(isset($fps) && $fps !== ''){
                $flag = '1';
                
                $action = $action."<br>مركز الشرطة : $fps";
            }
            
            $fct = $row['fcase_type'];
            if(isset($fct) && $fct !== ''){
                $flag = '1';
                
                $action = $action."<br>نوع القضية : $fct";
            }
            
            $fcr = $row['file_court'];
            if(isset($fcr) && $fcr !== ''){
                $flag = '1';
                
                $action = $action."<br>المحكمة : $fcr";
            }
            
            $fpr = $row['file_prosecution'];
            if(isset($fpr) && $fpr !== ''){
                $flag = '1';
                
                $action = $action."<br>النيابة العامة : $fpr";
            }
            
            $flr = $row['flegal_researcher'];
            if(isset($flr) && $flr !== '0'){
                $flag = '1';
                
                $stmfl = $conn->prepare("SELECT * FROM user WHERE id=?");
                $stmfl->bind_param("i", $flr);
                $stmfl->execute();
                $resulfl = $stmfl->get_result();
                $rowfl = $resulfl->fetch_assoc();
                $stmfl->close();
                $flname = $rowfl['name'];
                
                $action = $action."<br>الباحث القانوني : $flname";
            }
            
            $fla = $row['flegal_advisor'];
            if(isset($fla) && $fla !== '0'){
                $flag = '1';
                
                $stmfl = $conn->prepare("SELECT * FROM user WHERE id=?");
                $stmfl->bind_param("i", $fla);
                $stmfl->execute();
                $resulfl = $stmfl->get_result();
                $rowfl = $resulfl->fetch_assoc();
                $stmfl->close();
                $flname = $rowfl['name'];
                
                $action = $action."<br>المستشار القانوني : $flname";
            }
            
            $flsc = $row['file_secritary'];
            if(isset($flsc) && $flsc !== '0'){
                $flag = '1';
                
                $stmfl = $conn->prepare("SELECT * FROM user WHERE id=?");
                $stmfl->bind_param("i", $flsc);
                $stmfl->execute();
                $resulfl = $stmfl->get_result();
                $rowfl = $resulfl->fetch_assoc();
                $stmfl->close();
                $flscname = $rowfl['name'];
                
                $action = $action."<br>السكرتيرة : $flscname";
            }
            
            $flaw = $row['file_lawyer'];
            if(isset($flaw) && $flaw !== '0'){
                $flag = '1';
                
                $stmfl = $conn->prepare("SELECT * FROM user WHERE id=?");
                $stmfl->bind_param("i", $flaw);
                $stmfl->execute();
                $resulfl = $stmfl->get_result();
                $rowfl = $resulfl->fetch_assoc();
                $stmfl->close();
                $flawname = $rowfl['name'];
                
                $action = $action."<br>المحامي : $flawname";
            }
            
            $fu1 = $row['file_upload1'];
            if(isset($fu1) && $fu1 !== ''){
                $flag = '1';
                
                $action = $action."<br>تم حذف المرفق (1)";
            }
            
            $fu2 = $row['file_upload2'];
            if(isset($fu2) && $fu2 !== ''){
                $flag = '1';
                
                $action = $action."<br>تم حذف المرفق (2)";
            }
            
            $fu3 = $row['file_upload3'];
            if(isset($fu3) && $fu3 !== ''){
                $flag = '1';
                
                $action = $action."<br>تم حذف المرفق (3)";
            }
            
            $fu4 = $row['file_upload4'];
            if(isset($fu4) && $fu4 !== ''){
                $flag = '1';
                
                $action = $action."<br>تم حذف المرفق (4)";
            }
            
            $fu5 = $row['file_upload5'];
            if(isset($fu5) && $fu5 !== ''){
                $flag = '1';
                
                $action = $action."<br>تم حذف المرفق (5)";
            }
            
            $fu6 = $row['file_upload6'];
            if(isset($fu6) && $fu6 !== ''){
                $flag = '1';
                
                $action = $action."<br>تم حذف المرفق (6)";
            }
            
            if($flag === '1'){
                include_once 'addlog.php';
            }
            
            $stmt = $conn->prepare("DELETE FROM file WHERE file_id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
        }
    }
    if($queryString !== ''){
        header("Location: $page?$queryString");
    } else{
        header("Location: $page");
    }
    exit();
?>
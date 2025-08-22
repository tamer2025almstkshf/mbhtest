<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    
    if($row_permcheck['clients_eperm'] == 1){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $cid = filter_input(INPUT_POST, 'client_id', FILTER_SANITIZE_NUMBER_INT);
            $new_password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
            $stmt = $conn->prepare("UPDATE client SET password=? WHERE id=?");
            $stmt->bind_param("si", $new_password, $cid);
            $stmt->execute();
            
            $action = "تم تغيير كلمة مرور الموكل رقم : $cid";
            include_once 'addlog.php';
        
            echo json_encode(['status' => 'success']);
            exit(); 
        }
        
        if (isset($_GET['id'])) {
            $cid = $_GET['id'];
?>

<form id="passwordForm" action="" method="POST" style="display:none;">
    <input type="hidden" id="passwordInput" name="password">
    <input type="hidden" name="client_id" value="<?php echo $cid; ?>">
</form>

<script>
    function generateRandomPassword(length) {
        var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var password = '';
        for (var i = 0; i < length; i++) {
            password += characters.charAt(Math.floor(Math.random() * characters.length));
        }
        return password;
    }

    function setRandomPassword() {
        var passwordInput = document.getElementById('passwordInput');
        var randomPassword = generateRandomPassword(8);
        passwordInput.value = randomPassword;

        var formData = new FormData(document.getElementById('passwordForm'));

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '', true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    window.location.href = 'clients.php?passchanged=1';
                } else {
                    console.error('Error:', response.message);
                }
            }
        };
        xhr.send(formData);
    }

    window.onload = setRandomPassword;
</script>

<?php
            exit();
        }
    } else{
        header("Location: clients.php?error=0");
        exit();
    }
?>

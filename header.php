<div class="header">
    <p class="display-none"></p>
    <p class="display-none"></p>
    <div class="hsearch-main">
        <form method="post" action="result_process.php" enctype="multipart/form-data">
            <div>
                <input type="search" name="SearchKey" class="inp-searchmain" value="<?php if(isset($_GET['key']) && $_GET['key'] !== ''){ echo safe_output($_GET['key']); }?>">
                <div class="h-search-div">
                    <img src="img/magnifying-glass.png" onclick="submit()" class="h-search-btn">
                </div>
            </div>
            <div class="hsearch-radios">
                <div class="h-radios">
                    <input type="radio" name="Ckind" value="1" <?php if(isset($_GET['kind']) && $_GET['kind'] === '1'){ echo 'checked'; }?>> <font id="clientradio-translate" style="color: #fff;"> موكل</font>
                    <input type="radio" name="Ckind" value="2" <?php if(isset($_GET['kind']) && $_GET['kind'] === '2'){ echo 'checked'; }?>> <font id="opponentradio-translate" style="color: #fff;"> خصم</font>
                    <input type="radio" name="Ckind" value="3" <?php if(isset($_GET['kind']) && $_GET['kind'] === '3'){ echo 'checked'; }?>> <font id="attachmentradio-translate" style="color: #fff;"> مرفقات</font>
                </div>
                <div class="h-advanced-div">
                    <button type="button" class="h-AdvancedSearch-Btn green-button" id="advancedsearch-translate" onclick="window.open('AdvancedSearch.php', '_blank');">البحث المتقدم</button>
                </div>
            </div> 
        </form>
    </div>
    <div class="add-btnsalign">
    </div>
    <p></p>
    <div class="h-container">
        <i class='bx bxs-home bx-sm' style="cursor: pointer;" onclick="window.open('index.php', '_blank');"></i>
        <i class='bx bx-log-out bx-sm' style="cursor: pointer;" onclick="logoutsubmit();"></i>
        <div class="profile-image" style="<?php if(isset($rowmain['personal_image']) && $rowmain['personal_image'] !== ''){ $personal_image = safe_output($rowmain['personal_image']); echo "background-image: url($personal_image);"; }?>display: inline-block; width:25px; height:25px"></div>
    </div>
</div>

<script>
    function addtimer(){
        var addtimer = document.getElementById('addtimer-btn');
        if (addtimer.style.display === 'block') {
            addtimer.style.display = 'none';
        } else {
            addtimer.style.display = 'block';
        }
    }
    
    function logoutsubmit(){
        Swal.fire({
            icon: 'warning',
            title: 'هل ترغب في تسجيل الخروج؟',
            showCancelButton: true,
            confirmButtonText: 'نعم',
            cancelButtonText: 'إلغاء',
            confirmButtonColor: '#007bff',
            cancelButtonColor: '#d33',
            background: '#fff',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `logout.php`;
            }
        });
    }
</script>
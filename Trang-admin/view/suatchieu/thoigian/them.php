<?php
include "./view/home/sideheader.php";
?>
<!-- Content Body Start -->
<div class="content-body">

    <!-- Page Headings Start -->
    <div class="row justify-content-between align-items-center mb-10">

        <!-- Page Heading Start -->
        <div class="col-12 col-lg-auto mb-20">
            <div class="page-heading">
                <h3>Quản Lý Khung Giờ Chiếu<span>/ Thêm khung giờ chiếu</span></h3>
            </div>
        </div><!-- Page Heading End -->

        <!-- Page Button Group Start -->

    </div><!-- Page Headings End -->
    <?php if(isset($suatc)&&($suatc)!= ""){
        echo'<p  style="color: red; text-align: center;">' .$suatc. '</p>';
    }
    ?> 
    <!-- Add or Edit Product Start -->
    <form action="index.php?act=themthoigian" method="post"  enctype="multipart/form-data">
        <div class="add-edit-product-wrap col-12">

            <div class="add-edit-product-form">

                <h4 class="title">Thêm Khung Giờ Chiếu</h4>

                <div class="row">
                    <div class="col-lg-6 col-12 mb-30">

                        <div class="row2 mb10 form_content_container">
                            <select name="id_lc" class="form-control" required>
                                <option value="0">Chọn Lịch Chiếu</option>
                                <?php foreach ($loadlc as $lc){
                                    $id = (int)$lc['id'];
                                    $label = htmlspecialchars(($lc['ngay_chieu'] ?? '').' — '.($lc['tieu_de'] ?? ''), ENT_QUOTES);
                                    echo "<option value=\"{$id}\">{$label}</option>";
                                } ?>
                            </select>
                        </div>  </div>
                    <div class="col-lg-6 col-12 mb-30">

                        <div class="row2 mb10 form_content_container">

                            <select name="id_phong" class="form-control" required>
                                <option value="0">Chọn Phòng Chiếu</option>
                                <?php foreach ($loadphong as $phong){
                                    $id = (int)$phong['id'];
                                    $name = htmlspecialchars($phong['name'] ?? '', ENT_QUOTES);
                                    echo "<option value=\"{$id}\">{$name}</option>";
                                } ?>
                            </select><br>
                        </div>  </div>
                    <div class="col-lg-6 col-12 mb-30"><input class="form-control" type="time" placeholder="Giờ chiếu" name="tgc" required></div>
                    <div class="col-lg-6 col-12 mb-30">

                    </div>
                </div>

                <h4 class="title">Thao tác</h4>

                <div class="product-upload-gallery row flex-wrap">


                    <!-- Button Group Start -->
                    <div class="row">
                        <div class="d-flex flex-wrap justify-content-end col mbn-10">
                            <button class="button button-outline button-primary mb-10 ml-10 mr-0" name="them">Thêm</button>
                            <button class="button button-outline button-primary mb-10 ml-10 mr-0" ><a href="index.php?act=thoigian">Danh sách </a></button>
                        </div>
                    </div><!-- Button Group End -->

                </div>

            </div><!-- Add or Edit Product End -->
            <?php if(isset($error)&&$error !=""){
                echo '<p style="color: red; text-align: center;"
                > '.$error.' </p>';
            } ?>
        </div>
    </form>

</div><!-- Content Body End -->

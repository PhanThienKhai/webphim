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
                        <h3 class="title">Quản Lý Suất Chiếu<span>/ Suất Chiếu</span></h3>
                    </div>
                </div><!-- Page Heading End -->

            </div><!-- Page Headings End -->
           
            <?php if(isset($suatc)&&($suatc)!= ""){
        echo'<p  style="color: red; text-align: center;">' .$suatc. '</p>';
    }
    ?> 
            <div class="col-12 mb-30"><div class="news-item">
                <div class="content">
                <div class="categories"><a href="index.php?act=themlichchieu" class="product">Thêm Suất Chiếu</a></div></div></div>

                <!--Order List Start-->
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-vertical-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Phim</th>
                                   
                                    <th>Ngày chiếu</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($loadlich as $lich){
                                    $id = (int)$lich['id'];
                                    $tieu_de = $lich['tieu_de'] ?? '';
                                    $ngay_chieu = $lich['ngay_chieu'] ?? '';
                                    $st = trim($lich['trang_thai_duyet'] ?? '');
                                    $st_l = mb_strtolower($st, 'UTF-8');
                                    // Chuẩn hoá trạng thái về mã chuẩn
                                    if ($st === '' ) { $st_norm = ''; }
                                    elseif ($st_l==='cho_duyet' || $st_l==='chờ duyệt' || $st_l==='cho duyet') { $st_norm = 'cho_duyet'; }
                                    elseif ($st_l==='da_duyet' || $st_l==='đã duyệt' || $st_l==='da duyet') { $st_norm = 'da_duyet'; }
                                    elseif ($st_l==='tu_choi' || $st_l==='từ chối' || $st_l==='tu choi') { $st_norm = 'tu_choi'; }
                                    else { $st_norm = $st; }
                                    $status_text = ($st_norm==='cho_duyet' ? 'Chờ duyệt' : ($st_norm==='da_duyet' ? 'Đã duyệt' : ($st_norm==='tu_choi' ? 'Từ chối' : '-')));

                                    $linksua = "index.php?act=sualichchieu&idsua=".$id;
                                    $linkxoa= "index.php?act=xoalichchieu&idxoa=".$id;
                                    echo '<tr>
                                    <td>#'.$id.'</td>
                                    <td>'.htmlspecialchars($tieu_de, ENT_QUOTES).'</td>
                                    <td>'.htmlspecialchars($ngay_chieu, ENT_QUOTES).'</td>
                                    <td>'.$status_text.'</td>
                                    <td class="action h4">
                                        <div class="table-action-buttons">
                                            <a class="edit button button-box button-xs button-info" href="'.$linksua.'"><i class="zmdi zmdi-edit"></i></a>
                                            <a class="delete button button-box button-xs button-danger" href="'.$linkxoa.'" onclick="return confirm(\'Xóa lịch này?\')"><i class="zmdi zmdi-delete"></i></a>
                                            '.($st_norm==='' || $st_norm==='tu_choi' ? '<a class="button button-box button-xs button-success" href="index.php?act=gui_kehoach&id='.$id.'" title="Gửi duyệt"><i class="zmdi zmdi-mail-send"></i></a>' : '<span class="badge '.($st_norm==='cho_duyet'?'badge-warning':'badge-success').'">'.($st_norm==='cho_duyet'?'Chờ duyệt':'Đã duyệt').'</span>').'
                                        </div>
                                    </td>
                                </tr>';
                                } ?>

                            </tbody>
                        </table>
                    </div>
                </div>
                <!--Order List End-->

            </div>

        </div><!-- Content Body End -->

        

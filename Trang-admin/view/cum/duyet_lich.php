<?php include __DIR__ . '/../home/sideheader.php'; ?>

<div class="content-body">
    <div class="row justify-content-between align-items-center mb-20">
        <div class="col-12 col-lg-auto"><div class="page-heading"><h3>Duyệt kế hoạch chiếu</h3></div></div>
        <div class="col-12 col-lg-auto">
            <a class="button" href="index.php?act=duyet_lichchieu&filter=cho_duyet">Chờ duyệt</a>
            <a class="button" href="index.php?act=duyet_lichchieu&filter=da_duyet">Đã duyệt</a>
            <a class="button" href="index.php?act=duyet_lichchieu&filter=tu_choi">Từ chối</a>
        </div>
    </div>

    <?php if (!empty($msg)): ?><div class="alert alert-info"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead><tr><th>Rạp</th><th>Phim</th><th>Ngày chiếu</th><th>Trạng thái</th><th>Hành động</th></tr></thead>
            <tbody>
                <?php foreach (($ds_lich ?? []) as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['ten_rap']) ?></td>
                        <td><?= htmlspecialchars($r['tieu_de']) ?></td>
                        <td><?= htmlspecialchars($r['ngay_chieu']) ?></td>
                        <?php
                            $st = trim($r['trang_thai_duyet'] ?? '');
                            $st_l = mb_strtolower($st, 'UTF-8');
                            if ($st==='') { $st_norm=''; $st_text='-'; }
                            elseif ($st_l==='cho_duyet' || $st_l==='chờ duyệt' || $st_l==='cho duyet') { $st_norm='cho_duyet'; $st_text='Chờ duyệt'; }
                            elseif ($st_l==='da_duyet' || $st_l==='đã duyệt' || $st_l==='da duyet') { $st_norm='da_duyet'; $st_text='Đã duyệt'; }
                            elseif ($st_l==='tu_choi' || $st_l==='từ chối' || $st_l==='tu choi') { $st_norm='tu_choi'; $st_text='Từ chối'; }
                            else { $st_norm=$st; $st_text=$st; }
                        ?>
                        <td><?= htmlspecialchars($st_text) ?></td>
                        <td>
                            <?php if ($st_norm === 'cho_duyet'): ?>
                                <a class="button button-sm button-success" href="index.php?act=duyet_lichchieu&duyet=<?= (int)$r['id'] ?>">Duyệt</a>
                                <a class="button button-sm button-danger" href="index.php?act=duyet_lichchieu&tuchoi=<?= (int)$r['id'] ?>">Từ chối</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

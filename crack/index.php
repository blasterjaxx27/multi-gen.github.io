<?php
require('inc/fonctions.php');

if (!check_login()){
    header('Location: /connexion');
    exit();
}

if(isset($_POST['generer'])){
    $_SESSION['token_gen'] = time();
    if ($utilisateur['grade'] == 1) {
        $token_exeio = '2f21062697b21dc444cd68a7291c0375e3d735d6';
        $genid = htmlspecialchars($_POST['generer']);
        $aprespub = 'https://'.$_SERVER['HTTP_HOST'].'/captcha?id='.$genid;
        $url = 'https://exe.io/st?api='.$token_exeio.'&url='.$aprespub;
        header('Location: '.$url);
    } else {
        $genid = htmlspecialchars($_POST['generer']);
        $url = '/captcha?id='.$genid;
        header('Location: '.$url);
    }
}


$title = 'Générateurs';
require('inc/header_panel.php');

// Menu a gauche
require('inc/menu_panel.php');

// Menu en haut
require('inc/menu_haut.php');
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="https://freegen.io">FreeGen</a></li>
                        <li class="breadcrumb-item"><a href="https://manager.freegen.pw">Manager</a></li>
                        <li class="breadcrumb-item active">Générateurs</li>
                    </ol>
                </div>
                <h4 class="page-title"> <img src="https://i.imgur.com/oUreBgX.gif" width="380" />&nbsp;<img src="https://i.imgur.com/iRCwypu.jpg" width="380" />&nbsp;<a href="https://exe.io/ref/Freegen"><img width="380" src="https://img.cut-urls.com/r1.gif" title="CuT URLs - Earn money by shortening links with the highest CPMs Ever!" /></a></h4> 
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card widget-inline">
                <div class="card-body p-0">
                    <div class="row no-gutters">
                        <div class="col-sm-6 col-xl-3">
                            <div class="card shadow-none m-0">
                                <div class="card-body text-center">
                                    <i class="dripicons-user-group text-muted" style="font-size:24px"></i>
                                    <h3><span><?=get_utilisateurs() ?></span></h3>
                                    <p class="text-muted font-15 mb-0">Utilisateurs inscrits</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xl-3">
                            <div class="card shadow-none m-0 border-left">
                                <div class="card-body text-center">
                                    <i class="dripicons-checklist text-muted" style="font-size:24px"></i>
                                    <h3><span><?=get_generateurs() ?></span></h3>
                                    <p class="text-muted font-15 mb-0">Générateurs</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xl-3">
                            <div class="card shadow-none m-0 border-left">
                                <div class="card-body text-center">
                                    <i class="dripicons-box text-muted" style="font-size:24px"></i>
                                    <h3><span><?=get_user_gen($utilisateur['id']) ?></span></h3>
                                    <p class="text-muted font-15 mb-0">Vos générations</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xl-3">
                            <div class="card shadow-none m-0 border-left">
                                <div class="card-body text-center">
                                    <i class="dripicons-graph-line text-muted" style="font-size:24px"></i>
                                    <h3><span><?=get_generations_g() ?></span> <i class="mdi mdi-arrow-up text-success"></i></h3>
                                    <p class="text-muted font-15 mb-0">Générations globales</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
    <?php if (activation_maintenance()) {
        $req = $bdd->query('SELECT * FROM alerts WHERE connecte = 1 AND affiche = 1 ORDER BY id DESC');
        while($r = $req->fetch()) {
        ?>
        <div class="col-xl-12 col-lg-12">
            <div class="card cta-box bg-<?=$r['type'] ?> text-white">
                <div class="card-body">
                    <div class="media align-items-center">
                        <div class="media-body">
                            <h3 class="mt-0"><i class="mdi mdi-bullhorn-outline" style="font-size:40px"></i>&nbsp;</h3>
                            <h3 class="m-0 font-weight-normal cta-box-title"><?=$r['titre'] ?> <?=$r['contenu'] ?><i class="mdi mdi-arrow-right"></i></h3>
                        </div>
                        <img class="ml-3" src="/assets/images/file-searching.svg" width="120"/>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    <?php } else { ?>
        <div class="col-xl-12 col-lg-12">
            <div class="card cta-box bg-danger text-white">
                <div class="card-body">
                    <div class="media align-items-center">
                        <div class="media-body">
                            <h2 class="mt-0"><i class="mdi mdi-cogs"></i>&nbsp;</h2>
                            <h3 class="m-0 font-weight-normal cta-box-title">Le générateur est en <b>maintenance</b>. Seuls les Admins, Responsables et Fournisseurs peuvent se connecter. <i class="mdi mdi-arrow-right"></i></h3>
                        </div>
                        <img class="ml-3" src="/assets/images/maintenance.svg" width="120"/>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    </div>
    <div class="row">
        <?php
        $getGenMEA = get_generateur_MEA();
        while ($generateur = $getGenMEA->fetch()) {
            $stock = get_stock($generateur['id']);

        ?>
        <div class="col-md-6 col-xl-3">
            <div class="card d-block">
                <img class="card-img-top" width="10" src="<?=$generateur['icon_gif'] ?>" alt="<?=$generateur['nom'] ?>">
                
                <div class="card-img-overlay">
                    <?php if ($stock >= 1) { ?>
                    <div class="badge badge-success p-1">En stock</div>
                    <?php } else { ?>
                    <div class="badge badge-danger p-1">Hors stock</div>
                    <?php } ?>
                </div>
                <div class="card-body position-relative">
                    <h4 class="mt-0">
                        <a href="#" class="text-title"><?=$generateur['nom'] ?> </a>
                    </h4>
                    <p class="mb-3">
                        <span class="pr-2 text-nowrap">
                            <i class="mdi mdi-format-list-bulleted-type"></i>
                            <b><?=$stock ?></b> Comptes restants
                        </span>
                        <span class="text-nowrap">
                            <?php if ($generateur['verouillage'] == 1) { ?>
                                <button class="btn btn-warning text-white" disabled>Verrouillé</button>
                            <?php } elseif ($stock == 0) { ?>
                                <button class="btn btn-danger text-white" disabled>Hors stock</button>
                            <?php } elseif (check_statut_gen($stock, $utilisateur['grade'], $generateur['id'])) { ?>
                                <form method="POST"><button type="sumbit" name="generer" value="<?= $generateur['id'] ?>" class="btn btn-success text-white">Générer</button></form>
                            <?php } else { ?>
                                <a href="/vip" class="btn btn-warning text-white">Réservé aux VIP</a>
                            <?php } ?>
                        </span>
                    </p>
                    <p class="mb-2 font-weight"><?=coupePhrase($generateur['description'], 140) ?></p>
                    <div class="progress progress-sm">
                        <div class="progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="10000" style="width:<?=$stock ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Générateurs</h4>

                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-hover mb-0">
                            <tbody>
                                <?php
                                $getGen = get_generateur();
                                while ($generateur = $getGen->fetch()) {
                                    $stock = get_stock($generateur['id']);
                                    
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="media">
                                                <img class="mr-2 rounded-circle" src="<?=$generateur['icon'] ?>" width="40"/>
                                                <div class="media-body">
                                                    <h5 class="mt-0 mb-1"><?=$generateur['nom'] ?><small class="font-weight-normal ml-3 swipez"></small></h5>
                                                    <span class="font-13"><?=coupePhrase($generateur['description'], 110) ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-muted font-13">
                                                <i class="mdi mdi-link-variant"></i> <?php if ($utilisateur['grade'] == 1) { ?>Avec publicité<?php } else { ?>Sans publicité<?php } ?>
                                            </span>
                                            <br>
                                            <p class="mb-0"><i class="mdi mdi-format-list-bulleted-type"></i> <?=$stock ?> comptes en stock</p>
                                        </td>
                                        <td class="table-action" style="width:50px">
                                            <?php if ($generateur['verouillage'] == 1) { ?>
                                                <button class="btn btn-warning text-white" disabled>Verrouillé</button>
                                            <?php } elseif ($stock == 0) { ?>
                                                <button class="btn btn-danger text-white" disabled>Hors stock</button>
                                            <?php } elseif (check_statut_gen($stock, $utilisateur['grade'], $generateur['id'])) { ?>
                                                <form method="POST"><button type="sumbit" value="<?= $generateur['id'] ?>" name="generer" class="btn btn-success text-white">Générer</button></form>
                                            <?php } else { ?>
                                                <a href="/vip" class="btn btn-warning text-white">Réservé aux VIP</a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require('inc/footer_panel.php'); ?>
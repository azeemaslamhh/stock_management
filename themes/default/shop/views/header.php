<!DOCTYPE html>
<html lang="en">

<head>
    <title>Big Basket</title><title><?= $page_title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= $page_desc; ?>">
    <link rel="shortcut icon" href="<?= $assets; ?>images/icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= $assets; ?>css/main.css">
    <meta property="og:url" content="<?= isset($product) && !empty($product) ? site_url('product/' . $product->slug) : site_url(); ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="<?= $page_title; ?>" />
    <meta property="og:description" content="<?= $page_desc; ?>" />
    <meta property="og:image" content="<?= isset($product) && !empty($product) ? base_url('assets/uploads/' . $product->image) : base_url('assets/uploads/logos/' . $shop_settings->logo); ?>" />
</head>
<body>
    <div class="wrapper" id="wrapper">
        <header class="header">
            <div class="container">
                <div class="top-header">
                    <ul class="top-bar">
                        <li>
                            <div class="ico-holder"><img src="<?= $assets; ?>images/free-delivery.png" alt=""></div> <a
                                href="">FREE Shipiing on Rs. 1800</a>
                        </li>
                        <li><a href="#"> Free Returns in 10 days</a></li>
                        <li>
                            <div class="ico-holder"><img src="<?= $assets; ?>images/phone.png" alt=""></div><a href="#"> 0323
                                8325149</a>
                        </li>
                        <li>
                            <div class="text">Follow: Us</div>
                            <div class="social-links"><a href=""><i class="fab fa-facebook"></i></a><a href=""><i
                                        class="fab fa-twitter"></i></a><a href=""><i class="fab fa-linkedin"></i></a>
                            </div>
                        </li>
                    </ul>

                </div>
                <div class="mobile-sub-header">
                    <div class="logo"><img src="<?= $assets; ?>images/logo-bb.png" alt=""></div>
                    <div class="user-favourities">
                        <ul>
                            <li><a href="#"><i class="fa-solid fa-user"></i></a></li>
                            <li><a href="#"><i class="fa-regular fa-heart"></i></a></li>
                            <li><a href="#"><i class="fa-solid fa-basket-shopping"></i></a> <span>$14.00 (1 item)</span>
                            </li>
                        </ul>
                    </div>
                    <a href="javascript:;" class="nav-opener"><span></span></a>
                </div>
                <div class="header-holder">
                    <div class="header-wrapper">
                        <div class="middle-header">
                            <div class="logo"><img src="<?= $assets; ?>images/logo-bb.png" alt=""></div>
                            <div class="search-container">
                                <div class="seacrh-form">
                                    <?= shop_form_open('products', 'id="product-search-form"'); ?>
                                        <input type="text" placeholder="Search.." name="search">
                                        <button class="submit" type="submit"><i class="fa fa-search"></i></button>
                                    <?= form_close(); ?>
                                </div>
                            </div>

                            <div class="user-favourities">
                                <ul>
                                    <li><a href="#"><i class="fa-solid fa-user"></i></a></li>
                                    <li><a href="#"><i class="fa-regular fa-heart"></i></a></li>
                                    <li><a href="#"><i class="fa-solid fa-basket-shopping"></i></a> <span>$14.00 (1
                                            item)</span> </li>
                                </ul>
                            </div>
                        </div>
                        <div class="bottom-header">
                            <div class="flash-sale"><i class="fa-solid fa-bolt"></i><a href="">flash sales</a></div>
                            <div class="navbar">
                                <ul>
                                    
                            <li class="<?= $m == 'main' && $v == 'index' ? 'active' : ''; ?>"><a href="<?= base_url(); ?>"><?= lang('home'); ?></a></li>
                            <?php if ($isPromo) {
                                    ?>
                            <li class="<?= $m == 'shop' && $v == 'products' && $this->input->get('promo') == 'yes' ? 'active' : ''; ?>"><a href="<?= shop_url('products?promo=yes'); ?>"><?= lang('promotions'); ?></a></li>
                            <?php
                                } ?>
                            <li class="<?= $m == 'shop' && $v == 'products' && $this->input->get('promo') != 'yes' ? 'active' : ''; ?>"><a href="<?= shop_url('products'); ?>"><?= lang('products'); ?></a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    <?= lang('categories'); ?> <span class="caret"></span>
                                </a>                                
                            </li>
                            <li class="dropdown<?= (count($brands) > 20) ? ' mega-menu' : ''; ?>">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    <?= lang('brands'); ?> <span class="caret"></span>
                                </a>
                                <?php
                                if (count($brands) <= 10) {
                                    ?>
                                    <ul class="dropdown-menu">
                                        <?php
                                        foreach ($brands as $brand) {
                                            echo '<li><a href="' . site_url('brand/' . $brand->slug) . '" class="line-height-lg">' . $brand->name . '</a></li>';
                                        } ?>
                                    </ul>
                                    <?php
                                } elseif (count($brands) <= 20) {
                                    ?>
                                    <div class="dropdown-menu dropdown-menu-2x">
                                        <div class="dropdown-menu-content">
                                            <?php
                                            $brands_chunks = array_chunk($brands, 10);
                                    foreach ($brands_chunks as $brands) {
                                        ?>
                                                <div class="col-xs-6 padding-x-no line-height-md">
                                                    <ul class="nav">
                                                        <?php
                                                        foreach ($brands as $brand) {
                                                            echo '<li><a href="' . site_url('brand/' . $brand->slug) . '" class="line-height-lg">' . $brand->name . '</a></li>';
                                                        } ?>
                                                    </ul>
                                                </div>
                                                <?php
                                    } ?>
                                        </div>
                                    </div>
                                    <?php
                                } elseif (count($brands) > 20) {
                                    ?>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <div class="mega-menu-content">
                                                <div class="row">
                                                    <?php
                                                    $brands_chunks = array_chunk($brands, ceil(count($brands) / 4));
                                    foreach ($brands_chunks as $brands) {
                                        ?>
                                                        <div class="col-sm-3">
                                                            <ul class="list-unstyled">
                                                                <?php
                                                                foreach ($brands as $brand) {
                                                                    echo '<li><a href="' . site_url('brand/' . $brand->slug) . '" class="line-height-lg">' . $brand->name . '</a></li>';
                                                                } ?>
                                                            </ul>
                                                        </div>
                                                        <?php
                                    } ?>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                    <?php
                                }
                                ?>
                            </li>
                            <?php if (!$shop_settings->hide_price) {
                                    ?>
                            <li class="<?= $m == 'cart_ajax' && $v == 'index' ? 'active' : ''; ?>"><a href="<?= site_url('cart'); ?>"><?= lang('shopping_cart'); ?></a></li>
                            <li class="<?= $m == 'cart_ajax' && $v == 'checout' ? 'active' : ''; ?>"><a href="<?= site_url('cart/checkout'); ?>"><?= lang('checkout'); ?></a></li>
                            <?php
                                } ?>
                                </ul>
                            </div>
                        </div>
                        <div class="social-links"><a href=""><i class="fab fa-facebook"></i></a><a href=""><i
                                    class="fab fa-twitter"></i></a><a href=""><i class="fab fa-linkedin"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </header>
<main class="main" id="main">        
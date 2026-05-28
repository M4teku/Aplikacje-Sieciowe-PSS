<?php
/* Smarty version 4.5.5, created on 2026-05-29 01:40:49
  from 'cms_template:13' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.5',
  'unifunc' => 'content_6a18d2813889c4_14418344',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1712ca165c1007cb650b411a790636e29df28029' => 
    array (
      0 => 'cms_template:13',
      1 => '1780011441',
      2 => 'cms_template',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a18d2813889c4_14418344 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'C:\\xampp\\htdocs\\cmsms\\lib\\plugins\\function.title.php','function'=>'smarty_function_title',),1=>array('file'=>'C:\\xampp\\htdocs\\cmsms\\lib\\plugins\\function.sitename.php','function'=>'smarty_function_sitename',),2=>array('file'=>'C:\\xampp\\htdocs\\cmsms\\lib\\plugins\\function.metadata.php','function'=>'smarty_function_metadata',),3=>array('file'=>'C:\\xampp\\htdocs\\cmsms\\lib\\plugins\\function.root_url.php','function'=>'smarty_function_root_url',),));
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title><?php echo smarty_function_title(array(),$_smarty_tpl);?>
 - <?php echo smarty_function_sitename(array(),$_smarty_tpl);?>
</title>
    <?php echo smarty_function_metadata(array(),$_smarty_tpl);?>

    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" type="text/css" media="screen" />
    <?php echo '<script'; ?>
 src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"><?php echo '</script'; ?>
>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html, body {
            height: 100%;
        }
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            display: flex;
            flex-direction: column;
        }
        
        /* navabar */
        .navbar {
            background: #2c3e50;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }
        .logo a {
            color: white;
            font-size: 22px;
            font-weight: bold;
            text-decoration: none;
        }
        .logo a:hover {
            color: #f39c12;
        }
        .menu {
            display: flex;
            list-style: none;
            gap: 20px;
            margin: 0 auto;
        }
        .menu li a {
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 5px;
        }
        .menu li a:hover {
            background: #f39c12;
            color: #2c3e50;
        }
        .menu li.active a {
            background: #f39c12;
            color: #2c3e50;
        }
        
        /* Kontenery główne */
        .main {
            flex: 1;
            max-width: 1100px;
            margin: 20px auto 0 auto;
            padding: 0 20px;
            width: 100%;
        }
        .content {
            background: white;
            padding: 30px;
            border-radius: 10px;
            width: 100%;
            display: block;
            clear: both;
        }
        footer {
            background: #2c3e50;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: 30px;
            width: 100%;
        }

        /* aktualności */
        .news-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .news-item {
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .news-item-content {
            padding: 20px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }
        .news-date {
            font-size: 13px;
            color: #7f8c8d;
            margin-bottom: 10px;
        }
        .news-title {
            font-size: 18px;
            color: #2c3e50;
            margin-bottom: 12px;
            font-weight: bold;
        }
        .news-summary {
            font-size: 14px;
            color: #555;
            line-height: 1.6;
            margin-bottom: 20px;
            flex: 1;
        }
        .news-more {
            align-self: flex-start;
            background: #2c3e50;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 13px;
        }

        /*home page */
        .main-dashboard {
            display: flex;
            gap: 30px;
        }
        .dashboard-news {
            flex: 2;
        }
        .dashboard-sidebar {
            flex: 1;
            background: #f8f9fa;
            border-left: 4px solid #2c3e50;
            padding: 25px;
            border-radius: 8px;
            height: fit-content;
        }
        .section-title {
            font-size: 1.4rem;
            color: #2c3e50;
            border-bottom: 2px solid #ecf0f1;
            padding-bottom: 10px;
            margin-bottom: 25px;
            text-transform: uppercase;
            font-weight: bold;
        }
        .sidebar-gallery-btn {
            display: block;
            text-align: center;
            background: #2c3e50;
            color: #ffffff !important;
            padding: 12px;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            margin-top: 15px;
        }

        @media (max-width: 768px) {
            .navbar { flex-direction: column; gap: 15px; }
            .menu { flex-wrap: wrap; justify-content: center; }
            .main-dashboard { flex-direction: column; }
        }
    </style>
</head>
<body>

<div class="navbar">
    <div class="logo"><a href="<?php echo smarty_function_root_url(array(),$_smarty_tpl);?>
"><?php echo smarty_function_sitename(array(),$_smarty_tpl);?>
</a></div>
    <ul class="menu">
        
    </ul>
</div>

<div class="main">
    <div class="content">
        
        <?php if ($_smarty_tpl->tpl_vars['page_alias']->value == 'home' || $_smarty_tpl->tpl_vars['page_alias']->value == 'glowna') {?>
            <div class="main-dashboard">
                
                <div class="dashboard-news">
                    <h3 class="section-title">Najnowsze Wydarzenia</h3>
                    
                </div>
                
                <div class="dashboard-sidebar">
                    <h3 class="section-title">Ostatnio w Galerii</h3>
                    <div class="sidebar-gallery-wrapper">
                      
                    </div>
                    <a href="index.php?page=galeria" class="sidebar-gallery-btn">Przejdź do Galerii →</a>
                </div>

            </div>
        <?php } else { ?>
            
        <?php }?>

    </div>
</div>

<footer>
    <p>© <?php echo smarty_function_sitename(array(),$_smarty_tpl);?>
.</p>
</footer>

</body>
</html><?php }
}

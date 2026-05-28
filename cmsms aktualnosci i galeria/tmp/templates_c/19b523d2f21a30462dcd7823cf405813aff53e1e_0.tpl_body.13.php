<?php
/* Smarty version 4.5.5, created on 2026-05-29 01:45:30
  from 'tpl_body:13' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.5',
  'unifunc' => 'content_6a18d39a5494b3_62765073',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '19b523d2f21a30462dcd7823cf405813aff53e1e' => 
    array (
      0 => 'tpl_body:13',
      1 => '1780011929',
      2 => 'tpl_body',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a18d39a5494b3_62765073 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'C:\\xampp\\htdocs\\cmsms\\lib\\plugins\\function.root_url.php','function'=>'smarty_function_root_url',),1=>array('file'=>'C:\\xampp\\htdocs\\cmsms\\lib\\plugins\\function.sitename.php','function'=>'smarty_function_sitename',),2=>array('file'=>'C:\\xampp\\htdocs\\cmsms\\lib\\plugins\\function.cms_selflink.php','function'=>'smarty_function_cms_selflink',),3=>array('file'=>'C:\\xampp\\htdocs\\cmsms\\lib\\plugins\\function.current_date.php','function'=>'smarty_cms_function_current_date',),));
?>
<body>

<div class="navbar">
    <div class="logo"><a href="<?php echo smarty_function_root_url(array(),$_smarty_tpl);?>
"><?php echo smarty_function_sitename(array(),$_smarty_tpl);?>
</a></div>
    <ul class="menu">
        <?php echo Navigator::function_plugin(array('template'=>'Szablon Menu','number_of_levels'=>'1'),$_smarty_tpl);?>

    </ul>
</div>

<div class="main">
    <div class="content">
        
        <?php if ($_smarty_tpl->tpl_vars['page_alias']->value == 'home' || $_smarty_tpl->tpl_vars['page_alias']->value == 'glowna') {?>
            <div class="main-dashboard">
                
                <div class="dashboard-news">
                    <h3 class="section-title">Najnowsze Wydarzenia</h3>
                    <?php echo News::function_plugin(array('number'=>'2','template'=>'Kafelki','detailpage'=>'4'),$_smarty_tpl);?>

                </div>
                
                <div class="dashboard-sidebar">
                    <h3 class="section-title">Ostatnio w Galerii</h3>
                    <div class="sidebar-gallery-wrapper">
                                                <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['Gallery'][0], array( array('sortby'=>'date_desc','number'=>'4','template'=>'MojaGaleria','showonly'=>'images'),$_smarty_tpl ) );?>

                    </div>
                    <a href="<?php echo smarty_function_cms_selflink(array('href'=>'galeria'),$_smarty_tpl);?>
" class="sidebar-gallery-btn">Przejdź do Galerii →</a>
                </div>

            </div>
        <?php } else { ?>
            <?php CMS_Content_Block::smarty_internal_fetch_contentblock(array(),$_smarty_tpl); ?>
        <?php }?>

    </div>
</div>

<footer>
    <p>© <?php echo $_smarty_tpl->tpl_vars['sitename']->value;?>
 <?php echo smarty_cms_function_current_date(array('format'=>"%Y"),$_smarty_tpl);?>
.</p>
</footer>

</body>
</html><?php }
}

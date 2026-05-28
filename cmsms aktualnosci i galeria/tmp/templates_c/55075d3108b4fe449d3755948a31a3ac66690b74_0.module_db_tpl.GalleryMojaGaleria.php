<?php
/* Smarty version 4.5.5, created on 2026-05-29 01:54:00
  from 'module_db_tpl:Gallery;MojaGaleria' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.5',
  'unifunc' => 'content_6a18d598587000_88429657',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '55075d3108b4fe449d3755948a31a3ac66690b74' => 
    array (
      0 => 'module_db_tpl:Gallery;MojaGaleria',
      1 => 1780012436,
      2 => 'module_db_tpl',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a18d598587000_88429657 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="gallery-container">
    
        <?php if ($_smarty_tpl->tpl_vars['page_alias']->value != 'home' && $_smarty_tpl->tpl_vars['page_alias']->value != 'glowna') {?>
        <?php if (!empty($_smarty_tpl->tpl_vars['gallerytitle']->value)) {?>
            <h2 class="gallery-title"><?php echo $_smarty_tpl->tpl_vars['gallerytitle']->value;?>
</h2>
        <?php }?>

        <?php if (!empty($_smarty_tpl->tpl_vars['parent_url']->value)) {?>
            <a href="<?php echo $_smarty_tpl->tpl_vars['parent_url']->value;?>
" class="news-back-link" style="margin-bottom:20px; display:inline-block; color:#f39c12; font-weight:bold; text-decoration:none;">← Powrót do głównej galerii</a>
        <?php }?>
    <?php }?>
    
    <div class="gallery-grid">
        
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['images']->value, 'image');
$_smarty_tpl->tpl_vars['image']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['image']->value) {
$_smarty_tpl->tpl_vars['image']->do_else = false;
?>
            <?php if ($_smarty_tpl->tpl_vars['image']->value->isdir) {?>
                <div class="gallery-item gallery-dir-item">
                    <a href="<?php echo $_smarty_tpl->tpl_vars['image']->value->file;?>
">
                        <?php if (!empty($_smarty_tpl->tpl_vars['image']->value->thumb)) {?>
                            <img src="<?php echo $_smarty_tpl->tpl_vars['image']->value->thumb;?>
" alt="<?php echo $_smarty_tpl->tpl_vars['image']->value->title;?>
" />
                        <?php }?>
                        <div class="gallery-dir-title"><?php echo $_smarty_tpl->tpl_vars['image']->value->title;?>
</div>
                    </a>
                </div>
            <?php }?>
        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['images']->value, 'image');
$_smarty_tpl->tpl_vars['image']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['image']->value) {
$_smarty_tpl->tpl_vars['image']->do_else = false;
?>
            <?php if (!$_smarty_tpl->tpl_vars['image']->value->isdir) {?>
                <div class="gallery-item">
                    <a href="<?php echo $_smarty_tpl->tpl_vars['image']->value->file;?>
" data-lightbox="moje-zamki" data-title="<?php echo $_smarty_tpl->tpl_vars['image']->value->title;?>
">
                        <img src="<?php echo $_smarty_tpl->tpl_vars['image']->value->thumb;?>
" alt="<?php echo $_smarty_tpl->tpl_vars['image']->value->title;?>
" />
                    </a>
                </div>
            <?php }?>
        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        
    </div>
</div><?php }
}

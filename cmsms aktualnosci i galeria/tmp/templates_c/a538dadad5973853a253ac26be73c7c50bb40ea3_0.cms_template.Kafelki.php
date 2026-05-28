<?php
/* Smarty version 4.5.5, created on 2026-05-29 01:40:17
  from 'cms_template:Kafelki' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.5',
  'unifunc' => 'content_6a18d2613c1b06_14021436',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a538dadad5973853a253ac26be73c7c50bb40ea3' => 
    array (
      0 => 'cms_template:Kafelki',
      1 => '1780002194',
      2 => 'cms_template',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a18d2613c1b06_14021436 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'C:\\xampp\\htdocs\\cmsms\\lib\\plugins\\modifier.cms_date_format.php','function'=>'smarty_modifier_cms_date_format',),1=>array('file'=>'C:\\xampp\\htdocs\\cmsms\\lib\\smarty\\plugins\\modifier.truncate.php','function'=>'smarty_modifier_truncate',),));
?>
<div class="news-list">
<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['items']->value, 'entry');
$_smarty_tpl->tpl_vars['entry']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['entry']->value) {
$_smarty_tpl->tpl_vars['entry']->do_else = false;
?>
    <div class="news-item">
        <div class="news-item-content">
            <div class="news-date">
                <?php echo smarty_modifier_cms_date_format($_smarty_tpl->tpl_vars['entry']->value->postdate);?>

            </div>
            
            <div class="news-title">
                <a href="<?php echo $_smarty_tpl->tpl_vars['entry']->value->moreurl;?>
" title="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['entry']->value->title, ENT_QUOTES, 'UTF-8', true);?>
"><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['entry']->value->title, ENT_QUOTES, 'UTF-8', true);?>
</a>
            </div>

            <?php if ($_smarty_tpl->tpl_vars['entry']->value->summary) {?>
                <div class="news-summary">
                    <?php $_template = new CMS_Smarty_Template('eval:'.$_smarty_tpl->tpl_vars['entry']->value->summary, $_smarty_tpl->smarty, $_smarty_tpl);echo $_template->fetch(); ?>
                </div>
            <?php } elseif ($_smarty_tpl->tpl_vars['entry']->value->content) {?>
                <div class="news-summary">
                    <?php $_template = new CMS_Smarty_Template('eval:'.smarty_modifier_truncate(preg_replace('!<[^>]*?>!', ' ', (string) $_smarty_tpl->tpl_vars['entry']->value->content),150), $_smarty_tpl->smarty, $_smarty_tpl);echo $_template->fetch(); ?>
                </div>
            <?php }?>

            <a class="news-more" href="<?php echo $_smarty_tpl->tpl_vars['entry']->value->moreurl;?>
">Czytaj więcej</a>
        </div>
    </div>
<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
</div><?php }
}

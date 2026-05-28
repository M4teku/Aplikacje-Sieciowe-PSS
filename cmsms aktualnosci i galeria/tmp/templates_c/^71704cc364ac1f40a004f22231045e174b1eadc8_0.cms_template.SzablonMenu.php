<?php
/* Smarty version 4.5.5, created on 2026-05-29 01:37:22
  from 'cms_template:Szablon Menu' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.5',
  'unifunc' => 'content_6a18d1b2f255e5_96476276',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '71704cc364ac1f40a004f22231045e174b1eadc8' => 
    array (
      0 => 'cms_template:Szablon Menu',
      1 => '1780002036',
      2 => 'cms_template',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a18d1b2f255e5_96476276 (Smarty_Internal_Template $_smarty_tpl) {
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['nodes']->value, 'node');
$_smarty_tpl->tpl_vars['node']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['node']->value) {
$_smarty_tpl->tpl_vars['node']->do_else = false;
?>
    <li class="<?php if ($_smarty_tpl->tpl_vars['node']->value->current) {?>active<?php }?>">
        <a href="<?php echo $_smarty_tpl->tpl_vars['node']->value->url;?>
"><?php echo $_smarty_tpl->tpl_vars['node']->value->menutext;?>
</a>
    </li>
<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
}
}

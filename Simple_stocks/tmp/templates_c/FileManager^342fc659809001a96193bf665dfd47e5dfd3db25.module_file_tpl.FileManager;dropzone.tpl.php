<?php /* Smarty version Smarty-3.1-DEV, created on 2017-11-08 16:08:42
         compiled from "module_file_tpl:FileManager;dropzone.tpl" */ ?>
<?php /*%%SmartyHeaderCode:319365a03806a45eb74-64389491%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '342fc659809001a96193bf665dfd47e5dfd3db25' => 
    array (
      0 => 'module_file_tpl:FileManager;dropzone.tpl',
      1 => 1510168114,
      2 => 'module_file_tpl',
    ),
  ),
  'nocache_hash' => '319365a03806a45eb74-64389491',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'is_ie' => 0,
    'chdir_url' => 0,
    'max_chunksize' => 0,
    'dirlist' => 0,
    'FileManager' => 0,
    'cwd' => 0,
    'formstart' => 0,
    'actionid' => 0,
    'prompt_dropfiles' => 0,
    'formend' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_5a03806a4994f0_25995124',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5a03806a4994f0_25995124')) {function content_5a03806a4994f0_25995124($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include 'C:\\Users\\ksherrerd\\Desktop\\hph\\smallprgs\\Simple_stocks\\lib\\smarty\\plugins\\function.html_options.php';
?><?php if (!isset($_smarty_tpl->tpl_vars['is_ie']->value)) {?>

<script type="text/javascript">
$(document).ready(function(){

    var thediv = '#theme_dropzone';

    $(document).on('dialogopen', '.drop .dialog', function(event,ui){
        var url = '<?php echo $_smarty_tpl->tpl_vars['chdir_url']->value;?>
';
            url = url.replace(/amp;/g,'')+'&showtemplate=false';

        $.get(url,function(data) {
            $('#fm_newdir').val('/'+data);
        });
    });

    $('#chdir_form').submit(function(e){
        var data = $(this).serialize();
        var url = '<?php echo $_smarty_tpl->tpl_vars['chdir_url']->value;?>
';
        url = url.replace(/amp;/g,'')+'&showtemplate=false';

        $.post(url,data,function(data,textStatus,jqXHR){
            // stuff to do on post finishing.
            $('#chdir_form').trigger('dropzone_chdir');
            $('.dialog').dialog('close');
        });

    e.preventDefault();
});

// prevent browser default drag/drop handling
$(document).on('drop dragover', function(e) {
    // prevent default drag/drop stuff.
    e.preventDefault();
});

    $(thediv+'_i').fileupload({
        dataType: 'json',
        dropZone: $(thediv),
        maxChunkSize: <?php echo $_smarty_tpl->tpl_vars['max_chunksize']->value;?>
,

        progressall: function(e,data) {
            var total = (data.loaded / data.total * 100).toFixed(0);

            $(thediv).progressbar({ value: parseInt(total) });
            $('.ui-progressbar-value').html(total+'%');
         },

         stop: function(e,data) {
           $(thediv).progressbar('destroy');
           $(thediv).trigger('dropzone_stop');
         }
    });
});
</script>
<div class="drop">
	<div class="drop-inner cf">
	<?php if (isset($_smarty_tpl->tpl_vars['dirlist']->value)) {?>
		<span class="folder-selection open" title="<?php echo lang('open');?>
"></span>
		<div class="dialog invisible" role="dialog" title="<?php echo $_smarty_tpl->tpl_vars['FileManager']->value->Lang('change_working_folder');?>
">
			<form id="chdir_form" class="cms_form" action="<?php echo $_smarty_tpl->tpl_vars['chdir_url']->value;?>
" method="post">
				<fieldset>
					<legend><?php echo $_smarty_tpl->tpl_vars['FileManager']->value->Lang('change_working_folder');?>
</legend>
					<label><?php echo $_smarty_tpl->tpl_vars['FileManager']->value->Lang('folder');?>
: </label>
                                        <input type="hidden" name="m1_path" value="<?php echo $_smarty_tpl->tpl_vars['cwd']->value;?>
"/>
                                        <input type="hidden" name="m1_ajax" value="1"/>
					<select class="cms_dropdown" id="fm_newdir" name="m1_newdir">
                                          <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['dirlist']->value,'selected'=>"/".((string)$_smarty_tpl->tpl_vars['cwd']->value)),$_smarty_tpl);?>

					</select>
					<input type="submit" name="m1_submit" value="<?php echo $_smarty_tpl->tpl_vars['FileManager']->value->lang('submit');?>
" />
				</fieldset>
				</form>
		</div>
	<?php }?>
		<div class="zone">
			<div id="theme_dropzone">
				<?php echo $_smarty_tpl->tpl_vars['formstart']->value;?>

				<input type="hidden" name="disable_buffer" value="1"/>
				<input type="file" id="theme_dropzone_i" name="<?php echo $_smarty_tpl->tpl_vars['actionid']->value;?>
files[]" multiple style="display: none;"/>
				<?php echo $_smarty_tpl->tpl_vars['prompt_dropfiles']->value;?>

				<?php echo $_smarty_tpl->tpl_vars['formend']->value;?>

			</div>
		</div>
	</div>
	<a href="#" title="<?php echo lang('open');?>
/<?php echo lang('close');?>
" class="toggle-dropzone"><?php echo lang('open');?>
/<?php echo lang('close');?>
</a>
</div>
<?php }?><?php }} ?>

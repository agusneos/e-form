<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<script type="text/javascript" src="<?=base_url('assets/easyui/datagrid-scrollview.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/easyui/datagrid-filter.js')?>"></script>

<!-- Data Grid -->
<table id="grid-master_departemen"
    data-options="pageSize:50, multiSort:true, remoteSort:false, rownumbers:true, singleSelect:true, 
                fit:true, fitColumns:true, toolbar:toolbar_master_departemen">
    <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'a.departemen_id'"                   width="100" align="center" sortable="true">ID</th>
            <th data-options="field:'b.departemen_nama'"                 width="400" halign="center" align="left" sortable="true">Departemen</th>
            <th data-options="field:'a.departemen_nama'"                 width="400" halign="center" align="left" sortable="true">Bagian</th>
            </tr>
    </thead>
</table>

<script type="text/javascript">
    var toolbar_master_departemen = [{
        text:'New',
        iconCls:'icon-new_file',
        handler:function(){masterDepartemenCreate();}
    },{
        text:'Edit',
        iconCls:'icon-edit',
        handler:function(){masterDepartemenUpdate();}
    },{
        text:'Delete',
        iconCls:'icon-cancel',
        handler:function(){masterDepartemenHapus();}
    },{
        text:'Refresh',
        iconCls:'icon-reload',
        handler:function(){$('#grid-master_departemen').datagrid('reload');}
    }];
    
    $('#grid-master_departemen').datagrid({view:scrollview,remoteFilter:true,
        url:'<?php echo site_url('master/departemen/index'); ?>?grid=true'})
        .datagrid('enableFilter');
    
    function masterDepartemenCreate() {
        $('#dlg-master_departemen').dialog({modal: true}).dialog('open').dialog('setTitle','Tambah Data');
        $('#fm-master_departemen').form('clear');
        url = '<?php echo site_url('master/departemen/create'); ?>';
    }
    
    function masterDepartemenUpdate() {
        var row = $('#grid-master_departemen').datagrid('getSelected');
        if(row){
            $('#dlg-master_departemen').dialog({modal: true}).dialog('open').dialog('setTitle','Edit Data');
            $('#fm-master_departemen').form('load',row);
            url = '<?php echo site_url('master/departemen/update'); ?>/' + row['a.departemen_id'];            
        }
        else
        {
             $.messager.alert('Info','Data belum dipilih !','info');
        }
    }
    
    function masterDepartemenSave(){
        $('#fm-master_departemen').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success){
                    $('#dlg-master_departemen').dialog('close');
                    $('#grid-master_departemen').datagrid('reload');
                    $.messager.show({
                        title: 'Info',
                        msg: 'Data Berhasil Disimpan'
                    });
                } else {
                    $.messager.show({
                        title: 'Error',
                        msg: 'Input/Update Data Gagal, Nama Bagian Pada Departemen Tersebut Sudah Ada'
                    });
                }
            }
        });
    }
    
    function masterDepartemenHapus(){
        var row = $('#grid-master_departemen').datagrid('getSelected');
        if (row){
            $.messager.confirm('Konfirmasi','Anda yakin ingin menghapus Departeman '+row['b.departemen_nama']+' Bagian '+row['a.departemen_nama']+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('master/departemen/delete'); ?>',{id:row['a.departemen_id']},function(result){
                        if (result.success){
                            $('#grid-master_departemen').datagrid('reload');
                            $.messager.show({
                                title: 'Info',
                                msg: 'Hapus Data Berhasil'
                            });
                        } else {
                            $.messager.show({
                                title: 'Error',
                                msg: 'Hapus Data Gagal'
                            });
                        }
                    },'json');
                }
            });
        }
        else
        {
             $.messager.alert('Info','Data belum dipilih !','info');
        }
    }    
</script>

<style type="text/css">
    #fm-master_departemen{
        margin:0;
        padding:10px 30px;
    }
    .ftitle{
        font-size:14px;
        font-weight:bold;
        padding:5px 0;
        margin-bottom:10px;
        border-bottom:1px solid #ccc;
    }
    .fitem{
        margin-bottom:5px;
    }
    .fitem label{
        display:inline-block;
        width:100px;
    }
    .fitem input{
        display:inline-block;
        width:150px;
    }
</style>

<!-- ----------- -->
<div id="dlg-master_departemen" class="easyui-dialog" style="width:600px; height:300px; padding: 10px 20px" closed="true" buttons="#dlg-buttons-master_departemen">
    <form id="fm-master_departemen" method="post" novalidate>        
        <div class="fitem">
            <label for="type">Departemen</label>
            <input type="text" id="id_induk" name="a.departemen_induk" class="easyui-combobox" data-options="
                url:'<?php echo site_url('master/departemen/getDept'); ?>',
                method:'get', valueField:'id', textField:'departemen', panelHeight:'220'"/>
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-reload" plain="true" 
                onclick="$('#id_induk').combobox('reload', '<?php echo site_url('master/departemen/getDept'); ?>')"></a>
        </div>
        <div class="fitem">
            <label for="type">Bagian</label>
            <input type="text" id="bagian" name="a.departemen_nama" style="width:350px;" class="easyui-textbox" required="true"/>
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg-buttons-master_departemen">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="masterDepartemenSave()">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-master_departemen').dialog('close')">Batal</a>
</div>
<!-- End of file v_departemen.php -->
<!-- Location: ./application/views/master/v_departemen.php -->
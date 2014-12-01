<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<script type="text/javascript" src="<?=base_url('assets/easyui/datagrid-scrollview.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/easyui/datagrid-filter.js')?>"></script>

<!-- Data Grid -->
<table id="grid-master_karyawan"
    data-options="pageSize:50, multiSort:true, remoteSort:false, rownumbers:true, singleSelect:true, 
                fit:true, fitColumns:true, toolbar:toolbar_master_karyawan">
    <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'karyawan_nik'"         width="100" align="center" sortable="true">NIK</th>
            <th data-options="field:'karyawan_nama'"        width="400" halign="center" align="left" sortable="true">Nama Karyawan</th>
            <th data-options="field:'c.departemen_nama'"    width="400" halign="center" align="left" sortable="true">Departemen</th>
            <th data-options="field:'b.departemen_nama'"    width="400" halign="center" align="left" sortable="true">Bagian</th>
            </tr>
    </thead>
</table>

<script type="text/javascript">
    var toolbar_master_karyawan = [{
        text:'New',
        iconCls:'icon-new_file',
        handler:function(){masterCustomerCreate();}
    },{
        text:'Edit',
        iconCls:'icon-edit',
        handler:function(){masterCustomerUpdate();}
    },{
        text:'Delete',
        iconCls:'icon-cancel',
        handler:function(){masterCustomerHapus();}
    },{
        text:'Refresh',
        iconCls:'icon-reload',
        handler:function(){$('#grid-master_karyawan').datagrid('reload');}
    }];
    
    $('#grid-master_karyawan').datagrid({view:scrollview,remoteFilter:true,
        url:'<?php echo site_url('master/karyawan/index'); ?>?grid=true'})
        .datagrid('enableFilter');
    
    function masterCustomerCreate() {
        $('#dlg-master_karyawan').dialog({modal: true}).dialog('open').dialog('setTitle','Tambah Data');
        $('#fm-master_karyawan').form('clear');
        url = '<?php echo site_url('master/karyawan/create'); ?>';
        $('#nik').textbox({disabled: false});
    }
    
    function masterCustomerUpdate() {
        var row = $('#grid-master_karyawan').datagrid('getSelected');
        if(row){
            $('#dlg-master_karyawan').dialog({modal: true}).dialog('open').dialog('setTitle','Edit Data');
            $('#fm-master_karyawan').form('load',row);
            url = '<?php echo site_url('master/karyawan/update'); ?>/' + row.karyawan_nik;
            $('#nik').textbox({disabled: true});
        }
        else
        {
             $.messager.alert('Info','Data belum dipilih !','info');
        }
    }
    
    function masterCustomerSave(){
        $('#fm-master_karyawan').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success){
                    $('#dlg-master_karyawan').dialog('close');
                    $('#grid-master_karyawan').datagrid('reload');
                    $.messager.show({
                        title: 'Info',
                        msg: 'Data Berhasil Disimpan'
                    });
                } else {
                    $.messager.show({
                        title: 'Error',
                        msg: 'Input/Update Data Gagal, NIK Sudah Ada'
                    });
                }
            }
        });
    }
        
    function masterCustomerHapus(){
        var row = $('#grid-master_karyawan').datagrid('getSelected');
        if (row){
            $.messager.confirm('Konfirmasi','Anda yakin ingin menghapus Karyawan '+row.karyawan_nama+' dengan NIK '+row.karyawan_nik+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('master/karyawan/delete'); ?>',{karyawan_nik:row.karyawan_nik},function(result){
                        if (result.success){
                            $('#grid-master_karyawan').datagrid('reload');
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
    #fm-master_karyawan{
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
<div id="dlg-master_karyawan" class="easyui-dialog" style="width:600px; height:300px; padding: 10px 20px" closed="true" buttons="#dlg-buttons-master_karyawan">
    <form id="fm-master_karyawan" method="post" novalidate>        
        <div class="fitem">
            <label for="type">NIK</label>
            <input type="text" id="nik" name="karyawan_nik" class="easyui-textbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Nama Karyawan</label>
            <input type="text" id="nama" name="karyawan_nama" style="width:350px;" class="easyui-textbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Bagian</label>
            <input type="text" id="bagian2" name="karyawan_bagian" style="width:350px;" class="easyui-combobox" required="true"
                data-options="url:'<?php echo site_url('master/karyawan/getDept'); ?>',
                method:'get', valueField:'id', textField:'bagian', groupField:'departemen', panelHeight:'300'" />
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg-buttons-master_karyawan">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="masterCustomerSave()">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-master_karyawan').dialog('close')">Batal</a>
</div>

<!-- End of file v_karyawan.php -->
<!-- Location: ./application/views/master/v_karyawan.php -->
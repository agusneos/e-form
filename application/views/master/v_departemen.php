<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<script type="text/javascript" src="<?=base_url('assets/easyui/datagrid-scrollview.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/easyui/datagrid-filter.js')?>"></script>
<script type="text/javascript">
    $.extend($.fn.datebox.defaults,{
        formatter:function(date){
            var y = date.getFullYear();
            var m = date.getMonth()+1;
            var d = date.getDate();
            return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
        },
        parser:function(s){
            if (!s) return new Date();
            var ss = (s.split('-'));
            var y = parseInt(ss[0],10);
            var m = parseInt(ss[1],10);
            var d = parseInt(ss[2],10);
            if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
                return new Date(y,m-1,d);
            } else {
                return new Date();
            }
        }
    });
        
    $.extend($.fn.datetimebox.defaults,{
        formatter:function(date){
            var h = date.getHours();
            var M = date.getMinutes();
            var s = date.getSeconds();
            function formatNumber(value){
                return (value < 10 ? '0' : '') + value;
            }
            var separator = $(this).datetimebox('spinner').timespinner('options').separator;
            var r = $.fn.datebox.defaults.formatter(date) + ' ' + formatNumber(h)+separator+formatNumber(M);
            if ($(this).datetimebox('options').showSeconds){
                r += separator+formatNumber(s);
            }
            return r;
        },
        parser:function(s){
            if ($.trim(s) == ''){
                return new Date();
            }
            var dt = s.split(' ');
            var d = $.fn.datebox.defaults.parser(dt[0]);
            if (dt.length < 2){
                return d;
            }
            var separator = $(this).datetimebox('spinner').timespinner('options').separator;
            var tt = dt[1].split(separator);
            var hour = parseInt(tt[0], 10) || 0;
            var minute = parseInt(tt[1], 10) || 0;
            var second = parseInt(tt[2], 10) || 0;
            return new Date(d.getFullYear(), d.getMonth(), d.getDate(), hour, minute, second);
        }
    });
</script>
<!-- Data Grid -->
<table id="grid-master_departemen"
    data-options="pageSize:50, multiSort:true, remoteSort:true, rownumbers:true, singleSelect:true, 
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
        id      : 'master_departemen-new',
        text    : 'New',
        iconCls : 'icon-new_file',
        handler : function(){master_departemenCreate();}
    },{
        id      : 'master_departemen-edit',
        text    : 'Edit',
        iconCls : 'icon-edit',
        handler : function(){master_departemenUpdate();}
    },{
        id      : 'master_departemen-delete',
        text    : 'Delete',
        iconCls : 'icon-cancel',
        handler : function(){master_departemenHapus();}
    },{
        text    : 'Refresh',
        iconCls : 'icon-reload',
        handler : function(){masterDepartemenRefresh();}
    },{
        id      : 'master_departemen-head_delete',
        text    : 'Delete/Edit Induk Departemen',
        iconCls : 'icon-cancel',
        handler : function(){masterDepartemenHeadDelete();}
    }];
    
    function masterDepartemenRefresh() {
        $('#master_departemen-edit').linkbutton('disable');
        $('#master_departemen-delete').linkbutton('disable');
        $('#master_departemen-head_delete').linkbutton('disable');
        $('#grid-master_departemen').datagrid('reload');
    }
    
    function masterDepartemenHeadDelete() {
        $('#master_departemen-edit').linkbutton('enable');
        $('#master_departemen-delete').linkbutton('enable');
    }
    
    $('#grid-master_departemen').datagrid({view:scrollview,remoteFilter:true,
        url:'<?php echo site_url('master/departemen/index'); ?>?grid=true'})
        .datagrid({	
        onLoadSuccess: function(data){
            $('#master_departemen-edit').linkbutton('disable');
            $('#master_departemen-delete').linkbutton('disable');
            $('#master_departemen-head_delete').linkbutton('disable');
        },
        onClickRow: function(index,row){
            if(row['b.departemen_nama'] === null){
                $('#master_departemen-edit').linkbutton('disable');
                $('#master_departemen-delete').linkbutton('disable');
                $('#master_departemen-head_delete').linkbutton('enable');
            }
            else{
                $('#master_departemen-edit').linkbutton('enable');
                $('#master_departemen-delete').linkbutton('enable');
                $('#master_departemen-head_delete').linkbutton('disable');
            }            
        },
        onDblClickRow: function(index,row){
            if(row['b.departemen_nama'] !== null){
                master_departemenUpdate();
            }
	}
        }).datagrid('enableFilter');
    
    function master_departemenCreate() {
        $('#dlg-master_departemen').dialog({modal: true}).dialog('open').dialog('setTitle','Tambah Data');
        $('#fm-master_departemen').form('clear');
        url = '<?php echo site_url('master/departemen/create'); ?>';
        $('#id_induk').combobox('reload', '<?php echo site_url('master/departemen/getDept'); ?>');
    }
    
    function master_departemenUpdate() {
        var row = $('#grid-master_departemen').datagrid('getSelected');
        if(row){
            $('#dlg-master_departemen').dialog({modal: true}).dialog('open').dialog('setTitle','Edit Data');
            $('#fm-master_departemen').form('load',row);
            url = '<?php echo site_url('master/departemen/update'); ?>/' + row['a.departemen_id'];
            $('#id_induk').combobox('reload', '<?php echo site_url('master/departemen/getDept'); ?>');
        }
        else
        {
             $.messager.alert('Info','Data belum dipilih !','info');
        }
    }
    
    function master_departemenSave(){
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
    
    function master_departemenHapus(){
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
        </div>
        <div class="fitem">
            <label for="type">Bagian</label>
            <input type="text" id="bagian" name="a.departemen_nama" style="width:350px;" class="easyui-textbox" required="true"/>
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg-buttons-master_departemen">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="master_departemenSave()">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-master_departemen').dialog('close')">Batal</a>
</div>

<!-- End of file v_departemen.php -->
<!-- Location: ./application/views/master/v_departemen.php -->
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
<table id="grid-master_mobil"
    data-options="pageSize:50, multiSort:true, remoteSort:true, rownumbers:true, singleSelect:true, 
                fit:true, fitColumns:false, toolbar:toolbar_master_mobil">
    <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'mobil_no'"             width="100"  halign="center" align="center" sortable="true" >Nomor Mobil</th>
        </tr>
    </thead>
</table>

<script type="text/javascript">
    var toolbar_master_mobil = [{
        id      : 'mobil_new',
        text    : 'New',
        iconCls : 'icon-new_file',
        handler : function(){masterMobilCreate();}
    },{
        id      : 'mobil_edit',
        text    : 'Edit',
        iconCls : 'icon-edit',
        handler : function(){masterMobilUpdate();}
    },{
        id      : 'mobil_delete',
        text    : 'Delete',
        iconCls : 'icon-cancel',
        handler : function(){masterMobilHapus();}
    },{
        text    : 'Refresh',
        iconCls : 'icon-reload',
        handler : function(){masterMobilRefresh();}
    }];
       
    $('#grid-master_mobil').datagrid({view:scrollview,remoteFilter:true,
        url:'<?php echo site_url('master/mobil/index'); ?>?grid=true'})
        .datagrid({	
        onLoadSuccess: function(data){
            $('#mobil_edit').linkbutton('disable');
            $('#mobil_delete').linkbutton('disable');
        },
        onClickRow: function(index,row){
            $('#mobil_edit').linkbutton('enable');
            $('#mobil_delete').linkbutton('enable');
	},
        onDblClickRow: function(index,row){
            masterMobilUpdate();
	}
        })
        .datagrid('enableFilter');
    
    function masterMobilRefresh() {
        $('#mobil_edit').linkbutton('disable');
        $('#mobil_delete').linkbutton('disable');
        $('#grid-master_mobil').datagrid('reload');
    }
    
    function masterMobilCreate() {
        $('#dlg-master_mobil').dialog({modal: true}).dialog('open').dialog('setTitle','Tambah Data');
        $('#fm-master_mobil').form('clear');
        url = '<?php echo site_url('master/mobil/create'); ?>';
    }
    
    function masterMobilUpdate() {
        var row = $('#grid-master_mobil').datagrid('getSelected');
        if(row){
            $('#dlg-master_mobil').dialog({modal: true}).dialog('open').dialog('setTitle','Edit Data');
            $('#fm-master_mobil').form('load',row);
            url = '<?php echo site_url('master/mobil/update'); ?>/' + row.mobil_id;
        }
        else
        {
             $.messager.alert('Info','Data belum dipilih !','info');
        }
    }
    
    function masterMobilSave(){
        $('#fm-master_mobil').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success){
                    $('#dlg-master_mobil').dialog('close');
                    masterMobilRefresh();
                    $.messager.show({
                        title: 'Info',
                        msg: 'Data Berhasil Disimpan'
                    });
                } else {
                    $.messager.show({
                        title: 'Error',
                        msg: 'Input/Update Data Gagal'
                    });
                }
            }
        });
    }
        
    function masterMobilHapus(){
        var row = $('#grid-master_mobil').datagrid('getSelected');
        if (row){
            $.messager.confirm('Konfirmasi','Anda yakin ingin menghapus mobil no. '+row.mobil_no+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('master/mobil/delete'); ?>',{mobil_id:row.mobil_id},function(result){
                        if (result.success){
                            masterMobilRefresh();
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
    #fm-master_mobil{
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
<div id="dlg-master_mobil" class="easyui-dialog" style="width:400px; height:200px; padding: 10px 20px" closed="true" buttons="#dlg-buttons-master_mobil">
    <form id="fm-master_mobil" method="post" novalidate>
        <div class="fitem">
            <label for="type">Nomor Mobil</label>
            <input type="text" id="mobil_no" name="mobil_no" style="width:100px;" class="easyui-textbox" required="true"/>
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg-buttons-master_mobil">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="masterMobilSave()">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-master_mobil').dialog('close')">Batal</a>
</div>

<!-- End of file v_mobil.php -->
<!-- Location: ./application/views/master/v_mobil.php -->
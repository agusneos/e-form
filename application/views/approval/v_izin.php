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
<table id="grid-approval_izin"
    data-options="pageSize:50, multiSort:true, remoteSort:false, rownumbers:true, singleSelect:true, 
                fit:true, fitColumns:false, toolbar:toolbar_approval_izin">
    <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'fizin_id'"             width="50"  halign="center" align="center" sortable="true">ID</th>
            <th data-options="field:'fizin_tanggal'"        width="100" halign="center" align="center" sortable="true">Tanggal</th>
            <th data-options="field:'d.karyawan_nama'"      width="150" halign="center" align="center" sortable="true">Nama Karyawan</th>
            <th data-options="field:'c.departemen_nama'"    width="100" halign="center" align="center" sortable="true">Departemen</th>
            <th data-options="field:'b.departemen_nama'"    width="100" halign="center" align="center" sortable="true">Bagian</th>
            <th data-options="field:'fizin_jenis'"          width="100" halign="center" align="center" sortable="true">Jenis Izin</th>
            <th data-options="field:'fizin_dari'"           width="150" halign="center" align="center" sortable="true">Dari</th>
            <th data-options="field:'fizin_sampai'"         width="150" halign="center" align="center" sortable="true">Sampai</th>
            <th data-options="field:'fizin_keperluan'"      width="150" halign="center" align="center" sortable="true">Keperluan</th>
            <th data-options="field:'fizin_timestamp'"      width="150" halign="center" align="center" sortable="true">Tanggal Pembuatan</th>
            <th data-options="field:'e.name'"               width="70"  halign="center" align="center" sortable="true">Disetujui</th>
            <th data-options="field:'f.name'"               width="70"  halign="center" align="center" sortable="true">Diketahui</th>
        </tr>
    </thead>
</table>

<script type="text/javascript">
    var toolbar_approval_izin = [{
        id      : 'izin_new',
        text    : 'New',
        iconCls : 'icon-new_file',
        handler : function(){approvalIzinCreate();}
    },{
        id      : 'izin_edit',
        text    : 'Edit',
        iconCls : 'icon-edit',
        handler : function(){approvalIzinUpdate();}
    },{
        id      : 'izin_delete',
        text    : 'Delete',
        iconCls : 'icon-cancel',
        handler : function(){approvalIzinHapus();}
    },{
        text    : 'Refresh',
        iconCls : 'icon-reload',
        handler : function(){$('#grid-approval_izin').datagrid('reload');}
    }];
    
    $('#grid-approval_izin').datagrid({view:scrollview,remoteFilter:true,
        url:'<?php echo site_url('approval/izin/index'); ?>?grid=true'})
        .datagrid({	
        onLoadSuccess: function(data){
            $('#izin_edit').linkbutton('disable');
            $('#izin_delete').linkbutton('disable');
        },
        onClickRow: function(index,row){
            if(row.e.name === null){
                $('#izin_edit').linkbutton('enable');
                $('#izin_delete').linkbutton('enable');
            }
            else{
                $('#izin_edit').linkbutton('disable');
                $('#izin_delete').linkbutton('disable');
            }    
            
        },
        onDblClickRow: function(index,row){
            if(row.disetujui === null){
                approvalIzinUpdate();
            }           
	},
        rowStyler: function(index,row){
            if (row.disetujui !== null && row.diketahui !== null){
                return 'background-color:#00CC66;color:#000;';
            }
            if (row.disetujui !== null){
                return 'background-color:#FF9900;color:#000;';
            }
	}
        }).datagrid('enableFilter');
    
    function approvalIzinCreate() {
        $('#dlg-approval_izin').dialog({modal: true}).dialog('open').dialog('setTitle','Tambah Data');
        $('#fm-approval_izin').form('clear');
        url = '<?php echo site_url('approval/izin/create'); ?>';
        //$('#nik').textbox({disabled: false});
    }
    
    function approvalIzinUpdate() {
        var row = $('#grid-approval_izin').datagrid('getSelected');
        if(row){
            $('#dlg-approval_izin').dialog({modal: true}).dialog('open').dialog('setTitle','Edit Data');
            $('#fm-approval_izin').form('load',row);
            url = '<?php echo site_url('approval/izin/update'); ?>/' + row.fizin_id;
            //$('#nik').textbox({disabled: true});
        }
        else
        {
             $.messager.alert('Info','Data belum dipilih !','info');
        }
    }
    
    function approvalIzinSave(){
        $('#fm-approval_izin').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success){
                    $('#dlg-approval_izin').dialog('close');
                    $('#grid-approval_izin').datagrid('reload');
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
        
    function approvalIzinHapus(){
        var row = $('#grid-approval_izin').datagrid('getSelected');
        if (row){
            $.messager.confirm('Konfirmasi','Anda yakin ingin menghapus izin no. '+row.fizin_id+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('approval/izin/delete'); ?>',{fizin_id:row.fizin_id},function(result){
                        if (result.success){
                            $('#grid-approval_izin').datagrid('reload');
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
    #fm-approval_izin{
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
<div id="dlg-approval_izin" class="easyui-dialog" style="width:600px; height:350px; padding: 10px 20px" closed="true" buttons="#dlg-buttons-approval_izin">
    <form id="fm-approval_izin" method="post" novalidate>        
        <div class="fitem">
            <label for="type">Tanggal</label>
            <input type="text" id="fizin_tanggal" name="fizin_tanggal" class="easyui-datebox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Nama Karyawan</label>
            <input type="text" id="fizin_nik" name="fizin_nik" style="width:200px;" class="easyui-combobox" required="true"
                data-options="url:'<?php echo site_url('approval/izin/getKaryawan'); ?>',
                method:'get', valueField:'karyawan_nik', textField:'karyawan_nama', panelHeight:'300'" />
        </div>
        <div class="fitem">
            <label for="type">Bagian</label>
            <input type="text" id="fizin_bagian" name="fizin_bagian" style="width:200px;" class="easyui-combobox" required="true"
                data-options="url:'<?php echo site_url('approval/izin/getDept'); ?>',
                method:'get', valueField:'id', textField:'bagian', groupField:'departemen', panelHeight:'300'" />
        </div>
        <div class="fitem">
            <label for="type">Jenis Izin</label>
            <input type="text" id="fizin_jenis" name="fizin_jenis" style="width:150px;" class="easyui-combobox" required="true"
                data-options="url:'<?php echo site_url('approval/izin/enumJenis'); ?>',
                method:'get', valueField:'data', textField:'data', panelHeight:'300'" />
        </div>
        <div class="fitem">
            <label for="type">Dari</label>
            <input type="text" id="fizin_dari" name="fizin_dari" class="easyui-datetimebox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Sampai</label>
            <input type="text" id="fizin_sampai" name="fizin_sampai" class="easyui-datetimebox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Keperluan</label>
            <input type="text" id="fizin_keperluan" name="fizin_keperluan" style="width:350px;" class="easyui-textbox" required="true"/>
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg-buttons-approval_izin">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="approvalIzinSave()">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-approval_izin').dialog('close')">Batal</a>
</div>

<!-- End of file v_izin.php -->
<!-- Location: ./application/views/approval/v_izin.php -->
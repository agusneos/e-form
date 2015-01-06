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
<table id="grid-transaksi_cuti"
    data-options="pageSize:50, multiSort:true, remoteSort:false, rownumbers:true, singleSelect:true, 
                fit:true, fitColumns:false, toolbar:toolbar_transaksi_cuti">
    <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'fcuti_id'"         width="50" halign="center" align="center" sortable="true">ID</th>
            <th data-options="field:'fcuti_tanggal'"    width="100" halign="center" align="center" sortable="true">Tanggal</th>
            <th data-options="field:'d.karyawan_nama'"        width="150" halign="center" align="center" sortable="true">Nama Karyawan</th>
            <th data-options="field:'c.departemen_nama'"    width="100" halign="center" align="center" sortable="true">Departemen</th>
            <th data-options="field:'b.departemen_nama'"    width="100" halign="center" align="center" sortable="true">Bagian</th>
            <th data-options="field:'fcuti_dari'"       width="100" halign="center" align="center" sortable="true">Dari</th>
            <th data-options="field:'fcuti_sampai'"     width="100" halign="center" align="center" sortable="true">Sampai</th>
            <th data-options="field:'fcuti_keperluan'"  width="150" halign="center" align="center" sortable="true">Keperluan</th>
            <th data-options="field:'fcuti_timestamp'"  width="150" halign="center" align="center" sortable="true">Tanggal Pembuatan</th>
            <th data-options="field:'fcuti_disetujui'"  width="70" halign="center" align="center" sortable="true">Disetujui</th>
            <th data-options="field:'fcuti_diketahui'"  width="70" halign="center" align="center" sortable="true">Diketahui</th>
        </tr>
    </thead>
</table>

<script type="text/javascript">
    var toolbar_transaksi_cuti = [{
        text:'New',
        iconCls:'icon-new_file',
        handler:function(){transaksiCutiCreate();}
    },{
        text:'Edit',
        iconCls:'icon-edit',
        handler:function(){transaksiCutiUpdate();}
    },{
        text:'Delete',
        iconCls:'icon-cancel',
        handler:function(){transaksiCutiHapus();}
    },{
        text:'Refresh',
        iconCls:'icon-reload',
        handler:function(){$('#grid-transaksi_cuti').datagrid('reload');}
    }];
    
    $('#grid-transaksi_cuti').datagrid({view:scrollview,remoteFilter:true,
        url:'<?php echo site_url('transaksi/cuti/index'); ?>?grid=true'})
        .datagrid('enableFilter');
    
    function transaksiCutiCreate() {
        $('#dlg-transaksi_cuti').dialog({modal: true}).dialog('open').dialog('setTitle','Tambah Data');
        $('#fm-transaksi_cuti').form('clear');
        url = '<?php echo site_url('transaksi/cuti/create'); ?>';
        //$('#nik').textbox({disabled: false});
    }
    
    function transaksiCutiUpdate() {
        var row = $('#grid-transaksi_cuti').datagrid('getSelected');
        if(row){
            $('#dlg-transaksi_cuti').dialog({modal: true}).dialog('open').dialog('setTitle','Edit Data');
            $('#fm-transaksi_cuti').form('load',row);
            url = '<?php echo site_url('transaksi/cuti/update'); ?>/' + row.fcuti_id;
            //$('#nik').textbox({disabled: true});
        }
        else
        {
             $.messager.alert('Info','Data belum dipilih !','info');
        }
    }
    
    function transaksiCutiSave(){
        $('#fm-transaksi_cuti').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success){
                    $('#dlg-transaksi_cuti').dialog('close');
                    $('#grid-transaksi_cuti').datagrid('reload');
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
        
    function transaksiCutiHapus(){
        var row = $('#grid-transaksi_cuti').datagrid('getSelected');
        if (row){
            $.messager.confirm('Konfirmasi','Anda yakin ingin menghapus cuti no. '+row.fcuti_id+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('transaksi/cuti/delete'); ?>',{fcuti_id:row.fcuti_id},function(result){
                        if (result.success){
                            $('#grid-transaksi_cuti').datagrid('reload');
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
    #fm-transaksi_cuti{
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
<div id="dlg-transaksi_cuti" class="easyui-dialog" style="width:600px; height:350px; padding: 10px 20px" closed="true" buttons="#dlg-buttons-transaksi_cuti">
    <form id="fm-transaksi_cuti" method="post" novalidate>        
        <div class="fitem">
            <label for="type">Tanggal</label>
            <input type="text" id="fcuti_tanggal" name="fcuti_tanggal" class="easyui-datebox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Nama Karyawan</label>
            <input type="text" id="fcuti_nik" name="fcuti_nik" style="width:200px;" class="easyui-combobox" required="true"
                data-options="url:'<?php echo site_url('transaksi/cuti/getKaryawan'); ?>',
                method:'get', valueField:'karyawan_nik', textField:'karyawan_nama', panelHeight:'300'" />
        </div>
        <div class="fitem">
            <label for="type">Bagian</label>
            <input type="text" id="fcuti_bagian" name="fcuti_bagian" style="width:200px;" class="easyui-combobox" required="true"
                data-options="url:'<?php echo site_url('transaksi/cuti/getDept'); ?>',
                method:'get', valueField:'id', textField:'bagian', groupField:'departemen', panelHeight:'300'" />
        </div>
        <div class="fitem">
            <label for="type">Dari</label>
            <input type="text" id="fcuti_dari" name="fcuti_dari" class="easyui-datebox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Sampai</label>
            <input type="text" id="fcuti_sampai" name="fcuti_sampai" class="easyui-datebox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Keperluan</label>
            <input type="text" id="fcuti_keperluan" name="fcuti_keperluan" style="width:350px;" class="easyui-textbox" required="true"/>
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg-buttons-transaksi_cuti">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="transaksiCutiSave()">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-transaksi_cuti').dialog('close')">Batal</a>
</div>

<!-- End of file v_cuti.php -->
<!-- Location: ./application/views/transaksi/v_cuti.php -->
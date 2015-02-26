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
<div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'center',border:false">
        <table id="grid-transaksi_bpb"
            data-options="pageSize:50, multiSort:true, remoteSort:true, rownumbers:true, singleSelect:true, 
                        fit:true, fitColumns:true, toolbar:toolbar_transaksi_bpb">
            <thead>
                <tr>
                    <th data-options="field:'ck',checkbox:true" ></th>
                    <th data-options="field:'fbpb_id'"              width="50"  halign="center" align="center" sortable="true">ID</th>
                    <th data-options="field:'fbpb_tanggal'"         width="100" halign="center" align="center" sortable="true">Tanggal</th>
                    <th data-options="field:'d.karyawan_nama'"      width="150" halign="center" align="center" sortable="true">Nama Karyawan</th>
                    <th data-options="field:'c.departemen_nama'"    width="100" halign="center" align="center" sortable="true">Departemen</th>
                    <th data-options="field:'b.departemen_nama'"    width="100" halign="center" align="center" sortable="true">Bagian</th>
                    <th data-options="field:'fbpb_timestamp'"       width="150" halign="center" align="center" sortable="true">Tanggal Pembuatan</th>
                    <th data-options="field:'fbpb_disetujui'"       width="70"  halign="center" align="center" sortable="true">Disetujui</th>
                    <th data-options="field:'fbpb_diketahui'"       width="70"  halign="center" align="center" sortable="true">Diketahui</th>
                </tr>
            </thead>
        </table>
    </div>
    
    <div data-options="region:'south',split:true,border:true" style="height:200px">
        <table id="grid-transaksi_bpb_detail"
            data-options="pageSize:50, multiSort:true, remoteSort:true, rownumbers:true, singleSelect:true, 
                        fit:true, fitColumns:true, toolbar:toolbar_transaksi_bpb_detail">
            <thead>
                <tr>
                    <th data-options="field:'ck',checkbox:true" ></th>
                    <th data-options="field:'fbpb_detail_barang'"       width="300" halign="center" align="center" sortable="true">Nama Barang</th>
                    <th data-options="field:'fbpb_detail_qty'"          width="70" halign="center" align="center" sortable="true">Jumlah</th>
                    <th data-options="field:'fbpb_detail_digunakan'"    width="100" halign="center" align="center" sortable="true">Tgl Digunakan</th>
                    <th data-options="field:'fbpb_detail_stock'"        width="50" halign="center" align="center" sortable="true">Stock</th>
                    <th data-options="field:'fbpb_detail_pemakaian'"    width="70" halign="center" align="center" sortable="true">Pemakaian</th>
                    <th data-options="field:'fbpb_detail_ket'"          width="300"  halign="center" align="center" sortable="true">Keterangan</th>
                    </tr>
            </thead>
        </table>
    </div>
</div>


<script type="text/javascript">
    
    var toolbar_transaksi_bpb = [{
        id      : 'bpb_new',
        text    : 'New',
        iconCls : 'icon-new_file',
        handler : function(){transaksiBpbCreate();}
    },{
        id      : 'bpb_edit',
        text    : 'Edit',
        iconCls : 'icon-edit',
        handler : function(){transaksiBpbUpdate();}
    },{
        id      : 'bpb_delete',
        text    : 'Delete',
        iconCls : 'icon-cancel',
        handler : function(){transaksiBpbHapus();}
    },{
        id      : 'bpb_refresh',
        text    : 'Refresh',
        iconCls : 'icon-reload',
        handler : function(){headerRefresh();}
    }];
    
    $('#grid-transaksi_bpb').datagrid({view:scrollview,remoteFilter:true,
        url:'<?php echo site_url('transaksi/bpb/index'); ?>?grid=true'})        
        .datagrid({
	onLoadSuccess: function(data){
            alert();
        },
        onClickRow: function(index,row){
            $('#bpb_edit').linkbutton('enable');
            $('#bpb_delete').linkbutton('enable');
            $('#bpb_detail_new').linkbutton('enable');
            $('#bpb_detail_refresh').linkbutton('enable');
            nilai = row.fbpb_id;
            $('#grid-transaksi_bpb_detail').datagrid('load','<?php echo site_url('transaksi/bpb/index_detail'); ?>?grid=true&nilai='+nilai);
	},        
        onDblClickRow: function(index,row){
            transaksiBpbUpdate();
	},
        onSortColumn: function(sort,order){
            headerRefresh();
        }
        }).datagrid('enableFilter');
    
    function headerRefresh() {
        $('#bpb_edit').linkbutton('disable');
        $('#bpb_delete').linkbutton('disable');
        $('#bpb_detail_new').linkbutton('disable');
        $('#bpb_detail_edit').linkbutton('disable');
        $('#bpb_detail_delete').linkbutton('disable');
        $('#bpb_detail_refresh').linkbutton('disable');
            
        $('#grid-transaksi_bpb').datagrid('reload');
        nilai=null;
        $('#grid-transaksi_bpb_detail').datagrid('load','<?php echo site_url('transaksi/bpb/index_detail'); ?>?grid=true&nilai='+nilai);
        $('#grid-transaksi_bpb_detail').datagrid('reload');
        
    }
    
    function transaksiBpbCreate() {
        $('#dlg-transaksi_bpb').dialog({modal: true}).dialog('open').dialog('setTitle','Tambah Data');
        $('#fm-transaksi_bpb').form('clear');
        url = '<?php echo site_url('transaksi/bpb/create'); ?>';
        //$('#nik').textbox({disabled: false});
    }
    
    function transaksiBpbUpdate() {
        var row = $('#grid-transaksi_bpb').datagrid('getSelected');
        if(row){
            $('#dlg-transaksi_bpb').dialog({modal: true}).dialog('open').dialog('setTitle','Edit Data');
            $('#fm-transaksi_bpb').form('load',row);
            url = '<?php echo site_url('transaksi/bpb/update'); ?>/' + row.fbpb_id;
            //$('#nik').textbox({disabled: true});
        }
        else
        {
             $.messager.alert('Info','Data belum dipilih !','info');
        }
    }
    
    function transaksiBpbSave(){
        $('#fm-transaksi_bpb').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success){
                    $('#dlg-transaksi_bpb').dialog('close');
                    headerRefresh();
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
        
    function transaksiBpbHapus(){
        var row = $('#grid-transaksi_bpb').datagrid('getSelected');
        if (row){
            $.messager.confirm('Konfirmasi','Anda yakin ingin menghapus ID no. '+row.fbpb_id+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('transaksi/bpb/delete'); ?>',{fbpb_id:row.fbpb_id},function(result){
                        if (result.success){
                            headerRefresh();
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
    
    /////////////////////////////////////////////DETAIL////////////////////////////////////////////////
    var toolbar_transaksi_bpb_detail = [{
        id      : 'bpb_detail_new',
        text    : 'New',
        iconCls : 'icon-new_file',
        handler : function(){transaksiBpbDetailCreate();}
    },{
        id      : 'bpb_detail_edit',
        text    : 'Edit',
        iconCls : 'icon-edit',
        handler : function(){transaksiBpbDetailUpdate();}
    },{
        id      : 'bpb_detail_delete',
        text    : 'Delete',
        iconCls : 'icon-cancel',
        handler : function(){transaksiBpbDetailHapus();}
    },{
        id      : 'bpb_detail_refresh',
        text    : 'Refresh',
        iconCls : 'icon-reload',
        handler : function(){$('#grid-transaksi_bpb_detail').datagrid('reload');}
    }];
    
    $('#grid-transaksi_bpb_detail').datagrid({view:scrollview,remoteFilter:true})
        .datagrid({	
        onLoadSuccess: function(data){
            $('#bpb_detail_edit').linkbutton('disable');
            $('#bpb_detail_delete').linkbutton('disable');
        },
        onClickRow: function(index,row){
            $('#bpb_detail_edit').linkbutton('enable');
            $('#bpb_detail_delete').linkbutton('enable');
        },
        onDblClickRow: function(index,row){
            transaksiBpbDetailUpdate();
	}
        }).datagrid('enableFilter');
    
    function transaksiBpbDetailCreate() {
        var row = $('#grid-transaksi_bpb').datagrid('getSelected');
        if(row){
            $('#dlg-transaksi_bpb_detail').dialog({modal: true}).dialog('open').dialog('setTitle','Tambah Data');
            $('#fm-transaksi_bpb_detail').form('clear');
            url = '<?php echo site_url('transaksi/bpb/detailCreate'); ?>';
            $('#fbpb_detail_header').numberbox('setValue',nilai).numberbox({readonly: true});
        }
        else
        {
             $.messager.alert('Info','Header belum dipilih !','info');
        }
        
    }
    
    function transaksiBpbDetailUpdate() {
        var row = $('#grid-transaksi_bpb_detail').datagrid('getSelected');
        if(row){
            $('#dlg-transaksi_bpb_detail').dialog({modal: true}).dialog('open').dialog('setTitle','Edit Data');
            $('#fm-transaksi_bpb_detail').form('load',row);
            url = '<?php echo site_url('transaksi/bpb/detailUpdate'); ?>/' + row.fbpb_detail_id;
            $('#fbpb_detail_header').numberbox({readonly: true});
        }
        else
        {
             $.messager.alert('Info','Data belum dipilih !','info');
        }
    }
    
    function transaksiBpbDetailSave(){
        $('#fm-transaksi_bpb_detail').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success){
                    $('#dlg-transaksi_bpb_detail').dialog('close');
                    $('#grid-transaksi_bpb_detail').datagrid('reload');
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
        
    function transaksiBpbDetailHapus(){
        var row = $('#grid-transaksi_bpb_detail').datagrid('getSelected');
        if (row){
            $.messager.confirm('Konfirmasi','Anda yakin ingin menghapus ID no. '+row.fbpb_detail_id+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('transaksi/bpb/detailDelete'); ?>',{fbpb_detail_id:row.fbpb_detail_id},function(result){
                        if (result.success){
                            $('#grid-transaksi_bpb_detail').datagrid('reload');
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
    #fm-transaksi_bpb{
        margin:0;
        padding:10px 30px;
    }
    #fm-transaksi_bpb_detail{
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
        width:110px;
    }
    .fitem input{
        display:inline-block;
        width:150px;
    }
</style>

<!-- HEADER -->
<div id="dlg-transaksi_bpb" class="easyui-dialog" style="width:600px; height:350px; padding: 10px 20px" closed="true" buttons="#dlg-buttons-transaksi_bpb">
    <form id="fm-transaksi_bpb" method="post" novalidate>        
        <div class="fitem">
            <label for="type">Tanggal</label>
            <input type="text" id="fbpb_tanggal" name="fbpb_tanggal" class="easyui-datebox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Nama Karyawan</label>
            <input type="text" id="fbpb_nik" name="fbpb_nik" style="width:200px;" class="easyui-combobox" required="true"
                data-options="url:'<?php echo site_url('transaksi/bpb/getKaryawan'); ?>',
                method:'get', valueField:'karyawan_nik', textField:'karyawan_nama', panelHeight:'300'" />
        </div>
        <div class="fitem">
            <label for="type">Bagian</label>
            <input type="text" id="fbpb_bagian" name="fbpb_bagian" style="width:200px;" class="easyui-combobox" required="true"
                data-options="url:'<?php echo site_url('transaksi/bpb/getDept'); ?>',
                method:'get', valueField:'id', textField:'bagian', groupField:'departemen', panelHeight:'300'" />
        </div>        
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg-buttons-transaksi_bpb">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="transaksiBpbSave()">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-transaksi_bpb').dialog('close')">Batal</a>
</div>

<!-- ----------------DETAIL---------------------- -->
<div id="dlg-transaksi_bpb_detail" class="easyui-dialog" style="width:600px; height:350px; padding: 10px 20px" closed="true" buttons="#dlg-buttons-transaksi_bpb_detail">
    <form id="fm-transaksi_bpb_detail" method="post" novalidate>        
        <div class="fitem">
            <label for="type">Header ID</label>
            <input type="text" id="fbpb_detail_header" name="fbpb_detail_header" style="width:50px;" class="easyui-numberbox" required="true" />
        </div>
        <div class="fitem">
            <label for="type">Nama Barang</label>
            <input type="text" id="fbpb_detail_barang" name="fbpb_detail_barang" style="width:300px;" class="easyui-textbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Jumlah</label>
            <input type="text" id="fbpb_detail_qty" name="fbpb_detail_qty" style="width:100px;" class="easyui-numberbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Tgl. Digunakan</label>
            <input type="text" id="fbpb_detail_digunakan" name="fbpb_detail_digunakan" class="easyui-datebox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Stock Sekarang</label>
            <input type="text" id="fbpb_detail_stock" name="fbpb_detail_stock" style="width:100px;" class="easyui-numberbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Pemakaian Perbulan</label>
            <input type="text" id="fbpb_detail_pemakaian" name="fbpb_detail_pemakaian" style="width:100px;" class="easyui-numberbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Keterangan</label>
            <input type="text" id="fbpb_detail_ket" name="fbpb_detail_ket" style="width:300px;" class="easyui-textbox" required="true"/>
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg-buttons-transaksi_bpb_detail">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="transaksiBpbDetailSave()">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-transaksi_bpb_detail').dialog('close')">Batal</a>
</div>

<!-- End of file v_bpb.php -->
<!-- Location: ./application/views/transaksi/v_bpb.php -->
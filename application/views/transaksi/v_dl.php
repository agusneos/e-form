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
<table id="grid-transaksi_dl"
    data-options="pageSize:50, multiSort:true, remoteSort:true, rownumbers:true, singleSelect:true, 
                fit:true, fitColumns:false, toolbar:toolbar_transaksi_dl">
    <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'fdl_id'"               width="50"  halign="center" align="center" sortable="true" >ID</th>
            <th data-options="field:'fdl_tanggal'"          width="100" halign="center" align="center" sortable="true" >Tanggal</th>
            <th data-options="field:'d.karyawan_nama'"      width="150" halign="center" align="center" sortable="true" >Nama Karyawan</th>
            <th data-options="field:'c.departemen_nama'"    width="100" halign="center" align="center" sortable="true" >Departemen</th>
            <th data-options="field:'b.departemen_nama'"    width="100" halign="center" align="center" sortable="true" >Bagian</th>
            <th data-options="field:'fdl_dari'"             width="100" halign="center" align="center" sortable="true" >Dari</th>
            <th data-options="field:'fdl_sampai'"           width="100" halign="center" align="center" sortable="true" >Sampai</th>
            <th data-options="field:'fdl_jam'"              width="100" halign="center" align="center" sortable="true" >Jam</th>
            <th data-options="field:'fdl_tujuan'"           width="200" halign="center" align="center" sortable="true" >Tujuan</th>
            <th data-options="field:'fdl_bersama'"          width="200" halign="center" align="center" sortable="true" >Bersama</th>
            <th data-options="field:'fdl_keperluan'"        width="200" halign="center" align="center" sortable="true" >Keperluan</th>
            <th data-options="field:'fdl_keterangan'"       width="150" halign="center" align="center" sortable="true" >Keterangan</th>
            <th data-options="field:'e.name'"               width="70"  halign="center" align="center" sortable="true" >Disetujui</th>
            <th data-options="field:'f.name'"               width="70"  halign="center" align="center" sortable="true" >Diketahui</th>
            <th data-options="field:'g.name'"               width="70"  halign="center" align="center" sortable="true" >Ditolak</th>
            <th data-options="field:'h.name'"               width="70"  halign="center" align="center" sortable="true" >Dibuat</th>
            <th data-options="field:'fdl_timestamp'"        width="140" halign="center" align="center" sortable="true" >Tanggal Pembuatan</th>
        </tr>
    </thead>
</table>

<script type="text/javascript">
    var toolbar_transaksi_dl = [{
        id      : 'dl_new',
        text    : 'New',
        iconCls : 'icon-new_file',
        handler : function(){transaksiDlCreate();}
    },{
        id      : 'dl_edit',
        text    : 'Edit',
        iconCls : 'icon-edit',
        handler : function(){transaksiDlUpdate();}
    },{
        id      : 'dl_delete',
        text    : 'Delete',
        iconCls : 'icon-cancel',
        handler : function(){transaksiDlHapus();}
    },{
        text    : 'Refresh',
        iconCls : 'icon-reload',
        handler : function(){transaksiDlRefresh();}
    },{
        id      : 'dl_disetujui',
        handler : function(){transaksiDlDisetujui();}
    },{
        id      : 'dl_ditolak',
        handler : function(){transaksiDlDitolak();}
    },{
        id      : 'dl_diketahui',
        handler : function(){transaksiDlDiketahui();}
    }];
    
    var transaksiDlUser        = <?php echo $this->session->userdata('id');?>;
    var transaksiDlSetuju      = <?php echo $this->session->userdata('user_disetujui');?>;
    var transaksiDlTahu        = <?php echo $this->session->userdata('user_diketahui');?>;
    var transaksiDlDataSetuju  = null;
    var transaksiDlDataTahu    = null;
    
    $('#grid-transaksi_dl').datagrid({view:scrollview,remoteFilter:true,
        url:'<?php echo site_url('transaksi/dl/index'); ?>?grid=true'})
        .datagrid({	
        onLoadSuccess: function(data){
            $('#dl_edit').linkbutton('disable');
            $('#dl_delete').linkbutton('disable');
            $('#dl_disetujui').linkbutton({
                text    : '',
                iconCls : '',
                disabled: true
            });
            $('#dl_diketahui').linkbutton({
                text    : '',
                iconCls : '',
                disabled: true
            });
            $('#dl_ditolak').linkbutton({
                text    : '',
                iconCls : '',
                disabled: true
            });
        },
        onClickRow: function(index,row){
            if(row['g.name'] !== null){
                $('#dl_edit').linkbutton('disable');
                $('#dl_delete').linkbutton('disable');
                $('#dl_disetujui').linkbutton({
                    text    : '',
                    iconCls : '',
                    disabled: true
                });
                $('#dl_diketahui').linkbutton({
                    text    : '',
                    iconCls : '',
                    disabled: true
                });
                $('#dl_ditolak').linkbutton({
                    text    : '',
                    iconCls : '',
                    disabled: true
                });
            }
            else {
                if(row['e.name'] === null && row['f.name'] === null){                
                    $('#dl_edit').linkbutton('enable');
                    $('#dl_delete').linkbutton('enable');
                    $('#dl_diketahui').linkbutton({
                        text    : '',
                        iconCls : '',
                        disabled: true
                    });
                    if(transaksiDlSetuju){
                        $('#dl_disetujui').linkbutton({
                            text    : 'Disetujui',
                            iconCls : 'icon-approved',
                            disabled: false
                        });
                        transaksiDlDataSetuju = 1;
                        $('#dl_ditolak').linkbutton({
                            text    : 'Ditolak',
                            iconCls : 'icon-approved_denied',
                            disabled: false
                        });
                    }
                    else{
                        $('#dl_disetujui').linkbutton({
                            text    : '',
                            iconCls : '',
                            disabled: true
                        });
                        $('#dl_ditolak').linkbutton({
                            text    : '',
                            iconCls : '',
                            disabled: true
                        });
                    }
                }
                if(row['e.name'] !== null && row['f.name'] === null){
                    $('#dl_edit').linkbutton('disable');
                    $('#dl_delete').linkbutton('disable');
                    $('#dl_ditolak').linkbutton({
                        text    : '',
                        iconCls : '',
                        disabled: true
                    });
                    if(transaksiDlSetuju && transaksiDlTahu){
                        if(row.fdl_disetujui == transaksiDlUser){
                            $('#dl_disetujui').linkbutton({
                                text    : 'Batal Disetujui',
                                iconCls : 'icon-approved_denied',
                                disabled: false
                            });
                            transaksiDlDataSetuju = 0;
                            $('#dl_diketahui').linkbutton({
                                text    : '',
                                iconCls : '',
                                disabled: true
                            });
                        }
                        else{
                            $('#dl_disetujui').linkbutton({
                                text    : '',
                                iconCls : '',
                                disabled: true
                            });
                            $('#dl_diketahui').linkbutton({
                                text    : 'Diketahui',
                                iconCls : 'icon-approved',
                                disabled: false
                            });
                            transaksiDlDataTahu = 1;
                        }
                    }
                    else if(transaksiDlSetuju){
                        $('#dl_diketahui').linkbutton({
                            text    : '',
                            iconCls : '',
                            disabled: true
                        });
                        if(row.fdl_disetujui == transaksiDlUser){
                            $('#dl_disetujui').linkbutton({
                                text    : 'Batal Disetujui',
                                iconCls : 'icon-approved_denied',
                                disabled: false
                            });
                            transaksiDlDataSetuju = 0;
                        }
                        else{
                            $('#dl_disetujui').linkbutton({
                                text    : '',
                                iconCls : '',
                                disabled: true
                            });
                        }
                    }
                    else if(transaksiDlTahu){
                        $('#dl_disetujui').linkbutton({
                            text    : '',
                            iconCls : '',
                            disabled: true
                        });
                        if(row.fdl_disetujui == transaksiDlUser){
                            $('#dl_diketahui').linkbutton({
                                text    : '',
                                iconCls : '',
                                disabled: true
                            });
                        }
                        else{
                           $('#dl_diketahui').linkbutton({
                                text    : 'Diketahui',
                                iconCls : 'icon-approved',
                                disabled: false
                            });
                            transaksiDlDataTahu = 1;
                        }
                    }
                    else{
                        $('#dl_diketahui').linkbutton({
                            text    : '',
                            iconCls : '',
                            disabled: true
                        });
                        $('#dl_disetujui').linkbutton({
                            text    : '',
                            iconCls : '',
                            disabled: true
                        });
                    }
                }
                if(row['e.name'] !== null && row['f.name'] !== null){
                    $('#dl_edit').linkbutton('disable');
                    $('#dl_delete').linkbutton('disable');
                    $('#dl_disetujui').linkbutton({
                        text    : '',
                        iconCls : '',
                        disabled: true
                    });
                    $('#dl_ditolak').linkbutton({
                            text    : '',
                            iconCls : '',
                            disabled: true
                        });
                    if(transaksiDlTahu){
                        if(row.fdl_diketahui == transaksiDlUser){
                            $('#dl_diketahui').linkbutton({
                                text    : 'Batal Diketahui',
                                iconCls : 'icon-approved_denied',
                                disabled: false
                            });
                            transaksiDlDataTahu = 0;
                        }
                        else{
                            $('#dl_diketahui').linkbutton({
                                text    : '',
                                iconCls : '',
                                disabled: true
                            });
                        }
                    }
                    else{
                        $('#dl_diketahui').linkbutton({
                            text    : '',
                            iconCls : '',
                            disabled: true
                        });
                    }
                }
            }
        },
        onDblClickRow: function(index,row){
            if(row['e.name'] === null && row['g.name'] === null){
                transaksiDlUpdate();
            }
	},
        rowStyler: function(index,row){
            if (row['e.name'] !== null && row['f.name'] !== null){
                return 'background-color:#90EE90;color:#000;';
            }
            if (row['e.name'] !== null){
                return 'background-color:#87CEFA;color:#000;';
            }
            if (row['g.name'] !== null){
                return 'background-color:#FFB6C1;color:#000;';
            }
	}
        }).datagrid('enableFilter', [{
            field:'e.name',
            type:'textbox',
            op:['contains','is']
        }, {
            field:'f.name',
            type:'textbox',
            op:['contains','is']
        }, {
            field:'g.name',
            type:'textbox',
            op:['contains','is']
        }, {
            field:'h.name',
            type:'textbox',
            op:['contains','is']
        }
        ]);
    
    function transaksiDlRefresh() {
        $('#dl_edit').linkbutton('disable');
        $('#dl_delete').linkbutton('disable');
        $('#dl_disetujui').linkbutton({
            text    : '',
            iconCls : '',
            disabled: true
        });
        $('#dl_diketahui').linkbutton({
            text    : '',
            iconCls : '',
            disabled: true
        });
        $('#dl_ditolak').linkbutton({
            text    : '',
            iconCls : '',
            disabled: true
        });
        $('#grid-transaksi_dl').datagrid('reload');
    }
    
    function transaksiDlDisetujui() {
        var row = $('#grid-transaksi_dl').datagrid('getSelected');
        if (row){
            if(transaksiDlDataSetuju === 1){
                $.messager.confirm('Konfirmasi','Anda yakin ingin Menyetujui Dinas Luar no. '+row.fdl_id+' ?',function(r){
                    if (r){
                        $.post('<?php echo site_url('transaksi/dl/disetujui'); ?>',{fdl_id:row.fdl_id,fdl_disetujui:transaksiDlUser},function(result){
                            if (result.success){
                                transaksiDlRefresh();
                                $.messager.show({
                                    title: 'Info',
                                    msg: 'Update Data Berhasil'
                                });
                            } else {
                                $.messager.show({
                                    title: 'Error',
                                    msg: 'Update Data Gagal'
                                });
                            }
                        },'json');
                    }
                });
            }
            else if(transaksiDlDataSetuju === 0){
                $.messager.confirm('Konfirmasi','Anda yakin ingin Batal Menyetujui Dinas Luar no. '+row.fdl_id+' ?',function(r){
                    if (r){
                        $.post('<?php echo site_url('transaksi/dl/disetujui'); ?>',{fdl_id:row.fdl_id,fdl_disetujui:0},function(result){
                            if (result.success){
                                transaksiDlRefresh();
                                $.messager.show({
                                    title: 'Info',
                                    msg: 'Update Data Berhasil'
                                });
                            } else {
                                $.messager.show({
                                    title: 'Error',
                                    msg: 'Update Data Gagal'
                                });
                            }
                        },'json');
                    }
                });
            }
        }
        else
        {
             $.messager.alert('Info','Data belum dipilih !','info');
        }
    }
    
    function transaksiDlDiketahui() {
        var row = $('#grid-transaksi_dl').datagrid('getSelected');
        if (row){
            if(transaksiDlDataTahu === 1){
                $.messager.confirm('Konfirmasi','Anda yakin ingin Mengetahui Dinas Luar no. '+row.fdl_id+' ?',function(r){
                    if (r){
                        $.post('<?php echo site_url('transaksi/dl/diketahui'); ?>',{fdl_id:row.fdl_id,fdl_diketahui:transaksiDlUser},function(result){
                            if (result.success){
                                transaksiDlRefresh();
                                $.messager.show({
                                    title: 'Info',
                                    msg: 'Update Data Berhasil'
                                });
                            } else {
                                $.messager.show({
                                    title: 'Error',
                                    msg: 'Update Data Gagal'
                                });
                            }
                        },'json');
                    }
                });
            }
            else if(transaksiDlDataTahu === 0){
                $.messager.confirm('Konfirmasi','Anda yakin ingin Batal Mengetahui Dinas Luar no. '+row.fdl_id+' ?',function(r){
                    if (r){
                        $.post('<?php echo site_url('transaksi/dl/diketahui'); ?>',{fdl_id:row.fdl_id,fdl_diketahui:0},function(result){
                            if (result.success){
                                transaksiDlRefresh();
                                $.messager.show({
                                    title: 'Info',
                                    msg: 'Update Data Berhasil'
                                });
                            } else {
                                $.messager.show({
                                    title: 'Error',
                                    msg: 'Update Data Gagal'
                                });
                            }
                        },'json');
                    }
                });
            }
        }
        else
        {
             $.messager.alert('Info','Data belum dipilih !','info');
        }
    }
    
    function transaksiDlDitolak(){
        var row = $('#grid-transaksi_dl').datagrid('getSelected');
        if (row){
            $.messager.prompt('Konfirmasi','Mengapa anda ingin Menolak Dinas Luar no. '+row.fdl_id+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('transaksi/dl/ditolak'); ?>',{fdl_id:row.fdl_id,fdl_ditolak:transaksiDlUser,fdl_keterangan:r},function(result){
                        if (result.success){
                            transaksiDlRefresh();
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
    
    function transaksiDlCreate() {
        $('#dlg-transaksi_dl').dialog({modal: true}).dialog('open').dialog('setTitle','Tambah Data');
        $('#fm-transaksi_dl').form('clear');
        url = '<?php echo site_url('transaksi/dl/create'); ?>';
    }
    
    function transaksiDlUpdate() {
        var row = $('#grid-transaksi_dl').datagrid('getSelected');
        if(row){            
            $('#dlg-transaksi_dl').dialog({modal: true}).dialog('open').dialog('setTitle','Edit Data');
            $('#fm-transaksi_dl').form('load',row);
            url = '<?php echo site_url('transaksi/dl/update'); ?>/' + row.fdl_id;
        }
        else
        {
             $.messager.alert('Info','Data belum dipilih !','info');
        }
    }
    
    function transaksiDlSave(){
        $('#fm-transaksi_dl').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success){
                    $('#dlg-transaksi_dl').dialog('close');
                    transaksiDlRefresh();
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
        
    function transaksiDlHapus(){
        var row = $('#grid-transaksi_dl').datagrid('getSelected');
        if (row){
            $.messager.confirm('Konfirmasi','Anda yakin ingin menghapus ID no. '+row.fdl_id+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('transaksi/dl/delete'); ?>',{fdl_id:row.fdl_id},function(result){
                        if (result.success){
                            transaksiDlRefresh();
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
    #fm-transaksi_dl{
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
<div id="dlg-transaksi_dl" class="easyui-dialog" style="width:600px; height:400px; padding: 10px 20px" closed="true" buttons="#dlg-buttons-transaksi_dl">
    <form id="fm-transaksi_dl" method="post" novalidate>        
        <div class="fitem">
            <label for="type">Tanggal</label>
            <input type="text" id="fdl_tanggal" name="fdl_tanggal" style="width:100px;" class="easyui-datebox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Nama Karyawan</label>
            <input type="text" id="fdl_nik" name="fdl_nik" style="width:200px;" class="easyui-combogrid" required="true"
                data-options="
                    panelWidth: 500,
                    idField: 'karyawan_nik',
                    textField: 'karyawan_nama',
                    url:'<?php echo site_url('transaksi/dl/getKaryawan'); ?>',
                    mode:'remote',
                    fitColumns: true,
                    columns: [[
                        {field:'karyawan_nik',title:'NIK',width:50,align:'center'},
                        {field:'karyawan_nama',title:'Nama',width:120,halign:'center'},
                        {field:'c.departemen_nama',title:'Departemen',width:80,align:'center'},
                        {field:'b.departemen_nama',title:'Bagian',width:80,align:'center'}
                    ]],
                    onSelect: function (rowIndex, rowData) {
                        var g = $('#fdl_nik').combogrid('grid');
                        var r = g.datagrid('getSelected');
                        $('#fdl_bagian').combobox('setValue', r.karyawan_bagian);
                    }
            " />
        </div>
        <div class="fitem">
            <label for="type">Bagian</label>
            <input type="text" id="fdl_bagian" name="fdl_bagian" style="width:200px;" class="easyui-combobox"
                data-options="url:'<?php echo site_url('transaksi/dl/getDept'); ?>',
                method:'get', valueField:'id', textField:'bagian', groupField:'departemen', panelHeight:'300', readonly: true" />
        </div>
        <div class="fitem">
            <label for="type">Dari Tgl.</label>
            <input type="text" id="fdl_dari" name="fdl_dari" style="width:100px;" class="easyui-datebox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Sampai Tgl.</label>
            <input type="text" id="fdl_sampai" name="fdl_sampai" style="width:100px;" class="easyui-datebox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">JAM</label>
            <input type="text" id="fdl_jam" name="fdl_jam" style="width:100px;" class="easyui-timespinner" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Tujuan</label>
            <input type="text" id="fdl_tujuan" name="fdl_tujuan" style="width:350px;" class="easyui-textbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Bersama</label>
            <input type="text" id="fdl_bersama" name="fdl_bersama" style="width:350px;" class="easyui-textbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Keperluan</label>
            <input type="text" id="fdl_keperluan" name="fdl_keperluan" style="width:350px;" class="easyui-textbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Keterangan</label>
            <input type="text" id="fdl_keterangan" name="fdl_keterangan" style="width:350px;" class="easyui-textbox"/>
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg-buttons-transaksi_dl">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="transaksiDlSave()">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-transaksi_dl').dialog('close')">Batal</a>
</div>

<!-- End of file v_dl.php -->
<!-- Location: ./application/views/transaksi/v_dl.php -->
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
<table id="grid-transaksi_terlambat"
    data-options="pageSize:50, multiSort:true, remoteSort:true, rownumbers:true, singleSelect:true, 
                fit:true, fitColumns:false, toolbar:toolbar_transaksi_terlambat">
    <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'fterlambat_id'"            width="50"  halign="center" align="center" sortable="true" >ID</th>
            <th data-options="field:'fterlambat_tanggal'"       width="100" halign="center" align="center" sortable="true" >Tanggal</th>
            <th data-options="field:'d.karyawan_nama'"          width="150" halign="center" align="left"   sortable="true" >Nama Karyawan</th>
            <th data-options="field:'c.departemen_nama'"        width="100" halign="center" align="center" sortable="true" >Departemen</th>
            <th data-options="field:'b.departemen_nama'"        width="100" halign="center" align="center" sortable="true" >Bagian</th>
            <th data-options="field:'fterlambat_shift'"         width="50"  halign="center" align="center" sortable="true" >Shift</th>
            <th data-options="field:'fterlambat_waktu'"         width="100" halign="center" align="center" sortable="true" >Waktu</th>
            <th data-options="field:'fterlambat_alasan'"        width="200" halign="center" align="left"   sortable="true" >Alasan</th>
            <th data-options="field:'fterlambat_keterangan'"    width="150" halign="center" align="left"   sortable="true" >Keterangan</th>
            <th data-options="field:'e.name'"                   width="70"  halign="center" align="center" sortable="true" >Disetujui</th>
            <th data-options="field:'f.name'"                   width="70"  halign="center" align="center" sortable="true" >Diketahui</th>
            <th data-options="field:'g.name'"                   width="70"  halign="center" align="center" sortable="true" >Ditolak</th>
            <th data-options="field:'h.name'"                   width="70"  halign="center" align="center" sortable="true" >Dibuat</th>
            <th data-options="field:'fterlambat_timestamp'"     width="140" halign="center" align="center" sortable="true" >Tanggal Pembuatan</th>
        </tr>
    </thead>
</table>

<script type="text/javascript">
    var toolbar_transaksi_terlambat = [{
        id      : 'terlambat_new',
        text    : 'New',
        iconCls : 'icon-new_file',
        handler : function(){transaksiTerlambatCreate();}
    },{
        id      : 'terlambat_edit',
        text    : 'Edit',
        iconCls : 'icon-edit',
        handler : function(){transaksiTerlambatUpdate();}
    },{
        id      : 'terlambat_delete',
        text    : 'Delete',
        iconCls : 'icon-cancel',
        handler : function(){transaksiTerlambatHapus();}
    },{
        text    : 'Refresh',
        iconCls : 'icon-reload',
        handler : function(){transaksiTerlambatRefresh();}
    },{
        id      : 'terlambat_disetujui',
        handler : function(){transaksiTerlambatDisetujui();}
    },{
        id      : 'terlambat_ditolak',
        handler : function(){transaksiTerlambatDitolak();}
    },{
        id      : 'terlambat_diketahui',
        handler : function(){transaksiTerlambatDiketahui();}
    }];
    
    var transaksiTerlambatUser        = <?php echo $this->session->userdata('id');?>;
    var transaksiTerlambatSetuju      = <?php echo $this->session->userdata('user_disetujui');?>;
    var transaksiTerlambatTahu        = <?php echo $this->session->userdata('user_diketahui');?>;
    var transaksiTerlambatDataSetuju  = null;
    var transaksiTerlambatDataTahu    = null;
    
    $('#grid-transaksi_terlambat').datagrid({view:scrollview,remoteFilter:true,
        url:'<?php echo site_url('transaksi/terlambat/index'); ?>?grid=true'})
        .datagrid({	
        onLoadSuccess: function(data){
            $('#terlambat_edit').linkbutton('disable');
            $('#terlambat_delete').linkbutton('disable');
            $('#terlambat_disetujui').linkbutton({
                text    : '',
                iconCls : '',
                disabled: true
            });
            $('#terlambat_diketahui').linkbutton({
                text    : '',
                iconCls : '',
                disabled: true
            });
            $('#terlambat_ditolak').linkbutton({
                text    : '',
                iconCls : '',
                disabled: true
            });
        },
        onClickRow: function(index,row){
            if(row['g.name'] !== null){
                $('#terlambat_edit').linkbutton('disable');
                $('#terlambat_delete').linkbutton('disable');
                $('#terlambat_disetujui').linkbutton({
                    text    : '',
                    iconCls : '',
                    disabled: true
                });
                $('#terlambat_diketahui').linkbutton({
                    text    : '',
                    iconCls : '',
                    disabled: true
                });
                $('#terlambat_ditolak').linkbutton({
                    text    : '',
                    iconCls : '',
                    disabled: true
                });
            }
            else {
                if(row['e.name'] === null && row['f.name'] === null){                
                    $('#terlambat_edit').linkbutton('enable');
                    $('#terlambat_delete').linkbutton('enable');
                    $('#terlambat_diketahui').linkbutton({
                        text    : '',
                        iconCls : '',
                        disabled: true
                    });
                    if(transaksiTerlambatSetuju){
                        $('#terlambat_disetujui').linkbutton({
                            text    : 'Disetujui',
                            iconCls : 'icon-approved',
                            disabled: false
                        });
                        transaksiTerlambatDataSetuju = 1;
                        $('#terlambat_ditolak').linkbutton({
                            text    : 'Ditolak',
                            iconCls : 'icon-approved_denied',
                            disabled: false
                        });
                    }
                    else{
                        $('#terlambat_disetujui').linkbutton({
                            text    : '',
                            iconCls : '',
                            disabled: true
                        });
                        $('#terlambat_ditolak').linkbutton({
                            text    : '',
                            iconCls : '',
                            disabled: true
                        });
                    }
                }
                if(row['e.name'] !== null && row['f.name'] === null){
                    $('#terlambat_edit').linkbutton('disable');
                    $('#terlambat_delete').linkbutton('disable');
                    $('#terlambat_ditolak').linkbutton({
                        text    : '',
                        iconCls : '',
                        disabled: true
                    });
                    if(transaksiTerlambatSetuju && transaksiTerlambatTahu){
                        if(row.fterlambat_disetujui == transaksiTerlambatUser){
                            $('#terlambat_disetujui').linkbutton({
                                text    : 'Batal Disetujui',
                                iconCls : 'icon-approved_denied',
                                disabled: false
                            });
                            transaksiTerlambatDataSetuju = 0;
                            $('#terlambat_diketahui').linkbutton({
                                text    : '',
                                iconCls : '',
                                disabled: true
                            });
                        }
                        else{
                            $('#terlambat_disetujui').linkbutton({
                                text    : '',
                                iconCls : '',
                                disabled: true
                            });
                            $('#terlambat_diketahui').linkbutton({
                                text    : 'Diketahui',
                                iconCls : 'icon-approved',
                                disabled: false
                            });
                            transaksiTerlambatDataTahu = 1;
                        }
                    }
                    else if(transaksiTerlambatSetuju){
                        $('#terlambat_diketahui').linkbutton({
                            text    : '',
                            iconCls : '',
                            disabled: true
                        });
                        if(row.fterlambat_disetujui == transaksiTerlambatUser){
                            $('#terlambat_disetujui').linkbutton({
                                text    : 'Batal Disetujui',
                                iconCls : 'icon-approved_denied',
                                disabled: false
                            });
                            transaksiTerlambatDataSetuju = 0;
                        }
                        else{
                            $('#terlambat_disetujui').linkbutton({
                                text    : '',
                                iconCls : '',
                                disabled: true
                            });
                        }
                    }
                    else if(transaksiTerlambatTahu){
                        $('#terlambat_disetujui').linkbutton({
                            text    : '',
                            iconCls : '',
                            disabled: true
                        });
                        if(row.fterlambat_disetujui == transaksiTerlambatUser){
                            $('#terlambat_diketahui').linkbutton({
                                text    : '',
                                iconCls : '',
                                disabled: true
                            });
                        }
                        else{
                           $('#terlambat_diketahui').linkbutton({
                                text    : 'Diketahui',
                                iconCls : 'icon-approved',
                                disabled: false
                            });
                            transaksiTerlambatDataTahu = 1;
                        }
                    }
                    else{
                        $('#terlambat_diketahui').linkbutton({
                            text    : '',
                            iconCls : '',
                            disabled: true
                        });
                        $('#terlambat_disetujui').linkbutton({
                            text    : '',
                            iconCls : '',
                            disabled: true
                        });
                    }
                }
                if(row['e.name'] !== null && row['f.name'] !== null){
                    $('#terlambat_edit').linkbutton('disable');
                    $('#terlambat_delete').linkbutton('disable');
                    $('#terlambat_disetujui').linkbutton({
                        text    : '',
                        iconCls : '',
                        disabled: true
                    });
                    $('#terlambat_ditolak').linkbutton({
                            text    : '',
                            iconCls : '',
                            disabled: true
                        });
                    if(transaksiTerlambatTahu){
                        if(row.fterlambat_diketahui == transaksiTerlambatUser){
                            $('#terlambat_diketahui').linkbutton({
                                text    : 'Batal Diketahui',
                                iconCls : 'icon-approved_denied',
                                disabled: false
                            });
                            transaksiTerlambatDataTahu = 0;
                        }
                        else{
                            $('#terlambat_diketahui').linkbutton({
                                text    : '',
                                iconCls : '',
                                disabled: true
                            });
                        }
                    }
                    else{
                        $('#terlambat_diketahui').linkbutton({
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
                transaksiTerlambatUpdate();
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
    
    function transaksiTerlambatRefresh() {
        $('#terlambat_edit').linkbutton('disable');
        $('#terlambat_delete').linkbutton('disable');
        $('#terlambat_disetujui').linkbutton({
            text    : '',
            iconCls : '',
            disabled: true
        });
        $('#terlambat_diketahui').linkbutton({
            text    : '',
            iconCls : '',
            disabled: true
        });
        $('#terlambat_ditolak').linkbutton({
            text    : '',
            iconCls : '',
            disabled: true
        });
        $('#grid-transaksi_terlambat').datagrid('reload');
    }
    
    function transaksiTerlambatDisetujui() {
        var row = $('#grid-transaksi_terlambat').datagrid('getSelected');
        if (row){
            if(transaksiTerlambatDataSetuju === 1){
                $.messager.confirm('Konfirmasi','Anda yakin ingin Menyetujui terlambat no. '+row.fterlambat_id+' ?',function(r){
                    if (r){
                        $.post('<?php echo site_url('transaksi/terlambat/disetujui'); ?>',{fterlambat_id:row.fterlambat_id,fterlambat_disetujui:transaksiTerlambatUser},function(result){
                            if (result.success){
                                transaksiTerlambatRefresh();
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
            else if(transaksiTerlambatDataSetuju === 0){
                $.messager.confirm('Konfirmasi','Anda yakin ingin Batal Menyetujui terlambat no. '+row.fterlambat_id+' ?',function(r){
                    if (r){
                        $.post('<?php echo site_url('transaksi/terlambat/disetujui'); ?>',{fterlambat_id:row.fterlambat_id,fterlambat_disetujui:0},function(result){
                            if (result.success){
                                transaksiTerlambatRefresh();
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
    
    function transaksiTerlambatDiketahui() {
        var row = $('#grid-transaksi_terlambat').datagrid('getSelected');
        if (row){
            if(transaksiTerlambatDataTahu === 1){
                $.messager.confirm('Konfirmasi','Anda yakin ingin Mengetahui terlambat no. '+row.fterlambat_id+' ?',function(r){
                    if (r){
                        $.post('<?php echo site_url('transaksi/terlambat/diketahui'); ?>',{fterlambat_id:row.fterlambat_id,fterlambat_diketahui:transaksiTerlambatUser},function(result){
                            if (result.success){
                                transaksiTerlambatRefresh();
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
            else if(transaksiTerlambatDataTahu === 0){
                $.messager.confirm('Konfirmasi','Anda yakin ingin Batal Mengetahui terlambat no. '+row.fterlambat_id+' ?',function(r){
                    if (r){
                        $.post('<?php echo site_url('transaksi/terlambat/diketahui'); ?>',{fterlambat_id:row.fterlambat_id,fterlambat_diketahui:0},function(result){
                            if (result.success){
                                transaksiTerlambatRefresh();
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
    
    function transaksiTerlambatDitolak(){
        var row = $('#grid-transaksi_terlambat').datagrid('getSelected');
        if (row){
            $.messager.prompt('Konfirmasi','Mengapa anda ingin Menolak terlambat no. '+row.fterlambat_id+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('transaksi/terlambat/ditolak'); ?>',{fterlambat_id:row.fterlambat_id,fterlambat_ditolak:transaksiTerlambatUser,fterlambat_keterangan:r},function(result){
                        if (result.success){
                            transaksiTerlambatRefresh();
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
    
    function transaksiTerlambatCreate() {
        $('#dlg-transaksi_terlambat').dialog({modal: true}).dialog('open').dialog('setTitle','Tambah Data');
        $('#fm-transaksi_terlambat').form('clear');
        url = '<?php echo site_url('transaksi/terlambat/create'); ?>';
    }
    
    function transaksiTerlambatUpdate() {
        var row = $('#grid-transaksi_terlambat').datagrid('getSelected');
        if(row){
            $('#dlg-transaksi_terlambat').dialog({modal: true}).dialog('open').dialog('setTitle','Edit Data');
            $('#fm-transaksi_terlambat').form('load',row);
            url = '<?php echo site_url('transaksi/terlambat/update'); ?>/' + row.fterlambat_id;
        }
        else
        {
             $.messager.alert('Info','Data belum dipilih !','info');
        }
    }
    
    function transaksiTerlambatSave(){
        $('#fm-transaksi_terlambat').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success){
                    $('#dlg-transaksi_terlambat').dialog('close');
                    transaksiTerlambatRefresh();
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
        
    function transaksiTerlambatHapus(){
        var row = $('#grid-transaksi_terlambat').datagrid('getSelected');
        if (row){
            $.messager.confirm('Konfirmasi','Anda yakin ingin menghapus ID no. '+row.fterlambat_id+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('transaksi/terlambat/delete'); ?>',{fterlambat_id:row.fterlambat_id},function(result){
                        if (result.success){
                            transaksiTerlambatRefresh();
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
    #fm-transaksi_terlambat{
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
<div id="dlg-transaksi_terlambat" class="easyui-dialog" style="width:600px; height:350px; padding: 10px 20px" closed="true" buttons="#dlg-buttons-transaksi_terlambat">
    <form id="fm-transaksi_terlambat" method="post" novalidate>        
        <div class="fitem">
            <label for="type">Tanggal</label>
            <input type="text" id="fterlambat_tanggal" name="fterlambat_tanggal" class="easyui-datebox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Nama Karyawan</label>
            <input type="text" id="fterlambat_nik" name="fterlambat_nik" style="width:200px;" class="easyui-combogrid" required="true"
                data-options="
                    panelWidth: 500,
                    idField: 'karyawan_nik',
                    textField: 'karyawan_nama',
                    url:'<?php echo site_url('transaksi/terlambat/getKaryawan'); ?>',
                    mode:'remote',
                    fitColumns: true,
                    columns: [[
                        {field:'karyawan_nik',title:'NIK',width:50,align:'center'},
                        {field:'karyawan_nama',title:'Nama',width:120,halign:'center'},
                        {field:'c.departemen_nama',title:'Departemen',width:80,align:'center'},
                        {field:'b.departemen_nama',title:'Bagian',width:80,align:'center'}
                    ]],
                    onSelect: function (rowIndex, rowData) {
                        var g = $('#fterlambat_nik').combogrid('grid');
                        var r = g.datagrid('getSelected');
                        $('#fterlambat_bagian').combobox('setValue', r.karyawan_bagian);
                    }
            " />
        </div>
        <div class="fitem">
            <label for="type">Bagian</label>
            <input type="text" id="fterlambat_bagian" name="fterlambat_bagian" style="width:200px;" class="easyui-combobox"
                data-options="url:'<?php echo site_url('transaksi/terlambat/getDept'); ?>',
                method:'get', valueField:'id', textField:'bagian', groupField:'departemen', panelHeight:'300', readonly: true" />
        </div>
        <div class="fitem">
            <label for="type">Shift</label>
            <input type="text" id="fterlambat_shift" name="fterlambat_shift" style="width:80px;" class="easyui-combobox" required="true"
                data-options="url:'<?php echo site_url('transaksi/terlambat/enumShift'); ?>',
                method:'get', valueField:'data', textField:'data', panelHeight:'75'" />
        </div>
        <div class="fitem">
            <label for="type">Waktu</label>
            <input type="text" id="fterlambat_waktu" name="fterlambat_waktu" style="width:80px;" class="easyui-timespinner" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Alasan</label>
            <input type="text" id="fterlambat_alasan" name="fterlambat_alasan" style="width:350px;" class="easyui-textbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Keterangan</label>
            <input type="text" id="fterlambat_keterangan" name="fterlambat_keterangan" style="width:350px;" class="easyui-textbox"/>
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg-buttons-transaksi_terlambat">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="transaksiTerlambatSave()">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-transaksi_terlambat').dialog('close')">Batal</a>
</div>

<!-- End of file v_terlambat.php -->
<!-- Location: ./application/views/transaksi/v_terlambat.php -->
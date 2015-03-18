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
<table id="grid-transaksi_izin"
    data-options="pageSize:50, multiSort:true, remoteSort:true, rownumbers:true, singleSelect:true, 
                fit:true, fitColumns:false, toolbar:toolbar_transaksi_izin">
    <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'fizin_id'"             width="50"  halign="center" align="center" sortable="true" >ID</th>
            <th data-options="field:'fizin_tanggal'"        width="100" halign="center" align="center" sortable="true" >Tanggal</th>
            <th data-options="field:'d.karyawan_nama'"      width="150" halign="center" align="center" sortable="true" >Nama Karyawan</th>
            <th data-options="field:'c.departemen_nama'"    width="100" halign="center" align="center" sortable="true" >Departemen</th>
            <th data-options="field:'b.departemen_nama'"    width="100" halign="center" align="center" sortable="true" >Bagian</th>
            <th data-options="field:'fizin_jenis'"          width="100" halign="center" align="center" sortable="true" >Jenis Izin</th>
            <th data-options="field:'fizin_dari'"           width="150" halign="center" align="center" sortable="true" >Dari</th>
            <th data-options="field:'fizin_sampai'"         width="150" halign="center" align="center" sortable="true" >Sampai</th>
            <th data-options="field:'fizin_keperluan'"      width="150" halign="center" align="center" sortable="true" >Keperluan</th>
            <th data-options="field:'fizin_keterangan'"     width="150" halign="center" align="center" sortable="true" >Keterangan</th>
            <th data-options="field:'e.name'"               width="70"  halign="center" align="center" sortable="true" >Disetujui</th>
            <th data-options="field:'f.name'"               width="70"  halign="center" align="center" sortable="true" >Diketahui</th>
            <th data-options="field:'g.name'"               width="70"  halign="center" align="center" sortable="true" >Ditolak</th>
            <th data-options="field:'h.name'"               width="70"  halign="center" align="center" sortable="true" >Dibuat</th>
            <th data-options="field:'fizin_timestamp'"      width="140" halign="center" align="center" sortable="true" >Tanggal Pembuatan</th>
        </tr>
    </thead>
</table>

<script type="text/javascript">
    var toolbar_transaksi_izin = [{
        id      : 'izin_new',
        text    : 'New',
        iconCls : 'icon-new_file',
        handler : function(){transaksiIzinCreate();}
    },{
        id      : 'izin_edit',
        text    : 'Edit',
        iconCls : 'icon-edit',
        handler : function(){transaksiIzinUpdate();}
    },{
        id      : 'izin_delete',
        text    : 'Delete',
        iconCls : 'icon-cancel',
        handler : function(){transaksiIzinHapus();}
    },{
        text    : 'Refresh',
        iconCls : 'icon-reload',
        handler : function(){transaksiIzinRefresh();}
    },{
        id      : 'izin_disetujui',
        handler : function(){transaksiIzinDisetujui();}
    },{
        id      : 'izin_ditolak',
        handler : function(){transaksiIzinDitolak();}
    },{
        id      : 'izin_diketahui',
        handler : function(){transaksiIzinDiketahui();}
    }];
    
    var transaksiIzinUser        = <?php echo $this->session->userdata('id');?>;
    var transaksiIzinSetuju      = <?php echo $this->session->userdata('user_disetujui');?>;
    var transaksiIzinTahu        = <?php echo $this->session->userdata('user_diketahui');?>;
    var transaksiIzinDataSetuju  = null;
    var transaksiIzinDataTahu    = null;
    
    $('#grid-transaksi_izin').datagrid({view:scrollview,remoteFilter:true,
        url:'<?php echo site_url('transaksi/izin/index'); ?>?grid=true'})
        .datagrid({	
        onLoadSuccess: function(data){
            $('#izin_edit').linkbutton('disable');
            $('#izin_delete').linkbutton('disable');
            $('#izin_disetujui').linkbutton({
                text    : '',
                iconCls : '',
                disabled: true
            });
            $('#izin_diketahui').linkbutton({
                text    : '',
                iconCls : '',
                disabled: true
            });
            $('#izin_ditolak').linkbutton({
                text    : '',
                iconCls : '',
                disabled: true
            });
        },
        onClickRow: function(index,row){
            if(row['g.name'] !== null){
                $('#izin_edit').linkbutton('disable');
                $('#izin_delete').linkbutton('disable');
                $('#izin_disetujui').linkbutton({
                    text    : '',
                    iconCls : '',
                    disabled: true
                });
                $('#izin_diketahui').linkbutton({
                    text    : '',
                    iconCls : '',
                    disabled: true
                });
                $('#izin_ditolak').linkbutton({
                    text    : '',
                    iconCls : '',
                    disabled: true
                });
            }
            else {
                if(row['e.name'] === null && row['f.name'] === null){                
                    $('#izin_edit').linkbutton('enable');
                    $('#izin_delete').linkbutton('enable');
                    $('#izin_diketahui').linkbutton({
                        text    : '',
                        iconCls : '',
                        disabled: true
                    });
                    if(transaksiIzinSetuju){
                        $('#izin_disetujui').linkbutton({
                            text    : 'Disetujui',
                            iconCls : 'icon-approved',
                            disabled: false
                        });
                        transaksiIzinDataSetuju = 1;
                        $('#izin_ditolak').linkbutton({
                            text    : 'Ditolak',
                            iconCls : 'icon-approved_denied',
                            disabled: false
                        });
                    }
                    else{
                        $('#izin_disetujui').linkbutton({
                            text    : '',
                            iconCls : '',
                            disabled: true
                        });
                        $('#izin_ditolak').linkbutton({
                            text    : '',
                            iconCls : '',
                            disabled: true
                        });
                    }
                }
                if(row['e.name'] !== null && row['f.name'] === null){
                    $('#izin_edit').linkbutton('disable');
                    $('#izin_delete').linkbutton('disable');
                    $('#izin_ditolak').linkbutton({
                        text    : '',
                        iconCls : '',
                        disabled: true
                    });
                    if(transaksiIzinSetuju && transaksiIzinTahu){
                        if(row.fizin_disetujui == transaksiIzinUser){
                            $('#izin_disetujui').linkbutton({
                                text    : 'Batal Disetujui',
                                iconCls : 'icon-approved_denied',
                                disabled: false
                            });
                            transaksiIzinDataSetuju = 0;
                            $('#izin_diketahui').linkbutton({
                                text    : '',
                                iconCls : '',
                                disabled: true
                            });
                        }
                        else{
                            $('#izin_disetujui').linkbutton({
                                text    : '',
                                iconCls : '',
                                disabled: true
                            });
                            $('#izin_diketahui').linkbutton({
                                text    : 'Diketahui',
                                iconCls : 'icon-approved',
                                disabled: false
                            });
                            transaksiIzinDataTahu = 1;
                        }
                    }
                    else if(transaksiIzinSetuju){
                        $('#izin_diketahui').linkbutton({
                            text    : '',
                            iconCls : '',
                            disabled: true
                        });
                        if(row.fizin_disetujui == transaksiIzinUser){
                            $('#izin_disetujui').linkbutton({
                                text    : 'Batal Disetujui',
                                iconCls : 'icon-approved_denied',
                                disabled: false
                            });
                            transaksiIzinDataSetuju = 0;
                        }
                        else{
                            $('#izin_disetujui').linkbutton({
                                text    : '',
                                iconCls : '',
                                disabled: true
                            });
                        }
                    }
                    else if(transaksiIzinTahu){
                        $('#izin_disetujui').linkbutton({
                            text    : '',
                            iconCls : '',
                            disabled: true
                        });
                        if(row.fizin_disetujui == transaksiIzinUser){
                            $('#izin_diketahui').linkbutton({
                                text    : '',
                                iconCls : '',
                                disabled: true
                            });
                        }
                        else{
                           $('#izin_diketahui').linkbutton({
                                text    : 'Diketahui',
                                iconCls : 'icon-approved',
                                disabled: false
                            });
                            transaksiIzinDataTahu = 1;
                        }
                    }
                    else{
                        $('#izin_diketahui').linkbutton({
                            text    : '',
                            iconCls : '',
                            disabled: true
                        });
                        $('#izin_disetujui').linkbutton({
                            text    : '',
                            iconCls : '',
                            disabled: true
                        });
                    }
                }
                if(row['e.name'] !== null && row['f.name'] !== null){
                    $('#izin_edit').linkbutton('disable');
                    $('#izin_delete').linkbutton('disable');
                    $('#izin_disetujui').linkbutton({
                        text    : '',
                        iconCls : '',
                        disabled: true
                    });
                    $('#izin_ditolak').linkbutton({
                            text    : '',
                            iconCls : '',
                            disabled: true
                        });
                    if(transaksiIzinTahu){
                        if(row.fizin_diketahui == transaksiIzinUser){
                            $('#izin_diketahui').linkbutton({
                                text    : 'Batal Diketahui',
                                iconCls : 'icon-approved_denied',
                                disabled: false
                            });
                            transaksiIzinDataTahu = 0;
                        }
                        else{
                            $('#izin_diketahui').linkbutton({
                                text    : '',
                                iconCls : '',
                                disabled: true
                            });
                        }
                    }
                    else{
                        $('#izin_diketahui').linkbutton({
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
                transaksiIzinUpdate();
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
    
    function transaksiIzinRefresh() {
        $('#izin_edit').linkbutton('disable');
        $('#izin_delete').linkbutton('disable');
        $('#izin_disetujui').linkbutton({
            text    : '',
            iconCls : '',
            disabled: true
        });
        $('#izin_diketahui').linkbutton({
            text    : '',
            iconCls : '',
            disabled: true
        });
        $('#izin_ditolak').linkbutton({
            text    : '',
            iconCls : '',
            disabled: true
        });
        $('#grid-transaksi_izin').datagrid('reload');
    }
    
    function transaksiIzinDisetujui() {
        var row = $('#grid-transaksi_izin').datagrid('getSelected');
        if (row){
            if(transaksiIzinDataSetuju === 1){
                $.messager.confirm('Konfirmasi','Anda yakin ingin Menyetujui izin no. '+row.fizin_id+' ?',function(r){
                    if (r){
                        $.post('<?php echo site_url('transaksi/izin/disetujui'); ?>',{fizin_id:row.fizin_id,fizin_disetujui:transaksiIzinUser},function(result){
                            if (result.success){
                                transaksiIzinRefresh();
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
            else if(transaksiIzinDataSetuju === 0){
                $.messager.confirm('Konfirmasi','Anda yakin ingin Batal Menyetujui izin no. '+row.fizin_id+' ?',function(r){
                    if (r){
                        $.post('<?php echo site_url('transaksi/izin/disetujui'); ?>',{fizin_id:row.fizin_id,fizin_disetujui:0},function(result){
                            if (result.success){
                                transaksiIzinRefresh();
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
    
    function transaksiIzinDiketahui() {
        var row = $('#grid-transaksi_izin').datagrid('getSelected');
        if (row){
            if(transaksiIzinDataTahu === 1){
                $.messager.confirm('Konfirmasi','Anda yakin ingin Mengetahui izin no. '+row.fizin_id+' ?',function(r){
                    if (r){
                        $.post('<?php echo site_url('transaksi/izin/diketahui'); ?>',{fizin_id:row.fizin_id,fizin_diketahui:transaksiIzinUser},function(result){
                            if (result.success){
                                transaksiIzinRefresh();
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
            else if(transaksiIzinDataTahu === 0){
                $.messager.confirm('Konfirmasi','Anda yakin ingin Batal Mengetahui izin no. '+row.fizin_id+' ?',function(r){
                    if (r){
                        $.post('<?php echo site_url('transaksi/izin/diketahui'); ?>',{fizin_id:row.fizin_id,fizin_diketahui:0},function(result){
                            if (result.success){
                                transaksiIzinRefresh();
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
    
    function transaksiIzinDitolak(){
        var row = $('#grid-transaksi_izin').datagrid('getSelected');
        if (row){
            $.messager.prompt('Konfirmasi','Mengapa anda ingin Menolak izin no. '+row.fizin_id+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('transaksi/izin/ditolak'); ?>',{fizin_id:row.fizin_id,fizin_ditolak:transaksiIzinUser,fizin_keterangan:r},function(result){
                        if (result.success){
                            transaksiIzinRefresh();
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
    
    function transaksiIzinCreate() {
        $('#dlg-transaksi_izin').dialog({modal: true}).dialog('open').dialog('setTitle','Tambah Data');
        $('#fm-transaksi_izin').form('clear');
        url = '<?php echo site_url('transaksi/izin/create'); ?>';
    }
    
    function transaksiIzinUpdate() {
        var row = $('#grid-transaksi_izin').datagrid('getSelected');
        if(row){
            $('#dlg-transaksi_izin').dialog({modal: true}).dialog('open').dialog('setTitle','Edit Data');
            $('#fm-transaksi_izin').form('load',row);
            url = '<?php echo site_url('transaksi/izin/update'); ?>/' + row.fizin_id;
        }
        else
        {
             $.messager.alert('Info','Data belum dipilih !','info');
        }
    }
    
    function transaksiIzinSave(){
        $('#fm-transaksi_izin').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success){
                    $('#dlg-transaksi_izin').dialog('close');
                    transaksiIzinRefresh();
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
        
    function transaksiIzinHapus(){
        var row = $('#grid-transaksi_izin').datagrid('getSelected');
        if (row){
            $.messager.confirm('Konfirmasi','Anda yakin ingin menghapus izin no. '+row.fizin_id+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('transaksi/izin/delete'); ?>',{fizin_id:row.fizin_id},function(result){
                        if (result.success){
                            transaksiIzinRefresh();
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
    #fm-transaksi_izin{
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
<div id="dlg-transaksi_izin" class="easyui-dialog" style="width:600px; height:350px; padding: 10px 20px" closed="true" buttons="#dlg-buttons-transaksi_izin">
    <form id="fm-transaksi_izin" method="post" novalidate>        
        <div class="fitem">
            <label for="type">Tanggal</label>
            <input type="text" id="fizin_tanggal" name="fizin_tanggal" class="easyui-datebox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Nama Karyawan</label>
            <input type="text" id="fizin_nik" name="fizin_nik" style="width:200px;" class="easyui-combogrid" required="true"
                data-options="
                    panelWidth: 500,
                    idField: 'karyawan_nik',
                    textField: 'karyawan_nama',
                    url:'<?php echo site_url('transaksi/izin/getKaryawan'); ?>',
                    mode:'remote',
                    fitColumns: true,
                    columns: [[
                        {field:'karyawan_nik',title:'NIK',width:50,align:'center'},
                        {field:'karyawan_nama',title:'Nama',width:120,halign:'center'},
                        {field:'c.departemen_nama',title:'Departemen',width:80,align:'center'},
                        {field:'b.departemen_nama',title:'Bagian',width:80,align:'center'}
                    ]],
                    onSelect: function (rowIndex, rowData) {
                        var g = $('#fizin_nik').combogrid('grid');
                        var r = g.datagrid('getSelected');
                        $('#fizin_bagian').combobox('setValue', r.karyawan_bagian);
                    }
            " />
        </div>
        <div class="fitem">
            <label for="type">Bagian</label>
            <input type="text" id="fizin_bagian" name="fizin_bagian" style="width:200px;" class="easyui-combobox"
                data-options="url:'<?php echo site_url('transaksi/izin/getDept'); ?>',
                method:'get', valueField:'id', textField:'bagian', groupField:'departemen', panelHeight:'300', readonly: true" />
        </div>
        <div class="fitem">
            <label for="type">Jenis Izin</label>
            <input type="text" id="fizin_jenis" name="fizin_jenis" style="width:150px;" class="easyui-combobox" required="true"
                data-options="url:'<?php echo site_url('transaksi/izin/enumJenis'); ?>',
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
        <div class="fitem">
            <label for="type">Keterangan</label>
            <input type="text" id="fizin_keterangan" name="fizin_keterangan" style="width:350px;" class="easyui-textbox"/>
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg-buttons-transaksi_izin">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="transaksiIzinSave()">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-transaksi_izin').dialog('close')">Batal</a>
</div>

<!-- End of file v_izin.php -->
<!-- Location: ./application/views/transaksi/v_izin.php -->
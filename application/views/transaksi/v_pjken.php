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
<table id="grid-transaksi_pjken"
    data-options="pageSize:50, multiSort:true, remoteSort:true, rownumbers:true, singleSelect:true, 
                fit:true, fitColumns:false, toolbar:toolbar_transaksi_pjken">
    <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'fpjken_id'"            width="50"  halign="center" align="center" sortable="true" >ID</th>
            <th data-options="field:'fpjken_tanggal'"       width="100" halign="center" align="center" sortable="true" >Tanggal</th>
            <th data-options="field:'d.karyawan_nama'"      width="150" halign="center" align="center" sortable="true" >Nama Karyawan</th>
            <th data-options="field:'c.departemen_nama'"    width="100" halign="center" align="center" sortable="true" >Departemen</th>
            <th data-options="field:'b.departemen_nama'"    width="100" halign="center" align="center" sortable="true" >Bagian</th>
            <th data-options="field:'fpjken_pinjam'"        width="100" halign="center" align="center" sortable="true" >Tgl. Pinjam</th>
            <th data-options="field:'fpjken_mobil'"         width="100" halign="center" align="center" sortable="true" >No. Mobil</th>
            <th data-options="field:'fpjken_keperluan'"     width="150" halign="center" align="center" sortable="true" >Keperluan</th>
            <th data-options="field:'fpjken_keterangan'"     width="150" halign="center" align="center" sortable="true" >Keterangan</th>
            <th data-options="field:'e.name'"               width="70"  halign="center" align="center" sortable="true" >Disetujui</th>
            <th data-options="field:'f.name'"               width="70"  halign="center" align="center" sortable="true" >Diketahui</th>
            <th data-options="field:'g.name'"               width="70"  halign="center" align="center" sortable="true" >Ditolak</th>
            <th data-options="field:'h.name'"               width="70"  halign="center" align="center" sortable="true" >Dibuat</th>
            <th data-options="field:'fpjken_timestamp'"     width="150" halign="center" align="center" sortable="true" >Tanggal Pembuatan</th>
        </tr>
    </thead>
</table>

<script type="text/javascript">
    var toolbar_transaksi_pjken = [{
        id      : 'pjken_new',
        text    : 'New',
        iconCls : 'icon-new_file',
        handler : function(){transaksiPjkenCreate();}
    },{
        id      : 'pjken_edit',
        text    : 'Edit',
        iconCls : 'icon-edit',
        handler : function(){transaksiPjkenUpdate();}
    },{
        id      : 'pjken_delete',
        text    : 'Delete',
        iconCls : 'icon-cancel',
        handler : function(){transaksiPjkenHapus();}
    },{
        text    : 'Refresh',
        iconCls : 'icon-reload',
        handler : function(){transaksiPjkenRefresh();}
    },{
        id      : 'pjken_disetujui',
        handler : function(){transaksiPjkenDisetujui();}
    },{
        id      : 'pjken_ditolak',
        handler : function(){transaksiPjkenDitolak();}
    },{
        id      : 'pjken_diketahui',
        handler : function(){transaksiPjkenDiketahui();}
    }];
    
    var transaksiPjkenUser        = <?php echo $this->session->userdata('id');?>;
    var transaksiPjkenSetuju      = <?php echo $this->session->userdata('user_disetujui');?>;
    var transaksiPjkenTahu        = <?php echo $this->session->userdata('user_diketahui');?>;
    var transaksiPjkenDataSetuju  = null;
    var transaksiPjkenDataTahu    = null;
    
    $('#grid-transaksi_pjken').datagrid({view:scrollview,remoteFilter:true,
        url:'<?php echo site_url('transaksi/pjken/index'); ?>?grid=true'})
        .datagrid({	
        onLoadSuccess: function(data){
            $('#pjken_edit').linkbutton('disable');
            $('#pjken_delete').linkbutton('disable');
            $('#pjken_disetujui').linkbutton({
                text    : '',
                iconCls : '',
                disabled: true
            });
            $('#pjken_diketahui').linkbutton({
                text    : '',
                iconCls : '',
                disabled: true
            });
            $('#pjken_ditolak').linkbutton({
                text    : '',
                iconCls : '',
                disabled: true
            });
        },
        onClickRow: function(index,row){
            if(row['g.name'] !== null){
                $('#pjken_edit').linkbutton('disable');
                $('#pjken_delete').linkbutton('disable');
                $('#pjken_disetujui').linkbutton({
                    text    : '',
                    iconCls : '',
                    disabled: true
                });
                $('#pjken_diketahui').linkbutton({
                    text    : '',
                    iconCls : '',
                    disabled: true
                });
                $('#pjken_ditolak').linkbutton({
                    text    : '',
                    iconCls : '',
                    disabled: true
                });
            }
            else {
                if(row['e.name'] === null && row['f.name'] === null){                
                    $('#pjken_edit').linkbutton('enable');
                    $('#pjken_delete').linkbutton('enable');
                    $('#pjken_diketahui').linkbutton({
                        text    : '',
                        iconCls : '',
                        disabled: true
                    });
                    if(transaksiPjkenSetuju){
                        $('#pjken_disetujui').linkbutton({
                            text    : 'Disetujui',
                            iconCls : 'icon-approved',
                            disabled: false
                        });
                        transaksiPjkenDataSetuju = 1;
                        $('#pjken_ditolak').linkbutton({
                            text    : 'Ditolak',
                            iconCls : 'icon-approved_denied',
                            disabled: false
                        });
                    }
                    else{
                        $('#pjken_disetujui').linkbutton({
                            text    : '',
                            iconCls : '',
                            disabled: true
                        });
                        $('#pjken_ditolak').linkbutton({
                            text    : '',
                            iconCls : '',
                            disabled: true
                        });
                    }
                }
                if(row['e.name'] !== null && row['f.name'] === null){
                    $('#pjken_edit').linkbutton('disable');
                    $('#pjken_delete').linkbutton('disable');
                    $('#pjken_ditolak').linkbutton({
                        text    : '',
                        iconCls : '',
                        disabled: true
                    });
                    if(transaksiPjkenSetuju && transaksiPjkenTahu){
                        if(row.fpjken_disetujui == transaksiPjkenUser){
                            $('#pjken_disetujui').linkbutton({
                                text    : 'Batal Disetujui',
                                iconCls : 'icon-approved_denied',
                                disabled: false
                            });
                            transaksiPjkenDataSetuju = 0;
                            $('#pjken_diketahui').linkbutton({
                                text    : '',
                                iconCls : '',
                                disabled: true
                            });
                        }
                        else{
                            $('#pjken_disetujui').linkbutton({
                                text    : '',
                                iconCls : '',
                                disabled: true
                            });
                            $('#pjken_diketahui').linkbutton({
                                text    : 'Diketahui',
                                iconCls : 'icon-approved',
                                disabled: false
                            });
                            transaksiPjkenDataTahu = 1;
                        }
                    }
                    else if(transaksiPjkenSetuju){
                        $('#pjken_diketahui').linkbutton({
                            text    : '',
                            iconCls : '',
                            disabled: true
                        });
                        if(row.fpjken_disetujui == transaksiPjkenUser){
                            $('#pjken_disetujui').linkbutton({
                                text    : 'Batal Disetujui',
                                iconCls : 'icon-approved_denied',
                                disabled: false
                            });
                            transaksiPjkenDataSetuju = 0;
                        }
                        else{
                            $('#pjken_disetujui').linkbutton({
                                text    : '',
                                iconCls : '',
                                disabled: true
                            });
                        }
                    }
                    else if(transaksiPjkenTahu){
                        $('#pjken_disetujui').linkbutton({
                            text    : '',
                            iconCls : '',
                            disabled: true
                        });
                        if(row.fpjken_disetujui == transaksiPjkenUser){
                            $('#pjken_diketahui').linkbutton({
                                text    : '',
                                iconCls : '',
                                disabled: true
                            });
                        }
                        else{
                           $('#pjken_diketahui').linkbutton({
                                text    : 'Diketahui',
                                iconCls : 'icon-approved',
                                disabled: false
                            });
                            transaksiPjkenDataTahu = 1;
                        }
                    }
                    else{
                        $('#pjken_diketahui').linkbutton({
                            text    : '',
                            iconCls : '',
                            disabled: true
                        });
                        $('#pjken_disetujui').linkbutton({
                            text    : '',
                            iconCls : '',
                            disabled: true
                        });
                    }
                }
                if(row['e.name'] !== null && row['f.name'] !== null){
                    $('#pjken_edit').linkbutton('disable');
                    $('#pjken_delete').linkbutton('disable');
                    $('#pjken_disetujui').linkbutton({
                        text    : '',
                        iconCls : '',
                        disabled: true
                    });
                    $('#pjken_ditolak').linkbutton({
                            text    : '',
                            iconCls : '',
                            disabled: true
                        });
                    if(transaksiPjkenTahu){
                        if(row.fpjken_diketahui == transaksiPjkenUser){
                            $('#pjken_diketahui').linkbutton({
                                text    : 'Batal Diketahui',
                                iconCls : 'icon-approved_denied',
                                disabled: false
                            });
                            transaksiPjkenDataTahu = 0;
                        }
                        else{
                            $('#pjken_diketahui').linkbutton({
                                text    : '',
                                iconCls : '',
                                disabled: true
                            });
                        }
                    }
                    else{
                        $('#pjken_diketahui').linkbutton({
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
                transaksiPjkenUpdate();
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
    
    function transaksiPjkenRefresh() {
        $('#pjken_edit').linkbutton('disable');
        $('#pjken_delete').linkbutton('disable');
        $('#pjken_disetujui').linkbutton({
            text    : '',
            iconCls : '',
            disabled: true
        });
        $('#pjken_diketahui').linkbutton({
            text    : '',
            iconCls : '',
            disabled: true
        });
        $('#pjken_ditolak').linkbutton({
            text    : '',
            iconCls : '',
            disabled: true
        });
        $('#grid-transaksi_pjken').datagrid('reload');
    }
    
    function transaksiPjkenDisetujui() {
        var row = $('#grid-transaksi_pjken').datagrid('getSelected');
        if (row){
            if(transaksiPjkenDataSetuju === 1){
                $.messager.confirm('Konfirmasi','Anda yakin ingin Menyetujui Pinjam Kendaraan no. '+row.fpjken_id+' ?',function(r){
                    if (r){
                        $.post('<?php echo site_url('transaksi/pjken/disetujui'); ?>',{fpjken_id:row.fpjken_id,fpjken_disetujui:transaksiPjkenUser},function(result){
                            if (result.success){
                                transaksiPjkenRefresh();
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
            else if(transaksiPjkenDataSetuju === 0){
                $.messager.confirm('Konfirmasi','Anda yakin ingin Batal Menyetujui Pinjam Kendaraan no. '+row.fpjken_id+' ?',function(r){
                    if (r){
                        $.post('<?php echo site_url('transaksi/pjken/disetujui'); ?>',{fpjken_id:row.fpjken_id,fpjken_disetujui:0},function(result){
                            if (result.success){
                                transaksiPjkenRefresh();
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
    
    function transaksiPjkenDiketahui() {
        var row = $('#grid-transaksi_pjken').datagrid('getSelected');
        if (row){
            if(transaksiPjkenDataTahu === 1){
                $.messager.confirm('Konfirmasi','Anda yakin ingin Mengetahui Pinjam Kendaraan no. '+row.fpjken_id+' ?',function(r){
                    if (r){
                        $.post('<?php echo site_url('transaksi/pjken/diketahui'); ?>',{fpjken_id:row.fpjken_id,fpjken_diketahui:transaksiPjkenUser},function(result){
                            if (result.success){
                                transaksiPjkenRefresh();
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
            else if(transaksiPjkenDataTahu === 0){
                $.messager.confirm('Konfirmasi','Anda yakin ingin Batal Mengetahui Pinjam Kendaraan no. '+row.fpjken_id+' ?',function(r){
                    if (r){
                        $.post('<?php echo site_url('transaksi/pjken/diketahui'); ?>',{fpjken_id:row.fpjken_id,fpjken_diketahui:0},function(result){
                            if (result.success){
                                transaksiPjkenRefresh();
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
    
    function transaksiPjkenDitolak(){
        var row = $('#grid-transaksi_pjken').datagrid('getSelected');
        if (row){
            $.messager.prompt('Konfirmasi','Mengapa anda ingin Menolak Pinjam Kendaraan no. '+row.fpjken_id+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('transaksi/pjken/ditolak'); ?>',{fpjken_id:row.fpjken_id,fpjken_ditolak:transaksiPjkenUser,fpjken_keterangan:r},function(result){
                        if (result.success){
                            transaksiPjkenRefresh();
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
    
    function transaksiPjkenCreate() {
        $('#dlg-transaksi_pjken').dialog({modal: true}).dialog('open').dialog('setTitle','Tambah Data');
        $('#fm-transaksi_pjken').form('clear');
        url = '<?php echo site_url('transaksi/pjken/create'); ?>';
    }
    
    function transaksiPjkenUpdate() {
        var row = $('#grid-transaksi_pjken').datagrid('getSelected');
        if(row){
            $('#dlg-transaksi_pjken').dialog({modal: true}).dialog('open').dialog('setTitle','Edit Data');
            $('#fm-transaksi_pjken').form('load',row);
            url = '<?php echo site_url('transaksi/pjken/update'); ?>/' + row.fpjken_id;
        }
        else
        {
             $.messager.alert('Info','Data belum dipilih !','info');
        }
    }
    
    function transaksiPjkenSave(){
        $('#fm-transaksi_pjken').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success){
                    $('#dlg-transaksi_pjken').dialog('close');
                    transaksiPjkenRefresh();
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
        
    function transaksiPjkenHapus(){
        var row = $('#grid-transaksi_pjken').datagrid('getSelected');
        if (row){
            $.messager.confirm('Konfirmasi','Anda yakin ingin menghapus ID no. '+row.fpjken_id+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('transaksi/pjken/delete'); ?>',{fpjken_id:row.fpjken_id},function(result){
                        if (result.success){
                            transaksiPjkenRefresh();
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
    #fm-transaksi_pjken{
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
<div id="dlg-transaksi_pjken" class="easyui-dialog" style="width:600px; height:350px; padding: 10px 20px" closed="true" buttons="#dlg-buttons-transaksi_pjken">
    <form id="fm-transaksi_pjken" method="post" novalidate>        
        <div class="fitem">
            <label for="type">Tanggal</label>
            <input type="text" id="fpjken_tanggal" name="fpjken_tanggal" class="easyui-datebox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Nama Karyawan</label>
            <input type="text" id="fpjken_nik" name="fpjken_nik" style="width:200px;" class="easyui-combogrid" required="true"
                data-options="
                    panelWidth: 500,
                    idField: 'karyawan_nik',
                    textField: 'karyawan_nama',
                    url:'<?php echo site_url('transaksi/pjken/getKaryawan'); ?>',
                    mode:'remote',
                    fitColumns: true,
                    columns: [[
                        {field:'karyawan_nik',title:'NIK',width:50,align:'center'},
                        {field:'karyawan_nama',title:'Nama',width:120,halign:'center'},
                        {field:'c.departemen_nama',title:'Departemen',width:80,align:'center'},
                        {field:'b.departemen_nama',title:'Bagian',width:80,align:'center'}
                    ]],
                    onSelect: function (rowIndex, rowData) {
                        var g = $('#fpjken_nik').combogrid('grid');
                        var r = g.datagrid('getSelected');
                        $('#fpjken_bagian').combobox('setValue', r.karyawan_bagian);
                    }
            " />
        </div>
        <div class="fitem">
            <label for="type">Bagian</label>
            <input type="text" id="fpjken_bagian" name="fpjken_bagian" style="width:200px;" class="easyui-combobox"
                data-options="url:'<?php echo site_url('transaksi/pjken/getDept'); ?>',
                method:'get', valueField:'id', textField:'bagian', groupField:'departemen', panelHeight:'300', readonly: true" />
        </div>
        <div class="fitem">
            <label for="type">Tanggal Pinjam</label>
            <input type="text" id="fpjken_pinjam" name="fpjken_pinjam" class="easyui-datebox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">No. Mobil</label>
            <input type="text" id="fpjken_mobil" name="fpjken_mobil" style="width:200px;" class="easyui-combobox" required="true"
                data-options="url:'<?php echo site_url('transaksi/pjken/getMobil'); ?>',
                method:'get', valueField:'mobil_no', textField:'mobil_no', panelHeight:'300'" />
        </div>
        <div class="fitem">
            <label for="type">Keperluan</label>
            <input type="text" id="fpjken_keperluan" name="fpjken_keperluan" style="width:350px;" class="easyui-textbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Keterangan</label>
            <input type="text" id="fpjken_keterangan" name="fpjken_keterangan" style="width:350px;" class="easyui-textbox"/>
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg-buttons-transaksi_pjken">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="transaksiPjkenSave()">Simpan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-transaksi_pjken').dialog('close')">Batal</a>
</div>

<!-- End of file v_pjken.php -->
<!-- Location: ./application/views/transaksi/v_pjken.php -->
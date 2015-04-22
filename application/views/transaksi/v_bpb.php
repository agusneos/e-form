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
                    <th data-options="field:'fbpb_id'"              width="50"  halign="center" align="center" sortable="true" >ID</th>
                    <th data-options="field:'fbpb_tanggal'"         width="100" halign="center" align="center" sortable="true" >Tanggal</th>
                    <th data-options="field:'d.karyawan_nama'"      width="150" halign="center" align="left"   sortable="true" >Nama Karyawan</th>
                    <th data-options="field:'c.departemen_nama'"    width="100" halign="center" align="center" sortable="true" >Departemen</th>
                    <th data-options="field:'b.departemen_nama'"    width="100" halign="center" align="center" sortable="true" >Bagian</th>
                    <th data-options="field:'fbpb_keterangan'"      width="150" halign="center" align="left"   sortable="true" >Keterangan</th>
                    <th data-options="field:'e.name'"               width="70"  halign="center" align="center" sortable="true" >Disetujui</th>
                    <th data-options="field:'f.name'"               width="70"  halign="center" align="center" sortable="true" >Diketahui</th>
                    <th data-options="field:'g.name'"               width="70"  halign="center" align="center" sortable="true" >Ditolak</th>
                    <th data-options="field:'h.name'"               width="70"  halign="center" align="center" sortable="true" >Dibuat</th>
                    <th data-options="field:'fbpb_timestamp'"       width="140" halign="center" align="center" sortable="true" >Tanggal Pembuatan</th>
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
                    <th data-options="field:'fbpb_detail_barang'"       width="300" halign="center" align="left"   sortable="true">Nama Barang</th>
                    <th data-options="field:'fbpb_detail_qty'"          width="70"  halign="center" align="center" sortable="true">Jumlah</th>
                    <th data-options="field:'fbpb_detail_satuan'"       width="100" halign="center" align="center" sortable="true">Satuan</th>
                    <th data-options="field:'fbpb_detail_digunakan'"    width="100" halign="center" align="center" sortable="true">Tgl Digunakan</th>
                    <th data-options="field:'fbpb_detail_stock'"        width="50"  halign="center" align="center" sortable="true">Stock</th>
                    <th data-options="field:'fbpb_detail_pemakaian'"    width="70"  halign="center" align="center" sortable="true">Pemakaian</th>
                    <th data-options="field:'fbpb_detail_ket'"          width="300" halign="center" align="left"   sortable="true">Keterangan</th>
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
        handler : function(){transaksiBpbRefresh();}
    },{
        id      : 'bpb_disetujui',
        handler : function(){transaksiBpbDisetujui();}
    },{
        id      : 'bpb_ditolak',
        handler : function(){transaksiBpbDitolak();}
    },{
        id      : 'bpb_diketahui',
        handler : function(){transaksiBpbDiketahui();}
    }];
    
    var transaksiBpbUser        = <?php echo $this->session->userdata('id');?>;
    var transaksiBpbSetuju      = <?php echo $this->session->userdata('user_disetujui');?>;
    var transaksiBpbTahu        = <?php echo $this->session->userdata('user_diketahui');?>;
    var transaksiBpbDataSetuju  = null;
    var transaksiBpbDataTahu    = null;
    
    $('#grid-transaksi_bpb').datagrid({view:scrollview,remoteFilter:true,
        url:'<?php echo site_url('transaksi/bpb/index'); ?>?grid=true'})        
        .datagrid({
	onLoadSuccess: function(data){
            $('#bpb_edit').linkbutton('disable');
            $('#bpb_delete').linkbutton('disable');
            $('#bpb_disetujui').linkbutton({
                text    : '',
                iconCls : '',
                disabled: true
            });
            $('#bpb_diketahui').linkbutton({
                text    : '',
                iconCls : '',
                disabled: true
            });
            $('#bpb_ditolak').linkbutton({
                text    : '',
                iconCls : '',
                disabled: true
            });
            $('#bpb_detail_new').linkbutton('disable');
            $('#bpb_detail_edit').linkbutton('disable');
            $('#bpb_detail_delete').linkbutton('disable');
            $('#bpb_detail_refresh').linkbutton('disable');
        },
        onClickRow: function(index,row){
            
            if(row['g.name'] !== null){
                $('#bpb_edit').linkbutton('disable');
                $('#bpb_delete').linkbutton('disable');
                $('#bpb_disetujui').linkbutton({
                    text    : '',
                    iconCls : '',
                    disabled: true
                });
                $('#bpb_diketahui').linkbutton({
                    text    : '',
                    iconCls : '',
                    disabled: true
                });
                $('#bpb_ditolak').linkbutton({
                    text    : '',
                    iconCls : '',
                    disabled: true
                });
                $('#bpb_detail_new').linkbutton('disable');
                $('#bpb_detail_edit').linkbutton('disable');
                $('#bpb_detail_delete').linkbutton('disable');
                $('#bpb_detail_refresh').linkbutton('disable');
                transaksiBpbNilai = row.fbpb_id;
                $('#grid-transaksi_bpb_detail').datagrid('load','<?php echo site_url('transaksi/bpb/index_detail'); ?>?grid=true&nilai='+transaksiBpbNilai);
            }
            else {
                if(row['e.name'] === null && row['f.name'] === null){                
                    $('#bpb_edit').linkbutton('enable');
                    $('#bpb_delete').linkbutton('enable');
                    $('#bpb_detail_new').linkbutton('enable');
                    $('#bpb_detail_refresh').linkbutton('enable');
                    transaksiBpbNilai = row.fbpb_id;
                    $('#grid-transaksi_bpb_detail').datagrid('load','<?php echo site_url('transaksi/bpb/index_detail'); ?>?grid=true&nilai='+transaksiBpbNilai);
                    $('#bpb_diketahui').linkbutton({
                        text    : '',
                        iconCls : '',
                        disabled: true
                    });
                    if(transaksiBpbSetuju){
                        $('#bpb_disetujui').linkbutton({
                            text    : 'Disetujui',
                            iconCls : 'icon-approved',
                            disabled: false
                        });
                        transaksiBpbDataSetuju = 1;
                        $('#bpb_ditolak').linkbutton({
                            text    : 'Ditolak',
                            iconCls : 'icon-approved_denied',
                            disabled: false
                        });
                    }
                    else{
                        $('#bpb_disetujui').linkbutton({
                            text    : '',
                            iconCls : '',
                            disabled: true
                        });
                        $('#bpb_ditolak').linkbutton({
                            text    : '',
                            iconCls : '',
                            disabled: true
                        });
                    }
                }
                if(row['e.name'] !== null && row['f.name'] === null){
                    $('#bpb_edit').linkbutton('disable');
                    $('#bpb_delete').linkbutton('disable');
                    $('#bpb_ditolak').linkbutton({
                        text    : '',
                        iconCls : '',
                        disabled: true
                    });
                    $('#bpb_detail_new').linkbutton('disable');
                    $('#bpb_detail_edit').linkbutton('disable');
                    $('#bpb_detail_delete').linkbutton('disable');
                    $('#bpb_detail_refresh').linkbutton('disable');
                    transaksiBpbNilai = row.fbpb_id;
                    $('#grid-transaksi_bpb_detail').datagrid('load','<?php echo site_url('transaksi/bpb/index_detail'); ?>?grid=true&nilai='+transaksiBpbNilai);
                    
                    if(transaksiBpbSetuju && transaksiBpbTahu){
                        if(row.fbpb_disetujui == transaksiBpbUser){
                            $('#bpb_disetujui').linkbutton({
                                text    : 'Batal Disetujui',
                                iconCls : 'icon-approved_denied',
                                disabled: false
                            });
                            transaksiBpbDataSetuju = 0;
                            $('#bpb_diketahui').linkbutton({
                                text    : '',
                                iconCls : '',
                                disabled: true
                            });
                        }
                        else{
                            $('#bpb_disetujui').linkbutton({
                                text    : '',
                                iconCls : '',
                                disabled: true
                            });
                            $('#bpb_diketahui').linkbutton({
                                text    : 'Diketahui',
                                iconCls : 'icon-approved',
                                disabled: false
                            });
                            transaksiBpbDataTahu = 1;
                        }
                    }
                    else if(transaksiBpbSetuju){
                        $('#bpb_diketahui').linkbutton({
                            text    : '',
                            iconCls : '',
                            disabled: true
                        });
                        if(row.fbpb_disetujui == transaksiBpbUser){
                            $('#bpb_disetujui').linkbutton({
                                text    : 'Batal Disetujui',
                                iconCls : 'icon-approved_denied',
                                disabled: false
                            });
                            transaksiBpbDataSetuju = 0;
                        }
                        else{
                            $('#bpb_disetujui').linkbutton({
                                text    : '',
                                iconCls : '',
                                disabled: true
                            });
                        }
                    }
                    else if(transaksiBpbTahu){
                        $('#bpb_disetujui').linkbutton({
                            text    : '',
                            iconCls : '',
                            disabled: true
                        });
                        if(row.fbpb_disetujui == transaksiBpbUser){
                            $('#bpb_diketahui').linkbutton({
                                text    : '',
                                iconCls : '',
                                disabled: true
                            });
                        }
                        else{
                           $('#bpb_diketahui').linkbutton({
                                text    : 'Diketahui',
                                iconCls : 'icon-approved',
                                disabled: false
                            });
                            transaksiBpbDataTahu = 1;
                        }
                    }
                    else{
                        $('#bpb_diketahui').linkbutton({
                            text    : '',
                            iconCls : '',
                            disabled: true
                        });
                        $('#bpb_disetujui').linkbutton({
                            text    : '',
                            iconCls : '',
                            disabled: true
                        });
                    }
                }
                if(row['e.name'] !== null && row['f.name'] !== null){
                    $('#bpb_edit').linkbutton('disable');
                    $('#bpb_delete').linkbutton('disable');
                    $('#bpb_disetujui').linkbutton({
                        text    : '',
                        iconCls : '',
                        disabled: true
                    });
                    $('#bpb_ditolak').linkbutton({
                        text    : '',
                        iconCls : '',
                        disabled: true
                    });
                    $('#bpb_detail_new').linkbutton('disable');
                    $('#bpb_detail_edit').linkbutton('disable');
                    $('#bpb_detail_delete').linkbutton('disable');
                    $('#bpb_detail_refresh').linkbutton('disable');
                    transaksiBpbNilai = row.fbpb_id;
                    $('#grid-transaksi_bpb_detail').datagrid('load','<?php echo site_url('transaksi/bpb/index_detail'); ?>?grid=true&nilai='+transaksiBpbNilai);    
                    if(transaksiBpbTahu){
                        if(row.fbpb_diketahui == transaksiBpbUser){
                            $('#bpb_diketahui').linkbutton({
                                text    : 'Batal Diketahui',
                                iconCls : 'icon-approved_denied',
                                disabled: false
                            });
                            transaksiBpbDataTahu = 0;
                        }
                        else{
                            $('#bpb_diketahui').linkbutton({
                                text    : '',
                                iconCls : '',
                                disabled: true
                            });
                        }
                    }
                    else{
                        $('#bpb_diketahui').linkbutton({
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
                transaksiBpbUpdate();
            }        
	},
        onSortColumn: function(sort,order){
            transaksiBpbRefresh();
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
    
    function transaksiBpbRefresh() {
        $('#bpb_edit').linkbutton('disable');
        $('#bpb_delete').linkbutton('disable');
        $('#bpb_disetujui').linkbutton({
            text    : '',
            iconCls : '',
            disabled: true
        });
        $('#bpb_diketahui').linkbutton({
            text    : '',
            iconCls : '',
            disabled: true
        });
        $('#bpb_ditolak').linkbutton({
            text    : '',
            iconCls : '',
            disabled: true
        });
        $('#grid-transaksi_bpb').datagrid('reload');
        
        $('#bpb_detail_new').linkbutton('disable');
        $('#bpb_detail_edit').linkbutton('disable');
        $('#bpb_detail_delete').linkbutton('disable');
        $('#bpb_detail_refresh').linkbutton('disable');
        transaksiBpbNilai=null;
        $('#grid-transaksi_bpb_detail').datagrid('load','<?php echo site_url('transaksi/bpb/index_detail'); ?>?grid=true&nilai='+transaksiBpbNilai);
        $('#grid-transaksi_bpb_detail').datagrid('reload');
        
    }
    
    function transaksiBpbDisetujui() {
        var row = $('#grid-transaksi_bpb').datagrid('getSelected');
        if (row){
            if(transaksiBpbDataSetuju === 1){
                $.messager.confirm('Konfirmasi','Anda yakin ingin Menyetujui bpb no. '+row.fbpb_id+' ?',function(r){
                    if (r){
                        $.post('<?php echo site_url('transaksi/bpb/disetujui'); ?>',{fbpb_id:row.fbpb_id,fbpb_disetujui:transaksiBpbUser},function(result){
                            if (result.success){
                                transaksiBpbRefresh();
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
            else if(transaksiBpbDataSetuju === 0){
                $.messager.confirm('Konfirmasi','Anda yakin ingin Batal Menyetujui bpb no. '+row.fbpb_id+' ?',function(r){
                    if (r){
                        $.post('<?php echo site_url('transaksi/bpb/disetujui'); ?>',{fbpb_id:row.fbpb_id,fbpb_disetujui:0},function(result){
                            if (result.success){
                                transaksiBpbRefresh();
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
    
    function transaksiBpbDiketahui() {
        var row = $('#grid-transaksi_bpb').datagrid('getSelected');
        if (row){
            if(transaksiBpbDataTahu === 1){
                $.messager.confirm('Konfirmasi','Anda yakin ingin Mengetahui bpb no. '+row.fbpb_id+' ?',function(r){
                    if (r){
                        $.post('<?php echo site_url('transaksi/bpb/diketahui'); ?>',{fbpb_id:row.fbpb_id,fbpb_diketahui:transaksiBpbUser},function(result){
                            if (result.success){
                                transaksiBpbRefresh();
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
            else if(transaksiBpbDataTahu === 0){
                $.messager.confirm('Konfirmasi','Anda yakin ingin Batal Mengetahui bpb no. '+row.fbpb_id+' ?',function(r){
                    if (r){
                        $.post('<?php echo site_url('transaksi/bpb/diketahui'); ?>',{fbpb_id:row.fbpb_id,fbpb_diketahui:0},function(result){
                            if (result.success){
                                transaksiBpbRefresh();
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
    
    function transaksiBpbDitolak(){
        var row = $('#grid-transaksi_bpb').datagrid('getSelected');
        if (row){
            $.messager.prompt('Konfirmasi','Mengapa anda ingin Menolak bpb no. '+row.fbpb_id+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('transaksi/bpb/ditolak'); ?>',{fbpb_id:row.fbpb_id,fbpb_ditolak:transaksiBpbUser,fbpb_keterangan:r},function(result){
                        if (result.success){
                            transaksiBpbRefresh();
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
       
    function transaksiBpbCreate() {
        $('#dlg-transaksi_bpb').dialog({modal: true}).dialog('open').dialog('setTitle','Tambah Data');
        $('#fm-transaksi_bpb').form('clear');
        url = '<?php echo site_url('transaksi/bpb/create'); ?>';
    }
    
    function transaksiBpbUpdate() {
        var row = $('#grid-transaksi_bpb').datagrid('getSelected');
        if(row){
            $('#dlg-transaksi_bpb').dialog({modal: true}).dialog('open').dialog('setTitle','Edit Data');
            $('#fm-transaksi_bpb').form('load',row);
            url = '<?php echo site_url('transaksi/bpb/update'); ?>/' + row.fbpb_id;
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
                    transaksiBpbRefresh();
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
                            transaksiBpbRefresh();
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
            var rowHeader = $('#grid-transaksi_bpb').datagrid('getSelected');
            if(rowHeader['e.name'] === null && rowHeader['g.name'] === null){
                $('#bpb_detail_edit').linkbutton('enable');
                $('#bpb_detail_delete').linkbutton('enable');
            }
            else{
                $('#bpb_detail_edit').linkbutton('disable');
                $('#bpb_detail_delete').linkbutton('disable');
            }            
        },
        onDblClickRow: function(index,row){
            var rowHeader = $('#grid-transaksi_bpb').datagrid('getSelected');
            if(rowHeader['e.name'] === null && rowHeader['g.name'] === null){
                transaksiBpbDetailUpdate();
            }            
	}
        }).datagrid('enableFilter');
    
    function transaksiBpbDetailCreate() {
        var row = $('#grid-transaksi_bpb').datagrid('getSelected');
        if(row){
            $('#dlg-transaksi_bpb_detail').dialog({modal: true}).dialog('open').dialog('setTitle','Tambah Data');
            $('#fm-transaksi_bpb_detail').form('clear');
            url = '<?php echo site_url('transaksi/bpb/detailCreate'); ?>';
            $('#fbpb_detail_header').numberbox('setValue',transaksiBpbNilai).numberbox({readonly: true});
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
            <input type="text" id="fbpb_nik" name="fbpb_nik" style="width:200px;" class="easyui-combogrid" required="true"
                data-options="
                    panelWidth: 500,
                    idField: 'karyawan_nik',
                    textField: 'karyawan_nama',
                    url:'<?php echo site_url('transaksi/bpb/getKaryawan'); ?>',
                    mode:'remote',
                    fitColumns: true,
                    columns: [[
                        {field:'karyawan_nik',title:'NIK',width:50,align:'center'},
                        {field:'karyawan_nama',title:'Nama',width:120,halign:'center'},
                        {field:'c.departemen_nama',title:'Departemen',width:80,align:'center'},
                        {field:'b.departemen_nama',title:'Bagian',width:80,align:'center'}
                    ]],
                    onSelect: function (rowIndex, rowData) {
                        var g = $('#fbpb_nik').combogrid('grid');
                        var r = g.datagrid('getSelected');
                        $('#fbpb_bagian').combobox('setValue', r.karyawan_bagian);
                    }
            " />
        </div>
        <div class="fitem">
            <label for="type">Bagian</label>
            <input type="text" id="fbpb_bagian" name="fbpb_bagian" style="width:200px;" class="easyui-combobox"
                data-options="url:'<?php echo site_url('transaksi/bpb/getDept'); ?>',
                method:'get', valueField:'id', textField:'bagian', groupField:'departemen', panelHeight:'300', readonly: true" />
        </div>
        <div class="fitem">
            <label for="type">Keterangan</label>
            <input type="text" id="fbpb_keterangan" name="fbpb_keterangan" style="width:350px;" class="easyui-textbox"/>
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
            <label for="type">Satuan</label>
            <input type="text" id="fbpb_detail_satuan" name="fbpb_detail_satuan" style="width:100px;" class="easyui-textbox" required="true"/>
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
            <input type="text" id="fbpb_detail_ket" name="fbpb_detail_ket" style="width:300px;" class="easyui-textbox"/>
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
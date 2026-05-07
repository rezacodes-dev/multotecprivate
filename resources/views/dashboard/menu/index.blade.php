@extends('dashboard.layouts.app')

@push('page_css')
<link rel="stylesheet" href="{{ asset('public/assets/jquery_ui/jquery-ui.css') }}">
@endpush

@section('content_header')
<section class="content-header">
      <h1>
        Navigation Management
        <!--small>it all starts here</small-->
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Navigation</li>
      </ol>
    </section>
@endsection

@section('content')
<section class="content">

  @if(Session::has('msg'))
  <div class="ar-hide @if(Session::has('msg_class')){{ Session::get('msg_class') }}@endif">{{ Session::get('msg') }}</div>
  @endif

  <div id="navStatus"></div>

  <div class="row">
    <div class="col-md-6">
      
    </div>
    <div class="col-md-6">
    </div>
  </div>

  <!-- Default box -->
  <div class="box" style="margin-top: 10px;">
    <div class="box-header with-border">
      <div class="row">
        <div class="col-md-4">
          <select name="menu_id" id="menu_id" class="form-control">
            <option value="0">-SELECT MENU-</option>
            @if( isset($allMenus) )
              @foreach( $allMenus as $mn )
              <option value="{{ $mn->id }}">{{ ucwords($mn->name) }}</option>
              @endforeach
            @endif
          </select>
        </div>
        <div class="col-md-5"><label id="ajax_status"></label></div>
        <div class="col-md-3" style="text-align: right;">
          <input type="button" id="menu_save" class="btn btn-primary" value="Save">
          <input type="button" id="menu_delete" class="btn btn-danger" value="Delete">
        </div>
      </div>
    </div>
    <div class="box-body" id="mainBody">
      <div class="row">
        <div class="col-md-5">
          <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#all_pages">All Pages</a></li>
            <li><a data-toggle="tab" href="#add_links">Add Links</a></li>
            <li><a data-toggle="tab" href="#list_pages">List Pages</a></li>
            <li><a data-toggle="tab" href="#search_pages">Search Pages</a></li>
            
          </ul>
          <div class="tab-content">
            <div id="all_pages" class="tab-pane fade in active">
              @include('dashboard.menu.pages_tab')
            </div>
            <div id="add_links" class="tab-pane fade">
              <div class="row" style="margin-top: 10px;">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Link Text or Label : (English)<em>*</em></label>
                    <input type="text" id="link_text" class="form-control" placeholder="Enter Link Text">
                    <span class="base-red" id="link_text-error"></span>
                  </div>
                  <div class="form-group">
                    <label>URL : (English)</label>
                    <input type="text" id="link_url" class="form-control" placeholder="Enter Link URL ex:- http:// or https://">
                  </div>

                  @if(isset($otherLngs) && !empty($otherLngs))
                  <hr/>
                  	@foreach($otherLngs as $v)
                  	<input type="hidden" class="linklngid" value="{{$v->id}}">
                  	<div class="form-group">
                    	<label>Link Text or Label : ({{$v->name}})<em>*</em></label>
                    	<input type="text" id="linktext_{{$v->id}}" class="form-control linktext" placeholder="Enter Link Text">
                  	</div>
	                  <div class="form-group">
	                    <label>URL : ({{$v->name}})</label>
	                    <input type="text" id="linkurl_{{$v->id}}" class="form-control linkurl" placeholder="Enter Link URL ex:- http:// or https://">
	                  </div>
	                 <hr/>
                  	@endforeach
                  @endif
                  <div class="form-group">
                    <input type="button" id="add_link_btn" class="btn btn-primary" value="Add To Menu">
                  </div>
                </div>
              </div>
            </div>
            <div id="list_pages" class="tab-pane fade">
              @include('dashboard.menu.list_pages_tab')
            </div>
            <div id="search_pages" class="tab-pane fade">
              <div class="row" style="margin-top: 10px;">
                <div class="col-md-9">
                  <input type="text" id="src_page" class="form-control" placeholder="Find Page By Title or Slug">
                  <span class="base-red" id="src_page-error"></span>
                </div>
                <div class="col-md-3" style="text-align: right;">
                  <input type="button" id="find_page_btn" class="btn btn-primary" value="Find">
                </div>
              </div>
              <div class="row">
                <div class="col-md-12" id="pg_src_res"></div>
              </div>
            </div>
            <div id="list_pages" class="tab-pane fade">
              <div class="row" style="margin-top: 10px;">
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-7">
          <div id="naviHolder">
            <ol class="sortable"></ol>
          </div>
        </div>
      </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
      
    </div>
    <!-- /.box-footer-->
  </div>
  <!-- /.box -->

    </section>
@endsection

@push('page_js')
<script src="{{ asset('public/assets/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('public/assets/jquery_ui/jquery-ui.js') }}"></script>
<script src="{{ asset('public/assets/jquery.mjs.nestedSortable.js') }}"></script>

<script type="text/javascript">
$( function() {
  var ns = $('ol.sortable').nestedSortable({
    forcePlaceholderSize: true,
    handle: 'div',
    helper: 'clone',
    items: 'li',
    opacity: .6,
    placeholder: 'placeholder',
    revert: 250,
    tabSize: 25,
    tolerance: 'pointer',
    toleranceElement: '> div',
    maxLevels: 4,
    isTree: true,
    expandOnHover: 700,
    startCollapsed: false, //true
    change: function(){
      console.log('Relocated item');
    }
  });
  iniSet();

  /** When click on tablist left side **/
  $('body').on('click', '.pgHead', function() {
    var getID = $.trim( $(this).attr('id') );
    var getData = $.trim( $(this).attr('data') );
    var ckAjax = $( '#ajxck_' + getData + '_' + getID );
    var resDiv = $( '#' + getData + '_' + getID );
    if( $.trim( ckAjax.val() ) == 0 ) {
      $.ajax({
        type : 'POST',
        url : "{{ route('getPages') }}",
        data : {
          'getID' : getID,
          'getData' : getData,
          '_token' : "{{ csrf_token() }}"
        },
        cache : false,
        beforeSend : function() {
          resDiv.html('<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i> <span>Please Wait....</span>');
          $('#mainBody').block({ 
            message: '<h4>Please wait...</h4>', 
            css: { 
              border: 'none', 
              padding: '15px', 
              backgroundColor: '#000', 
              '-webkit-border-radius': '10px', 
              '-moz-border-radius': '10px', 
              opacity: .5, 
              color: '#fff' 
            } 
          });
        },
        success : function(viewData) {
          if( viewData.status == 'ok' ) {
            resDiv.html( viewData.html );
            $('#mainBody').unblock();
            ckAjax.val('1');
          }
        }
      });
    }
  });

  /** Each tablist pages checkbox with add button disabled **/
  $('body').on('click', '.ckAll', function() {
    var ckId = $(this).attr('id');
    var isCK = $(this).is(':checked');
    if(isCK == true){
      $('.ckbs_' + ckId).prop('checked', true);
      $('#btn_' + ckId).removeAttr('disabled');
    }
    if(isCK == false){
      $('.ckbs_' + ckId).prop('checked', false);
      $('#btn_' + ckId).attr('disabled', 'disabled');
    }
  });
  $('body').on('click', '.subck', function() {
    var getSubCkId = $(this).attr('id');
    var c = 0;
    $('.ckbs_' + getSubCkId).each(function() {
      if( $(this).is(':checked') ) {
        c++;
      }
    });
    if( c > 0 ) {
      $('#btn_' + getSubCkId).removeAttr('disabled');
      $('.ckAll_' + getSubCkId).prop('checked', true); 
    } else {
      $('#btn_' + getSubCkId).attr('disabled', 'disabled');
      $('.ckAll_' + getSubCkId).prop('checked', false);
    }
  });
  /***********************************************************/

  /** Add checked pages to menu - form left tablist **/
  $('body').on('click', '.admBtn', function() {
    var btnId = $(this).attr('id');
    var Arr = btnId.split('_');
    var cID = Arr[1];
    var menu_id = $('#menu_id').val();
    if( menu_id != 0 && menu_id != '' && menu_id != 'undefined') {
      if( cID != '' && cID != 'undefined' ) {
        var cms_ids = new Array();
        $('.ckbs_' + cID).each(function() {
          if( $(this).is(':checked') ) {
            cms_ids.push( $(this).val() );
          }
        });
        if( cms_ids.length > 0 ) {
          $.ajax({
            type: 'POST',
            url: "{{ route('setPages') }}",
            data: {
              'cms_ids' : cms_ids,
              'menu_id' : menu_id,
              '_token' : "{{ csrf_token() }}"
            },
            cache: false,
            beforeSend: function() {
              $('#mainBody').block({ 
                message: '<h4>Please wait...</h4>', 
                css: { 
                  border: 'none', 
                  padding: '15px', 
                  backgroundColor: '#000', 
                  '-webkit-border-radius': '10px', 
                  '-moz-border-radius': '10px', 
                  opacity: .5, 
                  color: '#fff' 
                } 
              });
            },
            success: function( viewData ) {
              if( viewData.status == 'ok' ) {
                $('#naviHolder ol:eq(0)').append( viewData.html );
                $('#mainBody').unblock();
                $('.subck').prop('checked', false);
                $('.ckAll').prop('checked', false);
                $('.admBtn').attr('disabled', 'disabled');
              }
            }
          });
        }
      }
    } else {
      $('#menu_id').focus();
    }
  });

  /** Initial Page Block **/
  $('#mainBody').block({ 
    message: '<h4>Please Select Menu First</h4>', 
    overlayCSS : {
      backgroundColor: '#f4f4f4'
    }
  });

  /** When menu deropdown changed **/
  $('#menu_id').on('change', function() {
    if( $(this).val() != '0' ) {
      $.ajax({
        type : 'POST',
        url : "{{ route('getMenu') }}",
        data : {
          'menu_id' : $(this).val(),
          '_token' : "{{ csrf_token() }}"
        },
        cache : false,
        beforeSend : function() {
          $('#mainBody').block({ 
            message: '<h4>Please Wait...</h4>', 
            overlayCSS : {
              backgroundColor: '#f4f4f4'
            }
          });
        },
        success : function(viewData) {
          if( viewData.status == 'ok' ) {
            $('#mainBody').unblock();
            $('#menu_save').removeAttr('disabled');
            $('#menu_delete').removeAttr('disabled');
            $('#naviHolder .sortable').html( viewData.html ); //first time load within shortable class
            $('ol.sortable').nestedSortable({
              forcePlaceholderSize: true,
              handle: 'div',
              helper: 'clone',
              items: 'li',
              opacity: .6,
              placeholder: 'placeholder',
              revert: 250,
              tabSize: 25,
              tolerance: 'pointer',
              toleranceElement: '> div',
              maxLevels: 4,
              isTree: true,
              expandOnHover: 700,
              startCollapsed: false,
              change: function(){
                console.log('Relocated item');
              }
            });
          }
        }
      });
    } else {
      iniSet();
      $('#mainBody').block({ 
        message: '<h4>Please Select Menu First</h4>', 
        overlayCSS : {
          backgroundColor: '#f4f4f4'
        }
      }); 
    }
  });

  /** When full menu saved button click **/
  $('#menu_save').on('click', function() {
    var menu_id = $('#menu_id').val();
    arraied = $('ol.sortable').nestedSortable('toArray', {startDepthCount: 0});
    arraied = JSON.stringify(arraied);
    $.ajax({
      type : 'POST',
      url : "{{ route('saveMenu') }}",
      data : {
        'menu_id' : menu_id,
        'arraied' : arraied,
        '_token' : "{{ csrf_token() }}"
      },
      cache : false,
      beforeSend : function() {
        $('#mainBody').block({ 
          message: '<h4>Please wait...</h4>', 
          css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
          } 
        });
      },
      success : function(sts) {
        if( sts === 'ok' ) {
          $('#mainBody').unblock();
          $('#ajax_status').show().html('<span class="base-green">Menu Saved Succesfully.</span>').fadeOut(5000);
        }
      }
    });
  });

  /** When full menu delete button click **/
  $('#menu_delete').on('click', function() {
    if( confirm('Are You Sure To Delete This Menu ?') ) {
      var menu_id = $('#menu_id').val();
      $.ajax({
        type : 'POST',
        url : "{{ route('delMenu') }}",
        data : {
          'menu_id' : menu_id,
          '_token' : "{{ csrf_token() }}"
        },
        beforeSend : function() {
          $('#mainBody').block({ 
            message: '<h4>Please Wait...</h4>', 
            overlayCSS : {
              backgroundColor: '#f4f4f4'
            }
          });
        },
        success : function(sts) {
          if( sts === 'ok' ) {
            $('#naviHolder .sortable').html('');
            $('#mainBody').unblock();
            $('#ajax_status').show().html('<span class="base-green">Menu Deleted Succesfully.</span>').fadeOut(5000);
          }
        }
      });
    }
  });


  /** Add CUSTOM_MENU_LINK **/
  $('#add_link_btn').on('click', function() {
    var link_text = $.trim( $('#link_text').val() );
    var link_url = $.trim( $('#link_url').val() );
    var menu_id = $.trim( $('#menu_id').val() );

    var collection = new Object();

    $('.linklngid').each( function() {

    	var lngid = $.trim($(this).val());
    	if( lngid != 0 && lngid != '0' ) {
	    	var innerArray = {};
	    	innerArray['link_text'] = $('#linktext_' + lngid).val();
	    	innerArray['link_url'] = $('#linkurl_' + lngid).val();
	    	collection[ lngid ] = innerArray;
    	}
    } );

    console.log(JSON.stringify(collection));


    if( link_text != '' ) {
      $.ajax({
        type : 'POST',
        url : "{{ route('addLink') }}",
        data : {
          'link_text' : link_text,
          'link_url' : link_url,
          'menu_id' : menu_id,
          'lnglinks' : JSON.stringify(collection),
          '_token' : "{{ csrf_token() }}"
        },
        cache : false,
        beforeSend : function() {
          $('#mainBody').block({ 
            message: '<h4>Please Wait...</h4>', 
            overlayCSS : {
              backgroundColor: '#f4f4f4'
            }
          });
        },
        success : function(viewData) {
          if( viewData.status == 'ok' ) {
            $('#naviHolder ol:eq(0)').append( viewData.html );
            $('#mainBody').unblock();
            $('#link_text-error').text('');
            $('#link_text').val('');
            $('#link_url').val('');
            $('.linktext').val('');
            $('.linkurl').val('');
          }
        }
      });
    } else {
      $('#link_text-error').text('Please Enter Link Text');
    }
  });

  /** List page checkbox checked or not checked **/
  $('body').on('click', '.list_pgs', function() {
    var ckcl = 0;
    $('.list_pgs').each( function() {
      if( $(this).is(':checked') ) {
        ckcl++;
      }
    });
    if( ckcl > 0 ) {
      $('#add_listpage_btn').removeAttr('disabled');
    } else {
      $('#add_listpage_btn').attr('disabled', 'disabled');
    }
  });

  /** Add List page to menu **/
  $('#add_listpage_btn').on('click', function() { 
    var menu_id = $.trim( $('#menu_id').val() );
    var lp_urls = new Array();
    $('.list_pgs').each(function() {
      if( $(this).is(':checked') ) {
        lp_urls.push( $(this).val() );
      }
    });
    if( lp_urls.length > 0 && menu_id != '' ) {

      $.ajax({
        type : "POST",
        url : "{{ route('addListPage') }}",
        data : {
          "menu_id" : menu_id,
          "listpage_urls" : lp_urls,
          "_token" : "{{ csrf_token() }}"
        },
        cache : false,
        beforeSend : function() {
          $('#mainBody').block({ 
            message: '<h4>Please Wait...</h4>', 
            overlayCSS : {
              backgroundColor: '#f4f4f4'
            }
          });
        },
        success : function(viewData) {
          if( viewData.status == 'ok' ) {
            $('#naviHolder ol:eq(0)').append( viewData.html );
            $('#mainBody').unblock();
            $('.list_pgs').prop("checked", false);
            $('#add_listpage_btn').attr('disabled', 'disabled');
          }
        }
      });
    } else {
      alert('Script Error');
    }    
  } );

  /** Find any page button click**/
  $('#find_page_btn').on('click', function() {
    var src_page = $.trim( $('#src_page').val() );
    if( src_page != '' ) {
      $.ajax({
        type : 'POST',
        url : "{{ route('srcPage') }}",
        data : {
          'src_page' : src_page,
          '_token' : "{{ csrf_token() }}"
        },
        cache : false,
        beforeSend : function() {
          $('#pg_src_res').html( '<br/><i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i> <span>Please Wait...</span>' );
          $('#find_page_btn').attr('disabled', 'disabled');
        },
        success : function(viewData) {
          if( viewData.status == 'ok' ) {
            $('#pg_src_res').html( viewData.html );
            //$('#src_page').val('');
            $('#find_page_btn').removeAttr('disabled');
          } else {
            $('#pg_src_res').html( '<span class="base-red">Server Error</span>' );
          }
        }
      });
    } else {
      $('#src_page-error').text('Please Enter Something.');
    }
  });

  /** Find page checkbox is checked or not **/
  $('body').on('click', '.src_ckb', function() {
    var src_c = 0;
    $('.src_ckb').each( function() {
      if( $(this).is(':checked') ) {
        src_c++;
      }
    });
    if( src_c > 0 ) {
      $('#admBtn_Src').removeAttr('disabled');
    } else {
      $('#admBtn_Src').attr('disabled', 'disabled');
    }
  });

  /** Add find page to menu **/
  $('body').on('click', '#admBtn_Src', function() {
    var menu_id = $.trim( $('#menu_id').val() );
    var cms_ids = new Array();
    $('.src_ckb').each(function() {
      if( $(this).is(':checked') ) {
        cms_ids.push( $(this).val() );
      }
    });
    if( cms_ids.length > 0 ) {
      $.ajax({
        type: 'POST',
        url: "{{ route('setPages') }}",
        data: {
          'cms_ids' : cms_ids,
          'menu_id' : menu_id,
          '_token' : "{{ csrf_token() }}"
        },
        cache: false,
        beforeSend: function() {
          $('#mainBody').block({ 
            message: '<h4>Please wait...</h4>', 
            css: { 
              border: 'none', 
              padding: '15px', 
              backgroundColor: '#000', 
              '-webkit-border-radius': '10px', 
              '-moz-border-radius': '10px', 
              opacity: .5, 
              color: '#fff' 
            } 
          });
        },
        success: function( viewData ) {
          if( viewData.status == 'ok' ) {
            $('#naviHolder ol:eq(0)').append( viewData.html );
            $('#mainBody').unblock();
            $('.src_ckb').prop('checked', false);
            $('#admBtn_Src').attr('disabled', 'disabled');
          }
        }
      });
    }
  });

  /** When menu pages tab click **/
  $('body').on('click', '.page_details', function() {
    var getNavId = $.trim( $(this).attr('id') );
    if( getNavId != '' && getNavId != 'undefined' && $( '#ajxFirSts_' + getNavId ).val() == '0' ) {
      $.ajax({
        type : 'POST',
        url : "{{ route('getPgBd') }}",
        data : {
          'getNavId' : getNavId,
          '_token' : "{{ csrf_token() }}"
        },
        cache : false,
        beforeSend : function() {
          $( '#pageBody_'+getNavId ).html( '<br/><i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i> <span>Please Wait...</span>' );
        },
        success : function( viewData ) {
          if( viewData.status == 'ok' ) {
            $( '#pageBody_' + getNavId ).html( viewData.html );
            $( '#ajxFirSts_' + getNavId ).val('1');
          }
        }
      });
    }
  });


  /** When save a page details in menu page tab **/
  $('body').on('click', '.savePageDetails', function() {
    var itsId = $.trim( $(this).attr('id') );
    var IdArr = itsId.split('_');
    var id = IdArr[1];
    var actionBody = 'srtnav' + id;
    var menu_id = $.trim($('#menu_id').val());
    var page_url = $( '#pageBody_' + id ).find( $('input[name="page_url"]') ).val();
    var label_txt = $( '#pageBody_' + id ).find( $('input[name="label_txt"]') ).val();
    var label_attr = $( '#pageBody_' + id ).find( $('input[name="label_attr"]') ).val();
    var is_link = $('#pageBody_' + id ).find( $('input[name="is_link"]') ).is(':checked');

    var collection = new Object();

    var i = 0;
    $( '#pageBody_' + id ).find('.lng_nav_id').each( function() {
      var lngnavid = $.trim($(this).val());
      var pocket = lngnavid + '_' + i;
      var _itsID = $(this).attr('id');
      var _itsIdArr = _itsID.split('_');
      var _getid = _itsIdArr[1];
      var innerArray = {};
      innerArray['page_url'] = $('#pageBody_' + id).find('#lngpgurl_' + _getid).val();
      innerArray['label_txt'] = $('#pageBody_' + id).find('#lnglabel_' + _getid).val();
      innerArray['label_attr'] = $('#pageBody_' + id).find('#lngattr_' + _getid).val();
      innerArray['table_type'] = $('#pageBody_' + id).find('#lngTableType_' + _getid).val();
      innerArray['table_id'] = $('#pageBody_' + id).find('#lngTableId_' + _getid).val();
      innerArray['cmslink_id'] = $('#pageBody_' + id).find('#cmsLinkId_' + _getid).val();
      innerArray['whos_id'] = $('#pageBody_' + id).find('#whosId_' + _getid).val();
      innerArray['lng_id'] = _getid;
      innerArray['is_link'] = is_link;
      collection[ pocket ] = innerArray;
      i++;
    } );

    console.log(JSON.stringify(collection));
    if( $.trim(label_txt) != '' && $.trim(label_attr) != '' ) {
      $.ajax({
        type : 'POST',
        url : "{{ route('svePgBd') }}",
        data : {
          'menu_id' : menu_id,
          'navid' : id,
          'page_url' : page_url,
          'label_txt' : label_txt,
          'label_attr' : label_attr,
          'is_link' : is_link,
          'lngnav_info' : JSON.stringify(collection),
          '_token' : "{{ csrf_token() }}"
        },
        cache : false,
        beforeSend : function() {
          $( '#pageBody_' + id ).block({ 
            message: '<h4>Please Wait...</h4>', 
            overlayCSS : {
              backgroundColor: '#f4f4f4'
            }
          });
        },
        success : function(rtns) {
          if( rtns === 'ok' ) {
            $( '#pageBody_' + id ).unblock();
            $( '#itemTitle_' + id ).text( label_txt );
            alert('Menu Item Saved Successfully');
          }
        }
      });
    } else {
      alert('Please Enter Label Text and Label Attribute');
    }
  });

  /** When delete a page details or tab from menu page tab **/
  $('body').on('click', '.delePageDetails', function() {
    if( confirm('Are You Sure To Delete ?') ) {
      var itsId = $.trim( $(this).attr('id') );
      var IdArr = itsId.split('_');
      var id = IdArr[1];
      if( id != '' && id != 'undefined' ) {
        $.ajax({
          type : 'POST',
          url : "{{ route('delPgBd') }}",
          data : {
            'id' : id,
            '_token' : "{{ csrf_token() }}"
          },
          cache : false,
          beforeSend : function() {
            $( '#pageBody_' + id ).block({ 
              message: '<h4>Please Wait...</h4>', 
              overlayCSS : {
                backgroundColor: '#f4f4f4'
              }
            });
          },
          success : function(resp) {
            if( resp === 'ok' ) {
              $('#menuItem_' + id).remove();
            }
          }
        });
      }
    }
  });
});

/** Page load initial **/
function iniSet() {
  $('#menu_save').attr('disabled', 'disabled');
  $('#menu_delete').attr('disabled', 'disabled');
}
</script>
@endpush
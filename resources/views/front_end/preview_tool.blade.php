<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="description" content="Responsive design testing for the masses">
    <title>Multotech.com</title>
    <style>
        * { vertical-align:top; }
        iframe { border: solid #ccc 1px; }
        
        .mob_device {
            border-radius: 25px;
            border: 1px;
            padding: 50px 0;
            box-shadow: 0 0 0 5px #f4f4f4, 0 0 0 8px #c2c2c2;
        }
        .pad_device {
            border-radius: 25px;
            border: 1px;
            padding: 50px 0;
            box-shadow: 0 0 0 5px #f4f4f4, 0 0 0 8px #c2c2c2;
        }
        .loading {
            margin-top: 50px;
            text-align: center;
            color: #0066cc;
        }
    </style>
</head>
<body>
    
    <div style="text-align: center;">
        <?php if( isset($_GET['device']) && $_GET['device'] == 'mobile' ) { ?>
        <div class="mob" style="float: left; margin-left: 20px;">
            <h3><strong>Mobile Preview</strong></h3>
            <div class="mob_device">
                <span class="loading">View Loading...<br/></span>
                <iframe class="frma" width="335" height="480" src="<?php if( isset($_GET['url']) ) echo trim($_GET['url']); ?>"></iframe>
            </div>
        </div>
        <div class="pad" style="float: right; margin-right: 20px;">
            <h3><strong>iPad/Tablet Preview</strong></h3>
            <div class="pad_device">
                <span class="loading">View Loading...<br/></span>
                <iframe class="frma" width="783" height="640" src="<?php if( isset($_GET['url']) ) echo trim($_GET['url']); ?>"></iframe>
            </div>
        </div>
        <div style="clear: both;"></div>
        <?php } ?>
        <?php if( isset($_GET['device']) && $_GET['device'] == 'desktop' ) { ?>
        <div class="desk">
            <h3><strong>Desktop Preview</strong></h3>
            <div class="dsk_device">
                <span class="loading">View Loading...<br/></span>
                <iframe class="frma" width="1200" height="786" src="<?php if( isset($_GET['url']) ) echo trim($_GET['url']); ?>"></iframe>
            </div>
        </div>
        <?php } ?>
    </div>
<script src="{{ asset('public/front_end/js/jquery.min.js') }}"></script>
<script type="text/javascript">
$( function() {
    $('.frma').on('load', function() {
        $('.loading').hide();
    });
});
</script>
</body>
</html>

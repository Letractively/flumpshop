<?php header("Content-type: text/css");
require_once dirname(__FILE__)."/../../../preload.php";
$size = ceil(75*$config->getNode('widget_carousel','imageScale'))."px";
?>.jcarousel-skin-tango .jcarousel-container {
    -moz-border-radius: 10px;
}


.jcarousel-skin-tango .jcarousel-container-vertical {
    width: <?php echo $size;?>;
    height: <?php echo $config->getNode('widget_carousel','indexHeight');?>px;
    padding: 40px 20px;
}

.jcarousel-skin-tango .jcarousel-clip-vertical {
    width:  <?php echo $size;?>;
    height: <?php echo $config->getNode('widget_carousel','indexHeight');?>px;
}

.jcarousel-skin-tango .jcarousel-item {
    width: <?php echo $size;?>;
    height: <?php echo $size;?>;
}


.jcarousel-skin-tango .jcarousel-item-vertical {
    margin-bottom: 10px;
}

.jcarousel-skin-tango .jcarousel-item-placeholder {
    color: #000;
}

/**
 *  Horizontal Buttons
 */
.jcarousel-skin-tango .jcarousel-next-horizontal {
    position: absolute;
    top: 43px;
    right: 5px;
    width: 32px;
    height: 32px;
    cursor: pointer;
    background: transparent url(next-horizontal.png) no-repeat 0 0;
}

.jcarousel-skin-tango .jcarousel-next-horizontal:hover {
    background-position: -32px 0;
}

.jcarousel-skin-tango .jcarousel-next-horizontal:active {
    background-position: -64px 0;
}

.jcarousel-skin-tango .jcarousel-next-disabled-horizontal,
.jcarousel-skin-tango .jcarousel-next-disabled-horizontal:hover,
.jcarousel-skin-tango .jcarousel-next-disabled-horizontal:active {
    cursor: default;
    background-position: -96px 0;
}

.jcarousel-skin-tango .jcarousel-prev-horizontal {
    position: absolute;
    top: 43px;
    left: 5px;
    width: 32px;
    height: 32px;
    cursor: pointer;
    background: transparent url(prev-horizontal.png) no-repeat 0 0;
}

.jcarousel-skin-tango .jcarousel-prev-horizontal:hover {
    background-position: -32px 0;
}

.jcarousel-skin-tango .jcarousel-prev-horizontal:active {
    background-position: -64px 0;
}

.jcarousel-skin-tango .jcarousel-prev-disabled-horizontal,
.jcarousel-skin-tango .jcarousel-prev-disabled-horizontal:hover,
.jcarousel-skin-tango .jcarousel-prev-disabled-horizontal:active {
    cursor: default;
    background-position: -96px 0;
}

/**
 *  Vertical Buttons
 */
.jcarousel-skin-tango .jcarousel-next-vertical {
    position: absolute;
    bottom: 5px;
    left: <?php echo (ceil((75*$config->getNode('widget_carousel','imageScale'))/2))."px";?>;
    width: 32px;
    height: 32px;
    cursor: pointer;
    background: transparent url(next-vertical.png) no-repeat 0 0;
}

.jcarousel-skin-tango .jcarousel-next-vertical:hover {
    background-position: 0 -32px;
}

.jcarousel-skin-tango .jcarousel-next-vertical:active {
    background-position: 0 -64px;
}

.jcarousel-skin-tango .jcarousel-next-disabled-vertical,
.jcarousel-skin-tango .jcarousel-next-disabled-vertical:hover,
.jcarousel-skin-tango .jcarousel-next-disabled-vertical:active {
    cursor: default;
    background-position: 0 -96px;
}

.jcarousel-skin-tango .jcarousel-prev-vertical {
    position: absolute;
    top: 5px;
    left: <?php echo (ceil((75*$config->getNode('widget_carousel','imageScale'))/2))."px";?>;
    width: 32px;
    height: 32px;
    cursor: pointer;
    background: transparent url(prev-vertical.png) no-repeat 0 0;
}

.jcarousel-skin-tango .jcarousel-prev-vertical:hover {
    background-position: 0 -32px;
}

.jcarousel-skin-tango .jcarousel-prev-vertical:active {
    background-position: 0 -64px;
}

.jcarousel-skin-tango .jcarousel-prev-disabled-vertical,
.jcarousel-skin-tango .jcarousel-prev-disabled-vertical:hover,
.jcarousel-skin-tango .jcarousel-prev-disabled-vertical:active {
    cursor: default;
    background-position: 0 -96px;
}

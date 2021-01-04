<?php header('Content-type: image/svg+xml'); ?>
<svg width="1266" height="600" xmlns="http://www.w3.org/2000/svg">
<defs>
</defs>
 <style>#svg_6 { 
    font-family: 'Rubik', sans-serif;
    font-size: <?php echo(strlen($ext)<4 ? "130" : "115"); ?>px;
    fill: #ffffff;
    text-align: center;
    font-weight: 600;
}</style>
 <g>
  <g id="svg_8">
   <path id="svg_1" fill="#E2E5E7" d="m472.187434,44c-17.6,0 -32,14.4 -32,32l0,448c0,17.6 14.4,32 32,32l320,0c17.6,0 32,-14.4 32,-32l0,-352l-128,-128l-224,0z"/>
   <path id="svg_2" fill="#B0B7BD" d="m728.187434,172l96,0l-128,-128l0,96c0,17.6 14.4,32 32,32z"/>
   <polygon id="svg_3" fill="#CAD1D8" points="824.1875020265579,268 728.1875020265579,172 824.1875020265579,172 "/>
   <path id="svg_4" fill="#<?php echo(isset($c) ? $c : "F15642"); ?>" d="m760.187434,460c0,8.8 -7.2,16 -16,16l-352,0c-8.8,0 -16,-7.2 -16,-16l0,-160c0,-8.8 7.2,-16 16,-16l352,0c8.8,0 16,7.2 16,16l0,160z"/>
   <path id="svg_7" fill="#CAD1D8" d="m744.187434,476l-304,0l0,16l304,0c8.8,0 16,-7.2 16,-16l0,-16c0,8.8 -7.2,16 -16,16z"/>
  </g>
  <g id="svg_5">
   <text id="svg_6" y="420" x="565" text-anchor="middle"><?php echo(strtoupper($ext)); ?></text>
  </g>
 </g>
</svg>
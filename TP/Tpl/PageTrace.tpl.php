<div id="think_page_trace" style="background:white;margin:6px;font-size:14px;border:1px dashed silver;padding:8px;clear:both;">
<fieldset id="querybox" style="margin:5px;">
<legend style="color:gray;font-weight:bold">页面Trace信息</legend>
<div style="overflow:auto;height:300px;text-align:left;">
<?php foreach ($_trace as $key=>$info){
echo $key.' : '.$info.'<br/>';
}?>
</div>
</fieldset>
</div>



<link rel="stylesheet" href="/adm/cropper/cropper.css" />

<script src="/adm/cropper/cropper.js"></script>


<div id="crop-modal">
<form action="/tiocms/file-menager/crop_save" method="post" class="form flex">
  <div class="left" style="padding:0px 50px 0px 0px;">
   
	<div class="form-row">  
	 <div class="form-label">
          <label><?=$post['btn_choose'];?></label>
     </div>
	 <div class="form-field">
	 <select name="size" id="size" onchange="ChangeSizeSelectCrop()">
	   <option value="16:10" selected="selected">460x300</option>
	   <option value="45:7">450x270</option>
	   <option value="16:16">16x16</option>
	   <option value="9:16">9x16</option>
	   <option value="16:9">16x9</option>
	   <option value="680:400">680x400</option>
	   <option value="1600:980">1600x980</option>
	 </select>
	 </div>
	</div> 
	<div class="form-row"> 
	  <div class="form-label">
          <label><?=$post['label_width'];?></label>
     </div>
	 <div class="form-field">
      <input id="width" type="text" value="16" onchange="ChangeSizeCrop()">
	  </div>
    </div>	
	<div class="form-row"> 
	<div class="form-label">
          <label><?=$post['label_height'];?></label>
     </div>
	 <div class="form-field">
      <input id="height" type="text" value="10" onchange="ChangeSizeCrop()">
	  </div>
    </div>
	<div class="form-row" id="output"> 
      <input type="hidden" name="output[width]" id="output_width" />
	  <input type="hidden" name="output[height]"  id="output_height" />
	  <input type="hidden" name="output[x]"  id="output_x" />
	  <input type="hidden" name="output[y]"  id="output_y" />
    </div> 
	 
  </div>
  <div class="right">
	<div id="contain" class="img-container">
	<img src="/image/<?=$post['file_path'];?>" id="crop-foto" />
	</div>
  </div>
  </form>
</div>

<script>
$( document ).ready(function() {
var $image = $('#crop-modal #crop-foto');

$image.cropper({
  aspectRatio: $('#crop-modal #width').val() / $('#crop-modal #height').val(),
  zoomable:false,
  minCropBoxWidth:50,
  crop: function(event) {
	$('#crop-modal #output_width').val(event.detail.width);
	$('#crop-modal #output_height').val(event.detail.height);
	$('#crop-modal #output_x').val(event.detail.x);
	if(event.detail.y<0) {
			$('#crop-modal #output_y').val(0);
	}
    else {			
		$('#crop-modal #output_y').val(event.detail.y);
	}
  }
});
});


	function ChangeSizeSelectCrop() {
		var size=$('#crop-modal #size').val();
		var size=size.split(':');
		$('#crop-modal #width').val(size[0]);
		$('#crop-modal #height').val(size[1]);
		ChangeSizeCrop();
	}	
	
	
	function ChangeSizeCrop() {
	var width=$('#crop-modal #width').val();
	var height=$('#crop-modal #height').val();
	
	
		var $image = $('#crop-modal #crop-foto');

		$image.cropper('destroy').cropper({
		  aspectRatio: $('#crop-modal #width').val() / $('#crop-modal #height').val(),
		  zoomable:false,
		  minCropBoxWidth:50,
		  crop: function(event) {
			$('#crop-modal #output_width').val(event.detail.width);
			$('#crop-modal #output_height').val(event.detail.height);
			$('#crop-modal #output_x').val(event.detail.x);
			if(event.detail.y<0) {
				$('#crop-modal #output_y').val(0);
			}
            else {			
			$('#crop-modal #output_y').val(event.detail.y);
			}
		  }
		});
	
	
	
	
	}

</script>



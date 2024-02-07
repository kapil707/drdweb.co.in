<div class="row">
    <div class="col-xs-12" style="margin-bottom:5px;">
		<a href="<?php echo base_url(); ?>admin/manage_chemist_map/view">
            <button type="submit" class="btn btn-info">
            	View all Users
            </button>
        </a>
   	</div>
	<div class="col-xs-12">
        <div class="table-responsive">
			Total Records : <?php echo $count_records ?> / <?= count($result) + ($_GET["pg"]) ?>
			<?php /*<table class="table table-striped table-bordered table-hover dataTables-example">*/ ?>
			<table class="table table-striped table-bordered table-hover">
                <thead>
                <thead>
                    <tr>
                    	<th>
                        	Sno.
                        </th>
						<th>
							User
						</th>
						<th>
							Date / Time
						</th>
						<th>
							Version
						</th>
						<th>
							Logout
						</th>
						<th>
							Clear Data
						</th>
						<th>
							View On Map
						</th>
                    </tr>
                </thead>
				<tbody>
                <?php
				$i=1;
                foreach ($result as $row)
                {
					?>
                    <tr id="row_<?= $row->id; ?>">
                    	<td>
                        	<?= $i++; ?>
                        </td>
						<td>
							<?php if($row->user_type=="chemist") { ?>
							<?php
							$row1 = $this->db->query("select name from tbl_acm where altercode='$row->chemist_id'")->row();
							?>
							<?= $row1->name; ?> (<?= $row->chemist_id; ?>) - chemist
							<?php } ?>
							
							<?php if($row->user_type=="sales") { ?>
							<?php
							$row1 = $this->db->query("select * from tbl_users where customer_code='$row->chemist_id'")->row();
							?>
							<?= $row1->firstname; ?> <?= $row1->lastname; ?> (<?= $row->chemist_id; ?>) - SalesMan
							<?php } ?>
						</td>
						<td>
							<?= date("d-M-y h:i a ",$row->time); ?>
                        </td>
						<td>
							<?= $row->versioncode; ?>
                        </td>
						<td>
							<?php if($row->logout==0) { ?>
							<form class="form-horizontal" role="form" method="post" enctype="multipart/form-data" onsubmit="return confirm('Do you really want to Logout?');">
							<input type="hidden" name="id1" value="<?=$row->id; ?>"/>
							<button type="submit" class="btn btn-info submit_button" name="Submit1">
							<i class="ace-icon fa fa-check bigger-110"></i>
							Logout
							</button>
							</form>
							<?php } else { ?>
							Logout Ready To Process
							<?php } ?>
                        </td>
						<td>
							<?php if($row->clear_database==0) { ?>
							<form class="form-horizontal" role="form" method="post" enctype="multipart/form-data" onsubmit="return confirm('Do you really want to submit the Clear Database?');">
							<input type="hidden" name="id2" value="<?=$row->id; ?>"/>
							<button type="submit" class="btn btn-info submit_button" name="Submit2">
							<i class="ace-icon fa fa-check bigger-110"></i>
							Clear Database
							</button>
							</form>
							<?php } else { ?>
							Clear Database Ready To Process
							<?php } ?>
                        </td>
						<td>
							<a href="view2/<?= $row->chemist_id; ?>"  class="btn btn-info submit_button">
							<i class="ace-icon fa fa-check bigger-110"></i>
							View On Map
							</a>
						</td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<ul class="pagination">
    <li class="startcss1 disabled"><span class="">First</span></li>
    <li class="startcss2 disabled"><span class="">Prev</span></li>
<?php
$endcss = "class='disabled'";
for($i=0;$i<($count_records);$i= 100 + $i){
	$showone = 0;
	if($i==0 && $per_page!=0){


		?>
		<li class="<?php if(ceil($per_page/100)==ceil($i/100)){ echo "active";} ?>"><a rel="noopener" href="javascript:void(0);" class="for_per" ford="<?php echo ceil($i/100) ?>" per_page="<?php echo ceil($i/100)*100 ?>" ><?php echo ceil($i/100)+1 ?></a></li>
		<script>
			$(".startcss1").removeClass("disabled");
			$(".startcss2").removeClass("disabled");

			$(".startcss1").html('<a rel="noopener" href="javascript:void(0)" class="fast" per_page="0">First</a>');
			$(".startcss2").html('<a rel="noopener" href="javascript:void(0)" class="previous" per_page="<?php echo $per_page - 100 ?>">Prev</a>');
		</script>
		<?php
		$showone = 1;
	}
	
	if($i==$per_page-100 && $showone == 0){
		?>
		<li class="<?php if(ceil($per_page/100)==ceil($i/100)){ echo "active";} ?>"><a rel="noopener" href="javascript:void(0);" class="for_per" ford="<?php echo ceil($i/100) ?>" per_page="<?php echo ceil($i/100)*100 ?>" ><?php echo $lastval = ceil($i/100)+1 ?></a></li>
		<?php
	}
	
	if($i==$per_page){
		?>
		<li class="<?php if(ceil($per_page/100)==ceil($i/100)){ echo "active";} ?>"><a rel="noopener" href="javascript:void(0);" class="for_per" ford="<?php echo ceil($i/100) ?>" per_page="<?php echo ceil($i/100)*100 ?>" ><?php echo $lastval1 = ceil($i/100)+1 ?></a></li>
		<?php
	}
	
	if($i==$per_page+100){
		$endcss = "";
		?>
		<li class="<?php if(ceil($per_page/100)==ceil($i/100)){ echo "active";} ?>"><a rel="noopener" href="javascript:void(0);" class="for_per" ford="<?php echo ceil($i/100) ?>" per_page="<?php echo ceil($i/100)*100 ?>" ><?php echo $lastval2 = ceil($i/100)+1 ?></a></li>
		<?php
	}
}

if(ceil($i/100)==$lastval || ceil($i/100)==$lastval1 || ceil($i/100)==$lastval2){}else{
	?>
	<li class="<?php if(ceil($per_page/100)==ceil($i/100)){ echo "active";} ?>"><a rel="noopener" href="javascript:void(0);" class="for_per" ford="<?php echo ceil($i/100) ?>" per_page="<?php echo ceil($count_records/100)*100 - 100; ?>" ><?php echo ceil($i/100)?></a></li>
	<?php
}
$last = $next = '';

if($endcss == ''){
	$last = 'last';
	$next = 'next';
}
       /*echo $per_page;
    for($i=0;$i<ceil($count_records/100);$i++){

  if($i<=ceil($per_page/100)+10&&$i>=ceil($per_page/100)){
       if($i==0){ ?>
      <li class="<?php if(ceil($per_page/100)==$i){ echo "active";} ?>"><a rel="noopener" href="javascript:void(0);" class="for_per" ford="<?php echo $i ?>" per_page="<?php echo "0"; ?>" ><?php echo "1" ?>xx</a></li>
      <?php  }else{ ?>
     <li class="<?php if(ceil($per_page/100)==$i){ echo "active";} ?>"><a rel="noopener" href="javascript:void(0);" class="for_per"  ford="<?php echo $i ?>" per_page="<?php echo $i*100; ?>" ><?php echo $i+1 ?>yy</a></li>
     <?php   }
    	?>
   
    
    <?php } ?>
    


    <?php }*/ ?>
    
    <li <?php /* class="<?php if($per_page >= $count_records){ echo 'disabled'; } ?>"*/ ?> <?=$endcss; ?>>
        <a rel="noopener" href="javascript:void(0);" class="<?= $next?>" per_page="<?php echo $per_page + 100; ?>">Next</a>
    </li>
    <li <?=$endcss; ?>><a rel="noopener" href="javascript:void(0)" class="<?= $last?>" per_page="<?php echo ceil($count_records/100)*100 - 100; ?>">Last</a></li>
</ul>
        </div>
    </div>
</div>
<script>
$(".for_per,.previous,.next,.last,.fast").click(function(){
	for_per=$(this).attr("ford");
	per_page=$(this).attr("per_page");
	url="<?php echo base_url() ?>admin/<?= $Page_name; ?>/view?pg="+per_page+"&vdt=<?php echo $vdt1 ?>&submit=submit";
	window.location.href=url;
  
  });
  $(".page_btn").click(function(){
  	//alert("ok");
 // per_page="<?php echo $per_page ?>";
// for_per=$(this).attr("ford");
  //per_page=$(this).attr("per_page");
 page_no=$('.page_no').val();

 bysearch=0;
 if(page_no!=""&&page_no!=0){
 	for_per=page_no-1;
  per_page=for_per*100;
  bysearch=1;
 }
 	url="<?php echo base_url() ?>admin/<?= $Page_name; ?>/view?pg="+per_page+"&vdt=<?php echo $vdt1 ?>&submit=submit";
	window.location.href=url;
  });
</script>
<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header text-center">
                <div class="dropdown profile-element"> <span>
                	<?php 
					if($this->session->userdata("user_type") !=""){ ?>
                    <img alt="image" class="img-circle" src="<?= base_url()?>uploads/manage_users/photo/<?= $this->session->userdata("image") ?>" width="100" />
                    <?php } else { 
					?>
                    <img alt="image" class="img-circle" src="<?= base_url()?>uploads/manage_profile/photo/unapproved.jpg" width="100" />
                    <?php
					}?>
                     </span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                    <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold"><?= $this->session->userdata("name"); ?></strong>
                     </span> <span class="text-muted text-xs block"><?php $user_type1 = $this->session->userdata("user_type"); ?>
                     <?php echo str_replace("_"," ",$user_type1); ?><b class="caret"></b></span> </span> </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="<?= base_url()?>admin/dashboard/edit_profile">Edit Profile</a></li>
                        <?php /* <li><a href="contacts.html">Contacts</a></li>
                        <li><a href="mailbox.html">Mailbox</a></li>
                       	<li class="divider"></li> */ ?>
                        <li><a href="<?= base_url()?>admin/logout">Logout</a></li>
                    </ul>
                </div>
                <div class="logo-element">
                    DRD
                </div>
            </li>
            <li <?php if($Page_menu=="dashboard") { ?> class="active" <?php } ?>>
                <a href="<?= base_url()?>admin/dashboard">
                <i class="fa fa-th-large"></i>
                <span class="nav-label">Dashboard</span>
                </a>
            </li>
			
			<?php
			$user_type = $this->session->userdata("user_type");			
			$menu = $this->db->query("select DISTINCT tbl_permission_settings.page_type,sorting_order from tbl_permission_settings,tbl_permission_page where tbl_permission_settings.page_type=tbl_permission_page.page_type and user_type='$user_type' and (tbl_permission_settings.page_type='manage_android_info' or tbl_permission_settings.page_type='manage_allbiker_map' or tbl_permission_settings.page_type='manage_android') GROUP BY tbl_permission_settings.page_type,sorting_order order by sorting_order asc")->result();
			if(!empty($menu)){
			?>
			<li <?php if($Page_menu=="manage_android_info" || $Page_menu=="manage_allbiker_map" || $Page_menu=="manage_android") { ?> class="active" <?php } ?>>
				<a href="#">
					<span class="nav-label">
						<i class="fa fa-th-large"></i>
						Manage Android
					</span><span class="fa arrow"></span>
				</a>
				<ul class="nav nav-second-level collapse">
					<?php 
					foreach($menu as $mymenu){
					if($mymenu->page_type=="manage_android_info") { ?>
					<li><a href="<?= base_url()?>admin/manage_android_info/view">Android Users</a>
					</li>
					<?php } 
					if($mymenu->page_type=="manage_allbiker_map") { ?>
					<li><a href="<?= base_url()?>admin/manage_allbiker_map/view">Rider Users Treck</a>
					</li>
					<?php } 
					if($mymenu->page_type=="manage_android") { ?>
					<li><a href="<?= base_url()?>admin/manage_android/add/android_mobile">Android Mobile</a>
					</li>
					<li><a href="<?= base_url()?>admin/manage_android/add/android_email">Android Email</a>
					</li>
					<li><a href="<?= base_url()?>admin/manage_android/add/android_whatsapp">Android Whatsapp</a>
					</li>
					<li><a href="<?= base_url()?>admin/manage_android/add/force_update_title">Android Force Update Title</a>
					</li>
					<li><a href="<?= base_url()?>admin/manage_android/add/force_update_message">Android Force Update Message</a>
					</li>
					<li><a href="<?= base_url()?>admin/manage_android/add/force_update">Android Force Update</a>
					</li>
					<li><a href="<?= base_url()?>admin/manage_android/add/android_versioncode">Android Version</a>
					</li>
					<?php } 
					}?>
				</ul>
			</li>
			<?php } 
			$menu = $this->db->query("select DISTINCT tbl_permission_settings.page_type,sorting_order from tbl_permission_settings,tbl_permission_page where tbl_permission_settings.page_type=tbl_permission_page.page_type and user_type='$user_type' and (tbl_permission_settings.page_type='manage_notification' or tbl_permission_settings.page_type='manage_notification_broadcast' or tbl_permission_settings.page_type='manage_notification_email' or tbl_permission_settings.page_type='manage_notification_whatsapp' or tbl_permission_settings.page_type='manage_notification_whatsapp_group' or tbl_permission_settings.page_type='manage_notification_email_setting' or tbl_permission_settings.page_type='manage_notification_email_cc') GROUP BY tbl_permission_settings.page_type,sorting_order order by sorting_order asc")->result();
			if(!empty($menu)){
			?>
			<li <?php foreach($menu as $mymenu){ if($Page_menu==$mymenu->page_type) { ?> class="active" <?php } }?>>
				<a href="#">
					<span class="nav-label">
						<i class="fa fa-th-large"></i>
						Manage Notification
					</span><span class="fa arrow"></span>
				</a>
				<ul class="nav nav-second-level collapse">
				<?php 
				foreach($menu as $mymenu){
					$row = $this->db->query("select page_title from  tbl_permission_page where page_type='$mymenu->page_type'")->row();
					?>
					<li><a href="<?= base_url()?>admin/<?php echo $mymenu->page_type ?>/view" <?php if($Page_menu==$mymenu->page_type) { ?> class="active" <?php }  ?>><?php echo $row->page_title;?></a>
					</li> 
				<?php } ?>
				</ul>
			</li>
			<?php } 
			$menu = $this->db->query("select DISTINCT tbl_permission_settings.page_type,sorting_order from tbl_permission_settings,tbl_permission_page where tbl_permission_settings.page_type=tbl_permission_page.page_type and user_type='$user_type' and (tbl_permission_settings.page_type='manage_bank_statment' or tbl_permission_settings.page_type='manage_bank_chemist' or tbl_permission_settings.page_type='manage_bank_whatsapp' or tbl_permission_settings.page_type='manage_bank_sms' or tbl_permission_settings.page_type='manage_bank_processing') GROUP BY tbl_permission_settings.page_type,sorting_order order by sorting_order asc")->result();
			if(!empty($menu)){
			?>
			<li <?php foreach($menu as $mymenu){ if($Page_menu==$mymenu->page_type) { ?> class="active" <?php } }?>>
				<a href="#">
					<span class="nav-label">
						<i class="fa fa-th-large"></i>
						Manage Bank
					</span><span class="fa arrow"></span>
				</a>
				<ul class="nav nav-second-level collapse">
				<?php 
				foreach($menu as $mymenu){
					$row = $this->db->query("select page_title from  tbl_permission_page where page_type='$mymenu->page_type'")->row();
					?>
					<li><a href="<?= base_url()?>admin/<?php echo $mymenu->page_type ?>/view" <?php if($Page_menu==$mymenu->page_type) { ?> class="active" <?php }  ?>><?php echo $row->page_title;?></a>
					</li> 
				<?php } ?>
				</ul>
			</li>
			<?php } 
			$menu = $this->db->query("select DISTINCT tbl_permission_settings.page_type,sorting_order from tbl_permission_settings,tbl_permission_page where tbl_permission_settings.page_type=tbl_permission_page.page_type and user_type='$user_type' and (tbl_permission_settings.page_type='manage_user_chemist' or tbl_permission_settings.page_type='manage_user_corporate' or tbl_permission_settings.page_type='manage_user_salesman' or tbl_permission_settings.page_type='manage_user_chemist_request' or tbl_permission_settings.page_type='manage_user_active') GROUP BY tbl_permission_settings.page_type,sorting_order order by sorting_order asc")->result();
			if(!empty($menu)){
			?>
			<li <?php foreach($menu as $mymenu){ if($Page_menu==$mymenu->page_type) { ?> class="active" <?php } }?>>
				<a href="#">
					<span class="nav-label">
						<i class="fa fa-th-large"></i>
						Manage User
					</span><span class="fa arrow"></span>
				</a>
				<ul class="nav nav-second-level collapse">
				<?php 
				foreach($menu as $mymenu){
					$row = $this->db->query("select page_title from  tbl_permission_page where page_type='$mymenu->page_type'")->row();
					?>
					<li><a href="<?= base_url()?>admin/<?php echo $mymenu->page_type ?>/view" <?php if($Page_menu==$mymenu->page_type) { ?> class="active" <?php }  ?>><?php echo $row->page_title;?></a>
					</li> 
				<?php } ?>
				</ul>
			</li>
			<?php } 
			$menu = $this->db->query("select DISTINCT tbl_permission_settings.page_type,sorting_order from tbl_permission_settings,tbl_permission_page where tbl_permission_settings.page_type=tbl_permission_page.page_type and user_type='$user_type' and (tbl_permission_settings.page_type='manage_medicine' or tbl_permission_settings.page_type='manage_company_discount' or tbl_permission_settings.page_type='manage_medicine_image' or tbl_permission_settings.page_type='manage_medicine_info2' or tbl_permission_settings.page_type='manage_top_search' or tbl_permission_settings.page_type='manage_top_search_by_chemist' or tbl_permission_settings.page_type='manage_medicine_use') GROUP BY tbl_permission_settings.page_type,sorting_order order by sorting_order asc")->result();
			if(!empty($menu)){
			?>
			<li <?php foreach($menu as $mymenu){ if($Page_menu==$mymenu->page_type) { ?> class="active" <?php } }?>>
				<a href="#">
					<span class="nav-label">
						<i class="fa fa-th-large"></i>
						Manage Medicine
					</span><span class="fa arrow"></span>
				</a>
				<ul class="nav nav-second-level collapse">
				<?php 
				foreach($menu as $mymenu){
					$row = $this->db->query("select page_title from  tbl_permission_page where page_type='$mymenu->page_type'")->row();
					?>
					<li><a href="<?= base_url()?>admin/<?php echo $mymenu->page_type ?>/view" <?php if($Page_menu==$mymenu->page_type) { ?> class="active" <?php }  ?>><?php echo $row->page_title;?></a>
					</li> 
				<?php } ?>
				</ul>
			</li>
			<?php }
			$menu = $this->db->query("select DISTINCT tbl_permission_settings.page_type,sorting_order from tbl_permission_settings,tbl_permission_page where tbl_permission_settings.page_type=tbl_permission_page.page_type and user_type='$user_type' and (tbl_permission_settings.page_type='manage_master' or tbl_permission_settings.page_type='manage_master_attendance' or tbl_permission_settings.page_type='manage_master_delivery' or tbl_permission_settings.page_type='manage_master_delivery_done' or tbl_permission_settings.page_type='manage_master_firebase_token' or tbl_permission_settings.page_type='manage_master_meter' or tbl_permission_settings.page_type='manage_master_tracking') GROUP BY tbl_permission_settings.page_type,sorting_order order by sorting_order asc")->result();
			if(!empty($menu)){
			?>
			<li <?php foreach($menu as $mymenu){ 
			if(strtolower($Page_menu)==strtolower($mymenu->page_type)) { ?> class="active" <?php } }?>>
				<a href="#">
					<span class="nav-label">
						<i class="fa fa-th-large"></i>
						Manage Master App
					</span><span class="fa arrow"></span>
				</a>
				<ul class="nav nav-second-level collapse">
				<?php 
				foreach($menu as $mymenu){
					$row = $this->db->query("select page_title from  tbl_permission_page where page_type='$mymenu->page_type'")->row();
					?>
					<li><a href="<?= base_url()?>admin/<?php echo $mymenu->page_type ?>/view" <?php if(strtolower($Page_menu)==strtolower($mymenu->page_type)) { ?> class="active" <?php } ?>><?php echo $row->page_title;?></a>
					</li> 
				<?php } ?>
				</ul>
			</li>
			<?php } 
			$menu = $this->db->query("select DISTINCT tbl_permission_settings.page_type,sorting_order from tbl_permission_settings,tbl_permission_page where tbl_permission_settings.page_type=tbl_permission_page.page_type and user_type='$user_type' and (tbl_permission_settings.page_type='manage_import' or tbl_permission_settings.page_type='manage_fail_log' or tbl_permission_settings.page_type='manage_stock_available' or tbl_permission_settings.page_type='manage_stock_available_by_chemist' or tbl_permission_settings.page_type='manage_stock_price_low' or tbl_permission_settings.page_type='manage_stock_low' or tbl_permission_settings.page_type='manage_daily_report') GROUP BY tbl_permission_settings.page_type,sorting_order order by sorting_order asc")->result();
			if(!empty($menu)){
			?>
			<li <?php foreach($menu as $mymenu){ if($Page_menu==$mymenu->page_type) { ?> class="active" <?php } }?>>
				<a href="#">
					<span class="nav-label">
						<i class="fa fa-th-large"></i>
						Manage Stock
					</span><span class="fa arrow"></span>
				</a>
				<ul class="nav nav-second-level collapse">
				<?php 
				foreach($menu as $mymenu){
					$row = $this->db->query("select page_title from  tbl_permission_page where page_type='$mymenu->page_type'")->row();
					?>
					<li><a href="<?= base_url()?>admin/<?php echo $mymenu->page_type ?>/view" <?php if($Page_menu==$mymenu->page_type) { ?> class="active" <?php } ?>><?php echo $row->page_title;?></a>
					</li> 
				<?php } ?>
				</ul>
			</li>
			<?php }
			$menu = $this->db->query("select DISTINCT tbl_permission_settings.page_type,sorting_order from tbl_permission_settings,tbl_permission_page where tbl_permission_settings.page_type=tbl_permission_page.page_type and user_type='$user_type' and (tbl_permission_settings.page_type='manage_order' or tbl_permission_settings.page_type='manage_order_max') GROUP BY tbl_permission_settings.page_type,sorting_order order by sorting_order asc")->result();
			if(!empty($menu)){
			?>
			<li <?php foreach($menu as $mymenu){ if($Page_menu==$mymenu->page_type) { ?> class="active" <?php } }?>>
				<a href="#">
					<span class="nav-label">
						<i class="fa fa-th-large"></i>
						Manage Order
					</span><span class="fa arrow"></span>
				</a>
				<ul class="nav nav-second-level collapse">
				<?php 
				foreach($menu as $mymenu){
					$row = $this->db->query("select page_title from  tbl_permission_page where page_type='$mymenu->page_type'")->row();
					?>
					<li><a href="<?= base_url()?>admin/<?php echo $mymenu->page_type ?>/view" <?php if($Page_menu==$mymenu->page_type) { ?> class="active" <?php } ?>><?php echo $row->page_title;?></a>
					</li> 
				<?php } ?>
				</ul>
			</li>
			<?php }
			$menu = $this->db->query("select DISTINCT tbl_permission_settings.page_type,sorting_order from tbl_permission_settings,tbl_permission_page where tbl_permission_settings.page_type=tbl_permission_page.page_type and user_type='$user_type' and ( tbl_permission_settings.page_type='manage_invoice') GROUP BY tbl_permission_settings.page_type,sorting_order order by sorting_order asc")->result();
			if(!empty($menu)){
			?>
			<li <?php foreach($menu as $mymenu){ if($Page_menu==$mymenu->page_type) { ?> class="active" <?php } }?>>
				<a href="#">
					<span class="nav-label">
						<i class="fa fa-th-large"></i>
						Manage Invoice
					</span><span class="fa arrow"></span>
				</a>
				<ul class="nav nav-second-level collapse">
				<?php 
				foreach($menu as $mymenu){
					$row = $this->db->query("select page_title from  tbl_permission_page where page_type='$mymenu->page_type'")->row();
					?>
					<li><a href="<?= base_url()?>admin/<?php echo $mymenu->page_type ?>/view" <?php if($Page_menu==$mymenu->page_type) { ?> class="active" <?php } ?>><?php echo $row->page_title;?></a>
					</li> 
				<?php } ?>
				</ul>
			</li>
			<?php }
			$menu = $this->db->query("select DISTINCT tbl_permission_settings.page_type,sorting_order from tbl_permission_settings,tbl_permission_page where tbl_permission_settings.page_type=tbl_permission_page.page_type and user_type='$user_type' and (tbl_permission_settings.page_type='manage_home' or  tbl_permission_settings.page_type='manage_medicine_menu' or tbl_permission_settings.page_type='manage_slider' or tbl_permission_settings.page_type='manage_item' or tbl_permission_settings.page_type='manage_item_category' or tbl_permission_settings.page_type='manage_company_division' or tbl_permission_settings.page_type='manage_company_division_category') GROUP BY tbl_permission_settings.page_type,sorting_order order by sorting_order asc")->result();
			if(!empty($menu)){?>
				<li <?php if($Page_menu=="manage_home" || $Page_menu=="manage_medicine_menu" || $Page_menu=="manage_slider" || $Page_menu=="manage_item" || $Page_menu=="manage_item_category" || $Page_menu=="manage_company_division" || $Page_menu=="manage_company_division_category") { ?> class="active" <?php } ?>>
				<a href="#">
					<span class="nav-label">
						<i class="fa fa-th-large"></i>
							Manage Home
						</span>
					<span class="fa arrow"></span>
				</a>
				<ul class="nav nav-second-level collapse">
					<?php
					foreach($menu as $mymenu){
						$row = $this->db->query("select page_title from  tbl_permission_page where page_type='$mymenu->page_type'")->row();
						?>
						<li>
							<a href="<?= base_url()?>admin/<?php echo $mymenu->page_type ?>/view" <?php if($Page_menu==$mymenu->page_type) { ?> class="active" <?php }  ?>><?php echo $row->page_title;?></a>
						</li>
					<?php } ?>
				</ul>
			</li>
			<?php }
			$menu = $this->db->query("select DISTINCT tbl_permission_settings.page_type,sorting_order from tbl_permission_settings,tbl_permission_page where tbl_permission_settings.page_type=tbl_permission_page.page_type and user_type='$user_type' and (tbl_permission_settings.page_type='manage_website' or tbl_permission_settings.page_type='manage_email') GROUP BY tbl_permission_settings.page_type,sorting_order order by sorting_order asc")->result();
			if(!empty($menu)){
			?>
			<li <?php if($Page_menu=="manage_website" || $Page_menu=="manage_email") { ?> class="active" <?php } ?>>
				<a href="#">
					<span class="nav-label">
						<i class="fa fa-th-large"></i>
						Manage Settings
					</span><span class="fa arrow"></span>
				</a>	
				<ul class="nav nav-second-level collapse">
				<?php 
				foreach($menu as $mymenu){ ?>
					<?php if($mymenu->page_type=="manage_website") { ?>
					<li><a href="<?= base_url()?>admin/manage_website/add/title">Title</a>
					</li>
					<li><a href="<?= base_url()?>admin/manage_website/add/title2">Title2</a>
					</li>
					<li><a href="<?= base_url()?>admin/manage_website/add/logo">Logo</a>
					</li>
					<li><a href="<?= base_url()?>admin/manage_website/add/icon">Icon</a>
					</li>
					<li><a href="<?= base_url()?>admin/manage_website/add/defaultpassword">Default Password</a>
					</li>
					<li><a href="<?= base_url()?>admin/manage_website/add/mapapikey">Map Api Key</a>
					</li>
					<li><a href="<?= base_url()?>admin/manage_website/add/whatsapp_deviceid">Whatsapp Deviceid</a>
					</li>
					<li><a href="<?= base_url()?>admin/manage_website/add/whatsapp_key">Whatsapp Key</a>
					</li>
					<li><a href="<?= base_url()?>admin/manage_website/add/whatsapp_group1">Whatsapp Group1</a>
					</li>
					<li><a href="<?= base_url()?>admin/manage_website/add/whatsapp_group2">Whatsapp Group2</a>
					</li>
					<li><a href="<?= base_url()?>admin/manage_website/add/whatsapp_group3">Whatsapp Group3</a>
					</li>
					<li><a href="<?= base_url()?>admin/manage_website/add/under_construction">Under Construction</a>
					</li>
					<li><a href="<?= base_url()?>admin/manage_website/add/under_construction_message">Under Construction Message</a>
					</li>
					<li><a href="<?= base_url()?>admin/manage_website/add/medicine_icon">Medicine icon</a>
					</li>
					<li><a href="<?= base_url()?>admin/manage_website/add/corporate_url">Corporate Url</a>
					</li>
					<li><a href="<?= base_url()?>admin/manage_website/add/corporate_url_local">Corporate Url Local</a>
					</li>
					<li><a href="<?= base_url()?>admin/manage_website/add/default_title_text">Default Title Text</a>
					</li>
					<li><a href="<?= base_url()?>admin/manage_website/add/email_footer_text">Email Footer Text</a>
					</li>
					<li><a href="<?= base_url()?>admin/manage_website/add/whatsapp_footer_text">WhatsApp Footer Text</a>
					</li>
					<li><a href="<?= base_url()?>admin/manage_website/add/login_details_text">Login Details Text</a>
					</li>
					<li><a href="<?= base_url()?>admin/manage_website/add/default_place_order_text">Default Place Order Text</a>
					</li>
					<li><a href="<?= base_url()?>admin/manage_website/add/place_order_message">Place Order Message</a>
					</li>
					<li><a href="<?= base_url()?>admin/manage_website/add/terms_of_services">Terms of services</a>
					</li>
					<li><a href="<?= base_url()?>admin/manage_website/add/privacy_policy">Privacy policy</a>
					</li>
					<?php }
					if($mymenu->page_type=="manage_email") { ?>
					<li><a href="<?= base_url()?>admin/manage_email/view">Email Setting</a>
					</li>
					<?php } }?>
				</ul>
			</li>
			<?php } 
			$menu = $this->db->query("select DISTINCT tbl_permission_settings.page_type,sorting_order from tbl_permission_settings,tbl_permission_page where tbl_permission_settings.page_type=tbl_permission_page.page_type and user_type='$user_type' and (tbl_permission_settings.page_type='profile_management' or tbl_permission_settings.page_type='manage_users' or tbl_permission_settings.page_type='manage_user_type') GROUP BY tbl_permission_settings.page_type,sorting_order order by sorting_order asc")->result();
			if(!empty($menu)){ ?>
			<li <?php if($Page_menu=="profile_management" || $Page_menu=="manage_users" || $Page_menu=="manage_user_type") { ?> class="active" <?php } ?>>
				<a href="#">
					<span class="nav-label">
						<i class="fa fa-th-large"></i>
						Manage Othres
					</span><span class="fa arrow"></span>
				</a>
				<ul class="nav nav-second-level collapse">	
				<?php 
				foreach($menu as $mymenu){
					if($mymenu->page_type=="profile_management") { ?>			
					<li><a href="<?= base_url()?>admin/profile_management/permission_settings">Profile Management</a>
					</li>
					<?php }
					if($mymenu->page_type=="manage_users") { ?>
					<li><a href="<?= base_url()?>admin/manage_users/view">Users</a></li>
					<?php }
					if($mymenu->page_type=="manage_user_type") { ?>
					<li><a href="<?= base_url()?>admin/manage_user_type/view">User Type</a>
					</li>
					<?php } } ?>
					<li><a href="<?= base_url()?>admin/logout">Logout</a>
					</li>
				</ul>
			</li>
			<?php } 
			$menu = $this->db->query("select DISTINCT tbl_permission_settings.page_type,sorting_order from tbl_permission_settings,tbl_permission_page where tbl_permission_settings.page_type=tbl_permission_page.page_type and user_type='$user_type' and (tbl_permission_settings.page_type='manage_website_seo' or tbl_permission_settings.page_type='manage_seo') GROUP BY tbl_permission_settings.page_type,sorting_order order by sorting_order asc")->result();
			if(!empty($menu)){
			?>
			<li <?php if($Page_menu=="manage_website_seo" || $Page_menu=="manage_seo") { ?> class="active" <?php } ?>>
				<a href="#">
					<span class="nav-label">
						<i class="fa fa-th-large"></i>
						Manage Settings Seo
					</span><span class="fa arrow"></span>
				</a>	
				<ul class="nav nav-second-level collapse">
				<?php 
				foreach($menu as $mymenu){ ?>
					<?php if($mymenu->page_type=="manage_website_seo") { ?>
					<li><a href="<?= base_url()?>admin/manage_website_seo/add/seo_author">Author</a>
					</li>
					<li><a href="<?= base_url()?>admin/manage_website_seo/add/seo_description">Description</a>
					</li>
					<li><a href="<?= base_url()?>admin/manage_website_seo/add/seo_keywords">Keywords</a>
					</li>
					<li><a href="<?= base_url()?>admin/manage_website_seo/add/seo_google">Google Tag</a>
					</li>
					<?php } ?>
					<?php if($mymenu->page_type=="manage_seo") { ?>
					<li><a href="<?= base_url()?>admin/manage_seo">Seo Pages</a>
					<?php } ?>
					</li>
				<?php } ?>
				</ul>
			</li>
			<?php } ?>
		</ul>
    </div>
</nav>
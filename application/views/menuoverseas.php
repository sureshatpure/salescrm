<div class="navbar" id="topMenus">
    <div class="navbar-inner" id="nav-inner">
        <div class="menuBar row-fluid">
            <!-- new -->
            <div class="span3 row-fluid">
                <!-- start of widget --> 
                <div id='jqxWidget' style='height: 32px;'>
                    <div id='jqxMenu' style='visibility: hidden; margin-left: 5px;'>
                        <ul>
                            <li><a href="<?= base_url() ?>overseas/">Home</a></li>
                            <li><a href="<?= base_url() ?>overseas/customerinfo">View</a></li>
                            <li><a href="<?= base_url() ?>admin/logout">Logout</a></li>

                       </ul>
                    </div> 
                    <!-- end  of jqwidget -->
                </div>
                <!-- new end -->

                <div class="span3 row-fluid" id="headerLinksmenu">			
                    <span class="pull-right headerLinksContainer">
                        <span class="dropdown span settingIcons ">
                            <a id="menubar_item_right_LBL_FEEDBACK" class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <img src="<?= base_url() ?>public/images/info.png" alt="Feedback" title="Feedback">
                            </a>

                        </span>
                        <span class="dropdown span settingIcons "><a id="menubar_item_right_LBL_CRM_SETTINGS" class="dropdown-toggle" data-toggle="dropdown" href="#"><img src="<?= base_url() ?>public/images/setting.png" alt="CRM Settings" title="CRM Settings"></a>
                            <ul class="dropdown-menu pull-right">
                                <li>
                                    <a target="" id="menubar_item_right_LBL_CRM_SETTINGS1" href="#">CRM Settings</a>
                                </li>
                            </ul>
                        </span>
                        <span class="dropdown span">
                            <span class="dropdown-toggle row-fluid" data-toggle="dropdown">
                                <a id="menubar_item_right_Administrator" class="userName textOverflowEllipsis span" title="User" style="font-size: 13px !important;line-height: 1.4; color:white; font-weight:bold;"><?php echo $this->session->userdata['username'] . "-" . $this->session->userdata['user_id'] . "-" . $this->session->userdata['reportingto']; ?> <i class="caret"></i> 
                                </a> 
                            </span>
                            <ul class="dropdown-menu pull-right">
                                <li>
                                    <a target="" id="menubar_item_right_LBL_MY_PREFERENCES" href="#">My Preferences</a>
                                </li>
                                <li class="divider">&nbsp;
                                </li>
                                <li>
                                    <a target="" id="menubar_item_right_LBL_SIGN_OUT" href="<?= base_url() ?>admin/logout">Sign Out</a>
                                </li>
                            </ul>
                        </span>
                    </span>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    </div>



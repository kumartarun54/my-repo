<?php $this->load->view('include/header2');?>
<link rel="stylesheet" href="<?php echo base_url(); ?>dist/css/kcbase.css">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header"></section>

    <!-- Main content -->
    <section class="content branches-content pb-3">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header ">
                            <h3 class="card-title"><a href="<?php echo base_url(); ?>">Home / <a href="<?php echo base_url('user/list'); ?>">User</a>
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <nav class="navbar navbar-expand p-0">
                                <ul class="nav nav-tabs mb-0 br-0 pl-0" role="tablist">
                                  <li class="nav-item"><a class="nav-link " href="<?php echo base_url('user/info/'.$user_id) ?>">Info</a></li>
                                  <li class="nav-item "><a class="nav-link" href="<?php echo base_url('user/course/'.$user_id); ?>">Courses</a></li>
                                  <li class="nav-item "><a class="nav-link active" href="<?php echo base_url('user/group/'.$user_id); ?>">Group</a></li>
                                  <li class="nav-item"><a class="nav-link" href="<?php echo base_url('user/branches/'.$user_id); ?>">Branches</a></li>
                                  <li class="nav-item"><a class="nav-link" href="<?php echo base_url('user/files/'.$user_id); ?>">Files</a></li>
                                </ul>
                                <ul class="navbar-nav ml-auto pb-2 mobile-none">
                                  <li>
                                    <div class="btn-group">
                                      <a href="#" class="btn btn-primary">Profile</a>
                                      <a href="#" class="btn btn-default">Progress</a>
                                      <a href="#" class="btn btn-default">Infographic</a>
                                    </div>
                                     
                                  </li>
                                </ul>
                              </nav>
                            <!-- branch not found end -->

                            <div class="dropdown-divider"></div>
                                  <div class="table-responsive">
                                    <?php if(!empty($results)){?>
                                  <table id="tl-list-user-groups" class="table grid-striped  dataTable no-footer" cellpadding="0" cellspacing="0" border="0" role="grid" aria-describedby="tl-list-user-groups_info" style="width: 100%;">
                                           <thead>
                                            <tr role="row">
                                                <th class="tl-align-left sorting" tabindex="0" aria-controls="tl-list-user-groups" rowspan="1" colspan="1" aria-label="Group" style="width: 574px;">Group</th>
                                                <th class="tl-align-center hidden-phone sorting" tabindex="0" aria-controls="tl-list-user-groups" rowspan="1" colspan="1" aria-label="Synchronize user with courses" style="width: 389px;">Synchronize user with courses</th>
                                                <th class="tl-align-center tl-table-operations-cell sorting_desc" tabindex="0" aria-controls="tl-list-user-groups" rowspan="1" colspan="1" aria-sort="descending" aria-label="Options" style="">Options</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                    <?php   // echo "<pre>"; print_r($results);
                                       
                                            foreach ($results as $value) {
                                            $userassig    =   $this->UserModel->get_groupassign_by_id($value->id,$user_id);?>
                                            <tr role="row" class="odd">
                                            <td class=" tl-align-left">
                                                <span class="tl-group-user-name"><a href="<?php echo  base_url(); ?>group/edit/id=<?php echo $value->id;?>"><span title="<?php echo $value->name;?>"><?php echo $value->name;?></span></a>&nbsp;
                                               <?php if(!empty($userassig)){?>
                                                        <span class="label label-registration">group member</span></span>
                                               <?php } ?>
                                               </td>
                                                <td class=" tl-align-center hidden-phone">
                                                    <?php if(empty($userassig)){?>
                                                          <div class="tl-user-group-courses-noinfo-wrapper">-</div>
                                                     <?php } ?>
                                                     <?php if(!empty($userassig)){?>
                                                           <div class="tl-user-group-courses-info-wrapper">
                                                                  <span class="tl-user-group-courses">0/0</span>
                                                               </div>
                                                     <?php } ?>
                                                    </td>
                                                        <td class=" tl-align-center tl-table-operations-cell">
                                                            <?php if(!empty($userassig)){?>
                                                            <div class="tl-table-operations">
                                                            <a href="#" class="unenroll" id="unenroll" data-groupid="<?php echo $value->id;?>" data-userid="<?php echo $user_id;?>" >
                                                                <i class="fa fa-window-minimize" aria-hidden="true"  alt="Remove from group" title="Remove from group" data-groupid="<?php echo $value->id;?>" data-userid="<?php echo $user_id;?>"></i></div>
                                                            <?php } else{?>
                                                            <a href="#" class="enroll" id="enroll" data-groupid="<?php echo $value->id;?>" data-userid="<?php echo $user_id;?>">
                                                               <i class="fa fa-plus" aria-hidden="true" alt="Add to group" title="Add to group" ></i>
                                                           </a>
                                                             <?php } ?>
                                                            </td>
                                                        </tr>
                                             <?php }
                                             ?>
                                        </tbody>
                                    </table>
                                     <?php }else{?>
                                        <div class="tl-empty-result text-center">
                                             <img class="tl-empty-results-img" src="<?php echo base_url();?>images/empty_states/groups.svg">
                                             <div class="empty-state-text-margin-bottom">
                                                   You do not have any group          
                                            </div>
                                        </div>
                                  <?php }?>
                             </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>

    <!-- /.content -->
</div>

<?php $this->load->view('include/footer2');?>

<script type="text/javascript">
    $(document).ready(function(){
        $(".unenroll").click(function( event ){
            event.preventDefault();
             var groupid = $(this).data('groupid');
             var userid = $(this).data('userid');
               $.ajax({
                 url:'<?php echo base_url();?>user/group_remove',
                 method: 'post',
                 data: {groupid: groupid,userid :userid},
                 dataType: 'json',
                 success: function(response){
                     window.location.reload();
                 }
              });
             });
         $(".enroll").click(function( event ){
            event.preventDefault();
             var groupid = $(this).data('groupid');
             var userid = $(this).data('userid');
               $.ajax({
                 url:'<?php echo base_url();?>user/group_assgin',
                 method: 'post',
                 data: {groupid: groupid,userid :userid},
                 dataType: 'json',
                 success: function(response){
                  window.location.reload();
             
                 }
              });
             });
    });


</script>
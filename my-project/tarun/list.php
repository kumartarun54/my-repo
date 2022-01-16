  <?php $this->load->view('include/header2');?> 

<style type="text/css">
  thead tr{border-bottom: 2px solid #ccc;}
  .fixed-table-pagination .float-left{float: right !important;}
  .refresh-btn{padding: .375rem .75rem;font-size: 1rem;}
  .refresh-btn i{margin-left: 10px;}
</style>

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <!-- <div class="container">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>User</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
              <li class="breadcrumb-item active">User Listing</li>
            </ol>
          </div>
        </div>
      </div> -->
    </section>

    <!-- Main content -->
    <section class="content user-content">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="card card-primary">
              <div class="card-header ">
                <a href="<?php echo base_url(); ?>">Home</a> / <b>User</b>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="card-tbl-head">
                  <div class="row">
                    <div class="col-sm-8">
                      <div class="btn-group">
                        <a href="create" class="btn btn-primary">Add User</a>
                        <button type="button" class="btn btn-primary dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                          <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu" role="menu" style="">
                          <a class="dropdown-item" href="#">Import User(s)</a>
                        </div>
                      </div>

                  <div class="tl-header-tools pull-left hidden-phone" style="display:none" id="checkbox-items" >
                      <div class="btn-group">
                          <a class='btn dropdown-toggle' href="#" data-toggle='dropdown' role='button'>Mass actions&nbsp;<b class="caret"></b></a>
                          <ul class='dropdown-menu' role='menu' style='right: 0px; left: auto;'>
                            <li role='presentation'>
                               <a class='massaction' href='javascript:void(0);' tabindex='-1' role='menuitem' data-mode='activate' >
                                Activate         
                                 </a>
                            </li>
                            <li role='presentation'>
                               <a class='massaction' href='javascript:void(0);' tabindex='-1' role='menuitem' data-mode='deactivate' >
                                Deactivate          
                              </a>
                            </li>
                              <li role='presentation'>
                              <a class='massaction' href='javascript:void(0);' tabindex='-1' role='menuitem' data-mode='delete' >
                                Delete          
                              </a>
                            </li>
                            <li class="divider"></li>
                            <li role='presentation'>
                              <a class='massaction' href='javascript:void(0);' tabindex='-1' role='menuitem' data-mode='branch-add' >
                                Add to branch        
                                 </a>
                            </li>
                            <li role='presentation'>
                              <a class='massaction' href='javascript:void(0);' tabindex='-1' role='menuitem' data-mode='branch-remove' >
                                Remove from branch         
                                 </a>
                            </li>
                              <li class="divider"></li>
                            <li role='presentation'>
                              <a class='massaction' href='javascript:void(0);' tabindex='-1' role='menuitem' data-mode='group-add' >
                                Add to group         
                               </a>
                            </li>
                            <li role='presentation'>
                              <a class='massaction' href='javascript:void(0);' tabindex='-1' role='menuitem' data-mode='group-remove' >
                                Remove from group        
                                 </a>
                            </li>
                               <li class="divider"></li>
                                 <li role='presentation'>
                                <a class='massaction' href='javascript:void(0);' tabindex='-1' role='menuitem' data-mode='message' >
                                  Send message            
                                </a>
                              </li>
                          </ul>
                      </div>
                  </div>
                  </div>
                  </div>
                </div>
                <div class='clear'></div>


                <div class="table-responsive">
                <table id="example1" class="table table-striped table-hover group-table">
                  <thead>
                    <tr>
                      <th><input id="tl-grid-checkbox-all" type="checkbox"></th>
                      <th>User</th><th>Email</th>
                      <th>User type</th>
                      <th>Registration</th>
                      <th>Last login</th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th>Options</th>
                    </tr>
                  </thead>
                  <tbody>
                     <?php //echo "<pre>"; print_r($list);
                         foreach ($list as $value) {
                               $old_date_timestamp = strtotime($value->created_at);
                               $new_date   = date('Y-m-d', $old_date_timestamp); 
                               $last_login = "";
                               if(!empty($value->last_login)){
                                  $last_login = $this->UserModel->time_elapsed_string($value->last_login);
                               }
                               
                               $usertype = $this->UserModel->get_user_id_by_id($value->user_type);?>
                        <tr>
                          <td><input type="checkbox" aria-checked="unchecked" aria-label="" class="hidden-mobile tl-grid-checkbox" id="" value="on" data-id="<?php echo $value->id;?>">
                          </td>
                          <td>
                              <a class="tl-tool-tip" rel="tooltip" title="Username: <?php echo $value->username;?> " href="<?php echo  base_url(); ?>user/info/?id=<?php echo $value->id;?>" ><span title='<?php echo $value->username;?> (<?php echo $value->full_name;?>)'><?php echo $value->username;?></span></a></td>
                              <td><span title='<?php echo $value->email;?>'> <?php echo $value->email;?></span></td>
                              <td><span title='SuperAdmin'><?php echo $usertype->name;?></span></td>
                              <td><?php echo $new_date; ?></td>
                              <td><?php echo $last_login;?></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td>
                               <a href="#" class="tbl-btn"> <i class="fa fa-signal" aria-hidden="true" alt="Reports" title="Reports" onclick="location='<?php echo  base_url(); ?>user/userinfo/<?php echo $value->id;?>'"></i></a>
                                &nbsp;
                              <a href="#" class="tbl-btn">  <i class="fa fa-edit" aria-hidden="true"  alt="Edit" title="Edit" onclick="location='<?php echo  base_url(); ?>user/info/?id=<?php echo $value->id;?>'"></i></a>
                            </td>
                          </tr>
                        <?php  } //end Foreach ?>
                  </tbody>
                </table>
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
<!-- page specific js -->

<script type="text/javascript">
  $(document).ready(function(){
    $('body').tooltip({selector: '.tl-tool-tip'});
    var options = '<ul class="dropdown-menu" role="menu">';
    options += '<li class="nav-header">Status</li>';
    options += '<li><a class="tl-cursor-pointer" href="https://netspark.talentlms.com/user/index/status:active">Active</a></li>';
    options += '</ul>';
    $('.tl-grid-filtering-wrapper').html('<a href="#" class="dropdown-toggle tl-grid-filtering pull-right" data-toggle="dropdown" data-target="#" role="button" title="Filter" ><i class="icon-filter"></i></a>' + options).show();
    $("#tl-users-grid").css('visibility', 'visible');
    $(document).on('click', '.tl-autologin-touser', function(){
      var url = 'https://netspark.talentlms.com/user/autologin/id:##id##'.replace('##id##', $(this).data('id'));

      myportal.app.ajax(url, {
        success: function(response){
          var response = $.parseJSON(response);
          $(location).attr('href', response.data.url);
        },
        error: function(jqXHR_obj){
          var response = $.parseJSON(jqXHR_obj.responseText);
          myportal.app.notify({type: "error", message: decodeURIComponent(response.message)});
        }
      });
    });

          $('#tl-grid-checkbox-all').change(function(){
        if(this.checked){
          $('#tl-grid-checkbox-all').addClass('tl-grid-checkbox-visible');
          $('.tl-grid-checkbox').prop('checked', true);
        }
        else{
          $('#tl-grid-checkbox-all').removeClass('tl-grid-checkbox-visible');
          $('.tl-grid-checkbox').prop('checked', false);
        }

        $('.tl-grid-checkbox').change();
      });

      $('#tl-users-grid_wrapper').on( "change", ".tl-grid-checkbox", function() {
        if($(this).is(":checked") && ($('.tl-grid-checkbox:checked').length = 1)){
          $('.tl-grid-checkbox').addClass('tl-grid-checkbox-visible');
          $('#tl-grid-checkbox-all').addClass('tl-grid-checkbox-visible');
          $('#checkbox-items').show();
        }
        else if (!$('.tl-grid-checkbox:checked').length){
          $('.tl-grid-checkbox').removeClass('tl-grid-checkbox-visible');
          $('#tl-grid-checkbox-all').removeClass('tl-grid-checkbox-visible');
          $('#checkbox-items').hide();
        }
      });
            var sendMessageFormId = 'tl-users-send-message';
      $('#' + sendMessageFormId + ' textarea').css('margin-bottom', '0px');
      $('#' + sendMessageFormId + ' #tl-message-attachment-group').css('margin', '10px 0px 0px 0px');
      $('#' + sendMessageFormId + ' #message_subject, #' + sendMessageFormId + ' #message_body').removeClass('span8').addClass('span7');
      $('#tl-users-send-message-modal #submit_send_message').removeClass('btn-primary').addClass('btn-success');
      $('.tl-tool-tip').tooltip().on('shown', function(e){ e.stopPropagation(); });
      
      $("#tl-branches-select").on("change", function(e){
        var userIds = new Array();
        $('.tl-grid-checkbox:checked').each(function (){
          userIds.push($(this).data("id"));
        });

        // Only count when adding/removing branches
        if (typeof(e.added) == 'undefined' && typeof(e.removed) == 'undefined'){
          return;
        }

        // No need to count again if adding a branch and all users will already be affected from the previous selection
        if (typeof(e.added) !== 'undefined' && userIds.length == $('#tl-mass-users-count').val()){
          return;
        }

        // No need to count again when removing branches and affected users are already 0
        if (typeof(e.removed) !== 'undefined' && $('#tl-mass-users-count').val() == 0){
          return;
        }

        if ($('#submit-mass-action').hasClass('addbranch')){
          var url = 'https://netspark.talentlms.com/user/countuserstoaddtobranches';
        }
        else {
          var url = 'https://netspark.talentlms.com/user/countuserstoremovefrombranches';
        }

        myportal.app.ajax(url, {
          type: "POST",
          data: {
            userIds: userIds,
            branchIds: $(this).val()
          },
          dataType: 'json',
          success: function(response){
            var data = response.data;
            $('#tl-mass-action-modal-message').empty().append('<p>' + decodeURIComponent(data.messageAffectedUsers) + '</p>');
            $('#tl-users-mass-action-modal').modal();
            $('#tl-mass-users-count').val(data.usersCount);
          }
        });

      });

      $("#tl-groups-select").on("change", function(e){
        var userIds = new Array();
        $('.tl-grid-checkbox:checked').each(function (){
          userIds.push($(this).data("id"));
        });

        // Only count when adding/removing groups
        if (typeof(e.added) == 'undefined' && typeof(e.removed) == 'undefined'){
          return;
        }

        // No need to count again if adding a group and all users will already be affected from the previous selection
        if (typeof(e.added) !== 'undefined' && userIds.length == $('#tl-mass-users-count').val()){
          return;
        }

        // No need to count again when removing groups and affected users are already 0
        if (typeof(e.removed) !== 'undefined' && $('#tl-mass-users-count').val() == 0){
          return;
        }

        if ($('#submit-mass-action').hasClass('addgroup')){
          var url = 'https://netspark.talentlms.com/user/countuserstoaddtogroups';
        }
        else {
          var url = 'https://netspark.talentlms.com/user/countuserstoremovefromgroups';
        }

        myportal.app.ajax(url, {
          type: "POST",
          data: {
            userIds: userIds,
            groupIds: $("#tl-groups-select").val()
          },
          dataType: 'json',
          success: function(response){
            var data = response.data;
            $('#tl-mass-action-modal-message').empty().append('<p>' + decodeURIComponent(data.messageAffectedUsers) + '</p>');
            $('#tl-users-mass-action-modal').modal();
            $('#tl-mass-users-count').val(data.usersCount);
          }
        });
      });

      $('.massaction').on('click', function(){
        var userIds = new Array();
        var mode = $(this).data('mode');
        $('.tl-grid-checkbox:checked').each(function (){
          userIds.push($(this).data("id"));
        });
        $("#tl-branches-select").val(null);
        $("#tl-groups-select").val(null);
        $("#tl-category-select").val(null).trigger('change');
        $('#tl-mass-users-count').val(0);

        $('#tl-mass-action-modal-branch-form-elements').hide();
        $('#tl-mass-action-modal-group-form-elements').hide();
        $('#tl-mass-action-modal-category-form-elements').hide();

        if(mode == 'activate'){
          $('#tl-users-mass-action-modal .modal-header h3').text("Activate");
          $('#submit-mass-action').text("Activate").removeClass().addClass('btn activateusers btn-success');
          var url = 'https://netspark.talentlms.com/user/countactiveinactiveusers';
        }
        else if(mode == 'deactivate'){
          $('#tl-users-mass-action-modal .modal-header h3').text("Deactivate");
          $('#submit-mass-action').text("Deactivate").removeClass().addClass('btn deactivateusers btn-danger');
          var url = 'https://netspark.talentlms.com/user/countactiveinactiveusers';
        }
        else if(mode == 'delete'){
          $('#tl-users-mass-action-modal .modal-header h3').text("Delete");
          $('#submit-mass-action').html("<i class=\"icon-trash\"></i>&nbsp;Delete").removeClass().addClass('btn deleteusers btn-danger');
          var url = 'https://netspark.talentlms.com/user/countuserstodelete';
        }
        else if(mode == 'message'){
          $('#tl-users-send-message-modal').modal();
        }
        else if(mode == 'branch-add'){
          $('#tl-users-mass-action-modal .modal-header h3').text("Add users to branch");
          $('#tl-mass-action-modal-branch-form-elements').show();
          $('#submit-mass-action').text("Add").removeClass().addClass('btn addbranch btn-success');
          var url = 'https://netspark.talentlms.com/user/countuserstoaddtobranches';
          loadSelectBoxOptions('branches', false);
        }
        else if(mode == 'branch-remove'){
          $('#tl-users-mass-action-modal .modal-header h3').text("Remove users from branch");
          $('#tl-mass-action-modal-branch-form-elements').show();
          $('#submit-mass-action').text("Remove").removeClass().addClass('btn removebranch btn-danger');
          var url = 'https://netspark.talentlms.com/user/countuserstoremovefrombranches';
          loadSelectBoxOptions('branches', userIds);
        }
        else if(mode == 'group-add'){
          $('#tl-users-mass-action-modal .modal-header h3').text("Add users to group");
          $('#tl-mass-action-modal-group-form-elements').show();
          $('#submit-mass-action').text("Add").removeClass().addClass('btn addgroup btn-success');
          var url = 'https://netspark.talentlms.com/user/countuserstoaddtogroups';
          loadSelectBoxOptions('groups', false);
        }
        else if(mode == 'group-remove'){
          $('#tl-users-mass-action-modal .modal-header h3').text("Remove users from group");
          $('#tl-mass-action-modal-group-form-elements').show();
          $('#submit-mass-action').text("Remove").removeClass().addClass('btn removegroup btn-danger');
          var url = 'https://netspark.talentlms.com/user/countuserstoremovefromgroups';
          loadSelectBoxOptions('groups', userIds);
        }

        if (typeof url !== 'undefined'){
          myportal.app.ajax(url, {
            type: "POST",
            data: {
              userIds: userIds,
              mode: mode
            },
            dataType: 'json',
            success: function(response){
              var data = response.data;
              $('#tl-mass-action-modal-message').empty().append('<p>' + decodeURIComponent(data.messageConfirm) + '</p>');
              if(data.showUndone){
                $('#tl-mass-action-modal-message').append('<p>' + "This action cannot be undone" + '</p>');
              }
              $('#tl-mass-action-modal-message').append('<p>' + decodeURIComponent(data.messageAffectedUsers) + '</p>');
              $('#tl-users-mass-action-modal').modal();
            }
          });
        }
      });

      // Get only the options that are applicable to the selection
      function loadSelectBoxOptions(type, userIds){
        if(type == 'branches'){
          var element = $("#tl-branches-select");
          if (userIds){
            var url = 'https://netspark.talentlms.com/branch/getbranchoptions';
          }
          else{
            element.html('');
                        element.val(null).trigger('change');
          }
        }
        if(type == 'groups'){
          var element = $("#tl-groups-select");
          if (userIds){
            var url = 'https://netspark.talentlms.com/group/getgroupoptions';
          }
          else{
            element.html('');
                        element.val(null).trigger('change');
          }
        }

        if (userIds){
          myportal.app.ajax(url, {
            type: "POST",
            data: {
              userIds: userIds,
            },
            dataType: 'json',
            success: function(response){
              var data = response.data;
              element.html('');
              for(var key in data){
                var option = new Option(data[key]['text'], data[key]['id'], true, true);
                element.append(option);
              }
              element.val(null).trigger('change');
            }
          });
        }
      }


      $('#tl-cancel-massaction').on('click', function(){  // cancel the mass action
        if($(this).hasClass('disabled')){
          return false;
        }

        $(this).addClass('disabled');
        $('#tl-user-info-modal .modal-footer div:first').show();
      });

      $('#tl-user-info-modal').on('hidden', function(){ // reset modal when the modal has finished being hidden from the user
        $('#tl-cancel-massaction').removeClass('disabled');
        $('#tl-user-info-modal .modal-footer div:first').hide();
        $('#tl-user-info-modal .modal-body .progress .bar').css('width', '1%');
      });

          $('#submit-mass-action').on('click', function(){
        var userIds = new Array();
        $('.tl-grid-checkbox:checked').each(function () {
          userIds.push($(this).data("id"));
        });

        var branchIds = '';
        var groupIds = '';
        var archivedSoFar = 0;

        if($(this).hasClass('deactivateusers')){
          var url = 'https://netspark.talentlms.com/user/countactiveinactiveusers';
          var mode = 'deactivate';
        }
        else if($(this).hasClass('activateusers')){
          var url = 'https://netspark.talentlms.com/user/countactiveinactiveusers';
          var mode = 'activate';
        }
        else if($(this).hasClass('deleteusers')){
          var url = 'https://netspark.talentlms.com/user/countuserstodelete';
          var mode = 'delete'
        }
        else if($(this).hasClass('addbranch')){
          var url = 'https://netspark.talentlms.com/user/countuserstoaddtobranches';
          var mode = 'addbranch';
          branchIds = $("#tl-branches-select").val();
        }
        else if($(this).hasClass('removebranch')){
          var url = 'https://netspark.talentlms.com/user/countuserstoremovefrombranches';
          var mode = 'removebranch';
          branchIds = $("#tl-branches-select").val();
        }
        else if($(this).hasClass('addgroup')){
          var url = 'https://netspark.talentlms.com/user/countuserstoaddtogroups';
          var mode = 'addgroup';
          groupIds = $("#tl-groups-select").val();
        }
        else if($(this).hasClass('removegroup')){
          var url = 'https://netspark.talentlms.com/user/countuserstoremovefromgroups';
          var mode = 'removegroup';
          groupIds = $("#tl-groups-select").val();
        }

        if (typeof url !== 'undefined'){
          myportal.app.ajax(url, {
            type: "post",
            data: {
              userIds: userIds,
              mode: mode,
              branchIds: branchIds,
              groupIds: groupIds
            },
            dataType: 'json',
            success: function(response){
              var data = response.data;
              $('#tl-users-mass-action-modal').modal('hide');
              if(data.usersCount > 0){
                if(data.reachedPlanLimit){
                  myportal.app.notify({type: "error", message: "This operation is not possible because the active users will be more than this account limit"});
                }
                if(data.messageBranchLimit){
                  myportal.app.notify({type: "error", message: decodeURIComponent(data.messageBranchLimit)});
                }
                else{
                  $('#tl-user-info-modal').modal({keyboard: false, backdrop: 'static'});
                  $('#tl-user-info-modal .modal-body div:first').html(decodeURIComponent(data.message));
                  if (mode == 'deactivate' || mode == 'delete' || mode == 'removebranch' || mode == 'removegroup'){
                    $('#tl-user-info-modal .modal-body .progress').removeClass('progress-success').addClass('progress-danger');
                  }
                  else{
                    $('#tl-user-info-modal .modal-body .progress').removeClass('progress-danger').addClass('progress-success');
                  }
                  processChunkUsers(mode, userIds, data.usersCount, data.chunks, 1, branchIds, groupIds, archivedSoFar);  // process the first chunk of users
                }
              }
              else{ // no users to process
                myportal.app.notify({type: "success", message: decodeURIComponent(data.messageNoUsers)});
              }
            }
          });
        }
      });

      function processChunkUsers(mode, userIds, usersToProcess, chunksCount, chunk, branchIds, groupIds, archivedSoFar){
        if (mode == 'activate'){
          var url = 'https://netspark.talentlms.com/user/activateusers';
          var requestData = {'userIds': userIds, 'users-count': usersToProcess, 'chunk': chunk, 'myToken': myToken};
        }
        if (mode == 'deactivate'){
          var url = 'https://netspark.talentlms.com/user/deactivateusers';
          var requestData = {'userIds': userIds, 'users-count': usersToProcess, 'chunk': chunk, 'myToken': myToken};
        }
        if (mode == 'delete'){
          var url = 'https://netspark.talentlms.com/user/deleteusers';
          var requestData = {'userIds': userIds, 'users-count': usersToProcess, 'archived-so-far': archivedSoFar, 'chunk': chunk, 'myToken': myToken};
        }
        if (mode == 'addbranch'){
          var url = 'https://netspark.talentlms.com/user/adduserstobranches';
          var requestData = {'userIds': userIds, 'users-count': usersToProcess, 'chunk': chunk, 'myToken': myToken, 'branchIds': branchIds};
        }
        if (mode == 'removebranch'){
          var url = 'https://netspark.talentlms.com/user/removeusersfrombranches';
          var requestData = {'userIds': userIds, 'users-count': usersToProcess, 'chunk': chunk, 'myToken': myToken, 'branchIds': branchIds};
        }
        if (mode == 'addgroup'){
          var url = 'https://netspark.talentlms.com/user/adduserstogroups';
          var requestData = {'userIds': userIds, 'users-count': usersToProcess, 'chunk': chunk, 'myToken': myToken, 'groupIds': groupIds};
        }
        if (mode == 'removegroup'){
          var url = 'https://netspark.talentlms.com/user/removeusersfromgroups';
          var requestData = {'userIds': userIds, 'users-count': usersToProcess, 'chunk': chunk, 'myToken': myToken, 'groupIds': groupIds};
        }
        myportal.app.ajax(url, {
          type: 'POST',
          data: requestData,
          success: function(response){
            var data = myportal.app.parseResponse(response);
            $('#tl-user-info-modal .modal-body .progress .bar').css('width', data.progressTillNow + '%');
            if(data.archivedSoFar){
              archivedSoFar = data.archivedSoFar;
            }

            if(chunk < chunksCount && !$('#tl-cancel-massaction').hasClass('disabled')){
              processChunkUsers(mode, userIds, usersToProcess, chunksCount, chunk + 1, branchIds, groupIds, archivedSoFar); // process the next chunk
            }
            else{ // all chunks are processed or the mass action was cancelled
              if (mode == 'delete'){
                var url = 'https://netspark.talentlms.com/user/deletearchivedusers';
                myportal.app.ajax(url, {
                  type: 'POST',
                  data: {'myToken': myToken},
                  success: function(response){
                    setTimeout(function(){
                      $('#tl-user-info-modal').modal('hide');
                      myportal.app.notify({type: "success", message: decodeURIComponent(data.processedTillNowMessage)});
                      $('#tl-users-grid').DataTable().draw();
                    }, 1000);
                  }
                });
              }
              else{
                setTimeout(function(){
                  $('#tl-user-info-modal').modal('hide');
                  myportal.app.notify({type: "success", message: decodeURIComponent(data.processedTillNowMessage)});
                  $('#tl-users-grid').DataTable().draw();
                }, 1000);
              }
            }
          }
        });

      }
    
          $('#tl-users-send-message-modal').on('show', function(){
        $('#' + sendMessageFormId + ' .error').removeClass('error');
        $('#' + sendMessageFormId + ' .help-block').html('');
        $('#tl-users-send-message-modal .alert').remove();
        $('#' + sendMessageFormId).show();
        $('#tl-users-send-message-modal .modal-footer').show();
        $('#submit_send_message').removeClass('disabled').text("Send message").val("Send message");
      });

      $('#submit_send_message').on('click', function(){
        if($(this).hasClass('disabled')){
          return false;
        }

        $(this).addClass('disabled').text("Sending...").val("Sending...");
        $('#' + sendMessageFormId + ' .error').removeClass('error');
        $('#' + sendMessageFormId + ' .help-block').html('');
        $('#tl-users-send-message-modal .alert').remove();
        $('#message_body').val($('.tl-message-editor').code());
        $('.tl-attachment-error').html('').hide();

        var url = 'https://netspark.talentlms.com/user/sendmessage';
        var userIds = new Array();
        $('.tl-grid-checkbox:checked').each(function () {
          userIds.push($(this).data("id"));
        });
        var messageData = $('#' + sendMessageFormId).serializeArray();
        messageData.push({name: "userIds", value: userIds});
        myportal.app.ajax(url, {
          type: 'POST',
          data: $.param(messageData),
          dataType: 'json',
          success: function(resp){
            if(!resp.success){
              if(resp.data){
                $.each(resp.data, function(key, val){
                  if(key == 'attachment_name' || key == 'tempfile'){
                    $('.tl-attachment-error').html(val).show();
                  }
                  else{
                    $("input[name='" + key + "']").parents('.control-group').addClass('error');
                    $("input[name='" + key + "']").closest('.controls').find('.help-block:first').html('<span class="help-inline">' + val + '</span>');
                    $("textarea[name='" + key + "']").parents('.control-group').addClass('error');
                    $("textarea[name='" + key + "']").closest('.controls').find('.help-block:first').html('<span class="help-inline">' + val + '</span>');
                  }
                });
              }
              else if(resp.exc){
                $('#tl-users-send-message-modal .modal-body').prepend('<div class="alert alert-error fade in out"><a class="close" data-dismiss="alert" href="#">&times;</a><p>' + resp.exc + '</p></div>');
              }

              $('#submit_send_message').removeClass('disabled').text("Send message").val("Send message");
            }
            else{
              $('#' + sendMessageFormId).hide();
              $('#tl-users-send-message-modal .modal-footer').hide();
              myportal.app.notify({type: "success", message: "Message sent successfully"});
              $('#tl-users-send-message-modal').modal('hide');
            }
          }
        });

        return false;
      });
    
  
  });//end of document ready function
</script>
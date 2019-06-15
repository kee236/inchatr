<?php

  $postback_id_str = $bot_info['id'];
  $postback_id_array = explode(",",$postback_id_str);

  $full_message_json = $bot_info['template_jsoncode'];
  $full_message_array = json_decode($full_message_json,true);

  $redirect_url = site_url('messenger_bot/template_manager');

  $image_upload_limit = 1; 
  if($this->config->item('messengerbot_image_upload_limit') != '')
  $image_upload_limit = $this->config->item('messengerbot_image_upload_limit'); 

  $video_upload_limit = 5; 
  if($this->config->item('messengerbot_video_upload_limit') != '')
  $video_upload_limit = $this->config->item('messengerbot_video_upload_limit');

  $audio_upload_limit = 3; 
  if($this->config->item('messengerbot_audio_upload_limit') != '')
  $audio_upload_limit = $this->config->item('messengerbot_audio_upload_limit');

  $file_upload_limit = 2; 
  if($this->config->item('messengerbot_file_upload_limit') != '')
  $file_upload_limit = $this->config->item('messengerbot_file_upload_limit');

?>
<?php $this->load->view("include/upload_js"); ?>

<style type="text/css">
  .item_remove
  {
  margin-top: 12px; 
  margin-left: -20px;
  font-size: 20px !important;
  cursor: pointer !important;
  font-weight: 200 !important;
  }
  .remove_reply
  {
  margin:10px 10px 0 0;
  font-size: 25px !important;
  cursor: pointer !important;
  font-weight: 200 !important;
  }
  .add_template,.ref_template{font-size: 10px;}
  .emojionearea.form-control{padding-top:12px !important;}
  .img_holder div:not(:first-child){display: none;position:fixed;bottom:87px;right:40px;}
  .img_holder div:first-child{position:fixed;bottom:87px;right:40px;}
  .lead_first_name,.lead_last_name{background: #EEE;border-radius: 0;}
  .input-group-addon{
  border-radius: 0;
  font-weight: bold;
  /* color: orange;   */
  /*border: 1px solid #607D8B !important;*/
  border: none;
  background: none;
  }
  /* .form-control-new
  {
  border: 1px solid #607D8B;
  height: 40px;
  width:100%;
  } */
  input[type=radio].css-checkbox {
  position:absolute; z-index:-1000; left:-1000px; overflow: hidden; clip: rect(0 0 0 0); height:1px; width:1px; margin:-1px; padding:0; border:0;
  }

  input[type=radio].css-checkbox + label.css-label {
  padding-left:24px;
  height:19px; 
  display:inline-block;
  line-height:19px;
  background-repeat:no-repeat;
  background-position: 0 0;
  font-size:19px;
  vertical-align:middle;
  cursor:pointer;

  }

  input[type=radio].css-checkbox:checked + label.css-label {
  background-position: 0 -19px;
  }
  label.css-label {
  background-image:url(<?php echo base_url('assets/images/csscheckbox.png'); ?>);
  -webkit-touch-callout: none;
  -webkit-user-select: none;
  -khtml-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  color: <?php echo $THEMECOLORCODE; ?> !important;
  font-size: 15px !important;
  }
  .css-label-container{padding:10px;border:1px dashed <?php echo $THEMECOLORCODE; ?>;border-radius: 5px;}
  .img_holder img{
  border: 1px solid #ccc;
  }

  .load_preview_modal
  {
    cursor: pointer !important;
  }

</style>

<div class="container-fluid">

  <div class="" id="add_bot_settings_modal">
    <div class="modal-dialog" style="width:100%;margin:20px 0 0 0;">
      <div class="modal-content">
        <div class="modal-header">
          <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
          <h4 class="modal-title" style="padding-left: 16px;"><i class='fa fa-edit'></i> <?php echo $this->lang->line("Edit template");?></h4>
        </div>
        <div class="modal-body" style="padding-left:30px;"> 
          <div class="row">
            <div class="col-xs-12 col-md-9">
              <form action="#" method="post" id="messenger_bot_form" style="padding-left: 0;">
                <input type="hidden" name="id" id="id" value="<?php echo  $bot_info['id'];?>">
              <!--   <br>    -->             
                <div class="text-left" style="display: none;">
                 <?php 
                   foreach ($keyword_types as $key => $value)
                   { ?>
                       <input type="radio" name="keyword_type" value="<?php echo $value; ?>" id="keyword_type_<?php echo $value;?>" class="css-checkbox keyword_type" /><label for="keyword_type_<?php echo $value;?>" class="css-label radGroup2"><?php echo $this->lang->line($value);?></label>
                       &nbsp;&nbsp;                  
                   <?php
                   } 
                ?>  
               </div>

               <br/>
                <div class="row"> 
                  <div class="col-xs-12"> 
                    <div class="form-group">
                      <label><?php echo $this->lang->line("Please give a name of this template"); ?></label>
                      <input type="text" name="bot_name" value="<?php if(set_value('bot_name')) echo set_value('bot_name');else {if(isset($bot_info['template_name'])) echo $bot_info['template_name'];}?>" id="bot_name" class="form-control">
                    </div>       
                  </div>  
                </div>

                <div class="row"> 
                  <div class="col-xs-12"> 
                    <div class="form-group">
                      <label><?php echo $this->lang->line("Selected page"); ?></label>
                      <?php 
                        $page_list[''] = "Please select a page";
                        echo form_dropdown('page_table_id',$page_list,$bot_info['page_id'],'id="page_table_id" class="form-control hidden"'); 
                        $pagename="";;
                        foreach ($page_list as $key => $value) 
                        {
                          if($key==$bot_info['page_id'])   $pagename=$value;                    
                        }
                        echo " : <b>".$pagename."</b>";
                      ?>
                    </div>       
                  </div>  
                </div>

                <div class="row"> 
                  <div class="col-xs-12"> 
                    <div class="form-group">
                      <label><?php echo $this->lang->line("Postback ID"); ?></label>
                      <input type="hidden" name="template_postback_id" id="template_postback_id" value="<?php if(set_value('postback_id')) echo set_value('postback_id');else {if(isset($bot_info['postback_id'])) echo $bot_info['postback_id'];}?>" class="form-control push_postback">
                      <?php echo " : <b>"; if(set_value('postback_id')) echo set_value('postback_id');else {if(isset($bot_info['postback_id'])) echo $bot_info['postback_id'];} echo "</b>";?>
                    </div>       
                  </div>  
                </div>

                <?php
                $is_broadcaster_exist=false;
                if($this->is_broadcaster_exist)
                {
                      $popover='<a href="#" data-placement="bottom"  data-toggle="popover" data-trigger="focus" title="'.$this->lang->line("Choose Labels").'" data-content="'.$this->lang->line("If you choose labels, then when user click on this PostBack they will be added in those labels, that will help you to segment your leads & broadcasting from Messenger Broadcaster. If you don't want to add labels for this PostBack , then just keep it blank as it is.").'"><i class="fa fa-info-circle"></i> </a>';

                      echo '<div class="row">
                      <div class="col-xs-12"> 
                          <div class="form-group">
                            <label style="width:100%">
                            '.$this->lang->line("Choose Labels")." ".$popover.'
                            </label>';
                            
                            $broadcaster_labels=$bot_info['broadcaster_labels'];
                            $broadcaster_labels=explode(',', $broadcaster_labels);

                            $str ='<select multiple="multiple"  class="form-control" id="label_ids" name="label_ids[]">';
                            foreach ($info_type as  $value)
                            {
                                $search_key = $value['id'];
                                $search_type = $value['group_name'];
                                $selected='';
                                if(in_array($search_key, $broadcaster_labels)) $selected='selected="selected"';

                                $str.=  "<option value='{$search_key}' {$selected}>".$search_type."</option>";  
                            }
                            $str.= '</select>';
                            echo $str;
                                                         
                      echo '</div>       
                      </div>
                    </div>';
                    $is_broadcaster_exist=true;
                }
                ?>                
                

               
                <div class="row" id="keywords_div" style="display: none;"> 
                  <div class="col-xs-12">              
                    <div class="form-group">
                      <label><?php echo $this->lang->line("Please provide your keywords in comma separated"); ?></label>
                      <textarea class="form-control"  name="keywords_list" id="keywords_list"><?php if(isset($bot_info['keywords'])) echo $bot_info['keywords'];?></textarea>
                    </div>        
                  </div>  
                </div>

               <div class="row" id="postback_div" style="display: none;"> 
                  <div class="col-xs-12">              
                    <div class="form-group">
                      <label><?php echo $this->lang->line("Please select your postback id : "); ?></label>
                      <!-- <input type="text" name="keywordtype_postback_id" id="keywordtype_postback_id" class=""> -->

                      <select class="form-control" id="keywordtype_postback_id" name="keywordtype_postback_id[]">
                     

                      <?php
                          $total_postback_id_array = array();
                          foreach($postback_ids as $value)
                          {
                            if(!in_array($value['postback_id'], $current_postbacks) )
                               $total_postback_id_array[$value['page_id']][] = strtoupper($value['postback_id']); 

                            $array_key = $value['postback_id'];
                            $array_value = $value['postback_id']." (".$value['bot_name'].")";
                            if($value['use_status'] == '0')
                            {                              
                              echo "<option value='{$array_key}'>{$array_value}</option>";
                            } 
                            else
                            {
                              if(in_array($array_key, $postback_id_array))
                              {
                                echo "<option value='{$array_key}' selected >{$array_value}</option>";
                              } 
                              
                            }                        
                          }
                      ?> 
                      
                      </select>
                    </div>        
                  </div>  
                </div>                    

          <?php 
          if(!isset($full_message_array[1]))
          {
            $full_message_array[1] = $full_message_array;
            $full_message_array[1]['message']['template_type'] = $bot_info['template_type'];
          }


          $active_reply_count = 0;
          for($k=1;$k<=3;$k++){ 

            $full_message[$k] = isset($full_message_array[$k]['message']) ? $full_message_array[$k]['message'] : array();

            if(isset($full_message[$k]["template_type"]))
              $full_message[$k]["template_type"] = str_replace('_', ' ', $full_message[$k]["template_type"]);       

          ?>

              <div id="multiple_template_div_<?php echo $k; ?>" 
                <?php 
                  if(!isset($full_message[$k]["template_type"]))
                    echo "style='display : none; margin-top:20px;background:#fff; border:.8px dashed ".$THEMECOLORCODE.";'"; 
                  else
                  {
                    $active_reply_count++;
                     echo "style='margin-top:20px;background:#fff; border:.8px dashed ".$THEMECOLORCODE.";'";
                  }
                ?>
              >  

                
                <?php if($k != 1 && $k == count($full_message_array)) : ?>
                  <i class="fa fa-2x fa-times-circle remove_reply pull-right red" row_id="multiple_template_div_<?php echo $k; ?>" counter_variable="" title="<?php echo $this->lang->line('Remove this item'); ?>"></i>
                <?php else : ?>
                  <i class="fa fa-2x fa-times-circle remove_reply pull-right red" style="display: none;" row_id="multiple_template_div_<?php echo $k; ?>" counter_variable="" title="<?php echo $this->lang->line('Remove this item'); ?>"></i>
                <?php endif; ?>

                <div style="padding: 0 15px 15px 15px !important;;">
                  <label for="template_type"><?php echo $this->lang->line("");?></label>          
                  <div class="form-group">
                    <span class="input-group-addon"><?php echo $this->lang->line("Template Type");?></span>
                     <select class="form-control form-control-new" id="template_type_<?php echo $k; ?>" name="template_type_<?php echo $k; ?>">
                      <?php 

                       foreach ($templates as $key => $value)
                       {
                        if(isset($full_message[$k]["template_type"]) && $full_message[$k]["template_type"] == $value) $selected='selected';
                        else $selected='';
                        echo "<option value='{$value}' {$selected}>{$this->lang->line($value)}</option>";
                       } 
                      ?>
                    </select>
                  </div>

                  <div class="row" id="text_div_<?php echo $k; ?>"> 
                    <div class="col-xs-12">              
                      <div class="form-group">
                        <label><?php echo $this->lang->line("Please provide your reply message"); ?>
                          <a href="#" data-placement="bottom"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("Spintax"); ?>" data-content="Spintax example : {Hello|Howdy|Hola} to you, {Mr.|Mrs.|Ms.} {{Jason|Malina|Sara}|Williams|Davis}"><i class='fa fa-info-circle'></i> </a>
                        </label>

                        <span class='pull-right'> 
                          <a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("include lead user last name") ?>" data-content="<?php echo $this->lang->line("You can include #LEAD_USER_LAST_NAME# variable inside your message. The variable will be replaced by real names when we will send it.") ?>"><i class='fa fa-info-circle'></i> </a> 
                          <a title="<?php echo $this->lang->line("include lead user name") ?>" class='btn btn-default btn-sm lead_last_name'><i class='fa fa-user'></i> <?php echo $this->lang->line("last name") ?></a>
                        </span>
                        <span class='pull-right'> 
                          <a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("include lead user first name") ?>" data-content="<?php echo $this->lang->line("You can include #LEAD_USER_FIRST_NAME# variable inside your message. The variable will be replaced by real names when we will send it.") ?>"><i class='fa fa-info-circle'></i> </a> 
                          <a title="<?php echo $this->lang->line("include lead user name") ?>" class='btn btn-default btn-sm lead_first_name'><i class='fa fa-user'></i> <?php echo $this->lang->line("first name") ?></a>
                        </span> 
						            <div class="clearfix"></div>
                        <textarea class="form-control"  name="text_reply_<?php echo $k; ?>" id="text_reply_<?php echo $k; ?>"><?php if(isset($full_message[$k]["template_type"]) && $full_message[$k]["template_type"] == 'text') echo $full_message[$k]['text'];?></textarea>
                      </div>        
                    </div>  
                  </div>

                  <div class="row" id="image_div_<?php echo $k; ?>" style="display: none;">             
                    <div class="col-xs-12">              
                      <div class="form-group">
                        <label><?php echo $this->lang->line("Please provide your reply image"); ?></label>

                        <span class="label label-light blue load_preview_modal pull-right" item_type="image" file_path="<?php if(isset($full_message[$k]['template_type']) && $full_message[$k]['template_type']== 'image') echo $full_message[$k]['attachment']['payload']['url'];?>"><i class="fa fa-eye"></i><?php echo $this->lang->line('preview'); ?></span>

                        <input type="hidden" class="form-control"  name="image_reply_field_<?php echo $k; ?>" id="image_reply_field_<?php echo $k; ?>" value="<?php if(isset($full_message[$k]['template_type']) && $full_message[$k]['template_type']== 'image') echo $full_message[$k]['attachment']['payload']['url'];?>">
                        <div id="image_reply_<?php echo $k; ?>"><?php echo $this->lang->line("upload") ?></div>
                        <img id="image_reply_div_<?php echo $k; ?>" style="display: none;" height="200px;" width="400px;">
                      </div>       
                    </div>             
                  </div>

                  <div class="row" id="audio_div_<?php echo $k; ?>" style="display: none;">  
                    <div class="col-xs-12">             
                      <div class="form-group">
                        <label><?php echo $this->lang->line("Please provide your reply audio"); ?></label>

                        <span class="label label-light blue load_preview_modal pull-right" item_type="audio" file_path="<?php if(isset($full_message[$k]['template_type']) && $full_message[$k]['template_type']== 'audio') echo $full_message[$k]['attachment']['payload']['url'];?>"><i class="fa fa-eye"></i><?php echo $this->lang->line('preview'); ?></span>

                        <input type="hidden" class="form-control"  name="audio_reply_field_<?php echo $k; ?>" id="audio_reply_field_<?php echo $k; ?>" value="<?php if(isset($full_message[$k]['template_type']) && $full_message[$k]['template_type']== 'audio') echo $full_message[$k]['attachment']['payload']['url'];?>">
                        <div id="audio_reply_<?php echo $k; ?>"><?php echo $this->lang->line("upload") ?></div>                      
                        <audio controls id="audio_tag_<?php echo $k; ?>" style="display: none;">
                          <source src="" id="audio_reply_div_<?php echo $k; ?>" type="audio/mpeg">
                        Your browser does not support the video tag.
                        </audio>
                      </div>           
                    </div>
                  </div>

                  <div class="row" id="video_div_<?php echo $k; ?>" style="display: none;">  
                    <div class="col-xs-12">             
                      <div class="form-group">
                        <label><?php echo $this->lang->line("Please provide your reply video"); ?></label>

                        <span class="label label-light blue load_preview_modal pull-right" item_type="video" file_path="<?php if(isset($full_message[$k]['template_type']) && $full_message[$k]['template_type'] == 'video') echo $full_message[$k]['attachment']['payload']['url'];?>"><i class="fa fa-eye"></i><?php echo $this->lang->line('preview'); ?></span>

                        <input type="hidden" class="form-control"  name="video_reply_field_<?php echo $k; ?>" id="video_reply_field_<?php echo $k; ?>" value="<?php if(isset($full_message[$k]['template_type']) && $full_message[$k]['template_type'] == 'video') echo $full_message[$k]['attachment']['payload']['url'];?>">
                        <div id="video_reply_<?php echo $k; ?>"><?php echo $this->lang->line("upload") ?></div>                      
                        <video width="400" height="200" controls id="video_tag_<?php echo $k; ?>" style="display: none;">
                          <source src="" id="video_reply_div_<?php echo $k; ?>" type="video/mp4">
                        Your browser does not support the video tag.
                        </video>
                      </div>           
                    </div>
                  </div>

                  <div class="row" id="file_div_<?php echo $k; ?>" style="display: none;">  
                    <div class="col-xs-12">             
                      <div class="form-group">
                        <label><?php echo $this->lang->line("Please provide your reply file"); ?></label>

                        <span class="label label-light blue load_preview_modal pull-right" item_type="file" file_path="<?php if(isset($full_message[$k]['template_type']) && $full_message[$k]['template_type'] == 'file') echo $full_message[$k]['attachment']['payload']['url'];?>"><i class="fa fa-eye"></i><?php echo $this->lang->line('preview'); ?></span>

                        <input type="hidden" class="form-control"  name="file_reply_field_<?php echo $k; ?>" id="file_reply_field_<?php echo $k; ?>" value="<?php if(isset($full_message[$k]['template_type']) && $full_message[$k]['template_type'] == 'file') echo $full_message[$k]['attachment']['payload']['url'];?>">
                        <div id="file_reply_<?php echo $k; ?>"><?php echo $this->lang->line("upload") ?></div> 
                      </div>           
                    </div>
                  </div>

                  <div class="row" id="quick_reply_div_<?php echo $k; ?>" style="display: none; margin-bottom: 10px;">  
                    <div class="col-xs-12">  

                      <div class="form-group">
                        <label><?php echo $this->lang->line("Please provide your reply message"); ?>
                          <a href="#" data-placement="bottom"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("Spintax"); ?>" data-content="Spintax example : {Hello|Howdy|Hola} to you, {Mr.|Mrs.|Ms.} {{Jason|Malina|Sara}|Williams|Davis}"><i class='fa fa-info-circle'></i> </a>
                        </label>

                        <span class='pull-right'> 
                          <a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("include lead user last name") ?>" data-content="<?php echo $this->lang->line("You can include #LEAD_USER_LAST_NAME# variable inside your message. The variable will be replaced by real names when we will send it.") ?>"><i class='fa fa-info-circle'></i> </a> 
                          <a title="<?php echo $this->lang->line("include lead user name") ?>" class='btn btn-default btn-sm lead_last_name'><i class='fa fa-user'></i> <?php echo $this->lang->line("last name") ?></a>
                        </span>
                        <span class='pull-right'> 
                          <a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("include lead user first name") ?>" data-content="<?php echo $this->lang->line("You can include #LEAD_USER_FIRST_NAME# variable inside your message. The variable will be replaced by real names when we will send it.") ?>"><i class='fa fa-info-circle'></i> </a> 
                          <a title="<?php echo $this->lang->line("include lead user name") ?>" class='btn btn-default btn-sm lead_first_name'><i class='fa fa-user'></i> <?php echo $this->lang->line("first name") ?></a>
                        </span> 
						            <div class="clearfix"></div>
                        <textarea class="form-control" name="quick_reply_text_<?php echo $k; ?>" id="quick_reply_text_<?php echo $k; ?>"><?php if(isset($full_message[$k]["template_type"]) && $full_message[$k]["template_type"] == 'quick reply') echo $full_message[$k]['text'];?></textarea>
                      </div> 

                      <?php $quickreply_add_button_display = 0; for ($i=1; $i <=11 ; $i++) : ?>
                     <div class="row" id="quick_reply_row_<?php echo $i; ?>_<?php echo $k; ?>" <?php if(!isset($full_message[$k]['quick_replies'][$i-1])) echo 'style="display: none;border:1px dashed #ccc; background: #fcfcfc;padding:10px;margin:5px 0 0 20px;"'; else {$quickreply_add_button_display++;echo 'style="border:1px dashed #ccc; background: #fcfcfc;padding:10px;margin:5px 0 0 20px;"';} ?> >
                        <div class="col-xs-12 col-sm-3 col-md-4">
                          <div class="form-group">
                            <label><?php echo $this->lang->line("button text"); ?></label>
                            <input type="text" class="form-control"  name="quick_reply_button_text_<?php echo $i; ?>_<?php echo $k; ?>" id="quick_reply_button_text_<?php echo $i; ?>_<?php echo $k; ?>" value="<?php if(isset($full_message[$k]['quick_replies'][$i-1]['title'])) echo $full_message[$k]['quick_replies'][$i-1]['title']; ?>" <?php if(isset($full_message[$k]['quick_replies'][$i-1]['content_type']) && ($full_message[$k]['quick_replies'][$i-1]['content_type'] == 'user_phone_number' || $full_message[$k]['quick_replies'][$i-1]['content_type'] == 'user_email')) echo 'readonly'; ?>>
                          </div>
                        </div>

                        <div class="col-xs-12 col-sm-3 col-md-4">
                          <div class="form-group">
                            <label><?php echo $this->lang->line("button type"); ?></label>
                            <select class="form-control quick_reply_button_type_class" id="quick_reply_button_type_<?php echo $i; ?>_<?php echo $k; ?>" name="quick_reply_button_type_<?php echo $i; ?>_<?php echo $k; ?>">
                              <option value=""><?php echo $this->lang->line('please select a type'); ?></option>
                              <option value="post_back" <?php if(isset($full_message[$k]['quick_replies'][$i-1]['content_type']) && $full_message[$k]['quick_replies'][$i-1]['content_type'] == 'text') echo 'selected'; ?> ><?php echo $this->lang->line("Post Back"); ?></option>
                              <option value="phone_number" <?php if(isset($full_message[$k]['quick_replies'][$i-1]['content_type']) && $full_message[$k]['quick_replies'][$i-1]['content_type'] == 'user_phone_number') echo 'selected'; ?> ><?php echo $this->lang->line("user phone number"); ?></option>
                              <option value="user_email" <?php if(isset($full_message[$k]['quick_replies'][$i-1]['content_type']) && $full_message[$k]['quick_replies'][$i-1]['content_type'] == 'user_email') echo 'selected'; ?> ><?php echo $this->lang->line("user email address"); ?></option>
                              <option value="location" <?php if(isset($full_message[$k]['quick_replies'][$i-1]['content_type']) && $full_message[$k]['quick_replies'][$i-1]['content_type'] == 'location') echo 'selected'; ?> ><?php echo $this->lang->line("user's location"); ?></option>
                            </select>
                          </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                          <div class="form-group" id="quick_reply_postid_div_<?php echo $i; ?>_<?php echo $k; ?>" <?php if(!isset($full_message[$k]['quick_replies'][$i-1]['content_type']) || $full_message[$k]['quick_replies'][$i-1]['content_type'] != 'text') echo 'style="display: none;"'; ?>>
                            <label><?php echo $this->lang->line("PostBack id"); ?></label>
                            <input type="text" class="form-control push_postback"  name="quick_reply_post_id_<?php echo $i; ?>_<?php echo $k; ?>" id="quick_reply_post_id_<?php echo $i; ?>_<?php echo $k; ?>" value="<?php if(isset($full_message[$k]['quick_replies'][$i-1]['payload'])) echo $full_message[$k]['quick_replies'][$i-1]['payload']; ?>">
                          </div>
                        </div>   

                        <?php if($i != 1) : ?>
                          <div class="hidden-xs col-sm-2 col-md-1" <?php if(isset($full_message[$k]['quick_replies'])) if(count($full_message[$k]['quick_replies']) != $i) echo 'style="display: none;"'; ?> >
                            <br/>
                            <i class="fa fa-2x fa-times-circle red item_remove" row_id="quick_reply_row_<?php echo $i; ?>_<?php echo $k; ?>" first_column_id="quick_reply_button_text_<?php echo $i; ?>_<?php echo $k; ?>" second_column_id="quick_reply_button_type_<?php echo $i; ?>_<?php echo $k; ?>" third_postback="quick_reply_post_id_<?php echo $i; ?>_<?php echo $k; ?>" third_weburl="" third_callus="" counter_variable="quick_reply_button_counter_<?php echo $k; ?>" add_more_button_id="quick_reply_add_button_<?php echo $k; ?>" title="<?php echo $this->lang->line('Remove this item'); ?>"></i>
                          </div>
                        <?php endif; ?>


                      </div>
                      <?php endfor; ?>

                      <div class="row clearfix">
                        <div class="col-xs-12 text-center"><button class="btn btn-outline-primary pull-right no_radius btn-xs" <?php if($quickreply_add_button_display==11) echo 'style="display : none;"'; ?> id="quick_reply_add_button_<?php echo $k; ?>"><i class="fa fa-plus-circle"></i> <?php echo $this->lang->line("add more button");?></button></div>
                      </div>

                    </div> 
                  </div>




                  <div class="row" id="media_div_<?php echo $k; ?>" style="display: none; margin-bottom: 10px;">  
                    <div class="col-xs-12"> 

                      <div class="form-group">
                        <label><?php echo $this->lang->line("Please provide your media URL"); ?>
                          <a href="#" class="media_template_modal" title="<?php echo $this->lang->line("How to get meida URL?"); ?>"><i class='fa fa-info-circle'></i> </a>
                        </label>

                        <div class="clearfix"></div>
                        <input class="form-control"  name="media_input_<?php echo $k; ?>" id="media_input_<?php echo $k; ?>" value="<?php if(isset($full_message[$k]["template_type"]) && $full_message[$k]["template_type"] == 'media') echo $full_message[$k]['attachment']['payload']['elements'][0]['url']; ?>" />
                      </div> 

                      <?php $media_add_button_display = 0; for ($i=1; $i <=3 ; $i++) : ?>
                      <div class="row" id="media_row_<?php echo $i; ?>_<?php echo $k; ?>" <?php if(!isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1])) echo 'style="display: none;border:1px dashed #ccc; background: #fcfcfc;padding:10px;margin:5px 0 0 20px;"'; else {$media_add_button_display++; echo 'style="border:1px dashed #ccc; background: #fcfcfc;padding:10px;margin:5px 0 0 20px;"';} ?> >
                        <div class="col-xs-12 col-sm-3 col-md-4">
                          <div class="form-group">
                            <label><?php echo $this->lang->line("button text"); ?></label>
                            <input type="text" class="form-control"  name="media_text_<?php echo $i; ?>_<?php echo $k; ?>" id="media_text_<?php echo $i; ?>_<?php echo $k; ?>" value="<?php if(isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['title'])) echo $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['title']; ?>" >
                          </div>
                        </div>
                        <div class="col-xs-12 col-sm-3 col-md-4">
                          <div class="form-group">
                            <label><?php echo $this->lang->line("button type"); ?></label>
                            <select class="form-control media_type_class" id="media_type_<?php echo $i; ?>_<?php echo $k; ?>" name="media_type_<?php echo $i; ?>_<?php echo $k; ?>">
                              <option value=""><?php echo $this->lang->line('please select a type'); ?></option>
                              <option value="post_back" <?php if(isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['type']) && $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['type'] == 'postback') echo 'selected'; ?> ><?php echo $this->lang->line("Post Back"); ?></option>
                              <option value="web_url" <?php if(isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['type']) && $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['type'] == 'web_url') echo 'selected'; ?> ><?php echo $this->lang->line("Web URL"); ?></option>
                              <option value="phone_number" <?php if(isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['type']) && $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['type'] == 'phone_number') echo 'selected'; ?> ><?php echo $this->lang->line("call us"); ?></option>
                              
                              <?php if($has_broadcaster_addon == 1) : ?>
                              <option value="post_back" id="unsubscribe_postback" <?php if(isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['type']) && $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['type'] == 'postback' && isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload']) && $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload'] == 'UNSUBSCRIBE_QUICK_BOXER') echo 'selected'; ?> ><?php echo $this->lang->line("unsubscribe"); ?></option>
                              <option value="post_back" id="resubscribe_postback" <?php if(isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['type']) && $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['type'] == 'postback' && isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload']) && $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload'] == 'RESUBSCRIBE_QUICK_BOXER') echo 'selected'; ?> ><?php echo $this->lang->line("re-subscribe"); ?></option>
                              <?php endif; ?>
                              <option value="post_back" id="human_postback" <?php if(isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['type']) && $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['type'] == 'postback' && isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload']) && $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload'] == 'YES_START_CHAT_WITH_HUMAN') echo 'selected'; ?> ><?php echo $this->lang->line("Chat with Human"); ?></option>
                              <option value="post_back" id="robot_postback" <?php if(isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['type']) && $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['type'] == 'postback' && isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload']) && $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload'] == 'YES_START_CHAT_WITH_BOT') echo 'selected'; ?> ><?php echo $this->lang->line("Chat with Robot"); ?></option>
                            </select>
                          </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                          <div class="form-group" id="media_postid_div_<?php echo $i; ?>_<?php echo $k; ?>" <?php if(!isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload']) || $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['type'] != 'postback') echo 'style="display: none;"'; ?> >
                            
                            <?php 
                            $label_style = '';
                            if(isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload']) && ($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload'] == 'UNSUBSCRIBE_QUICK_BOXER' || $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload'] == 'RESUBSCRIBE_QUICK_BOXER' || $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload'] == 'YES_START_CHAT_WITH_HUMAN' || $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload'] == 'YES_START_CHAT_WITH_BOT')) 
                              $label_style = 'style="display: none;"'; 
                            ?>

                            <label <?php echo $label_style; ?> ><?php echo $this->lang->line("PostBack id"); ?></label>

                            <?php 
                            if(isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload']) && ($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload'] == 'UNSUBSCRIBE_QUICK_BOXER' || $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload'] == 'RESUBSCRIBE_QUICK_BOXER' || $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload'] == 'YES_START_CHAT_WITH_HUMAN' || $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload'] == 'YES_START_CHAT_WITH_BOT')) 
                              $input_type = 'hidden'; 
                            else 
                              $input_type = 'text'; 
                            ?>

                            <input type="<?php echo $input_type; ?>" class="form-control push_postback"  name="media_post_id_<?php echo $i; ?>_<?php echo $k; ?>" id="media_post_id_<?php echo $i; ?>_<?php echo $k; ?>" value="<?php if(isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload']) && $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['type'] == 'postback' ) echo $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload']; ?>" >
                          </div>
                          <div class="form-group" id="media_web_url_div_<?php echo $i; ?>_<?php echo $k; ?>" <?php if(!isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['url'])) echo 'style="display: none;"'; ?>>
                            <label><?php echo $this->lang->line("web url"); ?></label>
                            <input type="text" class="form-control"  name="media_web_url_<?php echo $i; ?>_<?php echo $k; ?>" id="media_web_url_<?php echo $i; ?>_<?php echo $k; ?>" value="<?php if(isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['url'])) echo $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['url']; ?>" >
                          </div>

                          <div class="form-group" id="media_call_us_div_<?php echo $i; ?>_<?php echo $k; ?>" <?php if(!isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload']) || $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['type'] != 'phone_number') echo 'style="display: none;"'; ?>>
                            <label><?php echo $this->lang->line("phone number"); ?></label>
                            <input type="text" class="form-control"  name="media_call_us_<?php echo $i; ?>_<?php echo $k; ?>" id="media_call_us_<?php echo $i; ?>_<?php echo $k; ?>" value="<?php if(isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload']) && $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['type'] == 'phone_number' ) echo $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload']; ?>" >
                          </div>

                        </div>

                        <?php if($i != 1) : ?>
                          <div class="hidden-xs col-sm-2 col-md-1" <?php if(isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'])) if(count($full_message[$k]['attachment']['payload']['elements'][0]['buttons']) != $i) echo 'style="display: none;"'; ?>>
                            <br/>
                            <i class="fa fa-2x fa-times-circle red item_remove" row_id="media_row_<?php echo $i; ?>_<?php echo $k; ?>" first_column_id="media_text_<?php echo $i; ?>_<?php echo $k; ?>" second_column_id="media_type_<?php echo $i; ?>_<?php echo $k; ?>" third_postback="media_post_id_<?php echo $i; ?>_<?php echo $k; ?>" third_weburl="media_web_url_<?php echo $i; ?>_<?php echo $k; ?>" third_callus="media_call_us_<?php echo $i; ?>_<?php echo $k; ?>" counter_variable="media_counter_<?php echo $k; ?>" add_more_button_id="media_add_button_<?php echo $k; ?>" title="<?php echo $this->lang->line('Remove this item'); ?>"></i>
                          </div>
                        <?php endif; ?>

                      </div>
                      <?php endfor; ?>

                      <div class="row clearfix">
                        <div class="col-xs-12 text-center"><button class="btn btn-outline-primary pull-right no_radius btn-xs" <?php if($media_add_button_display==3) echo 'style="display : none;"'; ?> id="media_add_button_<?php echo $k; ?>"><i class="fa fa-plus-circle"></i> <?php echo $this->lang->line("add more button");?></button></div>
                      </div>

                    </div> 
                  </div>






                  <div class="row" id="text_with_buttons_div_<?php echo $k; ?>" style="display: none; margin-bottom: 10px;">  
                    <div class="col-xs-12"> 

                      <div class="form-group">
                        <label><?php echo $this->lang->line("Please provide your reply message"); ?>
                          <a href="#" data-placement="bottom"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("Spintax"); ?>" data-content="Spintax example : {Hello|Howdy|Hola} to you, {Mr.|Mrs.|Ms.} {{Jason|Malina|Sara}|Williams|Davis}"><i class='fa fa-info-circle'></i> </a>
                        </label>

                        <span class='pull-right'> 
                          <a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("include lead user last name") ?>" data-content="<?php echo $this->lang->line("You can include #LEAD_USER_LAST_NAME# variable inside your message. The variable will be replaced by real names when we will send it.") ?>"><i class='fa fa-info-circle'></i> </a> 
                          <a title="<?php echo $this->lang->line("include lead user name") ?>" class='btn btn-default btn-sm lead_last_name'><i class='fa fa-user'></i> <?php echo $this->lang->line("last name") ?></a>
                        </span>
                        <span class='pull-right'> 
                          <a href="#" data-placement="top"  data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("include lead user first name") ?>" data-content="<?php echo $this->lang->line("You can include #LEAD_USER_FIRST_NAME# variable inside your message. The variable will be replaced by real names when we will send it.") ?>"><i class='fa fa-info-circle'></i> </a> 
                          <a title="<?php echo $this->lang->line("include lead user name") ?>" class='btn btn-default btn-sm lead_first_name'><i class='fa fa-user'></i> <?php echo $this->lang->line("first name") ?></a>
                        </span> 
            						<div class="clearfix"></div>
                        <textarea class="form-control"  name="text_with_buttons_input_<?php echo $k; ?>" id="text_with_buttons_input_<?php echo $k; ?>"><?php if(isset($full_message[$k]["template_type"]) && $full_message[$k]["template_type"] == 'text with buttons') echo $full_message[$k]['attachment']['payload']['text']; ?></textarea>
                      </div> 

                      <?php $textwithbutton_add_button_display = 0; for ($i=1; $i <=3 ; $i++) : ?>
                      <div class="row" id="text_with_buttons_row_<?php echo $i; ?>_<?php echo $k; ?>" <?php if(!isset($full_message[$k]['attachment']['payload']['buttons'][$i-1])) echo 'style="display: none;border:1px dashed #ccc; background: #fcfcfc;padding:10px;margin:5px 0 0 20px;"'; else {$textwithbutton_add_button_display++; echo 'style="border:1px dashed #ccc; background: #fcfcfc;padding:10px;margin:5px 0 0 20px;"';} ?> >
                        <div class="col-xs-12 col-sm-3 col-md-4">
                          <div class="form-group">
                            <label><?php echo $this->lang->line("button text"); ?></label>
                            <input type="text" class="form-control"  name="text_with_buttons_text_<?php echo $i; ?>_<?php echo $k; ?>" id="text_with_buttons_text_<?php echo $i; ?>_<?php echo $k; ?>" value="<?php if(isset($full_message[$k]['attachment']['payload']['buttons'][$i-1]['title'])) echo $full_message[$k]['attachment']['payload']['buttons'][$i-1]['title']; ?>" >
                          </div>
                        </div>
                        <div class="col-xs-12 col-sm-3 col-md-4">
                          <div class="form-group">
                            <label><?php echo $this->lang->line("button type"); ?></label>
                            <select class="form-control text_with_button_type_class" id="text_with_button_type_<?php echo $i; ?>_<?php echo $k; ?>" name="text_with_button_type_<?php echo $i; ?>_<?php echo $k; ?>">
                              <option value=""><?php echo $this->lang->line('please select a type'); ?></option>
                              <option value="post_back" <?php if(isset($full_message[$k]['attachment']['payload']['buttons'][$i-1]['type']) && $full_message[$k]['attachment']['payload']['buttons'][$i-1]['type'] == 'postback') echo 'selected'; ?> ><?php echo $this->lang->line("Post Back"); ?></option>
                              <option value="web_url" <?php if(isset($full_message[$k]['attachment']['payload']['buttons'][$i-1]['type']) && $full_message[$k]['attachment']['payload']['buttons'][$i-1]['type'] == 'web_url') echo 'selected'; ?> ><?php echo $this->lang->line("Web URL"); ?></option>
                              <option value="phone_number" <?php if(isset($full_message[$k]['attachment']['payload']['buttons'][$i-1]['type']) && $full_message[$k]['attachment']['payload']['buttons'][$i-1]['type'] == 'phone_number') echo 'selected'; ?> ><?php echo $this->lang->line("call us"); ?></option>
                              
                              <?php if($has_broadcaster_addon == 1) : ?>
                              <option value="post_back" id="unsubscribe_postback" <?php if(isset($full_message[$k]['attachment']['payload']['buttons'][$i-1]['type']) && $full_message[$k]['attachment']['payload']['buttons'][$i-1]['type'] == 'postback' && isset($full_message[$k]['attachment']['payload']['buttons'][$i-1]['payload']) && $full_message[$k]['attachment']['payload']['buttons'][$i-1]['payload'] == 'UNSUBSCRIBE_QUICK_BOXER') echo 'selected'; ?> ><?php echo $this->lang->line("unsubscribe"); ?></option>
                              <option value="post_back" id="resubscribe_postback" <?php if(isset($full_message[$k]['attachment']['payload']['buttons'][$i-1]['type']) && $full_message[$k]['attachment']['payload']['buttons'][$i-1]['type'] == 'postback' && isset($full_message[$k]['attachment']['payload']['buttons'][$i-1]['payload']) && $full_message[$k]['attachment']['payload']['buttons'][$i-1]['payload'] == 'RESUBSCRIBE_QUICK_BOXER') echo 'selected'; ?> ><?php echo $this->lang->line("re-subscribe"); ?></option>
                              <?php endif; ?>
                              <option value="post_back" id="human_postback" <?php if(isset($full_message[$k]['attachment']['payload']['buttons'][$i-1]['type']) && $full_message[$k]['attachment']['payload']['buttons'][$i-1]['type'] == 'postback' && isset($full_message[$k]['attachment']['payload']['buttons'][$i-1]['payload']) && $full_message[$k]['attachment']['payload']['buttons'][$i-1]['payload'] == 'YES_START_CHAT_WITH_HUMAN') echo 'selected'; ?> ><?php echo $this->lang->line("Chat with Human"); ?></option>
                              <option value="post_back" id="robot_postback" <?php if(isset($full_message[$k]['attachment']['payload']['buttons'][$i-1]['type']) && $full_message[$k]['attachment']['payload']['buttons'][$i-1]['type'] == 'postback' && isset($full_message[$k]['attachment']['payload']['buttons'][$i-1]['payload']) && $full_message[$k]['attachment']['payload']['buttons'][$i-1]['payload'] == 'YES_START_CHAT_WITH_BOT') echo 'selected'; ?> ><?php echo $this->lang->line("Chat with Robot"); ?></option>
                            </select>
                          </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                          <div class="form-group" id="text_with_button_postid_div_<?php echo $i; ?>_<?php echo $k; ?>" <?php if(!isset($full_message[$k]['attachment']['payload']['buttons'][$i-1]['payload']) || $full_message[$k]['attachment']['payload']['buttons'][$i-1]['type'] != 'postback') echo 'style="display: none;"'; ?> >

                            <?php 
                            $label_style = '';
                            if(isset($full_message[$k]['attachment']['payload']['buttons'][$i-1]['payload']) && ($full_message[$k]['attachment']['payload']['buttons'][$i-1]['payload'] == 'UNSUBSCRIBE_QUICK_BOXER' || $full_message[$k]['attachment']['payload']['buttons'][$i-1]['payload'] == 'RESUBSCRIBE_QUICK_BOXER' || $full_message[$k]['attachment']['payload']['buttons'][$i-1]['payload'] == 'YES_START_CHAT_WITH_HUMAN' || $full_message[$k]['attachment']['payload']['buttons'][$i-1]['payload'] == 'YES_START_CHAT_WITH_BOT')) 
                            $label_style = 'style="display: none;"'; 
                            ?>
                            <label <?php echo $label_style; ?> ><?php echo $this->lang->line("PostBack id"); ?></label>

                            <?php 
                            if(isset($full_message[$k]['attachment']['payload']['buttons'][$i-1]['payload']) && ($full_message[$k]['attachment']['payload']['buttons'][$i-1]['payload'] == 'UNSUBSCRIBE_QUICK_BOXER' || $full_message[$k]['attachment']['payload']['buttons'][$i-1]['payload'] == 'RESUBSCRIBE_QUICK_BOXER' || $full_message[$k]['attachment']['payload']['buttons'][$i-1]['payload'] == 'YES_START_CHAT_WITH_HUMAN' || $full_message[$k]['attachment']['payload']['buttons'][$i-1]['payload'] == 'YES_START_CHAT_WITH_BOT')) 
                              $input_type = 'hidden'; 
                            else 
                              $input_type = 'text'; 
                            ?>
                            <input type="<?php echo $input_type; ?>" class="form-control push_postback"  name="text_with_button_post_id_<?php echo $i; ?>_<?php echo $k; ?>" id="text_with_button_post_id_<?php echo $i; ?>_<?php echo $k; ?>" value="<?php if(isset($full_message[$k]['attachment']['payload']['buttons'][$i-1]['payload']) && $full_message[$k]['attachment']['payload']['buttons'][$i-1]['type'] == 'postback' ) echo $full_message[$k]['attachment']['payload']['buttons'][$i-1]['payload']; ?>" >
                          </div>
                          <div class="form-group" id="text_with_button_web_url_div_<?php echo $i; ?>_<?php echo $k; ?>" <?php if(!isset($full_message[$k]['attachment']['payload']['buttons'][$i-1]['url'])) echo 'style="display: none;"'; ?>>
                            <label><?php echo $this->lang->line("web url"); ?></label>
                            <input type="text" class="form-control"  name="text_with_button_web_url_<?php echo $i; ?>_<?php echo $k; ?>" id="text_with_button_web_url_<?php echo $i; ?>_<?php echo $k; ?>" value="<?php if(isset($full_message[$k]['attachment']['payload']['buttons'][$i-1]['url'])) echo $full_message[$k]['attachment']['payload']['buttons'][$i-1]['url']; ?>" >
                          </div>

                          <div class="form-group" id="text_with_button_call_us_div_<?php echo $i; ?>_<?php echo $k; ?>" <?php if(!isset($full_message[$k]['attachment']['payload']['buttons'][$i-1]['payload']) || $full_message[$k]['attachment']['payload']['buttons'][$i-1]['type'] != 'phone_number') echo 'style="display: none;"'; ?>>
                            <label><?php echo $this->lang->line("phone number"); ?></label>
                            <input type="text" class="form-control"  name="text_with_button_call_us_<?php echo $i; ?>_<?php echo $k; ?>" id="text_with_button_call_us_<?php echo $i; ?>_<?php echo $k; ?>" value="<?php if(isset($full_message[$k]['attachment']['payload']['buttons'][$i-1]['payload']) && $full_message[$k]['attachment']['payload']['buttons'][$i-1]['type'] == 'phone_number' ) echo $full_message[$k]['attachment']['payload']['buttons'][$i-1]['payload']; ?>" >
                          </div>

                        </div>

                        <?php if($i != 1) : ?>
                          <div class="hidden-xs col-sm-2 col-md-1" <?php if(isset($full_message[$k]['attachment']['payload']['buttons'])) if(count($full_message[$k]['attachment']['payload']['buttons']) != $i) echo 'style="display: none;"'; ?>>
                            <br/>
                            <i class="fa fa-2x fa-times-circle red item_remove" row_id="text_with_buttons_row_<?php echo $i; ?>_<?php echo $k; ?>" first_column_id="text_with_buttons_text_<?php echo $i; ?>_<?php echo $k; ?>" second_column_id="text_with_button_type_<?php echo $i; ?>_<?php echo $k; ?>" third_postback="text_with_button_post_id_<?php echo $i; ?>_<?php echo $k; ?>" third_weburl="text_with_button_web_url_<?php echo $i; ?>_<?php echo $k; ?>" third_callus="text_with_button_call_us_<?php echo $i; ?>_<?php echo $k; ?>" counter_variable="text_with_button_counter_<?php echo $k; ?>" add_more_button_id="text_with_button_add_button_<?php echo $k; ?>" title="<?php echo $this->lang->line('Remove this item'); ?>"></i>
                          </div>
                        <?php endif; ?>

                      </div>
                      <?php endfor; ?>

                      <div class="row clearfix">
                        <div class="col-xs-12 text-center"><button class="btn btn-outline-primary pull-right no_radius btn-xs" <?php if($textwithbutton_add_button_display==3) echo 'style="display : none;"'; ?> id="text_with_button_add_button_<?php echo $k; ?>"><i class="fa fa-plus-circle"></i> <?php echo $this->lang->line("add more button");?></button></div>
                      </div>

                    </div> 
                  </div>

                  <div class="row" id="generic_template_div_<?php echo $k; ?>" style="display: none; margin-bottom: 10px;">   
                    <div class="col-xs-12"> 

                      <div class="row">
                        <div class="col-xs-12 col-md-6">
                          <div class="form-group">
                            <label><?php echo $this->lang->line("Please provide your reply image"); ?> <span style='color:orange !important;'>(<?php echo $this->lang->line("optional"); ?>)</span></label>

                            <span class="label label-light blue load_preview_modal pull-right" item_type="image" file_path="<?php if(isset($full_message[$k]['template_type']) && $full_message[$k]['template_type'] == 'generic template' && isset($full_message[$k]['attachment']['payload']['elements'][0]['image_url'])) echo $full_message[$k]['attachment']['payload']['elements'][0]['image_url'];?>"><i class="fa fa-eye"></i><?php echo $this->lang->line('preview'); ?></span>

                            <input type="hidden" class="form-control"  name="generic_template_image_<?php echo $k; ?>" id="generic_template_image_<?php echo $k; ?>" value="<?php if(isset($full_message[$k]['template_type']) && $full_message[$k]['template_type'] == 'generic template' && isset($full_message[$k]['attachment']['payload']['elements'][0]['image_url'])) echo $full_message[$k]['attachment']['payload']['elements'][0]['image_url'];?>" />
                            <div id="generic_image_<?php echo $k; ?>"><?php echo $this->lang->line('upload'); ?></div>
                          </div>                         
                        </div>
                        <div class="col-xs-12 col-md-6">
                          <div class="form-group">
                            <label><?php echo $this->lang->line("image click destination link"); ?> <span style='color:orange !important;'>(<?php echo $this->lang->line("optional"); ?>)</span></label>
                            <input type="text" class="form-control"  name="generic_template_image_destination_link_<?php echo $k; ?>" id="generic_template_image_destination_link_<?php echo $k; ?>" value="<?php if(isset($full_message[$k]['template_type']) && $full_message[$k]['template_type'] == 'generic template' && isset($full_message[$k]['attachment']['payload']['elements'][0]['default_action']['url'])) echo $full_message[$k]['attachment']['payload']['elements'][0]['default_action']['url'];?>"/>
                          </div> 
                        </div>                      
                      </div>

                      <div class="row">
                        <div class="col-xs-12 col-md-6">
                          <div class="form-group">
                            <label><?php echo $this->lang->line("title"); ?></label>
                            <input type="text" class="form-control"  name="generic_template_title_<?php echo $k; ?>" id="generic_template_title_<?php echo $k; ?>" value="<?php if(isset($full_message[$k]['template_type']) && $full_message[$k]['template_type'] == 'generic template' && isset($full_message[$k]['attachment']['payload']['elements'][0]['title'])) echo $full_message[$k]['attachment']['payload']['elements'][0]['title'];?>"/>
                          </div>
                        </div>  
                        <div class="col-xs-12 col-md-6">
                          <div class="form-group">
                            <label><?php echo $this->lang->line("sub-title"); ?></label>
                            <input type="text" class="form-control"  name="generic_template_subtitle_<?php echo $k; ?>" id="generic_template_subtitle_<?php echo $k; ?>" value="<?php if(isset($full_message[$k]['template_type']) && $full_message[$k]['template_type'] == 'generic template' && isset($full_message[$k]['attachment']['payload']['elements'][0]['subtitle'])) echo $full_message[$k]['attachment']['payload']['elements'][0]['subtitle'];?>" />
                          </div>
                        </div>  
                      </div>

                      <span class="pull-right"><span style='color:orange !important;'>(<?php echo $this->lang->line("optional"); ?>)</span></span><div class="clearfix"></div>
                      <?php $generic_add_button_display = 0; for ($i=1; $i <=3 ; $i++) : ?>
                      <div class="row" id="generic_template_row_<?php echo $i; ?>_<?php echo $k; ?>" <?php if(!isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1])) echo 'style="display: none;border:1px dashed #ccc; background: #fcfcfc;padding:10px;margin:5px 0 0 20px;"'; else {$generic_add_button_display++;echo 'style="border:1px dashed #ccc; background: #fcfcfc;padding:10px;margin:5px 0 0 20px;"';} ?> >
                        <div class="col-xs-12 col-sm-3 col-md-4">
                          <div class="form-group">
                            <label><?php echo $this->lang->line("button text"); ?></label>
                            <input type="text" class="form-control"  name="generic_template_button_text_<?php echo $i; ?>_<?php echo $k; ?>" id="generic_template_button_text_<?php echo $i; ?>_<?php echo $k; ?>" value="<?php if(isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['title'])) echo $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['title']; ?>">
                          </div>
                        </div>
                        <div class="col-xs-12 col-sm-3 col-md-4">
                          <div class="form-group">
                            <label><?php echo $this->lang->line("button type"); ?></label>
                            <select class="form-control generic_template_button_type_class" id="generic_template_button_type_<?php echo $i; ?>_<?php echo $k; ?>" name="generic_template_button_type_<?php echo $i; ?>_<?php echo $k; ?>">
                              <option value=""><?php echo $this->lang->line('please select a type'); ?></option>
                              <option value="post_back" <?php if(isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['type']) && $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['type'] == 'postback') echo 'selected'; ?> ><?php echo $this->lang->line("Post Back"); ?></option>
                              <option value="web_url" <?php if(isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['type']) && $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['type'] == 'web_url') echo 'selected'; ?> ><?php echo $this->lang->line("Web URL"); ?></option>
                              <option value="phone_number" <?php if(isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['type']) && $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['type'] == 'phone_number') echo 'selected'; ?> ><?php echo $this->lang->line("call us"); ?></option>
                              <?php if($has_broadcaster_addon == 1) : ?>
                              <option value="post_back" id="unsubscribe_postback" <?php if(isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['type']) && $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['type'] == 'postback' && isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload']) && $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload'] == 'UNSUBSCRIBE_QUICK_BOXER') echo 'selected'; ?> ><?php echo $this->lang->line("unsubscribe"); ?></option>
                              <option value="post_back" id="resubscribe_postback" <?php if(isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['type']) && $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['type'] == 'postback' && isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload']) && $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload'] == 'RESUBSCRIBE_QUICK_BOXER') echo 'selected'; ?> ><?php echo $this->lang->line("re-subscribe"); ?></option>
                              <?php endif; ?>
                              <option value="post_back" id="human_postback" <?php if(isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['type']) && $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['type'] == 'postback' && isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload']) && $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload'] == 'YES_START_CHAT_WITH_HUMAN') echo 'selected'; ?> ><?php echo $this->lang->line("Chat with Human"); ?></option>
                              <option value="post_back" id="robot_postback" <?php if(isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['type']) && $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['type'] == 'postback' && isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload']) && $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload'] == 'YES_START_CHAT_WITH_BOT') echo 'selected'; ?> ><?php echo $this->lang->line("Chat with Robot"); ?></option>
                            </select>
                          </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                          <div class="form-group" id="generic_template_button_postid_div_<?php echo $i; ?>_<?php echo $k; ?>" <?php if(!isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload']) || $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['type'] != 'postback') echo 'style="display: none;"'; ?>>

                            <?php 
                            $label_style = '';
                            if(isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload']) && ($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload'] == 'UNSUBSCRIBE_QUICK_BOXER' || $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload'] == 'RESUBSCRIBE_QUICK_BOXER' || $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload'] == 'YES_START_CHAT_WITH_HUMAN' || $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload'] == 'YES_START_CHAT_WITH_BOT')) 
                            $label_style = 'style="display: none;"'; 
                            ?>
                            <label <?php echo $label_style; ?> ><?php echo $this->lang->line("PostBack id"); ?></label>

                            <?php 
                            if(isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload']) && ($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload'] == 'UNSUBSCRIBE_QUICK_BOXER' || $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload'] == 'RESUBSCRIBE_QUICK_BOXER' || $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload'] == 'YES_START_CHAT_WITH_HUMAN' || $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload'] == 'YES_START_CHAT_WITH_BOT')) 
                              $input_type = 'hidden'; 
                            else 
                              $input_type = 'text'; 
                            ?>
                            <input type="<?php echo $input_type; ?>" class="form-control push_postback"  name="generic_template_button_post_id_<?php echo $i; ?>_<?php echo $k; ?>" id="generic_template_button_post_id_<?php echo $i; ?>_<?php echo $k; ?>" value="<?php if(isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload']) && $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['type'] == 'postback') echo $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload']; ?>" >
                          </div>
                          <div class="form-group" id="generic_template_button_web_url_div_<?php echo $i; ?>_<?php echo $k; ?>" <?php if(!isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['url'])) echo 'style="display: none;"'; ?>>
                            <label><?php echo $this->lang->line("web url"); ?></label>
                            <input type="text" class="form-control"  name="generic_template_button_web_url_<?php echo $i; ?>_<?php echo $k; ?>" id="generic_template_button_web_url_<?php echo $i; ?>_<?php echo $k; ?>" value="<?php if(isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['url'])) echo $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['url']; ?>" >
                          </div>
                          <div class="form-group" id="generic_template_button_call_us_div_<?php echo $i; ?>_<?php echo $k; ?>" <?php if(!isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload']) || $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['type'] != 'phone_number') echo 'style="display: none;"'; ?>>
                            <label><?php echo $this->lang->line("phone number"); ?></label>
                            <input type="text" class="form-control"  name="generic_template_button_call_us_<?php echo $i; ?>_<?php echo $k; ?>" id="generic_template_button_call_us_<?php echo $i; ?>_<?php echo $k; ?>" value="<?php if(isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload']) && $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['type'] == 'phone_number') echo $full_message[$k]['attachment']['payload']['elements'][0]['buttons'][$i-1]['payload']; ?>" >
                          </div>
                        </div>

                        <?php if($i != 1) : ?>
                          <div class="hidden-xs col-sm-2 col-md-1" <?php if(isset($full_message[$k]['attachment']['payload']['elements'][0]['buttons'])) if(count($full_message[$k]['attachment']['payload']['elements'][0]['buttons']) != $i) echo 'style="display: none;"'; ?>>
                            <br/>
                            <i class="fa fa-2x fa-times-circle red item_remove" row_id="generic_template_row_<?php echo $i; ?>_<?php echo $k; ?>" first_column_id="generic_template_button_text_<?php echo $i; ?>_<?php echo $k; ?>" second_column_id="generic_template_button_type_<?php echo $i; ?>_<?php echo $k; ?>" third_postback="generic_template_button_post_id_<?php echo $i; ?>_<?php echo $k; ?>" third_weburl="generic_template_button_web_url_<?php echo $i; ?>_<?php echo $k; ?>" third_callus="generic_template_button_call_us_<?php echo $i; ?>_<?php echo $k; ?>" counter_variable="generic_with_button_counter_<?php echo $k; ?>" add_more_button_id="generic_template_add_button_<?php echo $k; ?>" title="<?php echo $this->lang->line('Remove this item'); ?>"></i>
                          </div>
                        <?php endif; ?>

                      </div>
                      <?php endfor; ?>

                      <div class="row clearfix">
                        <div class="col-xs-12 text-center"><button class="btn btn-outline-primary pull-right no_radius btn-xs" <?php if($generic_add_button_display==3) echo 'style="display : none;"'; ?> id="generic_template_add_button_<?php echo $k; ?>"><i class="fa fa-plus-circle"></i> <?php echo $this->lang->line("add more button");?></button></div>
                      </div>

                    </div>
                  </div>

                  <div class="row" id="carousel_div_<?php echo $k; ?>" style="display: none; margin-bottom: 10px;">  
                    <?php for ($j=1; $j <=10 ; $j++) : ?>
                    <div class="col-xs-12" id="carousel_div_<?php echo $j; ?>_<?php echo $k; ?>" style="<?php if(!isset($full_message[$k]['attachment']['payload']['elements'][$j-1])) echo 'display: none;'; ?>  padding-top: 20px;"> 
                      <div style="border: 1px dashed #ccc; background:#fcfcfc;padding:10px 15px;">
                      <div class="row">
                        <div class="col-xs-12 col-md-6">
                          <div class="form-group">
                            <label><?php echo $this->lang->line("Please provide your reply image"); ?> <span style='color:orange !important;'>(<?php echo $this->lang->line("optional"); ?>)</span></label>

                            <span class="label label-light blue load_preview_modal pull-right" item_type="image" file_path="<?php if(isset($full_message[$k]['template_type']) && $full_message[$k]['template_type'] == 'carousel' && isset($full_message[$k]['attachment']['payload']['elements'][$j-1]['image_url'])) echo $full_message[$k]['attachment']['payload']['elements'][$j-1]['image_url'];?>"><i class="fa fa-eye"></i><?php echo $this->lang->line('preview'); ?></span>

                            <input type="hidden" class="form-control"  name="carousel_image_<?php echo $j; ?>_<?php echo $k; ?>" id="carousel_image_<?php echo $j; ?>_<?php echo $k; ?>" value="<?php if(isset($full_message[$k]['template_type']) && $full_message[$k]['template_type'] == 'carousel' && isset($full_message[$k]['attachment']['payload']['elements'][$j-1]['image_url'])) echo $full_message[$k]['attachment']['payload']['elements'][$j-1]['image_url'];?>"/>
                            <div id="generic_imageupload_<?php echo $j; ?>_<?php echo $k; ?>"><?php echo $this->lang->line('upload'); ?></div>
                          </div>                         
                        </div>
                        <div class="col-xs-12 col-md-6">
                          <div class="form-group">
                            <label><?php echo $this->lang->line("image click destination link"); ?> <span style='color:orange !important;'>(<?php echo $this->lang->line("optional"); ?>)</span></label>
                            <input type="text" class="form-control"  name="carousel_image_destination_link_<?php echo $j; ?>_<?php echo $k; ?>" id="carousel_image_destination_link_<?php echo $j; ?>_<?php echo $k; ?>" value="<?php if(isset($full_message[$k]['template_type']) && $full_message[$k]['template_type'] == 'carousel' && isset($full_message[$k]['attachment']['payload']['elements'][$j-1]['default_action']['url'])) echo $full_message[$k]['attachment']['payload']['elements'][$j-1]['default_action']['url'];?>"/>
                          </div> 
                        </div>                      
                      </div>

                      <div class="row">
                        <div class="col-xs-12 col-md-6">
                          <div class="form-group">
                            <label><?php echo $this->lang->line("title"); ?></label>
                            <input type="text" class="form-control"  name="carousel_title_<?php echo $j; ?>_<?php echo $k; ?>" id="carousel_title_<?php echo $j; ?>_<?php echo $k; ?>" value="<?php if(isset($full_message[$k]['template_type']) && $full_message[$k]['template_type'] == 'carousel' && isset($full_message[$k]['attachment']['payload']['elements'][$j-1]['title'])) echo $full_message[$k]['attachment']['payload']['elements'][$j-1]['title'];?>" />
                          </div>
                        </div>  
                        <div class="col-xs-12 col-md-6">
                          <div class="form-group">
                            <label><?php echo $this->lang->line("sub-title"); ?></label>
                            <input type="text" class="form-control"  name="carousel_subtitle_<?php echo $j; ?>_<?php echo $k; ?>" id="carousel_subtitle_<?php echo $j; ?>_<?php echo $k; ?>" value="<?php if(isset($full_message[$k]['template_type']) && $full_message[$k]['template_type'] == 'carousel' && isset($full_message[$k]['attachment']['payload']['elements'][$j-1]['subtitle'])) echo $full_message[$k]['attachment']['payload']['elements'][$j-1]['subtitle'];?>" />
                          </div>
                        </div>  
                      </div>

                      <span class="pull-right"><span style='color:orange !important;'>(<?php echo $this->lang->line("optional"); ?>)</span></span><div class="clearfix"></div>
                      <?php $carousel_add_button_display = 0; for ($i=1; $i <=3 ; $i++) : ?>
                       <div class="row" id="carousel_row_<?php echo $j."_".$i; ?>_<?php echo $k; ?>" <?php if(!isset($full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1])) echo 'style="display: none;border:1px dashed #ccc; background: #fff;padding:10px;margin:5px 0 0 20px;"'; else {$carousel_add_button_display++; echo 'style="border:1px dashed #ccc; background: #fff;padding:10px;margin:5px 0 0 20px;"';} ?>>
                        <div class="col-xs-12 col-sm-3 col-md-4">
                          <div class="form-group">
                            <label><?php echo $this->lang->line("button text"); ?></label>
                            <input type="text" class="form-control"  name="carousel_button_text_<?php echo $j."_".$i; ?>_<?php echo $k; ?>" id="carousel_button_text_<?php echo $j."_".$i; ?>_<?php echo $k; ?>" value="<?php if(isset($full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['title'])) echo $full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['title']; ?>" >
                          </div>
                        </div>
                        <div class="col-xs-12 col-sm-3 col-md-4">
                          <div class="form-group">
                            <label><?php echo $this->lang->line("button type"); ?></label>
                            <select class="form-control carousel_button_type_class" id="carousel_button_type_<?php echo $j."_".$i; ?>_<?php echo $k; ?>" name="carousel_button_type_<?php echo $j."_".$i; ?>_<?php echo $k; ?>">
                              <option value=""><?php echo $this->lang->line('please select a type'); ?></option>
                              <option value="post_back" <?php if(isset($full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['type']) && $full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['type'] == 'postback') echo 'selected'; ?> ><?php echo $this->lang->line("Post Back"); ?></option>
                              <option value="web_url" <?php if(isset($full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['type']) && $full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['type'] == 'web_url') echo 'selected'; ?> ><?php echo $this->lang->line("Web URL"); ?></option>
                              <option value="phone_number" <?php if(isset($full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['type']) && $full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['type'] == 'phone_number') echo 'selected'; ?> ><?php echo $this->lang->line("call us"); ?></option>
                              
                              <?php if($has_broadcaster_addon == 1) : ?>
                              <option value="post_back" id="unsubscribe_postback" <?php if(isset($full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['type']) && $full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['type'] == 'postback' && isset($full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['payload']) && $full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['payload'] == 'UNSUBSCRIBE_QUICK_BOXER') echo 'selected'; ?> ><?php echo $this->lang->line("unsubscribe"); ?></option>
                              <option value="post_back" id="resubscribe_postback" <?php if(isset($full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['type']) && $full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['type'] == 'postback' && isset($full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['payload']) && $full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['payload'] == 'RESUBSCRIBE_QUICK_BOXER') echo 'selected'; ?> ><?php echo $this->lang->line("re-subscribe"); ?></option>
                              <?php endif; ?>
                                <option value="post_back" id="human_postback" <?php if(isset($full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['type']) && $full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['type'] == 'postback' && isset($full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['payload']) && $full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['payload'] == 'YES_START_CHAT_WITH_HUMAN') echo 'selected'; ?> ><?php echo $this->lang->line("Chat with Human"); ?></option>
                              <option value="post_back" id="robot_postback" <?php if(isset($full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['type']) && $full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['type'] == 'postback' && isset($full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['payload']) && $full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['payload'] == 'YES_START_CHAT_WITH_BOT') echo 'selected'; ?> ><?php echo $this->lang->line("Chat with Robot"); ?></option>
                            </select>
                          </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                          <div class="form-group" id="carousel_button_postid_div_<?php echo $j."_".$i; ?>_<?php echo $k; ?>" <?php if(!isset($full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['payload']) || $full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['type'] != 'postback') echo 'style="display: none;"'; ?> >

                            <?php 
                            $label_style = '';
                            if(isset($full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['payload']) && ($full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['payload'] == 'UNSUBSCRIBE_QUICK_BOXER' || $full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['payload'] == 'RESUBSCRIBE_QUICK_BOXER'  || $full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['payload'] == 'YES_START_CHAT_WITH_HUMAN'|| $full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['payload'] == 'YES_START_CHAT_WITH_BOT')) 
                            $label_style = 'style="display: none;"'; 
                            ?>
                            <label <?php echo $label_style; ?> ><?php echo $this->lang->line("PostBack id"); ?></label>

                            <?php 
                            if(isset($full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['payload']) && ($full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['payload'] == 'UNSUBSCRIBE_QUICK_BOXER' || $full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['payload'] == 'RESUBSCRIBE_QUICK_BOXER'  || $full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['payload'] == 'YES_START_CHAT_WITH_HUMAN'|| $full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['payload'] == 'YES_START_CHAT_WITH_BOT')) 
                              $input_type = 'hidden'; 
                            else 
                              $input_type = 'text'; 
                            ?>
                            <input type="<?php echo $input_type; ?>" class="form-control push_postback"  name="carousel_button_post_id_<?php echo $j."_".$i; ?>_<?php echo $k; ?>" id="carousel_button_post_id_<?php echo $j."_".$i; ?>_<?php echo $k; ?>" value="<?php if(isset($full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['payload']) && $full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['type'] == 'postback') echo $full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['payload']; ?>">
                          </div>
                          <div class="form-group" id="carousel_button_web_url_div_<?php echo $j."_".$i; ?>_<?php echo $k; ?>" <?php if(!isset($full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['url'])) echo 'style="display: none;"'; ?>>
                            <label><?php echo $this->lang->line("web url"); ?></label>
                            <input type="text" class="form-control"  name="carousel_button_web_url_<?php echo $j."_".$i; ?>_<?php echo $k; ?>" id="carousel_button_web_url_<?php echo $j."_".$i; ?>_<?php echo $k; ?>" value="<?php if(isset($full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['url'])) echo $full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['url']; ?>" >
                          </div>
                          <div class="form-group" id="carousel_button_call_us_div_<?php echo $j."_".$i; ?>_<?php echo $k; ?>" <?php if(!isset($full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['payload']) || $full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['type'] != 'phone_number') echo 'style="display: none;"'; ?> >
                            <label><?php echo $this->lang->line("phone number"); ?></label>
                            <input type="text" class="form-control"  name="carousel_button_call_us_<?php echo $j."_".$i; ?>_<?php echo $k; ?>" id="carousel_button_call_us_<?php echo $j."_".$i; ?>_<?php echo $k; ?>" value="<?php if(isset($full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['payload']) && $full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['type'] == 'phone_number') echo $full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'][$i-1]['payload']; ?>">
                          </div>
                        </div>

                        <?php if($i != 1) : ?>
                          <div class="hidden-xs col-sm-2 col-md-1" <?php if(isset($full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons'])) if(count($full_message[$k]['attachment']['payload']['elements'][$j-1]['buttons']) != $i) echo 'style="display: none;"'; ?> >
                            <br/>
                            <i class="fa fa-2x fa-times-circle red item_remove" row_id="carousel_row_<?php echo $j."_".$i; ?>_<?php echo $k; ?>" first_column_id="carousel_button_text_<?php echo $j."_".$i; ?>_<?php echo $k; ?>" second_column_id="carousel_button_type_<?php echo $j."_".$i; ?>_<?php echo $k; ?>" third_postback="carousel_button_post_id_<?php echo $j."_".$i; ?>_<?php echo $k; ?>" third_weburl="carousel_button_web_url_<?php echo $j."_".$i; ?>_<?php echo $k; ?>" third_callus="carousel_button_call_us_<?php echo $j."_".$i; ?>_<?php echo $k; ?>" counter_variable="carousel_add_button_counter_<?php echo $j; ?>_<?php echo $k; ?>" add_more_button_id="carousel_add_button_<?php echo $j; ?>_<?php echo $k; ?>" title="<?php echo $this->lang->line('Remove this item'); ?>"></i>
                          </div>
                        <?php endif; ?>

                      </div>
                      <?php endfor; ?>

                      <div class="row clearfix" style="padding-bottom: 10px;">
                        <div class="col-xs-12 text-center"><button class="btn btn-outline-primary pull-right no_radius btn-xs" <?php if($carousel_add_button_display==3) echo 'style="display : none;"'; ?> id="carousel_add_button_<?php echo $j; ?>_<?php echo $k; ?>"><i class="fa fa-plus-circle"></i> <?php echo $this->lang->line("add more button");?></button></div>
                      </div>
                    </div>
                    </div>
                    <?php endfor; ?>

                    <div class="col-xs-12 clearfix">
                      <button id="carousel_template_add_button_<?php echo $k; ?>" class="btn btn-sm btn-outline-primary pull-right no_radius"><i class="fa fa-plus-circle"></i> <?php echo $this->lang->line("add more template");?></button>
                    </div>

                  </div>

                  <div class="row" id="list_div_<?php echo $k; ?>" style="display: none; padding-top: 20px;">  
                     <div class="col-xs-12">
                        <div class="row" id="list_with_buttons_row">
                           <div class="col-xs-12 col-sm-4 col-md-4">
                              <div class="form-group">
                                 <label><?php echo $this->lang->line("bottom button text"); ?></label>
                                 <input type="text" class="form-control"  name="list_with_buttons_text_<?php echo $k; ?>" id="list_with_buttons_text_<?php echo $k; ?>" value="<?php if(isset($full_message[$k]['attachment']['payload']['buttons'][0]['title'])) echo $full_message[$k]['attachment']['payload']['buttons'][0]['title']; ?>">
                              </div>
                           </div>
                           <div class="col-xs-12 col-sm-4 col-md-4">
                              <div class="form-group">
                                 <label><?php echo $this->lang->line("bottom button type"); ?></label>
                                 <select class="form-control list_with_button_type_class" id="list_with_button_type_<?php echo $k; ?>" name="list_with_button_type_<?php echo $k; ?>">
                                    <option value=""><?php echo $this->lang->line('please select a type'); ?></option>
                                    <option value="post_back" <?php if(isset($full_message[$k]['attachment']['payload']['buttons'][0]['type']) && $full_message[$k]['attachment']['payload']['buttons'][0]['type'] == 'postback') echo 'selected'; ?> ><?php echo $this->lang->line("Post Back"); ?></option>
                                    <option value="web_url" <?php if(isset($full_message[$k]['attachment']['payload']['buttons'][0]['type']) && $full_message[$k]['attachment']['payload']['buttons'][0]['type'] == 'web_url') echo 'selected'; ?> ><?php echo $this->lang->line("Web URL"); ?></option>
                                    <option value="phone_number" <?php if(isset($full_message[$k]['attachment']['payload']['buttons'][0]['type']) && $full_message[$k]['attachment']['payload']['buttons'][0]['type'] == 'phone_number') echo 'selected'; ?> ><?php echo $this->lang->line("call us"); ?></option>
                                 </select>
                              </div>
                           </div>
                           <div class="col-xs-12 col-sm-4 col-md-4">
                              <div class="form-group" id="list_with_button_postid_div_<?php echo $k; ?>" <?php if(!isset($full_message[$k]['attachment']['payload']['buttons'][0]['payload']) || $full_message[$k]['attachment']['payload']['buttons'][0]['type'] != 'postback') echo 'style="display: none;"'; ?> >

                                <?php 
                                $label_style = '';
                                if(isset($full_message[$k]['attachment']['payload']['buttons'][0]['payload']) && ($full_message[$k]['attachment']['payload']['buttons'][0]['payload'] == 'UNSUBSCRIBE_QUICK_BOXER' || $full_message[$k]['attachment']['payload']['buttons'][0]['payload'] == 'RESUBSCRIBE_QUICK_BOXER'  || $full_message[$k]['attachment']['payload']['buttons'][0]['payload'] == 'YES_START_CHAT_WITH_HUMAN' || $full_message[$k]['attachment']['payload']['buttons'][0]['payload'] == 'YES_START_CHAT_WITH_BOT')) 
                                  $label_style = 'style="display: none;"'; 
                                ?>
                                <label <?php echo $label_style; ?> ><?php echo $this->lang->line("PostBack id"); ?></label>

                                <?php 
                                if(isset($full_message[$k]['attachment']['payload']['buttons'][0]['payload']) && ($full_message[$k]['attachment']['payload']['buttons'][0]['payload'] == 'UNSUBSCRIBE_QUICK_BOXER' || $full_message[$k]['attachment']['payload']['buttons'][0]['payload'] == 'RESUBSCRIBE_QUICK_BOXER'  || $full_message[$k]['attachment']['payload']['buttons'][0]['payload'] == 'YES_START_CHAT_WITH_HUMAN' || $full_message[$k]['attachment']['payload']['buttons'][0]['payload'] == 'YES_START_CHAT_WITH_BOT')) 
                                  $input_type = 'hidden'; 
                                else 
                                  $input_type = 'text'; 
                                ?>
                                 <?php $pname="list_with_button_post_id_".$k; ?>
                                 <input type="<?php echo $input_type; ?>" class="form-control push_postback"  name="list_with_button_post_id_<?php echo $k; ?>" id="list_with_button_post_id_<?php echo $k; ?>" value="<?php if(isset($full_message[$k]['attachment']['payload']['buttons'][0]['payload']) && $full_message[$k]['attachment']['payload']['buttons'][0]['type'] == 'postback') echo $full_message[$k]['attachment']['payload']['buttons'][0]['payload']; ?>">
                              </div>
                              <div class="form-group" id="list_with_button_web_url_div_<?php echo $k; ?>" <?php if(!isset($full_message[$k]['attachment']['payload']['buttons'][0]['url'])) echo 'style="display: none;"'; ?> >
                                 <label><?php echo $this->lang->line("web url"); ?></label>
                                 <input type="text" class="form-control"  name="list_with_button_web_url_<?php echo $k; ?>" id="list_with_button_web_url_<?php echo $k; ?>" value="<?php if(isset($full_message[$k]['attachment']['payload']['buttons'][0]['url'])) echo $full_message[$k]['attachment']['payload']['buttons'][0]['url']; ?>" >
                              </div>
                              <div class="form-group" id="list_with_button_call_us_div_<?php echo $k; ?>" <?php if(!isset($full_message[$k]['attachment']['payload']['buttons'][0]['payload']) || $full_message[$k]['attachment']['payload']['buttons'][0]['type'] != 'phone_number') echo 'style="display: none;"'; ?> >
                                 <label><?php echo $this->lang->line("phone number"); ?></label>
                                 <input type="text" class="form-control"  name="list_with_button_call_us_<?php echo $k; ?>" id="list_with_button_call_us_<?php echo $k; ?>" value="<?php if(isset($full_message[$k]['attachment']['payload']['buttons'][0]['payload']) && $full_message[$k]['attachment']['payload']['buttons'][0]['type'] == 'phone_number') echo $full_message[$k]['attachment']['payload']['buttons'][0]['payload']; ?>" >
                              </div>
                           </div>
                        </div>
                     </div>

                     <?php for ($j=1; $j <=4 ; $j++) : ?>
                        <div class="col-xs-12" id="list_div_<?php echo $j; ?>_<?php echo $k; ?>" style="<?php if(!isset($full_message[$k]['attachment']['payload']['elements'][$j-1])) echo 'display: none;'; ?> padding-top: 20px;"> 
                           <div style="border: 1px dashed #ccc; background:#fcfcfc;padding:10px 15px;">
                              <div class="row">
                                 <div class="col-xs-12 col-md-6">
                                    <div class="form-group">
                                       <label><?php echo $this->lang->line("Please provide your reply image"); ?></label>

                                       <span class="label label-light blue load_preview_modal pull-right" item_type="image" file_path="<?php if(isset($full_message[$k]['template_type']) && $full_message[$k]['template_type'] == 'list' && isset($full_message[$k]['attachment']['payload']['elements'][$j-1]['image_url'])) echo $full_message[$k]['attachment']['payload']['elements'][$j-1]['image_url'];?>"><i class="fa fa-eye"></i><?php echo $this->lang->line('preview'); ?></span>

                                       <input type="hidden" class="form-control"  name="list_image_<?php echo $j; ?>_<?php echo $k; ?>" id="list_image_<?php echo $j; ?>_<?php echo $k; ?>" value="<?php if(isset($full_message[$k]['template_type']) && $full_message[$k]['template_type'] == 'list' && isset($full_message[$k]['attachment']['payload']['elements'][$j-1]['image_url'])) echo $full_message[$k]['attachment']['payload']['elements'][$j-1]['image_url'];?>" />
                                       <div id="list_imageupload_<?php echo $j; ?>_<?php echo $k; ?>"><?php echo $this->lang->line('upload'); ?></div>
                                    </div>                         
                                 </div>
                                 <div class="col-xs-12 col-md-6">
                                    <div class="form-group">
                                       <label><?php echo $this->lang->line("image click destination link"); ?></label>
                                       <input type="text" class="form-control"  name="list_image_destination_link_<?php echo $j; ?>_<?php echo $k; ?>" id="list_image_destination_link_<?php echo $j; ?>_<?php echo $k; ?>" value="<?php if(isset($full_message[$k]['template_type']) && $full_message[$k]['template_type'] == 'list' && isset($full_message[$k]['attachment']['payload']['elements'][$j-1]['default_action']['url'])) echo $full_message[$k]['attachment']['payload']['elements'][$j-1]['default_action']['url'];?>" />
                                    </div> 
                                 </div>                      
                              </div>

                              <div class="row">
                                 <div class="col-xs-12 col-md-6">
                                    <div class="form-group">
                                       <label><?php echo $this->lang->line("title"); ?></label>
                                       <input type="text" class="form-control"  name="list_title_<?php echo $j; ?>_<?php echo $k; ?>" id="list_title_<?php echo $j; ?>_<?php echo $k; ?>" value="<?php if(isset($full_message[$k]['template_type']) && $full_message[$k]['template_type'] == 'list' && isset($full_message[$k]['attachment']['payload']['elements'][$j-1]['title'])) echo $full_message[$k]['attachment']['payload']['elements'][$j-1]['title'];?>" />
                                    </div>
                                 </div>  
                                 <div class="col-xs-12 col-md-6">
                                    <div class="form-group">
                                       <label><?php echo $this->lang->line("sub-title"); ?></label>
                                       <input type="text" class="form-control"  name="list_subtitle_<?php echo $j; ?>_<?php echo $k; ?>" id="list_subtitle_<?php echo $j; ?>_<?php echo $k; ?>" value="<?php if(isset($full_message[$k]['template_type']) && $full_message[$k]['template_type'] == 'list' && isset($full_message[$k]['attachment']['payload']['elements'][$j-1]['subtitle'])) echo $full_message[$k]['attachment']['payload']['elements'][$j-1]['subtitle'];?>" />
                                    </div>
                                 </div>  
                              </div>
                           </div>
                        </div>
                     <?php endfor; ?>

                     <div class="col-xs-12 clearfix">
                        <button <?php if(isset($full_message[$k]['template_type']) && $full_message[$k]['template_type'] == 'list' && count($full_message[$k]['attachment']['payload']['elements']) == 4) echo "style='display : none;'"; ?> id="list_template_add_button_<?php echo $k; ?>" class="btn btn-sm btn-outline-primary pull-right no_radius"><i class="fa fa-plus-circle"></i> <?php echo $this->lang->line("add more template");?></button>
                     </div>

                  </div>


                </div>
              </div>
          <?php } ?>
                <div class="row">
                  <div class="col-xs-6">
                    <br><button id="submit" class="btn btn-lg btn-primary"><i class="fa fa-send"></i> <?php echo $this->lang->line('Update'); ?></button>
                  </div>
                  <div class="col-xs-6 clearfix">
                    <button id="multiple_template_add_button" class="btn btn-outline-primary pull-right no_radius" <?php if($active_reply_count==3) echo 'style="display: none;"'; ?> ><i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('add more reply'); ?></button>
                  </div>
                </div>
              </form>
            </div>

            <div class="hidden-xs hidden-sm col-md-3 img_holder" style="" >
              <div id="text_preview_div" style="">
                <!-- <center><h4><b>Text</b></h4></center> -->
                <center><img src="<?php if(file_exists(FCPATH.'assets/images/preview/text.png')) echo site_url()."assets/images/preview/text.png"; else echo "https://mysitespy.net/2waychat_demo/msgbot_demo/preview/text.png"; ?>" class="img-rounded" alt="Text Preview"></center>
              </div>

              <div id="image_preview_div" style="display: none;">
                <!-- <center><h4><b>Image</b></h4></center> -->
                <center><img src="<?php if(file_exists(FCPATH.'assets/images/preview/image.png')) echo site_url()."assets/images/preview/image.png"; else echo "https://mysitespy.net/2waychat_demo/msgbot_demo/preview/image.png"; ?>" class="img-rounded" alt="Image Preview"></center>
              </div>

              <div id="audio_preview_div" style="display: none;">
                <!-- <center><h4><b>Audio</b></h4></center> -->
                <center><img src="<?php if(file_exists(FCPATH.'assets/images/preview/mp3.png')) echo site_url()."assets/images/preview/mp3.png"; else echo "https://mysitespy.net/2waychat_demo/msgbot_demo/preview/mp3.png"; ?>" class="img-rounded" alt="Audio Preview"></center>
              </div>

              <div id="video_preview_div" style="display: none;">
                <!-- <center><h4><b>Video</b></h4></center> -->
                <center><img src="<?php if(file_exists(FCPATH.'assets/images/preview/video.png')) echo site_url()."assets/images/preview/video.png"; else echo "https://mysitespy.net/2waychat_demo/msgbot_demo/preview/video.png"; ?>" class="img-rounded" alt="Video Preview"></center>
              </div>

              <div id="file_preview_div" style="display: none;">
                <!-- <center><h4><b>File</b></h4></center> -->
                <center><img src="<?php if(file_exists(FCPATH.'assets/images/preview/file.png')) echo site_url()."assets/images/preview/file.png"; else echo "https://mysitespy.net/2waychat_demo/msgbot_demo/preview/file.png"; ?>" class="img-rounded" alt="File Preview"></center>
              </div>

              <div id="quick_reply_preview_div" style="display: none;">
                <!-- <center><h4><b>Quick Reply</b></h4></center> -->
                <center><img src="<?php if(file_exists(FCPATH.'assets/images/preview/quick_reply.png')) echo site_url()."assets/images/preview/quick_reply.png"; else echo "https://mysitespy.net/2waychat_demo/msgbot_demo/preview/quick_reply.png"; ?>" class="img-rounded" alt="Quick Reply Preview"></center>
              </div>

              <div id="text_with_buttons_preview_div" style="display: none;">
                <!-- <center><h4><b>Text with buttons</b></h4></center> -->
                <center><img src="<?php if(file_exists(FCPATH.'assets/images/preview/button.png')) echo site_url()."assets/images/preview/button.png"; else echo "https://mysitespy.net/2waychat_demo/msgbot_demo/preview/button.png"; ?>" class="img-rounded" alt="Text With Buttons Preview"></center>
              </div>

              <div id="generic_template_preview_div" style="display: none;">
                <!-- <center><h4><b>Generic Template</b></h4></center> -->
                <center><img src="<?php if(file_exists(FCPATH.'assets/images/preview/generic.png')) echo site_url()."assets/images/preview/generic.png"; else echo "https://mysitespy.net/2waychat_demo/msgbot_demo/preview/generic.png"; ?>" class="img-rounded" alt="Generic Template Preview"></center>
              </div>

              <div id="carousel_preview_div" style="display: none;">
                <!-- <center><h4><b>Carousel Template</b></h4></center> -->
                <center><img src="<?php if(file_exists(FCPATH.'assets/images/preview/carousel.png')) echo site_url()."assets/images/preview/carousel.png"; else echo "https://mysitespy.net/2waychat_demo/msgbot_demo/preview/carousel.png"; ?>" class="img-rounded" alt="Carousel Template Preview"></center>
              </div>

              <div id="list_preview_div" style="display: none;">
                <center><img src="<?php if(file_exists(FCPATH.'assets/images/preview/list.png')) echo site_url()."assets/images/preview/list.png"; else echo "https://mysitespy.net/2waychat_demo/msgbot_demo/preview/list.png"; ?>" class="img-rounded" alt="List Template Preview"></center>
              </div>

              <div id="media_preview_div" style="display: none;">
                <center><img src="<?php if(file_exists(FCPATH.'assets/images/preview/media.png')) echo site_url()."assets/images/preview/media.png"; else echo "https://mysitespy.net/2waychat_demo/msgbot_demo/preview/media.png"; ?>" class="img-rounded" alt="Media Template Preview"></center>
              </div>

            </div>
           
          </div>
          <br>
          <div id="submit_status" class="text-center"></div>
        </div>
        <div class="modal-footer">
        </div>
      </div>
    </div>
  </div>

  <br>
  <?php if($this->session->flashdata('bot_success')===1) { ?>
  <div class="alert alert-success text-center" id="bot_success"><i class="fa fa-check"></i> <?php echo $this->lang->line("Bot settings has been updated successfully.");?></div>
  <?php } ?>
</div>

<?php 
$areyousure=$this->lang->line("are you sure"); 
$somethingwentwrong = $this->lang->line("something went wrong.");  
$doyoureallywanttodeletethisbot = $this->lang->line("do you really want to delete this bot?");
?>

<script type="text/javascript">

$(document).ready(function(){
	$j("#text_reply_1, #text_reply_2, #text_reply_3, #quick_reply_text_1, #quick_reply_text_2, #quick_reply_text_3, #text_with_buttons_input_1, #text_with_buttons_input_2, #text_with_buttons_input_3").emojioneArea({
			autocomplete: false,
			pickerPosition: "bottom"
	  });

  $j("#label_ids").multipleSelect({
      filter: true,
      multiple: true
  });
});

	


  $(document).ready(function(e){

    $(document.body).on('click','.load_preview_modal',function(e){
      e.preventDefault();
      var item_type = $(this).attr('item_type');
      var file_path = $(this).next().val();
      $("#preview_text_field").val(file_path);
      if(item_type == 'image')
      {
        $("#modal_preview_image").attr('src',file_path);
        $("#image_preview_div_modal").show();
        $("#video_preview_div_modal").hide();
        $("#audio_preview_div_modal").hide();
        
      }
      if(item_type == 'video')
      {
        var html_content = "<source src='"+file_path+"' type='video/mp4'>";
        $("#modal_preview_video").html(html_content);
        $("#image_preview_div_modal").hide();
        $("#audio_preview_div_modal").hide();
        $("#video_preview_div_modal").show();
      }
      if(item_type == 'audio')
      {
        var html_content = "<source src='"+file_path+"' type='audio/ogg'>";
        $("#modal_preview_audio").html(html_content);
        $("#image_preview_div_modal").hide();
        $("#video_preview_div_modal").hide();
        $("#audio_preview_div_modal").show();
      }
      $("#modal_for_preview").modal();
    });

    $( document ).on( 'click', '.bs-dropdown-to-select-group .dropdown-menu li', function( event ) {
      var $target = $( event.currentTarget );
      $target.closest('.bs-dropdown-to-select-group')
      .find('[data-bind="bs-drp-sel-value"]').val($target.attr('data-value'))
      .end()
      .children('.dropdown-toggle').dropdown('toggle');
      $target.closest('.bs-dropdown-to-select-group')
      .find('[data-bind="bs-drp-sel-label"]').text($target.context.textContent);
      return false;
    });

    $(document.body).on('click','.media_template_modal',function(){
       $("#media_template_modal").modal();
    });


  });

  var multiple_template_add_button_counter = <?php echo $active_reply_count; ?>;
  $(document.body).on('click','#multiple_template_add_button',function(e){
    e.preventDefault();
    multiple_template_add_button_counter++
    $("#multiple_template_div_"+multiple_template_add_button_counter).show();
    $("#multiple_template_div_"+multiple_template_add_button_counter).find(".remove_reply").show();
    if(multiple_template_add_button_counter == 3){
      var previous_div_id_counter = multiple_template_add_button_counter-1;
      $("#multiple_template_div_"+previous_div_id_counter).find(".remove_reply").hide();
      $("#multiple_template_add_button").hide();
    }
  });

  $(document.body).on('click','.remove_reply',function(){
    var remove_reply_counter_variable = "multiple_template_add_button_counter";
    var remove_reply_row_id = $(this).attr('row_id');
    $("#"+remove_reply_row_id).find('textarea,input,select').val('');

    $("#"+remove_reply_row_id).hide();
    eval(remove_reply_counter_variable+"--");
    var temp = eval(remove_reply_counter_variable);
    if(temp != 1)
    {
      $("#multiple_template_div_"+temp).find(".remove_reply").show();
    }
    if(temp < 3) $("#multiple_template_add_button").show();
  });

  $(document.body).on('click','.lead_first_name',function(){
  
     	var textAreaTxt = $(this).parent().next().next().next().children('.emojionearea-editor').html();
		
		var lastIndex = textAreaTxt.lastIndexOf("<br>");
		
		if(lastIndex!='-1')
			textAreaTxt = textAreaTxt.substring(0, lastIndex);
			
	    var txtToAdd = " #LEAD_USER_FIRST_NAME# ";
	    var new_text = textAreaTxt + txtToAdd;
	   	$(this).parent().next().next().next().children('.emojionearea-editor').html(new_text);
	   	$(this).parent().next().next().next().children('.emojionearea-editor').click();
		
  });

  $(document.body).on('click','.lead_last_name',function(){

     	var textAreaTxt = $(this).parent().next().next().next().next().children('.emojionearea-editor').html();
		
		var lastIndex = textAreaTxt.lastIndexOf("<br>");
		
		if(lastIndex!='-1')
			textAreaTxt = textAreaTxt.substring(0, lastIndex);
			
	    var txtToAdd = " #LEAD_USER_LAST_NAME# ";
		var new_text = textAreaTxt + txtToAdd;
	   $(this).parent().next().next().next().next().children('.emojionearea-editor').html(new_text);
	   $(this).parent().next().next().next().next().children('.emojionearea-editor').click();
	   
  });
  
</script>


<script type="text/javascript">

  var user_id = "<?php echo $this->session->userdata('user_id'); ?>";
  var base_url="<?php echo site_url(); ?>";
  var areyousure="<?php echo $areyousure;?>";


  <?php foreach($page_list as $key=>$value) : ?>    
    var js_array_<?php echo $key ?> = [<?php echo ""; ?>];
  <?php endforeach; ?> 

  <?php foreach($total_postback_id_array as $key=>$value) : ?>    
    var js_array_<?php echo $key ?> = [<?php echo '"'.implode('","', $value ).'"' ?>];
  <?php endforeach; ?> 

  

  //start rakib work 
  var keyword_type = $("input[name=keyword_type]:checked").val();
  if(keyword_type == 'reply')
  {
    $("#keywords_div").show();
  }else{
    $("#keywords_div").hide();
  }

  $(document.body).on('change','input[name=keyword_type]',function(){
    if($("input[name=keyword_type]:checked").val()=="reply")
    {
      $("#keywords_div").show();
    }
    else 
    {
      $("#keywords_div").hide();
    }
  });

  // end rakib work


  var keyword_type = $("input[name=keyword_type]:checked").val();
  if(keyword_type == 'post-back')
  {
    $("#postback_div").show();
  }

  $(document.body).on('change','input[name=keyword_type]',function(){    
    if($("input[name=keyword_type]:checked").val()=="post-back")
    {
      $("#postback_div").show();
    }
    else 
    {
      $("#postback_div").hide();
    }
  });

  var image_upload_limit = "<?php echo $image_upload_limit; ?>";
  var video_upload_limit = "<?php echo $video_upload_limit; ?>";
  var audio_upload_limit = "<?php echo $audio_upload_limit; ?>";
  var file_upload_limit = "<?php echo $file_upload_limit; ?>";

  <?php for($template_type=1;$template_type<=3;$template_type++){ ?>

      var template_type_order="#template_type_<?php echo $template_type ?>";

      $("#image_reply_<?php echo $template_type; ?>").uploadFile({
        url:base_url+"messenger_bot/upload_image_only",
        fileName:"myfile",
        maxFileSize:image_upload_limit*1024*1024,
        showPreview:false,
        returnType: "json",
        dragDrop: true,
        showDelete: true,
        multiple:false,
        maxFileCount:1, 
        acceptFiles:".png,.jpg,.jpeg,.JPEG,.JPG,.PNG,.gif,.GIF",
        deleteCallback: function (data, pd) {
            var delete_url="<?php echo site_url('messenger_bot/delete_uploaded_file');?>";
            $.post(delete_url, {op: "delete",name: data},
                function (resp,textStatus, jqXHR) {
                  $("#image_reply_field_<?php echo $template_type; ?>").val('');  
                  $("#image_reply_div_<?php echo $template_type; ?>").hide();                     
                });
           
         },
         onSuccess:function(files,data,xhr,pd)
           {
               var data_modified = base_url+"upload/image/"+user_id+"/"+data;
               $("#image_reply_field_<?php echo $template_type; ?>").val(data_modified);   
               $("#image_reply_div_<?php echo $template_type; ?>").show().attr('src',data_modified);   
           }
      });


      $("#video_reply_<?php echo $template_type; ?>").uploadFile({
        url:base_url+"messenger_bot/upload_live_video",
        fileName:"myfile",
        maxFileSize:video_upload_limit*1024*1024,
        showPreview:false,
        returnType: "json",
        dragDrop: true,
        showDelete: true,
        multiple:false,
        maxFileCount:1, 
        acceptFiles:".flv,.mp4,.wmv,.WMV,.MP4,.FLV",
        deleteCallback: function (data, pd) {
          var delete_url="<?php echo site_url('messenger_bot/delete_uploaded_live_file');?>";
          $.post(delete_url, {op: "delete",name: data},
            function (resp,textStatus, jqXHR) {  
                $("#video_reply_field_<?php echo $template_type; ?>").val('');  
                $("#video_tag_<?php echo $template_type; ?>").hide();             
            });

        },
        onSuccess:function(files,data,xhr,pd)
        {
          var file_path = base_url+"upload/video/"+data;
          $("#video_reply_field_<?php echo $template_type; ?>").val(file_path);   
          $("#video_tag_<?php echo $template_type; ?>").show();
          $("#video_reply_div_<?php echo $template_type; ?>").attr('src',file_path); 
        }
      });

      $("#audio_reply_<?php echo $template_type; ?>").uploadFile({
        url:base_url+"messenger_bot/upload_audio_file",
        fileName:"myfile",
        maxFileSize:audio_upload_limit*1024*1024,
        showPreview:false,
        returnType: "json",
        dragDrop: true,
        showDelete: true,
        multiple:false,
        maxFileCount:1, 
        acceptFiles:".amr,.mp3,.wav,.WAV,.MP3,.AMR",
        deleteCallback: function (data, pd) {
          var delete_url="<?php echo site_url('messenger_bot/delete_audio_file');?>";
          $.post(delete_url, {op: "delete",name: data},
            function (resp,textStatus, jqXHR) {  
                $("#audio_reply_field_<?php echo $template_type; ?>").val('');  
                $("#audio_tag_<?php echo $template_type; ?>").hide();             
            });

        },
        onSuccess:function(files,data,xhr,pd)
        {
          var file_path = base_url+"upload/audio/"+data;
          $("#audio_reply_field_<?php echo $template_type; ?>").val(file_path);   
          $("#audio_tag_<?php echo $template_type; ?>").show();
          $("#audio_reply_div_<?php echo $template_type; ?>").attr('src',file_path); 
        }
      });

      $("#file_reply_<?php echo $template_type; ?>").uploadFile({
        url:base_url+"messenger_bot/upload_general_file",
        fileName:"myfile",
        maxFileSize:file_upload_limit*1024*1024,
        showPreview:false,
        returnType: "json",
        dragDrop: true,
        
        showDelete: true,
        multiple:false,
        maxFileCount:1, 
        acceptFiles:".doc,.docx,.pdf,.txt,.ppt,.pptx,.xls,.xlsx,.DOC,.DOCX,.PDF,.TXT,.PPT,.PPTX,.XLS,.XLSX",
        deleteCallback: function (data, pd) {
          var delete_url="<?php echo site_url('messenger_bot/delete_general_file');?>";
          $.post(delete_url, {op: "delete",name: data},
            function (resp,textStatus, jqXHR) {  
                $("#file_reply_field_<?php echo $template_type; ?>").val('');            
            });

        },
        onSuccess:function(files,data,xhr,pd)
        {
          var file_path = base_url+"upload/file/"+data;
          $("#file_reply_field_<?php echo $template_type; ?>").val(file_path);   
        }
      });


      $("#generic_image_<?php echo $template_type; ?>").uploadFile({
        url:base_url+"messenger_bot/upload_image_only",
        fileName:"myfile",
        maxFileSize:image_upload_limit*1024*1024,
        showPreview:false,
        returnType: "json",
        dragDrop: true,
        showDelete: true,
        multiple:false,
        maxFileCount:1, 
        acceptFiles:".png,.jpg,.jpeg,.JPEG,.JPG,.PNG,.gif,.GIF",
        deleteCallback: function (data, pd) {
            var delete_url="<?php echo site_url('messenger_bot/delete_uploaded_file');?>";
            $.post(delete_url, {op: "delete",name: data},
                function (resp,textStatus, jqXHR) {
                  $("#generic_template_image_<?php echo $template_type; ?>").val('');                   
                });
           
         },
         onSuccess:function(files,data,xhr,pd)
           {
               var data_modified = base_url+"upload/image/"+user_id+"/"+data;
               $("#generic_template_image_<?php echo $template_type; ?>").val(data_modified);     
           }
      });

  
      <?php for($i=1; $i<=10; $i++) : ?>
        $("#generic_imageupload_<?php echo $i; ?>_<?php echo $template_type; ?>").uploadFile({
          url:base_url+"messenger_bot/upload_image_only",
          fileName:"myfile",
          maxFileSize:image_upload_limit*1024*1024,
          showPreview:false,
          returnType: "json",
          dragDrop: true,
          showDelete: true,
          multiple:false,
          maxFileCount:1, 
          acceptFiles:".png,.jpg,.jpeg,.JPEG,.JPG,.PNG,.gif,.GIF",
          deleteCallback: function (data, pd) {
              var delete_url="<?php echo site_url('messenger_bot/delete_uploaded_file');?>";
              $.post(delete_url, {op: "delete",name: data},
                  function (resp,textStatus, jqXHR) {
                    $("#carousel_image_<?php echo $i; ?>_<?php echo $template_type; ?>").val('');                      
                  });
             
           },
           onSuccess:function(files,data,xhr,pd)
             {
                 var data_modified = base_url+"upload/image/"+user_id+"/"+data;
                 $("#carousel_image_<?php echo $i; ?>_<?php echo $template_type; ?>").val(data_modified);     
             }
        });
      <?php endfor; ?>

      <?php for($i=1; $i<=4; $i++) : ?>
        $("#list_imageupload_<?php echo $i; ?>_<?php echo $template_type; ?>").uploadFile({
          url:base_url+"messenger_bot/upload_image_only",
          fileName:"myfile",
          maxFileSize:image_upload_limit*1024*1024,
          showPreview:false,
          returnType: "json",
          dragDrop: true,
          showDelete: true,
          multiple:false,
          maxFileCount:1, 
          acceptFiles:".png,.jpg,.jpeg,.JPEG,.JPG,.PNG,.gif,.GIF",
          deleteCallback: function (data, pd) {
              var delete_url="<?php echo site_url('messenger_bot/delete_uploaded_file');?>";
              $.post(delete_url, {op: "delete",name: data},
                  function (resp,textStatus, jqXHR) {
                    $("#list_image_<?php echo $i; ?>_<?php echo $template_type; ?>").val('');                      
                  });
             
           },
           onSuccess:function(files,data,xhr,pd)
             {
                 var data_modified = base_url+"upload/image/"+user_id+"/"+data;
                 $("#list_image_<?php echo $i; ?>_<?php echo $template_type; ?>").val(data_modified);     
             }
        });
      <?php endfor; ?>

      $j("document").ready(function(){
        var selected_template = $("#template_type_<?php echo $template_type ?>").val();
        selected_template = selected_template.replace(/ /gi, "_");

        var template_type_array = ['text','image','audio','video','file','quick_reply','text_with_buttons','generic_template','carousel','list','media'];
        template_type_array.forEach(templates_hide_show_function);
        function templates_hide_show_function(item, index)
        {
          var template_type_preview_div_name = "#"+item+"_preview_div";

          var template_type_div_name = "#"+item+"_div_<?php echo $template_type; ?>";
          if(selected_template == item){
            $(template_type_div_name).show();
            $(template_type_preview_div_name).show();
          }
          else{
            $(template_type_div_name).hide();
            $(template_type_preview_div_name).hide();
          }

          if(selected_template == 'quick_reply')
          {
            $("#quick_reply_row_1_<?php echo $template_type; ?>").show();
          }

          if(selected_template == 'media')
          {
            $("#media_row_1_<?php echo $template_type; ?>").show();     
          }

          if(selected_template == 'text_with_buttons')
          {
            $("#text_with_buttons_row_1_<?php echo $template_type; ?>").show();
          }

          if(selected_template == 'generic_template')
          {
            $("#generic_template_row_1_<?php echo $template_type; ?>").show();
          }

          if(selected_template == 'carousel')
          {
            $("#carousel_div_1_<?php echo $template_type; ?>").show();
            for (var i = 1; i <= 10; i++) 
            {
              $("#carousel_row_"+i+"_1_<?php echo $template_type; ?>").show();
            }
          }

          if(selected_template == 'list')
          {
            $("#list_div_1_<?php echo $template_type; ?>").show();
            $("#list_div_2_<?php echo $template_type; ?>").show();
          }

        }
      });


      $j(document.body).on('change',"#template_type_<?php echo $template_type ?>",function(){
      
        var selected_template_on_change = $("#template_type_<?php echo $template_type ?>").val();
        selected_template_on_change = selected_template_on_change.replace(/ /gi, "_");

        var template_type_array = ['text','image','audio','video','file','quick_reply','text_with_buttons','generic_template','carousel','list','media'];
        template_type_array.forEach(templates_hide_show_function);
        function templates_hide_show_function(item, index)
        {
          var template_type_preview_div_name = "#"+item+"_preview_div";

          var template_type_div_name = "#"+item+"_div_<?php echo $template_type; ?>";
          if(selected_template_on_change == item){
            $(template_type_div_name).show();
            $(template_type_preview_div_name).show();
          }
          else{
            $(template_type_div_name).hide();
            $(template_type_preview_div_name).hide();
          }

          if(selected_template_on_change == 'quick_reply')
          {
            $("#quick_reply_row_1_<?php echo $template_type; ?>").show();
          }

          if(selected_template_on_change == 'media')
          {
            $("#media_row_1_<?php echo $template_type; ?>").show();     
          }

          if(selected_template_on_change == 'text_with_buttons')
          {
            $("#text_with_buttons_row_1_<?php echo $template_type; ?>").show();
          }

          if(selected_template_on_change == 'generic_template')
          {
            $("#generic_template_row_1_<?php echo $template_type; ?>").show();
          }

          if(selected_template_on_change == 'carousel')
          {
            $("#carousel_div_1_<?php echo $template_type; ?>").show();
            $("#carousel_row_1_1_<?php echo $template_type; ?>").show();
          }

          if(selected_template_on_change == 'list')
          {
            $("#list_div_1_<?php echo $template_type; ?>").show();
            $("#list_div_2_<?php echo $template_type; ?>").show();
          }

        }
      });



      var quick_reply_button_counter_<?php echo $template_type; ?> = "<?php if (isset($full_message[$template_type]['quick_replies'])) echo count($full_message[$template_type]['quick_replies']); else echo 1; ?>";

    
      $j(document.body).on('click',"#quick_reply_add_button_<?php echo $template_type; ?>",function(e){
        e.preventDefault();
      
        var button_id = quick_reply_button_counter_<?php echo $template_type; ?>;      
        var quick_reply_button_text = "#quick_reply_button_text_"+button_id+"_<?php echo $template_type; ?>";
        var quick_reply_post_id = "#quick_reply_post_id_"+button_id+"_<?php echo $template_type; ?>";
        var quick_reply_button_type = "#quick_reply_button_type_"+button_id+"_<?php echo $template_type; ?>";

        quick_reply_button_type = $(quick_reply_button_type).val();

        var quick_reply_post_id_check = $(quick_reply_post_id).val();

        if(quick_reply_button_type == 'post_back')
        {        
          if(quick_reply_post_id_check == ''){
            $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your PostBack Id')?>");
            $("#error_modal").modal();
            return;
          }
          /*
          var page_table_id = $("#page_table_id").val();
          var new_variable_name = "js_array_"+page_table_id;

          if(jQuery.inArray(quick_reply_post_id_check.toUpperCase(), eval(new_variable_name)) !== -1){
            $("#error_modal_content").html("<?php echo $this->lang->line('The PostBack ID you have given is allready exist. Please provide different PostBack Id')?>");
            $("#error_modal").modal();
            return ;
          }
          */
        }

        if(quick_reply_button_type == '')
        {
          $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Button Type')?>");
          $("#error_modal").modal();
          return;
        }

        

        var quick_reply_button_text_check = $(quick_reply_button_text).val();
        if(quick_reply_button_type == 'post_back')
        { 
          if(quick_reply_button_text_check == ''){
            $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Button Text')?>");
            $("#error_modal").modal();
            return;
          }
        }

        quick_reply_button_counter_<?php echo $template_type; ?>++;

        // remove button hide for current div and show for next div
        var div_id = "#quick_reply_button_type_"+button_id+"_<?php echo $template_type; ?>";
        $(div_id).parent().parent().next().next().hide();
        var next_item_remove_parent_div = $(div_id).parent().parent().parent().next().attr('id');
        $("#"+next_item_remove_parent_div+" div:last").show();
      
        var x=  quick_reply_button_counter_<?php echo $template_type; ?>;
        $("#quick_reply_row_"+x+"_<?php echo $template_type; ?>").show();
      
        if(quick_reply_button_counter_<?php echo $template_type; ?> == 11)
          $("#quick_reply_add_button_<?php echo $template_type; ?>").hide();

      });






      var media_counter_<?php echo $template_type; ?> = "<?php if (isset($full_message[$template_type]['attachment']['payload']['elements'][0]['buttons'])) echo count($full_message[$template_type]['attachment']['payload']['elements'][0]['buttons']); else echo 1; ?>";
  
     $j(document.body).on('click',"#media_add_button_<?php echo $template_type; ?>",function(e){
        e.preventDefault();

        var button_id = media_counter_<?php echo $template_type; ?>;
        var media_text = "#media_text_"+button_id+"_<?php echo $template_type; ?>";
        var media_type = "#media_type_"+button_id+"_<?php echo $template_type; ?>";

        var media_text_check = $(media_text).val();
        if(media_text_check == ''){
          $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Button Text')?>");
          $("#error_modal").modal();
          return;
        }

        var media_type_check = $(media_type).val();
        if(media_type_check == ''){
          $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Button Type')?>");
          $("#error_modal").modal();
          return;
        }else if(media_type_check == 'post_back'){

          var media_post_id = "#media_post_id_"+button_id+"_<?php echo $template_type; ?>";
          var media_post_id_check = $(media_post_id).val();
          if(media_post_id_check == ''){
            $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your PostBack Id')?>");
            $("#error_modal").modal();
            return;
          }
          /*
          var page_table_id = $("#page_table_id").val();
          var new_variable_name = "js_array_"+page_table_id;

          if(jQuery.inArray(media_post_id_check.toUpperCase(), eval(new_variable_name)) !== -1){
            $("#error_modal_content").html("<?php echo $this->lang->line('The PostBack ID you have given is allready exist. Please provide different PostBack Id')?>");
            $("#error_modal").modal();
            return ;
          }
          */
        }else if(media_type_check == 'web_url'){
          var media_web_url = "#media_web_url_"+button_id+"_<?php echo $template_type; ?>";
          var media_web_url_check = $(media_web_url).val();
          if(media_web_url_check == ''){
            $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Web Url')?>");
            $("#error_modal").modal();
            return;
          }
        }else if(media_type_check == 'phone_number'){
          var media_call_us = "#media_call_us_"+button_id+"_<?php echo $template_type; ?>";
          var media_call_us_check = $(media_call_us).val();
          if(media_call_us_check == ''){
            $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Phone Number')?>");
            $("#error_modal").modal();
            return;
          }
        }

        media_counter_<?php echo $template_type; ?>++;

        // remove button hide for current div and show for next div
        $(media_type).parent().parent().next().next().hide();
        var next_item_remove_parent_div = $(media_type).parent().parent().parent().next().attr('id');
        $("#"+next_item_remove_parent_div+" div:last").show();

        var x=media_counter_<?php echo $template_type; ?>;
        $("#media_row_"+x+"_<?php echo $template_type; ?>").show();
        if(media_counter_<?php echo $template_type; ?> == 3)
          $("#media_add_button_<?php echo $template_type; ?>").hide();
      });





     var text_with_button_counter_<?php echo $template_type; ?> = "<?php if (isset($full_message[$template_type]['attachment']['payload']['buttons'])) echo count($full_message[$template_type]['attachment']['payload']['buttons']); else echo 1; ?>";
  
     $j(document.body).on('click',"#text_with_button_add_button_<?php echo $template_type; ?>",function(e){
        e.preventDefault();

        var button_id = text_with_button_counter_<?php echo $template_type; ?>;
        var text_with_buttons_text = "#text_with_buttons_text_"+button_id+"_<?php echo $template_type; ?>";
        var text_with_button_type = "#text_with_button_type_"+button_id+"_<?php echo $template_type; ?>";

        var text_with_buttons_text_check = $(text_with_buttons_text).val();
        if(text_with_buttons_text_check == ''){
          $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Button Text')?>");
          $("#error_modal").modal();
          return;
        }

        var text_with_button_type_check = $(text_with_button_type).val();
        if(text_with_button_type_check == ''){
          $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Button Type')?>");
          $("#error_modal").modal();
          return;
        }else if(text_with_button_type_check == 'post_back'){

          var text_with_button_post_id = "#text_with_button_post_id_"+button_id+"_<?php echo $template_type; ?>";
          var text_with_button_post_id_check = $(text_with_button_post_id).val();
          if(text_with_button_post_id_check == ''){
            $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your PostBack Id')?>");
            $("#error_modal").modal();
            return;
          }
          /*
          var page_table_id = $("#page_table_id").val();
          var new_variable_name = "js_array_"+page_table_id;

          if(jQuery.inArray(text_with_button_post_id_check.toUpperCase(), eval(new_variable_name)) !== -1){
            $("#error_modal_content").html("<?php echo $this->lang->line('The PostBack ID you have given is allready exist. Please provide different PostBack Id')?>");
            $("#error_modal").modal();
            return ;
          }
          */
        }else if(text_with_button_type_check == 'web_url'){
          var text_with_button_web_url = "#text_with_button_web_url_"+button_id+"_<?php echo $template_type; ?>";
          var text_with_button_web_url_check = $(text_with_button_web_url).val();
          if(text_with_button_web_url_check == ''){
            $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Web Url')?>");
            $("#error_modal").modal();
            return;
          }
        }else if(text_with_button_type_check == 'phone_number'){
          var text_with_button_call_us = "#text_with_button_call_us_"+button_id+"_<?php echo $template_type; ?>";
          var text_with_button_call_us_check = $(text_with_button_call_us).val();
          if(text_with_button_call_us_check == ''){
            $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Phone Number')?>");
            $("#error_modal").modal();
            return;
          }
        }

        text_with_button_counter_<?php echo $template_type; ?>++;

        // remove button hide for current div and show for next div
        $(text_with_button_type).parent().parent().next().next().hide();
        var next_item_remove_parent_div = $(text_with_button_type).parent().parent().parent().next().attr('id');
        $("#"+next_item_remove_parent_div+" div:last").show();

        var x=text_with_button_counter_<?php echo $template_type; ?>;
        $("#text_with_buttons_row_"+x+"_<?php echo $template_type; ?>").show();
        if(text_with_button_counter_<?php echo $template_type; ?> == 3)
          $("#text_with_button_add_button_<?php echo $template_type; ?>").hide();
      });


     var  generic_with_button_counter_<?php echo $template_type; ?> = "<?php if(isset($full_message[$template_type]['attachment']['payload']['elements'][0]['buttons'])) echo count($full_message[$template_type]['attachment']['payload']['elements'][0]['buttons']); else echo 1; ?>";
  
    $j(document.body).on('click',"#generic_template_add_button_<?php echo $template_type; ?>",function(e){
      e.preventDefault();

      var button_id = generic_with_button_counter_<?php echo $template_type; ?>;
      var generic_template_button_text = "#generic_template_button_text_"+button_id+"_<?php echo $template_type; ?>";
      var generic_template_button_type = "#generic_template_button_type_"+button_id+"_<?php echo $template_type; ?>";

      var generic_template_button_text_check = $(generic_template_button_text).val();
      if(generic_template_button_text_check == ''){
        $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Button Text')?>");
        $("#error_modal").modal();
        return;
      }

      var generic_template_button_type_check = $(generic_template_button_type).val();
      if(generic_template_button_type_check == ''){
        $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Button Type')?>");
        $("#error_modal").modal();
        return;
      }else if(generic_template_button_type_check == 'post_back'){

        var generic_template_button_post_id = "#generic_template_button_post_id_"+button_id+"_<?php echo $template_type; ?>";
        var generic_template_button_post_id_check = $(generic_template_button_post_id).val();
        if(generic_template_button_post_id_check == ''){
          $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your PostBack Id')?>");
          $("#error_modal").modal();
          return;
        }
        /*
        var page_table_id = $("#page_table_id").val();
        var new_variable_name = "js_array_"+page_table_id;

        if(jQuery.inArray(generic_template_button_post_id_check.toUpperCase(), eval(new_variable_name)) !== -1){
          $("#error_modal_content").html("<?php echo $this->lang->line('The PostBack ID you have given is allready exist. Please provide different PostBack Id')?>");
          $("#error_modal").modal();
          return ;
        }
        */

      }else if(generic_template_button_type_check == 'web_url'){

        var generic_template_button_web_url = "#generic_template_button_web_url_"+button_id+"_<?php echo $template_type; ?>";
        var generic_template_button_web_url_check = $(generic_template_button_web_url).val();
        if(generic_template_button_web_url_check == ''){
          $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Web Url')?>");
          $("#error_modal").modal();
          return;
        }
      }else if(generic_template_button_type_check == 'phone_number'){
        var generic_template_button_call_us = "#generic_template_button_call_us_"+button_id+"_<?php echo $template_type; ?>";
        var generic_template_button_call_us_check = $(generic_template_button_call_us).val();
        if(generic_template_button_call_us_check == ''){
          $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Phone Number')?>");
          $("#error_modal").modal();
          return;
        }
      }

      generic_with_button_counter_<?php echo $template_type; ?>++;

      // remove button hide for current div and show for next div
      $(generic_template_button_type).parent().parent().next().next().hide();
      var next_item_remove_parent_div = $(generic_template_button_type).parent().parent().parent().next().attr('id');
      $("#"+next_item_remove_parent_div+" div:last").show();
    
      var x=generic_with_button_counter_<?php echo $template_type; ?>;
    
      $("#generic_template_row_"+x+"_<?php echo $template_type; ?>").show();
      if(generic_with_button_counter_<?php echo $template_type; ?> == 3)
        $("#generic_template_add_button_<?php echo $template_type; ?>").hide();
      });


    <?php for($j=1; $j<=10; $j++) : ?>
      
       var carousel_add_button_counter_<?php echo $j; ?>_<?php echo $template_type; ?> = "<?php if(isset($full_message[$template_type]['attachment']['payload']['elements'][$j-1]['buttons'])) echo count($full_message[$template_type]['attachment']['payload']['elements'][$j-1]['buttons']); else echo 1; ?>";
    
       $j(document.body).on('click',"#carousel_add_button_<?php echo $j; ?>_<?php echo $template_type; ?>",function(e){
         e.preventDefault();

         var y= carousel_add_button_counter_<?php echo $j; ?>_<?php echo $template_type; ?>;

         var carousel_button_text = "#carousel_button_text_<?php echo $j; ?>_"+y+"_<?php echo $template_type; ?>";
         var carousel_button_type = "#carousel_button_type_<?php echo $j; ?>_"+y+"_<?php echo $template_type; ?>";
    
         var carousel_button_text_check = $(carousel_button_text).val();
         if(carousel_button_text_check == ''){
           $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Button Text')?>");
           $("#error_modal").modal();
           return;
         }

         var carousel_button_type_check = $(carousel_button_type).val();
         if(carousel_button_type_check == ''){
           $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Button Type')?>");
           $("#error_modal").modal();
           return;
         }else if(carousel_button_type_check == 'post_back'){

           var carousel_button_post_id = "#carousel_button_post_id_<?php echo $j;?>_"+y+"_<?php echo $template_type; ?>";
           var carousel_button_post_id_check = $(carousel_button_post_id).val();
           if(carousel_button_post_id_check == ''){
             $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your PostBack Id')?>");
             $("#error_modal").modal();
             return;
           }
           /*
           var page_table_id = $("#page_table_id").val();
           var new_variable_name = "js_array_"+page_table_id;

           if(jQuery.inArray(carousel_button_post_id_check.toUpperCase(), eval(new_variable_name)) !== -1){
             $("#error_modal_content").html("<?php echo $this->lang->line('The PostBack ID you have given is allready exist. Please provide different PostBack Id')?>");
             $("#error_modal").modal();
             return ;
           }
           */
         }else if(carousel_button_type_check == 'web_url'){

           var carousel_button_web_url = "#carousel_button_web_url_<?php echo $j;?>_"+y+"_<?php echo $template_type; ?>";
           var carousel_button_web_url_check = $(carousel_button_web_url).val();
           if(carousel_button_web_url_check == ''){
             $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Web Url')?>");
             $("#error_modal").modal();
             return;
           }
         }else if(carousel_button_type_check == 'phone_number'){
          var carousel_button_call_us = "#carousel_button_call_us_<?php echo $j;?>_"+y+"_<?php echo $template_type; ?>";
          var carousel_button_call_us_check = $(carousel_button_call_us).val();
          if(carousel_button_call_us_check == ''){
            $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Phone Number')?>");
            $("#error_modal").modal();
            return;
          }
        }

          carousel_add_button_counter_<?php echo $j; ?>_<?php echo $template_type; ?> ++;

          // remove button hide for current div and show for next div
          $(carousel_button_type).parent().parent().next().next().hide();
          var next_item_remove_parent_div = $(carousel_button_type).parent().parent().parent().next().attr('id');
          $("#"+next_item_remove_parent_div+" div:last").show();

          var x= carousel_add_button_counter_<?php echo $j; ?>_<?php echo $template_type; ?>;
          $("#carousel_row_<?php echo $j; ?>_"+x+"_<?php echo $template_type; ?>").show();
          if(carousel_add_button_counter_<?php echo $j; ?>_<?php echo $template_type; ?> == 3)
            $("#carousel_add_button_<?php echo $j; ?>_<?php echo $template_type; ?>").hide();        

       });
    <?php endfor; ?>
    
    
    var carousel_template_counter_<?php echo $template_type; ?> = "<?php if(isset($full_message[$template_type]['attachment']['payload']['elements'])) echo count($full_message[$template_type]['attachment']['payload']['elements']); else echo 1; ?>";
    
    $j(document.body).on('click','#carousel_template_add_button_<?php echo $template_type; ?>',function(e){
         e.preventDefault();

         var carousel_image = "#carousel_image_"+carousel_template_counter_<?php echo $template_type; ?>+"_"+<?php echo $template_type; ?>;
         var carousel_image_check = $(carousel_image).val();
         // if(carousel_image_check == ''){
         //   $("#error_modal_content").html("<?php echo $this->lang->line('Please provide your reply image')?>");
         //   $("#error_modal").modal();
         //   return;
         // }

         var carousel_title = "#carousel_title_"+carousel_template_counter_<?php echo $template_type; ?>+"_"+<?php echo $template_type; ?>;
         var carousel_title_check = $(carousel_title).val();
         if(carousel_title_check == ''){
           $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide carousel title')?>");
           $("#error_modal").modal();
           return;
         }

        var carousel_subtitle = "#carousel_subtitle_"+carousel_template_counter_<?php echo $template_type; ?>+"_"+<?php echo $template_type; ?>;
        var carousel_subtitle_check = $(carousel_subtitle).val();
        // if(carousel_subtitle_check == ''){
        //   $("#error_modal_content").html("<?php echo $this->lang->line('Please give the sub-title')?>");
        //   $("#error_modal").modal();
        //   return;
        // }

         var carousel_image_destination_link = "#carousel_image_destination_link_"+carousel_template_counter_<?php echo $template_type; ?>+"_"+<?php echo $template_type; ?>;
         var carousel_image_destination_link_check = $(carousel_image_destination_link).val();
         // if(carousel_image_destination_link_check == ''){
         //   $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Image Click Destination Link')?>");
         //   $("#error_modal").modal();
         //   return;        
         // }

         carousel_template_counter_<?php echo $template_type; ?>++;
      
         var x = carousel_template_counter_<?php echo $template_type; ?>;
      
         $("#carousel_div_"+x+"_<?php echo $template_type; ?>").show();
         $("#carousel_row_"+x+"_1"+"_<?php echo $template_type; ?>").show();
         if( carousel_template_counter_<?php echo $template_type; ?> == 10)
           $("#carousel_template_add_button_<?php echo $template_type; ?>").hide();
    });

    var list_template_counter_<?php echo $template_type; ?> = "<?php if(isset($full_message[$template_type]['attachment']['payload']['elements'])) echo count($full_message[$template_type]['attachment']['payload']['elements']); else echo 2; ?>";
    
    $j(document.body).on('click','#list_template_add_button_<?php echo $template_type; ?>',function(e){
      e.preventDefault();

      var list_button_text = "#list_with_buttons_text_<?php echo $template_type; ?>";
      var list_button_type = "#list_with_button_type_<?php echo $template_type; ?>";

      var list_button_text_check = $(list_button_text).val();
      if(list_button_text_check == ''){
        $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Button Text')?>");
        $("#error_modal").modal();
        return;
      }

      var list_button_type_check = $(list_button_type).val();
      if(list_button_type_check == ''){
        $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Button Type')?>");
        $("#error_modal").modal();
        return;
      }else if(list_button_type_check == 'post_back'){

        var list_button_post_id = "#list_with_button_post_id_<?php echo $template_type; ?>";
        var list_button_post_id_check = $(list_button_post_id).val();
        if(list_button_post_id_check == ''){
          $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your PostBack Id')?>");
          $("#error_modal").modal();
          return;
        }
      }else if(list_button_type_check == 'web_url'){

        var list_button_web_url = "#list_with_button_web_url_<?php echo $template_type; ?>";
        var list_button_web_url_check = $(list_button_web_url).val();
        if(list_button_web_url_check == ''){
          $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Web Url')?>");
          $("#error_modal").modal();
          return;
        }
      }else if(list_button_type_check == 'phone_number'){
        var list_button_call_us = "#list_with_button_call_us_<?php echo $template_type; ?>";
        var list_button_call_us_check = $(list_button_call_us).val();
        if(list_button_call_us_check == ''){
          $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Phone Number')?>");
          $("#error_modal").modal();
          return;
        }
      }


      var prev_list_image_counter = eval(list_template_counter_<?php echo $template_type; ?>+"-1");
      var list_image_1 = "#list_image_"+prev_list_image_counter+"_"+<?php echo $template_type; ?>;
      var list_image_check_1 = $(list_image_1).val();
      if(list_image_check_1 == ''){
        $("#error_modal_content").html("<?php echo $this->lang->line('Please provide your reply image')?>");
        $("#error_modal").modal();
        return;
      }

      var list_image = "#list_image_"+list_template_counter_<?php echo $template_type; ?>+"_"+<?php echo $template_type; ?>;
      var list_image_check = $(list_image).val();
      if(list_image_check == ''){
        $("#error_modal_content").html("<?php echo $this->lang->line('Please provide your reply image')?>");
        $("#error_modal").modal();
        return;
      }

      var prev_list_title_counter = eval(list_template_counter_<?php echo $template_type; ?>+"-1");
      var list_title_1 = "#list_title_"+prev_list_title_counter+"_"+<?php echo $template_type; ?>;
      var list_title_check_1 = $(list_title_1).val();
      if(list_title_check_1 == ''){
        $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide list title')?>");
        $("#error_modal").modal();
        return;
      }

      var list_title = "#list_title_"+list_template_counter_<?php echo $template_type; ?>+"_"+<?php echo $template_type; ?>;
      var list_title_check = $(list_title).val();
      if(list_title_check == ''){
        $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide list title')?>");
        $("#error_modal").modal();
        return;
      }

      var prev_list_dest_counter = eval(list_template_counter_<?php echo $template_type; ?>+"-1");
      var list_image_destination_link_1 = "#list_image_destination_link_"+prev_list_dest_counter+"_"+<?php echo $template_type; ?>;
      var list_image_destination_link_check_1 = $(list_image_destination_link_1).val();
      if(list_image_destination_link_check_1 == ''){
        $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Image Click Destination Link')?>");
        $("#error_modal").modal();
        return;        
      }

      var list_image_destination_link = "#list_image_destination_link_"+list_template_counter_<?php echo $template_type; ?>+"_"+<?php echo $template_type; ?>;
      var list_image_destination_link_check = $(list_image_destination_link).val();
      if(list_image_destination_link_check == ''){
        $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Image Click Destination Link')?>");
        $("#error_modal").modal();
        return;        
      }

      list_template_counter_<?php echo $template_type; ?>++;
    
      var x = list_template_counter_<?php echo $template_type; ?>;
    
      $("#list_div_"+x+"_<?php echo $template_type; ?>").show();
      if( list_template_counter_<?php echo $template_type; ?> == 4)
        $("#list_template_add_button_<?php echo $template_type; ?>").hide();
    });



  <?php } ?>



  $j(document).ready(function() {

    $(document.body).on('click','.item_remove',function(){
      var counter_variable = $(this).attr('counter_variable');
      var row_id = $(this).attr('row_id');

      var first_column_id = $(this).attr('first_column_id');
      var second_column_id = $(this).attr('second_column_id');
      var add_more_button_id = $(this).attr('add_more_button_id');

      var item_remove_postback = $(this).attr('third_postback');
      var item_remove_weburl = $(this).attr('third_weburl');
      var item_remove_callus = $(this).attr('third_callus');

      $("#"+first_column_id).val('');
      $("#"+first_column_id).removeAttr('readonly');
      var item_remove_button_type = $("#"+second_column_id).val();
      $("#"+second_column_id).val('');

      if(item_remove_button_type == 'post_back')
      {
        $("#"+item_remove_postback).val('');
      }
      else if (item_remove_button_type == 'web_url')
      {
        $("#"+item_remove_weburl).val('');
      }
      else
        $("#"+item_remove_callus).val('');

      $("#"+row_id).hide();
      eval(counter_variable+"--");
      var temp = eval(counter_variable);

      if(temp != 1)
      {        
        var previous_item_remove_div = $("#"+row_id).prev('div').attr('id');
        $("#"+previous_item_remove_div+" div:last").show();
      }
      $(this).parent().hide();

      if(temp < 3) $("#"+add_more_button_id).show();

    });

    $(document.body).on('click','.delete_bot',function(){
      var id = $(this).attr('id');
      var somethingwentwrong = "<?php echo $somethingwentwrong; ?>";
      var doyoureallywanttodeletethisbot = "<?php echo $doyoureallywanttodeletethisbot; ?>";
      var ans = confirm(doyoureallywanttodeletethisbot);
      if(ans)
      {
        $.ajax({
           type:'POST' ,
           url: "<?php echo base_url('messenger_bot/delete_bot')?>",
           data: {id:id},
           success:function(response)
           {
            if(response=='1')
            location.reload();
            else alert(somethingwentwrong);
           }
        });
      }
    });



    $j(document.body).on('change','.media_type_class',function(){
      var button_type = $(this).val();
      var which_number_is_clicked = $(this).attr('id');
      which_number_is_clicked_main = which_number_is_clicked.split('_');
      which_number_is_clicked = which_number_is_clicked_main[which_number_is_clicked_main.length - 2];
      var which_block_is_clicked = which_number_is_clicked_main[which_number_is_clicked_main.length - 1];

      if(button_type == 'post_back')
      {
        $("#media_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked+" input").attr('type','text').val(""); 
        $("#media_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked).find('label').first().show();
        $("#media_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked).show();
        $("#media_web_url_div_"+which_number_is_clicked+"_"+which_block_is_clicked).hide();
        $("#media_call_us_div_"+which_number_is_clicked+"_"+which_block_is_clicked).hide();
        var option_id=$(this).children(":selected").attr("id");
        if(option_id=="unsubscribe_postback")
        {           
           $("#media_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked+" input").val("UNSUBSCRIBE_QUICK_BOXER"); 
           $("#media_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked+" input").attr('type','hidden'); 
           $("#media_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked).show();
           $("#media_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked).find('label').first().hide();
        }
        if(option_id=="resubscribe_postback")
        {
           $("#media_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked+" input").val("RESUBSCRIBE_QUICK_BOXER"); 
           $("#media_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked+" input").attr('type','hidden'); 
           $("#media_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked).show();
           $("#media_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked).find('label').first().hide();
        }
        if(option_id=="human_postback")
        {
           $("#media_post_id_"+which_number_is_clicked+"_"+which_block_is_clicked).val("YES_START_CHAT_WITH_HUMAN");
           $("#media_post_id_"+which_number_is_clicked+"_"+which_block_is_clicked).attr('type','hidden');
           $("#media_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked).show();
           $("#media_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked).find('label').first().hide();
        }
        if(option_id=="robot_postback")
        {
           $("#media_post_id_"+which_number_is_clicked+"_"+which_block_is_clicked).val("YES_START_CHAT_WITH_BOT"); 
           $("#media_post_id_"+which_number_is_clicked+"_"+which_block_is_clicked).attr('type','hidden'); 
           $("#media_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked).show();
           $("#media_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked).find('label').first().hide();
        }
      }
      else if(button_type == 'web_url')
      {
        $("#media_web_url_div_"+which_number_is_clicked+"_"+which_block_is_clicked).show();
        $("#media_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked).hide();
        $("#media_call_us_div_"+which_number_is_clicked+"_"+which_block_is_clicked).hide();
      }
      else if(button_type == 'phone_number')
      {
        $("#media_call_us_div_"+which_number_is_clicked+"_"+which_block_is_clicked).show();
        $("#media_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked).hide();
        $("#media_web_url_div_"+which_number_is_clicked+"_"+which_block_is_clicked).hide();
      }
      else
      {
        $("#media_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked).hide();
        $("#media_web_url_div_"+which_number_is_clicked+"_"+which_block_is_clicked).hide();
        $("#media_call_us_div_"+which_number_is_clicked+"_"+which_block_is_clicked).hide();
      }
    });

  



 
    $j(document.body).on('change','.quick_reply_button_type_class',function(){
      var button_type = $(this).val();
      var which_number_is_clicked = $(this).attr('id');
      var which_block_is_clicked="";
    
      which_number_is_clicked_main = which_number_is_clicked.split('_');
      which_number_is_clicked = which_number_is_clicked_main[which_number_is_clicked_main.length - 2];
      which_block_is_clicked = which_number_is_clicked_main[which_number_is_clicked_main.length - 1];

      if(button_type == 'post_back')
      {
        $("#quick_reply_button_text_"+which_number_is_clicked+"_"+which_block_is_clicked).removeAttr('readonly');
        $("#quick_reply_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked).show();
      }
      else
      {
        $("#quick_reply_button_text_"+which_number_is_clicked+"_"+which_block_is_clicked).attr('readonly','readonly');
        $("#quick_reply_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked).hide();
      }
      // alert(which_number_is_clicked);
    });


    $j(document.body).on('change','.text_with_button_type_class',function(){
      var button_type = $(this).val();
      var which_number_is_clicked = $(this).attr('id');
      which_number_is_clicked_main = which_number_is_clicked.split('_');
      which_number_is_clicked = which_number_is_clicked_main[which_number_is_clicked_main.length - 2];
      var which_block_is_clicked = which_number_is_clicked_main[which_number_is_clicked_main.length - 1];

      if(button_type == 'post_back')
      {
        $("#text_with_button_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked+" input").attr('type','text').val(""); 
        $("#text_with_button_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked).find('label').first().show();
        $("#text_with_button_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked).show();
        $("#text_with_button_web_url_div_"+which_number_is_clicked+"_"+which_block_is_clicked).hide();
        $("#text_with_button_call_us_div_"+which_number_is_clicked+"_"+which_block_is_clicked).hide();
        var option_id=$(this).children(":selected").attr("id");
        if(option_id=="unsubscribe_postback")
        {           
           $("#text_with_button_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked+" input").val("UNSUBSCRIBE_QUICK_BOXER"); 
           $("#text_with_button_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked+" input").attr('type','hidden'); 
           $("#text_with_button_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked).show();
           $("#text_with_button_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked).find('label').first().hide();
        }
        if(option_id=="resubscribe_postback")
        {
           $("#text_with_button_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked+" input").val("RESUBSCRIBE_QUICK_BOXER"); 
           $("#text_with_button_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked+" input").attr('type','hidden'); 
           $("#text_with_button_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked).show();
           $("#text_with_button_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked).find('label').first().hide();
        }
        if(option_id=="human_postback")
        {
           $("#text_with_button_post_id_"+which_number_is_clicked+"_"+which_block_is_clicked).val("YES_START_CHAT_WITH_HUMAN"); 
           $("#text_with_button_post_id_"+which_number_is_clicked+"_"+which_block_is_clicked).attr('type','hidden'); 
           $("#text_with_button_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked).show();
           $("#text_with_button_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked).find('label').first().hide();
        }
        if(option_id=="robot_postback")
        {
           $("#text_with_button_post_id_"+which_number_is_clicked+"_"+which_block_is_clicked).val("YES_START_CHAT_WITH_BOT"); 
           $("#text_with_button_post_id_"+which_number_is_clicked+"_"+which_block_is_clicked).attr('type','hidden'); 
           $("#text_with_button_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked).show();
           $("#text_with_button_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked).find('label').first().hide();
        }
      }
      else if(button_type == 'web_url')
      {
        $("#text_with_button_web_url_div_"+which_number_is_clicked+"_"+which_block_is_clicked).show();
        $("#text_with_button_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked).hide();
        $("#text_with_button_call_us_div_"+which_number_is_clicked+"_"+which_block_is_clicked).hide();
      }
      else if(button_type == 'phone_number')
      {
        $("#text_with_button_call_us_div_"+which_number_is_clicked+"_"+which_block_is_clicked).show();
        $("#text_with_button_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked).hide();
        $("#text_with_button_web_url_div_"+which_number_is_clicked+"_"+which_block_is_clicked).hide();
      }
      else
      {
        $("#text_with_button_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked).hide();
        $("#text_with_button_web_url_div_"+which_number_is_clicked+"_"+which_block_is_clicked).hide();
        $("#text_with_button_call_us_div_"+which_number_is_clicked+"_"+which_block_is_clicked).hide();
      }
      // alert(which_number_is_clicked);
    });



    $j(document.body).on('change','.generic_template_button_type_class',function(){
      var button_type = $(this).val();
      var which_number_is_clicked = $(this).attr('id');
      which_number_is_clicked_main = which_number_is_clicked.split('_');
      which_number_is_clicked = which_number_is_clicked_main[which_number_is_clicked_main.length - 2];
      which_block_is_clicked = which_number_is_clicked_main[which_number_is_clicked_main.length - 1];
    
    

      if(button_type == 'post_back')
      {
        $("#generic_template_button_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked+" input").attr('type','text').val("");
        $("#generic_template_button_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked).find('label').first().show();
        $("#generic_template_button_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked).show();
        $("#generic_template_button_web_url_div_"+which_number_is_clicked+"_"+which_block_is_clicked).hide();
        $("#generic_template_button_call_us_div_"+which_number_is_clicked+"_"+which_block_is_clicked).hide();
        var option_id=$(this).children(":selected").attr("id");
        if(option_id=="unsubscribe_postback")
        {
           $("#generic_template_button_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked+" input").val("UNSUBSCRIBE_QUICK_BOXER"); 
           $("#generic_template_button_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked+" input").attr('type','hidden'); 
           $("#generic_template_button_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked).hide(); 
           $("#generic_template_button_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked).find('label').first().hide(); 
        }
        if(option_id=="resubscribe_postback")
        {
           $("#generic_template_button_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked+" input").val("RESUBSCRIBE_QUICK_BOXER"); 
           $("#generic_template_button_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked+" input").attr('type','hidden'); 
           $("#generic_template_button_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked).hide(); 
           $("#generic_template_button_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked).find('label').first().hide(); 
        }
        if(option_id=="human_postback")
        {
           $("#text_with_button_post_id_"+which_number_is_clicked+"_"+which_block_is_clicked).val("YES_START_CHAT_WITH_HUMAN"); 
           $("#text_with_button_post_id_"+which_number_is_clicked+"_"+which_block_is_clicked).attr('type','hidden'); 
           $("#generic_template_button_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked).show();
           $("#generic_template_button_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked).find('label').first().hide();
        }
        if(option_id=="robot_postback")
        {
           $("#text_with_button_post_id_"+which_number_is_clicked+"_"+which_block_is_clicked).val("YES_START_CHAT_WITH_BOT"); 
           $("#text_with_button_post_id_"+which_number_is_clicked+"_"+which_block_is_clicked).attr('type','hidden'); 
           $("#generic_template_button_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked).show();
           $("#generic_template_button_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked).find('label').first().hide();
        }
      }
      else if(button_type == 'web_url')
      {
        $("#generic_template_button_web_url_div_"+which_number_is_clicked+"_"+which_block_is_clicked).show();
        $("#generic_template_button_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked).hide();
        $("#generic_template_button_call_us_div_"+which_number_is_clicked+"_"+which_block_is_clicked).hide();
      }
      else if(button_type == 'phone_number')
      {
        $("#generic_template_button_call_us_div_"+which_number_is_clicked+"_"+which_block_is_clicked).show();
        $("#generic_template_button_web_url_div_"+which_number_is_clicked+"_"+which_block_is_clicked).hide();
        $("#generic_template_button_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked).hide();
      }
      else
      {
        $("#generic_template_button_postid_div_"+which_number_is_clicked+"_"+which_block_is_clicked).hide();
        $("#generic_template_button_web_url_div_"+which_number_is_clicked+"_"+which_block_is_clicked).hide();
        $("#generic_template_button_call_us_div_"+which_number_is_clicked+"_"+which_block_is_clicked).hide();
      }
      // alert(which_number_is_clicked);
    });



    $j(document.body).on('change','.carousel_button_type_class',function(){
      var button_type = $(this).val();
      var which_number_is_clicked = $(this).attr('id');
      which_number_is_clicked = which_number_is_clicked.split('_');
    
      var first = which_number_is_clicked[which_number_is_clicked.length - 2];
      var second = which_number_is_clicked[which_number_is_clicked.length - 3];
    
      var block_template_third= which_number_is_clicked[which_number_is_clicked.length - 1];

      if(button_type == 'post_back')
      {
        $("#carousel_button_postid_div_"+second+"_"+first+"_"+block_template_third+" input").attr('type','text').val("");
        $("#carousel_button_postid_div_"+second+"_"+first+"_"+block_template_third).find('label').first().show();
        $("#carousel_button_postid_div_"+second+"_"+first+"_"+block_template_third).show();
        $("#carousel_button_web_url_div_"+second+"_"+first+"_"+block_template_third).hide();
        $("#carousel_button_call_us_div_"+second+"_"+first+"_"+block_template_third).hide();
        var option_id=$(this).children(":selected").attr("id");
        if(option_id=="unsubscribe_postback")
        {
           $("#carousel_button_postid_div_"+second+"_"+first+"_"+block_template_third+" input").val("UNSUBSCRIBE_QUICK_BOXER"); 
           $("#carousel_button_postid_div_"+second+"_"+first+"_"+block_template_third+" input").attr('type','hidden'); 
           $("#carousel_button_postid_div_"+second+"_"+first+"_"+block_template_third).hide();
           $("#carousel_button_postid_div_"+second+"_"+first+"_"+block_template_third).find('label').first().hide();

        }
        if(option_id=="resubscribe_postback")
        {
           $("#carousel_button_postid_div_"+second+"_"+first+"_"+block_template_third+" input").val("RESUBSCRIBE_QUICK_BOXER"); 
           $("#carousel_button_postid_div_"+second+"_"+first+"_"+block_template_third+" input").attr('type','hidden'); 
           $("#carousel_button_postid_div_"+second+"_"+first+"_"+block_template_third).hide();
           $("#carousel_button_postid_div_"+second+"_"+first+"_"+block_template_third).find('label').first().hide();

        }
        if(option_id=="human_postback")
        {
           $("#carousel_button_postid_div_"+second+"_"+first+"_"+block_template_third+" input").val('YES_START_CHAT_WITH_HUMAN');
           $("#carousel_button_postid_div_"+second+"_"+first+"_"+block_template_third+" input").attr('type','hidden');
           $("#carousel_button_postid_div_"+second+"_"+first+"_"+block_template_third).show();
           $("#carousel_button_postid_div_"+second+"_"+first+"_"+block_template_third).find('label').first().hide();
        }
        if(option_id=="robot_postback")
        {
           $("#carousel_button_postid_div_"+second+"_"+first+"_"+block_template_third+" input").val('YES_START_CHAT_WITH_BOT');
           $("#carousel_button_postid_div_"+second+"_"+first+"_"+block_template_third+" input").attr('type','hidden');
           $("#carousel_button_postid_div_"+second+"_"+first+"_"+block_template_third).show();
           $("#carousel_button_postid_div_"+second+"_"+first+"_"+block_template_third).find('label').first().hide();
        }
      }
      else if(button_type == 'web_url')
      {
        $("#carousel_button_web_url_div_"+second+"_"+first+"_"+block_template_third).show();
        $("#carousel_button_postid_div_"+second+"_"+first+"_"+block_template_third).hide();
        $("#carousel_button_call_us_div_"+second+"_"+first+"_"+block_template_third).hide();
      }
      else if(button_type == 'phone_number')
      {
        $("#carousel_button_call_us_div_"+second+"_"+first+"_"+block_template_third).show();
        $("#carousel_button_web_url_div_"+second+"_"+first+"_"+block_template_third).hide();
        $("#carousel_button_postid_div_"+second+"_"+first+"_"+block_template_third).hide();
      }
      else
      {
        $("#carousel_button_postid_div_"+second+"_"+first+"_"+block_template_third).hide();
        $("#carousel_button_web_url_div_"+second+"_"+first+"_"+block_template_third).hide();
        $("#carousel_button_call_us_div_"+second+"_"+first+"_"+block_template_third).hide();
      }
      // alert(which_number_is_clicked);
    });


    $j(document.body).on('change','.list_with_button_type_class',function(){
      var button_type = $(this).val();
      var which_number_is_clicked = $(this).attr('id');
      which_number_is_clicked_main = which_number_is_clicked.split('_');
      var which_block_is_clicked = which_number_is_clicked_main[which_number_is_clicked_main.length - 1];

      if(button_type == 'post_back')
      {
        $("#list_with_button_postid_div_"+which_block_is_clicked+" input").attr('type','text').val("");
        $("#list_with_button_postid_div_"+which_block_is_clicked).find('label').first().show();
        $("#list_with_button_postid_div_"+which_block_is_clicked).show();
        $("#list_with_button_web_url_div_"+which_block_is_clicked).hide();
        $("#list_with_button_call_us_div_"+which_block_is_clicked).hide();
        var option_id=$(this).children(":selected").attr("id");
        if(option_id=="unsubscribe_postback")
        {
           $("#list_with_button_postid_div_"+which_block_is_clicked+" input").val("UNSUBSCRIBE_QUICK_BOXER"); 
           $("#list_with_button_postid_div_"+which_block_is_clicked+" input").attr('type','hidden'); 
           $("#list_with_button_postid_div_"+which_block_is_clicked).hide();
           $("#list_with_button_postid_div_"+which_block_is_clicked).find('label').first().hide();
        }
        if(option_id=="resubscribe_postback")
        {
           $("#list_with_button_postid_div_"+which_block_is_clicked+" input").val("RESUBSCRIBE_QUICK_BOXER"); 
           $("#list_with_button_postid_div_"+which_block_is_clicked+" input").attr('type','hidden'); 
           $("#list_with_button_postid_div_"+which_block_is_clicked).hide();
           $("#list_with_button_postid_div_"+which_block_is_clicked).find('label').first().hide();
        }
        if(option_id=="human_postback")
        {
           $("#list_with_button_postid_div_"+which_block_is_clicked).val("YES_START_CHAT_WITH_HUMAN"); 
           $("#list_with_button_postid_div_"+which_block_is_clicked).attr('type','hidden'); 
           $("#list_with_button_postid_div_"+which_block_is_clicked).show();
           $("#list_with_button_postid_div_"+which_block_is_clicked).find('label').first().hide();
        }
        if(option_id=="robot_postback")
        {
           $("#list_with_button_postid_div_"+which_block_is_clicked).val("YES_START_CHAT_WITH_BOT"); 
           $("#list_with_button_postid_div_"+which_block_is_clicked).attr('type','hidden'); 
           $("#list_with_button_postid_div_"+which_block_is_clicked).show();
           $("#list_with_button_postid_div_"+which_block_is_clicked).find('label').first().hide();
        }
      }
      else if(button_type == 'web_url')
      {
        $("#list_with_button_web_url_div_"+which_block_is_clicked).show();
        $("#list_with_button_postid_div_"+which_block_is_clicked).hide();
        $("#list_with_button_call_us_div_"+which_block_is_clicked).hide();
      }
      else if(button_type == 'phone_number')
      {
        $("#list_with_button_call_us_div_"+which_block_is_clicked).show();
        $("#list_with_button_postid_div_"+which_block_is_clicked).hide();
        $("#list_with_button_web_url_div_"+which_block_is_clicked).hide();
      }
      else
      {
        $("#list_with_button_postid_div_"+which_block_is_clicked).hide();
        $("#list_with_button_web_url_div_"+which_block_is_clicked).hide();
        $("#list_with_button_call_us_div_"+which_block_is_clicked).hide();
      }
    });



    function hasDuplicates(array) {
    	var valuesSoFar = Object.create(null);
    	for (var i = 0; i < array.length; ++i) {
    		var value = array[i];
    		if (value in valuesSoFar) {
    			return true;
    		}
    		valuesSoFar[value] = true;
    	}
    	return false;
    }


    $(document.body).on('click','#submit',function(e){   
      e.preventDefault();

      // var selected_postback_array = [];
      // $(".push_postback").each(function(){
      //   if($(this).is(":visible"))
      //     selected_postback_array.push($(this).val());
      // });

      
      // if(hasDuplicates(selected_postback_array))
      // {
      //   $("#error_modal_content").html("<?php echo $this->lang->line('Please provide different postback id for each button.');?>");
      //   $("#error_modal").modal();
      //   return;
      // }

      var bot_name = $("#bot_name").val();
      var template_postback_id = $("#template_postback_id").val();

      var page_table_id = $("#page_table_id").val();
      var new_variable_name = "js_array_"+page_table_id;

      if(jQuery.inArray(template_postback_id.toUpperCase(), eval(new_variable_name)) !== -1){
        $("#error_modal_content").html("<?php echo $this->lang->line('The PostBack ID you have given is allready exist. Please provide different PostBack Id')?>");
        $("#error_modal").modal();
        return ;
      }

      var keyword_type = $("input[name=keyword_type]:checked").val();

      if(bot_name == '')
      {
        $("#error_modal_content").html("<?php echo $this->lang->line('Please Give Bot Name')?>");
        $("#error_modal").modal();
        return;
      }

      if(page_table_id == '')
      {
        $("#error_modal_content").html("<?php echo $this->lang->line('Please select a page')?>");
        $("#error_modal").modal();
        return;
      }

      if(template_postback_id == '')
      {
        $("#error_modal_content").html("<?php echo $this->lang->line('Please give a postback ID')?>");
        $("#error_modal").modal();
        return;
      }


      if(keyword_type == 'post-back')
      {

        if($("#keywordtype_postback_id").val() == '' || typeof($("#keywordtype_postback_id").val()) == 'undefined' || $("#keywordtype_postback_id").val() == null)
        {
          $("#error_modal_content").html("<?php echo $this->lang->line('Please provide postback id')?>");
          $("#error_modal").modal();
          return;
        }
      }

      if(keyword_type == 'reply')
      {
        var keywords_list = $("#keywords_list").val();
        if(keywords_list =='')
        {
          $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Keywords In Comma Separated')?>");
          $("#error_modal").modal();
          return;
        }
      }

      for(var m=1; m<=multiple_template_add_button_counter; m++)
      {
          var template_type = $("#template_type_"+m).val();

          if(template_type == 'text')
          {
            var text_reply = $("#text_reply_"+m).val();
            if(text_reply == ''){
              $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Reply Message')?>");
              $("#error_modal").modal();
              return;
            }
          }

          if(template_type == "image")
          {
            var image_reply_field =$("#image_reply_field_"+m).val();
            if(image_reply_field == ''){
              $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Reply Image')?>");
              $("#error_modal").modal();
              return;
            }
          }

          if(template_type == "audio")
          {
            var audio_reply_field = $("#audio_reply_field_"+m).val();
            if(audio_reply_field == ''){
              $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Reply Audio')?>");
              $("#error_modal").modal();
              return;
            }
          }

          if(template_type == "video")
          {
            var video_reply_field = $("#video_reply_field_"+m).val();
            if(video_reply_field == ''){
              $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Reply Video')?>");
              $("#error_modal").modal();
              return;          
            }
          }


          if(template_type == "file")
          {
            var file_reply_field = $("#file_reply_field_"+m).val();
            if(file_reply_field == ''){
              $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Reply File')?>");
              $("#error_modal").modal();
              return;          
            }
          }

          if(template_type == "quick reply")
          {
            var quick_reply_text = $("#quick_reply_text_"+m).val();
            if(quick_reply_text == ''){
              $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Reply Message')?>");
              $("#error_modal").modal();
              return;
            }
            var submited_quick_reply_button_counter = eval("quick_reply_button_counter_"+m);

            for(var n=1; n<=submited_quick_reply_button_counter; n++)
            {
              var quick_reply_button_text = "#quick_reply_button_text_"+n+"_"+m;
              var quick_reply_post_id = "#quick_reply_post_id_"+n+"_"+m;
              var quick_reply_button_type = "#quick_reply_button_type_"+n+"_"+m;

              quick_reply_button_type = $(quick_reply_button_type).val();

              var quick_reply_post_id_check = $(quick_reply_post_id).val();
              if(quick_reply_button_type == 'post_back')
              {        
                if(quick_reply_post_id_check == ''){
                  $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your PostBack Id')?>");
                  $("#error_modal").modal();
                  return;
                }

                var quick_reply_button_text_check = $(quick_reply_button_text).val();

                if(quick_reply_button_text_check == ''){
                  $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Button Text')?>");
                  $("#error_modal").modal();
                  return;
                }
                /*
                var page_table_id = $("#page_table_id").val();
                var new_variable_name = "js_array_"+page_table_id;

                if(jQuery.inArray(quick_reply_post_id_check.toUpperCase(), eval(new_variable_name)) !== -1){
                  $("#error_modal_content").html("<?php echo $this->lang->line('The PostBack ID you have given is allready exist. Please provide different PostBack Id')?>");
                  $("#error_modal").modal();
                  return ;
                }
                */

              }
              if(quick_reply_button_type == '')
              {
                $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Button Type')?>");
                $("#error_modal").modal();
                return;
              }
            }    
          }




          if(template_type == "media")
          {
            var media_input = $("#media_input_"+m).val();
            if(media_input == ''){
              $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Media URL')?>");
              $("#error_modal").modal();
              return;          
            }

            var facebook_url = media_input.match(/business.facebook.com/g);
            var facebook_url2 = media_input.match(/www.facebook.com/g);

            if(facebook_url == null && facebook_url2 == null)
            {
              $("#error_modal_content").html("<?php echo $this->lang->line('Please provide Facebook content URL as Media URL')?>");
              $("#error_modal").modal();
              return; 
            }

            var submited_media_counter = eval("media_counter_"+m);

            for(var n=1; n<=submited_media_counter; n++)
            {

              var media_text = "#media_text_"+n+"_"+m;
              var media_type = "#media_type_"+n+"_"+m;

              var media_text_check = $(media_text).val();
              if(media_text_check == ''){
                $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Button Text')?>");
                $("#error_modal").modal();
                return;
              }

              var media_type_check = $(media_type).val();
              if(media_type_check == ''){
                $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Button Type')?>");
                $("#error_modal").modal();
                return;
              }else if(media_type_check == 'post_back'){

                var media_post_id = "#media_post_id_"+n+"_"+m;
                var media_post_id_check = $(media_post_id).val();
                if(media_post_id_check == ''){
                  $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your PostBack Id')?>");
                  $("#error_modal").modal();
                  return;
                }
                /*
                var page_table_id = $("#page_table_id").val();
                var new_variable_name = "js_array_"+page_table_id;

                if(jQuery.inArray(media_post_id_check.toUpperCase(), eval(new_variable_name)) !== -1){
                  $("#error_modal_content").html("<?php echo $this->lang->line('The PostBack ID you have given is allready exist. Please provide different PostBack Id')?>");
                  $("#error_modal").modal();
                  return ;
                }
                */
              }else if(media_type_check == 'web_url'){
                var media_web_url = "#media_web_url_"+n+"_"+m;
                var media_web_url_check = $(media_web_url).val();
                if(media_web_url_check == ''){
                  $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Web Url')?>");
                  $("#error_modal").modal();
                  return;
                }
              }else if(media_type_check == 'phone_number'){
                var media_call_us = "#media_call_us_"+n+"_"+m;
                var media_call_us_check = $(media_call_us).val();
                if(media_call_us_check == ''){
                  $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Phone Number')?>");
                  $("#error_modal").modal();
                  return;
                }
              }
            }
            
          }





          if(template_type == "text with buttons")
          {
            var text_with_buttons_input = $("#text_with_buttons_input_"+m).val();
            if(text_with_buttons_input == ''){
              $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Reply Message')?>");
              $("#error_modal").modal();
              return;          
            }

            var submited_text_with_button_counter = eval("text_with_button_counter_"+m);

            for(var n=1; n<=submited_text_with_button_counter; n++)
            {

              var text_with_buttons_text = "#text_with_buttons_text_"+n+"_"+m;
              var text_with_button_type = "#text_with_button_type_"+n+"_"+m;

              var text_with_buttons_text_check = $(text_with_buttons_text).val();
              if(text_with_buttons_text_check == ''){
                $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Button Text')?>");
                $("#error_modal").modal();
                return;
              }

              var text_with_button_type_check = $(text_with_button_type).val();
              if(text_with_button_type_check == ''){
                $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Button Type')?>");
                $("#error_modal").modal();
                return;
              }else if(text_with_button_type_check == 'post_back'){

                var text_with_button_post_id = "#text_with_button_post_id_"+n+"_"+m;
                var text_with_button_post_id_check = $(text_with_button_post_id).val();
                if(text_with_button_post_id_check == ''){
                  $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your PostBack Id')?>");
                  $("#error_modal").modal();
                  return;
                }
                /*
                var page_table_id = $("#page_table_id").val();
                var new_variable_name = "js_array_"+page_table_id;

                if(jQuery.inArray(text_with_button_post_id_check.toUpperCase(), eval(new_variable_name)) !== -1){
                  $("#error_modal_content").html("<?php echo $this->lang->line('The PostBack ID you have given is allready exist. Please provide different PostBack Id')?>");
                  $("#error_modal").modal();
                  return ;
                }
                */
              }else if(text_with_button_type_check == 'web_url'){
                var text_with_button_web_url = "#text_with_button_web_url_"+n+"_"+m;
                var text_with_button_web_url_check = $(text_with_button_web_url).val();
                if(text_with_button_web_url_check == ''){
                  $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Web Url')?>");
                  $("#error_modal").modal();
                  return;
                }
              }else if(text_with_button_type_check == 'phone_number'){
                var text_with_button_call_us = "#text_with_button_call_us_"+n+"_"+m;
                var text_with_button_call_us_check = $(text_with_button_call_us).val();
                if(text_with_button_call_us_check == ''){
                  $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Phone Number')?>");
                  $("#error_modal").modal();
                  return;
                }
              }
            }
            
          }

          if(template_type == "generic template")
          {
            var generic_template_image = $("#generic_template_image_"+m).val();
            // if(generic_template_image == ''){
            //   $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Reply Image')?>");
            //   $("#error_modal").modal();
            //   return;          
            // }    

            var generic_template_title = $("#generic_template_title_"+m).val();
            if(generic_template_title == ''){
              $("#error_modal_content").html("<?php echo $this->lang->line('Please give the title')?>");
              $("#error_modal").modal();
              return;          
            }

            var generic_template_subtitle = $("#generic_template_subtitle_"+m).val();
            // if(generic_template_subtitle == ''){
            //   $("#error_modal_content").html("<?php echo $this->lang->line('Please give the sub-title')?>");
            //   $("#error_modal").modal();
            //   return;          
            // }


            var submited_generic_button_counter = eval("generic_with_button_counter_"+m);
            for(var n=1; n<=submited_generic_button_counter; n++)
            {            
              var generic_template_button_text = "#generic_template_button_text_"+n+"_"+m;
              var generic_template_button_type = "#generic_template_button_type_"+n+"_"+m;

              var generic_template_button_text_check = $(generic_template_button_text).val();
              var generic_template_button_type_check = $(generic_template_button_type).val();

              if(generic_template_button_text_check == ''  && generic_template_button_type_check!=''){
                $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Button Text')?>");
                $("#error_modal").modal();
                return;
              }

              // if(generic_template_button_type_check == ''){
              //   $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Button Type')?>");
              //   $("#error_modal").modal();
              //   return;
              // }else 

              if(generic_template_button_type_check == 'post_back'){

                var generic_template_button_post_id = "#generic_template_button_post_id_"+n+"_"+m;
                var generic_template_button_post_id_check = $(generic_template_button_post_id).val();
                if(generic_template_button_post_id_check == ''){
                  $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your PostBack Id')?>");
                  $("#error_modal").modal();
                  return;
                }
                /*
                var page_table_id = $("#page_table_id").val();
                var new_variable_name = "js_array_"+page_table_id;

                if(jQuery.inArray(generic_template_button_post_id_check.toUpperCase(), eval(new_variable_name)) !== -1){
                  $("#error_modal_content").html("<?php echo $this->lang->line('The PostBack ID you have given is allready exist. Please provide different PostBack Id')?>");
                  $("#error_modal").modal();
                  return ;
                }
                */

              }else if(generic_template_button_type_check == 'web_url'){

                var generic_template_button_web_url = "#generic_template_button_web_url_"+n+"_"+m;
                var generic_template_button_web_url_check = $(generic_template_button_web_url).val();
                if(generic_template_button_web_url_check == ''){
                  $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Web Url')?>");
                  $("#error_modal").modal();
                  return;
                }
              }else if(generic_template_button_type_check == 'phone_number'){
                var generic_template_button_call_us = "#generic_template_button_call_us_"+n+"_"+m;
                var generic_template_button_call_us_check = $(generic_template_button_call_us).val();
                if(generic_template_button_call_us_check == ''){
                  $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Phone Number')?>");
                  $("#error_modal").modal();
                  return;
                }
              }
            }
            
          }


          if(template_type == "carousel")
          {
            var submited_carousel_template_counter = eval("carousel_template_counter_"+m);
            for(var n=1; n<=submited_carousel_template_counter; n++)
            {
              var carousel_image = "#carousel_image_"+n+"_"+m;
              var carousel_image_check = $(carousel_image).val();
              // if(carousel_image_check == ''){
              //   $("#error_modal_content").html("<?php echo $this->lang->line('Please provide your reply image')?>");
              //   $("#error_modal").modal();
              //   return;
              // }

              var carousel_title = "#carousel_title_"+n+"_"+m;
              var carousel_title_check = $(carousel_title).val();
              if(carousel_title_check == ''){
                $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide carousel title')?>");
                $("#error_modal").modal();
                return;
              }

              var carousel_subtitle = "#carousel_subtitle_"+n+"_"+m;
              var carousel_subtitle_check = $(carousel_subtitle).val();
              // if(carousel_subtitle_check == ''){
              //   $("#error_modal_content").html("<?php echo $this->lang->line('Please give the sub-title')?>");
              //   $("#error_modal").modal();
              //   return;
              // }

              var carousel_image_destination_link = "#carousel_image_destination_link_"+n+"_"+m;
              var carousel_image_destination_link_check = $(carousel_image_destination_link).val();
              // if(carousel_image_destination_link_check == ''){
              //   $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Image Click Destination Link')?>");
              //   $("#error_modal").modal();
              //   return;        
              // }
            }

            <?php for($j=1; $j<=10; $j++) : ?>
              var submited_carousel_add_button_counter = eval("carousel_add_button_counter_<?php echo $j; ?>_"+m);
              for(var n=1; n<=submited_carousel_add_button_counter; n++)
              {
                var carousel_button_text = "#carousel_button_text_<?php echo $j; ?>_"+n+"_"+m;
                var carousel_button_type = "#carousel_button_type_<?php echo $j; ?>_"+n+"_"+m;

                if($(carousel_button_type).parent().parent().parent().is(":visible"))
                {
                  var carousel_button_text_check = $(carousel_button_text).val();
                  var carousel_button_type_check = $(carousel_button_type).val();

                  if(carousel_button_text_check == ''  && carousel_button_type_check!=""){
                    $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Button Text')?>");
                    $("#error_modal").modal();
                    return;
                  }

                  // if(carousel_button_type_check == ''){
                  //   $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Button Type')?>");
                  //   $("#error_modal").modal();
                  //   return;
                  // }else 

                  if(carousel_button_type_check == 'post_back'){

                    var carousel_button_post_id = "#carousel_button_post_id_<?php echo $j;?>_"+n+"_"+m;
                    var carousel_button_post_id_check = $(carousel_button_post_id).val();
                    if(carousel_button_post_id_check == ''){
                      $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your PostBack Id')?>");
                      $("#error_modal").modal();
                      return;
                    }
                    /*
                    var page_table_id = $("#page_table_id").val();
                    var new_variable_name = "js_array_"+page_table_id;

                    if(jQuery.inArray(carousel_button_post_id_check.toUpperCase(), eval(new_variable_name)) !== -1){
                      $("#error_modal_content").html("<?php echo $this->lang->line('The PostBack ID you have given is allready exist. Please provide different PostBack Id')?>");
                      $("#error_modal").modal();
                      return ;
                    }
                    */
                  }else if(carousel_button_type_check == 'web_url'){

                    var carousel_button_web_url = "#carousel_button_web_url_<?php echo $j;?>_"+n+"_"+m;
                    var carousel_button_web_url_check = $(carousel_button_web_url).val();
                    if(carousel_button_web_url_check == ''){
                      $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Web Url')?>");
                      $("#error_modal").modal();
                      return;
                    }
                  }else if(carousel_button_type_check == 'phone_number'){
                    var carousel_button_call_us = "#carousel_button_call_us_<?php echo $j;?>_"+n+"_"+m;
                    var carousel_button_call_us_check = $(carousel_button_call_us).val();
                    if(carousel_button_call_us_check == ''){
                      $("#error_modal_content").html("<?php echo $this->lang->line('Please Provide Your Phone Number')?>");
                      $("#error_modal").modal();
                      return;
                    }
                  }
                }
                
                

              }
            <?php endfor; ?>

          }


      }

      $("#submit").addClass("disabled");
      var loading = '<img src="'+base_url+'assets/pre-loader/custom_lg.gif" class="center-block">';

      $("#submit_status").removeClass('alert').removeClass('alert-success').removeClass('alert-danger').html('<img src="'+base_url+'assets/pre-loader/custom_lg.gif" class="center-block">');
      // $("#submit_status").html(loading);

      $("input:not([type=hidden])").each(function(){
        if($(this).is(":visible") == false)
          $(this).attr("disabled","disabled");
      });


      var queryString = new FormData($("#messenger_bot_form")[0]);
        $.ajax({
          type:'POST' ,
          url: base_url+"messenger_bot/edit_template_action",
          data: queryString,
          dataType : 'JSON',
          // async: false,
          cache: false,
          contentType: false,
          processData: false,
          success:function(response){
              if(response.status=="1")
              {
                $("#submit_status").addClass('alert alert-success').html(response.message);
                var link="<?php echo $redirect_url; ?>"; 
                window.location.assign(link);

              }
              else
              {
                $("#submit_status").html(response.message);
              }
          }

        });

    });

    $('[data-toggle="popover"]').popover(); 
    $('[data-toggle="popover"]').on('click', function(e) {e.preventDefault(); return true;});
  }); 
</script>



<div class="modal fade" id="error_modal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><i class="fa fa-info"></i> <?php echo $this->lang->line('campaign error'); ?></h4>
      </div>
      <div class="modal-body">
        <div class="alert text-center alert-warning" id="error_modal_content">
          
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_for_preview" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><i class="fa fa-eye"></i> <?php echo $this->lang->line('item preview'); ?></h4>
      </div>
      <div class="modal-body">
        <div id="image_preview_div_modal" style="display: none;">
          <img id="modal_preview_image" width="100%" src="">
        </div>
        <div id="video_preview_div_modal" style="display: none;">
          <video width="100%" id="modal_preview_video" controls>
            
          </video>
        </div>
        <div id="audio_preview_div_modal" style="display: none;">
          <audio width="100%" id="modal_preview_audio" controls>
            
          </audio>
        </div>
        <div>
          <input class="form-control" type="text" id="preview_text_field">
        </div>
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="media_template_modal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content modal-lg">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><i class="fa fa-info"></i> <?php echo $this->lang->line("How to get meida URL?"); ?></h4>
      </div>
      <div class="modal-body">
        <div>
          <h4>To get the Facebook URL for an image or video, do the following:</h4>
          <ol>
            <li>Click the image or video thumbnail to open the full-size view.</li>
            <li>Copy the URL from your browser's address bar.</li>
          </ol>
          <p>Facebook URLs should be in the following base format:</p>
          <table class='table table-condensed table-bordered table-hover table-striped' >
            <tr>
              <th>Media Type</th>
              <th>Media Source</th>
              <th>URL Format</th>
            </tr>
            <tr>
              <td>Video</td>
              <td>Facebook Page</td>
              <td>https://business.facebook.com/<b>PAGE_NAME</b>/videos/<b>NUMERIC_ID</b></td>
            </tr>
            <tr>
              <td>Video</td>
              <td>Facebook Account</td>
              <td>https://www.facebook.com/<b>USERNAME</b>/videos/<b>NUMERIC_ID</b>/</td>
            </tr>
            <tr>
              <td>Image</td>
              <td>Facebook Page</td>
              <td>https://business.facebook.com/<b>PAGE_NAME</b>/photos/<b>NUMERIC_ID</b></td>
            </tr>
            <tr>
              <td>Image</td>
              <td>Facebook Account</td>
              <td>https://www.facebook.com/photo.php?fbid=<b>NUMERIC_ID</b></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
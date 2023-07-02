function get_action_json(element){
	    let container = element.closest('.action_main_container').siblings('.actioncontainer');
	    let output = {};
	    if(!container.length){
	        return JSON.stringify(output);
	    }
	    output.order = get_action_execution_order(container);
	    if(!(container.find('.set_attribute_value').css('display') == 'none')){
	   // if(container.find('.set_attribute_value').is(':visible')){
	        output.set_attribute = [];
	        container.find('.set_attribute_value #selected_attributes div').each(function(){
	            if(!($(this).css('display') == 'none')){//if($(this).is(':visible')){
	                let obj = {};
	                obj.id = $(this).find('.set_attr_label').data('id');
	               // obj.name = $(this).find('.set_attr_label').text().trim();
	                obj.value = $(this).find('.set_attr_val').val();
	                obj.time_settings = $(this).find('.set_attr_val').data('time_settings');
	                obj.history_settings = $(this).find('.set_attr_val').data('history_settings');
	                if(obj.id){
	                    let formatted = format_input_from_textbox($(this).find('.set_attr_val'),obj.value);
	                    obj.value = formatted;
	                    output.set_attribute.push(obj);
	                }
	            }
	        });
	        if(!output.set_attribute.length) delete output.set_attribute;
	    }
	    if(!(container.find('.clear_attribute_value').css('display') == 'none')){
	   // if(container.find('.clear_attribute_value').is(':visible')){
	        output.clear_attribute = [];
	        container.find('.clear_attribute_value #selected_list div').each(function(){
	            let id = $(this).find('.sub_seq').data('id');
	            if($(this).is(':visible') && !output.clear_attribute.includes(id) && id){
	                output.clear_attribute.push(id);
	            }
	        })
	       // console.log(output.clear_attribute);exit;
	        if(!output.clear_attribute.length) delete output.clear_attribute;
	    }
	    if(!(container.find('.set_score').css('display') == 'none')){
	   // if(container.find('.set_score').is(':visible')){
	        let value = container.find('.set_score_val').val();
	        if(value){
	            output.score = value;
	        }
	    }
	    if(!(container.find('.open_webpage').css('display') == 'none')){
	   // if(container.find('.open_webpage').is(':visible')){
	        let link = container.find('.page_link').val();
	        url = format_input_from_textbox(container.find('.page_link'),link);
	        let width = container.find('.page_height').val();
	        let time_settings = container.find('.page_link').data('time_settings');
	        let history_settings = container.find('.page_link').data('history_settings');
	        if(url && width){
	            output.open_link = {
	                url,
	                width,
	                time_settings,
	                history_settings
	            };
	        }
	    }
	    if(!(container.find('.remove_opt').css('display') == 'none')){
	        let element = container.find('.remove_opt');
	        output.remove_opt = {};
	        let current_action = {};
	        current_action.opts = [];
	        element.find('.opts #selected_list .input-group-text').each(function(){
	            let id = $(this).find('.sub_seq').data('id');
	            current_action.opts.push(id);
	        });
	        current_action.channel = element.find('.selected_channel').data('id');
	        if(!(container.find('.remove_opt .dynchannel .dyn_sel_ct').css('display') == 'none')){
	            current_action.dynamic_channel = get_data_from_textbox(element.find('.dyn_sel_val'));
	        }
	        output.remove_opt = current_action;
	        output.remove_opt.params = [];
            container.find('.remove_opt').find('#added_url_list div.connected_attribute_container').each(function(){
                if(!($(this).css('display') == 'none')){
	                let obj = get_data_from_textbox($(this).find('.add_item_val'));
	               // if(obj.value){
	                    output.remove_opt.params.push(obj);
	               // }
	            }
            });
	        if(!output.remove_opt.opts.length) delete output.remove_opt;
	    }
	    if(!(container.find('.add_opt').css('display') == 'none')){
	        let element = container.find('.add_opt');
	        output.add_opt = {};
	        let current_action = {};
	        current_action.opts = [];
	        element.find('.opts #selected_list .input-group-text').each(function(){
	            let id = $(this).find('.sub_seq').data('id');
	            current_action.opts.push(id);
	        });
	        current_action.channel = element.find('.selected_channel').data('id');
	        if(!(container.find('.add_opt .dynchannel .dyn_sel_ct').css('display') == 'none')){
	            current_action.dynamic_channel = get_data_from_textbox(element.find('.dyn_sel_val'));
	        }
	        output.add_opt = current_action;
	        output.add_opt.params = [];
            container.find('.add_opt').find('#added_url_list div.connected_attribute_container').each(function(){
                if(!($(this).css('display') == 'none')){
	                let obj = get_data_from_textbox($(this).find('.add_item_val'));
	               // if(obj.value){
	                    output.add_opt.params.push(obj);
	               // }
	            }
            });
	        if(!output.add_opt.opts.length) delete output.add_opt;
	    }
	    if(!(container.find('.add_flow').css('display') == 'none')){
	        let element = container.find('.add_flow');
	        let current_action = {};
	        current_action.channel = element.find('.selected_channel').data('id');
            if(!(container.find('.add_flow .dynchannel .dyn_sel_ct').css('display') == 'none')){
	            current_action.dynamic_channel = get_data_from_textbox(element.find('.dynchannel .dyn_sel_val'));
	        }
	        current_action.chatbot = element.find('.selected_chatbot').data('id');
            current_action.from_add = get_data_from_textbox(element.find('.mapping_attribute')); 
            current_action.to_add = {
                value : element.find('.mapp_attr').val()
            };
            current_action.restart = element.find('.exitExisting').is(':checked');
            // console.log(current_action);
            if(
                (current_action.channel || (current_action.dynamic_channel && current_action.dynamic_channel.value)) && 
                (current_action.chatbot) &&  
                current_action.from_add.value && current_action.to_add.value
            ){
                output.add_chatbot = current_action;
            }
	    }
	    
	    if(!(container.find('.remove_flow').css('display') == 'none')){
	        let element = container.find('.remove_flow');
	        let current_action = {};
	        current_action.channel = element.find('.selected_channel').data('id');
            if(!(container.find('.remove_flow .dynchannel .dyn_sel_ct').css('display') == 'none')){
	            current_action.dynamic_channel = get_data_from_textbox(element.find('.dynchannel .dyn_sel_val'));
	        }
	       // current_action.chatbot = element.find('.selected_chatbot').data('id');
            current_action.from_add = get_data_from_textbox(element.find('.mapping_attribute')); 
            current_action.to_add = {
                value : element.find('.mapp_attr').val()
            };
            if(
                (current_action.channel || (current_action.dynamic_channel && current_action.dynamic_channel.value)) && 
                // (current_action.chatbot) &&  
                current_action.from_add.value && current_action.to_add.value
            ){
                output.remove_chatbot = current_action;
            }
	    }
	    if(!(container.find('.map_subscriber').css('display') == 'none')){
	        let element = container.find('.map_subscriber');
	        let current_action = {};
	        current_action.channel = element.find('.selected_channel').data('id');
            if(!(container.find('.map_subscriber .dynchannel .dyn_sel_ct').css('display') == 'none')){
	            current_action.dynamic_channel = get_data_from_textbox(element.find('.dynchannel .dyn_sel_val'));
	        }
	        current_action.owner = element.find('.selected_agent').data('id');
            if(!(container.find('.map_subscriber .dynagent .dyn_sel_ct').css('display') == 'none')){
	            current_action.dynamic_owner = get_data_from_textbox(element.find('.dynagent .dyn_sel_val'));
	        }
            current_action.from_add = get_data_from_textbox(element.find('.mapping_attribute')); 
            current_action.to_add = {
                value : element.find('.mapp_attr').val()
            };
            current_action.attrs = [];
            element.find('.actn_mappng_sngle_div').each(function(){
                let mpng = {
                    name : $(this).find('.ActionEditableHd').val(),
                    from : get_data_from_textbox($(this).find('.mapping_attribute_actn')),
                    to : $(this).find('.map_resp').data('id')
                };
                if(mpng.from && mpng.from.value && mpng.to){
                    current_action.attrs.push(mpng);
                }
            })
            // console.log(current_action);
            if(
                (current_action.channel || (current_action.dynamic_channel && current_action.dynamic_channel.value)) && 
                (current_action.owner || (current_action.dynamic_owner && current_action.dynamic_owner.value)) &&  
                current_action.from_add.value && current_action.to_add.value
            ){
                output.map_subscriber = current_action;
            }
	    }
	    if(!(container.find('.delete_contact').css('display') == 'none')){
	        let element = container.find('.delete_contact');
	        let current_action = {};
	        current_action.channel = element.find('.selected_channel').data('id');
            if(!(container.find('.delete_contact .dynchannel .dyn_sel_ct').css('display') == 'none')){
	            current_action.dynamic_channel = get_data_from_textbox(element.find('.dynchannel .dyn_sel_val'));
	        }
            current_action.from_add = get_data_from_textbox(element.find('.mapping_attribute')); 
            current_action.to_add = {
                value : element.find('.mapp_attr').val()
            };
            // console.log(current_action);
            if((current_action.channel || (current_action.dynamic_channel && current_action.dynamic_channel.value)) &&  current_action.from_add.value && current_action.to_add.value){
                output.delete_contact = current_action;
            }
	    }
	    if(!(container.find('.add_reminder').css('display') == 'none')){
	        let element = container.find('.add_reminder');
	        output.add_reminder = {};
	        let current_action = {};
	        current_action.agents = [];
	        element.find('.agents #selected_list .input-group-text').each(function(){
	            let id = $(this).find('.sub_seq').data('id');
	            current_action.agents.push(id);
	        });
	        if(!(container.find('.add_reminder .dyn_agn_ct').css('display') == 'none')){
	            current_action.dynamic_agent = get_data_from_textbox(element.find('.dyn_agn_val'));
	        }
	       // console.log(current_action);
	        current_action.heading = get_data_from_textbox(element.find('.reminder_title'));
	        current_action.desc = get_data_from_editable_div(element.find('.reminder_description'));
	        current_action.time = get_data_from_textbox(element.find('.reminder_time_dyn')); 
	        current_action.before = get_data_from_textbox(element.find('.remind_me_before'));
	        current_action.before_type = element.find('.reminder_duration_type').val();
	        current_action.contact = element.find('.assign_contact_note').is(':checked');
	        if(current_action.contact){
	            current_action.channel = element.find('.selected_channel').data('id');
	            if(!(container.find('.dynchannel .dyn_sel_ct').css('display') == 'none')){
    	            current_action.dynamic_channel = get_data_from_textbox(element.find('.dynchannel .dyn_sel_val'));
    	        }
	            current_action.from_add = get_data_from_textbox(element.find('.mapping_attribute')); 
	            current_action.to_add = {
	                value : element.find('.mapp_attr').val()
	            };
	        }
	        current_action.attachment = element.find('.attach_media_actn').is(':checked');
	        if(current_action.attachment){
	            current_action.audio = fetch_selected_file(element.find('.image_box'));
	        }
	        current_action.timezone = element.find('.select_timezone_reminder').val();
            if(!(element.find('.dyn_timezone .dyn_sel_ct').css('display') == 'none')){
	            current_action.dynamic_timezone = get_data_from_textbox(element.find('.dyn_timezone .dyn_sel_val'));
	        }
	        output.add_reminder = current_action;
	        if(!output.add_reminder.agents.length && (!current_action.dynamic_agent || !current_action.dynamic_agent.value)) delete output.add_reminder;
	    }
	    if(!(container.find('.add_note').css('display') == 'none')){
	        let element = container.find('.add_note');
	        output.add_note = {};
	        let current_action = {};
	        current_action.agents = [];
	        element.find('.agents #selected_list .input-group-text').each(function(){
	            let id = $(this).find('.sub_seq').data('id');
	            current_action.agents.push(id);
	        });
	        if(!(container.find('.add_note .dyn_agn_ct').css('display') == 'none')){
	            current_action.dynamic_agent = get_data_from_textbox(element.find('.dyn_agn_val'));
	        }
	       // console.log(current_action);
	        current_action.heading = get_data_from_textbox(element.find('.note_heading'));
	        current_action.desc = get_data_from_editable_div(element.find('.note_description'));
	        current_action.color = element.find('.NoteColorActive').attr('color');
	        current_action.pin = element.find('.pin_note').is(':checked');
	        current_action.contact = element.find('.assign_contact_note').is(':checked');
	        if(current_action.contact){
	            current_action.channel = element.find('.selected_channel').data('id');
	            if(!(container.find('.add_note .dynchannel .dyn_sel_ct').css('display') == 'none')){
    	            current_action.dynamic_channel = get_data_from_textbox(element.find('.dynchannel .dyn_sel_val'));
    	        }
    	       // console.log(current_action);
	            current_action.from_add = get_data_from_textbox(element.find('.mapping_attribute')); 
	            current_action.to_add = {
	                value : element.find('.mapp_attr').val()
	            };
	        }
	        output.add_note = current_action;
	        if(!output.add_note.agents.length && (!current_action.dynamic_agent || !current_action.dynamic_agent.value)) delete output.add_note;
	    }
	    if(!(container.find('.kick_off_users').css('display') == 'none')){
	        output.kick_off = {};
	        output.kick_off.agents = [];
	        container.find('.kick_off_users').find('.agents #selected_list .input-group-text').each(function(){
	            let id = $(this).find('.sub_seq').data('id');
	            output.kick_off.agents.push(id);
	        });
	        if(!(container.find('.kick_off_users .dyn_agn_ct').css('display') == 'none')){
	            output.kick_off.dynamic_agent = get_data_from_textbox(container.find('.kick_off_users').find('.dyn_agn_val'));
	        }
	        if(!output.kick_off.agents.length && (!output.kick_off.dynamic_agent || !output.kick_off.dynamic_agent.value)){
	            delete output.kick_off;
	        }
	    }
	    if(!(container.find('.kick_all_users').css('display') == 'none')){
	        let value = container.find('.enablKeykick').is(":checked");
	        if(value){
	            output.kick_all = value;
	        }
	    }
	    if(!(container.find('.assign_chat').css('display') == 'none')){
	        output.assign_chat = {};
	        let current_action = {};
	        let element = container.find('.assign_chat');
	        current_action.channel = element.find('.message_application').val();
	        if(!(container.find('.assign_chat .dynchannel .dyn_sel_ct').css('display') == 'none')){
	            current_action.dynamic_channel = get_data_from_textbox(container.find('.assign_chat .dynchannel .dyn_sel_val'));
	        }
	        current_action.logic = (element.find('.default_routing_rule').is(':checked')) ? 'default' : 'assign';
	        if(current_action.logic == 'default'){
	            current_action.send = (element.find('.send_no_response').is(":checked")) ? true : false;
	        }
	        if(element.find('.force_assign_chat').is(':checked')){
	            current_action.logic = 'force';
	            current_action.isForcefulChatAssign = true;
	        }
	        current_action.departments = [];
	        element.find('.departments #selected_list .input-group-text').each(function(){
	            let id = $(this).find('.sub_seq').data('id');
	            current_action.departments.push(id);
	        });
	        current_action.agents = [];
	        element.find('.agents #selected_list .input-group-text').each(function(){
	            let id = $(this).find('.sub_seq').data('id');
	            current_action.agents.push(id);
	        });
	        if(!(container.find('.assign_chat .dyn_agn_ct').css('display') == 'none')){
	            current_action.dynamic_agent = get_data_from_textbox(element.find('.dyn_agn_val'));
	        }
	       // console.log(current_action);
	        current_action.message = get_data_from_editable_div(element.find('.send_message_body'));
	        if(element.find('.toggle_event_feed i').hasClass('fa-toggle-on')){
	            current_action.feed = {};
	            current_action.feed.subject = get_data_from_textbox(element.find('.event_feed_subject'));
	            current_action.feed.background = element.find('.head_background').val();
	            current_action.feed.text = element.find('.head_text').val();
	            current_action.feed.body = get_data_from_editable_div(element.find('.event_body'));
	            current_action.feed.image = fetch_selected_file(container.find('.assign_chat .image_box'));
	        }
	        if(element.find('.toggle_event_button i').hasClass('fa-toggle-on')){
	            current_action.button = [];
	            element.find('.event_buttons .single_event_button').each(function(){
	                let button = {};
	                let elemen = $(this);
	                button.name = get_data_from_textbox(elemen.find('.button_name'));
	                button.background = elemen.find('.but_background').val();
	                button.border = elemen.find('.but_border').val();
	                button.text = elemen.find('.but_text').val();
	                button.url = get_data_from_textbox(elemen.find('.event_url'));
	                button.open = elemen.find('.event_open').val();
	                if(button.name) current_action.button.push(button);
	            })
	        }
	       // if(current_action.message && current_action.message.value){
	        if((current_action.departments && current_action.departments.length) || (current_action.agents && current_action.agents.length) || (current_action.dynamic_agent && current_action.dynamic_agent.value)){
	            output.assign_chat = current_action;
	        }else{
	            delete output.assign_chat;
	        }
	    }
	    if(!(container.find('.add_feed').css('display') == 'none')){
	        output.add_feed = {};
	        let current_action = {};
	        let element = container.find('.add_feed');
	        current_action.channel = element.find('.message_application').val();
	        if(!(container.find('.add_feed .dynchannel .dyn_sel_ct').css('display') == 'none')){
	            current_action.dynamic_channel = get_data_from_textbox(container.find('.add_feed .dynchannel .dyn_sel_val'));
	        }
	        current_action.feed = {};
	        current_action.feed.subject = get_data_from_textbox(element.find('.event_feed_subject'));
	       // console.log(current_action.feed.subject.value);
	        current_action.feed.background = element.find('.head_background').val();
	        current_action.feed.text = element.find('.head_text').val();
	        current_action.feed.body = get_data_from_editable_div(element.find('.event_body'));
	       // console.log(current_action.feed.body);
	       current_action.feed.image = fetch_selected_file(container.find('.add_feed .image_box'));
	        if(element.find('.toggle_event_button i').hasClass('fa-toggle-on')){
	            current_action.button = [];
	            element.find('.event_buttons .single_event_button').each(function(){
	                let button = {};
	                let elemen = $(this);
	                button.name = get_data_from_textbox(elemen.find('.button_name'));
	                button.background = elemen.find('.but_background').val();
	                button.border = elemen.find('.but_border').val();
	                button.text = elemen.find('.but_text').val();
	                button.url = get_data_from_textbox(elemen.find('.event_url'));
	                button.open = elemen.find('.event_open').val();
	                if(button.name) current_action.button.push(button);
	            });
	        }
	       // console.log(current_action.feed);
	        if(current_action.feed.subject && current_action.feed.subject.value){
	            output.add_feed = current_action;
	        }else{
	            delete output.add_feed;
	        }
	    }
	    if(!(container.find('.broad_caster').css('display') == 'none')){
	   // if(container.find('.send_message').is(':visible')){
	        output.broad_caster = {};
	       // output.broad_caster.to = {};
	        output.broad_caster.image = {};
	        output.broad_caster.code = container.find('.broad_caster .select_filter_segment').data('filtercode');
	        output.broad_caster.opts = [];
	        container.find('.broad_caster .opts #selected_list .input-group-text').each(function(){
	            let id = $(this).find('.sub_seq').data('id');
	            if(id) output.broad_caster.opts.push(id);
	        });
	        output.broad_caster.image = fetch_selected_file(container.find('.broad_caster .image_box'));
	        output.broad_caster.application = container.find('.broad_caster .message_application').val();
	        if(!(container.find('.broad_caster .dynchannel .dyn_sel_ct').css('display') == 'none')){
	            output.broad_caster.dynamic_channel = get_data_from_textbox(container.find('.broad_caster .dynchannel .dyn_sel_val'));
	        }
	        if(output.broad_caster.application == 8 || output.broad_caster.application > 100 || output.broad_caster.dynamic_channel){
	            let template_box = container.find('.broad_caster .template_box');
	            if(!(template_box.css('display') == 'none')){
	                output.broad_caster.template = {};
	                output.broad_caster.template.id = container.find('.broad_caster.send_message_cont').data('template_id');
	                output.broad_caster.template.name = template_box.find('.template_name').text();
	                output.broad_caster.template.type = container.find('.broad_caster.send_message_cont').data('type');
	                output.broad_caster.template.lang = container.find('.broad_caster .template_language .template_lang_select').val();
	                if(template_box.find('.builder_box_img').is(':visible')){
	                    output.broad_caster.template.media = template_box.find('.builder_box_img img').data("link");
	                    if(output.broad_caster.template.media){
	                        output.broad_caster.template.thumb = template_box.find('.builder_box_img img').attr('src');
	                    }
	                    if(template_box.find('.builder_box_img .template_media_url').css('display') != 'none'){
	                        let template_media_link = get_data_from_textbox(template_box.find('.builder_box_img .image_link'));
	                        if(template_media_link && template_media_link.value){
	                            output.broad_caster.template.media_link = template_media_link.value;
	                            output.broad_caster.template.media = undefined;
	                        }
	                    }
	                }
	                output.broad_caster.template.message = get_data_from_editable_div(template_box.find('.send_message_template_body'));
	                output.broad_caster.template.values = [];
	                template_box.find('.InsertDynamicDiv .fillDynamicGrp').each(function(){
	                    let obj = get_data_from_textbox($(this).find('.dynamic_value'));
	                    obj.count = $(this).find('.dynamic_value').data('count');
	                    output.broad_caster.template.values.push(obj);
	                })
	                if(template_box.find('.dynamic_value_header').val()){
	                    output.broad_caster.header_value = get_data_from_textbox(template_box.find('.dynamic_value_header'));
	                }
	                if(template_box.find('.whtsapp_dyno_btn input').length){
	                    output.broad_caster.button_value = get_data_from_textbox(template_box.find('.whtsapp_dyno_btn input'));
	                }
	            }else{
	                output.broad_caster.message = get_data_from_editable_div(container.find('.broad_caster').find('.send_message_body'));
	            }
	        }else{
	             output.broad_caster.message = get_data_from_editable_div(container.find('.broad_caster').find('.send_message_body'));
	        }
	        if(!output.broad_caster.code) delete output.broad_caster;
	    }
	    if(!(container.find('.send_message').css('display') == 'none')){
	   // if(container.find('.send_message').is(':visible')){
	        output.send_message = {};
	        output.send_message.to = [];
	        output.send_message.image = {};
	        container.find('.send_message #added_number_message div').each(function(){
	            if(!($(this).css('display') == 'none')){//if($(this).is(':visible')){
	                let obj = get_data_from_textbox($(this).find('.mobile_num_val'));
	                if(obj.value){
	                    output.send_message.to.push(obj);
	                }
	            }
	        });
	        output.send_message.opts = [];
	        container.find('.send_message .opts #selected_list .input-group-text').each(function(){
	            let id = $(this).find('.sub_seq').data('id');
	            if(id) output.send_message.opts.push(id);
	        });
	        
	        output.send_message.image = fetch_selected_file(container.find('.send_message .image_box'));
	        output.send_message.application = container.find('.send_message .message_application').val();
	        if(!(container.find('.send_message .dynchannel .dyn_sel_ct').css('display') == 'none')){
	            output.send_message.dynamic_channel = get_data_from_textbox(container.find('.send_message .dynchannel .dyn_sel_val'));
	        }
	        
	        if(output.send_message.application == 8 || output.send_message.application > 100 || output.send_message.dynamic_channel){
	            let template_box = container.find('.send_message .template_box');
	           // if(template_box.is(':visible')){
	           if(!(template_box.css('display') == 'none')){
	                output.send_message.template = {};
	                output.send_message.template.id = container.find('.send_message.send_message_cont').data('template_id');
	                output.send_message.template.name = template_box.find('.template_name').text();
	                output.send_message.template.type = container.find('.send_message.send_message_cont').data('type');
	                output.send_message.template.lang = container.find('.send_message .template_language .template_lang_select').val();
	               // if(template_box.find('.builder_box_img').is(':visible')){
	               if(!(template_box.find('.builder_box_img').css('display') == 'none')){
	                    output.send_message.template.media = template_box.find('.builder_box_img img').data("link");
	                    if(output.send_message.template.media){
	                        output.send_message.template.thumb = template_box.find('.builder_box_img img').attr('src');
	                    }
	                    if(template_box.find('.builder_box_img .template_media_url').css('display') != 'none'){
	                        let template_media_link = get_data_from_textbox(template_box.find('.builder_box_img .image_link'));
	                        if(template_media_link && template_media_link.value){
	                            output.send_message.template.media_link = template_media_link.value;
	                            output.send_message.template.media = undefined;
	                        }
	                    }
	                }
	                output.send_message.template.message = get_data_from_editable_div(template_box.find('.send_message_template_body'));
	                output.send_message.template.values = [];
	                template_box.find('.InsertDynamicDiv .fillDynamicGrp').each(function(){
	                    let obj = get_data_from_textbox($(this).find('.dynamic_value'));
	                    obj.count = $(this).find('.dynamic_value').data('count');
	                    output.send_message.template.values.push(obj);
	                });
	                if(template_box.find('.dynamic_value_header').val()){
	                    output.send_message.header_value = get_data_from_textbox(template_box.find('.dynamic_value_header'));
	                }
	                if(template_box.find('.whtsapp_dyno_btn input').length){
	                    output.send_message.button_value = get_data_from_textbox(template_box.find('.whtsapp_dyno_btn input'));
	                }
	            }else{
	                output.send_message.message = get_data_from_editable_div(container.find('.send_message').find('.send_message_body'));
	            }
	        }else{
	             output.send_message.message = get_data_from_editable_div(container.find('.send_message').find('.send_message_body'));
	        }
	       // console.log(output.send_message);
	        if(!output.send_message.to.length) delete output.send_message;
	    }
	    if(!(container.find('.send_wa_message').css('display') == 'none')){
	        output.wa_group_message = {};
	        output.wa_group_message.to = [];
	        output.wa_group_message.image = {};
	        output.wa_group_message.message = get_data_from_editable_div(container.find('.send_wa_message').find('.send_message_body'));
	        container.find('.send_wa_message #selected_list div').each(function(){
	            if(!($(this).css('display') == 'none')){//if($(this).is(':visible')){
	               let id = $(this).find('.sub_seq').data('id');
	                if(id && !output.wa_group_message.to.includes(id)){
	                    output.wa_group_message.to.push(id);
	                }
	            }
	        });
	        output.wa_group_message.image = fetch_selected_file(container.find('.send_wa_message .image_box'));
	        output.wa_group_message.channel = container.find('.send_wa_message').find('.selected_channel').data('id');
	        if(!(container.find('.send_wa_message .dynchannel .dyn_sel_ct').css('display') == 'none')){
	            output.wa_group_message.dynamic_channel = get_data_from_textbox(container.find('.send_wa_message').find('.dyn_sel_val'));
	        }
	        if(!output.wa_group_message.to.length) delete output.wa_group_message;
	    }
	    if(!(container.find('.add_user_wa').css('display') == 'none')){
	        output.wa_add_user = {};
	        output.wa_add_user.to = [];
	        output.wa_add_user.numbers = [];
	        output.wa_add_user.auto = (container.find('.add_user_wa .auto_create_group').is(':checked')) ? true : false;
	        container.find('.add_user_wa #selected_list div').each(function(){
	            if(!($(this).css('display') == 'none')){//if($(this).is(':visible')){
	               let id = $(this).find('.sub_seq').data('id');
	                if(id && !output.wa_add_user.to.includes(id)){
	                    output.wa_add_user.to.push(id);
	                }
	            }
	        });
	        container.find('.add_user_wa #added_number_message div').each(function(){
	            if(!($(this).css('display') == 'none')){//if($(this).is(':visible')){
	                let obj = get_data_from_textbox($(this).find('.mobile_num_val'));
	                if(obj.value){
	                    output.wa_add_user.numbers.push(obj);
	                }
	            }
	        });
	        output.wa_add_user.channel = container.find('.add_user_wa').find('.selected_channel').data('id');
	        if(!(container.find('.add_user_wa .dynchannel .dyn_sel_ct').css('display') == 'none')){
	            output.wa_add_user.dynamic_channel = get_data_from_textbox(container.find('.add_user_wa').find('.dyn_sel_val'));
	        }
	       // console.log(output.wa_add_user);
	        if(!output.wa_add_user.to.length || !output.wa_add_user.numbers.length) delete output.wa_add_user;
	       // if(!output.wa_add_user.numbers.length) delete output.wa_add_user;
	    }
	    if(!(container.find('.remove_user_wa').css('display') == 'none')){
	        output.wa_remove_user = {};
	        output.wa_remove_user.to = [];
	        output.wa_remove_user.numbers = [];
	        container.find('.remove_user_wa #selected_list div').each(function(){
	            if(!($(this).css('display') == 'none')){//if($(this).is(':visible')){
	               let id = $(this).find('.sub_seq').data('id');
	               //console.log(id);
	                if(id && !output.wa_remove_user.to.includes(id)){
	                    output.wa_remove_user.to.push(id);
	                }
	            }
	        });
	        container.find('.remove_user_wa #added_number_message div').each(function(){
	            if(!($(this).css('display') == 'none')){//if($(this).is(':visible')){
	                let obj = get_data_from_textbox($(this).find('.mobile_num_val'));
	                if(obj.value){
	                    output.wa_remove_user.numbers.push(obj);
	                }
	            }
	        });
	        output.wa_remove_user.channel = container.find('.remove_user_wa').find('.selected_channel').data('id');
	        if(!(container.find('.remove_user_wa .dynchannel .dyn_sel_ct').css('display') == 'none')){
	            output.wa_remove_user.dynamic_channel = get_data_from_textbox(container.find('.remove_user_wa').find('.dyn_sel_val'));
	        }
	       // console.log(output.wa_remove_user);
	        if(!output.wa_remove_user.to.length || !output.wa_remove_user.numbers.length) delete output.wa_remove_user;
	       // if(!output.wa_remove_user.numbers.length) delete output.wa_remove_user;
	    }
	    if(!(container.find('.create_wa_group').css('display') == 'none')){
	        output.wa_create_group = {};
	        output.wa_create_group.numbers = [];
	        output.wa_create_group.permission = container.find('.create_wa_group .group_permission').val();
	        output.wa_create_group.name = get_data_from_textbox(container.find('.create_wa_group .group_name'));
	        container.find('.create_wa_group #added_number_message div').each(function(){
	            if(!($(this).css('display') == 'none')){//if($(this).is(':visible')){
	                let obj = get_data_from_textbox($(this).find('.mobile_num_val'));
	                if(obj.value){
	                    output.wa_create_group.numbers.push(obj);
	                }
	            }
	        });
	        output.wa_create_group.channel = container.find('.create_wa_group').find('.selected_channel').data('id');
	        if(!(container.find('.create_wa_group .dynchannel .dyn_sel_ct').css('display') == 'none')){
	            output.wa_create_group.dynamic_channel = get_data_from_textbox(container.find('.create_wa_group').find('.dyn_sel_val'));
	        }
	       // console.log(output.wa_create_group);
	        if(!output.wa_create_group.name.value || !output.wa_create_group.numbers.length) delete output.wa_create_group;
	       // if(!output.wa_create_group.numbers.length) delete output.wa_create_group;
	    }
	    if(!(container.find('.invoke_formatter').css('display') == 'none')){
	        output.invoke_formatter = {};
	        output.invoke_formatter.to = [];
	       // output.invoke_formatter.value = get_data_from_textbox(container.find('.trigger_formatter .increment_value'));
	        container.find('.invoke_formatter #selected_list div').each(function(){
	            if(!($(this).css('display') == 'none')){//if($(this).is(':visible')){
	               let id = $(this).find('.sub_seq').data('id');
	                if(id && !output.invoke_formatter.to.includes(id)){
	                    output.invoke_formatter.to.push(id);
	                }
	            }
	        });
	       // console.log(output.invoke_formatter);
	        if(!output.invoke_formatter.to.length) delete output.invoke_formatter;
	    }
	    if(!(container.find('.trigger_formatter').css('display') == 'none')){
	        output.trigger_formatter = {};
	        output.trigger_formatter.to = [];
	        output.trigger_formatter.value = get_data_from_textbox(container.find('.trigger_formatter .increment_value'));
	        container.find('.trigger_formatter #selected_list div').each(function(){
	            if(!($(this).css('display') == 'none')){//if($(this).is(':visible')){
	               let id = $(this).find('.sub_seq').data('id');
	                if(id && !output.trigger_formatter.to.includes(id)){
	                    output.trigger_formatter.to.push(id);
	                }
	            }
	        });
	       // console.log(output.wa_add_user);
	        if(!output.trigger_formatter.to.length) delete output.trigger_formatter;
	    }
	    if(!(container.find('.reset_formatter').css('display') == 'none')){
	        output.reset_formatter = {};
	        output.reset_formatter.to = [];
	        container.find('.reset_formatter #selected_list div').each(function(){
	            if(!($(this).css('display') == 'none')){//if($(this).is(':visible')){
	               let id = $(this).find('.sub_seq').data('id');
	                if(id && !output.reset_formatter.to.includes(id)){
	                    output.reset_formatter.to.push(id);
	                }
	            }
	        });
	       // console.log(output.wa_add_user);
	        if(!output.reset_formatter.to.length) delete output.reset_formatter;
	    }
	    if(!(container.find('.subscribe_to_sequence').css('display') == 'none')){
	   // if(container.find('.subscribe_to_sequence').is(':visible')){
	        output.sequence = (output.sequence) ? output.sequence : {};
	        output.sequence.subscribe = [];
	        container.find('.subscribe_to_sequence #selected_list div').each(function(){
	            if(!($(this).css('display') == 'none')){//if($(this).is(':visible')){
	                let id = $(this).find('.sub_seq').data('id');
	                if(id && !output.sequence.subscribe.includes(id)){
	                    output.sequence.subscribe.push(id);
	                }
	            }
	        });
	        if(!output.sequence.subscribe.length) delete output.sequence.subscribe;
	    }
	    if(!(container.find('.add_catagory').css('display') == 'none')){
	   // if(container.find('.add_catagory').is(':visible')){
	        output.catagory = (output.catagory) ? output.catagory : {};
	        output.catagory.add = [];
	        container.find('.add_catagory #selected_list div').each(function(){
	            if(!($(this).css('display') == 'none')){//if(!($(this).css('display') == 'none')){//if($(this).is(':visible')){
	                let id = $(this).data('id');
	               // let letter = $(this).eq(0).find('span').text();
	               // let name = $(this).find('button').eq(1).html().replace(/\n/g, '');
	               // let exist = output.catagory.add.filter(x => x.id == id);
	                if(id && !output.catagory.add.includes(id)){
	                    output.catagory.add.push(id);
	                }
	            }
	        });
	        if(!output.catagory.add.length) delete output.catagory.add;
	    }
	    if(!(container.find('.add_tag').css('display') == 'none')){
	   // if(container.find('.add_tag').is(':visible')){
	        output.tag = (output.tag) ? output.tag : {};
	        output.tag.add = [];
	        container.find('.add_tag #selected_list div').each(function(){
	            if(!($(this).css('display') == 'none')){//if($(this).is(':visible')){
	                let id = $(this).data('id');
	                if(id && !output.tag.add.includes(id)){
	                    output.tag.add.push(id);
	                }
	            }
	        });
	        if(!output.tag.add.length) delete output.tag.add;
	    }
	    if(!(container.find('.remove_tag').css('display') == 'none')){
	   // if(container.find('.remove_tag').is(':visible')){
	        output.tag = (output.tag) ? output.tag : {};
	        output.tag.remove = [];
	        container.find('.remove_tag #selected_list div').each(function(){
	            if(!($(this).css('display') == 'none')){//if($(this).is(':visible')){
	                let id = $(this).data('id');
	                if(id && !output.tag.remove.includes(id)){
	                    output.tag.remove.push(id);
	                }
	            }
	        });
	        if(!output.tag.remove.length) delete output.tag.remove;
	        if(jQuery.isEmptyObject(output.tag)) delete output.tag;
	    }
	    if(!(container.find('.remove_catagory').css('display') == 'none')){
	   // if(container.find('.remove_catagory').is(':visible')){
	        output.catagory = (output.catagory) ? output.catagory : {};
	        output.catagory.remove = [];
	        container.find('.remove_catagory #selected_list div').each(function(){
	            if(!($(this).css('display') == 'none')){//if($(this).is(':visible')){
	                let id = $(this).data('id');
	                if(id && !output.catagory.remove.includes(id)){
	                    output.catagory.remove.push(id);
	                       // id,
	                       // name : name.trim(),
	                       // letter
	                }
	            }
	        });
	        if(!output.catagory.remove.length) delete output.catagory.remove;
	        if(jQuery.isEmptyObject(output.catagory)) delete output.catagory;
	    }
	    if(!(container.find('.unsubscribe_to_sequence').css('display') == 'none')){
	   // if(container.find('.unsubscribe_to_sequence').is(':visible')){
	        output.sequence = (output.sequence) ? output.sequence : {};
	        output.sequence.unsubscribe = [];
	        container.find('.unsubscribe_to_sequence #selected_list div').each(function(){
	            if(!($(this).css('display') == 'none')){//if($(this).is(':visible')){
	                let id = $(this).find('.sub_seq').data('id');
	                if(id && !output.sequence.unsubscribe.includes(id)){
	                    output.sequence.unsubscribe.push(id);
	                }
	            }
	        });
	        if(!output.sequence.unsubscribe.length) delete output.sequence.unsubscribe;
	        if(jQuery.isEmptyObject(output.sequence)) delete output.sequence;
	    }
	    if(!(container.find('.pause_smart_reply').css('display') == 'none')){
	   // if(container.find('.pause_smart_reply').is(':visible')){
	        let value = container.find('.pause_for').val();
	        if(value){
	            output.pause_smart_reply = value;
	        }
	    }
	    if(!(container.find('.blacklist').css('display') == 'none')){
	   // if(container.find('.blacklist').is(':visible')){
	        let value = container.find('.enablKeyblack').is(":checked");
	        if(value){
	            output.blacklist = value;
	        }
	    }
	    if(!(container.find('.stop_actions').css('display') == 'none')){
	   // if(container.find('.blacklist').is(':visible')){
	        let value = container.find('.stop_further_actions').is(":checked");
	        if(value){
	            output.stop_actions = value;
	        }
	    }
	    if(!(container.find('.whitelist').css('display') == 'none')){
	   // if(container.find('.whitelist').is(':visible')){
	        let value = container.find('.enablKeywhite').is(":checked");
	        if(value){
	            output.whitelist = value;
	        }
	    }
	    if(!(container.find('.send_zendesk').css('display') == 'none')){
	   // if(container.find('.send_zendesk').is(':visible')){
	        let value = get_data_from_editable_div(container.find('.send_zendesk').find('.zendesk_body'));
	        if(value && value.value){
	            output.zendesk_message = value;
	        }
	    }
	    if(!(container.find('.send_zapier').css('display') == 'none')){
	   // if(container.find('.send_zapier').is(':visible')){
	        output.zapier = {};
	        output.zapier.connected_zaps = [];
	        container.find('.send_zapier #selected_list div').each(function(){
	            if(!($(this).css('display') == 'none')){//if($(this).is(':visible')){
	                let id = $(this).find('.sub_seq').data('id');
	                if(id && !output.zapier.connected_zaps.includes(id)){
	                    output.zapier.connected_zaps.push(id);
	                }
	            }
	        });
	        output.zapier.message = get_data_from_editable_div(container.find('.send_zapier').find('.zapier_body'));
	        if(!output.zapier.connected_zaps.length) delete output.zapier;
	    }
	    if(!(container.find('.send_whatsmail').css('display') == 'none')){
	   // if(container.find('.send_whatsmail').is(':visible')){
	        output.whatsmail = {};
	        output.whatsmail.to = [];
	        container.find('.send_whatsmail #selected_list div').each(function(){
	            if(!($(this).css('display') == 'none')){//if($(this).is(':visible')){
	                let id = $(this).find('.sub_seq').data('id');
	                if(id){
	                    output.whatsmail.to.push({
	                       value : id
	                    });
	                }
	            }
	        });
	        output.whatsmail.subject = get_data_from_textbox(container.find('.send_whatsmail .whatsmail_subject'));
	        output.whatsmail.body = get_data_from_editable_div(container.find('.send_whatsmail').find('.whatsmail_body'));
	       // console.log(output.whatsmail); // show_whatsmail
	        if(!output.whatsmail.to.length) delete output.whatsmail;
	    }
	    if(!(container.find('.click_to_call').css('display') == 'none')){
	   // if(container.find('.click_to_call').is(':visible')){
	        let element = container.find('.click_to_call');
	        output.call = {};
	        output.call.project = element.find('select[name=select_project]').val();
	        output.call.gateway = element.find('select[name=select_gateway]').val();
            output.call.route = element.find('select[name=select_route]').val();
	        if(output.call.route == "agent"){
	            let agent_bl = element.find('#selected_agent').find('input[name=agent]');
	            output.call.agent = agent_bl.data('id');
	            if(!(container.find('.click_to_call .dyn_agn_ct').css('display') == 'none')){
    	            output.call.dynamic_agent = get_data_from_textbox(element.find('.dyn_agn_val'));
    	        }
	           // output.call.agent.name = agent_bl.data('name');
	        }else{
	            output.call.number = get_data_from_textbox(element.find('.agent_number'));
	        }
	        if(!output.call.route) delete output.call;
	       // console.log(output.call);
	    }
	    if(!(container.find('.open_ticket').css('display') == 'none')){
	   // if(container.find('.open_ticket').is(':visible')){
	        if(container.find('.open_ticket .ticket_sub').val()){
	            output.ticket = {};
	            output.ticket.subject = get_data_from_textbox(container.find('.open_ticket .ticket_sub'));
	            output.ticket.des = get_data_from_editable_div(container.find('.open_ticket').find('.ticket_des'));
	            output.ticket.priority = container.find('.open_ticket .ticket_pri').val();
	            output.ticket.due = container.find('.open_ticket .ticket_due').val();
	        }
	    }
	    if(!(container.find('.send_email').css('display') == 'none')){
	   // if(container.find('.send_email').is(':visible')){
	        output.email = {};
	        output.email.to = [];
	        container.find('.send_email #added_item_list div').each(function(){
	            if(!($(this).css('display') == 'none')){//if($(this).is(':visible')){
	                let obj = get_data_from_textbox($(this).find('.add_item_val'));
	                if(obj.value){
	                    output.email.to.push(obj);
	                }
	            }
	        })
	        output.email.opts = [];
	        container.find('.send_email .opts #selected_list .input-group-text').each(function(){
	            let id = $(this).find('.sub_seq').data('id');
	            if(id) output.email.opts.push(id);
	        });
	        output.email.channel = (container.find('.email_channel').val()) ? container.find('.email_channel').val() : 'default';
	        if(!(container.find('.send_email .dynchannel .dyn_sel_ct').css('display') == 'none')){
	            output.email.dynamic_channel = get_data_from_textbox(container.find('.send_email .dynchannel .dyn_sel_val'));
	        }
	        output.email.from_address = get_data_from_textbox(container.find('.send_email .from_address'));
	        output.email.sender_name = get_data_from_textbox(container.find('.send_email .sender_name'));
	        output.email.reply_address  = get_data_from_textbox(container.find('.send_email .reply_address')); 
	        output.email.cc_address  = get_data_from_textbox(container.find('.send_email .cc_address'));
	        output.email.bcc_address  = get_data_from_textbox(container.find('.send_email .bcc_address'));
	        output.email.pre_header  = get_data_from_textbox(container.find('.send_email .pre_header'));
	         
	        output.email.subject = get_data_from_textbox(container.find('.send_email .whatsmail_subject'));
	        output.email.body = get_data_from_editable_div(container.find('.send_email').find('.whatsmail_body'));
	        output.email.attachments = [];
	        container.find('#added_files_list .filec').each(function(){
	            let id = $(this).data('id');
	           // if(!output.email.attachments.includes(id)){
	                output.email.attachments.push(id);
	           // }
	        });
	       // console.log(output.email.attachments);
	        if(!(container.find('.send_email').find('.email_template_container').css('display') == 'none')){
	            let par = container.find('.send_email').find('.email_template_container');
	            output.email.template = {};
	            output.email.template.id = par.find('.show_select_options').data('id');
	            output.email.template.atrs = [];
	            par.find('.email_atrs_list .connected_attribute_container').each(function(){
	                let attr_id = $(this).data('id');
	                let attr_value = ($(this).find('input').length) ? get_data_from_textbox($(this).find('input')) : $(this).find('button').data('id');
	                if($(this).find('.select_opttt_email').length){
	                    attr_value = $(this).find('.select_opttt_email').data('id');
	                }
	                output.email.template.atrs.push({
	                    id : attr_id,
	                    value : attr_value
	                });
	               // console.log($(this).data('id'));
	            })
	        }
	        output.email.email_type = container.find('.send_email').find('.email_type').val();
	        if(!(container.find('.send_email').find('.insert_mdia_url_cont').css('display') == 'none')){
	            output.email.custom_url = [];
	            container.find('.send_email').find('#added_url_list div').each(function(){
	                if(!($(this).css('display') == 'none')){
    	                let obj = get_data_from_textbox($(this).find('.add_item_val'));
    	                if(obj.value){
    	                    output.email.custom_url.push(obj);
    	                }
    	            }
	            });
	        }
	        output.email.seperate_email = container.find('.send_email .seperate_email').is(':checked');
	       // console.log(output.email);
	        if(!output.email.to.length && !output.email.body.value && (!output.email.template || !output.email.template.id)) delete output.email;
	       // console.log(output.email);
	    }
	    if(!(container.find('.trigger_webhook').css('display') == 'none')){
	   // if(container.find('.trigger_webhook').is(':visible')){
	        let value = container.find('#enable_webhook').is(":checked");
	        if(value){
	            output.webhook = value;
	        }
	    }
	    if(!(container.find('.call_connector').css('display') == 'none')){
	   // if(container.find('.trigger_webhook').is(':visible')){
	        let cont = container.find('.call_connector');
	       // let call_connectors = [];
	       // cont.find('#selected_list').find('.sub_seq').each(function(){
	       //     let id = $(this).data('id');
	       //     if(!call_connectors.includes(id)){
	       //        call_connectors.push(id);
	       //     }
	       // });
	       let connector_value = cont.find('.selected_connector').data('id');
	        if(connector_value){
	            output.call_connector = connector_value;
	        }
	    }
	    if(!(container.find('.change_owner').css('display') == 'none')){
	        let cont = container.find('.change_owner');
	        let owner = cont.find('.selected_connector').data('id');
	        if(owner || (owner == 0 )){
	            output.change_owner = owner;
	        }
	        if(!(container.find('.change_owner .dyn_agn_ct').css('display') == 'none')){
	            output.change_owner = get_data_from_textbox(cont.find('.dyn_agn_val'));
	        }
	        if(!owner && (output.change_owner && !output.change_owner.value)){
	            delete output.change_owner;
	        }
	    }
	    if(!(container.find('.http_request').css('display') == 'none')){
	        let value = container.find('.http_req_json').val();
	        if(value){
	            output.http_request = value;
	        }
	    }
	    
	    if(!(container.find('.cancel_delay').css('display') == 'none')){
	        let cont = container.find('.cancel_delay');
	        let delay = {
	            connector : [],
	            identifier : [],
	            execution : cont.find('.execution').val()
	        };
	        cont.find('#selected_list').find('.sub_seq').each(function(){
	            let id = $(this).data('id');
	            if(!delay.connector.includes(id)){
	               delay.connector.push(id);
	            }
	        });
	        cont.find('.identifier').find('.add_item_val').each(function(){
	            let value = get_data_from_textbox($(this));
	            if(value && value.value){
	               delay.identifier.push(value);
	            }
	        });
	        if(delay && delay.connector.length && delay.identifier.length){
	            output.cancel_delay = delay;
	           // console.log(delay);
	        }
	    }
	    output.storage = {};
	    
	    if(!(container.find('.create_picky_storage').css('display') == 'none')){
	        let cntr = container.find('.create_picky_storage');
	        let name = get_data_from_textbox(cntr.find('.row_name'));
	        let value = get_data_from_textbox(cntr.find('.row_value'));
	        if(name && name.value && value){
	            output.storage.create = {
	                name,
	                value
	            }
	        };
	    }
	    
	    if(!(container.find('.delete_picky_storage').css('display') == 'none')){
	        let cntr = container.find('.delete_picky_storage');
	        let id = [];
	        cntr.find('#selected_list').find('.show_hover_container').each(function(){
	            let d = $(this).find('.sub_seq').data('id');
	            if(d && !id.includes(d)){
	                id.push(d);
	            }
	        });
	        if(id && id.length){
	            output.storage.delete = id;
	        }
	    }
	    
	    if(!(container.find('.delete_storage_data').css('display') == 'none')){
	        let cntr = container.find('.delete_storage_data');
	        let id = [];
	        cntr.find('#selected_list').find('.show_hover_container').each(function(){
	            let d = $(this).find('.sub_seq').data('id');
	            if(d && !id.includes(d)){
	                id.push(d);
	            }
	        });
	        if(id && id.length){
	            output.storage.empty = id;
	        }
	    }
	    
	    if(!(container.find('.update_storage_data').css('display') == 'none')){
	        let cntr = container.find('.update_storage_data');
	        let id = [];
	        cntr.find('#selected_list').find('.show_hover_container').each(function(){
	            let d = $(this).find('.sub_seq').data('id');
	            if(d && !id.includes(d)){
	                id.push(d);
	            }
	        });
	        let value = get_data_from_textbox(cntr.find('.row_value'));
	        if(id && id.length && value){
	            output.storage.update = {
	                id,
	                value
	            }
	        }
	    }
	    if(jQuery.isEmptyObject(output.storage)){
	        delete output.storage;
	    }
	    return JSON.stringify(output);
	}
	
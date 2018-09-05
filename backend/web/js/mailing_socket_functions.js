$(document).ready(function(){
    startSendingCore();
    
    function startSendingCore(){
        var button_id = 'start-sending-btn';
        var select_id = 'release-to-send-select';
        var console_table_id = 'mail-send-console';
        
        $('#'+button_id).on('click', function(){
            var button = this;
            if(!isButonClicked(button)){
                var release_id = $('#'+select_id).val();
                if(release_id){
                    disableButton(button);
                    startSocket(release_id);
                }else{
                    alert('Релиз не выбран!');
                }
            }
        });
        
        function enableButton(button){
            $(button).data('is_clicked', false);
            button.disabled = false;
        }
        
        function disableButton(button){
            $(button).data('is_clicked', true);
            button.disabled = true;
        }
        
        function isButonClicked(button){
            return $(button).data('is_clicked');
        }
        
        function startSocket(release_id){
            socket = new WebSocket('ws://localhost:8080');
            socket.onopen = function(e) {
                socket.send('{"action" : "get_emails_for_sending", "release_id" : ' + release_id + '}');
            };
            socket.onmessage = function(e) {
                var data = JSON.parse(e.data);
                if(data.result === "success"){
                    switch(data.action){
                        case 'get_emails_for_sending':
                            if(data.mails_list.length > 0){
                                data.action = 'send_one_email';
                                clearConsole();
                                $('#mail-send-console-wrap').slideDown(); //Show console zone
                                $('#send-mail-progress-zone').slideDown(); //Show progressbar zone
                            }else{
                                data.action = 'sending_finished';
                            }
                            socket.send(JSON.stringify(data));
                            break;
                        case 'send_one_email':
                            if(data.send_log){
                                appendInConsole(data.send_log);
                                pushProgressBar(data.tolal_mails_already_procces, data.tolal_mails_in_release);
                                data.send_log = null;
                            }
                            if(data.mails_list.length > 0){
                                data.action = 'send_one_email';
                                socket.send(JSON.stringify(data));
                            }else{
                                data.action = 'sending_finished';
                                finishSendingAction(data);
                            }
                            break;
                        case 'sending_finished':
                            finishSendingAction(data);
                            break;
                        case '':
                            break;
                        default:
                        break;
                    }
                }
                if(data.result === "error"){
                    alert(data.err_message);
                }
            };
        }
        
        function finishSendingAction(data){
            var button = $('#'+button_id).get()[0];
            enableButton(button);
            $('#send-mail-progressbar>.progress-bar').text('Готово');
        }
        
        function appendInConsole(log){
            var console_table = $('#'+console_table_id).get()[0];
            var console_table_body = $(console_table).find('tbody')[0];
            var new_row =   '<tr class="info-row">' +
                                '<td class="col-xs-8 col-sm-8 col-md-8 col-lg-10">'+log.email+'</td>' +
                                '<td class="col-xs-4 col-sm-4 col-md-4 col-lg-2"><span class="result-'+log.result+'">'+log.result_message+'</span></td>' +
                            '</tr>';
            $(console_table).find('tbody').append(new_row);
            console_table_body.scrollTop = console_table.scrollHeight;   //scroll console to bottom
        }
        
        function clearConsole(){
            $('#mail-send-console .info-row').remove();
        }
        
        function pushProgressBar(current, all){
            var percents = (current/all)*100;
            $('#send-mail-progressbar>.progress-bar').css({"width": percents + "%"}).text(current + '/' + all);
        }
        
    }
});
$(document).ready(function(){
    releasesGridBulkActions();
    testMailStart();

    function releasesGridBulkActions(){
        $('body').on('click', '#kill-changed-receivers', function(event){
            event.preventDefault();
            var agree = confirm('Точно удалить?');
            if(agree){
                makeBulkAction('delete');
            }else{
                return false;
            }
        });
        $('body').on('click', '.receivers-bulk-button', function(event){
            event.preventDefault();
            var action = $(this).data('action');
            makeBulkAction('set-status-' + action);
        });
    }
    
    function makeBulkAction(action){
        var grid = $('#release-receivers-grid')[0];
        var keys = $(grid).yiiGridView('getSelectedRows');
        var table_rows = $(grid).find('tr');
        var ids = [];
        table_rows.each(function(index, node){
            var row_key = $(node).data('key');
            if(keys.indexOf(row_key) !== -1){
                var id_td_note = $(node).find('.receiver-id-link')[0];
                var receiver_id = parseInt($(id_td_note).text());
                if(Number.isInteger(receiver_id) && receiver_id > 0){
                    ids.push(receiver_id);
                }
            }
        });
        if(ids.length > 0){
            $.ajax({
                url : window.location.origin + '/admin/releases/bulk-action',
                type : 'POST',
                dataType : 'json',
                data : {
                    ids : ids,
                    action : action
                },
                success : function(response){
                    if(response.result === 'ok'){
                        $.pjax.reload({container:'#kv-pjax-container-release'});
                    }
                    if(response.result === 'error'){
                        alert(response.message);
                    }
                },
                error : function(){
                    alert('Произошла ошибка в ajax запросе!');
                }
            });
        }
    }
    
    function testMailStart(){
        var test_button = $('#test-mailing-submit').get()[0];
        var test_email_input = $('#test-mailing-email').get()[0];
        $(test_button).on('click', function(event){
            event.preventDefault();
            //vars
            var release_id = $(test_email_input).data('release-id');
            var email = $(test_email_input).val();
            //validate
            if(!release_id){
                alert('Ошибка при получении релиза!');
                return false;
            }
            if(!email){
                alert('Ошибка при получении email для отправки!');
                return false;
            }
            //start ajax
            $.ajax({
                url : window.location.origin + '/admin/releases/send-test-email',
                type : 'POST',
                dataType : 'json',
                data : {
                    release_id : release_id,
                    email : email
                },
                beforeSend : function(){
                    //hide messages
                    $('#test-mail-success-message').slideUp(200);
                    $('#test-mail-error-message').slideUp(200);
                    //show spiner
                    $('#preloader-spiner').css({"display":"inline-block"});
                },
                success : function(response){
                    if(response.result === true){
                        $('#test-mail-success-message').slideDown();
                    }else{
                        $('#test-mail-error-message').slideDown();
                    }
                },
                error : function(){
                    alert('Произошла ошибка в ajax запросе!');
                },
                complete : function(){
                    $('#preloader-spiner').css({"display":"none"});
                }
            });
            return false;
        });
        $('#test-mail-success-message .close').on('click', function(){
            $('#test-mail-success-message').fadeOut();
        });
        $('#test-mail-error-message .close').on('click', function(){
            $('#test-mail-error-message').fadeOut();
        });
    }
});

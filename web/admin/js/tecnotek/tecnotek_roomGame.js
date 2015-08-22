var Tecnotek = Tecnotek || {};

Tecnotek.RoomGame = {
    List: {
        operateFormatter: function(value, row, index){
            return [
                '<a class="edit" href="javascript:void(0)" title="Editar">',
                '<i class="glyphicon glyphicon-edit"></i>',
                '</a>',
                '<a class="delete" href="javascript:void(0)" title="Eliminar">',
                '<i class="glyphicon glyphicon-remove"></i>',
                '</a>'
            ].join('');
        },
        operateEvents: {
            'click .like': function (e, value, row, index) {
                alert('You click like action, row: ' + JSON.stringify(row));
            },
            'click .delete': function (e, value, row, index) {
                if(Tecnotek.showConfirmationQuestion(Tecnotek.UI.translates['confirm-delete'])){
                    Tecnotek.ajaxCall(Tecnotek.UI.urls['delete-roomGame'], {
                            id:     row.id
                        },
                        function(data){
                            if(data.error){
                                Tecnotek.showErrorMessage(data.msg);
                            } else {
                                $("#roomGame-list").bootstrapTable('refresh');
                                Tecnotek.showInfoMessage(data.msg);
                            }
                        }, function(){
                        }, true);
                }
            },
            'click .edit': function(e, value, row, index) {
                $("#panel-roomGame").addClass("hidden");
                Tecnotek.UI.vars["roomGame_id"] = row.id;
                $('#form-roomGame').data('bootstrapValidator').resetForm(true);
                $("#name").val(row.name);
                $("#panel-roomGame-title").html(Tecnotek.UI.translates["edit-roomGame"]);
                $("#panel-roomGame").removeClass("hidden");
            }
        },
        init: function(){
            $("#btn-new").click(function(e){
                e.preventDefault();
                e.stopPropagation();
                $("#name").val("");
                Tecnotek.UI.vars["roomGame_id"] = 0;
                $("#panel-roomGame-title").html($(this).attr("title"));
                $('#form-roomGame').data('bootstrapValidator').resetForm(true);
                $("#panel-roomGame").removeClass("hidden");
            });

            $("#btn-cancel").click(function(e){
                e.preventDefault();
                e.stopPropagation();
                $("#panel-roomGame").addClass("hidden");
                $('#form-roomGame').data('bootstrapValidator').resetForm(true);
            });

            $('#form-roomGame').bootstrapValidator({
                excluded: ':disabled',
                message: Tecnotek.UI.translates['invalid.value'],
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                onSuccess: function(e){
                    e.preventDefault();
                    e.stopPropagation();
                    var $name = $("#name").val();
                    Tecnotek.ajaxCall(Tecnotek.UI.urls['save-roomGame'], {
                            id:     Tecnotek.UI.vars["roomGame_id"],
                            name:   $name
                        },
                        function(data){
                            if(data.error){
                                Tecnotek.showErrorMessage(data.msg);
                            } else {
                                $("#panel-roomGame").addClass("hidden");
                                $('#form-roomGame').data('bootstrapValidator').resetForm(true);
                                $("#roomGame-list").bootstrapTable('refresh');
                                Tecnotek.showInfoMessage(data.msg);
                            }
                        }, function(){
                        }, true);
                    return false;
                },
                fields: {
                    'name': {
                        validators: {
                            notEmpty: {
                                message: Tecnotek.UI.translates['field.not.empty']
                            }
                        }
                    }
                }
            });
        }
    }
};
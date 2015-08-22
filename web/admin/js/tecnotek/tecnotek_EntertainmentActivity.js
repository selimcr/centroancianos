var Tecnotek = Tecnotek || {};

Tecnotek.EntertaimentActivity = {
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
                    Tecnotek.ajaxCall(Tecnotek.UI.urls['delete-entertaimentActivity'], {
                            id:     row.id
                        },
                        function(data){
                            if(data.error){
                                Tecnotek.showErrorMessage(data.msg);
                            } else {
                                $("#entertaimentActivity-list").bootstrapTable('refresh');
                                Tecnotek.showInfoMessage(data.msg);
                            }
                        }, function(){
                        }, true);
                }
            },
            'click .edit': function(e, value, row, index) {
                $("#panel-entertaimentActivity").addClass("hidden");
                Tecnotek.UI.vars["entertaimentActivity_id"] = row.id;
                $('#form-entertaimentActivity').data('bootstrapValidator').resetForm(true);
                $("#name").val(row.name);
                $("#panel-entertaimentActivity-title").html(Tecnotek.UI.translates["edit-entertaimentActivity"]);
                $("#panel-entertaimentActivity").removeClass("hidden");
            }
        },
        init: function(){
            $("#btn-new").click(function(e){
                e.preventDefault();
                e.stopPropagation();
                $("#name").val("");
                Tecnotek.UI.vars["entertaimentActivity_id"] = 0;
                $("#panel-entertaimentActivity-title").html($(this).attr("title"));
                $('#form-entertaimentActivity').data('bootstrapValidator').resetForm(true);
                $("#panel-entertaimentActivity").removeClass("hidden");
            });

            $("#btn-cancel").click(function(e){
                e.preventDefault();
                e.stopPropagation();
                $("#panel-entertaimentActivity").addClass("hidden");
                $('#form-entertaimentActivity').data('bootstrapValidator').resetForm(true);
            });

            $('#form-entertaimentActivity').bootstrapValidator({
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
                    Tecnotek.ajaxCall(Tecnotek.UI.urls['save-entertaimentActivity'], {
                            id:     Tecnotek.UI.vars["entertaimentActivity_id"],
                            name:   $name
                        },
                        function(data){
                            if(data.error){
                                Tecnotek.showErrorMessage(data.msg);
                            } else {
                                $("#panel-entertaimentActivity").addClass("hidden");
                                $('#form-entertaimentActivity').data('bootstrapValidator').resetForm(true);
                                $("#entertaimentActivity-list").bootstrapTable('refresh');
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
var Tecnotek = Tecnotek || {};

Tecnotek.Disease = {
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
                    Tecnotek.ajaxCall(Tecnotek.UI.urls['delete-disease'], {
                            id:     row.id
                        },
                        function(data){
                            if(data.error){
                                Tecnotek.showErrorMessage(data.msg);
                            } else {
                                $("#disease-list").bootstrapTable('refresh');
                                Tecnotek.showInfoMessage(data.msg);
                            }
                        }, function(){
                        }, true);
                }
            },
            'click .edit': function(e, value, row, index) {
                $("#panel-disease").addClass("hidden");
                Tecnotek.UI.vars["disease_id"] = row.id;
                $('#form-disease').data('bootstrapValidator').resetForm(true);
                $("#name").val(row.name);
                $("#panel-disease-title").html(Tecnotek.UI.translates["edit-disease"]);
                $("#panel-disease").removeClass("hidden");
            }
        },
        init: function(){
            $("#btn-new").click(function(e){
                e.preventDefault();
                e.stopPropagation();
                $("#name").val("");
                Tecnotek.UI.vars["disease_id"] = 0;
                $("#panel-disease-title").html($(this).attr("title"));
                $('#form-disease').data('bootstrapValidator').resetForm(true);
                $("#panel-disease").removeClass("hidden");
            });

            $("#btn-cancel").click(function(e){
                e.preventDefault();
                e.stopPropagation();
                $("#panel-disease").addClass("hidden");
                $('#form-disease').data('bootstrapValidator').resetForm(true);
            });

            $('#form-disease').bootstrapValidator({
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
                    Tecnotek.ajaxCall(Tecnotek.UI.urls['save-disease'], {
                            id:     Tecnotek.UI.vars["disease_id"],
                            name:   $name
                        },
                        function(data){
                            if(data.error){
                                Tecnotek.showErrorMessage(data.msg);
                            } else {
                                $("#panel-disease").addClass("hidden");
                                $('#form-disease').data('bootstrapValidator').resetForm(true);
                                $("#disease-list").bootstrapTable('refresh');
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
var Tecnotek = Tecnotek || {};

Tecnotek.Writing = {
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
                    Tecnotek.ajaxCall(Tecnotek.UI.urls['delete-writing'], {
                            id:     row.id
                        },
                        function(data){
                            if(data.error){
                                Tecnotek.showErrorMessage(data.msg);
                            } else {
                                $("#writing-list").bootstrapTable('refresh');
                                Tecnotek.showInfoMessage(data.msg);
                            }
                        }, function(){
                        }, true);
                }
            },
            'click .edit': function(e, value, row, index) {
                $("#panel-writing").addClass("hidden");
                Tecnotek.UI.vars["writing_id"] = row.id;
                $('#form-writing').data('bootstrapValidator').resetForm(true);
                $("#name").val(row.name);
                $("#panel-writing-title").html(Tecnotek.UI.translates["edit-writing"]);
                $("#panel-writing").removeClass("hidden");
            }
        },
        init: function(){
            $("#btn-new").click(function(e){
                e.preventDefault();
                e.stopPropagation();
                $("#name").val("");
                Tecnotek.UI.vars["writing_id"] = 0;
                $("#panel-writing-title").html($(this).attr("title"));
                $('#form-writing').data('bootstrapValidator').resetForm(true);
                $("#panel-writing").removeClass("hidden");
            });

            $("#btn-cancel").click(function(e){
                e.preventDefault();
                e.stopPropagation();
                $("#panel-writing").addClass("hidden");
                $('#form-writing').data('bootstrapValidator').resetForm(true);
            });

            $('#form-writing').bootstrapValidator({
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
                    Tecnotek.ajaxCall(Tecnotek.UI.urls['save-writing'], {
                            id:     Tecnotek.UI.vars["writing_id"],
                            name:   $name
                        },
                        function(data){
                            if(data.error){
                                Tecnotek.showErrorMessage(data.msg);
                            } else {
                                $("#panel-writing").addClass("hidden");
                                $('#form-writing').data('bootstrapValidator').resetForm(true);
                                $("#writing-list").bootstrapTable('refresh');
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
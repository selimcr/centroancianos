var Tecnotek = Tecnotek || {};

Tecnotek.SleepHabit = {
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
                    Tecnotek.ajaxCall(Tecnotek.UI.urls['delete-sleepHabit'], {
                            id:     row.id
                        },
                        function(data){
                            if(data.error){
                                Tecnotek.showErrorMessage(data.msg);
                            } else {
                                $("#sleepHabit-list").bootstrapTable('refresh');
                                Tecnotek.showInfoMessage(data.msg);
                            }
                        }, function(){
                        }, true);
                }
            },
            'click .edit': function(e, value, row, index) {
                $("#panel-sleepHabit").addClass("hidden");
                Tecnotek.UI.vars["sleepHabit_id"] = row.id;
                $('#form-sleepHabit').data('bootstrapValidator').resetForm(true);
                $("#name").val(row.name);
                $("#panel-sleepHabit-title").html(Tecnotek.UI.translates["edit-sleepHabit"]);
                $("#panel-sleepHabit").removeClass("hidden");
            }
        },
        init: function(){
            $("#btn-new").click(function(e){
                e.preventDefault();
                e.stopPropagation();
                $("#name").val("");
                Tecnotek.UI.vars["sleepHabit_id"] = 0;
                $("#panel-sleepHabit-title").html($(this).attr("title"));
                $('#form-sleepHabit').data('bootstrapValidator').resetForm(true);
                $("#panel-sleepHabit").removeClass("hidden");
            });

            $("#btn-cancel").click(function(e){
                e.preventDefault();
                e.stopPropagation();
                $("#panel-sleepHabit").addClass("hidden");
                $('#form-sleepHabit').data('bootstrapValidator').resetForm(true);
            });

            $('#form-sleepHabit').bootstrapValidator({
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
                    Tecnotek.ajaxCall(Tecnotek.UI.urls['save-sleepHabit'], {
                            id:     Tecnotek.UI.vars["sleepHabit_id"],
                            name:   $name
                        },
                        function(data){
                            if(data.error){
                                Tecnotek.showErrorMessage(data.msg);
                            } else {
                                $("#panel-sleepHabit").addClass("hidden");
                                $('#form-sleepHabit').data('bootstrapValidator').resetForm(true);
                                $("#sleepHabit-list").bootstrapTable('refresh');
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
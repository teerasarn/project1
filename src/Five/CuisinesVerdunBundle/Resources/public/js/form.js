          $(document).ready( function() {
            $("#f-mg-submit").click(function(){
              var formEl = $("#general-message");
              //formEl.find(".form-loader").show();
              //formEl.find(".form-error").hide().empty();
              //formEl.find(".form-group").removeClass('has-error');

              $.post('/message_submit', $("#general-message").serialize(), function(response){
                  //formEl.find(".form-loader").hide();
                  console.log(response);
                  if (response.success == true) {
                      //formEl.remove();

                        alert("blaap");
                      $("#form-confirm").show();
                  } else {
                      if (response.error == 'fields_required') {
                          //formEl.find(".form-error").text("Veuillez remplir tous les champs obligatoires").show();

                        alert("bliip");
                           if (response.errors !== undefined) {
                              for (var i = 0; i < response.errors.length ; i++ ) {
                                  var fieldName = response.errors[i];
                                  $("#general-message").find('input[name=' + fieldName +']').parents('.form-group').addClass('has-error');
                              }
                            }
                      } else {
                        alert("bloop");
                          //formEl.find(".form-error").text("Une erreur s'est produite. Veuillez réessayer.").show();
                      }
                  }
              });
            });
            $("#f-sc-submit").click(function(){
              var formEl = $("#soumission-cuisine");
              //formEl.find(".form-loader").show();
              //formEl.find(".form-error").hide().empty();
              //formEl.find(".form-group").removeClass('has-error');

              $.post('/cuisine_submit', $("#soumission-cuisine").serialize(), function(response){
                  //formEl.find(".form-loader").hide();

                  if (response.success == true) {
                      //formEl.remove();
                      $("#form-confirm").show();
                  } else {
                      if (response.error == 'fields_required') {
                          //formEl.find(".form-error").text("Veuillez remplir tous les champs obligatoires").show();

                           if (response.errors !== undefined) {
                              for (var i = 0; i < response.errors.length ; i++ ) {
                                  var fieldName = response.errors[i];
                                  $("#soumission-cuisine").find('input[name=' + fieldName +']').parents('.form-group').addClass('has-error');
                              }
                            }
                      } else {
                          //formEl.find(".form-error").text("Une erreur s'est produite. Veuillez réessayer.").show();
                      }
                  }
              });
            });
            $("#f-sb-submit").click(function(){
              var formEl = $("#soumission-bain");
              //formEl.find(".form-loader").show();
              //formEl.find(".form-error").hide().empty();
              //formEl.find(".form-group").removeClass('has-error');

              $.post('/bain_submit', $("#soumission-bain").serialize(), function(response){
                  //formEl.find(".form-loader").hide();

                  if (response.success == true) {
                      //formEl.remove();
                      $("#form-confirm").show();
                  } else {
                      if (response.error == 'fields_required') {
                          //formEl.find(".form-error").text("Veuillez remplir tous les champs obligatoires").show();

                           if (response.errors !== undefined) {
                              for (var i = 0; i < response.errors.length ; i++ ) {
                                  var fieldName = response.errors[i];
                                  $("#soumission-bain").find('input[name=' + fieldName +']').parents('.form-group').addClass('has-error');
                              }
                            }
                      } else {
                          //formEl.find(".form-error").text("Une erreur s'est produite. Veuillez réessayer.").show();
                      }
                  }
              });
            });
          });
